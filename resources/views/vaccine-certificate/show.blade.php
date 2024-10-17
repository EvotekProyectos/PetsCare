@extends('layouts.app')

@section('template_title')
    {{ $vaccineCertificate->name ?? __('Show') . " " . __('Vaccine Certificate') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Vaccine Certificate</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('vaccine-certificates.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Pet Id:</strong>
                            {{ $vaccineCertificate->pet_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Service Id:</strong>
                            {{ $vaccineCertificate->service_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Vaccine:</strong>
                            {{ $vaccineCertificate->vaccine }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Lab:</strong>
                            {{ $vaccineCertificate->lab }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Lote:</strong>
                            {{ $vaccineCertificate->lote }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Application Date:</strong>
                            {{ $vaccineCertificate->application_date }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Next Vaccination Date:</strong>
                            {{ $vaccineCertificate->next_vaccination_date }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Observations Vaccine:</strong>
                            {{ $vaccineCertificate->observations_vaccine }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Product Internal:</strong>
                            {{ $vaccineCertificate->product_internal }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Dose Internal:</strong>
                            {{ $vaccineCertificate->dose_internal }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Last Deworming Internal:</strong>
                            {{ $vaccineCertificate->last_deworming_internal }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Next Internal Date:</strong>
                            {{ $vaccineCertificate->next_internal_date }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Observations Internal:</strong>
                            {{ $vaccineCertificate->observations_internal }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Product External:</strong>
                            {{ $vaccineCertificate->product_external }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Dose External:</strong>
                            {{ $vaccineCertificate->dose_external }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Last Deworming External:</strong>
                            {{ $vaccineCertificate->last_deworming_external }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Next External Date:</strong>
                            {{ $vaccineCertificate->next_external_date }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Observations External:</strong>
                            {{ $vaccineCertificate->observations_external }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
