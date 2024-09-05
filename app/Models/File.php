<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Class File
 *
 * @property $id
 * @property $name
 * @property $route
 * @property $user_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class File extends Model
{
    use SoftDeletes;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'route', 'user_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    public function uploadFile(Request $request, $route = "")
    {
        $file = new File();
        if ($request->hasFile('file')) {
            $archivo = $request->file('file');
            // $nombreArchivo =  $archivo->getClientOriginalName() . '_' . time();
            $nombreArchivo = uniqid() . '_' . time() . '.' . $archivo->getClientOriginalExtension();
            $rutaArchivo = $archivo->storeAs('public/' . $route, $nombreArchivo);
            $rutaPublica = Storage::url($rutaArchivo);

            $file = File::create([
                'name' => $nombreArchivo,
                'route' => $rutaPublica,
                'user_id' => Auth::id()

            ]);

            return $file->id;
        }

        return null;
    }

    public function updateFile(Request $request, $route = "")
    {
        if ($request->hasFile('file')) {
            
            Storage::delete(str_replace('/storage', 'public', $this->route));

           
            $archivo = $request->file('file');
            $nombreArchivo = uniqid() . '_' . time() . '.' . $archivo->getClientOriginalExtension();
            $rutaArchivo = $archivo->storeAs('public/' . $route, $nombreArchivo);
            $rutaPublica = Storage::url($rutaArchivo);

            
            $this->update([
                'name' => $nombreArchivo,
                'route' => $rutaPublica,
                'user_id' => Auth::id()
            ]);

            return $this->id;
        }

        return null;
    }
}
