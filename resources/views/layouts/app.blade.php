<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('plugins/nprogress/nprogress.css') }}" rel="stylesheet" />
    <link href='{{ asset('plugins/jvectormap/jquery-jvectormap-2.0.3.css') }}' rel='stylesheet'>
    <link href='{{ asset('plugins/toastr/toastr.min.css') }}' rel='stylesheet'>
    <script src="{{ asset('plugins/nprogress/nprogress.js') }}"></script>
    <link href='{{ asset('plugins/ladda/ladda.min.css') }}' rel='stylesheet'>
    <link id="sleek-css" rel="stylesheet" href="{{ asset('css/sleek.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('head')
    @livewireStyles
</head>

<body>
    <script>
        NProgress.configure({
            showSpinner: false
        });
        NProgress.start();
    </script>

    @include('sweetalert::alert')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <x-livewire-alert::scripts />


    <div id="app">
        <header class="sidebar" id="sidebar">
            <nav class="top-nav">
                <ul>
                    <li>
                        <a href="{{ route('dashboard') }}">
                            <img src="{{ asset('img/icons/circle-left-regular.svg') }}" alt="">
                            Atras
                        </a>
                    </li>
                    <!--li>
                        <a href="#">
                            <img src="{{ asset('img/icons/calendar-days-regular.svg') }}" alt="">
                            Agenda
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="{{ asset('img/icons/calculator-solid.svg') }}" alt="">
                            Calculadora
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="{{ asset('img/icons/list-ul-solid.svg') }}" alt="">
                            Selección
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="{{ asset('img/icons/chart-line-solid.svg') }}" alt="">
                            Graficos
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('settings') }}"
                            class="{{ request()->routeIs('settings') ? 'active' : '' }}">
                            <img src="{{ asset('img/icons/gear-solid.svg') }}" alt="">
                            Configuración
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="{{ asset('img/icons/shapes-solid.svg') }}" alt="">
                            Aplicaciones
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="{{ asset('img/icons/users-solid.svg') }}" alt="">
                            Usuario
                        </a>
                    </li-->
                </ul>
            </nav>

            <nav class="nav">
                <li class="has-sub {{ request()->is('human-capital/*') ? 'has-active' : '' }}">
                    <a href="#" onclick="toggleSubMenu(this)">Capital humano
                        <img src="{{ asset('img/icons/angle-right-solid.svg') }}" alt="">
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ route('human-capital.action-requests') }}"
                                class="{{ request()->routeIs('human-capital.action-requests') ? 'active' : '' }}">Acciones</a>
                        </li>
                        <!--li><a href="{{ route('human-capital.employees') }}"
                                class="{{ request()->routeIs('human-capital.employees') ? 'active' : '' }}">Empleados</a>
                        </li-->
                    </ul>
                </li>

                <!--li><a href="#">Reclutamiento</a></li>
                <li><a href="#">RR.HH.</a></li>
                <li><a href="#">Nómina General</a></li>
                <li><a href="#">Staffing Guide</a></li>
                <li><a href="#">Capacitación</a></li>
                <li><a href="#">Evaluaciones</a></li-->

                <li>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Cerrar sesión
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </nav>
            <div class="sidebar-overlay" id="sidebar-overlay"></div>
        </header>

        <main>
            <div class="wrapper">
                <nav class="navbar">
                    <button class="btn-sidebar-toggler" onclick="toggleSidebar()">
                        <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"></path>
                        </svg>
                    </button>

                    <div class="profile">
                        <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                        </svg>
                        <strong>{{ auth()->user()->NOM }}</strong>
                    </div>
                </nav>

                <div class="p-3 overflow-auto">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src='{{ asset('plugins/charts/Chart.min.js') }}'></script>
    <script src='{{ asset('js/chart.js') }}'></script>
    <script src="{{ asset('js/sleek.js') }}"></script>
    <script src='{{ asset('plugins/ladda/spin.min.js') }}'></script>
    <script src='{{ asset('plugins/ladda/ladda.min.js') }}'></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @livewireScripts
    @yield('scripts')
</body>

</html>
