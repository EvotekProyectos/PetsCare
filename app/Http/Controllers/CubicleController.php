<?php

namespace App\Http\Controllers;

use App\Models\Cubicle;
use App\Http\Requests\CubicleRequest;
use App\Models\CubicleType;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class CubicleController
 * @package App\Http\Controllers
 */
class CubicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cubicles = Cubicle::paginate();

        return view('cubicle.index', compact('cubicles'))
            ->with('i', (request()->input('page', 1) - 1) * $cubicles->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cubicle = new Cubicle();
        $c_types= CubicleType::all(); 
        $cubicle->state=0;
        return view('cubicle.create', compact('cubicle', 'c_types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CubicleRequest $request)
    {
        Cubicle::create($request->validated());


        return redirect()->route('cubicles.index')
            ->with('success', 'Cubicle created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cubicle = Cubicle::find($id);

        return view('cubicle.show', compact('cubicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cubicle = Cubicle::find($id);
        $c_types= CubicleType::all(); 
        return view('cubicle.edit', compact('cubicle', 'c_types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CubicleRequest $request, Cubicle $cubicle)
    {
        $cubicle->update($request->validated());

        return redirect()->route('cubicles.index')
            ->with('success', 'Cubículo actualizado correctamente.');
    }

    public function destroy($id)
    {
        $cubicle=Cubicle::find($id);
        $cubicle->delete();

        return response()->json($cubicle);
    }

    public function view(){
        $cubicles = Cubicle::paginate();
        return view ('cubicle.view' , compact('cubicles'));
    }

    public function list()
    {
        $cubicles = Cubicle::with('cubicleType')->get();
        return DataTables::of($cubicles) ->make(true);

        //return response()-> json($cubicles); 
    }
}
