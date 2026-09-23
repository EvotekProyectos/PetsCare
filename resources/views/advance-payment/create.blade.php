@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Advance Payment
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="lets-icons--paper-fill"></span> Registrar Nuevo Anticipo
                            </h4>
                        </div>
                    </div>
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <p>{{ $message }}</p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="card-body">
                        <form method="POST" action="{{ route('advance-payments.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('advance-payment.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
