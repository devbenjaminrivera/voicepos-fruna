<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Control - Distribución Fruna') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-gradient-to-r from-fruna-darkred to-fruna-red rounded-2xl shadow-md overflow-hidden mb-8 text-white p-8 relative">
                <div class="z-10 relative max-w-2xl">
                    <h3 class="text-3xl font-bold mb-3 text-fruna-yellow">¡Hola, {{ Auth::user()->name }}!</h3>
                    <p class="text-red-50 text-base leading-relaxed">
                        Bienvenido al sistema VoicePOS de Fruna. Desde esta Dashboard puedes acceder directamente para generar una venta y ver el historial de las boletas hechas.
                    </p>
                </div>
                <div class="absolute right-0 top-0 bottom-0 opacity-20 flex items-center justify-center pointer-events-none pr-8 select-none">
                     <img src="{{ asset('img/logo.png') }}" alt="Fruna Watermark" class="h-48 w-auto object-contain blur-[2px] grayscale">
                 </div>
            </div>

            <!-- Resumen del Día -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                <!-- Métrica 1: Ventas Hoy -->
                <div class="bg-white/70 backdrop-blur-md rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Ventas Hoy</span>
                        <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <span class="text-3xl font-bold text-fruna-red">${{ number_format($ventasHoy, 0, ',', '.') }}</span>
                </div>

                <!-- Métrica 2: Pedidos Emitidos -->
                <div class="bg-white/70 backdrop-blur-md rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Pedidos Hoy</span>
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                    </div>
                    <span class="text-3xl font-bold text-gray-900">{{ $cantidadBoletasHoy }}</span>
                </div>
            </div>

            <!-- Accesos Rápidos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <a href="{{ route('boletas.create') }}" class="group bg-white/70 backdrop-blur-md rounded-2xl shadow-sm hover:shadow-lg border border-gray-100 overflow-hidden transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-8 flex items-center">
                        <div class="p-5 bg-red-50 rounded-xl text-fruna-red group-hover:bg-fruna-red group-hover:text-white transition-colors duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-10 h-10">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 0 0 6-6v-1.5m-6 7.5a6 6 0 0 1-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 0 1-3-3V4.5a3 3 0 1 1 6 0v8.25a3 3 0 0 1-3 3Z" />
                            </svg>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-1">Iniciar Venta por Voz</h3>
                            <p class="text-gray-500 text-sm">Abre el módulo de reconocimiento para generar un ticket.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('boletas.index') }}" class="group bg-white/70 backdrop-blur-md rounded-2xl shadow-sm hover:shadow-lg border border-gray-100 overflow-hidden transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-8 flex items-center">
                        <div class="p-5 bg-gray-50 rounded-xl text-gray-600 group-hover:bg-gray-800 group-hover:text-white transition-colors duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-10 h-10">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-1">Historial de Boletas</h3>
                            <p class="text-gray-500 text-sm">Revisa pedidos anteriores y exporta boletas a TXT.</p>
                        </div>
                    </div>
                </a>

            </div>

            <div class="bg-white/70 backdrop-blur-md rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 bg-transparent">
                    <h3 class="text-xl font-bold text-gray-800">Catálogo de Productos</h3>
                    <p class="text-sm text-gray-500 mt-1">Listado de artículos habilitados para el procesamiento mediante comandos de voz.</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 uppercase font-semibold">
                            <tr>
                                <th scope="col" class="px-8 py-4">Nombre del Producto</th>
                                <th scope="col" class="px-8 py-4 text-right">Precio Unitario Base</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            @forelse($productosBD as $prod)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-8 py-4 font-medium text-gray-900">{{ $prod->nombre }}</td>
                                    <td class="px-8 py-4 text-right">${{ number_format($prod->precio_unitario, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center py-4 text-gray-400">El catálogo está vacío.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>