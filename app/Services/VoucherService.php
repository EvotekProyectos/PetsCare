<?php

namespace App\Services;

use App\Models\Voucher;
use App\Models\VoucherProduct;

/**
 * Cancelación de vales fuera del flujo manual con firma (ver
 * VoucherController::cancel()): usada cuando el sistema cancela un vale
 * automáticamente (consulta finalizada / alta / servicio eliminado), no el
 * usuario desde el modal de firma.
 */
class VoucherService
{
    /**
     * Cancela UN vale puntual si sigue Pendiente (no-op en cualquier otro
     * estatus, igual criterio que VoucherController::cancel()).
     */
    public function cancelVoucher(Voucher $voucher, string $reason): void
    {
        if ($voucher->status !== 'Pendiente') {
            return;
        }

        $voucher->update([
            'status' => 'Cancelado',
            'cancellation_reason' => $reason,
            'cancelled_by' => auth()->id(),
        ]);
    }

    /**
     * Cancela todos los vales Pendiente de una recepción (ver
     * AppointmentController::store(), HospitalizationController::discharge()/
     * dischargeDeath()): un consumible cuyo vale nunca se surtió no se cobra.
     */
    public function cancelPendingForReception(int $receptionId, string $reason): void
    {
        Voucher::where('reception_id', $receptionId)
            ->where('status', 'Pendiente')
            ->get()
            ->each(fn (Voucher $voucher) => $this->cancelVoucher($voucher, $reason));
    }

    /**
     * Quita un producto de su vale (ver AppointmentServiceController::removeService()/
     * RedSheetController::removeService()); si el vale se quedó sin productos,
     * lo cancela también, para no dejar un vale "activo" vacío.
     */
    public function removeVoucherProductAndKeepConsistent(VoucherProduct $voucherProduct, string $cancelReasonIfEmptied): void
    {
        $voucher = $voucherProduct->voucher;
        $voucherProduct->delete();

        if ($voucher && $voucher->voucherProducts()->count() === 0) {
            $this->cancelVoucher($voucher, $cancelReasonIfEmptied);
        }
    }
}
