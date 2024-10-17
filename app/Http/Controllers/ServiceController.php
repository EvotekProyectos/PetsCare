<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Requests\ServiceRequest;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class ServiceController
 * @package App\Http\Controllers
 */
class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::paginate();
        $this->authorize("viewAny", Service::class);
        return view('service.index', compact('services'))
            ->with('i', (request()->input('page', 1) - 1) * $services->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $service = new Service();
        $this->authorize("create", Service::class);
        return view('service.create', compact('service'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceRequest $request)

    {   $this->authorize("create", Service::class);
        Service::create($request->validated());

        return redirect()->route('services.index')
            ->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $service = Service::find($id);
        $this->authorize("view", Service::class);
        return view('service.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $service = Service::find($id);
        $this->authorize("update", $service);
        return view('service.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceRequest $request, Service $service)
    {
        $service->update($request->validated());
        $this->authorize("update", $service);

        return redirect()->route('services.index')
            ->with('success', 'Service updated successfully');
    }

    public function destroy($id)
    {
        $service =  Service::find($id); 
        $this->authorize("delete", $service);
        $service->delete();
        return response()->json($service);
    }

    public function list()
    {
        $service = Service::all();
        return DataTables::of($service)->make(true);
    }
}
