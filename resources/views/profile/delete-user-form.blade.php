<x-jet-action-section>
    <x-slot name="title">
        {{ __('Apagar conta') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Apague sua conta permanentemente') }}
    </x-slot>

    <x-slot name="content">
        <div>
            {{ __('Uma vez deletada a conta, todos os seus dados produzidos serão permanentementet apagados. Salve a informação que deseja manter.') }}
        </div>

        <div class="mt-3">
            <x-jet-danger-button wire:click="confirmUserDeletion" wire:loading.attr="disabled">
                {{ __('Apagar conta') }}
            </x-jet-danger-button>
        </div>

        <!-- Delete User Confirmation Modal -->
        <x-jet-dialog-modal wire:model="confirmingUserDeletion">
            <x-slot name="title">
                {{ __('Apagar conta') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Realmente deseja apgar sua conta? Uma vez deletada a conta, todos os seus dados produzidos serão permanentementet apagados. Salve a informação que deseja manter.') }}

                <div class="mt-2 w-md-75" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-jet-input type="password" class="{{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="{{ __('Password') }}"
                                 x-ref="password"
                                 wire:model.defer="password"
                                 wire:keydown.enter="deleteUser" />

                    <x-jet-input-error for="password" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingUserDeletion')"
                                        wire:loading.attr="disabled">
                    {{ __('Cancelar') }}
                </x-jet-secondary-button>

                <x-jet-danger-button wire:click="deleteUser" wire:loading.attr="disabled">
                    {{ __('Apagar conta') }}
                </x-jet-danger-button>
            </x-slot>
        </x-jet-dialog-modal>
    </x-slot>

</x-jet-action-section>
