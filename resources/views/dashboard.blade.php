<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Control - Distribución Fruna') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-red-600 rounded-2xl shadow-md overflow-hidden mb-8 text-white p-8 relative">
                <div class="z-10 relative max-w-2xl">
                    <h3 class="text-3xl font-bold mb-3">¡Hola, {{ Auth::user()->name }}!</h3>
                    <p class="text-red-100 text-base leading-relaxed">
                        Bienvenido al sistema VoicePOS de Fruna. Desde esta Dashboard puedes acceder directamente para generar una venta y ver el historial de las boletas hechas.
                    </p>
                </div>
                <div class="absolute right-0 top-0 bottom-0 opacity-20 flex items-center justify-center pointer-events-none pr-8 select-none">
                     <img src="{{ asset('img/logo.png') }}" alt="Fruna Watermark" class="h-48 w-auto object-contain blur-[2px] grayscale">
                 </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                
                <a href="{{ route('boletas.create') }}" class="group bg-white rounded-2xl shadow-sm hover:shadow-lg border border-gray-100 overflow-hidden transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-8 flex items-center">
                        <div class="p-5 bg-red-50 rounded-xl text-red-600 group-hover:bg-red-600 group-hover:text-white transition-colors duration-300">
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

                <a href="{{ route('boletas.index') }}" class="group bg-white rounded-2xl shadow-sm hover:shadow-lg border border-gray-100 overflow-hidden transition-all duration-300 transform hover:-translate-y-1">
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

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 bg-white">
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
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-8 py-4 font-medium text-gray-900">Cocacola / Coca Cola</td>
                                <td class="px-8 py-4 text-right">$800</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-8 py-4 font-medium text-gray-900">Papas Fritas</td>
                                <td class="px-8 py-4 text-right">$900</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-8 py-4 font-medium text-gray-900">Cereales</td>
                                <td class="px-8 py-4 text-right">$1.200</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-8 py-4 font-medium text-gray-900">Galletas</td>
                                <td class="px-8 py-4 text-right">$600</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-8 py-4 font-medium text-gray-900">Gomitas</td>
                                <td class="px-8 py-4 text-right">$550</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-8 py-4 font-medium text-gray-900">Jugos</td>
                                <td class="px-8 py-4 text-right">$700</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-8 py-4 font-medium text-gray-900">Chocolates</td>
                                <td class="px-8 py-4 text-right">$500</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-8 py-4 font-medium text-gray-900">Ramitas</td>
                                <td class="px-8 py-4 text-right">$650</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>