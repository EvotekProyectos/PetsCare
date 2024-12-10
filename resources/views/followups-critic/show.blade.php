@extends('layouts.app')

@section('template_title')
    {{ $followupsCritic->name ?? __('Show') . " " . __('Followups Critic') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Followups Critic</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('followups-critics.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Reception Id:</strong>
                            {{ $followupsCritic->reception_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Pet Status:</strong>
                            {{ $followupsCritic->pet_status }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Preasure:</strong>
                            {{ $followupsCritic->preasure }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Temperature:</strong>
                            {{ $followupsCritic->temperature }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Glycemia:</strong>
                            {{ $followupsCritic->glycemia }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Throwup:</strong>
                            {{ $followupsCritic->throwup }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Throwup Detail:</strong>
                            {{ $followupsCritic->throwup_detail }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Defecate:</strong>
                            {{ $followupsCritic->defecate }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Defecate Detail:</strong>
                            {{ $followupsCritic->defecate_detail }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Orino:</strong>
                            {{ $followupsCritic->orino }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Orino Detail:</strong>
                            {{ $followupsCritic->orino_detail }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Eat:</strong>
                            {{ $followupsCritic->eat }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Eat Detail:</strong>
                            {{ $followupsCritic->eat_detail }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Infusions:</strong>
                            {{ $followupsCritic->infusions }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Infusions Detail:</strong>
                            {{ $followupsCritic->infusions_detail }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Terapeutic:</strong>
                            {{ $followupsCritic->terapeutic }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Terapeutic Detail:</strong>
                            {{ $followupsCritic->terapeutic_detail }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Imaging:</strong>
                            {{ $followupsCritic->imaging }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Imaging Detail:</strong>
                            {{ $followupsCritic->imaging_detail }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Pends:</strong>
                            {{ $followupsCritic->pends }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Vet Id:</strong>
                            {{ $followupsCritic->vet_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
