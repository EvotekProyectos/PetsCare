@extends('layouts.app')

@section('template_title')
    {{ $budget->name ?? __('Show') . ' ' . __('Budget') }}
@endsection

@section('content')
    <section class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <h4 id="card_title" class="text-primary text-uppercase">
                            <span class="ic--baseline-price-change"></span> PRESUPUESTO FOLIO
                            {{ str_pad($budget->id, 4, '0', STR_PAD_LEFT) }}
                        </h4>
                    </div>

                    <div class="card-bodY">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" style="color: #8f8d8d">
                                Datos Generales
                            </h5>
                        </div>

                        <div class="row">
                            <div class="col-3">
                                <p style="font-weight: bold">FOLIO: </p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: normal">
                                    {{ str_pad($budget->id, 4, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: bold">FECHA DE CREACIÓN: </p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: normal">
                                    {{ $budget->date }}</p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: bold">NOTAS EXTRA: </p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: normal">
                                    {{ $budget->others ?? 'Sin notas' }}</p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: bold">M.V.Z. RESPONSABLE: </p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: normal">
                                    {{ $budget->vet->name }}</p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: bold">FAMILIA: </p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: normal">
                                    {{ $budget->pet->family->name }}</p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: bold">DOMICILIO: </p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: normal">
                                    {{ $budget->pet->family->address }}</p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: bold">CORREO: </p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: normal">
                                    {{ $budget->pet->family->email }}</p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: bold">TELEFONO: </p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: normal">
                                    {{ $budget->pet->family->phone }}</p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: bold">MASCOTA: </p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: normal">
                                    {{ $budget->pet->name }}</p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: bold">ESPECIA: </p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: normal">
                                    {{ $budget->pet->specie }}</p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: bold">RAZA: </p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: normal">
                                    {{ $budget->pet->raza }}</p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: bold">SEXO: </p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: normal">
                                    {{ $budget->pet->genre->name }}</p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: bold">DESCRIPCIÓN: </p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: normal">
                                    {{ $budget->pet->physic_descrip }}</p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: bold">PESO: </p>
                            </div>
                            <div class="col-3">
                                <p style="font-weight: normal">
                                    {{ $budget->pet->weight }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 id="card_title" style="color: #8f8d8d">
                                    Servicios Presupuestados en la fecha {{ $budget->date }}
                                </h5>
                            </div>
                            <table class="table table-striped table-hover responsive w-100">

                                <thead class="thead table-primary text-uppercase">
                                    <tr>
                                        <th class="tableup" style="text-align: center">Servicio Médico</th>
                                        <th class="tableup" style="text-align: center">Notas</th>
                                        <th class="tableup" style="text-align: center">Precio</th>
                                    </tr>
                                </thead>
                                @foreach ($details->whereNotNull('service_id') as $detail)
                                    <tbody>
                                        <tr>
                                            <td class="fillable">
                                                {{ $detail->serv->NOMBRE ?? 'N/A' }}
                                            </td>
                                            <td class="fillable">{{ $detail->notes ?? 'Sin notas' }}</td>
                                            <td class="fillable" style="text-align: right">
                                                ${{ number_format($detail->price ?? 0, 2) }}</td>
                                        </tr>
                                    </tbody>
                                @endforeach

                                <thead class="thead table-primary text-uppercase">
                                    <tr>
                                        <th class="tableup" style="text-align: center">Laboratorio</th>
                                        <th class="tableup" style="text-align: center">Notas</th>
                                        <th class="tableup" style="text-align: center">Precio</th>
                                    </tr>
                                </thead>
                                @foreach ($details->whereNotNull('lab_id') as $detail)
                                    <tbody>
                                        <tr>
                                            <td class="fillable">
                                                {{ $detail->lab->NOMBRE ?? 'N/A' }}
                                            </td>
                                            <td class="fillable">{{ $detail->notes ?? 'Sin notas' }}</td>
                                            <td class="fillable" style="text-align: right">
                                                ${{ number_format($detail->price ?? 0, 2) }}</td>
                                        </tr>
                                    </tbody>
                                @endforeach

                                <thead class="thead table-primary text-uppercase">
                                    <tr>
                                        <th class="tableup" style="text-align: center">Imageneología</th>
                                        <th class="tableup" style="text-align: center">Notas</th>
                                        <th class="tableup" style="text-align: center">Precio</th>
                                    </tr>
                                </thead>
                                @foreach ($details->whereNotNull('img_id') as $detail)
                                    <tbody>
                                        <tr>
                                            <td class="fillable">
                                                {{ $detail->img->NOMBRE ?? 'N/A' }}
                                            </td>
                                            <td class="fillable">{{ $detail->notes ?? 'Sin notas' }}</td>
                                            <td class="fillable" style="text-align: right">
                                                ${{ number_format($detail->price ?? 0, 2) }}</td>
                                        </tr>
                                    </tbody>
                                @endforeach
                                <tfoot>
                                    <tr>
                                        <td style="text-align: right; background-color: #0455a0db;" colspan="3">
                                            <h5 style="color: rgb(233, 237, 243)">
                                                {{-- <span class="streamline-emojis--money-mouth-face-1"></span> --}}
                                                GRAN TOTAL:
                                                ${{ number_format($budget->total) }}
                                                {{-- <span class="streamline-emojis--money-mouth-face-1"></span> --}}
                                            </h5>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="col-12 mt-2 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm text-uppercase rounded-4"
                                    onclick="window.open('{{ asset('storage/budgets/budget_' . $budget->id . '.pdf') }}', '_blank')">
                                    <i class="fas fa-file-pdf"></i>
                                    Ver PDF
                                </button>


                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
