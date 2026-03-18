<?php

namespace App\Http\Controllers;

use App\Models\VoucherProduct;
use App\Http\Requests\VoucherProductRequest;

/**
 * Class VoucherProductController
 * @package App\Http\Controllers
 */
class VoucherProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $voucherProducts = VoucherProduct::paginate();

        return view('voucher-product.index', compact('voucherProducts'))
            ->with('i', (request()->input('page', 1) - 1) * $voucherProducts->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $voucherProduct = new VoucherProduct();
        return view('voucher-product.create', compact('voucherProduct'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VoucherProductRequest $request)
    {
        VoucherProduct::create($request->validated());

        return redirect()->route('voucher-products.index')
            ->with('success', 'VoucherProduct created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $voucherProduct = VoucherProduct::find($id);

        return view('voucher-product.show', compact('voucherProduct'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $voucherProduct = VoucherProduct::find($id);

        return view('voucher-product.edit', compact('voucherProduct'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VoucherProductRequest $request, VoucherProduct $voucherProduct)
    {
        $voucherProduct->update($request->validated());

        return redirect()->route('voucher-products.index')
            ->with('success', 'VoucherProduct updated successfully');
    }

    public function destroy($id)
    {
        VoucherProduct::find($id)->delete();

        return redirect()->route('voucher-products.index')
            ->with('success', 'VoucherProduct deleted successfully');
    }
}
