<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class AccountController
 *
 * @package App\Http\Controllers
 */
class AccountController extends Controller
{
    public function index()
    {
        // $this->authorize('viewAny', Account::class);

        return view('account.index');
    }

    /**
     * Endpoint único, compartido entre account.index y la sección de
     * pet_history (con pet_id) — ver public/js/accounts/table.js.
     */
    public function list(Request $request)
    {
        // $this->authorize('viewAny', Account::class);

        $accounts = Account::with(['episode.pet.family', 'latestSalesOrder'])
            ->withSum('activeCharges as total', 'total')
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('pet_id'), function ($query) use ($request) {
                $query->whereHas('episode', function ($q) use ($request) {
                    $q->where('pet_id', $request->pet_id);
                });
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereHas('episode', function ($q) use ($request) {
                    $q->whereDate('opened_at', '>=', $request->date_from);
                });
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereHas('episode', function ($q) use ($request) {
                    $q->whereDate('opened_at', '<=', $request->date_to);
                });
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->whereHas('episode.pet', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('family', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->get();

        return DataTables::of($accounts)->make(true);
    }

    /* Detalle de solo lectura de UNA cuenta: lista los Charge (ACTIVE) */
    public function detail(int $id)
    {
        $account = Account::with([
            'episode.pet.family',
            'activeCharges',
            'latestSalesOrder',
            'payments',
        ])->findOrFail($id);

        // $this->authorize('view', $account);

        return response()->json([
            'id' => $account->id,
            'status' => $account->status,
            'closed_at' => $account->closed_at,
            'pet' => [
                'name' => $account->episode->pet->name ?? null,
            ],
            'family' => [
                'name' => $account->episode->pet->family->name ?? null,
            ],
            'opened_at' => $account->episode->opened_at ?? null,
            'sales_order' => $account->latestSalesOrder ? [
                'folio' => $account->latestSalesOrder->folio,
                'status' => $account->latestSalesOrder->status,
            ] : null,
            'charges' => $account->activeCharges->map(fn ($charge) => [
                'description' => $charge->description,
                'quantity' => $charge->quantity,
                'unit_price' => $charge->unit_price,
                'total' => $charge->total,
            ])->values(),
            'total' => $account->activeCharges->sum('total'),
            'payments' => $account->payments->map(fn ($payment) => [
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'paid_at' => $payment->paid_at,
            ])->values(),
        ]);
    }
}
