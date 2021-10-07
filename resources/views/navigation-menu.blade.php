@php
    $restaurants = auth()->user()->recoverUserRestaurant();
@endphp
<div class="">
    <nav class="navbar navbar-expand-md navbar-light bg-white border-bottom sticky-top full-screen">
        <div class="container">
            <a class="navbar-brand mr-4" href="{{route('dashboard')}}">
                <img src="{{ asset('images/logo.png') }}" style="height: 30px"> 
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <!--
                    <x-jet-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Home') }}
                    </x-jet-nav-link>
                    -->

                    <livewire:menu.roles />

                    @hasanyrole('admin|equipe|produtos')
                        @if(auth()->user()->menagesRestaurants())
                            <x-jet-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                                <i class="fas fa-chart-line"></i> Gerencie
                            </x-jet-nav-link>                        
                        @endif

                        <x-jet-dropdown id="teamManagementDropdown">
                            <x-slot name="trigger">
                                <i class="fas fa-motorcycle"></i> Configure
                            </x-slot>

                            <x-slot name="content">
                                <!--
                                <h6 class="dropdown-header">
                                    {{ __('Integrações e processo') }}
                                </h6>
                                -->
                                @role('admin')
                                <x-jet-dropdown-link href="{{ route('wizard.restaurant.index') }}">
                                    {{ __('Configuração expressa') }}
                                </x-jet-dropdown-link>  
                                
                                <hr class="dropdown-divider">

                                <x-jet-dropdown-link href="{{ route('configuration.restaurant.index') }}">
                                    {{ __('Sobre seu delivery') }}
                                </x-jet-dropdown-link>
                                @endrole

                                @if(auth()->user()->menagesRestaurants())
                                    <x-jet-dropdown-link href="{{ route('configuration.broker.index') }}">
                                        {{ __('Integrações') }}
                                    </x-jet-dropdown-link>

                                    <x-jet-dropdown-link href="{{ route('configuration.production-line.index') }}">
                                        {{ __('Processo de produção') }}
                                    </x-jet-dropdown-link>
                                @endif
                                
                                    @hasanyrole('admin|equipe')
                                        <x-jet-dropdown-link href="{{ route('configuration.teams.index') }}">
                                            {{ __('Equipe de trabalho') }}
                                        </x-jet-dropdown-link>
                                    @endhasanyrole
                                    @hasanyrole('admin|produtos')
                                    <x-jet-dropdown-link href="{{ route('products.index') }}">
                                        {{ __('Produtos comercializados') }}
                                    </x-jet-dropdown-link>
                                    @endhasanyrole
                                
                            </x-slot>
                        </x-jet-dropdown>



                    @endhasanyrole

                    @if(auth()->user()->menagesRestaurants())
                        <x-jet-nav-link href="{{ route('welcome') }}" :active="request()->routeIs('welc')">
                            <i class="fas fa-question-circle"></i> Ajuda
                        </x-jet-nav-link>                        
                    @endif

                </ul>
                <ul class="navbar-nav ml-auto align-items-baseline">
                    @auth
                        <x-jet-dropdown id="settingsDropdown">
                            <x-slot name="trigger">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    <img class="rounded-circle" width="32" height="32" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                @else
                                <i class="fas fa-user"></i>
                                    {{auth()->user()->name}}                           
                                
                                @endif
                            </x-slot>

                            <x-slot name="content">
                                <x-jet-dropdown-link href="{{ route('profile.show') }}">
                                    {{ __('Meus dados') }}
                                </x-jet-dropdown-link>

                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <x-jet-dropdown-link href="{{ route('api-tokens.index') }}">
                                        {{ __('API Tokens') }}
                                    </x-jet-dropdown-link>
                                @endif

                                <hr class="dropdown-divider">

                                <x-jet-dropdown-link href="{{ route('logout') }}"
                                                    onclick="event.preventDefault();
                                                            document.getElementById('logout-form').submit();">
                                    {{ __('Sair') }}
                                </x-jet-dropdown-link>
                                <form method="POST" id="logout-form" action="{{ route('logout') }}">
                                    @csrf
                                </form>
                            </x-slot>
                        </x-jet-dropdown>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    <div class="bg-white border-bottom">
        <div class="container">
            <div class="row">
                <div class="col my-1">
                    @php
                        $names = [];
                    @endphp            
                    @foreach($restaurants as $restaurant)
                        @php
                            $names[] = $restaurant->name;
                        @endphp
                        
                    @endforeach
                    <span class="h5 text-muted"><small>{!!implode(' &bull; ', $names)!!} </small></span>

                </div>
            </div>
        </div>
    </div>
           <div class="page-progress" wire:ignore>
            <script>
                function startProgress(seconds, progressEnclosure){
                    return $(progressEnclosure).progressBarTimer({ 
                        autoStart: true, smooth: false, timeLimit: seconds, completeStyle: 'bg-primary', warningStyle: 'bg-primary', baseStyle: 'bg-primary'
                    });
                }         
            </script>
        </div> 
</div>