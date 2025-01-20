@extends('layouts.app')

@section('template_title')
    Cubicle
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">

                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="game-icons--dog-house" style="font-size: 18px;"></span>
                                 CUBÍCULOS
                            </h4>

                            <div class="float-right">
                                <a href="{{ route('cubicles.create') }}" class="btn btn-primary btn-sm rounded-4"  
                                data-placement="left">
                                <i class="fas fa-plus"></i> CREAR NUEVO CUBÍCULO
                                </a>
                              </div>
                        </div>
                    </div>

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover responsive w-100" id="table">
                                <thead class="thead table-primary text-uppercase">
                                    <tr>
                                        {{-- <th>No</th> --}}
                                        
										<th>NOMBRE</th>
										<th>TIPO DE PENSIÓN</th>
										<th>ESTADO</th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    {{-- @foreach ($cubicles as $cubicle)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $cubicle->name }}</td>
											<td>{{ $cubicle->cubicle_type_id }}</td>
											<td>{{ $cubicle->state }}</td>

                                            <td>
                                                <form action="{{ route('cubicles.destroy',$cubicle->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('cubicles.show',$cubicle->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('cubicles.edit',$cubicle->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('js/cubicles/index.js')}}" defer></script>
@endpush
