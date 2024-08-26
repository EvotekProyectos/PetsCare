<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Http\Requests\GenreRequest;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class GenreController
 * @package App\Http\Controllers
 */
class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $genres = Genre::paginate();
        $this->authorize("viewAny", Genre::class);
        return view('genre.index', compact('genres'))
            ->with('i', (request()->input('page', 1) - 1) * $genres->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $genre = new Genre();
        $this->authorize("create", Genre::class);
        return view('genre.create', compact('genre'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GenreRequest $request)
    {
        $this->authorize("create", Genre::class);
        Genre::create($request->validated());

        return redirect()->route('genres.index')
            ->with('success', 'Genre created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $genre = Genre::find($id);
        $this->authorize("view", Genre::class);

        return view('genre.show', compact('genre'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $genre = Genre::find($id);
        $this->authorize("update", $genre);

        return view('genre.edit', compact('genre'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GenreRequest $request, Genre $genre)
    {
        $genre->update($request->validated());
        $this->authorize("update", $genre);

        return redirect()->route('genres.index')
            ->with('success', 'Genre updated successfully');
    }

    public function destroy($id)
    {
        $genre = Genre::find($id);
        $this->authorize("delete", $genre);
        $genre->delete();
        return response()->json($genre);
    }

    public function list()
    {
        $genres = Genre::all();

        return DataTables::of($genres) ->make(true);
    }
}
