<x-jet-dropdown id="productionLineDropdown">
    <x-slot name="trigger">
        <i class="fas fa-industry"></i> Acompanhe
    </x-slot>
    <x-slot name="content">
        @forelse($roles as $role)
            @hasanyrole($role->name . '|admin')
                <x-jet-dropdown-link href="{{ route('panels.production-line-panel.index', ['role_name' => $role->name]) }}">
                    {{ __($role->custom_name) }}
                </x-jet-dropdown-link>
            @endhasanyrole
        @empty
            @role('admin')
                <div class="m-3">
                    {{ __("Você ainda não configurou sua linha de produção.") }}
                    <a class="btn btn-primary btn-sm mt-1" href="{{ route('wizard.restaurant.index') }}">Configurar plataforma</a>
                </div>    
            @endrole   
        @endforelse


            <hr class="dropdown-divider">

            

            @hasanyrole('admin|delivery')
                <x-jet-dropdown-link href="{{ route('panels.delivery-panel.index') }}" :active="request()->routeIs('panels.delivery-panel.index')">
                    <i class="fas fa-fw fa-tv"></i> {{ __('Painel de delivery') }}
                </x-jet-dropdown-link>
            @endhasanyrole

            @hasanyrole('admin|estoque')
                <x-jet-dropdown-link href="{{ route('stock.panel') }}" :active="request()->routeIs('stock.panel')">
                    <i class="fas fa-fw fa-hamburger"></i> {{ __('Painel de estoque') }}
                </x-jet-dropdown-link>                
            @endhasanyrole

            @if(auth()->user()->menagesRestaurants())
                <x-jet-dropdown-link href="{{ route('orders.keyboard.index') }}" :active="request()->routeIs('orders.keyboard.index')">
                    <i class="fas fa-fw fa-search"></i> {{ __('Encontre um pedido') }}
                </x-jet-dropdown-link>     
            @endif

            <hr class="dropdown-divider">

            @hasanyrole('admin')
                <x-jet-dropdown-link href="{{ route('indoor.order.index') }}" :active="request()->routeIs('indoor.order.index')">
                    <i class="fas fa-fw fa-mug-hot"></i> {{ __('Incluir pedido') }}
                </x-jet-dropdown-link>                
            @endhasanyrole            
    </x-slot>
</x-jet-dropdown>