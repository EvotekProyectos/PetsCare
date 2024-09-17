@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Family
@endsection

@section('content')
    <section class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card bg-primary-soft border-0 p-3t">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="fluent-mdl2--family"></span> FAMILIA
                            </h4>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <p>{{ $message }}</p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 ">
                                <form method="POST" action="{{ route('families.update', $family->id) }}" role="form"
                                    enctype="multipart/form-data">
                                    {{ method_field('PATCH') }}
                                    @csrf

                                    @include('family.form')

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <p></p>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card  border-0 p-3t" style="background-color: #d3f0f336;">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class=" text-uppercase" style="color: #007c84">
                                <span class="mdi--pets"></span> MASCOTAS
                            </h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 ">
                                <form method="POST" action="{{ route('pets.store') }}" role="form"
                                    enctype="multipart/form-data">

                                    @csrf

                                    @include('pet.form')

                                </form>
                            </div>
                        </div>
                        <div class="row" id="pet-list">
                            {{-- <div class="col-2 mx-4 my-2">
                                <div class="row  bg-white d-flex justify-content-between align-items-center"
                                style="border-radius: 10px; ">
                                    <div class="col-4 my-2">
                                        <img src="{{ asset('img/pic.png') }}" alt="Foto de la Mascota"
                                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px; ">
                                    </div>
                                    <div class="col-5">
                                        <p>Nombre</p>
                                    </div>
                                    <div class="col-3">
                                        <span class="mdi--edit-circle"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2 mx-4 my-2">
                                <div class="row  bg-white d-flex justify-content-between align-items-center"
                                style="border-radius: 10px;">
                                    <div class="col-4 my-2">
                                        <img src="{{ asset('img/pic.png') }}" alt="Foto de la Mascota"
                                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px; ">
                                    </div>
                                    <div class="col-5">
                                        <p>Nombre</p>
                                    </div>
                                    <div class="col-3">
                                        <a href=""> <span class="mdi--edit-circle"></span></a>
                                    </div>
                                </div>
                            </div> --}}
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        var ruta = "{{ asset('') }}";
        var imgDefault = "{{ asset('img/pet_pic.png') }}";
        var family_id = {{$family->id}};
    </script>
    <script src="{{ asset('js/families/add.js') }}" defer></script>
@endpush
