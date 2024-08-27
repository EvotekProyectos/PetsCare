@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Fam Classification
@endsection

@section('content')
    <section class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <i class="fas fa-th-list"></i> Editar Clasificación para Familias
                            </h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">

                                <form method="POST"
                                    action="{{ route('fam-classifications.update', $famClassification->id) }}"
                                    role="form" enctype="multipart/form-data">
                                    {{ method_field('PATCH') }}
                                    @csrf

                                    @include('fam-classification.form')

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
