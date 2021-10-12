<x-guest-layout>

    <div class="col-md-12 text-center mb-5 mt-5">
        <h2 class="text-uppercase ftco-uppercase">Valide seu e-mail</h2>
    </div>

    <x-jet-validation-errors class="mb-3 px-3" />

    <div class="row">
        <div class="col-md-8 mb-3">
            <div class="card">

                <div class="card-body">
                    <div class="mb-3 small text-muted">
                        {!! __('Bem-vindo ao foodStock! <br><br>Seu cadastro foi realizado com sucesso! Você recebeu um email de validação. Acesse sua caixa postal e siga as orientações. <br />Caso não tenha recebido o e-mail, clique no link abaixo que reenviaremos a validação.') !!}
                    </div>

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success" role="alert">
                            {{ __('Um link de verificação foi enviado para o endereço de e-mail que você cadastrou.') }}
                        </div>
                    @endif

                    <div class="mt-4 d-flex justify-content-between">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf

                            <div>
                                <x-jet-button type="submit">
                                    {{ __('Reenviar e-mail de verificação') }}
                                </x-jet-button>
                            </div>
                        </form>

                        <form method="POST" action="/logout">
                            @csrf

                            <button type="submit" class="btn btn-link">
                                {{ __('Sair') }}
                            </button>
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