@extends('layouts.app')

@section('template_title')
    {{ $appointmentService->name ?? __('Show') . " " . __('Appointment Service') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Appointment Service</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('appointment-services.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Reception Id:</strong>
                            {{ $appointmentService->reception_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Lab Type Id:</strong>
                            {{ $appointmentService->lab_type_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Imaging Type Id:</strong>
                            {{ $appointmentService->imaging_type_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Observations:</strong>
                            {{ $appointmentService->observations }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Vet Id:</strong>
                            {{ $appointmentService->vet_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
