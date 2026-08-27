@extends('layouts.app')

@section('template_title')
    Hotel
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/receptions/index.css') }}">
@endpush

@section('hotel', 'active border-start border-3 border-primary')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">

               <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 id="card_title" class="text-primary text-uppercase fw-bold mb-0">
                             <i class="fas fa-bed"></i>
                                     HOTEL
                            </h3>

                             {{-- <div class="float-right">
                                <a href="{{ route('hotels.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                              </div> --}}
                        </div>
                    </div>

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <p>{{ $message }}</p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="reception-card">
                                    <div class="tab-content p-3" id="receptionTabsContent">
                                        <div class="tab-pane fade show active card-panel" id="tab-hotel"
                                            role="tabpanel" aria-labelledby="tab-hotel-tab">
                                            <div class="row g-2 mb-2 align-items-end">
                                                <div class="col">
                                                    <label for="filterHotelFecha" class="form-label mb-1">Fecha</label>
                                                    <input type="date" id="filterHotelFecha"
                                                        class="form-control form-control-sm" value="">
                                                </div>
                                                <div class="col">
                                                    <label for="filterHotelEstado" class="form-label mb-1">Estado</label>
                                                    <select id="filterHotelEstado" class="form-control form-control-sm">
                                                        <option value="">Todos</option>
                                                        @foreach ($hotelStatuses as $hotelStatus)
                                                            <option value="{{ $hotelStatus->id }}">{{ $hotelStatus->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-auto">
                                                    <button type="button" id="btnClearHotelFilters"
                                                        class="btn btn-outline-secondary btn-sm btn-clear-filters"
                                                        title="Limpiar filtros" aria-label="Limpiar filtros">
                                                        <i class="fas fa-eraser"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-hover table-flat-rows responsive w-100" id="table">
                                                    <thead class="thead table-header-solid text-uppercase">
                                                    <tr>

                                                    <th>No. Collar</th>
                                                    <th>mascota</th>
                                                    <th>Raza</th>
                                                    <th>Familia</th>
                                                    <th>Pensión</th>
                                                    <th>No. Cubículo</th>
                                                    <th>Video</th>
                                                    <th>Fecha de entrada</th>
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
                            </div>
                        </div>
                    </div>
                </div>
                {{-- {!! $hotels->links() !!} --}}
            </div>
        </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('js/hotels/index.js')}}" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

@endpush
