<?php

namespace App\Http\Controllers;

use App\Models\ProductClassification;
use App\Http\Requests\ProductClassificationRequest;

/**
 * Class ProductClassificationController
 * @package App\Http\Controllers
 */
class ProductClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productClassifications = ProductClassification::paginate();

        return view('product-classification.index', compact('productClassifications'))
            ->with('i', (request()->input('page', 1) - 1) * $productClassifications->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productClassification = new ProductClassification();
        return view('product-classification.create', compact('productClassification'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductClassificationRequest $request)
    {
        ProductClassification::create($request->validated());

        return redirect()->route('product-classifications.index')
            ->with('success', 'ProductClassification created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $productClassification = ProductClassification::find($id);

        return view('product-classification.show', compact('productClassification'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $productClassification = ProductClassification::find($id);

        return view('product-classification.edit', compact('productClassification'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductClassificationRequest $request, ProductClassification $productClassification)
    {
        $productClassification->update($request->validated());

        return redirect()->route('product-classifications.index')
            ->with('success', 'ProductClassification updated successfully');
    }

    public function destroy($id)
    {
        ProductClassification::find($id)->delete();

        return redirect()->route('product-classifications.index')
            ->with('success', 'ProductClassification deleted successfully');
    }
}
