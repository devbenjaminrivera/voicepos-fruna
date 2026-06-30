<?php

namespace App\Http\Controllers;

use App\Models\Boleta;
use App\Models\Producto;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BoletaController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Boleta::with(['empresa', 'productos'])
                       ->where('vendedor_id', Auth::id());

        // Búsqueda por ID o Empresa
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                if (is_numeric($search)) {
                    $q->where('id', $search);
                } else {
                    $q->whereHas('empresa', function($q2) use ($search) {
                        $q2->where('nombre', 'like', "%{$search}%");
                    });
                }
            });
        }

        // Filtro Hoy
        if ($request->input('filter') === 'hoy') {
            $query->whereDate('created_at', \Carbon\Carbon::today());
        }

        $boletas = $query->orderBy('id', 'desc')->get();

        return view('boletas.index', compact('boletas'));
    }

    
    public function create()
    {
        $empresa = \App\Models\Empresa::first();
        $productos = \App\Models\Producto::all();
        return view('boletas.create', compact('empresa', 'productos'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'texto_dictado' => 'required|string',
            'empresa_id' => 'required|exists:empresas,id'
        ]);

        $resultado = $this->procesarTextoAPedido($request->texto_dictado);

        try {
            DB::beginTransaction();

            $boleta = Boleta::create([
                'empresa_id' => $request->empresa_id,
                'vendedor_id' => Auth::id(),
                'total' => $resultado['total']
            ]);

            foreach ($resultado['productos'] as $prod) {
                $boleta->productos()->attach($prod['id'], [
                    'cantidad' => $prod['cantidad'],
                    'subtotal' => $prod['subtotal']
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Boleta generada con éxito.',
                'boleta_id' => $boleta->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error al guardar boleta: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Hubo un problema interno al guardar la boleta. Contacte a soporte.'], 500);
        }
    }

    private function procesarTextoAPedido($textoDictado)
    {
        $textoRecibido = strtolower(trim($textoDictado));
        $textoRecibido = preg_replace('/[^a-zñáéíóú0-9\s]/i', ' ', $textoRecibido);
        $textoRecibido = str_replace('aparte', '', $textoRecibido);
        $textoRecibido = str_replace(['.', ',', ';'], ' ', $textoRecibido);

        $mapaNumeros = [
            '/\b(un|una|uno)\b/' => '1',
            '/\b(dos)\b/' => '2',
            '/\b(tres)\b/' => '3',
            '/\b(cuatro)\b/' => '4',
            '/\b(cinco)\b/' => '5',
            '/\b(seis)\b/' => '6',
            '/\b(siete)\b/' => '7',
            '/\b(ocho)\b/' => '8',
            '/\b(nueve)\b/' => '9',
            '/\b(diez)\b/' => '10',
        ];
        $textoRecibido = preg_replace(array_keys($mapaNumeros), array_values($mapaNumeros), $textoRecibido);

        preg_match_all('/(\d+)\s*([a-zñáéíóú\s]+?)(?=\s*\d|$)/i', $textoRecibido, $matches);

        $itemsDictados = [];
        for ($i = 0; $i < count($matches[0]); $i++) {
            $itemsDictados[] = [
                'cantidad' => (int) $matches[1][$i],
                'palabra' => trim($matches[2][$i])
            ];
        }

        $productosDetectados = [];
        $totalCalculado = 0;
        $productosDB = Producto::all();

        foreach ($itemsDictados as $item) {
            if (empty($item['palabra'])) continue;

            $mejorCoincidencia = null;
            $mayorSimilitud = 0; 

            foreach ($productosDB as $producto) {
                $palabraDictadaLimpia = preg_replace('/[^a-zñáéíóú]/i', '', $item['palabra']);
                $nombreBDLimpio = preg_replace('/[^a-zñáéíóú]/i', '', strtolower($producto->nombre));

                if (str_contains($palabraDictadaLimpia, $nombreBDLimpio)) {
                    $porcentaje = 100;
                } else {
                    similar_text($palabraDictadaLimpia, $nombreBDLimpio, $porcentaje);
                }
                
                if ($porcentaje > $mayorSimilitud) {
                    $mayorSimilitud = $porcentaje;
                    $mejorCoincidencia = $producto;
                }
            }

            if ($mayorSimilitud >= 65 && $mejorCoincidencia) {
                $subtotal = $mejorCoincidencia->precio_unitario * $item['cantidad'];
                
                // Agrupar si ya detectamos el mismo producto en esta sesión
                $encontrado = false;
                foreach($productosDetectados as &$pd) {
                    if($pd['id'] == $mejorCoincidencia->id) {
                        $pd['cantidad'] += $item['cantidad'];
                        $pd['subtotal'] += $subtotal;
                        $encontrado = true;
                        break;
                    }
                }

                if(!$encontrado) {
                    $productosDetectados[] = [
                        'id' => $mejorCoincidencia->id,
                        'nombre' => $mejorCoincidencia->nombre,
                        'precio_unitario' => $mejorCoincidencia->precio_unitario,
                        'cantidad' => $item['cantidad'],
                        'subtotal' => $subtotal
                    ];
                }
                
                $totalCalculado += $subtotal;
            }
        }

        return [
            'productos' => $productosDetectados,
            'total' => $totalCalculado
        ];
    }
    
    public function downloadTxt(Boleta $boleta)
    {
        
        $boleta->load(['vendedor', 'productos']);

        
        $anchoTicket = 56;
        $lineaPunteada = str_repeat('-', $anchoTicket) . "\r\n";

        
        $contenido = $lineaPunteada;
        $contenido .= "FRUNA\r\n";
        $contenido .= "Fecha:    [" . $boleta->created_at->format('Y-m-d H:i') . "]\r\n";
        $contenido .= "Vendedor: [" . $boleta->vendedor->name . "]\r\n\r\n";
        
        $contenido .= "Detalle:\r\n";
        
        
        foreach ($boleta->productos as $producto) {
            $textoIzquierda = "[" . $producto->nombre . "] x [" . $producto->pivot->cantidad . "]";
            $textoDerecha = "$ [" . $producto->pivot->subtotal . "]";
            
            
            $espaciosFaltantes = $anchoTicket - strlen($textoDerecha);
            
            
            $contenido .= str_pad($textoIzquierda, $espaciosFaltantes, " ", STR_PAD_RIGHT) . $textoDerecha . "\r\n";
        }
        
        
        $contenido .= "\r\n" . $lineaPunteada;
        
        $textoTotalIzquierda = "TOTAL:";
        $textoTotalDerecha = "$ [" . $boleta->total . "]";
        $espaciosFaltantesTotal = $anchoTicket - strlen($textoTotalDerecha);
        
        $contenido .= str_pad($textoTotalIzquierda, $espaciosFaltantesTotal, " ", STR_PAD_RIGHT) . $textoTotalDerecha . "\r\n";
        $contenido .= $lineaPunteada;

        $nombreArchivo = 'boleta_fruna_N' . $boleta->id . '.txt';

        
        return response($contenido)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $nombreArchivo . '"');
    }
}