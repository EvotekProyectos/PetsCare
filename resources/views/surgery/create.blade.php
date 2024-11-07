@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Surgery
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                               <span class="healthicons--surgical-sterilization-outline"></span> CIRUGÍAS
                            </h4>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('surgeries.store') }}" id="New role="form" enctype="multipart/form-data">
                            @csrf

                            @include('surgery.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script src="{{ asset('js/prescriptions/create.js') }}" defer></script>
@endpush

