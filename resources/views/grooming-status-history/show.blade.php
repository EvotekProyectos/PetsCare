@extends('layouts.app')

@section('template_title')
    {{ $groomingStatusHistory->name ?? __('Show') . " " . __('Grooming Status History') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Grooming Status History</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('grooming-status-histories.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Reception Id:</strong>
                            {{ $groomingStatusHistory->reception_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Grooming Status Id:</strong>
                            {{ $groomingStatusHistory->grooming_status_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
