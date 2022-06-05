<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InfCapacitacionController extends Controller
{
    //

    public function index(Request $request)
    {
        $infEmp = DB::table('empleados')
            ->select('empleados.*')
            ->get();
        return response()->json($infEmp);
    }

    public function show($id)
    {
        $infEmp = DB::table('empleados')
            ->select('empleados.*','puestos.nombre as puesto')
            ->leftJoin('puestos','puestos.id','=','empleados.puesto_id')
            ->where('empleados.id',$id)
            ->first();
        return response()->json($infEmp);
    }
}
