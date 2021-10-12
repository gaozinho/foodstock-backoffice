<x-guest-layout>

    <div class="col-md-12 text-center mb-5 mt-5">
        <h2 class="text-uppercase ftco-uppercase">Cadastre-se</h2>
    </div>

    <x-jet-validation-errors class="mb-3 px-3" />

    <div class="row">
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group">
                            <x-jet-label value="{{ __('Name') }}" />

                            <x-jet-input class="{{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                                name="name" :value="old('name')" required autofocus autocomplete="name" />
                            <x-jet-input-error for="name"></x-jet-input-error>
                        </div>

                        <div class="form-group">
                            <x-jet-label value="{{ __('Código do convite') }}" />

                            <x-jet-input class="{{ $errors->has('invitation') ? 'is-invalid' : '' }}" type="text"
                                name="invitation" :value="old('invitation')" required autofocus autocomplete="invitation" />
                            <x-jet-input-error for="invitation"></x-jet-input-error>
                        </div>                        

                        <div class="form-group">
                            <x-jet-label value="{{ __('Email') }}" />

                            <x-jet-input class="{{ $errors->has('email') ? 'is-invalid' : '' }}" type="email"
                                name="email" :value="old('email')" required />
                            <x-jet-input-error for="email"></x-jet-input-error>
                        </div>

                        <div class="form-group">
                            <x-jet-label value="{{ __('Password') }}" />

                            <x-jet-input class="{{ $errors->has('password') ? 'is-invalid' : '' }}" type="password"
                                name="password" required autocomplete="new-password" />
                            <x-jet-input-error for="password"></x-jet-input-error>
                        </div>

                        <div class="form-group">
                            <x-jet-label value="{{ __('Confirm Password') }}" />

                            <x-jet-input class="form-control" type="password" name="password_confirmation" required
                                autocomplete="new-password" />
                        </div>

                        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <x-jet-checkbox id="terms" name="terms" />
                                    <label class="custom-control-label" for="terms">
                                        {!! __('Aceito os :privacy_policy', [
                                            //'terms_of_service' => '<a target="_blank" href="' . route('terms.show') . '">' . __('Terms of Service') . '</a>',
                                            'privacy_policy' => '<a target="_blank" href="' . route('policy.show') . '">' . __('Termos de Uso e Política de Privacidade') . '</a>',
                                        ]) !!}
                                    </label>
                                </div>
                            </div>
                        @endif

                        <div class="mb-0">
                            <div class="d-flex justify-content-end align-items-baseline">
                                <a class="text-muted mr-3 text-decoration-none" href="{{ route('login') }}">
                                    {{ __('Already registered?') }}
                                </a>

                                <x-jet-button>
                                    {{ __('Register') }}
                                </x-jet-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">Estamos em fase de lançamento, por isso, só é possível realizar o cadastro com convite em mãos. 
                    <p>Se você não tem o convite, pode solicitar no email foodstock.contato@gmail.com. Informe o nome de seu delivery e conte-nos por que deseja ajuda para gerenciá-lo.</p>
                </div>
            </div>
        </div>  
    </div>

</x-guest-layout>
