@extends('layouts.app')

@section('template_title')
    Budget Detail
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Budget Detail') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('budget-details.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Budget Id</th>
										<th>Service Id</th>
										<th>Price</th>
										<th>Notes</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($budgetDetails as $budgetDetail)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $budgetDetail->budget_id }}</td>
											<td>{{ $budgetDetail->service_id }}</td>
											<td>{{ $budgetDetail->price }}</td>
											<td>{{ $budgetDetail->notes }}</td>

                                            <td>
                                                <form action="{{ route('budget-details.destroy',$budgetDetail->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('budget-details.show',$budgetDetail->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('budget-details.edit',$budgetDetail->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $budgetDetails->links() !!}
            </div>
        </div>
    </div>
@endsection
