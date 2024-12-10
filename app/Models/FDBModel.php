<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class FDBModel extends Model
{
    protected $connection = "firebird";
    /**
     * Inserta el registro en la tabla y devuelve una instancia de la clase con esos datos
     *
     * @param  array $data Un arreglo asociativo con los campos a insertar
     * @return User
     */
    public static function create(array $data)
    {
        $selfClass = get_called_class();
        $instance = new $selfClass();

        $fillable = $instance->fillable;
        $guarded = $instance->guarded;
        $pk = $instance->getKeyName();

        foreach ($data as $key => $value) {
            if (gettype($value) == 'string') {
                $data[$key] = '\'' . $value . '\'';
            }

            if (gettype($value) == 'boolean') {
                $data[$key] = $value ? 'true' : 'false';
            }

            if (gettype($value) == 'NULL') {
                unset($data[$key]);
            }

            if ((in_array('*', $guarded) && !in_array($key, $fillable)) || in_array($key, $guarded)) {
                unset($data[$key]);
            }
        }

        if (!isset($data[$pk])) {
            $data[$pk] = $selfClass::nextId();
        }

        $now = Carbon::now()->format('Y-m-d H:i:s');

        if ($instance->timestamps && !isset($data[$selfClass::CREATED_AT])) {
            $data[$selfClass::CREATED_AT] = '\'' . $now . '\'';
        }

        if ($instance->timestamps && !isset($data[$selfClass::UPDATED_AT])) {
            $data[$selfClass::UPDATED_AT] = '\'' . $now . '\'';
        }

        $query = 'INSERT INTO "' . $instance->getTable() . '"
        ("' . implode('", "', array_keys($data)) . '") VALUES
        (' . implode(', ', array_values($data)) . ')';

        DB::insert($query);

        return static::find($data[$pk]);
    }

    /**
     * Obtiene el menor id disponible para insertar
     *
     * @return int
     */
    public static function nextId()
    {
        $next = 1;
        $selfClass = get_called_class();
        $pk = (new $selfClass())->getKeyName();

        $max = self::orderByDesc($pk)->first();

        if ($max) {
            $next = $max->$pk + 1;
        }

        return $next;
    }
}
