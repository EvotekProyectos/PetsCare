@extends('layouts.app')

@section('template_title')
    Hotel
@endsection

@section('hotel', 'active border-start border-3 border-primary') 

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">

               <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="icon-park-outline--hotel" style="font-size: 20px;"></span>
                                     HOTEL
                            </h4>

                             {{-- <div class="float-right">
                                <a href="{{ route('hotels.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                              </div> --}}
                        </div>
                    </div>

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover responsive w-100" id="table">
                                        <thead class="thead table-primary text-uppercase">
                                        <tr>

										<th>Fecha de entrada</th>
                                        <th>mascota</th>
										<th>No. Collar</th>
										<th>Pensión</th>
										<th>No. Cubículo</th>
                                        <th>Video</th>
                                        <th>Fecha de salida</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($hotels as $hotel)
                                        <tr>
                                           
											<td>{{ $hotel->reception->entry_date }}</td>
											<td>{{ $hotel->reception->pet->name }}</td>
											<td>{{ $hotel->reception->num }}</td>
											<td>{{ $hotel->service_type_id }}</td>
											<td>{{ $hotel->cubicle->name }}</td>
											<td>{{ $hotel->reception->exit_date }}</td>
	
                                            <td>
                                                <form action="{{ route('hotels.destroy',$hotel->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('hotels.show',$hotel->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('hotels.edit',$hotel->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- {!! $hotels->links() !!} --}}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('js/hotels/index.js')}}" defer></script>
@endpush
