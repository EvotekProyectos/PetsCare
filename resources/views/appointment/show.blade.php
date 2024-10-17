@extends('layouts.app')

@section('template_title')
    {{ $appointment->name ?? __('Show') . " " . __('Appointment') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Appointment</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('appointments.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        {{-- <div class="form-group mb-2 mb20">
                            <strong>Reception Id:</strong>
                            {{ $appointment->reception_id }}
                        </div> --}}


                        <div class="form-group mb-2 mb20">
                            <strong>Anamnesis:</strong>
                            {{ $appointment->anamnesis }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Exam Details:</strong>
                            {{ $appointment->exam_details }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Diagnosis:</strong>
                            {{ $appointment->diagnosis }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Observations:</strong>
                            {{ $appointment->observations }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Day Next Check:</strong>
                            {{ $appointment->day_next_check }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Time Next Check:</strong>
                            {{ $appointment->time_next_check }}
                        </div>
                        {{-- <div class="form-group mb-2 mb20">
                            <strong>Reason Next Check Id:</strong>
                            {{ $appointment->reason_next_check_id }}
                        </div> --}}

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
