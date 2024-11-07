@extends('layouts.app')

@section('template_title')
    Hospitalization
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Hospitalization') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('hospitalizations.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
										<th>Reason</th>
										<th>Total Days</th>
										<th>Total Payment</th>
										<th>Already Paid</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hospitalizations as $hospitalization)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $hospitalization->reception_id }}</td>
											<td>{{ $hospitalization->reason }}</td>
											<td>{{ $hospitalization->total_days }}</td>
											<td>{{ $hospitalization->total_payment }}</td>
											<td>{{ $hospitalization->already_paid }}</td>

                                            <td>
                                                <form action="{{ route('hospitalizations.destroy',$hospitalization->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('hospitalizations.show',$hospitalization->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('hospitalizations.edit',$hospitalization->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $hospitalizations->links() !!}
            </div>
        </div>
    </div>
@endsection
