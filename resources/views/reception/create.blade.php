@extends('layouts.app')

@section('home', 'active border-start border-3 border-primary') 

@push('styles')

<link rel="stylesheet" href="{{ asset('css/receptions/form.css') }}">
@endpush

@section('content')
    <section class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="ph--call-bell-fill"></span> RECEPCIÓN
                            </h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('receptions.store') }}" role="form"
                            enctype="multipart/form-data">
                            @csrf

                            @include('reception.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{asset('js/receptions/form.js')}}" defer></script>
@endpush
