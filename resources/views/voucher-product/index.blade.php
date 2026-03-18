@extends('layouts.app')

@section('template_title')
    Voucher Product
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Voucher Product') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('voucher-products.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Voucher Id</th>
										<th>Product Id</th>
										<th>Requested Quantity</th>
										<th>Unit Of Measure</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($voucherProducts as $voucherProduct)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $voucherProduct->voucher_id }}</td>
											<td>{{ $voucherProduct->product_id }}</td>
											<td>{{ $voucherProduct->requested_quantity }}</td>
											<td>{{ $voucherProduct->unit_of_measure }}</td>

                                            <td>
                                                <form action="{{ route('voucher-products.destroy',$voucherProduct->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('voucher-products.show',$voucherProduct->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('voucher-products.edit',$voucherProduct->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $voucherProducts->links() !!}
            </div>
        </div>
    </div>
@endsection
