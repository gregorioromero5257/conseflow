<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $fecha = $request->fecha;
        if (!file_exists(storage_path().'/logs/auditoria-'.$fecha.'.log'))
            return response()->json([]);

        $logFile = file(storage_path().'/logs/auditoria-'.$fecha.'.log');
        $registros = [];

        foreach ($logFile as $log){
            array_push( $registros, 
                [
                    'f' => substr($log, 1, 19),
                    'o' => json_decode(substr($log, 35, strlen($log)))
                ]
            );
        }

        return response()->json($registros);
    }
}