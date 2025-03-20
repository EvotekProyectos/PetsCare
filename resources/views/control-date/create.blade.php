@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Control Date
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <i class="fa fa-calendar-check"></i> CREAR CITA
                            </h4>
                        </div>
                    </div>

                      
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <form method="POST" action="{{ route('control-dates.store') }}"  role="form" enctype="multipart/form-data">
                                    @csrf

                                    @include('control-date.form')

                                </form>
                            </div>
                        </div> 
                    </div>       
            </div>
        </div>
    </section>
@endsection


@push('scripts')
<script src="{{ asset('js/control_dates/create.js') }}" defer></script>
@endpush
