<?php

namespace App\Services;

use App\Models\SystemVersion;

/**
 * Única fuente de verdad para incrementar la versión global de Recepciones
 * (ver ReceptionController::lastUpdateGlobal(), que la LEE, y los Observers
 * de Reception/*StatusHistory/Account/AdvancePayment, que la INCREMENTAN vía
 * touch()). Centraliza la sentencia SQL para que ningún Observer conozca la
 * tabla/columna subyacente ni pueda implementarla de forma distinta.
 *
 * Atomicidad: touch() usa Builder::increment(), que genera un único
 * `UPDATE system_versions SET version = version + 1 WHERE key = ?` -no hay
 * lectura previa del valor en PHP, así que dos llamadas concurrentes no
 * pueden pisarse entre sí (el motor serializa el UPDATE a nivel de fila).
 *
 * Transacciones: touch() no abre ninguna transacción propia ni especifica
 * una conexión distinta a la default -usa la misma conexión activa de quien
 * la llame-. Si un Observer se dispara dentro de un DB::transaction() ya
 * abierto (mismo patrón que ya usa este proyecto en RedSheetController,
 * BudgetConversionService, ReceptionTransferController, etc.), este UPDATE
 * pasa a formar parte de esa misma transacción y se revierte junto con ella
 * ante un rollback, sin necesitar código adicional aquí.
 */
class ReceptionVersionService
{
    public const KEY = 'receptions';

    /**
     * Incrementa atómicamente la versión de Recepciones en 1.
     */
    public function touch(): void
    {
        SystemVersion::where('key', self::KEY)->increment('version');
    }
}
