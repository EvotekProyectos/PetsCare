@extends('layouts.app')

@section('template_title')
    Role Has Permission
@endsection
@section('megamenu', 'active border-start border-3 border-primary') 
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0"">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <i class="fas fa-user-lock"></i>  Roles y permisos
                            </h4>
                            <div class="float-right">
                                <button type="submit" form="form" class="btn btn-primary btn-sm rounded-4"> <i class="fas fa-check"></i> Guardar permisos</button>
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <p class="fw-bold">{{ $message }}</p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                    <div class="card-body">
                        <div class="">
                            <form id="form" action="{{ route('role-has-permissions.store') }}" method="post">
                                @csrf
                                <table class="table responsive w-100 table-striped table-hover" id="table">
                                    <thead class="thead table-primary">
                                        <tr>
                                            <th>Permisos</th>
                                            <th class="d-none">Tipo</th>
                                            @foreach ($roles as $role)
                                                <th class="text-center text-capitalize ">{{ $role->name }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permisos as $permiso)
                                            <tr>
                                                <td>
                                                    <label class="form-check-label text-capitalize ">
                                                        {{ $permiso->name }}
                                                    </label>
                                                </td>
                                                <td class="d-none">
                                                    {{ $permiso->type }}
                                                </td>
                                                @foreach ($roles as $role)
                                                    <td>
                                                        <div class="text-center">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="{{ $permiso->name }}"
                                                                name="{{ $role->name }}[{{ $permiso->id }}][name]"
                                                                {{ $role->hasPermissionTo($permiso->name) ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/role-has-permissions/index.js') }}" defer></script>
@endpush
