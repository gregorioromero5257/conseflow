<?php

namespace App\Http\Controllers;


use App\ControlNomina;
use http\Env\Response;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;


class ControlNominaController extends Controller
{
    //
    protected function index(Request $request)
    {
        $empleadoPrincipal = DB::table('control_nomina')
            ->select('control_nomina.*', DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) AS nombre"), 'proyectos.nombre_corto as proyecto')
            ->join('empleados', 'empleados.id', '=', 'control_nomina.empleado_id')
            ->join('proyectos', 'proyectos.id', '=', 'control_nomina.proyecto_id')
            ->where('control_nomina.condicion', '=', '0')
            ->where('control_nomina.empleado_id', '=', $request->id)
            ->get();

        return response()->json($empleadoPrincipal);

    }

    public function generaReporte($id)
    {
        $control_nomina_suma = DB::table('control_nomina')
            ->select(DB::raw("SUM(control_nomina.sueldo) as suma"))
            ->where('control_nomina.condicion', '=', '0')
            ->where('control_nomina.empleado_id', '=', $id)
            ->first();

        $empleadoPrincipal = DB::table('control_nomina')
            ->select('control_nomina.*')
            ->where('control_nomina.condicion', '=', '0')
            ->where('control_nomina.empleado_id', '=', $id)
            ->get();

        $nominamov = new \App\MovimientosNomina();
        $nominamov->dias_trabajados = count($empleadoPrincipal);
        $nominamov->empleado_id = $empleadoPrincipal[0]->empleado_id;
        $nominamov->suma_salarios = $control_nomina_suma->suma;
        $nominamov->proyecto_id = $empleadoPrincipal[0]->proyecto_id;
        $nominamov->save();

        foreach ( $nominamov as $key => $value) {

            $sueldo = floatval($value->suma);
            $sdi = floatval($sueldo * 1.045890411);
            $dias = ($value->dias_trabajados);
            $transferencia = floatval($sdi*$dias);
            $total = floatval( $sueldo*$dias);
            $efectivo = floatval($total*($sdi*$dias));

            echo ($sueldo);

        }

        foreach ($empleadoPrincipal as $value) {


            $control_nomina_act = \App\ControlNomina::where('id', $value->id)->first();
            $control_nomina_act->condicion = 1;
            $control_nomina_act->nommov_id = $nominamov->id;
            $control_nomina_act->salario_total = $control_nomina_act[0]->sdi;
            $control_nomina_act->save();
        }

        return response()->json(array('status' => true));
    }

    public function verReporte($id)
    {

        $verReporte = DB::table('nominas_movimientos')
            ->select('nominas_movimientos.*', DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) AS nombre"),'control_nomina.salario_total as sdi',
                'proyectos.nombre_corto as proyecto', 'control_nomina.fecha as fecha', 'control_nomina.condicion as condicion')
            ->leftJoin('control_nomina', 'control_nomina.nommov_id', '=', 'nominas_movimientos.id')
            ->leftJoin('empleados', 'empleados.id', 'nominas_movimientos.empleado_id')
            ->leftjoin('proyectos', 'proyectos.id', '=', 'nominas_movimientos.proyecto_id')
            ->leftjoin('contratos', 'contratos.proyecto_id', '=', 'proyectos.id')
            ->where('nominas_movimientos.empleado_id', '=', $id)
            ->where('control_nomina.condicion', '<>', null)
            ->distinct()->get();
/*dd($verReporte);*/
        return response()->json($verReporte);

    }

    public function principalEmpleado(Request $request)
    {
        $empleadoNominaC = DB::table('empleados')
            ->select('empleados.*')
            ->get();

        return response()->json($empleadoNominaC);

    }

    public function store(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');

        $controlNomina = new ControlNomina();
        $controlNomina->fecha = $request->fecha;
        $controlNomina->proyecto_id = $request->proyecto_id;
        $controlNomina->empleado_id = $request->empleado_id;
        $controlNomina->sueldo = $request->sueldo;
        Utilidades::auditar($controlNomina, $controlNomina->id);
        $controlNomina->save();

        return response()->json(array('status' => true));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

    }

    public function update(Request $request, $id)
    {
        try{
        if (!$request->ajax()) return redirect('/');

        $controlNomina = ControlNomina::where('id', $id)->first();

        $controlNomina->fecha = $request->fecha;
        $controlNomina->proyecto_id = $request->proyecto_id;
        $controlNomina->empleado_id = $request->empleado_id;
        $controlNomina->sueldo = $request->sueldo;
        Utilidades::auditar($controlNomina, $controlNomina->id);
        $controlNomina->save();

        return response()->json(array('status' => true));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

    }

    public function calculoNomina(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');
        $nomina = new \App\Nomina();
        $nomina->fecha_inicial = $request->fecha_inicial;
        $nomina->fecha_final = $request->fecha_final;
        $nomina->periodo = $request->periodo;
        $nomina->tipo_nomina = 1;
        Utilidades::auditar($nomina, $nomina->id);
        $nomina->save();
        $tiposContrato = DB::table('contratos')
            ->leftJoin('tipo_contratos', 'tipo_contratos.id', '=', 'contratos.tipo_contrato_id')
            ->leftJoin('tipo_nomina', 'tipo_nomina.id', '=', 'contratos.tipo_nomina_id')
            ->leftJoin('empleados', 'empleados.id', '=', 'contratos.empleado_id')
            ->leftJoin('tipo_ubicacion AS Ubi', 'Ubi.id', '=', 'contratos.tipo_ubicacion_id')
            ->leftJoin('sueldos', 'sueldos.contrato_id', '=',  'contratos.id')
            ->leftJoin('proyectos AS P','P.id','=','contratos.proyecto_id')
            ->leftJoin('datos_bancarios_empleados', 'datos_bancarios_empleados.empleado_id', '=', 'contratos.id')
            ->select('contratos.*','P.id AS nombrep','sueldos.sueldo_diario_integral AS sueldo_diario_integral','sueldos.sueldo_diario_neto AS sueldo_diario_neto','sueldos.infonavit AS infonavit','empleados.id AS empleado_id')
            ->where('contratos.condicion', '=', '1')
            ->where('tipo_nomina.id', '=', '2')
            ->where('empleados.condicion','=','1')
            ->orderBy('contratos.id', 'ASC')
            ->get();
        //    MovimientosNomina
        foreach ($tiposContrato as $key => $value) {
            $bancos = DB::table('datos_bancarios_empleados')->select('datos_bancarios_empleados.*')
                ->where('datos_bancarios_empleados.empleado_id','=',$value->empleado_id)->first();
            $a=floatval($value->sueldo_diario_integral);
            $b= $request->dias_num;
            $c=floatval($value->infonavit);
            $d=floatval($value->sueldo_diario_neto);
            $e=0;
            $f=0;
            $transferencia=($a*$b)-$c;
            $total=($d*$b);
            $efectivo=($total-($a * $b));
            $banco = null;
            if(is_null($bancos)){
                $banco = null;
            }
            else {
                $banco = $bancos->id;
            }
            $mov_nomi = new \App\MovimientosNomina();
            $mov_nomi->dias_trabajados = $b;
            $mov_nomi->transferencias = $transferencia;
            $mov_nomi->efectivos = $efectivo;
            $mov_nomi->otros = 0;
            $mov_nomi->descuento = 0;
            $mov_nomi->totales = $total;
            $mov_nomi->empleado_id = $value->empleado_id;
            $mov_nomi->contrato_id = $value->id;
            $mov_nomi->nomina_id = $nomina->id;
            $mov_nomi->banco_id = $banco;
            $mov_nomi->proyecto_id = $value->nombrep;
            Utilidades::auditar($mov_nomi, $mov_nomi->id);
            $mov_nomi->save();
            // }
        }    // return response()->json($request);

        return response()->json(array(
            'status' => true
        ));
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }
     
}
