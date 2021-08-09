<x-jet-dropdown id="productionLineDropdown">
    <x-slot name="trigger">
        <i class="fas fa-industry"></i> Acompanhe
    </x-slot>
    <x-slot name="content">
        @forelse($roles as $role)
            @hasanyrole($role->name . '|admin')
                <x-jet-dropdown-link href="{{ route('panels.production-line-panel.index', ['role_name' => $role->name]) }}">
                    {{ __($role->description) }}
                </x-jet-dropdown-link>
            @endhasanyrole
        @empty
        <div class="m-3">
            {{ __("Você ainda não configurou sua linha de produção.") }}
            <a class="btn btn-primary btn-sm mt-1" href="{{ route('wizard.restaurant.index') }}">Configurar plataforma</a>
        </div>       
        @endforelse

        @role('admin')
            <hr class="dropdown-divider">

            @if(auth()->user()->menagesRestaurants())


                <x-jet-dropdown-link href="{{ route('panels.delivery-panel.index') }}" :active="request()->routeIs('panels.delivery-panel.index')">
                    <i class="fas fa-tv"></i> {{ __('Painel de delivery') }}
                </x-jet-dropdown-link>

                <x-jet-dropdown-link href="{{ route('orders.keyboard.index') }}" :active="request()->routeIs('orders.keyboard.index')">
                    <i class="fas fa-search"></i> {{ __('Encontre um pedido') }}
                </x-jet-dropdown-link>     

            @endif

        @endrole
        
    </x-slot>
</x-jet-dropdown>