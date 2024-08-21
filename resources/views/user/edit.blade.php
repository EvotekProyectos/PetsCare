@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-6">
                <div class="card bg-primary-soft border-0 p-3 h-100">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <i class="fas fa-user"></i> Actualizar datos de Usuario
                            </h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <form method="POST" action="#" role="form"
                                    enctype="multipart/form-data">
                                    {{-- {{ method_field('PATCH') }}
                                    @csrf --}}

                                    @include('user.form')

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card bg-primary-soft border-0 p-3 h-100">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <i class="fas fa-user-lock"></i> Seguridad
                            </h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <form method="POST" action="{{ route('users.update', $user->id) }}" role="form"
                                    enctype="multipart/form-data">
                                    {{ method_field('PATCH') }}
                                    @csrf
                                    @include('user.password')
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
