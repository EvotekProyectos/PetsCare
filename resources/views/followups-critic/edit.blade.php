@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Followups Critic
@endsection

@section('content')
<section class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="card bg-primary-soft border-0 p-3t">
                <div class="card-header bg-transparent border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 id="card_title" class="text-primary text-uppercase">
                            <span class="clarity--note-edit-line"></span> EDITAR PASE DE GUARDIA Criticos
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                        <form method="POST" action="{{ route('followups-critics.update', $followupsCritic->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('followups-critic.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
