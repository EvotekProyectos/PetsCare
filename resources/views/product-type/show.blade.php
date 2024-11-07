@extends('layouts.app')

@section('template_title')
    {{ $productType->name ?? __('Show') . " " . __('Product Type') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Product Type</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('product-types.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Product Classification Id:</strong>
                            {{ $productType->product_classification_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Microsip Id:</strong>
                            {{ $productType->microsip_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Name:</strong>
                            {{ $productType->name }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Price:</strong>
                            {{ $productType->price }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
