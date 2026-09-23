@extends('layouts.app')

@push('styles')
    <style>
        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        /* anula el padding que el layout le mete a <main class="py-4">
                   y el fondo blanco forzado de <body class="bg-white">, solo en esta vista */
        main.py-4 {
            padding: 0 !important;
            margin: 0 !important;
        }

        body.bg-white {
            background: #0A1C30 !important;
        }

        .login-shell {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            background: #0A1C30;
            padding: 0;
        }

        .login-bg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            z-index: 0;
        }

        .login-content {
            position: relative;
            z-index: 2;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, .25);
            padding: 2.25rem;
        }

        .login-card .logo-circle {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background-color: #E6F1FB;
        }

        .login-card .logo-circle img {
            width: 150px;
        }

        .login-slogan {
            margin-top: 2rem;
            width: 100%;
            max-width: 720px;
            text-align: center;
            color: #fff;
        }

        .login-slogan i,
        .login-slogan span {
            color: #fff;
        }

        .login-slogan .bi--heart-pulse-fill {
            filter: brightness(0) invert(1);
            vertical-align: middle;
            position: relative;
            top: 1px;
        }

        .login-slogan h2 {
            margin: 0;
            font-weight: 600;
            font-size: clamp(1.1rem, 3vw, 2rem);
            white-space: nowrap;
        }

        
        @media (max-width: 480px) {
            .login-slogan h2 {
                white-space: normal;
                font-size: 1.4rem;
            }
        }

        .btn-primary-dark {
            background-color: #13395E;
            border-color: #13395E;
        }

        .btn-primary-dark:hover,
        .btn-primary-dark:focus {
            background-color: #0C2B47;
            border-color: #0C2B47;
        }
    </style>
@endpush

@section('content')
    <div class="login-shell">

        <img src="{{ asset('img/fondo-blur.jpg') }}" class="login-bg" alt="">


        <div class="login-content">

            <div class="login-card">

                <div class="text-center mb-4">
                    <div class="logo-circle d-inline-flex align-items-center justify-content-center mb-3">
                        <img src="{{ asset('img/logo-petscare.png') }}" alt="Pets Care" class="img-fluid">
                    </div>
                    <h1 class="h4 mb-1">Inicia sesión</h1>
                    <p class="text-muted small mb-0">Ingresa tus datos para continuar</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                <i class="fas fa-at text-primary"></i>
                            </span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                placeholder="Correo electrónico" aria-label="Correo electrónico" value="{{ old('email') }}"
                                id="email" name="email" autocomplete="email" required autofocus
                                aria-describedby="basic-addon1">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                <i class="fas fa-lock text-primary"></i>
                            </span>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                placeholder="Contraseña" aria-label="Contraseña" value="" id="password"
                                name="password" autocomplete="current-password" required aria-describedby="basic-addon1">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label small" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="small" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div> --}}

                    <button type="submit" class="btn btn-primary-dark w-100 shadow-sm">
                        {{ __('Login') }}
                    </button>
                </form>

                {{-- <p class="text-center text-muted small mt-4 mb-0">
                    &copy; {{ date('Y') }} Pets Care
                </p> --}}

            </div>

            <div class="login-slogan">
                <h2>
                    El amor . . . nuestra mejor medicina
                    <span class="bi--heart-pulse-fill"></span>
                </h2>
            </div>

        </div>
    </div>
@endsection
