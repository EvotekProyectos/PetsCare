@extends('layouts.app')

@section('template_title')
    {{ $petsStatus->name ?? __('Show') . " " . __('Pets Status') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Pets Status</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('pets-statuses.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Name:</strong>
                            {{ $petsStatus->name }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Description:</strong>
                            {{ $petsStatus->description }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Color:</strong>
                            {{ $petsStatus->color }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
