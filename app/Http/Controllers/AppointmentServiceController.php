<?php

namespace App\Http\Controllers;

use App\Models\AppointmentService;
use App\Http\Requests\AppointmentServiceRequest;
use App\Models\Precios;
use App\Models\Producto;
use App\Models\ProductType;
use App\Models\Reception;
use App\Models\VaccineCertificate;
use App\Models\VoucherProduct;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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
        $this->authorize("create", AppointmentService::class);
        $this->guardReceptionNotTransferred(Reception::findOrFail($request->reception_id));

        $new = AppointmentService::create($request->validated());

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

    /**
     * Elimina un servicio de la tabla de servicios de una consulta (ver
     * ServicesTable en appointment/create.blade.php)
     * Reglas: un consumible (ES_ALMACENABLE "S") con vale Surtido no se puede eliminar;
     * con vale Pendiente sí, y ese vale se cancela si se queda sin productos.
     */
    public function removeService(Request $request, $id, VoucherService $voucherService)
    {
        $appointmentService = AppointmentService::with('lab', 'imaging')->findOrFail($id);
        $this->authorize("delete", $appointmentService);

        $request->validate(['reason' => 'required|string|max:1000']);

        $producto = $appointmentService->lab_type_id ? $appointmentService->lab : $appointmentService->imaging;
        $esAlmacenable = $producto->ES_ALMACENABLE ?? null;

        if ($esAlmacenable === 'S') {
            $activeVoucherProduct = VoucherProduct::where('sourceable_id', $appointmentService->id)
                ->where('sourceable_type', AppointmentService::class)
                ->whereHas('voucher', fn ($q) => $q->whereNotIn('status', ['Cancelado', 'Rechazado']))
                ->with('voucher')
                ->first();

            if ($activeVoucherProduct && $activeVoucherProduct->voucher->status === 'Surtido') {
                return response()->json([
                    'message' => 'Este servicio ya tiene un vale Surtido y no puede eliminarse.'
                ], 422);
            }

            if ($activeVoucherProduct) {
                $voucherService->removeVoucherProductAndKeepConsistent($activeVoucherProduct, 'Eliminación de servicio');
            }
        }

        $appointmentService->update([
            'deleted_by' => auth()->id(),
            'delete_reason' => $request->reason,
        ]);
        $appointmentService->delete();

        return response()->json(['success' => true]);
    }

    public function getLabs(int $id)
    {
        $data = AppointmentService::with('vet', 'lab')->where("reception_id", $id)->whereNotNull("lab_type_id")->get();

        return DataTables::of($data)->make(true);
    }

    public function getImgs(int $id)
    {
        $data = AppointmentService::with('vet', 'imaging')->where("reception_id", $id)->whereNotNull("imaging_type_id")->get();

        return DataTables::of($data)->make(true);
    }

    public function getServices(int $id)
    {
        $rawServices = AppointmentService::with('vet', 'lab', 'imaging', 'laboratory', 'img')
            ->where('reception_id', $id)
            ->where(function ($q) {
                $q->whereNotNull('lab_type_id')
                    ->orWhereNotNull('imaging_type_id');
            })
            ->get();

        $serviceIds = $rawServices->pluck('id')->all();
        $activeVouchers = VoucherProduct::activeMapFor(AppointmentService::class, $serviceIds);
        $voucherHistory = VoucherProduct::historyMapFor(AppointmentService::class, $serviceIds);

        $services = $rawServices->map(function ($item) use ($activeVouchers, $voucherHistory) {
            // lab()/imaging() apuntan a Producto (NOMBRE, ES_ALMACENABLE);
            // laboratory()/img() apuntan a Precios (PRECIO) — mismo par
            // lab_type_id/imaging_type_id, tabla distinta.
            $producto = $item->lab_type_id ? $item->lab : $item->imaging;
            $precio = $item->lab_type_id ? $item->laboratory : $item->img;
            $active = $activeVouchers->get($item->id);

            $hasCancelledHistory = ($voucherHistory->get($item->id) ?? collect())
                ->contains(fn($vp) => in_array($vp->voucher?->status, ['Cancelado', 'Rechazado']));

            return [
                'id'                    => $item->id,
                'source'                => 'appointment_service',
                'tipo'                  => $item->lab_type_id ? 'Laboratorio' : 'Imagen',
                'nombre'                => $producto->NOMBRE ?? '',
                'observations'          => $item->observations ?? 'Sin observaciones',
                'vet'                   => $item->vet->name ?? '',
                'precio'                => $precio->PRECIO ?? null,
                'es_almacenable'        => $producto->ES_ALMACENABLE ?? null,
                'active_voucher_folio'  => $active?->voucher?->folio,
                'active_voucher_id'     => $active?->voucher_id,
                'active_voucher_status' => $active?->voucher?->status,
                'has_cancelled_history' => $hasCancelledHistory,
            ];
        });

        $vaccineCertificates = VaccineCertificate::with('vet')
            ->where('reception_id', $id)
            ->get();

        $productIds = $vaccineCertificates->pluck('product')->filter()->unique()->values();
        $productos = Producto::whereIn('ARTICULO_ID', $productIds)
            ->get()
            ->keyBy('ARTICULO_ID');
        $precios = Precios::whereIn('ARTICULO_ID', $productIds)
            ->pluck('PRECIO', 'ARTICULO_ID');

        $vaccines = $vaccineCertificates->map(function ($item) use ($productos, $precios) {
            $producto = $productos->get($item->product);
            $tipoLabels = [1 => 'Vacuna', 2 => 'Desparasitación interna', 3 => 'Desparasitación externa'];

            return [
                'id'             => $item->id,
                'source'         => 'vaccine_certificate',
                'tipo'           => $tipoLabels[$item->service_id] ?? 'Vacuna',
                'nombre'         => $producto->NOMBRE ?? '',
                'observations'   => $item->observations ?? 'Sin observaciones',
                'vet'            => $item->vet->name ?? '',
                'precio'         => $precios->get($item->product),
                'es_almacenable' => $producto->ES_ALMACENABLE ?? null,
            ];
        });

        $data = $services->concat($vaccines)->values();

        return DataTables::of($data)->make(true);
    }
}
