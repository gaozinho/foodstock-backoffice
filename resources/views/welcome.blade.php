<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>foodStock - delivery simples</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="foodStock :: Seu delivery simples!" />
    <meta name="keywords" content="foodstock, delivery, gestão de produção, rappi, ifood, uber eats" />
    <meta name="author" content="Wagner Gonçalves wagnerggg@gmail.com" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('landing/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/open-iconic-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/animate.css') }}">

    <link rel="stylesheet" href="{{ asset('landing/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/owl.theme.default.min.css') }}">

    <link rel="stylesheet" href="{{ asset('landing/css/icomoon.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/style.css') }}">
</head>

<body data-spy="scroll" data-target="#ftco-navbar" data-offset="200">

    @include('header')

        <section class="ftco-cover ftco-slant" style="background-image: url({{ asset('landing/images/bg_3.jpg') }});"
            id="section-home">
            <div class="container">
                <div class="row align-items-center justify-content-center text-center ftco-vh-75">
                    <div class="col-md-10">
                        <h1 class="ftco-heading ftco-animate">Gerencie seu delivery. Simples e fácil!</h1>
                        <h2 class="h5 ftco-subheading mb-5 ftco-animate">FoodStock: uma ferramenta gratuita para gerenciar seu processo
                            de produção e despacho de pedidos. Integramos com as principais ferramentas de delivery do mercado.</h2>
                        <p><a href="{{ route('register') }}"
                                class="btn btn-banner ftco-animate">Cadastre-se agora</a></p>
                    </div>
                </div>
            </div>
        </section>



        <section class="ftco-section bg-light  ftco-slant ftco-slant-white" id="section-features">
            <div class="container">

                <div class="row">
                    <div class="col-md-12 text-center mb-5 ftco-animate">
                        <h2 class="text-uppercase ftco-uppercase">Com o foodStock você tem</h2>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="media d-block mb-4 text-center ftco-media p-md-5 p-4 ftco-animate">
                            <div class="ftco-icon mb-3"><i class="fas fa-3x fa-utensils"></i></div>
                            <div class="media-body">
                                <h5 class="mt-0">Integração com vários market places</h5>
                                <p class="mb-5">Centraliza os pedidos dos market places e informa sobre pedidos recebidos e despachados.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="media d-block mb-4 text-center ftco-media p-md-5 p-4 ftco-animate">
                            <div class="ftco-icon mb-3"><i class="fas fa-3x fa-chart-bar"></i></div>
                            <div class="media-body">
                                <h5 class="mt-0">Gestão à vista</h5>
                                <p class="mb-5">Funciona com o conceito de gestão à vista e disponibiliza quadros kankan que representam as etapas do processo produtivo do delivery.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="media d-block mb-4 text-center ftco-media p-md-5 p-4 ftco-animate">
                            <div class="ftco-icon mb-3"><i class="fas fa-3x fa-tasks"></i></div>
                            <div class="media-body">
                                <h5 class="mt-0">Processo de produção gerenciado</h5>
                                <p class="mb-5">dispobiliza painéis para as equipes, desde o cozinheiro ao despacho. Painéis também para os motoboys, na TV e no celular.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="media d-block mb-4 text-center ftco-media p-md-5 p-4 ftco-animate">
                            <div class="ftco-icon mb-3"><i class="fas fa-3x fa-funnel-dollar"></i></div>
                            <div class="media-body">
                                <h5 class="mt-0">Gestão de vendas e pedidos</h5>
                                <p class="mb-5">Tenha relatórios visuais de vendas e pedidos. Saiba em tempo real o que acontece no processo de produção do seu delivery.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="media d-block mb-4 text-center ftco-media p-md-5 p-4 ftco-animate">
                            <div class="ftco-icon mb-3"><i class="fas fa-3x fa-users-cog"></i>
                            </div>
                            <div class="media-body">
                                <h5 class="mt-0">Faça times de trabalho</h5>
                                <p class="mb-5">Gerencie times de produção e, para que cada membro, forneça acesso somenta à parte do processo onde ele trabalha.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="media d-block mb-4 text-center ftco-media p-md-5 p-4 ftco-animate">
                            <div class="ftco-icon mb-3"><i class="fas fa-3x fa-user-check"></i></div>
                            <div class="media-body">
                                <h5 class="mt-0">Configure você mesmo</h5>
                                <p class="mb-5">Nossa intenção é ser simples. Você mesmo integra com os market places e configura o seu time e processo de trabalho. Tudo do seu jeito.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END section -->

        <section class="ftco-section ftco-slant" id="section-services">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center ftco-animate">
                        <h2 class="text-uppercase ftco-uppercase">Funcionalidades</h2>
                        <div class="row justify-content-center mb-5">
                            <div class="col-md-7">
                                <p class="lead">Conheça um pouco das funcionalidades do foodStock. Tentamos manter os processos simples para manter você com foco no seu negócio.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END row -->
                <div class="row">
                    <div class="col-lg-4 mb-5 ftco-animate">
                        <figure><img src="{{ asset('images/landing/tela-configure.png') }}" class="img-fluid"></figure>
                        <div class="p-3">
                            <h3 class="h4">Configure você mesmo</h3>
                            <ul class="list-unstyled ftco-list-check text-left">
                                <li class="d-flex mb-2"><span class="oi oi-check mr-3 text-primary"></span> <span>Não há necessidade de consultoria especializada.</span></li>
                                <li class="d-flex mb-2"><span class="oi oi-check mr-3 text-primary"></span> <span>As configurações são rápidas.</span></li>
                                <li class="d-flex mb-2"><span class="oi oi-check mr-3 text-primary"></span> <span>Mantemos tutoriais e vídeos para auxiliá-lo.</span></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-5 ftco-animate">
                        <figure><img src="{{ asset('images/landing/tela-pedido.png') }}" class="img-fluid"></figure>
                        <div class="p-3">
                            <h3 class="h4">Os pedidos em um só lugar</h3>
                            <ul class="list-unstyled ftco-list-check text-left">
                                <li class="d-flex mb-2"><span class="oi oi-check mr-3 text-primary"></span> <span>Veja detalhes dos seus pedidos em qualquer ponto da produção</span></li>
                                <li class="d-flex mb-2"><span class="oi oi-check mr-3 text-primary"></span> <span>Você pode colocar painéis (TVs ou tablets) para os seu time acompanhar a produção (até para o entregador)</span></li>
                            </ul>
                        </div>
                    </div>                    

                    <div class="col-lg-4 mb-5 ftco-animate">
                        <figure><img src="{{ asset('images/landing/tela-dashboard.png') }}"  class="img-fluid"></figure>
                        <div class="p-3">
                            <h3 class="h4">Painel de vendas</h3>
                            <ul class="list-unstyled ftco-list-check text-left">
                                <li class="d-flex mb-2"><span class="oi oi-check mr-3 text-primary"></span> <span>Acompanhe em tempo real como está a produção no seu delivery</span></li>
                                <li class="d-flex mb-2"><span class="oi oi-check mr-3 text-primary"></span> <span>Tenha uma visão semanal de vendas</span></li>
                                <li class="d-flex mb-2"><span class="oi oi-check mr-3 text-primary"></span> <span>Compare a semana de vendas com a semana anterior</span></li>
                            </ul>
                        </div>
                    </div>



                </div>
            </div>
        </section>

        <section class="ftco-section bg-light ftco-slant ftco-slant-white" id="section-pricing">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center ftco-animate">
                        <h2 class="text-uppercase ftco-uppercase">Planos</h2>
                        <div class="row justify-content-center mb-5">
                            <div class="col-md-7">
                                <p class="lead">No período de lançamento do foodStock você não paga nada! Seja um parceiro e ajude-nos a melhorar sempre, testantando novas funcionalidades e nos fornecendo feedbacks da interação com o sistema.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                /*
                <!-- END row -->
                <div class="row">
                    <div class="col-md bg-white p-5 m-2 text-center mb-2 ftco-animate">
                        <div class="ftco-pricing">
                            <h2>Standard</h2>
                            <p class="ftco-price-per text-center"><sup>$</sup><strong>25</strong><span>/mo</span></p>
                            <ul class="list-unstyled mb-5">
                                <li>Far far away behind the word mountains</li>
                                <li>Even the all-powerful Pointing has no control</li>
                                <li>When she reached the first hills</li>
                            </ul>
                            <p><a href="#" class="btn btn-secondary btn-sm">Get Started</a></p>
                        </div>
                    </div>
                    <div class="col-md bg-white p-5 m-2 text-center mb-2 ftco-animate">
                        <div class="ftco-pricing">
                            <h2>Professional</h2>
                            <p class="ftco-price-per text-center"><sup>$</sup><strong>75</strong><span>/mo</span></p>
                            <ul class="list-unstyled mb-5">
                                <li>Far far away behind the word mountains</li>
                                <li>Even the all-powerful Pointing has no control</li>
                                <li>When she reached the first hills</li>
                            </ul>
                            <p><a href="#" class="btn btn-secondary btn-sm">Get Started</a></p>
                        </div>
                    </div>
                    <div class="w-100 clearfix d-xl-none"></div>
                    <div class="col-md bg-white  ftco-pricing-popular p-5 m-2 text-center mb-2 ftco-animate">
                        <span class="popular-text">Popular</span>
                        <div class="ftco-pricing">
                            <h2>Silver</h2>
                            <p class="ftco-price-per text-center"><sup>$</sup><strong
                                    class="text-primary">135</strong><span>/mo</span></p>
                            <ul class="list-unstyled mb-5">
                                <li>Far far away behind the word mountains</li>
                                <li>Even the all-powerful Pointing has no control</li>
                                <li>When she reached the first hills</li>
                            </ul>
                            <p><a href="#" class="btn btn-primary btn-sm">Get Started</a></p>
                        </div>
                    </div>
                    <div class="col-md bg-white p-5 m-2 text-center mb-2 ftco-animate">
                        <div class="ftco-pricing">
                            <h2>Platinum</h2>
                            <p class="ftco-price-per text-center"><sup>$</sup><strong>215</strong><span>/mo</span></p>
                            <ul class="list-unstyled mb-5">
                                <li>Far far away behind the word mountains</li>
                                <li>Even the all-powerful Pointing has no control</li>
                                <li>When she reached the first hills</li>
                            </ul>
                            <p><a href="#" class="btn btn-secondary btn-sm">Get Started</a></p>
                        </div>
                    </div>
                </div>
                */
                @endphp
            </div>
        </section>

        <section class="ftco-section bg-white ftco-slant ftco-slant-dark" id="section-faq">

            <div class="container">
                <div class="row mb-5">
                    <div class="col-md-12 text-center ftco-animate">
                        <h2 class="text-uppercase ftco-uppercase">Perguntas frequentes</h2>
                    </div>
                </div>
                <!-- END row -->
                <div class="row">
                    <div class="col-md-6 mb-5 ftco-animate">
                        <h3 class="h5 mb-4">O foodStock é gratuito?</h3>
                        <p>Algumas funcionalidades sim, são gratuitas. Mas não se preocupe, com elas é possível gerenciar seu delivery.</p>
                        <p>Em breve oferecemos funcionalidades PREMIUM, com elas ajudaremos ainda mais o seu negócio.</p>
                    </div>
                    <div class="col-md-6 mb-5 ftco-animate">
                        <h3 class="h5 mb-4">Eu poderei migrar para um novo plano?</h3>
                        <p>Sim, claro! A medida que os planos forem sendo lançados, informaremos você sobre as novidades.</p>
                    </div>
                    <div class="col-md-6 mb-5 ftco-animate">
                        <h3 class="h5 mb-4">Quais são as funcionalidades PREMIUM?</h3>
                        <p>Ainda estamos trabalhando nisso. Por enquanto disponibilizamos as funcionalidades gratuitas. Em breve mais novidades.</p>
                    </div>
                    <div class="col-md-6 mb-5 ftco-animate">
                        <h3 class="h5 mb-4">Onde consigo suporte?</h3>
                        <p>Trabalhamos no email contato@foodstock.com.br. Envie-nos suas dúvidas e sugestões.</p>
                    </div>
                </div>
            </div>
        </section>


        @include('footer')

        <!-- loader -->
        <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
                <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
                <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10"
                    stroke="#4586ff" />
            </svg></div>


        <script src="{{ asset('landing/js/jquery.min.js') }}"></script>
        <script src="{{ asset('landing/js/popper.min.js') }}"></script>
        <script src="{{ asset('landing/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('landing/js/jquery.easing.1.3.js') }}"></script>
        <script src="{{ asset('landing/js/jquery.waypoints.min.js') }}"></script>
        <script src="{{ asset('landing/js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('landing/js/jquery.animateNumber.min.js') }}"></script>


        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false">
        </script>
        <script src="{{ asset('landing/js/google-map.js') }}"></script>

        <script src="{{ asset('landing/js/main.js') }}"></script>


    </body>

    </html>
