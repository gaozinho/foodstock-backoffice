<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        

        @livewireStyles

        <!-- SCRIPTS -->
        @stack('modals')
     

        @livewireScripts
        @livewireChartsScripts
        <script src="{{ asset('vendor/livewire-charts/app.js') }}"></script>
        <!-- ALERTS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>  
        
        <x-livewire-alert::scripts />

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}"></script>

        <script src="{{ asset('js/spectrum/spectrum.js') }}" type="text/javascript" charset="utf-8"></script>
        <link href="{{ asset('js/spectrum/spectrum.css') }}" rel="stylesheet" type="text/css">
        
        @stack('scripts')

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-VPL9CXZHBZ"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-VPL9CXZHBZ');
        </script>

    </head>
    <body class="font-sans antialiased bg-light">
        <x-jet-banner />
        @livewire('navigation-menu')

        @if(isset($header))
        <!-- Page Heading 
        <header class="d-flex py-3 bg-white shadow-sm border-bottom full-screen mb-4">
            <div class="container">
                <span class="h3 text-muted">{{ $header }}</span>
            </div>
        </header>
        -->
        @endif

        <!-- Page Content -->
        <main id="main-container" class="container mb-4 mt-4">
            {{ $slot }}
        </main>
    </body>
</html>
