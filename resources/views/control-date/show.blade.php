@extends('layouts.app')

@section('template_title')
    {{ $controlDate->name ?? __('Show') . " " . __('Control Date') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Control Date</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('control-dates.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Reception Id:</strong>
                            {{ $controlDate->reception_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Family Id:</strong>
                            {{ $controlDate->family_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Pet Id:</strong>
                            {{ $controlDate->pet_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Date Type Id:</strong>
                            {{ $controlDate->date_type_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Status Date Id:</strong>
                            {{ $controlDate->status_date_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Date:</strong>
                            {{ $controlDate->date }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>User Id:</strong>
                            {{ $controlDate->user_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
