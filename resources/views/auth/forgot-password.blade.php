<x-guest-layout>

    <div class="col-md-12 text-center mb-5 mt-5">
        <h2 class="text-uppercase ftco-uppercase">Recuperar senha</h2>
    </div>

    <x-jet-validation-errors class="mb-3 px-3" />

    <div class="row">
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-body">

                    <div class="mb-3">
                        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="/forgot-password">
                        @csrf

                        <div class="form-group">
                            <x-jet-label value="Email" />
                            <x-jet-input type="email" name="email" :value="old('email')" required autofocus />
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <x-jet-button>
                                {{ __('Email Password Reset Link') }}
                            </x-jet-button>
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
