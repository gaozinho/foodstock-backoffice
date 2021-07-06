<x-guest-layout>

    <div class="col-md-12 text-center mb-5 mt-5">
        <h2 class="text-uppercase ftco-uppercase">Entre</h2>
    </div>

    <x-jet-validation-errors class="mb-3 px-3" />

    <div class="row">
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success mb-3 rounded-0" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <x-jet-label value="{{ __('Email') }}" />

                            <x-jet-input class="{{ $errors->has('email') ? 'is-invalid' : '' }}" type="email"
                                name="email" :value="old('email')" required />
                            <x-jet-input-error for="email"></x-jet-input-error>
                        </div>

                        <div class="form-group">
                            <x-jet-label value="{{ __('Password') }}" />

                            <x-jet-input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                type="password" name="password" required autocomplete="current-password" />
                            <x-jet-input-error for="password"></x-jet-input-error>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <x-jet-checkbox id="remember_me" name="remember" />
                                <label class="custom-control-label" for="remember_me">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>

                        <div class="mb-0">
                            <div class="d-flex justify-content-end align-items-baseline">
                                @if (Route::has('password.request'))
                                    <a class="text-muted mr-3" href="{{ route('password.request') }}">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                @endif

                                <x-jet-button>
                                    {{ __('Entrar') }}
                                </x-jet-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">No período de lançamento do foodStock você não paga nada! Seja um parceiro e
                    ajude-nos a melhorar sempre, testantando novas funcionalidades e nos fornecendo feedbacks da
                    interação com o sistema.
                </div>
            </div>
        </div>
    </div>

</x-guest-layout>
