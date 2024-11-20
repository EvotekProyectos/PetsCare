<?php

namespace App\Http\Controllers;

use App\Models\AppointmentService;
use App\Http\Requests\AppointmentServiceRequest;
use App\Models\ProductType;

/**
 * Class AppointmentServiceController
 * @package App\Http\Controllers
 */
class AppointmentServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointmentServices = AppointmentService::paginate();
        $this->authorize("viewAny", AppointmentService::class);

        return view('appointment-service.index', compact('appointmentServices'))
            ->with('i', (request()->input('page', 1) - 1) * $appointmentServices->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $appointmentService = new AppointmentService();
        $products = ProductType::all();
        $this->authorize("create", AppointmentService::class);
        return view('appointment-service.create', compact('appointmentService', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AppointmentServiceRequest $request)
    {
        $new = AppointmentService::create($request->validated());
        $this->authorize("create", AppointmentService::class);

        return response()->json($new);

        // return redirect()->route('appointment-services.index')
        //     ->with('success', 'AppointmentService created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $appointmentService = AppointmentService::find($id);
        $this->authorize("view", $appointmentService);

        return view('appointment-service.show', compact('appointmentService'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $appointmentService = AppointmentService::find($id);
        $this->authorize("update", $appointmentService);

        return view('appointment-service.edit', compact('appointmentService'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AppointmentServiceRequest $request, AppointmentService $appointmentService)
    {
        $appointmentService->update($request->validated());
        $this->authorize("update", $appointmentService);

        return redirect()->route('appointment-services.index')
            ->with('success', 'AppointmentService updated successfully');
    }

    public function destroy($id)
    {
        $appointmentService = AppointmentService::find($id);
        $this->authorize("delete", $appointmentService);
        $appointmentService->delete();

        return redirect()->route('appointment-services.index')
            ->with('success', 'AppointmentService deleted successfully');
    }
}
