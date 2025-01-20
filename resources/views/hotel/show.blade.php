@extends('layouts.app')

@section('template_title')
    {{ $hotel->name ?? __('Show') . " " . __('Hotel') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Hotel</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('hotels.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Reception Id:</strong>
                            {{ $hotel->reception_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Vaccine Certificate Id:</strong>
                            {{ $hotel->vaccine_certificate_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Food:</strong>
                            {{ $hotel->food }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Objects:</strong>
                            {{ $hotel->objects }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Observations:</strong>
                            {{ $hotel->observations }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Number Days:</strong>
                            {{ $hotel->number_days }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Service Type Id:</strong>
                            {{ $hotel->service_type_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Cubicle Id:</strong>
                            {{ $hotel->cubicle_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
