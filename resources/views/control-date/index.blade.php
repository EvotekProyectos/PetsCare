@extends('layouts.app')

@section('template_title')
    Control Date
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 id="card_title" class="text-primary text-uppercase">
                                    <i class="fa fa-calendar-check"></i>próximas citas
                                </h4>

        
                             <div class="float-right">
                                <a href="{{ route('control-dates.create') }}"class="btn btn-primary btn-sm rounded-4">
                                    <i class="fas fa-plus"></i> CREAR NUEVA CITA
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
                                        {{-- <th>No</th>
                                        
										<th>Reception Id</th> --}}
										<th>Familia</th>
										<th>Mascota</th>
										<th>tipo de cita</th>
										<th>Fecha</th>
                                        <th>Estado</th>
										{{-- <th>User Id</th> --}}

                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($controlDates as $controlDate)
                                        <tr>
                                            {{-- <td>{{ ++$i }}</td>
                                            
											<td>{{ $controlDate->reception_id }}</td> 
											<td>{{ $controlDate->family_id }}</td>
											<td>{{ $controlDate->pet_id }}</td>
											<td>{{ $controlDate->date_type_id }}</td>
											
											<td>{{ $controlDate->date }}</td>
                                            <td>{{ $controlDate->status_date_id }}</td>
											{{-- <td>{{ $controlDate->user_id }}</td> 

                                            <td>
                                                <form action="{{ route('control-dates.destroy',$controlDate->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('control-dates.show',$controlDate->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('control-dates.edit',$controlDate->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {{-- {!! $controlDates->links() !!} --}}
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('js/control_dates/create.js') }}" defer></script>
@endpush
