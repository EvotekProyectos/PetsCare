@extends('layouts.app')

@section('template_title')
    {{ $pet->name ?? __('Show') . " " . __('Pet') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Pet</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('pets.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Family Id:</strong>
                            {{ $pet->family_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Name:</strong>
                            {{ $pet->name }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Picture Id:</strong>
                            {{ $pet->picture_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Specie:</strong>
                            {{ $pet->specie }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Raza:</strong>
                            {{ $pet->raza }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Gender Id:</strong>
                            {{ $pet->gender_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Birthday:</strong>
                            {{ $pet->birthday }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Reproductive Status Id:</strong>
                            {{ $pet->reproductive_status_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Weight:</strong>
                            {{ $pet->weight }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Physic Descrip:</strong>
                            {{ $pet->physic_descrip }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Notes:</strong>
                            {{ $pet->notes }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Pet Classification Id:</strong>
                            {{ $pet->pet_classification_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Deceased:</strong>
                            {{ $pet->deceased }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
