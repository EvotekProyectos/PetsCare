<?php

namespace App\Http\Controllers;

use App\Models\TagType;
use App\Http\Requests\TagTypeRequest;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class TagTypeController
 * @package App\Http\Controllers
 */
class TagTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tagTypes = TagType::paginate();
        $this->authorize("viewAny", TagType::class);
        return view('tag-type.index', compact('tagTypes'))
            ->with('i', (request()->input('page', 1) - 1) * $tagTypes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tagType = new TagType();
        $this->authorize("create", TagType::class);
        return view('tag-type.create', compact('tagType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagTypeRequest $request)
    {
        TagType::create($request->validated());
        $this->authorize("create", TagType::class);
        return redirect()->route('tag-types.index')
            ->with('success', 'TagType created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tagType = TagType::find($id);
        $this->authorize("view", TagType::class);
        return view('tag-type.show', compact('tagType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $tagType = TagType::find($id);
        $this->authorize("update", $tagType);
        return view('tag-type.edit', compact('tagType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TagTypeRequest $request, TagType $tagType)
    {
        $tagType->update($request->validated());
        $this->authorize("update", $tagType);
        
        return redirect()->route('tag-types.index')
            ->with('success', 'Tipo de placa actualizado correctamente');
    }

    public function destroy($id)
    {
        $tag=TagType::find($id);
        $this->authorize("delete", $tag);
        $tag->delete();
        return response()->json($tag);
    }

    public function list()
    {
        $tags = TagType::all();

        return DataTables::of($tags) ->make(true);
    }
}
