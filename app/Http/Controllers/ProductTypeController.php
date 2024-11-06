<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use App\Http\Requests\ProductTypeRequest;
use App\Models\ProductClassification;

/**
 * Class ProductTypeController
 * @package App\Http\Controllers
 */
class ProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productTypes = ProductType::paginate();

        return view('product-type.index', compact('productTypes'))
            ->with('i', (request()->input('page', 1) - 1) * $productTypes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productType = new ProductType();
        $classifications = ProductClassification::all();

        return view('product-type.create', compact('productType','classifications'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductTypeRequest $request)
    {
        ProductType::create($request->validated());

        return redirect()->route('product-types.index')
            ->with('success', 'ProductType created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $productType = ProductType::find($id);

        return view('product-type.show', compact('productType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $productType = ProductType::find($id);
        $classifications = ProductClassification::all();

        return view('product-type.edit', compact('productType','classifications'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductTypeRequest $request, ProductType $productType)
    {
        $productType->update($request->validated());

        return redirect()->route('product-types.index')
            ->with('success', 'ProductType updated successfully');
    }

    public function destroy($id)
    {
        ProductType::find($id)->delete();

        return redirect()->route('product-types.index')
            ->with('success', 'ProductType deleted successfully');
    }
}
