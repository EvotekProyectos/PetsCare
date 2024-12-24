@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Cm Type
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <i class="clarity--two-way-arrows-line"></i> Tipos de C.M
                            </h4>
                        </div>
                    </div>
                    <div class="card-body ">
                        <form method="POST" action="{{ route('cm-types.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('cm-type.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
