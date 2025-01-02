@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Cremation
@endsection

@section('content')
    <section class="container-fluid">
        <div class="row">

            <div class="col-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="emojione-monotone--funeral-urn" style="font-size: 20px;"></span>
                                 CREMACIÓN
                            </h4>
                        </div>
                    </div>

                    <div class="row card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                DATOS GENERALES MASCOTA
                            </h5>
                        </div>
                        <div class="col-md-4 d-flex justify-content-left">
                            <div class="form-group text-center">
                                <img src="{{ asset('img/pet_pic.png') }}" alt="Foto Mascota" id="preview"
                                    class="img-fixed"
                                    style="width: 115px; height: 115px; object-fit: cover; border-radius: 70px; ">
                                <h5>{{ $reception->pet->name }}</h5>
                            </div>
                        </div>

                        <div class="row d-flex justify-content-center" style="margin-bottom: -11px;">
                            <div class="col-md-3">
                                <p style="font-weight: bold">Especie: <span style="font-weight: normal">
                                        {{ $reception->pet->specie }} </span></p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Raza: <span style="font-weight: normal">
                                        {{ $reception->pet->raza }} </span></p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Género: <span style="font-weight: normal">
                                        {{ $reception->pet->genre->name }} </span></p>
                            </div>
                        </div>

                        <div class="row d-flex justify-content-center" style="margin-bottom: -11px;">
                            <div class="col-md-3">
                                <p style="font-weight: bold">Peso: <span style="font-weight: normal">
                                        {{ $reception->pet->weight }} </span></p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">E. Reproductivo: <span style="font-weight: normal">
                                    {{ $reception->pet->reproductiveStatus->name }} </span></p>
                            </div>
                            @php
                            $birthday = \Carbon\Carbon::parse($reception->pet->birthday);
                            $now = \Carbon\Carbon::now();

                            $years = $birthday->diffInYears($now);
                            $months = $birthday->copy()->addYears($years)->diffInMonths($now);
                            $days = $birthday->copy()->addYears($years)->addMonths($months)->diffInDays($now);
                        @endphp
                        <div class="col-md-3">
                            <p style="font-weight: bold">Edad: <span style="font-weight: normal">
                                    {{ $years }} años, {{ $months }} meses, y {{ $days }} días
                                </span></p>
                            </div>
                    </div>

                        <div class="card-body ">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                    DATOS GENERALES FAMILIA
                                </h5>
                            </div>
                           
                            <div class="row d-flex justify-content-center" style="margin-bottom: -11px;">
                                <div class="col-md-3">
                                    <p style="font-weight: bold">Nombre: <span style="font-weight: normal">
                                            {{ $reception->family->name }} </span></p>
                                </div>

                                <div class="col-md-3">
                                    <p> </p>
                                </div>
                                
                                <div class="col-md-3">
                                    <p style="font-weight: bold">Recepcionista: <span style="font-weight: normal">
                                            {{$reception->receptionist->name }} </span></p>
                                </div>

                                

                            </div>
                            
                            <div class="row d-flex justify-content-center" style="margin-bottom: -11px;">
                                <div class="col-md-3">
                                    <p style="font-weight: bold">Teléfono: <span style="font-weight: normal">
                                        {{ $reception->family->phone }} </span></p>
                                </div>

                                <div class="col-md-3">
                                    <p> </p>
                                </div>

                                <div class="col-md-3">
                                    <p style="font-weight: bold">Médico responsable: <span style="font-weight: normal">
                                            {{ $reception->vet->name }} </span></p>
                                </div>

                                
                            </div>
                          
                        </div>
                        <div class="card-body ">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                    DETALLES DEL SERVICIO
                                </h5>
                            </div>
                        </div>

                        <div class="card-body">
                            {{-- <form method="POST" onsubmit="Cremation()" role="form" id="newCremation"
                            enctype="multipart/form-data">
                                @csrf
    
                                @include('cremation.form')
    
                            </form> --}}

                            <form method="POST" onsubmit="Cremation(event)" role="form" id="newCremation" enctype="multipart/form-data">
                                @csrf
                                @include('cremation.form')
                            </form>
                            
                        </div>


            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    var ruta = "{{ asset('') }}";
    var imgDefault = "{{ asset('img/pet_pic.png') }}";
    var Reception_Id = {{ $reception->id }};
    var Pet_Id = {{ $reception->pet_id }};
    var Pic_id = {{ $reception->pet->picture_id ?? 'null' }};
    var Pic_route = "{{ $reception->pet->file->route ?? '' }}";
</script>
<script src="{{ asset('js/cremations/create.js') }}" defer></script>
@endpush
