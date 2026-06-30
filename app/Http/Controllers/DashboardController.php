<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boleta;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $ventasHoy = Boleta::where('vendedor_id', Auth::id())
            ->whereDate('created_at', \Carbon\Carbon::today())
            ->sum('total');
            
        $cantidadBoletasHoy = Boleta::where('vendedor_id', Auth::id())
            ->whereDate('created_at', \Carbon\Carbon::today())
            ->count();

        $productosBD = Producto::all();

        return view('dashboard', compact('ventasHoy', 'cantidadBoletasHoy', 'productosBD'));
    }
}
