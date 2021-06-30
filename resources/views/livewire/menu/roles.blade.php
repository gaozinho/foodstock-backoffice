<x-jet-dropdown id="productionLineDropdown">
    <x-slot name="trigger">
        <i class="fas fa-industry"></i> Linha de produção
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
    </x-slot>
</x-jet-dropdown>