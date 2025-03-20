@extends('layouts.app')

@section('template_title')
    General Grooming
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('General Grooming') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('general-groomings.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
										<th>Instructions</th>
										<th>Next Service</th>
										<th>Critic Status</th>
										<th>Delivery Service</th>
										<th>Delivery References</th>
										<th>Folio</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($generalGroomings as $generalGrooming)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $generalGrooming->reception_id }}</td>
											<td>{{ $generalGrooming->instructions }}</td>
											<td>{{ $generalGrooming->next_service }}</td>
											<td>{{ $generalGrooming->critic_status }}</td>
											<td>{{ $generalGrooming->delivery_service }}</td>
											<td>{{ $generalGrooming->delivery_references }}</td>
											<td>{{ $generalGrooming->folio }}</td>

                                            <td>
                                                <form action="{{ route('general-groomings.destroy',$generalGrooming->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('general-groomings.show',$generalGrooming->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('general-groomings.edit',$generalGrooming->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $generalGroomings->links() !!}
            </div>
        </div>
    </div>
@endsection
