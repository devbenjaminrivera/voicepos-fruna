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
    
    public function index()
    {
    
        $boletas = Boleta::with(['empresa', 'productos'])
                         ->where('vendedor_id', Auth::id())
                         ->orderBy('created_at', 'desc')
                         ->get();

        return view('boletas.index', compact('boletas'));
    }

    
    public function create()
    {
        return view('boletas.create');
    }

    
    public function store(Request $request)
    {
        
        $request->validate([
            'texto_dictado' => 'required|string',
            'empresa_id' => 'required|exists:empresas,id'
        ]);

        
        $textoRecibido = strtolower($request->texto_dictado);
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
                'palabra' => trim($matches[2][$i]) // quita espacios sobrantes
            ];
        }

        try {
            DB::beginTransaction();

            // Creación de la boleta inicial con total 0
            $boleta = Boleta::create([
                'empresa_id' => $request->empresa_id,
                'vendedor_id' => Auth::id(),
                'total' => 0 
            ]);

            $totalCalculado = 0;
            $productosDB = Producto::all();

            // 4. Lógica de Similitud Porcentual 
            foreach ($itemsDictados as $item) {
                if (empty($item['palabra'])) continue;

                $mejorCoincidencia = null;
                $mayorSimilitud = 0; 

                foreach ($productosDB as $producto) {
                    $palabraDictadaLimpia = str_replace(' ', '', $item['palabra']);
                    $nombreBDLimpio = strtolower(str_replace(' ', '', $producto->nombre));

                    
                    similar_text($palabraDictadaLimpia, $nombreBDLimpio, $porcentaje);
                    
                    if ($porcentaje > $mayorSimilitud) {
                        $mayorSimilitud = $porcentaje;
                        $mejorCoincidencia = $producto;
                    }
                }

                
                if ($mejorCoincidencia && $mayorSimilitud >= 75) {
                    $subtotal = $mejorCoincidencia->precio_unitario * $item['cantidad'];
                    $totalCalculado += $subtotal;

                    $boleta->productos()->attach($mejorCoincidencia->id, [
                        'cantidad' => $item['cantidad'],
                        'subtotal' => $subtotal
                    ]);
                }
            }

            
            $boleta->update(['total' => $totalCalculado]);

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Boleta generada con éxito.',
                'boleta_id' => $boleta->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
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