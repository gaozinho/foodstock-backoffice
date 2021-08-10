<div>
    <div class="mb-3 mt-3 mb-5">
        <div class="row d-flex justify-content-between align-items-start">
            <div class="col-md-7 col-xs-12">
                <span class="mt-3 mb-0 pb-0 h1"><i class="fas fa-exclamation-circle"></i> Bem-vindo!</span>
                <div>
                    <p>Preparamos um breve tutorial para você configurar a plataforma. Tire um tempo para passar pelos pontos abaixo.</p>
                </div>                
            </div>
            <div class="mb-2 col-md-5 col-xs-12 text-right"><a class="btn btn-lg btn-danger" href="{{route('wizard.restaurant.index')}}"><i class="fas fa-cog"></i> QUERO CONFIGURAR MEU DELIVERY AGORA</a></div>
        </div>


        <div class="row">
            <div class="col-3 d-none d-md-block">
                <div class="card">
                    <div class="card-body">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <a class="nav-link active" id="v-pills-1-tab" data-toggle="pill" href="#v-pills-1"
                                role="tab" aria-controls="v-pills-1" aria-selected="true">Primeiros passos na
                                plataforma</a>
                            <a class="nav-link" id="v-pills-2-tab" data-toggle="pill" href="#v-pills-2" role="tab"
                                aria-controls="v-pills-2" aria-selected="false">Gestão à vista</a>
                            <a class="nav-link" id="v-pills-3-tab" data-toggle="pill" href="#v-pills-3" role="tab"
                                aria-controls="v-pills-3" aria-selected="false">Integração e informação</a>
                            <a class="nav-link" id="v-pills-4-tab" data-toggle="pill" href="#v-pills-4" role="tab"
                                aria-controls="v-pills-4" aria-selected="false">Configurando a plataforma</a>
                            <a class="nav-link" id="v-pills-4-tab" data-toggle="pill" href="#v-pills-5" role="tab"
                                aria-controls="v-pills-4" aria-selected="false">Gerenciar o seu time de trabalho</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-1" role="tabpanel"
                        aria-labelledby="v-pills-1-tab">
                        <div class="card">
                            <div class="card-body">
                                <p class="h3">Primeiros passos na plataforma</p>
                                <p class="">Agora que você se cadastrou e pretende controlar seu delivery com o
                                    <b>food</b>Stock,
                                    vamos ajudar você a entender e configurar tudo corretamente.</p>
                                <p class="">Mas não se preocupe, é simples. Vamos lá?</p>
                            </div>
                            <div class="card-footer text-right">
                                <a class="btn btn-sm btn-secondary" onclick="changeTab('2')">Próximo: Gestão à vista <i class="fas fa-forward"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-2" role="tabpanel" aria-labelledby="v-pills-2-tab">
                        <div class="card">
                            <div class="card-body">
                                <p class="h3">Gestão à vista</p>
                                <p class="">O <b>food</b>Stock integra-se automaticamente com plataformas de
                                    vendas como IFOOD e
                                    RAPPI, recebendo os pedidos e os colocando-os em uma esteira de produção para ajudar você e seu
                                    negócio.</p>
                                <p class="">Funciona com o conceito de <a
                                        href="https://pt.wikipedia.org/wiki/Gest%C3%A3o_%C3%A0_vista"
                                        target="_blank">gestão à vista</a> e
                                    disponibiliza quadros <a href="https://pt.wikipedia.org/wiki/Kanban"
                                        target="_blank">kankan</a> que
                                    representam as etapas do processo produtivo do delivery.</p>
                                <p class="">Para cada etapa há um quadro que você e sua equipe podem - e devem
                                    - interagir com ele
                                    para avançar o processo de produção.</p>
                                <p class="">Você pode então disponibilizar um painel eletrônico
                                    (TV/celular/tablet) em cada etapa
                                    do seu processo. Ex: um painel na cozinha onde o cozinheiro informa que o prato está
                                    preparado e um
                                    painel para quem despacha o pedido informar que tudo já foi entregue ao motoboy.
                                    <b>No <b>food</b>Stock
                                        você configura seu processo.</b></p>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a class="btn btn-sm btn-secondary" onclick="changeTab('3')">Próximo: Integração e informação <i class="fas fa-forward"></i></a>
                        </div>                        
                    </div>
                    <div class="tab-pane fade" id="v-pills-3" role="tabpanel" aria-labelledby="v-pills-3-tab">
                        <div class="card">
                            <div class="card-body">
                                <p class="h3">Integração e informação</p>
                                <p>Durante o andamento do processo o <b>food</b>Stock faz algumas coisas para você: </p>
                                <ul>
                                    <li>avisa a plataforma de venda que o pedido foi recebido com sucesso;</li>
                                    <li>avisa também quando o pedido saiu para a entrega;</li>
                                    <li>dispobiliza um painel para o motoboy acompanhar o pedido na sua loja;</li>
                                    <li>disponibilida um painel para o próprio motoboy acompanhar no celular a liberação
                                        para retirada do
                                        pedido;</li>
                                    <li>fornece informações de vendas e quantidade de pedidos do seu delivery;</li>
                                    <li>gerencia times de produção para que cada membro acesse corretamente o seu painel
                                        e os dados que deve
                                        acessar.</li>
                                </ul>
                                </p>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a class="btn btn-sm btn-secondary" onclick="changeTab('4')">Próximo: Configurando a plataforma <i class="fas fa-forward"></i></a>
                        </div>                           
                    </div>
                    <div class="tab-pane fade" id="v-pills-4" role="tabpanel" aria-labelledby="v-pills-4-tab">
                        <div class="card">
                            <div class="card-body">
                                <p class="h3">Configurando a plataforma</p>
                                <p class="">Chega de conversa e vamos configurar a plataforma.</p>
                                <p class="">Criamos uma configuração expressa para ajudá-lo a configurar a
                                    plataforma. São 3
                                    passos.</b></p>
                                <p class="h4 mt-3">1 - Cadastre seu delivery</p>
                                <p class="">Aqui, você cria o seu delivery no <b>food</b>Stock. Informe algumas
                                    informações básicas
                                    e pronto.</p>
                                <p class="">Após o cadastro do delivery várias opções se abrirão em seu menu.
                                </p>

                                <p class="h4 mt-3">2 - Configure as integrações</p>
                                <p class="">Mostre ao <b>food</b>Stock com quais plataformas de venda você
                                    trabalha. Atualmente
                                    trabalhamos com o IFOOD.</p>
                                <p class="">Cada plataforma de venda tem sua forma de integração. Não se
                                    preocupa, nesta etapa da
                                    configuração vamos orientá-lo sobre quais dados sâo necessários para a correta
                                    integração e cada
                                    plataforma.</p>

                                <p class="h4 mt-3">3 - Configure seu processo de produção</p>
                                <p class="">A gente sabe que o processo de produção varia de um delivery para
                                    outro, por isso
                                    deixamos você configurar o seu próprio processo.</p>
                                <p class="">O pedido passa por vários passos até ser entregue ao cliente, não
                                    é? Nós precisamos
                                    entender como seu delivery funciona para integrarmos corretamente com as plataformas
                                    de venda e ajudá-lo
                                    a controlar melhor sua produção.</p>
                                <p class="">O <b>food</b>Stock inicializa um processo modelo para você, ele
                                    funciona conforme a
                                    imagem abaixo. Fique a vontade para modificá-lo-lo.</p>
                                <div class="mt-3"><img class="img-fluid" style="max-height: 300px"
                                        src="{{ asset('/images/help/production-line.png') }}" /></div>
                            </div>
                            <div class="card-footer text-right">
                                <a class="btn btn-sm btn-secondary" onclick="changeTab('5')">Próximo: Gerenciar o seu time de trabalho <i class="fas fa-forward"></i></a>
                            </div>                              
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-5" role="tabpanel" aria-labelledby="v-pills-4-tab">
                        <div class="card">
                            <div class="card-body">
                                <p class="h3">Gerenciar o seu time de trabalho</p>
                                <p class="">Opcionalmente você pode gerenciar seus funcionários no <b>food</b>Stock.</p>
                                <p class="">Crie cozinheiros, atendentes etc. Cada um tem acesso apenas ao seu
                                    painel e todas as
                                    interações com o sistema são registradas.</p>
                                <p class="">Em breve teremos relatórios de produtividade e de registros de
                                    ações no sistema
                                    executados por membros de sua equipe.</p>
                                <p class="">Mas não se preocupe, se você mesmo quer cuidar do controle da
                                    produção, enquanto
                                    administrador do delivery você tem acesso a todas as funcionalidades.</p>

                            </div>
                            <div class="card-footer text-right">
                                <a class="btn btn-sm btn-danger" href="{{route('wizard.restaurant.index')}}">CONFIGURAR AGORA <i class="fas fa-forward"></i></a>
                            </div>     

                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function changeTab(openTabId){
                //$('.tab-pane').tab("hide");
                $('#v-pills-tab a[href="#v-pills-' + openTabId + '"]').tab('show')
            }
        </script>

    </div>

   