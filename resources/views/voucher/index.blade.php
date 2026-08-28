@extends('layouts.app')

@section('template_title')
    Voucher
@endsection

@section('vouchers', 'active border-start border-3 border-primary')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">

                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="heroicons-outline--ticket"></span> Vales
                            </h4>

                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover responsive w-100" id="table">
                                        <thead class="thead table-primary text-uppercase">
                                            <tr>
                                                <th></th>
                                                <th>Folio</th>
                                                <th>Fecha</th>
                                                <th>Solicitante</th>
                                                <th>Mascota</th>
                                                <th>Insumo</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const esAlmacenista = @json(auth()->user()->user === 'almacenista');
    </script>
    <script src="{{ asset('js/vouchers/index.js') }}" defer></script>
    <script src="{{ asset('js/vouchers/sign-modal.js') }}" defer></script>
@endpush

@push('modals')
    @include('voucher.partials.sign-modal')
@endpush
