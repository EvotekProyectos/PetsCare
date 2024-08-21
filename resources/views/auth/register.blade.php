@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-10 p-0 m-0">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <h4 id="card_title" class="text-primary text-uppercase">
                            <i class="fas fa-user"></i> {{ __('Register') }}
                        </h4>
                    </div>

                    <div class="card-body p-0">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="row d-flex justify-content-center">
                                <div class="col-12 col-lg-7"> 
                                    <label for="name" class="">{{ __('Name') }}</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary-subtle" id="basic-addon1"><i
                                                class="fas fa-user text-primary"></i></span>
                                        <input placeholder="Nombre" id="name" type="text"
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            value="{{ old('name') }}" required autocomplete="name" autofocus>

                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                               
                                <div class="col-12 col-lg-7">
                                     <label for="email"
                                    class="">Correo electrónico</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary-subtle" id="basic-addon1"><i class="fas fa-at text-primary"></i></span>
                                        <input placeholder="Correo electrónico" id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" required autocomplete="email">

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                               

                                <div class="col-12 col-lg-7">
                                     <label for="password" class="">{{ __('Password') }}</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary-subtle" id="basic-addon1"><i class="fas fa-lock  text-primary"></i></span>
                                        <input placeholder="Contraseña" id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required autocomplete="new-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                              

                                <div class="col-12 col-lg-7">  
                                    <label for="password-confirm"
                                    class="">{{ __('Confirm Password') }}</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary-subtle" id="basic-addon1"><i class="fas fa-lock  text-primary"></i></span>
                                        <input placeholder="Confirmar contraseña" id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation" required autocomplete="new-password"
                                            placeholder="Cofirmar">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-7 d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary rounded-4 btn-sm">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
