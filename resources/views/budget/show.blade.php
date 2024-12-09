@extends('layouts.app')

@section('template_title')
    {{ $budget->name ?? __('Show') . " " . __('Budget') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Budget</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('budgets.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Pet Id:</strong>
                            {{ $budget->pet_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Date:</strong>
                            {{ $budget->date }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Surgery Pack Id:</strong>
                            {{ $budget->surgery_pack_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Procedure:</strong>
                            {{ $budget->procedure }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Procedure Price:</strong>
                            {{ $budget->procedure_price }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Biometric:</strong>
                            {{ $budget->biometric }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Biometric Price:</strong>
                            {{ $budget->biometric_price }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Chemistry:</strong>
                            {{ $budget->chemistry }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Chemistry Price:</strong>
                            {{ $budget->chemistry_price }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Nodulectomy:</strong>
                            {{ $budget->nodulectomy }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Nodulectomy Price:</strong>
                            {{ $budget->nodulectomy_price }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Histopathology:</strong>
                            {{ $budget->histopathology }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Histopathology Price:</strong>
                            {{ $budget->histopathology_price }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Xrays:</strong>
                            {{ $budget->xrays }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Xrays Price:</strong>
                            {{ $budget->xrays_price }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Collar:</strong>
                            {{ $budget->collar }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Collar Price:</strong>
                            {{ $budget->collar_price }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Body:</strong>
                            {{ $budget->body }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Body Price:</strong>
                            {{ $budget->body_price }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Others:</strong>
                            {{ $budget->others }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Total:</strong>
                            {{ $budget->total }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Vet Id:</strong>
                            {{ $budget->vet_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
