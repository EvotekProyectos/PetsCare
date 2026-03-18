@extends('layouts.app')

@section('template_title')
    {{ $voucherProduct->name ?? __('Show') . " " . __('Voucher Product') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Voucher Product</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('voucher-products.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Voucher Id:</strong>
                            {{ $voucherProduct->voucher_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Product Id:</strong>
                            {{ $voucherProduct->product_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Requested Quantity:</strong>
                            {{ $voucherProduct->requested_quantity }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Unit Of Measure:</strong>
                            {{ $voucherProduct->unit_of_measure }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
