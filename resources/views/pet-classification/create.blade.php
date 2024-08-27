@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Pet Classification
@endsection

@section('content')
    <section class=" container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <i class="fas fa-th-list"></i> Crear Clasificación para Mascotas
                            </h4>
                        </div>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('pet-classifications.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('pet-classification.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
