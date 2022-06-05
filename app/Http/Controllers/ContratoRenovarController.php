<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ContratoRenovarController extends Controller
{

    /**
     * [index Consulta en la BD de los contratos por renovar]
     * @return [type] [description]
     */
    public function index()
    {
        $contratorenovar = DB::table('contratos_renovar')
        ->leftJoin('contratos AS C','C.id','=','contratos_renovar.contrato_id')
        ->leftJoin('empleados AS E','E.id','=','C.empleado_id')
        ->leftJoin('empresas AS EM', 'EM.id', '=', 'C.empresa_id' )
        ->leftJoin('proyectos AS P','P.id','=','C.proyecto_id')
        ->leftJoin('tipo_nomina AS TN','TN.id','=','C.tipo_nomina_id')
        ->leftJoin('tipo_contratos AS TC', 'TC.id', '=', 'C.tipo_contrato_id')
        ->leftJoin('tipo_ubicacion AS TU', 'TU.id', '=', 'C.tipo_ubicacion_id')
        ->select('contratos_renovar.*','C.id AS contrato_id','P.nombre AS nombre_proyecto','C.fecha_ingreso','TN.nombre AS nombre_tipo_nomina',
        'TC.nombre AS nombre_tipo_contrato','EM.nombre AS nombre_empresa','TU.nombre AS nombre_ubicacion',
        'C.fecha_fin','E.id AS empleado_id',DB::raw("CONCAT(E.nombre ,' ',E.ap_paterno,' ',E.ap_materno) AS empleado"))
        ->where('contratos_renovar.condicion','=','1')->get();
        return response()->json($contratorenovar);
    }

}
