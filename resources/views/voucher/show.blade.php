@extends('layouts.app')

@section('template_title')
    {{ $voucher->name ?? __('Show') . " " . __('Voucher') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Voucher</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('vouchers.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Folio:</strong>
                            {{ $voucher->folio }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Status:</strong>
                            {{ $voucher->status }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Reception Id:</strong>
                            {{ $voucher->reception_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Vet Id:</strong>
                            {{ $voucher->vet_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Issuer Id:</strong>
                            {{ $voucher->issuer_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Issued At:</strong>
                            {{ $voucher->issued_at }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Generated Document:</strong>
                            {{ $voucher->generated_document }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Warehouse Observations:</strong>
                            {{ $voucher->warehouse_observations }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Cancellation Reason:</strong>
                            {{ $voucher->cancellation_reason }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Rejection Reason:</strong>
                            {{ $voucher->rejection_reason }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
