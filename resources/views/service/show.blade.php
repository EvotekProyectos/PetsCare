@extends('layouts.app')

@section('template_title')
    {{ $service->name ?? __('Show') . " " . __('Service') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Service</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('services.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Vaccine:</strong>
                            {{ $service->vaccine }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Deworming Internal:</strong>
                            {{ $service->deworming_internal }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Deworming External:</strong>
                            {{ $service->deworming_external }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
