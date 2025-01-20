@extends('layouts.app')

@section('template_title')
    {{ $surgerySchedule->name ?? __('Show') . " " . __('Surgery Schedule') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Surgery Schedule</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('surgery-schedules.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Family Id:</strong>
                            {{ $surgerySchedule->family_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Pet Id:</strong>
                            {{ $surgerySchedule->pet_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Surgical Procedures Type Id:</strong>
                            {{ $surgerySchedule->surgical_procedures_type_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Day:</strong>
                            {{ $surgerySchedule->day }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Hour:</strong>
                            {{ $surgerySchedule->hour }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Veterinarian Id:</strong>
                            {{ $surgerySchedule->veterinarian_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Status Surgery Id:</strong>
                            {{ $surgerySchedule->status_surgery_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
