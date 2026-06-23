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

        // Limpiamos el texto: a minúsculas y quitamos la palabra clave "aparte"
        $textoRecibido = strtolower($request->texto_dictado);
        $textoRecibido = str_replace('aparte', '', $textoRecibido);

        // Expresión regular para capturar el patrón: [Numero] [Palabra]
        // Ej: "2 cocacolas" -> Cantidad: 2, Producto: "cocacolas"
        preg_match_all('/(\d+)\s+([a-zñáéíóú]+)/i', $textoRecibido, $matches);

        $itemsDictados = [];
        for ($i = 0; $i < count($matches[0]); $i++) {
            $itemsDictados[] = [
                'cantidad' => (int) $matches[1][$i],
                'palabra' => $matches[2][$i]
            ];
        }

        try {
            DB::beginTransaction();

            // 2. Creación de la boleta inicial con total 0
            $boleta = Boleta::create([
                'empresa_id' => $request->empresa_id,
                'vendedor_id' => Auth::id(),
                'total' => 0 
            ]);

            $totalCalculado = 0;
            $productosDB = Producto::all();

            // 3. Lógica de Fuzzy Matching (Distancia de Levenshtein)
            foreach ($itemsDictados as $item) {
                $mejorCoincidencia = null;
                $menorDistancia = -1;

                foreach ($productosDB as $producto) {
                    // Comparamos la palabra dictada con el nombre del producto en BD
                    $distancia = levenshtein($item['palabra'], strtolower($producto->nombre));
                    
                    // Buscamos la palabra que requiera menos cambios
                    if ($menorDistancia == -1 || $distancia < $menorDistancia) {
                        $menorDistancia = $distancia;
                        $mejorCoincidencia = $producto;
                    }
                }

                // Umbral de tolerancia: Si la distancia es aceptable (ej. máximo 4 letras distintas)
                // Esto permite que "cocacola" coincida con "cocacolas" o "cereales" con "cereal"
                if ($mejorCoincidencia && $menorDistancia <= 4) {
                    // El cálculo matemático se hace estrictamente en el Backend
                    $subtotal = $mejorCoincidencia->precio_unitario * $item['cantidad'];
                    $totalCalculado += $subtotal;

                    // Insertamos en la tabla pivote
                    $boleta->productos()->attach($mejorCoincidencia->id, [
                        'cantidad' => $item['cantidad'],
                        'subtotal' => $subtotal
                    ]);
                }
            }

            // 4. Actualizamos el total real de la boleta
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
    // Genera y descarga la boleta en formato TXT
    public function downloadTxt(Boleta $boleta)
    {
        // Asegurarnos de cargar las relaciones necesarias
        $boleta->load(['vendedor', 'productos']);

        // Armar el contenido del archivo de texto respetando la estructura obligatoria
        $contenido = "FRUNA\r\n";
        $contenido .= "Fecha: [" . $boleta->created_at->format('Y-m-d H:i') . "]\r\n";
        $contenido .= "Vendedor: [" . $boleta->vendedor->name . "]\r\n";
        $contenido .= "Detalle:\r\n";
        
        foreach ($boleta->productos as $producto) {
            $contenido .= "[" . $producto->nombre . "] x [" . $producto->pivot->cantidad . "]\r\n";
            $contenido .= "$ [" . $producto->pivot->subtotal . "]\r\n";
        }
        
        $contenido .= "TOTAL:\r\n";
        $contenido .= "$ [" . $boleta->total . "]\r\n";

        $nombreArchivo = 'boleta_fruna_N' . $boleta->id . '.txt';

        // Retornar la respuesta forzando la descarga del archivo de texto
        return response($contenido)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $nombreArchivo . '"');
    }
}