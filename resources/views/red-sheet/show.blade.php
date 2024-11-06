@extends('layouts.app')

@section('template_title')
    {{ $redSheet->name ?? __('Show') . " " . __('Red Sheet') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Red Sheet</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('red-sheets.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Reception Id:</strong>
                            {{ $redSheet->reception_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Lab Type Id:</strong>
                            {{ $redSheet->lab_type_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Imaging Type Id:</strong>
                            {{ $redSheet->imaging_type_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Service Type Id:</strong>
                            {{ $redSheet->service_type_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Observations:</strong>
                            {{ $redSheet->observations }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Day Count:</strong>
                            {{ $redSheet->day_count }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Vet Id:</strong>
                            {{ $redSheet->vet_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
