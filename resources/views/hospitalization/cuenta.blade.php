@extends('layouts.app')

@section('template_title')
    Format
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="fluent--document-28-filled"></span> CUENTA
                            </h4>
                            {{-- <div class="float-right">
                                <a href="{{ route('formats.add', $pet->id) }}" class="btn btn-primary btn-sm float-right" data-placement="left">
                                    <i class="fas fa-plus"></i> AGREGAR NUEVO FORMATO
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

        <div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto / Servicio</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($redSheets as $redSheet)
                        @if ($redSheet->service)
                            <tr>
                                <td>{{ $redSheet->service->name }}</td>
                                <td>${{ number_format($redSheet->service->price, 2) }}</td>
                            </tr>
                        @endif
                        @if ($redSheet->lab)
                            <tr>
                                <td>{{ $redSheet->lab->name }}</td>
                                <td>${{ number_format($redSheet->lab->price, 2) }}</td>
                            </tr>
                        @endif
                    @endforeach

                    @foreach ($surgeries as $surgery)
                        @if ($surgery->service)
                            <tr>
                                <td>{{ $surgery->service->name }}</td>
                                <td>${{ number_format($surgery->service->price, 2) }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>
            <h3>Total: ${{ number_format($total, 2) }}</h3>
        </div>
    </div>
@endsection

{{-- @push('scripts') 
<script> var receptionId = {{ $reception->id }};
 </script> <script src="{{ asset('js/hospitalization/cuenta.js') }}" defer>
 </script>
  @endpush --}}
