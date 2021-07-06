<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>

        <!-- Templates -->
        <link rel="stylesheet" href="{{ asset('landing/css/style.css') }}">
    </head>
    <body class="bg-light font-sans antialiased">

        @include('header')
        <main id="main-container" class="container">
            {{ $slot }}
        </main>
        <section class="ftco-section ftco-slant ftco-slant-dark" id="section-faq">

        </section>

        @include('footer')     
    </body>
</html>