@extends('layouts.app')



@section('content')
    <section class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card bg-primary-soft border-0 p-3 h-100">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <i class="fas fa-list"></i> Actualizar Estado Reproductivo
                            </h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <form method="POST"
                                    action="{{ route('reproductive-statuses.update', $reproductiveStatus->id) }}"
                                    role="form" enctype="multipart/form-data">
                                    {{ method_field('PATCH') }}
                                    @csrf

                                    @include('reproductive-status.form')

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
