@extends('layouts.app')

@section('template_title')
    Pet
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Pet') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('pets.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Family Id</th>
										<th>Name</th>
										<th>Picture Id</th>
										<th>Specie</th>
										<th>Raza</th>
										<th>Gender Id</th>
										<th>Birthday</th>
										<th>Reproductive Status Id</th>
										<th>Weight</th>
										<th>Physic Descrip</th>
										<th>Notes</th>
										<th>Pet Classification Id</th>
										<th>Deceased</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pets as $pet)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $pet->family_id }}</td>
											<td>{{ $pet->name }}</td>
											<td>{{ $pet->picture_id }}</td>
											<td>{{ $pet->specie }}</td>
											<td>{{ $pet->raza }}</td>
											<td>{{ $pet->gender_id }}</td>
											<td>{{ $pet->birthday }}</td>
											<td>{{ $pet->reproductive_status_id }}</td>
											<td>{{ $pet->weight }}</td>
											<td>{{ $pet->physic_descrip }}</td>
											<td>{{ $pet->notes }}</td>
											<td>{{ $pet->pet_classification_id }}</td>
											<td>{{ $pet->deceased }}</td>

                                            <td>
                                                <form action="{{ route('pets.destroy',$pet->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('pets.show',$pet->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('pets.edit',$pet->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $pets->links() !!}
            </div>
        </div>
    </div>
@endsection
