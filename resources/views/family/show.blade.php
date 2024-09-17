@extends('layouts.app')

@section('template_title')
    {{ $family->name ?? __('Show') . " " . __('Family') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Family</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('families.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Name:</strong>
                            {{ $family->name }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Phone:</strong>
                            {{ $family->phone }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Email:</strong>
                            {{ $family->email }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Address:</strong>
                            {{ $family->address }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Contact Name:</strong>
                            {{ $family->contact_name }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Contact Number:</strong>
                            {{ $family->contact_number }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Fam Classification Id:</strong>
                            {{ $family->fam_classification_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
