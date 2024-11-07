@extends('layouts.app')

@section('template_title')
    Surgery
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Surgery') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('surgeries.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
										<th>Surgery Type Id</th>
										<th>Surgery Date</th>
										<th>Surgery Description</th>
										<th>Preanesthetic</th>
										<th>Anesthetic</th>
										<th>Other Medicines</th>
										<th>Treatment</th>
										<th>Observations</th>
										<th>Complications</th>
										<th>Vet Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($surgeries as $surgery)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $surgery->reception_id }}</td>
											<td>{{ $surgery->surgery_type_id }}</td>
											<td>{{ $surgery->surgery_date }}</td>
											<td>{{ $surgery->surgery_description }}</td>
											<td>{{ $surgery->preanesthetic }}</td>
											<td>{{ $surgery->anesthetic }}</td>
											<td>{{ $surgery->other_medicines }}</td>
											<td>{{ $surgery->treatment }}</td>
											<td>{{ $surgery->observations }}</td>
											<td>{{ $surgery->complications }}</td>
											<td>{{ $surgery->vet_id }}</td>

                                            <td>
                                                <form action="{{ route('surgeries.destroy',$surgery->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('surgeries.show',$surgery->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('surgeries.edit',$surgery->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    {{-- <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button> --}}
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $surgeries->links() !!}
            </div>
        </div>
    </div>
@endsection
