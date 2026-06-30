<aside class="flex flex-col w-64 h-full bg-white border-r border-gray-100 shrink-0">
    <!-- Logo -->
    <div class="flex items-center justify-center h-20 border-b border-gray-50">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <x-application-logo class="block h-10 w-auto fill-current text-fruna-red" />
            <span class="text-xl font-bold text-gray-800 tracking-tight">VoicePOS</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto py-6">
        <nav class="space-y-2 px-4">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors w-full border-b-0 {{ request()->routeIs('dashboard') ? 'bg-red-50 text-fruna-red font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent' }}" style="border-bottom: 0px !important;">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span>{{ __('Dashboard') }}</span>
            </x-nav-link>

            <x-nav-link :href="route('boletas.index')" :active="request()->routeIs('boletas.index')" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors w-full border-b-0 {{ request()->routeIs('boletas.index') ? 'bg-red-50 text-fruna-red font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent' }}" style="border-bottom: 0px !important;">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <span>{{ __('Historial de Boletas') }}</span>
            </x-nav-link>

            <x-nav-link :href="route('productos.index')" :active="request()->routeIs('productos.*')" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors w-full border-b-0 {{ request()->routeIs('productos.*') ? 'bg-red-50 text-fruna-red font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent' }}" style="border-bottom: 0px !important;">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span>{{ __('Catálogo de Productos') }}</span>
            </x-nav-link>

            <x-nav-link :href="route('boletas.create')" :active="request()->routeIs('boletas.create')" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors w-full border-b-0 {{ request()->routeIs('boletas.create') ? 'bg-red-50 text-fruna-red font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent' }}" style="border-bottom: 0px !important;">
                <svg class="w-5 h-5 shrink-0 text-fruna-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                <span>{{ __('Nuevo Pedido (Voz)') }}</span>
            </x-nav-link>
        </nav>
    </div>

    <!-- User & Settings (Bottom) -->
    <div class="p-4 border-t border-gray-100">
        <x-dropdown align="top" width="48">
            <x-slot name="trigger">
                <button class="flex items-center justify-between w-full p-2 bg-gray-50 rounded-xl hover:bg-gray-100 border border-gray-100 transition-colors focus:outline-none">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-9 h-9 bg-gradient-to-br from-fruna-darkred to-fruna-red rounded-full flex items-center justify-center text-white font-bold text-sm shadow-sm shrink-0">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="text-sm font-medium text-gray-700 truncate text-left">
                            {{ Auth::user()->name }}
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-dropdown-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        <span class="text-red-600">{{ __('Cerrar Sesión') }}</span>
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</aside>
