@extends('layouts.app')


@section('design')
    <link rel="stylesheet" href="{{ asset('css/appointment.css') }}?v={{ filemtime(public_path('css/appointment.css')) }}">
@endsection

@section('content')
    <section class="container-fluid">
        <div class="row">
            <div
                style=" display: flex;flex-direction: column; align-items: center; justify-content: center; margin: 0; padding: 20px;">
                <div class="col-12">
                    <div class="card bg-primary-soft border-0 p-3">
                        <div class="card-header bg-transparent border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 id="card_title" class="text-primary text-uppercase">
                                    <span class="lucide--calendar-check " style="font-size: 20px;"></span>
                                    CITAS CONFIRMADAS
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="row" style="padding: 1%">
                                        <div class="col-2">
                                            <div class="form-group">
                                                <select id="veterinarioFilter" class="form-select"
                                                    data-user="{{ auth()->user()->name }}">
                                                    <option value="1"> Mis citas</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-12">
                                            <div id='calendar'></div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/control_dates/calendarIndividual.js') }}" defer></script>
@endpush
