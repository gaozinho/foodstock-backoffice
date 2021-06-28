<x-jet-dropdown id="productionLineDropdown">
    <x-slot name="trigger">
        Linha de produção
        <svg class="ml-2" width="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
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