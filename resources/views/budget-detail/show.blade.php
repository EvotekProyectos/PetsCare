@extends('layouts.app')

@section('template_title')
    {{ $budgetDetail->name ?? __('Show') . " " . __('Budget Detail') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Budget Detail</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('budget-details.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Budget Id:</strong>
                            {{ $budgetDetail->budget_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Service Id:</strong>
                            {{ $budgetDetail->service_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Price:</strong>
                            {{ $budgetDetail->price }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Notes:</strong>
                            {{ $budgetDetail->notes }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
