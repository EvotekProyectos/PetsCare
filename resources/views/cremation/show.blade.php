@extends('layouts.app')

@section('template_title')
    {{ $cremation->name ?? __('Show') . " " . __('Cremation') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Cremation</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('cremations.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Reception Id:</strong>
                            {{ $cremation->reception_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Pet Id:</strong>
                            {{ $cremation->pet_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Date Death:</strong>
                            {{ $cremation->date_death }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Date Finish:</strong>
                            {{ $cremation->date_finish }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Servicie:</strong>
                            {{ $cremation->servicie }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Cm Id:</strong>
                            {{ $cremation->CM_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Type Urn:</strong>
                            {{ $cremation->type_urn }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Urn Model:</strong>
                            {{ $cremation->urn_model }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Observations:</strong>
                            {{ $cremation->observations }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Placa Type Id:</strong>
                            {{ $cremation->placa_type_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Price:</strong>
                            {{ $cremation->price }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
