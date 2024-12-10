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
                        <span class="ic--baseline-price-change"></span> Editar presupuesto quirurgico
                    </h4>
                </div>
                    <div class="card-body ">
                        <form method="POST" action="{{ route('budgets.update', $budget->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('budget.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
