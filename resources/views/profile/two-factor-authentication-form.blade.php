<x-jet-action-section>
    <x-slot name="title">
        {{ __('Autenticação em Dois Fatores') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Adicione mais segurança à sua conta com Autenticação em Dois Fatores.') }}
    </x-slot>

    <x-slot name="content">
        <h3 class="h5 font-weight-bold">
            @if ($this->enabled)
                {{ __('Você ativou a autenticação em dois fatores.') }}
            @else
                {{ __('Você não ativou a autenticação em dois fatores.') }}
            @endif
        </h3>

        <p class="mt-3">
            {{ __('Quando a autenticação em dois fatores está ativada, por segurança, você deverá fornecer um código adicional sempre que acessar o Foodstock. Este código adicional é gerado pelo aplicativo Google Authenticator. Baixe o aplicativo na loja.') }}
        </p>

        @if ($this->enabled)
            @if ($showingQrCode)
                <p class="mt-3">
                    {{ __('A autenticação em dois fatores está ativade. Leia o QR Code com o Google Authenticator para testar o código.') }}
                </p>

                <div class="mt-3">
                    {!! $this->user->twoFactorQrCodeSvg() !!}
                </div>
            @endif

            @if ($showingRecoveryCodes)
                <p class="mt-3">
                    {{ __('Guarde estes códigos com segurança. Eles poderão ser usados caso você não consiga usar a autenticação em dois fatores ou caso você perca seu aparelho celular.') }}
                </p>

                <div class="bg-light rounded p-3">
                    @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                        <div>{{ $code }}</div>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="mt-3">
            @if (! $this->enabled)
                <x-jet-confirms-password wire:then="enableTwoFactorAuthentication">
                    <x-jet-button type="button" wire:loading.attr="disabled">
                        {{ __('Ativar') }}
                    </x-jet-button>
                </x-jet-confirms-password>
            @else
                @if ($showingRecoveryCodes)
                    <x-jet-confirms-password wire:then="regenerateRecoveryCodes">
                        <x-jet-secondary-button class="mr-3">
                            {{ __('Gerar novos códigos de recuperação') }}
                        </x-jet-secondary-button>
                    </x-jet-confirms-password>
                @else
                    <x-jet-confirms-password wire:then="showRecoveryCodes">
                        <x-jet-secondary-button class="mr-3">
                            {{ __('Mostrar códigos de recuperação') }}
                        </x-jet-secondary-button>
                    </x-jet-confirms-password>
                @endif

                <x-jet-confirms-password wire:then="disableTwoFactorAuthentication">
                    <x-jet-danger-button wire:loading.attr="disabled">
                        {{ __('Desativar') }}
                    </x-jet-danger-button>
                </x-jet-confirms-password>
            @endif
        </div>
    </x-slot>
</x-jet-action-section>