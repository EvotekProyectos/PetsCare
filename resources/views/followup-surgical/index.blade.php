@extends('layouts.app')

@section('template_title')
    Followup Surgical
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Followup Surgical') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('followup-surgicals.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
										<th>Cleaning</th>
										<th>Clean Observations</th>
										<th>Secretion</th>
										<th>Secretion Observations</th>
										<th>Drainage</th>
										<th>Quantity Drainage</th>
										<th>Blockedages</th>
										<th>Type Blocked</th>
										<th>Infusions</th>
										<th>Type Time Infusions</th>
										<th>Alterations Surgery</th>
										<th>Which Alterations Surgery</th>
										<th>Observations</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($followupSurgicals as $followupSurgical)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $followupSurgical->reception_id }}</td>
											<td>{{ $followupSurgical->date }}</td>
											<td>{{ $followupSurgical->alterations }}</td>
											<td>{{ $followupSurgical->which_alterations }}</td>
											<td>{{ $followupSurgical->therapeutic }}</td>
											<td>{{ $followupSurgical->which_therapeutic }}</td>
											<td>{{ $followupSurgical->vomiting }}</td>
											<td>{{ $followupSurgical->quantity_vomiting }}</td>
											<td>{{ $followupSurgical->defecation }}</td>
											<td>{{ $followupSurgical->quantity_defecation }}</td>
											<td>{{ $followupSurgical->urine }}</td>
											<td>{{ $followupSurgical->quantity_urine }}</td>
											<td>{{ $followupSurgical->feeding }}</td>
											<td>{{ $followupSurgical->type_feeding }}</td>
											<td>{{ $followupSurgical->pendings }}</td>
											<td>{{ $followupSurgical->cleaning }}</td>
											<td>{{ $followupSurgical->clean_observations }}</td>
											<td>{{ $followupSurgical->secretion }}</td>
											<td>{{ $followupSurgical->secretion_observations }}</td>
											<td>{{ $followupSurgical->drainage }}</td>
											<td>{{ $followupSurgical->quantity_drainage }}</td>
											<td>{{ $followupSurgical->blockedages }}</td>
											<td>{{ $followupSurgical->type_blocked }}</td>
											<td>{{ $followupSurgical->infusions }}</td>
											<td>{{ $followupSurgical->type_time_infusions }}</td>
											<td>{{ $followupSurgical->alterations_surgery }}</td>
											<td>{{ $followupSurgical->which_alterations_surgery }}</td>
											<td>{{ $followupSurgical->observations }}</td>

                                            <td>
                                                <form action="{{ route('followup-surgicals.destroy',$followupSurgical->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('followup-surgicals.show',$followupSurgical->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('followup-surgicals.edit',$followupSurgical->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $followupSurgicals->links() !!}
            </div>
        </div>
    </div>
@endsection
