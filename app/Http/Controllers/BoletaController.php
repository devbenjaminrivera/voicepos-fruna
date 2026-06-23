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
    // Muestra el listado de boletas generadas
    public function index()
    {
        // Obtenemos las boletas del vendedor autenticado con sus relaciones
        $boletas = Boleta::with(['empresa', 'productos'])
                         ->where('vendedor_id', Auth::id())
                         ->orderBy('created_at', 'desc')
                         ->get();

        return view('boletas.index', compact('boletas'));
    }

    // Muestra la vista del POS (Punto de Venta) con el micrófono
    public function create()
    {
        return view('boletas.create');
    }

    // Procesa el texto del dictado y genera la boleta
    public function store(Request $request)
    {
        // 1. Validación de la petición
        $request->validate([
            'texto_dictado' => 'required|string',
            'empresa_id' => 'required|exists:empresas,id'
        ]);

        // Limpieza básica: a minúsculas, quitamos palabra clave y reemplazamos puntos/comas por espacios
        $textoRecibido = strtolower($request->texto_dictado);
        $textoRecibido = str_replace('aparte', '', $textoRecibido);
        $textoRecibido = str_replace(['.', ',', ';'], ' ', $textoRecibido);

        // 2. Mapeo inteligente: Convertir números en palabras a dígitos reales
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

        // 3. Expresión regular avanzada: 
        // Captura (\d+) un número y luego todo el texto hasta encontrar el siguiente número o el final del string.
        preg_match_all('/(\d+)\s*([a-zñáéíóú\s]+?)(?=\s*\d|$)/i', $textoRecibido, $matches);

        $itemsDictados = [];
        for ($i = 0; $i < count($matches[0]); $i++) {
            $itemsDictados[] = [
                'cantidad' => (int) $matches[1][$i],
                'palabra' => trim($matches[2][$i]) // trim() quita espacios sobrantes
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

            // 4. Lógica de Similitud Porcentual (Blindaje)
            foreach ($itemsDictados as $item) {
                if (empty($item['palabra'])) continue;

                $mejorCoincidencia = null;
                $mayorSimilitud = 0; // Ahora buscamos el mayor porcentaje, no la menor distancia

                foreach ($productosDB as $producto) {
                    $palabraDictadaLimpia = str_replace(' ', '', $item['palabra']);
                    $nombreBDLimpio = strtolower(str_replace(' ', '', $producto->nombre));

                    // similar_text guarda en la variable $porcentaje un valor del 0 al 100
                    similar_text($palabraDictadaLimpia, $nombreBDLimpio, $porcentaje);
                    
                    if ($porcentaje > $mayorSimilitud) {
                        $mayorSimilitud = $porcentaje;
                        $mejorCoincidencia = $producto;
                    }
                }

                // Exigimos un mínimo del 75% de similitud para aceptar el producto
                if ($mejorCoincidencia && $mayorSimilitud >= 75) {
                    $subtotal = $mejorCoincidencia->precio_unitario * $item['cantidad'];
                    $totalCalculado += $subtotal;

                    $boleta->productos()->attach($mejorCoincidencia->id, [
                        'cantidad' => $item['cantidad'],
                        'subtotal' => $subtotal
                    ]);
                }
            }

            // Actualizamos el total real
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
    // Genera y descarga la boleta en formato TXT con diseño estricto
    public function downloadTxt(Boleta $boleta)
    {
        // Asegurarnos de cargar las relaciones necesarias
        $boleta->load(['vendedor', 'productos']);

        // Definimos el ancho del ticket (ej. 56 caracteres)
        $anchoTicket = 56;
        $lineaPunteada = str_repeat('-', $anchoTicket) . "\r\n";

        // Armar el encabezado
        $contenido = $lineaPunteada;
        $contenido .= "FRUNA\r\n";
        $contenido .= "Fecha:    [" . $boleta->created_at->format('Y-m-d H:i') . "]\r\n";
        $contenido .= "Vendedor: [" . $boleta->vendedor->name . "]\r\n\r\n";
        
        $contenido .= "Detalle:\r\n";
        
        // Armar el detalle de productos alineado
        foreach ($boleta->productos as $producto) {
            $textoIzquierda = "[" . $producto->nombre . "] x [" . $producto->pivot->cantidad . "]";
            $textoDerecha = "$ [" . $producto->pivot->subtotal . "]";
            
            // Calculamos cuánto espacio en blanco necesitamos para que el precio quede a la derecha
            $espaciosFaltantes = $anchoTicket - strlen($textoDerecha);
            
            // Añadimos el producto, lo rellenamos con espacios hasta el margen, y pegamos el precio
            $contenido .= str_pad($textoIzquierda, $espaciosFaltantes, " ", STR_PAD_RIGHT) . $textoDerecha . "\r\n";
        }
        
        // Armar el pie del ticket con el total
        $contenido .= "\r\n" . $lineaPunteada;
        
        $textoTotalIzquierda = "TOTAL:";
        $textoTotalDerecha = "$ [" . $boleta->total . "]";
        $espaciosFaltantesTotal = $anchoTicket - strlen($textoTotalDerecha);
        
        $contenido .= str_pad($textoTotalIzquierda, $espaciosFaltantesTotal, " ", STR_PAD_RIGHT) . $textoTotalDerecha . "\r\n";
        $contenido .= $lineaPunteada;

        $nombreArchivo = 'boleta_fruna_N' . $boleta->id . '.txt';

        // Retornar la respuesta forzando la descarga del archivo
        return response($contenido)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $nombreArchivo . '"');
    }
}