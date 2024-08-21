@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <img src="{{ asset('img/veterinary-bro.png') }}" alt="" class="img-fluid">
            </div>
            <div class="col-md-6 d-flex justify-content-center align-items-center">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">

                        <h4 id="card_title" class="text-primary text-uppercase">
                            <i class="fas fa-user"></i> {{ __('Reset Password') }}
                        </h4>
                    </div>

                    <div class="card-body">
                        
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="row d-flex justify-content-center">
                                <label for="email"
                                    class="col-12 col-form-label">{{ __('Correo electrónico') }}</label>

                                <div class="col-12">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary-subtle" id="basic-addon1"><i class="fas fa-at text-primary"></i></span>
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Correo elecrónico">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-12  d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Send Password Reset Link') }}
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
