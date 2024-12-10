@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Surgery Pack
@endsection

@section('content')
    <section class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <h4 id="card_title" class="text-primary text-uppercase">
                            <i class="fas fa-first-aid"></i> Crear Paquete de Cirugia
                        </h4>
                    </div>
                    <div class="card-body ">
                        <form method="POST" action="{{ route('surgery-packs.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('surgery-pack.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
