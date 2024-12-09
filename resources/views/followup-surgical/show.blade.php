@extends('layouts.app')

@section('template_title')
    {{ $followupSurgical->name ?? __('Show') . " " . __('Followup Surgical') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Followup Surgical</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('followup-surgicals.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Reception Id:</strong>
                            {{ $followupSurgical->reception_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Date:</strong>
                            {{ $followupSurgical->date }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Alterations:</strong>
                            {{ $followupSurgical->alterations }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Which Alterations:</strong>
                            {{ $followupSurgical->which_alterations }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Therapeutic:</strong>
                            {{ $followupSurgical->therapeutic }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Which Therapeutic:</strong>
                            {{ $followupSurgical->which_therapeutic }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Vomiting:</strong>
                            {{ $followupSurgical->vomiting }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Quantity Vomiting:</strong>
                            {{ $followupSurgical->quantity_vomiting }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Defecation:</strong>
                            {{ $followupSurgical->defecation }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Quantity Defecation:</strong>
                            {{ $followupSurgical->quantity_defecation }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Urine:</strong>
                            {{ $followupSurgical->urine }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Quantity Urine:</strong>
                            {{ $followupSurgical->quantity_urine }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Feeding:</strong>
                            {{ $followupSurgical->feeding }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Type Feeding:</strong>
                            {{ $followupSurgical->type_feeding }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Pendings:</strong>
                            {{ $followupSurgical->pendings }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Cleaning:</strong>
                            {{ $followupSurgical->cleaning }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Clean Observations:</strong>
                            {{ $followupSurgical->clean_observations }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Secretion:</strong>
                            {{ $followupSurgical->secretion }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Secretion Observations:</strong>
                            {{ $followupSurgical->secretion_observations }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Drainage:</strong>
                            {{ $followupSurgical->drainage }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Quantity Drainage:</strong>
                            {{ $followupSurgical->quantity_drainage }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Blockedages:</strong>
                            {{ $followupSurgical->blockedages }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Type Blocked:</strong>
                            {{ $followupSurgical->type_blocked }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Infusions:</strong>
                            {{ $followupSurgical->infusions }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Type Time Infusions:</strong>
                            {{ $followupSurgical->type_time_infusions }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Alterations Surgery:</strong>
                            {{ $followupSurgical->alterations_surgery }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Which Alterations Surgery:</strong>
                            {{ $followupSurgical->which_alterations_surgery }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Observations:</strong>
                            {{ $followupSurgical->observations }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
