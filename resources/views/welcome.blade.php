<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- 
    More Templates Visit ==> Free-Template.co
    -->
    <title>Exclusivity - Free Bootstrap 4 Template</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Free Template by Free-Template.co" />
    <meta name="keywords" content="foodstock, delivery, gestão de produção, rappi, ifood, uber eats" />
    <meta name="author" content="Wagner Gonçalves wagnerggg@gmail.com" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="javasript:;"><img style="width: 180px"
                    src="{{ asset('images/logo-invert.png') }}" /></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
                aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="oi oi-menu"></span> Menu
            </button>

            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active"><a href="#section-home" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="#section-features" class="nav-link">Features</a></li>
                    <li class="nav-item"><a href="#section-services" class="nav-link">Services</a></li>
                    <li class="nav-item"><a href="#section-pricing" class="nav-link">Pricing</a></li>
                    <li class="nav-item"><a href="#section-about" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="#section-contact" class="nav-link">Contact</a></li>


                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item"><a href="{{ url('/dashboard') }}" class="nav-link">Acesse seu
                                    delivery</a></li>
                        @else
                            <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">Entre</a></li>

                            @if (Route::has('register'))
                                <li class="nav-item"><a href="{{ route('register') }}"
                                        class="ml-4 nav-link">Cadastre-se</a></li>
                            @endif
                        @endif
                        @endif


                    </ul>
                </div>
            </div>
        </nav>
        <!-- END nav -->

        <section class="ftco-cover ftco-slant" style="background-image: url({{ asset('landing/images/bg_3.jpg') }});"
            id="section-home">
            <div class="container">
                <div class="row align-items-center justify-content-center text-center ftco-vh-75">
                    <div class="col-md-10">
                        <h1 class="ftco-heading ftco-animate">Gerencie seu delivery. Simples e fácil!</h1>
                        <h2 class="h5 ftco-subheading mb-5 ftco-animate">FoodStock: uma ferramenta gratuita para gerenciar seu processo
                            de produção e despacho de pedidos. Integramos com as principais ferramentas de delivery do mercado.</h2>
                        <p><a href="https://free-template.co/" target="_blank"
                                class="btn btn-banner ftco-animate">Cadastre-se agora</a></p>
                    </div>
                </div>
            </div>
        </section>



        <section class="ftco-section bg-light  ftco-slant ftco-slant-white" id="section-features">
            <div class="container">

                <div class="row">
                    <div class="col-md-12 text-center mb-5 ftco-animate">
                        <h2 class="text-uppercase ftco-uppercase">Our Awesome Features</h2>
                        <div class="row justify-content-center">
                            <div class="col-md-7">
                                <p class="lead">Far far away, behind the word mountains, far from the countries Vokalia and
                                    Consonantia, there live the blind texts.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="media d-block mb-4 text-center ftco-media p-md-5 p-4 ftco-animate">
                            <div class="ftco-icon mb-3"><span class="oi oi-thumb-up display-4 text-muted"></span></div>
                            <div class="media-body">
                                <h5 class="mt-0">Free Bootstrap 4</h5>
                                <p class="mb-5">Far far away, behind the word mountains, far from the countries Vokalia and
                                    Consonantia, there live the blind texts.</p>
                                <p class="mb-0"><a href="#" class="btn btn-primary btn-sm">Learn More</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="media d-block mb-4 text-center ftco-media p-md-5 p-4 ftco-animate">
                            <div class="ftco-icon mb-3"><span class="oi oi-bolt display-4 text-muted"></span></div>
                            <div class="media-body">
                                <h5 class="mt-0">Fast Loading</h5>
                                <p class="mb-5">Far far away, behind the word mountains, far from the countries Vokalia and
                                    Consonantia, there live the blind texts.</p>
                                <p class="mb-0"><a href="#" class="btn btn-primary btn-sm">Learn More</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="media d-block mb-4 text-center ftco-media p-md-5 p-4 ftco-animate">
                            <div class="ftco-icon mb-3"><span class="oi oi-person display-4 text-muted"></span></div>
                            <div class="media-body">
                                <h5 class="mt-0">Designer &amp; Developer</h5>
                                <p class="mb-5">Far far away, behind the word mountains, far from the countries Vokalia and
                                    Consonantia, there live the blind texts.</p>
                                <p class="mb-0"><a href="#" class="btn btn-primary btn-sm">Learn More</a></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="media d-block mb-4 text-center ftco-media p-md-5 p-4 ftco-animate">
                            <div class="ftco-icon mb-3"><span class="oi oi-code display-4 text-muted"></span></div>
                            <div class="media-body">
                                <h5 class="mt-0">Clean Code</h5>
                                <p class="mb-5">Far far away, behind the word mountains, far from the countries Vokalia and
                                    Consonantia, there live the blind texts.</p>
                                <p class="mb-0"><a href="#" class="btn btn-primary btn-sm">Learn More</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="media d-block mb-4 text-center ftco-media p-md-5 p-4 ftco-animate">
                            <div class="ftco-icon mb-3"><span class="oi oi-magnifying-glass display-4 text-muted"></span>
                            </div>
                            <div class="media-body">
                                <h5 class="mt-0">Search Engine</h5>
                                <p class="mb-5">Far far away, behind the word mountains, far from the countries Vokalia and
                                    Consonantia, there live the blind texts.</p>
                                <p class="mb-0"><a href="#" class="btn btn-primary btn-sm">Learn More</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="media d-block mb-4 text-center ftco-media p-md-5 p-4 ftco-animate">
                            <div class="ftco-icon mb-3"><span class="oi oi-phone display-4 text-muted"></span></div>
                            <div class="media-body">
                                <h5 class="mt-0">Fully Responsive</h5>
                                <p class="mb-5">Far far away, behind the word mountains, far from the countries Vokalia and
                                    Consonantia, there live the blind texts.</p>
                                <p class="mb-0"><a href="#" class="btn btn-primary btn-sm">Learn More</a></p>
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
                        <h2 class="text-uppercase ftco-uppercase">Services</h2>
                        <div class="row justify-content-center mb-5">
                            <div class="col-md-7">
                                <p class="lead">Far far away, behind the word mountains, far from the countries Vokalia and
                                    Consonantia, there live the blind texts.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END row -->
                <div class="row">
                    <div class="col-lg-4 mb-5 ftco-animate">
                        <figure><img src="{{ asset('landing/images/img_2.jpg') }}" class="img-fluid"></figure>
                        <div class="p-3">
                            <h3 class="h4">Title of Service here</h3>
                            <p class="mb-4">Far far away, behind the word mountains, far from the countries Vokalia and
                                Consonantia, there live the blind texts.</p>
                            <ul class="list-unstyled ftco-list-check text-left">
                                <li class="d-flex mb-2"><span class="oi oi-check mr-3 text-primary"></span> <span>Free
                                        template for designer and developers</span></li>
                                <li class="d-flex mb-2"><span class="oi oi-check mr-3 text-primary"></span> <span>Vokalia
                                        and consonantia blind texts</span></li>
                                <li class="d-flex mb-2"><span class="oi oi-check mr-3 text-primary"></span> <span>Behind the
                                        word mountains blind texts</span></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-5 ftco-animate">
                        <figure><img src="{{ asset('landing/images/img_1.jpg') }}"
                                alt="Free Template by Free-Template.co" class="img-fluid"></figure>
                        <div class="p-3">
                            <h3 class="h4">Title of Service here</h3>
                            <p class="mb-4">Far far away, behind the word mountains, far from the countries Vokalia and
                                Consonantia, there live the blind texts.</p>
                            <ul class="list-unstyled ftco-list-check text-left">
                                <li class="d-flex mb-2"><span class="oi oi-check mr-3 text-primary"></span> <span>Free
                                        template for designer and developers</span></li>
                                <li class="d-flex mb-2"><span class="oi oi-check mr-3 text-primary"></span> <span>Vokalia
                                        and consonantia blind texts</span></li>
                                <li class="d-flex mb-2"><span class="oi oi-check mr-3 text-primary"></span> <span>Behind the
                                        word mountains blind texts</span></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-5 ftco-animate">
                        <figure><img src="{{ asset('landing/images/img_3.jpg') }}"
                                alt="Free Template by Free-Template.co" class="img-fluid"></figure>
                        <div class="p-3">
                            <h3 class="h4">Title of Service here</h3>
                            <p class="mb-4">Far far away, behind the word mountains, far from the countries Vokalia and
                                Consonantia, there live the blind texts.</p>
                            <ul class="list-unstyled ftco-list-check text-left">
                                <li class="d-flex mb-2"><span class="oi oi-check mr-3 text-primary"></span> <span>Free
                                        template for designer and developers</span></li>
                                <li class="d-flex mb-2"><span class="oi oi-check mr-3 text-primary"></span> <span>Vokalia
                                        and consonantia blind texts</span></li>
                                <li class="d-flex mb-2"><span class="oi oi-check mr-3 text-primary"></span> <span>Behind the
                                        word mountains blind texts</span></li>
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
                        <h2 class="text-uppercase ftco-uppercase">Pricing</h2>
                        <div class="row justify-content-center mb-5">
                            <div class="col-md-7">
                                <p class="lead">Far far away, behind the word mountains, far from the countries Vokalia and
                                    Consonantia, there live the blind texts.</p>
                            </div>
                        </div>
                    </div>
                </div>
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
            </div>
        </section>

        <section class="ftco-section ftco-slant ftco-slant-light">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center ftco-animate">
                        <h2 class="text-uppercase ftco-uppercase">More Features</h2>
                        <div class="row justify-content-center mb-5">
                            <div class="col-md-7">
                                <p class="lead">Far far away, behind the word mountains, far from the countries Vokalia and
                                    Consonantia, there live the blind texts.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END row -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="owl-carousel ftco-owl">

                            <div class="item ftco-animate">
                                <div class="media d-block text-left ftco-media p-md-5 p-4">
                                    <div class="ftco-icon mb-3"><span class="oi oi-pencil display-4"></span></div>
                                    <div class="media-body">
                                        <h5 class="mt-0">Easy to Customize</h5>
                                        <p>Far far away, behind the word mountains, far from the countries Vokalia and
                                            Consonantia, there live the blind texts.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="item ftco-animate">
                                <div class="media d-block text-left ftco-media p-md-5 p-4">
                                    <div class="ftco-icon mb-3"><span class="oi oi-monitor display-4"></span></div>
                                    <div class="media-body">
                                        <h5 class="mt-0">Web Development</h5>
                                        <p>Far far away, behind the word mountains, far from the countries Vokalia and
                                            Consonantia, there live the blind texts.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="item ftco-animate">
                                <div class="media d-block text-left ftco-media p-md-5 p-4">
                                    <div class="ftco-icon mb-3"><span class="oi oi-location display-4"></span></div>
                                    <div class="media-body">
                                        <h5 class="mt-0">Free Bootstrap 4</h5>
                                        <p>Far far away, behind the word mountains, far from the countries Vokalia and
                                            Consonantia, there live the blind texts.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="item ftco-animate">
                                <div class="media d-block text-left ftco-media p-md-5 p-4">
                                    <div class="ftco-icon mb-3"><span class="oi oi-person display-4"></span></div>
                                    <div class="media-body">
                                        <h5 class="mt-0">For People Like You</h5>
                                        <p>Far far away, behind the word mountains, far from the countries Vokalia and
                                            Consonantia, there live the blind texts.</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="ftco-section ftco-slant ftco-slant-light  bg-light ftco-slant ftco-slant-white" id="section-faq">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-md-12 text-center ftco-animate">
                        <h2 class="text-uppercase ftco-uppercase">FAQ</h2>
                        <div class="row justify-content-center mb-5">
                            <div class="col-md-7">
                                <p class="lead">Far far away, behind the word mountains, far from the countries Vokalia and
                                    Consonantia, there live the blind texts.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END row -->
                <div class="row">
                    <div class="col-md-6 mb-5 ftco-animate">
                        <h3 class="h5 mb-4">What is Exclusivity?</h3>
                        <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there
                            live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics,
                            a large language ocean.</p>
                        <p class="mb-5"><a href="#">Learn More</a></p>
                    </div>
                    <div class="col-md-6 mb-5 ftco-animate">
                        <h3 class="h5 mb-4">Can I upgrade?</h3>
                        <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there
                            live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics,
                            a large language ocean.</p>
                        <p class="mb-5"><a href="#">Learn More</a></p>
                    </div>
                    <div class="col-md-6 mb-5 ftco-animate">
                        <h3 class="h5 mb-4">Can I have more than 5 users?</h3>
                        <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there
                            live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics,
                            a large language ocean.</p>
                        <p class="mb-5"><a href="#">Learn More</a></p>
                    </div>
                    <div class="col-md-6 mb-5 ftco-animate">
                        <h3 class="h5 mb-4">If I need support who do I contact?</h3>
                        <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there
                            live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics,
                            a large language ocean.</p>
                        <p class="mb-5"><a href="#">Learn More</a></p>
                    </div>
                </div>
            </div>
        </section>

        <section class="ftco-section ftco-slant ftco-slant-light" id="section-about">
            <div class="container">

                <div class="row mb-5">
                    <div class="col-md-12 text-center ftco-animate">
                        <h2 class="text-uppercase ftco-uppercase">About Us</h2>
                        <div class="row justify-content-center mb-5">
                            <div class="col-md-7">
                                <p class="lead">Far far away, behind the word mountains, far from the countries Vokalia and
                                    Consonantia, there live the blind texts. Feel free to send us an email to <a
                                        href="#">info@yourdomain.com</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END row -->


                <div class="row no-gutters align-items-center ftco-animate">
                    <div class="col-md-6 mb-md-0 mb-5">
                        <img src="{{ asset('landing/images/bg_3.jpg') }}" alt="Free Template by Free-Template.co"
                            class="img-fluid">
                    </div>
                    <div class="col-md-6 p-md-5">
                        <h3 class="h3 mb-4">Far far away, behind the word mountains</h3>
                        <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there
                            live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics,
                            a large language ocean.</p>
                        <p class="mb-5"><a href="#">Learn More</a></p>
                    </div>
                </div>
                <div class="row no-gutters align-items-center ftco-animate">
                    <div class="col-md-6 order-md-3 mb-md-0 mb-5">
                        <img src="{{ asset('landing/images/bg_1.jpg') }}" alt="Free Template by Free-Template.co"
                            class="img-fluid">
                    </div>
                    <div class="col-md-6 p-md-5 order-md-1">
                        <h3 class="h3 mb-4">Far from the countries Vokalia and Consonantia</h3>
                        <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there
                            live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics,
                            a large language ocean.</p>
                        <p class="mb-5"><a href="#">Learn More</a></p>
                    </div>
                </div>

            </div>
        </section>
        <section class="ftco-section bg-light ftco-slant ftco-slant-white" id="section-counter">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-md-12 text-center ftco-animate">
                        <h2 class="text-uppercase ftco-uppercase">Fun Facts</h2>
                        <div class="row justify-content-center mb-5">
                            <div class="col-md-7">
                                <p class="lead">Far far away, behind the word mountains, far from the countries Vokalia and
                                    Consonantia, there live the blind texts.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END row -->
                <div class="row">
                    <div class="col-md ftco-animate">
                        <div class="ftco-counter text-center">
                            <span class="ftco-number" data-number="34146">0</span>
                            <span class="ftco-label">Lines of Codes</span>
                        </div>
                    </div>
                    <div class="col-md ftco-animate">
                        <div class="ftco-counter text-center">
                            <span class="ftco-number" data-number="1239">0</span>
                            <span class="ftco-label">Pizza Consume</span>
                        </div>
                    </div>
                    <div class="col-md ftco-animate">
                        <div class="ftco-counter text-center">
                            <span class="ftco-number" data-number="124">0</span>
                            <span class="ftco-label">Number of Clients</span>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <section class="ftco-section bg-white ftco-slant ftco-slant-dark" id="section-contact">
            <div class="container">
                <div class="row">

                    <div class="col-md pr-md-5 mb-5">
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="name" class="sr-only">Name</label>
                                <input type="text" class="form-control" id="name" placeholder="Enter your name">
                            </div>
                            <div class="form-group">
                                <label for="email" class="sr-only">Email</label>
                                <input type="text" class="form-control" id="email" placeholder="Enter your email">
                            </div>
                            <div class="form-group">
                                <label for="message" class="sr-only">Message</label>
                                <textarea name="message" id="message" cols="30" rows="10" class="form-control"
                                    placeholder="Write your message"></textarea>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value="Send Message">
                            </div>
                        </form>
                    </div>
                    <div class="col-md" id="map">
                    </div>
                </div>
            </div>
        </section>
        <footer class="ftco-footer ftco-bg-dark">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md">
                                <div class="ftco-footer-widget mb-4">
                                    <h2 class="ftco-heading-2">Company</h2>
                                    <ul class="list-unstyled">
                                        <li><a href="#" class="py-2 d-block">About</a></li>
                                        <li><a href="#" class="py-2 d-block">Jobs</a></li>
                                        <li><a href="#" class="py-2 d-block">Press</a></li>
                                        <li><a href="#" class="py-2 d-block">News</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="ftco-footer-widget mb-4">
                                    <h2 class="ftco-heading-2">Communities</h2>
                                    <ul class="list-unstyled">
                                        <li><a href="#" class="py-2 d-block">Support</a></li>
                                        <li><a href="#" class="py-2 d-block">Sharing is Caring</a></li>
                                        <li><a href="#" class="py-2 d-block">Better Web</a></li>
                                        <li><a href="#" class="py-2 d-block">Good Template</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="ftco-footer-widget mb-4">
                                    <h2 class="ftco-heading-2">Useful links</h2>
                                    <ul class="list-unstyled">
                                        <li><a href="#" class="py-2 d-block">Bootstrap 4</a></li>
                                        <li><a href="#" class="py-2 d-block">jQuery</a></li>
                                        <li><a href="#" class="py-2 d-block">HTML5</a></li>
                                        <li><a href="#" class="py-2 d-block">Sass</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="ftco-footer-widget mb-4">
                            <ul class="ftco-footer-social list-unstyled float-md-right float-lft">
                                <li><a href="#"><span class="icon-twitter"></span></a></li>
                                <li><a href="#"><span class="icon-facebook"></span></a></li>
                                <li><a href="#"><span class="icon-instagram"></span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md text-left">
                        <p>&copy; Exclusivity 2017. All Rights Reserved. Made with <span
                                class="icon-heart text-danger"></span> by <a
                                href="https://free-template.co/">Free-Template.co</a></p>
                    </div>
                </div>
            </div>
        </footer>

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
