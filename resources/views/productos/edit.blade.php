<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Editar Producto') }}
            </h2>
            <a href="{{ route('productos.index') }}" class="text-gray-500 hover:text-fruna-red font-medium flex items-center gap-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Catálogo
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur-md rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex-none bg-white/50">
                    <h3 class="text-xl font-bold text-gray-800">Actualizar Producto</h3>
                    <p class="text-sm text-gray-500 mt-1">Los cambios se aplicarán al instante para el reconocimiento de voz de futuras boletas.</p>
                </div>

                <div class="p-8">
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('productos.update', $producto->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <label for="nombre" class="block text-sm font-bold text-gray-700 mb-2">Nombre del Producto</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $producto->nombre) }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" required>
                        </div>

                        <div class="mb-8">
                            <label for="precio_unitario" class="block text-sm font-bold text-gray-700 mb-2">Precio Unitario Base ($)</label>
                            <input type="number" name="precio_unitario" id="precio_unitario" value="{{ old('precio_unitario', $producto->precio_unitario) }}" min="0" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" required>
                        </div>

                        <div class="flex justify-end gap-4">
                            <a href="{{ route('productos.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-bold transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-gradient-to-br from-blue-600 to-blue-800 hover:shadow-[0_0_20px_rgba(37,99,235,0.4)] hover:scale-105 text-white font-bold py-3 px-8 rounded-xl transition-all duration-300 shadow-md">
                                Actualizar Producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
