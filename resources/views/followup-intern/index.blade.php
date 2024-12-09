@extends('layouts.app')

@section('template_title')
    Followup Intern
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Followup Intern') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('followup-interns.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
										<th>Alterations</th>
										<th>Which Alterations</th>
										<th>Therapeutic</th>
										<th>Which Therapeutic</th>
										<th>Vomiting</th>
										<th>Quantity Vomiting</th>
										<th>Defecation</th>
										<th>Quantity Defecation</th>
										<th>Urine</th>
										<th>Quantity Urine</th>
										<th>Feeding</th>
										<th>Type Feeding</th>
										<th>Pendings</th>
										<th>Ultrasounds</th>
										<th>Observations Ultrasounds</th>
										<th>Observations</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($followupInterns as $followupIntern)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $followupIntern->reception_id }}</td>
											<td>{{ $followupIntern->date }}</td>
											<td>{{ $followupIntern->alterations }}</td>
											<td>{{ $followupIntern->which_alterations }}</td>
											<td>{{ $followupIntern->therapeutic }}</td>
											<td>{{ $followupIntern->which_therapeutic }}</td>
											<td>{{ $followupIntern->vomiting }}</td>
											<td>{{ $followupIntern->quantity_vomiting }}</td>
											<td>{{ $followupIntern->defecation }}</td>
											<td>{{ $followupIntern->quantity_defecation }}</td>
											<td>{{ $followupIntern->urine }}</td>
											<td>{{ $followupIntern->quantity_urine }}</td>
											<td>{{ $followupIntern->feeding }}</td>
											<td>{{ $followupIntern->type_feeding }}</td>
											<td>{{ $followupIntern->pendings }}</td>
											<td>{{ $followupIntern->ultrasounds }}</td>
											<td>{{ $followupIntern->observations_ultrasounds }}</td>
											<td>{{ $followupIntern->observations }}</td>

                                            <td>
                                                <form action="{{ route('followup-interns.destroy',$followupIntern->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('followup-interns.show',$followupIntern->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('followup-interns.edit',$followupIntern->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $followupInterns->links() !!}
            </div>
        </div>
    </div>
@endsection
