<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

/**
 * Cachea (300s, mismo TTL ya usado en AppointmentController::consultation()
 * y BudgetDetailController::price()) el resultado de CUALQUIER lectura hecha
 * a través de este Builder. Pensado exclusivamente para modelos de catálogo
 * de Microsip/Firebird (Producto, Precios — ver newEloquentBuilder() en
 * ambos), donde el costo real no es la consulta en sí sino la conexión a
 * Firebird (~2-20s medidos en otros puntos de este proyecto), y los datos
 * (nombre/precio de un artículo) casi nunca cambian segundo a segundo.
 *
 * get() es el único punto de entrada real de Eloquent para LEER (find(),
 * first() y el eager-loading de relaciones -belongsTo, etc.- terminan
 * llamando a get() internamente), así que cachear aquí cubre automáticamente
 * cualquier uso presente o futuro de estos modelos sin tener que acordarse
 * de envolver cada controller/relación por separado.
 *
 * NO se aplica a la clase base FDBModel: otros modelos Firebird de este
 * proyecto (ej. Folio, usado como contador/secuencia en
 * OrdenVentaService::generar()) necesitan leer siempre el valor real más
 * reciente -cachear ahí produciría folios/IDs duplicados-. Solo Producto y
 * Precios son catálogo de solo lectura.
 */
class CachedFirebirdBuilder extends Builder
{
    public function get($columns = ['*'])
    {
        $key = 'firebird_eloquent_' . md5(get_class($this->getModel()) . '|' . $this->toSql() . '|' . serialize($this->getBindings()));

        return Cache::remember($key, 300, function () use ($columns) {
            return parent::get($columns);
        });
    }
}
