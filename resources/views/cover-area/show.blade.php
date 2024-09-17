@extends('layouts.app')

@section('template_title')
    {{ $coverArea->name ?? __('Show') . " " . __('Cover Area') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Cover Area</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('cover-areas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Name:</strong>
                            {{ $coverArea->name }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Color:</strong>
                            {{ $coverArea->color }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
