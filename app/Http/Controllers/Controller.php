<?php

namespace App\Http\Controllers;

use App\Models\Reception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Impide registrar nuevos servicios sobre una recepción que ya fue
     * trasladada a otra
     */
    protected function guardReceptionNotTransferred(Reception $reception): void
    {
        if ($reception->isTransferred()) {
            abort(422, 'No se pueden registrar más servicios en una recepción que ya fue trasladada.');
        }
    }
}
