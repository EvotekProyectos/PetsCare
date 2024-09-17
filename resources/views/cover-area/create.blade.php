@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Cover Area
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="mdi--invoice-scheduled-outline"></span> CREAR AREÁ A CUBRIR EN HORARIO
                            </h4>
                        </div>
                    </div>
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-12">
                                <form method="POST" action="{{ route('cover-areas.store') }}" role="form"
                                    enctype="multipart/form-data">
                                    @csrf

                                    @include('cover-area.form')

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
