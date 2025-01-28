@extends('layouts.app')

@section('template_title')
    {{  __('History') . ' ' . __('Cremation') }}
@endsection

@section('content')
    <section class="container-fluid">
        <div class="row">
            <div class="col-md-12">
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
                                DATOS GENERALES
                            </h5>
                        </div>
                        <div>
                            <table style="width: 100%;">
                                <tr>
                                    <td>
                                        <p style="font-weight:bold;">Fecha:
                                            <span style="font-weight: normal">
                                            {{ $cremation->reception->entry_date }} </span>
                                        </p>
                                    </td>

                                    <td>
                                        <p style="font-weight:bold;">Recepcionista:
                                            <span style="font-weight: normal">
                                            {{ $cremation->reception->receptionist->name }} </span>
                                        </p>
                                    </td>

                                    <td>
                                        <p style="font-weight:bold;">
                                            Médico responsable:                                             
                                            <span style="font-weight: normal"> {{ $cremation->vet->name ?? 'Sin especificar' }} </span>
                                        </p>
                                    </td>

                                </tr>
                            </table>
                        </div>

                        <div
                            style="border:1px solid #c5e8f7; margin-top:0%; border-left:none; border-right:none; border-top:none; background-color: #eef7fc;">
                            <div>
                                <table style="width: 100%;">
                                    <tr>
                                        <td>
                                            <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                                DATOS DE LA FAMILIA
                                            </h5>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div>
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td>
                                        <p style="font-weight:bold; margin: 0;"> Nombre:</p>

                                    </td>
                                    <td style="text-align: left; padding: 5px 10px;">
                                        <p style="font-weight:normal; margin: 0;">
                                            {{ $cremation->pet->family->name }}
                                        </p>
                                    </td>
                                    <td>
                                        <p style="font-weight:bold; margin: 0;"> Teléfono:</p>
                                    </td>
                                    <td style="padding: 5px 10px;">
                                        <p style="font-weight:normal; margin: 0;">
                                            {{ $cremation->pet->family->phone }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <div
                                style="border:1px solid #c5e8f7; margin-top:2%; border-left:none; border-right:none; border-top:none; background-color: #eef7fc;">
                                <div>
                                    <table style="width: 100%;">
                                        <tr>
                                            <td>
                                                <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                                    DATOS DE LA MASCOTA
                                                </h5>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div>
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td>
                                            <p style="font-weight:bold; margin: 0;"> Nombre:</p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:normal; margin: 0;">
                                                {{ $cremation->pet->name }}
                                            </p>
                                        <td>
                                            <p style="font-weight:bold; margin: 0;"> Raza:</p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:normal; margin: 0;">
                                                {{ $cremation->pet->raza }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-weight:bold; margin: 0;"> Peso:</p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:normal; margin: 0;">
                                                {{ $cremation->pet->weight }}
                                            </p>
                                        </td>
                                        <td>
                                            <p style="font-weight:bold; margin: 0;"> Sexo:</p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:normal; margin: 0;">
                                                {{ $cremation->pet->genre->name }}
                                            </p>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <p style="font-weight:bold; margin: 0;"> Fecha de
                                                nacimiento:</p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:normal; margin: 0;">
                                                {{ $cremation->pet->birthday }}
                                            </p>
                                        </td>
                                        <td>
                                            <p style="font-weight:bold; margin: 0;">Fecha de
                                                defunción:</p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:normal; margin: 0;">
                                                {{ $cremation->date_death ?? 'Sin especificar' }}
                                            </p>
                                        </td>
                                    </tr>

                                </table>
                            </div>

                            <div
                                style="border:1px solid #c5e8f7; margin-top: 40px; border-left:none; border-right:none; border-top:none; background-color: #eef7fc;">
                                <div>
                                    <table style="width: 100%; ">
                                        <tr>
                                            <td style="width: 80%">
                                                <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                                    DETALLES DEL SERVICIO
                                                </h5>
                                            </td>
                                            

                                            <td style="width: 20%">
                                                <p style="font-weight:bold; margin: 0;"> Folio para
                                                    Pagar:
                                                    {{ $reception->payment->folio_odv ?? 'Pendiente' }}
                                                </p>
                                            </td>

                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div>
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td>
                                            <p style="font-weight:bold; margin: 0;"> Tipo de
                                                servicio:</p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:normal; margin: 0;">
                                                {{ $cremation->serv->NOMBRE }}
                                            </p>
                                        <td>
                                            <p style="font-weight:bold; margin: 0;"> Tipo de Urna:
                                            </p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:normal; margin: 0;">
                                                {{ $cremation->type_urn ?? 'Sin especificar' }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-weight:bold; margin: 0;"> C.M:</p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:normal; margin: 0;">
                                                {{ $cremation->cm->name ?? 'Sin especificar' }}
                                            </p>
                                        <td>
                                            <p style="font-weight:bold; margin: 0;">Tipo de placa:
                                            </p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:normal; margin: 0;">
                                                {{ $cremation->tag->name ?? 'Sin especificar' }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-weight:bold; margin: 0;"> Mensaje placa:
                                            </p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:normal; margin: 0;">
                                                {{ $cremation->text_placa ?? 'Sin especificar' }}
                                            </p>
                                        <td>
                                            <p style="font-weight:bold; margin: 0;">Observaciones:
                                            </p>
                                        </td>
                                        <td
                                            style="padding: 5px 10px; word-wrap: break-word; word-break: break-word; white-space: normal;">
                                            <p style=" font-weight:normal; margin: 0;">
                                                {{ $cremation->observations ?? 'Sin especificar' }}
                                            </p>
                                        </td>

                                    </tr>
                                    <tr>

                                        <td>
                                            <p style="font-weight:bold; margin: 0;"> Precio:</p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:normal; margin: 0;">
                                                ${{ $cremation->service?->PRECIO ?? 'Sin especificar' }}
                                            </p>
                                        </td>
                                        <td>
                                            <p style="font-weight:bold; margin: 0;"> Fecha de
                                                entrega:</p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:normal; margin: 0;">
                                                {{ $cremation->date_finish ?? 'Sin especificar' }}
                                            </p>
                                        </td>

                                    </tr>
                            </div>

                          

                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection

{{-- @push('scripts')
    <script>
        var ruta = "{{ asset('') }}";
        var imgDefault = "{{ asset('img/pet_pic.png') }}";
        var Pet_Id = {{ $reception->pet_id }};
        var Pic_id = {{ $reception->pet->picture_id ?? 'null' }};
        var Pic_route = "{{ $reception->pet->file->route ?? '' }}";
        const Reception_Id = {{ $reception->id }};
    </script>

    <script src="{{ asset('js/groomings/show.js') }}" defer></script>
@endpush --}}
