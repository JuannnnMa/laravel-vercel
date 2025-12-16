<?php

use App\Models\AnioAcademico;
use Illuminate\Support\Facades\Session;

if (!function_exists('anio_actual')) {
    function anio_actual() {
        // 1. Try to get from session
        if (Session::has('anio_seleccionado_id')) {
            $anio = AnioAcademico::find(Session::get('anio_seleccionado_id'));
            if ($anio) return $anio;
        }

        // 2. Default to active year
        return AnioAcademico::where('estado', true)->first();
    }
}
