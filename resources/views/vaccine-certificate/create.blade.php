@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Vaccine Certificate
@endsection

@section('content')
    <section class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="solar--document-add-broken"></span>
                                VACUNAS Y DESPARASITACIONES
                            </h4>
                        </div>
                    </div>
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-12">
                                <form method="POST" action="{{ route('vaccine-certificates.store') }}" role="form"
                                    enctype="multipart/form-data">
                                    @csrf

                                    @include('vaccine-certificate.form')

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
