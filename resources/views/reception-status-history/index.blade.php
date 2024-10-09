@extends('layouts.app')

@section('template_title')
    Reception Status History
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Reception Status History') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('reception-status-histories.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
										<th>Attention Status Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($receptionStatusHistories as $receptionStatusHistory)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $receptionStatusHistory->reception_id }}</td>
											<td>{{ $receptionStatusHistory->attention_status_id }}</td>

                                            <td>
                                                <form action="{{ route('reception-status-histories.destroy',$receptionStatusHistory->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('reception-status-histories.show',$receptionStatusHistory->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('reception-status-histories.edit',$receptionStatusHistory->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $receptionStatusHistories->links() !!}
            </div>
        </div>
    </div>
@endsection
