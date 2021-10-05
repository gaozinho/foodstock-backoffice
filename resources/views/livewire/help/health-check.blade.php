<div class="mt-3 mb-3">
    <link href="{{ asset('css/wizard/style.css') }}" rel="stylesheet" type="text/css">
    <div class="row">
        @if (!$everythingOk)
            <div class="col-md-12 mb-2"><span class="h5">Alguns itens <b>precisam de sua atenção</b> para
                    correto
                    funcionamento dos painéis e integração com as paltaformas de delivery.</span>
            </div>
        @endif
        <div class="col-md-8 mb-3">
            <div class="card card-painel">
                <div class="card-header">
                    <span class="h4">
                        @if ($everythingOk)
                            Seu delivery está configurado
                        @else
                            <i class="fas fa-lg fa-exclamation-circle"></i>
                            <a class="text-danger" href="{{ route('wizard.restaurant.index') }}">Seu delivery ainda
                                não está configurado</a>
                        @endif
                    </span>
                </div>
                <div class="card-body">
                    <div class="container">
                        <div
                            class="steps d-flex flex-wrap flex-sm-nowrap justify-content-between padding-top-2x padding-bottom-1x">

                            <div class="step {{ $deliveryOk ? 'completed' : '' }}">
                                <a href="{{ route('wizard.restaurant.index') }}" style="text-decoration: none">
                                    <div class="step-icon-wrap">
                                        <div class="step-icon">
                                            @if ($deliveryOk)
                                                <i class="fas fa-lg fa-check text-success"></i>
                                            @else
                                                <i class="fas fa-lg fa-times text-danger"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <h4 class="step-title">Informações do delivery</h4>
                                </a>
                            </div>

                            <div class="step {{ $integrationOk ? 'completed' : '' }}">
                                <a href="{{ route('wizard.broker.index') }}" style="text-decoration: none">
                                    <div class="step-icon-wrap">
                                        <div class="step-icon">
                                            @if ($integrationOk)
                                                <i class="fas fa-lg fa-check text-success"></i>
                                            @else
                                                <i class="fas fa-lg fa-times text-danger"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <h4 class="step-title">Integrações</h4>
                                </a>
                            </div>
                            <div class="step {{ $proccessOk ? 'completed' : '' }}">
                                <a href="{{ route('wizard.production-line.index') }}" style="text-decoration: none">
                                    <div class="step-icon-wrap">
                                        <div class="step-icon">
                                            @if ($proccessOk)
                                                <i class="fas fa-lg fa-check text-success"></i>
                                            @else
                                                <i class="fas fa-lg fa-times text-danger"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <h4 class="step-title">Processo de produção</h4>
                                </a>
                            </div>



                        </div>
                        <hr />
                        <div class="row mt-4">




                            @foreach ($merchantsInfo as $brokerName => $integrationInfo)
                                <div class="col-12 col-md-6"><small>
                                        <div class="mb-3 d-flex align-items-end" style="height: 45px">
                                            <div class="align-bottom">
                                            @if ($brokerName == 'NEEMO')
                                                <img  src="{{ asset('images/brokers/neemo-logo.png') }}"
                                                    style="width:75px">
                                            @elseif ($brokerName == "IFOOD")
                                                <img src="{{ asset('images/brokers/ifood-logo.png') }}"
                                                    style="width:75px">
                                            @endif
                                            </div>
                                        </div>
                                        <table class="table table-striped table-hover table-bordered table-sm ">

                                            <tr>
                                                <th>Delivery</th>
                                                <th>Configurado?</th>
                                                <th>Conectado?</th>
                                            </tr>

                                            @foreach ($integrationInfo as $checkItens)
                                                <tr>
                                                    <td>{{ $checkItens['restaurant'] }}</td>
                                                    <td class="text-center">{!! $checkItens['validated'] ? '<i class="fas fa-lg fa-check text-success"></i>' : '<i class="fas fa-lg fa-times text-danger"></i>' !!}</td>
                                                    <td>{!! $checkItens['available'] ? '<i class="fas fa-lg fa-check text-success"></i>' : '<i class="fas fa-lg fa-times text-danger"></i>' !!}
                                                        {!! $checkItens['reason'] != '' ? '<small>' . $checkItens['reason'] . '</small>' : '' !!}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </small>
                                </div>
                            @endforeach


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-painel">
                <div class="card-body">
                    <p class="h4">Com o delivery configurado você pode: </p>
                    <p class="my-1"><b>Acompanhar os pedidos</b> que chegarem no
                        <a href="{{ route('panels.delivery-panel.index') }}">
                            <i class="fas fa-tv"></i> {{ __('Painel') }}
                        </a>
                    </p>
                    <p class="my-1"><b>Configurar sua
                            <a href="{{ route('configuration.teams.index') }}">
                                {{ __('equipe de trabalho') }}
                            </a>
                        </b> para acessar o foodStock.
                    </p>
                    <p class="my-1">Cadastrar <b>
                            <a href="{{ route('products.index') }}">
                                {{ __('produtos que você comercializa') }}
                            </a>
                        </b>.
                    </p>
                </div>
            </div>
        </div>


    </div>

</div>
