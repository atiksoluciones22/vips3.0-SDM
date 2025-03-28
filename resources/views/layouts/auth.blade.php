<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('plugins/nprogress/nprogress.css') }}" rel="stylesheet" />
    <script src="{{ asset('plugins/nprogress/nprogress.js') }}"></script>
    <link href='{{ asset('plugins/ladda/ladda.min.css') }}' rel='stylesheet'>
    <link id="sleek-css" rel="stylesheet" href="{{ asset('css/sleek.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <script>
        NProgress.configure({
            showSpinner: false
        });
        NProgress.start();
    </script>
    @include('sweetalert::alert')

    <main class="py-4">
        @yield('content')
    </main>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/sleek.js') }}"></script>
    <script src='{{ asset('plugins/ladda/spin.min.js') }}'></script>
    <script src='{{ asset('plugins/ladda/ladda.min.js') }}'></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
