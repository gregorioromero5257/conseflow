<?php

namespace App\Http\Controllers;

use App\PlanCapacitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanCapacitacionController extends Controller
{
    //

    public function index(Request $request)
    {
        $capacitacion = DB::table('capacitacion')
            ->select('capacitacion.*','empresas.nombre as empresa')
               /* DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) AS nombre"))
            ->leftjoin('empleados', 'empleados.id', '=', 'capacitacion.empleado_id')*/
            ->leftjoin('empresas', 'empresas.id', '=', 'capacitacion.empresa_id')
            ->get();

        return response()->json($capacitacion);
    }

    public function store(Request $request)
    {

        if(!$request->ajax()) return redirect('/');

        $capacitacion = new PlanCapacitacion();
        $capacitacion->empresa_id = $request->empresa_id;
        $capacitacion->nombre_curso = $request->nombre_curso;
        $capacitacion->fecha_inicio = $request->fecha_inicio;
        $capacitacion->fecha_fin = $request->fecha_fin;
        $capacitacion->duracion = $request->duracion;
        $capacitacion->nombre_capacitador = $request->nombre_capacitador;
        $capacitacion->empresa_capacitadora = $request->empresa_capacitadora;
        $capacitacion->costo = $request->costo;
        $capacitacion->save();

        return response()->json(array('status' => true));

    }

    public function update (Request $request, $id){

        if(!$request->ajax()) return redirect('/');

        $capacitacionUpd = PlanCapacitacion::where('id',$id)->first();
        $capacitacionUpd->empresa_id = $request->empresa_id;
        $capacitacionUpd->nombre_curso = $request->nombre_curso;
        $capacitacionUpd->fecha_inicio = $request->fecha_inicio;
        $capacitacionUpd->fecha_fin = $request->fecha_fin;
        $capacitacionUpd->duracion = $request->duracion;
        $capacitacionUpd->nombre_capacitador = $request->nombre_capacitador;
        $capacitacionUpd->empresa_capacitadora = $request->empresa_capacitadora;
        $capacitacionUpd->costo = $request->costo;
        $capacitacionUpd->save();

        return response()->json(array('status' => true));

    }

    public function eliminar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $folio = PlanCapacitacion::findOrFail($request->id);
        $folio->delete();
    }

}
