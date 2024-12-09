@extends('layouts.app')

@section('template_title')
    {{ $followupIntern->name ?? __('Show') . " " . __('Followup Intern') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Followup Intern</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('followup-interns.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Reception Id:</strong>
                            {{ $followupIntern->reception_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Date:</strong>
                            {{ $followupIntern->date }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Alterations:</strong>
                            {{ $followupIntern->alterations }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Which Alterations:</strong>
                            {{ $followupIntern->which_alterations }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Therapeutic:</strong>
                            {{ $followupIntern->therapeutic }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Which Therapeutic:</strong>
                            {{ $followupIntern->which_therapeutic }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Vomiting:</strong>
                            {{ $followupIntern->vomiting }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Quantity Vomiting:</strong>
                            {{ $followupIntern->quantity_vomiting }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Defecation:</strong>
                            {{ $followupIntern->defecation }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Quantity Defecation:</strong>
                            {{ $followupIntern->quantity_defecation }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Urine:</strong>
                            {{ $followupIntern->urine }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Quantity Urine:</strong>
                            {{ $followupIntern->quantity_urine }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Feeding:</strong>
                            {{ $followupIntern->feeding }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Type Feeding:</strong>
                            {{ $followupIntern->type_feeding }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Pendings:</strong>
                            {{ $followupIntern->pendings }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Ultrasounds:</strong>
                            {{ $followupIntern->ultrasounds }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Observations Ultrasounds:</strong>
                            {{ $followupIntern->observations_ultrasounds }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Observations:</strong>
                            {{ $followupIntern->observations }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
