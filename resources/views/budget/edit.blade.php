@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Budget
@endsection

@section('content')
    <section class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <h4 id="card_title" class="text-primary text-uppercase">
                            <span class="ic--baseline-price-change"></span> PRESUPUESTO
                        </h4>
                    </div>
                    <div class="card-body ">
                        <form method="POST" action="{{ route('budgets.update', $budget->id) }}" role="form"
                            enctype="multipart/form-data" id="budget">
                            {{-- {{ method_field('PATCH') }} --}}
                            @csrf

                            @include('budget.form')

                        </form>
                        <div>
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 id="card_title"  style="color: #BEBEBE">
                                    Servicios Médicos
                                </h5>
                            </div>
                            <form method="POST" onsubmit="NewEntry()" role="form"
                                enctype="multipart/form-data" id="details">
                                @csrf

                                @include('budget-detail.form')

                            </form>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 id="card_title"  style="color: #BEBEBE">
                                    Examenes de Laboratorio
                                </h5>
                            </div>
                            <form method="POST" onsubmit="NewLab()" role="form"
                                enctype="multipart/form-data" id="labs">
                                @csrf

                                @include('budget-detail.form-lab')

                            </form>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 id="card_title"  style="color: #BEBEBE">
                                    Imageneología
                                </h5>
                            </div>
                            <form method="POST" onsubmit="NewImg()" role="form"
                                enctype="multipart/form-data" id="imgs">
                                @csrf

                                @include('budget-detail.form-img')

                            </form>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover responsive w-100" id="table">
                                        <thead class="thead table-primary text-uppercase">
                                            <tr>
                                                <th>Servicio</th>
                                                <th>Notas</th>
                                                <th>Precio</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-2 d-flex justify-content-end">
                            <button type="button" onclick="generate(event)" class="btn btn-primary">
                                Siguiente <i class="fas fa-arrow-right"></i></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        const Budget_Id = {{ $budget->id }};
        const Base_Price = {{ $budget->total }};
    </script>
    <script src="{{ asset('js/budgets/create.js') }}" defer></script>
@endpush
