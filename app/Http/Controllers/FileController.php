<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Http\Requests\FileRequest;

/**
 * Class FileController
 * @package App\Http\Controllers
 */
class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort(404);
        $files = File::paginate();

        return view('file.index', compact('files'))
            ->with('i', (request()->input('page', 1) - 1) * $files->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404);
        $file = new File();
        return view('file.create', compact('file'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FileRequest $request)
    {
        File::create($request->validated());

        return redirect()->route('files.index')
            ->with('success', 'File created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        abort(404);
        $file = File::find($id);

        return view('file.show', compact('file'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        abort(404);
        $file = File::find($id);

        return view('file.edit', compact('file'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FileRequest $request, File $file)
    {
        $file->update($request->validated());

        return redirect()->route('files.index')
            ->with('success', 'File updated successfully');
    }

    public function destroy($id)
    {
        File::find($id)->delete();

        return redirect()->route('files.index')
            ->with('success', 'File deleted successfully');
    }
}
