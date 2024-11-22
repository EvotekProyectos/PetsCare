@extends('layouts.app')

@section('template_title')
    Format
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Format') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('formats.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
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
                                        <th>No</th>
                                        
										<th>Format Type Id</th>
										<th>Reception Id</th>
										<th>Format Pdf</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($formats as $format)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $format->format_type_id }}</td>
											<td>{{ $format->reception_id }}</td>
											<td>{{ $format->format_pdf }}</td>

                                            <td>
                                                <form action="{{ route('formats.destroy',$format->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('formats.show',$format->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('formats.edit',$format->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $formats->links() !!}
            </div>
        </div>
    </div>
@endsection
