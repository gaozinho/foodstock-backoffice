<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="/"><img style="width: 180px"
                src="{{ asset('images/logo-invert.png') }}" /></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
            aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>

        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active"><a href="/#section-home" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="/#section-features" class="nav-link">Caracterísiticas</a></li>
                <li class="nav-item"><a href="/#section-services" class="nav-link">Funcionalidades</a></li>
                <li class="nav-item"><a href="/#section-pricing" class="nav-link">Planos</a></li>

                @if (Route::has('login'))
                    @auth
                        <li class="nav-item"><a href="{{ url('/dashboard') }}" class="nav-link">Entre</a></li>
                    @else
                        <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">Entre</a></li>

                        @if (Route::has('register'))
                            <li class="nav-item"><a href="{{ route('register') }}"
                                    class="nav-link">Cadastre-se</a></li>
                        @endif
                    @endif
                @endif
            </ul>
        </div>
    </div>
</nav>