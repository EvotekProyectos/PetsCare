@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Reception
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/receptions/form.css') }}">
@endpush

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="ph--call-bell-fill"></span> RECEPCIóN
                            </h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('receptions.update', $reception->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('reception.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        const selectedPetId = {{ old('pet_id', $reception?->pet_id) ?? 'null' }};
        var type = {{$reception-> reception_type_id}}
    </script>

    <script src="{{asset('js/receptions/edit.js')}}" defer></script>

@endpush