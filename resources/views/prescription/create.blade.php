@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Prescription
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-12">
                
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="material-symbols--prescriptions-outline "></span> FÓRMULA MÉDICA
                            </h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" onsubmit="AddPrescription()" role="form"  id="NewPrescription"
                            enctype="multipart/form-data">
                            @csrf

                            @include('prescription.form2')

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
