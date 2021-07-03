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