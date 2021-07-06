<x-guest-layout>

    <div class="col-md-12 text-center mb-5 mt-5">
        <h2 class="text-uppercase ftco-uppercase">Login em duas etapas</h2>
    </div>

    <x-jet-validation-errors class="mb-3 px-3" />

    <div class="row">
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-body">
                    <div x-data="{ recovery: false }">
                        <div class="mb-3" x-show="! recovery">
                            {{ __('Confirme o acesso à sua conta informando o código de segurança fornecido pelo Google Authenticator') }}
                        </div>

                        <div class="mb-3" x-show="recovery">
                            {{ __('Confirme o acesso à sua conta fornecendo se código emergencial de recuperação.') }}
                        </div>

                        <form method="POST" action="{{ route('two-factor.login') }}">
                            @csrf

                            <div class="form-group" x-show="! recovery">
                                <x-jet-label value="{{ __('Código') }}" />
                                <x-jet-input class="{{ $errors->has('code') ? 'is-invalid' : '' }}" type="text"
                                    inputmode="numeric" name="code" autofocus x-ref="code"
                                    autocomplete="one-time-code" />
                                <x-jet-input-error for="code"></x-jet-input-error>
                            </div>

                            <div class="form-group" x-show="recovery">
                                <x-jet-label value="{{ __('Código de recuperação') }}" />
                                <x-jet-input class="{{ $errors->has('recovery_code') ? 'is-invalid' : '' }}"
                                    type="text" name="recovery_code" x-ref="recovery_code"
                                    autocomplete="one-time-code" />
                                <x-jet-input-error for="recovery_code"></x-jet-input-error>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="button" class="btn btn-outline-secondary mr-2" x-show="! recovery"
                                    x-on:click="
                                            recovery = true;
                                            $nextTick(() => { $refs.recovery_code.focus() })
                                        ">
                                    {{ __('Use um código de recuperação') }}
                                </button>

                                <button type="button" class="btn btn-outline-secondary mr-2" x-show="recovery"
                                    x-on:click="
                                            recovery = false;
                                            $nextTick(() => { $refs.code.focus() })
                                        ">
                                    {{ __('Use um código de autenticação') }}
                                </button>

                                <x-jet-button>
                                    {{ __('Entrar') }}
                                </x-jet-button>
                            </div>
                        </form>


                    </div>
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
