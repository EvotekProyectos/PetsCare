<?php

namespace App\Http\Controllers;

use App\Models\Format;
use App\Http\Requests\FormatRequest;

/**
 * Class FormatController
 * @package App\Http\Controllers
 */
class FormatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $formats = Format::paginate();
        $this->authorize("viewAny", Format::class);
        return view('format.index', compact('formats'))
            ->with('i', (request()->input('page', 1) - 1) * $formats->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $format = new Format();
        $this->authorize("create", Format::class);
        return view('format.create', compact('format'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FormatRequest $request)
    {
        Format::create($request->validated());
        $this->authorize("create", Format::class);

        return redirect()->route('formats.index')
            ->with('success', 'Format created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $format = Format::find($id);
        $this->authorize("view", $format);
        return view('format.show', compact('format'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $format = Format::find($id);
        $this->authorize("update", $format);
        return view('format.edit', compact('format'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FormatRequest $request, Format $format)
    {
        $format->update($request->validated());
        $this->authorize("update", $format);
        return redirect()->route('formats.index')
            ->with('success', 'Format updated successfully');
    }

    public function destroy($id)
    {
        $format=Format::find($id);
        $this->authorize("update", $format);
        $format->delete();

        return response()->json($format);
    }
}
