<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GenericModel extends FDBModel
{
    protected $guarded = [];
    protected $table;
    protected $primaryKey = "id";

    public function __construct(string $table, string $primary_key = null, bool $timestamps = true)
    {
        $this->table = $table;
        if ($primary_key) {
            $this->primaryKey = $primary_key;
        }
        if (!$timestamps) {
            $this->timestamps = false;
        }
    }

    /**
     * Inserta el registro en la tabla y devuelve el ID
     *
     * @param  array $data Un arreglo asociativo con los campos a insertar
     * @return SicmaModel
     */
    public function createGeneric(array $data)
    {
        $selfClass = get_called_class();

        $fillable = $this->fillable;
        $guarded = $this->guarded;
        $pk = $this->getKeyName();

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
            $data[$pk] = $this->getNextId();
        }

        $now = Carbon::now()->format('Y-m-d H:i:s');

        if ($this->timestamps && !isset($data[$selfClass::CREATED_AT])) {
            $data[$selfClass::CREATED_AT] = '\'' . $now . '\'';
        }

        if ($this->timestamps && !isset($data[$selfClass::UPDATED_AT])) {
            $data[$selfClass::UPDATED_AT] = '\'' . $now . '\'';
        }

        $query = 'INSERT INTO "' . $this->getTable() . '"
        ("' . implode('", "', array_keys($data)) . '") VALUES
        (' . implode(', ', array_values($data)) . ')';

        DB::insert($query);

        return $data[$pk];
    }

    public function allGeneric() {
        $query = 'SELECT * FROM "'.$this->table.'"';

        return DB::connection($this->connection)->select($query);
    }

    /**
     * Obtiene el menor id disponible para insertar
     *
     * @return int
     */
    private function getNextId()
    {
        $next = 1;
        $pk = $this->primaryKey;

        $max = DB::connection($this->connection)->select('SELECT "'.$pk.'" 
        FROM "'.$this->table.'" 
        ORDER BY "'.$pk.'" DESC');

        if ($max) {
            $next = $max[0]->$pk + 1;
        }

        return $next;
    }
}

