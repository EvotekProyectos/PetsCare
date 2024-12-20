@extends('layouts.app')

@section('template_title')
    Cm Type
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">

                            <h4 id="card_title" class="text-primary text-uppercase">
                                <i class="clarity--two-way-arrows-line"></i> Tipos de C.M
                            </h4>
                       

                    <div class="float-right">
                                <a href="{{ route('cm-types.create') }}" class="btn btn-primary btn-sm float-right" 
                                 data-placement="left">
                                 <i class="fas fa-plus"></i> CREAR NUEVO C.M
                                </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body ">
                        <div class="row">
                            <div class="col-12 col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover responsive w-100" id="table">
                                        <thead class="thead table-primary text-uppercase">
                                            <tr>
                                                <th>Nombre</th>

                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Name</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cmTypes as $cmType)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $cmType->name }}</td>

                                            <td>
                                                <form action="{{ route('cm-types.destroy',$cmType->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('cm-types.show',$cmType->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('cm-types.edit',$cmType->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>--}}
                </div>
                
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/cremations/cm.js') }}" defer></script>
@endpush
