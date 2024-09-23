<!doctype html>
<html lang="es">

<head>
    @routes
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" id="csrf" content="{{ csrf_token() }}">

    <title>PETS CARE</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <!-- ICONIFY CSS -->
    <link rel="stylesheet" href="{{ asset('css/iconify.css') }}">

    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/jquery.mCustomScrollbar.min.css') }}">
    {{-- dataTables CSS --}}
    <link href="{{ asset('css/dataTables.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/responsive.dataTables.min.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">

    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js"
        integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous">
    </script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js"
        integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous">
    </script>
    <!-- Full Calendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
    <script src='fullcalendar/core/locales/es.global.js'></script>

 
    
    @stack('styles')
</head>

<body class="bg-white">
    @guest
        <main class="py-4">
            @yield('content')
        </main>
    @else
        <div class="wrapper">
            <!-- Sidebar  -->
            <nav id="sidebar">
                <div class="sidebar-header px-lg-5">
                    <img src="{{ asset('img/logo-petscare.png') }}" class="img-fluid" alt="">
                    {{-- <h3>Bootstrap Sidebar</h3> --}}
                </div>

                <ul class="list-unstyled components ps-4 pe-3">

                    {{-- <p>Dummy Heading</p> --}}
                    <li class="active border-start border-3 border-primary">
                        <a href="#homeSubmenu" class="ms-2">
                            <i class="fas fa-home"></i>
                            Inicio
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('receptions.index') }}" class="ms-2">
                            <i class="fas fa-list"></i>
                            Recepción
                        </a>
                    </li>

                    <li>
                        <a href="#" class=" ms-2">
                            <i class="fas fa-hand-holding-heart"></i>
                            Asignaciones
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('families.index') }}" class=" ms-2">
                            <span class="fluent-mdl2--family"></span>
                            Familias
                        </a>
                    </li>

                    <li>
                        <a href="#pageSubmenu" data-bs-toggle="collapse" aria-expanded="false"
                            class="dropdown dropdown-toggle  ms-2">
                            <i class="fas fa-cogs"></i>
                            Configuración
                        </a>
                        <ul class="collapse list-unstyled" id="pageSubmenu">
                            <li>
                                <a href="{{ route('logs.index') }}" class="ms-2"><i class="fa fa-calendar-check"></i>
                                    Bitácora</a>
                            </li>
                            <li>
                                <a href="{{ route('users.index') }}" class="ms-2"><i class="fas fa-user"></i>
                                    Usuarios</a>
                            </li>
                            <li>
                                <a href="{{ route('rooms.index') }}" class="ms-2"><i class="fas fa-first-aid"></i>
                                    Consultorios</a>
                            </li>
                            <li>
                                <a href="{{ route('shifts.index') }}" class="ms-2"><i class="fa fa-clock"></i> Turnos</a>
                            </li>
                            <li>
                                <a href="{{ route('cover-areas.index') }}" class="ms-2"><i class="fas fa-list"></i> Areas Horarios</a>
                            </li>
                            <li>
                                <a href="{{ route('schedules.index') }}" class="ms-2"><i class="fa fa-calendar-check"></i> Horarios</a>
                            </li>
                            <li>
                                <a href="{{ route('reasons.index') }}" class="ms-2"><i class="fas fa-list"></i>
                                    Motivos</a>
                            </li>
                            <li>
                                <a href="{{ route('areas.index') }}" class="ms-2"><i class="fas fa-list"></i> Áreas</a>
                            </li>
                            <li>
                                <a href="{{ route('attention-statuses.index') }}" class="ms-2"><i
                                        class="fas fa-list"></i> Estados de atención</a>
                            </li>
                            <li>
                                <a href="{{ route('reception-types.index') }}" class="ms-2"><i class="fas fa-list"></i>
                                    Tipos de recepción</a>
                            </li>
                            <li>
                                <a href="{{ route('genres.index') }}" class="ms-2"><i class="fas fa-venus-mars"></i>
                                    Géneros</a>
                            </li>
                            <li>
                                <a href="{{ route('admission-types.index') }}" class="ms-2"><i class="fas fa-list"></i>
                                    Tipos de ingreso</a>
                            </li>
                            <li>
                                <a href="{{route('reproductive-statuses.index')}}" class="ms-2"><i class="fas fa-list"></i> 
                                    Estados Reproductivos</a>
                            </li>
                            <li>
                                <a href="{{route('fam-classifications.index')}}" class="ms-2"><i class="fas fa-list"></i> 
                                    Clasificación Familias</a>
                            </li>
                            <li>
                                <a href="{{route('pet-classifications.index')}}" class="ms-2"><i class="fas fa-list"></i> 
                                    Clasificación Mascotas</a>
                            </li>
                            <li>
                                <a href="{{route('pets-statuses.index')}}" class="ms-2"><i class="fas fa-list"></i> 
                                    Estados de Mascotas</a>
                            </li>
                        </ul>
                    </li>
                </ul>

                {{-- <ul class="list-unstyled CTAs">
                    <li>
                        <a href="https://bootstrapious.com/tutorial/files/sidebar.zip" class="download">Download source</a>
                    </li>
                    <li>
                        <a href="https://bootstrapious.com/p/bootstrap-sidebar" class="article">Back to article</a>
                    </li>
                </ul> --}}
            </nav>

            <!-- Page Content  -->
            <div id="content">
                <nav class="navbar navbar-expand-md navbar-light bg-white shadow-none bg-transparent">
                    <div class="container">
                        <button type="button" id="sidebarCollapse" class="btn">
                            <i class="fas fa-align-left"></i>
                        </button>
                        {{-- <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a> --}}
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <!-- Left Side Of Navbar -->
                            <ul class="navbar-nav me-auto">

                            </ul>

                            <!-- Right Side Of Navbar -->
                            <ul class="navbar-nav ms-auto">
                                <!-- Authentication Links -->
                                {{-- @guest
                                    @if (Route::has('login'))
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                        </li>
                                    @endif

                                    @if (Route::has('register'))
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                        </li>
                                    @endif
                                @else --}}
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link p-0" href="#" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ Auth::user()->name }} <i class="fas fa-sort-down"></i>
                                    </a>
                                    <small>Hola, de nuevo. 🌤</small>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                                {{-- @endguest --}}
                            </ul>
                        </div>
                    </div>
                </nav>
                <main class="">
                    @yield('content')
                </main>

            </div>
        @endguest
    </div>


    </div>

    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <!-- Popper.JS -->
    <script src="{{ asset('js/popper.min.js') }}" defer></script>
    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.min.js') }}" defer></script>
    <!-- jQuery Custom Scroller CDN -->
    <script src="{{ asset('js/jquery.mCustomScrollbar.concat.min.js') }}" defer></script>
    {{-- dataTables JS --}}
    <script src="{{ asset('js/dataTables.js') }}" defer></script>
    <script src="{{ asset('js/jquery.dataTables.spanish.js') }}" defer></script>
    {{-- <script src="{{ asset('js/responsive.dataTables.min.js') }}" defer></script> --}}
    <script src="{{ asset('js/global.js') }}" defer></script>
    {{-- Select2 JS --}}
    <script src="{{ asset('js/select2.min.js') }}" defer></script>
    {{-- MOMENT JS --}}
    <script src="{{ asset('js/momentjs.min.js') }}" defer></script>
    {{-- sweetalert JS --}}
    <script src="{{ asset('js/sweetalert2.all.min.js') }}" defer></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#sidebar").mCustomScrollbar({
                theme: "minimal"
            });

            $('#sidebarCollapse').on('click', function() {
                $('#sidebar, #content').toggleClass('active');
                $('.collapse.in').toggleClass('in');
                $('a[aria-expanded=true]').attr('aria-expanded', 'false');
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
