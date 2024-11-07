@extends('layouts.app')

@section('template_title')
    {{ $followUp->name ?? __('Show') . " " . __('Follow Up') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Follow Up</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('follow-ups.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Reception Id:</strong>
                            {{ $followUp->reception_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Time:</strong>
                            {{ $followUp->time }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Details:</strong>
                            {{ $followUp->details }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Temperature:</strong>
                            {{ $followUp->temperature }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Systolic:</strong>
                            {{ $followUp->systolic }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Diastolic:</strong>
                            {{ $followUp->diastolic }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Average:</strong>
                            {{ $followUp->average }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Glycemia Level:</strong>
                            {{ $followUp->glycemia_level }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Vet Id:</strong>
                            {{ $followUp->vet_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
