@extends('layouts.app')

@section('template_title')
    {{ $receptionStatusHistory->name ?? __('Show') . " " . __('Reception Status History') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Reception Status History</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('reception-status-histories.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Reception Id:</strong>
                            {{ $receptionStatusHistory->reception_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Attention Status Id:</strong>
                            {{ $receptionStatusHistory->attention_status_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
