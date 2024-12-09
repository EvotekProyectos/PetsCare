@extends('layouts.app')

@section('template_title')
    {{ $format->name ?? __('Show') . " " . __('Format') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Format</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('formats.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Format Type Id:</strong>
                            {{ $format->format_type_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Reception Id:</strong>
                            {{ $format->reception_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Format Pdf:</strong>
                            {{ $format->format_pdf }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
