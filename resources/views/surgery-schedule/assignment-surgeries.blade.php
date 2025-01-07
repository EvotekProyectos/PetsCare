@extends('layouts.app')

@section('template_title')
    Surgery Schedule
@endsection

@section('assignmentssurgery', 'active border-start border-3 border-primary') 

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">

                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="display: flex; justify-content: space-between; align-items: center;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 id="card_title" class="text-primary text-uppercase">
                                    <i class="fa fa-calendar-check"></i> ASIGNACIÓN DE CIRUGÍAS
                                </h4>

                             {{-- <div class="float-right">
                                <a href="{{ route('surgery-schedules.create') }}" class="btn btn-primary btn-sm rounded-4">
                                    <i class="fas fa-plus"></i> CREAR NUEVA ASIGNACIÓN
                                </a>
                              </div> --}}
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
                                        
										<th>Familia</th>
										<th>Mascota</th>
										<th>Procedimiento quirúrgico</th>
										<th>Día</th>
										<th>Hora</th>
										<th>M.V.Z</th>
                                        <th>Estado</th>
                                        <th>ACCIONES</th>
										{{-- <th>Status Surgery Id</th> --}}

                                        {{-- <th></th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($surgerySchedules as $surgerySchedule)
                                        <tr>
                                            {{-- <td>{{ ++$i }}</td> 
                                            
											<td>{{ $surgerySchedule->family_id }}</td>
											<td>{{ $surgerySchedule->pet_id }}</td>
											<td>{{ $surgerySchedule->surgical_procedures_type_id }}</td>
											<td>{{ $surgerySchedule->day }}</td>
											<td>{{ $surgerySchedule->hour }}</td>
											<td>{{ $surgerySchedule->veterinarian_id }}</td>
											{{-- <td>{{ $surgerySchedule->status_surgery_id }}</td> 

                                            <td>
                                                <form action="{{ route('surgery-schedules.destroy',$surgerySchedule->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('surgery-schedules.show',$surgerySchedule->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('surgery-schedules.edit',$surgerySchedule->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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

                    <div class="row">
                        <div class="col-12">
                            <div id='calendar'></div>
                        </div>
                    </div>

                </div>
               
                {{-- {!! $surgerySchedules->links() !!} --}}
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('js/surgery-schedules/assignment.js') }}" defer></script>
@endpush
