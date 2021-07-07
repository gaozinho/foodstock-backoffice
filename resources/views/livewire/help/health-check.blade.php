<div class="mt-3 mb-3">
    <link href="{{ asset('css/wizard/style.css') }}" rel="stylesheet" type="text/css">
    <div class="row">
        @if (!$everythingOk)
            <div class="col-md-12 mb-2"><span class="h5">Alguns itens <b>precisam de sua atenção</b> para correto
                    funcionamento dos painéis e integração com as paltaformas de delivery.</span></div>
        @endif
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-header">
                    <span class="h4">
                        @if ($everythingOk)
                            Seu delivery está configurado
                        @else
                            <i class="fas fa-lg fa-exclamation-circle"></i>
                            <a class="text-danger" href="{{ route('wizard.restaurant.index') }}">Seu delivery ainda não está configurado</a>
                        @endif
                    </span>
                </div>
                <div class="card-body">
                    <div class="container">
                        <div class="steps d-flex flex-wrap flex-sm-nowrap justify-content-between padding-top-2x padding-bottom-1x">
                            
                            <div class="step {{ $deliveryOk ? 'completed' : '' }}">
                                <a href="{{route('wizard.restaurant.index')}}" style="text-decoration: none">
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
                                <a href="{{route('wizard.broker.index')}}" style="text-decoration: none">
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
                                <small>
                                    @foreach ($brokersOk as $key => $ok)
                                        @if ($ok)
                                            <i class="fas fa-lg fa-check text-success"></i>
                                        @else
                                            <i class="fas fa-lg fa-times text-danger"></i>
                                        @endif

                                        {{ $key }}
                                    @endforeach
                                </small>
                            </div>
                            <div class="step {{ $proccessOk ? 'completed' : '' }}">
                                <a href="{{route('wizard.production-line.index')}}" style="text-decoration: none">
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

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <span class="h4">Próximos passos</span>
                </div>
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
                </div>                
            </div>
        </div>


    </div>

</div>
