@extends('layouts.app')

@section('template_title')
    Follow Up
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Follow Up') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('follow-ups.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Reception Id</th>
										<th>Time</th>
										<th>Details</th>
										<th>Temperature</th>
										<th>Systolic</th>
										<th>Diastolic</th>
										<th>Average</th>
										<th>Glycemia Level</th>
										<th>Vet Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($followUps as $followUp)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $followUp->reception_id }}</td>
											<td>{{ $followUp->time }}</td>
											<td>{{ $followUp->details }}</td>
											<td>{{ $followUp->temperature }}</td>
											<td>{{ $followUp->systolic }}</td>
											<td>{{ $followUp->diastolic }}</td>
											<td>{{ $followUp->average }}</td>
											<td>{{ $followUp->glycemia_level }}</td>
											<td>{{ $followUp->vet_id }}</td>

                                            <td>
                                                <form action="{{ route('follow-ups.destroy',$followUp->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('follow-ups.show',$followUp->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('follow-ups.edit',$followUp->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                    </div>
                </div>
                {!! $followUps->links() !!}
            </div>
        </div>
    </div>
@endsection
