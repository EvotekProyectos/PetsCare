@extends('layouts.app')

@section('template_title')
    Assignment Delivey
@endsection

@section('assignmentsdelivery', 'active border-start border-3 border-primary')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0"">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="mdi--house-export-outline"></span> Servicio a Domicilio
                            </h4>

                        </div>
                    </div>


                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover responsive w-100" id="table">
                                        <thead class="thead table-primary text-uppercase">
                                            <tr>
                                                <th>Llegada</th>
                                                <th>Mascota</th>
                                                <th>Encargado</th>
                                                <th>Estado</th>
                                                <th>Hora Entrega</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="ModalDetails" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content" style="background-color: #e9eced; border-radius: 20px;">
                    <div class="modal-header">
                        <div class="col-11 d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class=" text-uppercase" style="color: #0455A0">
                                <span class="mdi--house-export-outline"></span> Servicio a Domicilio
                            </h5>
                        </div>
                        <div class="col-1 d-flex justify-content-between align-items-center">
                            <button type="button" class="btn-close" onclick="closeModal()" aria-label="Close"></button>
                        </div>

                    </div>
                    <div class="modal-body" style="width: 100%;">

                        <div class="row card-body">
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-6">
                                    <p style="font-weight: bold">Recolección: <span style="font-weight: normal" id="collect">
                                             </span></p>
                                </div>
                                <div class="col-md-6">
                                    <p style="font-weight: bold">Entrega: <span style="font-weight: normal" id="deliver">
                                             </span></p>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-6">
                                    <p style="font-weight: bold">Familia: <span style="font-weight: normal" id="family">
                                            </span></p>
                                </div>
                                <div class="col-md-6">
                                    <p style="font-weight: bold">Tel: <span style="font-weight: normal" id="phone">
                                           </span></p>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-6">
                                    <p style="font-weight: bold">Dirección: <span style="font-weight: normal" id="address">
                                             </span></p>
                                </div>
                                <div class="col-md-6">
                                    <p style="font-weight: bold">Referencias: <span style="font-weight: normal" id="references">
                                             </span></p>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-6">
                                    <p style="font-weight: bold">Mascota: <span style="font-weight: normal" id="pet">
                                             </span></p>
                                </div>
                                <div class="col-md-6">
                                    <p style="font-weight: bold">Especie: <span style="font-weight: normal" id="specie">
                                             </span></p>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-6">
                                    <p style="font-weight: bold">Raza: <span style="font-weight: normal" id="raza">
                                             </span></p>
                                </div>
                                <div class="col-md-6">
                                    <p style="font-weight: bold">Sexo: <span style="font-weight: normal" id="genre">
                                             </span></p>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-12">
                                    <p style="font-weight: bold">Clasificación: <span style="font-weight: normal" id="classification">
                                             </span></p>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-12">
                                    <p style="font-weight: bold">Descripción: <span style="font-weight: normal" id="description">
                                         </span></p>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var rutaBase = "{{ asset('storage/groomings/critic-statuses/') }}";
    </script>
    <script src="{{ asset('js/assignments/delivery.js') }}" defer></script>
@endpush
