@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <h4 id="card_title" class="text-primary text-uppercase text-center">
                            <i class="fas fa-rocket"></i> PÉRDIDO EN EL ESPACIO <i class="fas fa-rocket"></i>
                        </h4>
                        <p class="text-center text-dark mb-0">Hmm, parece que esta página no existe.</p>
                    </div>
                    <div class="card-body">
                        <div class="row d-flex justify-content-center">
                            <div class="col-6">
                                <img src="{{ asset('img/404-Error-bro.png') }}" alt="" srcset=""
                                    class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
