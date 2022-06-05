<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Nomina;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class NominaController extends Controller
{
    // protected $rules = array(
    //     'nombre' => 'required|max:50',
    //     'edad' => 'required|max:3',
    //
    // );

    public function index(Request $request, $id)
    {
      $nomina = DB::table('contratos')
      ->leftJoin('proyectos', 'proyectos.id', '=', 'contratos.proyecto_id')
      ->join('empleados AS Empleado', 'Empleado.id', '=', 'contratos.empleado_id')
      ->join('contrato_recibos AS ConRec', 'ConRec.contrato_id', '=', 'contratos.id')
      ->join('recibos', 'recibos.id', '=', 'ConRec.recibo_id')
      ->select('contratos.*',
      DB::raw("CONCAT(Empleado.nombre,' ',Empleado.ap_paterno,' ',Empleado.ap_materno) AS empleado"),'recibos.periodo AS p_periodo', 'proyectos.nombre AS p_nombre', 'proyectos.nombre_corto AS p_nombre_corto', 'proyectos.clave AS p_clave' )
      ->where('contratos.empleado_id', '=', $id)
      ->get();

      return response()->json(
          $nomina->toArray()
      );


    }

    public function store(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');
        // $validator = Validator::make($request->all(), $this->rules);
        //
        // if ($validator->fails()) {
        //     return response()->json(array(
        //         'status' => false,
        //         'errors' => $validator->errors()->all()
        //     ));
        // }
        $nomina = new Nomina();
        $nomina->fill($request->all());
        Utilidades::auditar($nomina, $nomina->id);
        $nomina->save();
        return response()->json(array(
            'status' => true
        ));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }


    public function update(Request $request, $id)
    {
        try{
        if (!$request->ajax()) return redirect('/');

        $nomina = Nomina::findOrFail($request->id);
        $nomina->fill($request->all());
         Utilidades::auditar($nomina, $nomina->id);
        $nomina->save();

        return response()->json(array(
            'status' => true
        ));
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
        
    }



    public function edit($id)
    {

    }

    public function show(Request $request)
    {
        $nomina = DB::table('contratos')
      ->leftJoin('proyectos', 'proyectos.id', '=', 'contratos.proyecto_id')
      ->join('empleados AS Empleado', 'Empleado.id', '=', 'contratos.empleado_id')
      ->join('contrato_recibos AS ConRec', 'ConRec.contrato_id', '=', 'contratos.id')
      ->join('recibos', 'recibos.id', '=', 'ConRec.recibo_id')
      ->select('contratos.*',
      DB::raw("CONCAT(Empleado.nombre,' ',Empleado.ap_paterno,' ',Empleado.ap_materno) AS empleado"),'recibos.periodo AS p_periodo', 'proyectos.nombre AS p_nombre', 'proyectos.nombre_corto AS p_nombre_corto', 'proyectos.clave AS p_clave' )
      ->get();

      return response()->json(
          $nomina->toArray()
      );

    }



    public function activar(Request $request)
    {

    }


}
