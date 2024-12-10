@extends('layouts.app')

@section('template_title')
    {{ $surgeryPack->name ?? __('Show') . " " . __('Surgery Pack') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Surgery Pack</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('surgery-packs.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Name:</strong>
                            {{ $surgeryPack->name }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Total:</strong>
                            {{ $surgeryPack->total }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Catheterization:</strong>
                            {{ $surgeryPack->catheterization }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Catheterization Price:</strong>
                            {{ $surgeryPack->catheterization_price }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Preanesthetic:</strong>
                            {{ $surgeryPack->preanesthetic }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Preanesthetic Price:</strong>
                            {{ $surgeryPack->preanesthetic_price }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Monitoring:</strong>
                            {{ $surgeryPack->monitoring }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Monitoring Price:</strong>
                            {{ $surgeryPack->monitoring_price }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Surgical Clothing:</strong>
                            {{ $surgeryPack->surgical_clothing }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Surgical Clothing Price:</strong>
                            {{ $surgeryPack->surgical_clothing_price }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Preparations:</strong>
                            {{ $surgeryPack->preparations }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Preparations Price:</strong>
                            {{ $surgeryPack->preparations_price }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Observation:</strong>
                            {{ $surgeryPack->observation }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Observation Price:</strong>
                            {{ $surgeryPack->observation_price }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
