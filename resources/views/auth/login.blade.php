@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <img src="{{ asset('img/square.jpeg') }}" alt="" class="img-fluid">
            </div>
            <div class="col-md-6 d-flex justify-content-center align-items-center flex-column">

                <div class="row justify-content-center">
                    <div class="col-3">
                        <img src="{{ asset('img/logo-petscare.png') }}" class="img-fluid" alt="">
                    </div>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="row mb-0">
                        <label for="email" class="col-md-12 col-form-label">Correo electrónico</label>

                        <div class="col-12">
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                    <i class="fas fa-at text-primary"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Correo electrónico" aria-label="Correo electrónico"
                                    value="{{ old('email') }}" id="email" name="email" autocomplete="email" required
                                    autofocus aria-describedby="basic-addon1">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="row mb-0">
                        <label for="password" class="col-md-12 col-form-label">{{ __('Password') }}</label>

                        <div class="col-12">
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-primary-subtle" id="basic-addon1"><i
                                        class="fas fa-lock text-primary"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Contraseña" aria-label="Contraseña" value="" id="password"
                                    name="password" autocomplete="current-password" required
                                    aria-describedby="basic-addon1">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-5">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>


                        </div>
                        <div class="col-7 text-end">
                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary-dark w-100 shadow">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
        <div class="row ">
            <div class="col-12 d-flex justify-content-center align-items-center flex-column">
                <h1 class="text-primary ">El amor . . . nuestra mejor medicina <span class="bi--heart-pulse-fill"></span></h1>
            </div>
        </div>
    </div>
@endsection
