@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Followup Surgical
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/followups/form.css') }}">
@endpush

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">


                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="solar--document-add-broken "></span> PASE DE GUARDIA DE QUIRÚRGICOS
                            </h4>
                        </div>
                    </div>
                    
                   

                    <div class="card-body">
                        <form method="POST" action="{{ route('followup-surgicals.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('followup-surgical.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
