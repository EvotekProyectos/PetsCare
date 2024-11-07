@extends('layouts.app')

@section('template_title')
    Red Sheet
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Red Sheet') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('red-sheets.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
										<th>Lab Type Id</th>
										<th>Imaging Type Id</th>
										<th>Service Type Id</th>
										<th>Observations</th>
										<th>Day Count</th>
										<th>Vet Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($redSheets as $redSheet)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $redSheet->reception_id }}</td>
											<td>{{ $redSheet->lab_type_id }}</td>
											<td>{{ $redSheet->imaging_type_id }}</td>
											<td>{{ $redSheet->service_type_id }}</td>
											<td>{{ $redSheet->observations }}</td>
											<td>{{ $redSheet->day_count }}</td>
											<td>{{ $redSheet->vet_id }}</td>

                                            <td>
                                                <form action="{{ route('red-sheets.destroy',$redSheet->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('red-sheets.show',$redSheet->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('red-sheets.edit',$redSheet->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $redSheets->links() !!}
            </div>
        </div>
    </div>
@endsection
