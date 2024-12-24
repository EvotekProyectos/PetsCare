@extends('layouts.app')

@section('template_title')
    Grooming Status History
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Grooming Status History') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('grooming-status-histories.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
										<th>Grooming Status Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($groomingStatusHistories as $groomingStatusHistory)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $groomingStatusHistory->reception_id }}</td>
											<td>{{ $groomingStatusHistory->grooming_status_id }}</td>

                                            <td>
                                                <form action="{{ route('grooming-status-histories.destroy',$groomingStatusHistory->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('grooming-status-histories.show',$groomingStatusHistory->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('grooming-status-histories.edit',$groomingStatusHistory->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $groomingStatusHistories->links() !!}
            </div>
        </div>
    </div>
@endsection
