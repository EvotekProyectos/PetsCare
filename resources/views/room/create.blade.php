@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Room
@endsection

@section('content')
    <section class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header  bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <i class="fas fa-first-aid"></i> Crear Consultorio
                            </h4>
                        </div>
                    </div>
                    <div class="card-body ">
                        <form method="POST" action="{{ route('rooms.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('room.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
