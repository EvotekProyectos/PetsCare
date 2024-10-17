@extends('layouts.app')

@section('template_title')
    Vaccine Certificate
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">

                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="solar--document-add-broken"></span> CARTILLA VIRTUAL
                            </h4>

                            <div class="float-right">
                                <a href="{{ route('vaccine-certificates.create') }}"class="btn btn-primary btn-sm rounded-4"
                                    data-placement="left">
                                    <i class="fas fa-plus"></i> CREAR NUEVA CARTILLA
                                </a>
                            </div>
                        </div>
                    </div>

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        
                                        
										<th>Pet Id</th>
										{{-- <th>Service Id</th> --}}
										<th>Producto</th>

                                        <th>application_date</th>
										<th>next_application_date</th>
										
										

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vaccineCertificates as $vaccineCertificate)
                                        <tr>
                                            
											<td>{{ $vaccineCertificate->pet_id }}</td>
											<td>{{ $vaccineCertificate->product }}</td>
                                            <td>{{ $vaccineCertificate->application_date }}</td>
											<td>{{ $vaccineCertificate->next_application_date }}</td>
											
											
											
						
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $vaccineCertificates->links() !!}
            </div>
        </div>
    </div>
@endsection
