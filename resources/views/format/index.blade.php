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
                                <span class="fluent--document-28-filled"></span> FORMATOS
                            <div class="float-right">
                                {{-- <a href="{{ route('formats.add', $pet->id) }}" class="btn btn-primary btn-sm float-right" data-placement="left">
                                    <i class="fas fa-plus"></i> AGREGAR NUEVO FORMATO
                                </a> --}}
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover responsive w-100" id="table">
                                        <thead class="thead table-primary text-uppercase">
                                            <tr>
                                        {{-- <th>No</th> --}}
                                        <th>Fecha</th>
										<th>Tipo de formato</th>
										<th>Tipo de recepción</th>
                                        <th>Mascota</th>
										<th>Acciones</th>
                                        
                                        {{-- <th></th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                   {{--  @foreach ($formats as $format)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $format->formatType->name }}</td>
                                            <td>{{ $format->reception->receptionType->name ?? 'Generado sin recepción' }}</td>
                                         
                                            <td>{{ $format->reception->pet->name ?? $format->pet->name }}</td>
											<td>
                                                <a href="{{ Storage::url($format->format_pdf) }}" 
                                                class="btn btn-primary" 
                                                target="_blank">
                                                Ver PDF
                                                </a>
                                            </td> --}}
                                            {{-- <td>
                                                <form action="{{ route('formats.destroy',$format->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('formats.show',$format->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('formats.edit',$format->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td> 
                                        </tr>
                                    @endforeach--}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- {!! $formats->links() !!} --}}
            </div>
        </div>
    </div>
@endsection

@push('scripts') 
{{-- <script> var petId = {{ $pet->id }};
 </script>  --}}
 <script src="{{ asset('js/formats/index.js') }}" defer>
 </script>
  @endpush
