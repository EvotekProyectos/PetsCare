@extends('layouts.app')

@section('template_title')
    {{ $advancePayment->name ?? __('Show') . " " . __('Advance Payment') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Advance Payment</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('advance-payments.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Reception Id:</strong>
                            {{ $advancePayment->reception_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Reference:</strong>
                            {{ $advancePayment->reference }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Concept:</strong>
                            {{ $advancePayment->concept }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Date:</strong>
                            {{ $advancePayment->date }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Amount:</strong>
                            {{ $advancePayment->amount }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>User Id:</strong>
                            {{ $advancePayment->user_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Status:</strong>
                            {{ $advancePayment->status }}
                        </div>

                        {!! DNS1D::getBarcodeHTML($advancePayment->reference, 'C128', 2, 60) !!}

                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        window.open("{{ route('advance-payments.pdf', $advancePayment->id) }}", "_blank");
        window.location.href = "{{ route('advance-payments.index') }}";
    </script>
    
@endsection
