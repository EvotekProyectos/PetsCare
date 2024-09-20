@extends('layouts.app')

@section('template_title')
    {{ $reception->name ?? __('Show') . " " . __('Reception') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Reception</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('receptions.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Reception Type Id:</strong>
                            {{ $reception->reception_type_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Admission Type Id:</strong>
                            {{ $reception->admission_type_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Area Id:</strong>
                            {{ $reception->area_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Family Id:</strong>
                            {{ $reception->family_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Pet Id:</strong>
                            {{ $reception->pet_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Reason Id:</strong>
                            {{ $reception->reason_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Veterinarian Id:</strong>
                            {{ $reception->veterinarian_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Recepcionist Id:</strong>
                            {{ $reception->recepcionist_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Room Id:</strong>
                            {{ $reception->room_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Entry Date:</strong>
                            {{ $reception->entry_date }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Exit Date:</strong>
                            {{ $reception->exit_date }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
