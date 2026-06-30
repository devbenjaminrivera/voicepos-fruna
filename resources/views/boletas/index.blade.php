<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Historial de Boletas') }}
            </h2>
            <a href="{{ route('boletas.create') }}" class="bg-gradient-to-r from-fruna-red to-fruna-darkred hover:shadow-lg hover:scale-105 transform text-white font-bold py-2.5 px-5 rounded-xl transition-all duration-300 shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Pedido
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Barra de Herramientas y Filtros -->
            <form method="GET" action="{{ route('boletas.index') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="relative w-full sm:w-96">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-fruna-red/20 focus:border-fruna-red transition-colors sm:text-sm" placeholder="Buscar por ID o Empresa...">
                </div>
                
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="submit" name="filter" value="hoy" class="px-4 py-2 {{ request('filter') === 'hoy' ? 'bg-fruna-red text-white border-fruna-red' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100' }} border rounded-xl text-sm font-medium transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Hoy
                    </button>
                    @if(request('search') || request('filter'))
                        <a href="{{ route('boletas.index') }}" class="px-4 py-2 bg-gray-50 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-100 text-sm font-medium transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Limpiar
                        </a>
                    @else
                        <button type="submit" class="px-4 py-2 bg-gray-800 border border-gray-800 text-white rounded-xl hover:bg-gray-700 text-sm font-medium transition-colors flex items-center gap-2">
                            Buscar
                        </button>
                    @endif
                </div>
            </form>

            <!-- Tabla de Datos -->
            <div class="bg-white/70 backdrop-blur-md overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                <th class="px-6 py-4 rounded-tl-3xl">ID Boleta</th>
                                <th class="px-6 py-4">Fecha de Emisión</th>
                                <th class="px-6 py-4">Empresa</th>
                                <th class="px-6 py-4">Total</th>
                                <th class="px-6 py-4 text-center rounded-tr-3xl">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($boletas as $boleta)
                                <tr class="hover:bg-gray-50/80 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                            #{{ $boleta->id }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $boleta->created_at->format('Y-m-d H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-fruna-yellow/20 flex items-center justify-center text-fruna-red font-bold text-xs border border-fruna-yellow/50">
                                                {{ substr($boleta->empresa->nombre, 0, 1) }}
                                            </div>
                                            <div class="ml-3 text-sm font-medium text-gray-900">
                                                {{ $boleta->empresa->nombre }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-bold text-gray-900 bg-green-50 text-green-700 px-3 py-1 rounded-lg border border-green-100">
                                            ${{ number_format($boleta->total, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('boletas.download', $boleta->id) }}" target="_blank" data-turbolinks="false" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors border border-blue-100 tooltip" title="Descargar Boleta TXT">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                                            <p class="text-lg font-medium text-gray-900 mb-1">No hay boletas generadas</p>
                                            <p class="text-sm text-gray-500 mb-4">Aún no has creado ningún pedido con el sistema de voz.</p>
                                            <a href="{{ route('boletas.create') }}" class="text-fruna-red hover:text-fruna-darkred font-medium flex items-center gap-1">
                                                Comenzar ahora <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>