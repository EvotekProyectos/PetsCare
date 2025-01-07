<?php

namespace App\Http\Controllers;

use App\Http\Requests\CubicleRequest;
use App\Models\CubicleType;
use App\Http\Requests\CubicleTypeRequest;
use App\Models\Producto;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class CubicleTypeController
 * @package App\Http\Controllers
 */
class CubicleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cubicleTypes = CubicleType::paginate();

        return view('cubicle-type.index', compact('cubicleTypes'))
            ->with('i', (request()->input('page', 1) - 1) * $cubicleTypes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cubicleType = new CubicleType();
        $products = Producto::where("ESTATUS",  "A")->get();
        return view('cubicle-type.create', compact('cubicleType', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CubicleTypeRequest $request)
    {
        CubicleType::create($request->validated());


        return redirect()->route('cubicle-types.index')
            ->with('success', 'Creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cubicleType = CubicleType::find($id);

        return view('cubicle-type.show', compact('cubicleType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cubicleType = CubicleType::find($id);

        return view('cubicle-type.edit', compact('cubicleType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CubicleTypeRequest $request, CubicleType $cubicleType)
    {
        $cubicleType->update($request->validated());

        return redirect()->route('cubicle-types.index')
            ->with('success', 'Actualizado correctamente');
    }

    public function destroy($id)
    {
        $CubicleType=CubicleType::find($id);
        $CubicleType->delete();

        return response()->json($CubicleType);
    }

    public function list() 
     {
        $c_types = CubicleType::all();

        return DataTables::of($c_types)->make(true);
    }
}
