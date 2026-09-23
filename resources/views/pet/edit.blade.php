@extends('layouts.app')

@section('template_title')
    EDITAR MASCOTA
@endsection

@section('content')
    <section class="container-fluid">
        <div class="">
            <div class="col-12">

                <div class="card  border-0 p-3t" style="background-color: #d3f0f336;">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class=" text-uppercase" style="color: #007c84">
                                <span class="mdi--pets"></span> MASCOTAS
                            </h4>
                        </div>
                    </div>
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-12 ">
                            <form method="POST" action="{{ route('pets.update', $pet->id) }}"
                                role="form" enctype="multipart/form-data">
                                {{ method_field('PATCH') }}
                                @csrf

                                @include('pet.form')

                                </form>
                            </div>
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
        var Pic_id = {{$pet->picture_id}};
        var Pic_route = "{{ $pet->file->route ?? '' }}";
    </script>
    <script src="{{ asset('js/pets/edit.js') }}" defer></script>
    <script src="{{ asset('js/pets/breed-cascade.js') }}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            bindSpeciesBreedCascade('species_id', 'breed_id');
        });
    </script>
@endpush
