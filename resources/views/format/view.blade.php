@extends('layouts.app')

@section('template_title')
    Format
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="fluent--document-28-filled"></span> FORMATOS DE <strong>{{ $pet->name }}</strong>
                            </h4>
                            <div class="float-right">
                                <a href="{{ route('formats.add', $pet->id) }}" class="btn btn-primary btn-sm float-right" data-placement="left">
                                    <i class="fas fa-plus"></i> AGREGAR NUEVO FORMATO
                                </a>
                            </div>
                        </div>
                    </div>

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <p>{{ $message }}</p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover responsive w-100" id="table">
                                        <thead class="thead table-primary text-uppercase">
                                            <tr>
                                                <th>Fecha</th>
                                                {{-- <th>Tipo de recepción</th> --}}
                                                <th>Tipo de formato</th>
                                                {{-- <th>Formato</th> --}}
                                                {{-- <th>Acciones</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                             {{-- @foreach ($formats as $format)
                                            <tr>
                                                <td>{{ $format->reception->receptionType->name ??null }}</td>
                                                <td>{{ $format->formatType->name }}</td>
                                                <td>
                                                    <a href="{{ Storage::url($format->format_pdf) }}" class="btn btn-primary" target="_blank">
                                                        Ver PDF
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach  --}}
                                        
                                        </tbody>
                                    </table>
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
<script> var petId = {{ $pet->id }};
 </script> <script src="{{ asset('js/formats/view.js') }}" defer>
 </script>
  @endpush
