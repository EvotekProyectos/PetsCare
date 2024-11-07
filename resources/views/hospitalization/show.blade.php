@extends('layouts.app')

@section('template_title')
    {{ $hospitalization->name ?? __('Show') . " " . __('Hospitalization') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Hospitalization</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('hospitalizations.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Reception Id:</strong>
                            {{ $hospitalization->reception_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Reason:</strong>
                            {{ $hospitalization->reason }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Total Days:</strong>
                            {{ $hospitalization->total_days }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Total Payment:</strong>
                            {{ $hospitalization->total_payment }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Already Paid:</strong>
                            {{ $hospitalization->already_paid }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
