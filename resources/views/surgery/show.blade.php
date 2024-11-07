@extends('layouts.app')

@section('template_title')
    {{ $surgery->name ?? __('Show') . " " . __('Surgery') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Surgery</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('surgeries.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Reception Id:</strong>
                            {{ $surgery->reception_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Surgery Type Id:</strong>
                            {{ $surgery->surgery_type_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Surgery Date:</strong>
                            {{ $surgery->surgery_date }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Surgery Description:</strong>
                            {{ $surgery->surgery_description }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Preanesthetic:</strong>
                            {{ $surgery->preanesthetic }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Anesthetic:</strong>
                            {{ $surgery->anesthetic }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Other Medicines:</strong>
                            {{ $surgery->other_medicines }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Treatment:</strong>
                            {{ $surgery->treatment }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Observations:</strong>
                            {{ $surgery->observations }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Complications:</strong>
                            {{ $surgery->complications }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Vet Id:</strong>
                            {{ $surgery->vet_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
