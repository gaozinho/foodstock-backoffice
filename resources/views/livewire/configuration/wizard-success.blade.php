<div>

    <div class="row">
        <div class="col-md-12 margin-tb">

            <div class="card border mb-4">
                <div class="card-body">
                    <div class="row justify-content-between no-gutters">
                        <div class="col-1"><i class="fas fa-4x fa-trophy"></i></div>
                        <div class="col-11 align-self-end">
                            <span class="h3">Ótimo! Você concluiu a configuração do deu delivery.</span>
                            <p class="mb-2" style="line-height: 1.2">Caso você tenha realizado pelo menos uma integração com sucesso, já pode acompanhar a chegada de seus pedidos no menu "<b>Acompanhe</b>" ou através do Painel de Delivery.</p>
                            <a href="{{route('panels.delivery-panel.index')}}" class="btn btn-lg btn-success"><i class="fas fa-tv"></i> Acessar Painel de Delivery</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border mb-4">
                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-md-12 align-self-end">
                            <div class="h3 font-weight-bolder mb-3">Próximos passos</div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="h4 mb-1">Configurar os produtos comercializados</p>
                                        <p class="text-muted mb-1">É possível integrar também o seu cardápio já existente no marktplace com o foodStock e controlar o estoque de cada item.</p>
                                        <a href="{{route('products.index')}}" class="btn btn-success"><i class="fas fa-hamburger"></i> Produtos</a>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="h4 mb-1">Configurar equipe de trabalho</p>
                                        <p class="text-muted mb-1">Defina quem pode acessar o seu delivery e acompanhar o processo de produção.</p>
                                        <a href="{{route('configuration.teams.index')}}" class="btn btn-success"><i class="fas fa-users"></i> Equipe</a>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="h4 mb-1">Painel de controle</p>
                                        <p class="text-muted mb-1">Tenha uma visão resumida de vendas e de sua linha de produção em tempo real.</p>
                                        <a href="{{route('dashboard')}}" class="btn btn-success"><i class="fas fa-chart-line"></i> Painel de controle</a>
                                    
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>            

            

        </div>
    </div>
</div>

