@extends('layouts.app')

@section('template_title')
    {{ $grooming->name ?? __('Show') . ' ' . __('Grooming') }}
@endsection

@section('content')
    <section class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="gravity-ui--scissors"></span> GROOMING
                            </h4>
                        </div>
                    </div>
                    <div class="row card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                DATOS GENERALES
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
                                <p style="font-weight: bold">Descripción física: <span style="font-weight: normal">
                                        {{ $reception->pet->physic_descrip }} </span></p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Peso: <span style="font-weight: normal">
                                        {{ $reception->pet->weight }} </span></p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Clasificación: <span style="font-weight: normal">
                                        {{ $reception->pet->petClassification?->name ?? ''}} </span></p>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
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
                            <div class="col-md-3">
                                <p style="font-weight: bold">Fallecido: <span style="font-weight: normal">
                                        {{ $reception->pet->deceased == 1 ? 'Sí' : 'No' }}
                                    </span></p>
                            </div>
                        </div>
                        {{-- <div class="row d-flex justify-content-center mt-2" style="margin-bottom: -11px;">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Admisión: <span style="font-weight: normal">
                                        {{ $reception->id }} </span></p>
                            </div>
                            <div class="col-md-3">
                            </div>
                        </div> --}}
                        <div class="row d-flex justify-content-center " style="margin-bottom: -11px;">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Llegada: <span style="font-weight: normal">
                                        {{ $reception->entry_date }} </span></p>
                            </div>
                            <div class="col-md-3">
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center " style="margin-bottom: -11px;">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Entrega para: <span style="font-weight: normal">
                                    {{ $reception->exit_date }} </span></p>
                            </div>
                            <div class="col-md-3">
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center " style="margin-bottom: -11px;">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Estado Critico: <span style="font-weight: normal">
                                    {{ $reception->grooming->critic_status == 1 ? 'Aplica' : 'No aplica' }} </span></p>
                            </div>
                            <div class="col-md-3">
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center ">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Num Collar/Arete: <span style="font-weight: normal">
                                        {{ $reception->num }} </span></p>
                            </div>
                            <div class="col-md-3">
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col d-flex justify-content-between align-items-center my-2">
                            <div class="col">
                                <button class="btn btn-costum-services btn-lg text-uppercase rounded-4"
                                    onclick="OpenCarnet()">
                                    <span class="badge custom-badge-pill"><span
                                            class="healthicons--syringe-vaccine"></span></span> Registrar en Cartilla
                                </button>
                            </div>
                        </div>
                    </div> --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="text-uppercase" style="color: #0445A0;">
                            Instrucciones de los servicios a realizar
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-12">
                                <p style="font-size: 13pt">{{ $reception->grooming->instructions }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover responsive w-100" id="table">
                                        <thead class="thead table-primary text-uppercase">
                                            <tr>
                                                <th>Servicio</th>
                                                <th>Precio</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-2 d-flex justify-content-end">
                        <h5 id="total-price">Total Final: $0.00</h5>
                    </div>
                    
                    @if ($reception->statusGrooming->last()->grooming_status_id != 2)
                        <div class="col-12 mt-2 d-flex justify-content-end">
                            <form method="POST" onsubmit="EndService()" role="form" id="updatestatus">
                                <div hidden>
                                    <input type="text" value="{{ $reception->id }}" name="reception_id">
                                    <input type="text" value="2" name="grooming_status_id">
                                </div>@csrf
                                <button type="submit" class="btn btn-primary">
                                    Finalizar Servicio <i class="fas fa-arrow-right"></i></button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- <div class="modal" id="ModalCertificate" tabindex="-1" role="dialog" aria-hidden="true"
            style="display: none;">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content" style="background-color: #e9eced; border-radius: 20px;">
                    <div class="modal-header">
                        <div class="col-11 d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class=" text-uppercase" style="color: #0455A0">
                                <span class="map--veterinary-care"></span> VACUNAS Y DESPARACITACIONES
                            </h5>
                        </div>
                        <div class="col-1">
                            <button type="button" class="btn-close" onclick="closeModal()" aria-label="Close"></button>
                        </div>

                    </div>
                    <div class="modal-body" style="width: 100%;">

                        <div class="row">
                            <div class="col-12">
                                @include('vaccine-certificate.fillform')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

    </section>
@endsection

@push('scripts')
    <script>
        var ruta = "{{ asset('') }}";
        var imgDefault = "{{ asset('img/pet_pic.png') }}";
        var Pet_Id = {{ $reception->pet_id }};
        var Pic_id = {{ $reception->pet->picture_id ?? 'null' }};
        var Pic_route = "{{ $reception->pet->file->route ?? '' }}";
        const Reception_Id = {{ $reception->id }};
    </script>

    <script src="{{ asset('js/groomings/show.js') }}" defer></script>
@endpush
