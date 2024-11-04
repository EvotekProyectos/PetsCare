@extends('layouts.app')

@section('template_title')
    Product Type
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Product Type') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('product-types.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Product Classification Id</th>
										<th>Microsip Id</th>
										<th>Name</th>
										<th>Price</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productTypes as $productType)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $productType->product_classification_id }}</td>
											<td>{{ $productType->microsip_id }}</td>
											<td>{{ $productType->name }}</td>
											<td>{{ $productType->price }}</td>

                                            <td>
                                                <form action="{{ route('product-types.destroy',$productType->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('product-types.show',$productType->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('product-types.edit',$productType->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $productTypes->links() !!}
            </div>
        </div>
    </div>
@endsection
