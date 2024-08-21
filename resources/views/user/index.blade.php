@extends('layouts.app')

@section('template_title')
    User
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">

                            <h4 id="card_title" class="text-primary text-uppercase">
                                <i class="fas fa-user"></i> Usuarios
                            </h4>

                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm rounded-4" data-placement="left">
                                <i class="fas fa-plus"></i> CREAR NUEVO USUARIO
                            </a>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <p>{{ $message }}</p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="row">

                            <div class="col-12 col-lg-9">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover responsive w-100" id="table">
                                        <thead class="thead table-primary text-uppercase">
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Correo</th>
                                                <th>Rol</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @can('ver permisos usuarios')
                                <div class="col-md-3 rounded-4 border border-4 border-primary-subtle p-3 text-center">
                                    <p id="textUserName" class="mb-0 text-black text-secondary fw-semibold">Nombre</p>
                                    <p class="mb-0 text-black">Cuenta con los siguientes permisos de acuerdo a su tipo de
                                        usuario:</p>
                                    <p id="textUserType" class="text-black fw-semibold">TIPO USUARIO</p>
                                    <form id="formPermissionCheck" action="" method="POST">
                                        {{-- {{ method_field('PATCH') }} --}}
                                        @csrf
                                        @foreach ($permisos as $permiso)
                                            <div class="form-check text-start">
                                                <input class="form-check-input" type="checkbox" value="{{ $permiso->name }}"
                                                    id="permissionCheck{{ $permiso->id }}" name="permissions[]">

                                                <label class="form-check-label text-capitalize"
                                                    for="permissionCheck{{ $permiso->id }}">
                                                    {{ $permiso->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                        <div class="box-footer mt-3">
                                            <button type="button" id="btnChangePermissions" class="btn btn-primary"
                                                @cannot('editar permisos usuarios') hidden @endcan>{{ __('Actualizar permisos') }}</button>
                                        </div>
                                    </form>
                                </div>
                            @endcan
                        </div>
 
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/users/index.js') }}" defer></script>
@endpush
