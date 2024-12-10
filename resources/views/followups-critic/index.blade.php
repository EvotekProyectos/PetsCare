@extends('layouts.app')

@section('template_title')
    Followups Critic
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Followups Critic') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('followups-critics.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
										<th>Pet Status</th>
										<th>Preasure</th>
										<th>Temperature</th>
										<th>Glycemia</th>
										<th>Throwup</th>
										<th>Throwup Detail</th>
										<th>Defecate</th>
										<th>Defecate Detail</th>
										<th>Orino</th>
										<th>Orino Detail</th>
										<th>Eat</th>
										<th>Eat Detail</th>
										<th>Infusions</th>
										<th>Infusions Detail</th>
										<th>Terapeutic</th>
										<th>Terapeutic Detail</th>
										<th>Imaging</th>
										<th>Imaging Detail</th>
										<th>Pends</th>
										<th>Vet Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($followupsCritics as $followupsCritic)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $followupsCritic->reception_id }}</td>
											<td>{{ $followupsCritic->pet_status }}</td>
											<td>{{ $followupsCritic->preasure }}</td>
											<td>{{ $followupsCritic->temperature }}</td>
											<td>{{ $followupsCritic->glycemia }}</td>
											<td>{{ $followupsCritic->throwup }}</td>
											<td>{{ $followupsCritic->throwup_detail }}</td>
											<td>{{ $followupsCritic->defecate }}</td>
											<td>{{ $followupsCritic->defecate_detail }}</td>
											<td>{{ $followupsCritic->orino }}</td>
											<td>{{ $followupsCritic->orino_detail }}</td>
											<td>{{ $followupsCritic->eat }}</td>
											<td>{{ $followupsCritic->eat_detail }}</td>
											<td>{{ $followupsCritic->infusions }}</td>
											<td>{{ $followupsCritic->infusions_detail }}</td>
											<td>{{ $followupsCritic->terapeutic }}</td>
											<td>{{ $followupsCritic->terapeutic_detail }}</td>
											<td>{{ $followupsCritic->imaging }}</td>
											<td>{{ $followupsCritic->imaging_detail }}</td>
											<td>{{ $followupsCritic->pends }}</td>
											<td>{{ $followupsCritic->vet_id }}</td>

                                            <td>
                                                <form action="{{ route('followups-critics.destroy',$followupsCritic->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('followups-critics.show',$followupsCritic->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('followups-critics.edit',$followupsCritic->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $followupsCritics->links() !!}
            </div>
        </div>
    </div>
@endsection
