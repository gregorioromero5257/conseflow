<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\RegistroResguardo;
use Illuminate\Validation\Rule;

class RegistroResguardoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $responsable = '';
        $fecha = date("Y-m-d");
        $lotes = \App\LoteAlmacen::where('id','=', $request->lote_id)->first();
        $articulos =\App\Articulo::where('articulos.id', '=', $lotes->articulo_id)->first();

        /*Verifica el tipo de salida para determinar el empleado responsable del resguardo*/
        if ($request->tiposalida_id == 1)//Taller
        {
            $salidaT =\App\Salida::where('salidas.id', '=', $request->salida_id)->first();
            $responsable = $salidaT->empleado_id;
        }

        if ($request->tiposalida_id == 2)//Sitio
        {
            $salidaS =\App\SalidaSitio::where('salidassitio.id', '=', $request->salida_id)->first();
            $responsable = $salidaS->empleado_solicita_id;
        }

        if ($request->tiposalida_id == 3)//Resguardo
        {
            $salidaR =\App\SalidasResguardo::where('salidasresguardo.id', '=', $request->salida_id)->first();
            $responsable = $salidaR->empleado_supervisor_id;
        }
        /*************************************************************************/

        /*Verifica si los articulos tienen asignado un tipo de resguardo
        y retorna la respuesta
        */
        if ($articulos->tipo_resguardo_id == NULL) {
            return response()->json(array(
                'status' => 'NULL'
            ));
        }
        if ($articulos->tipo_resguardo_id > 1) {
            $RegistroResguardo = new RegistroResguardo();
            $RegistroResguardo->tipo_resguardo_id = $articulos->tipo_resguardo_id;
            $RegistroResguardo->salida_id = $request->salida_id;
            $RegistroResguardo->tipo_salida_id = $request->tiposalida_id;
            $RegistroResguardo->responsable_id = $responsable;
            $RegistroResguardo->articulo_id = $articulos->id;
            $RegistroResguardo->cantidad = $request->cantidad;
            $RegistroResguardo->fecha_entrega = $fecha;
            $RegistroResguardo->fecha_devolucion = NULL;
            $RegistroResguardo->condicion = 1;
            $RegistroResguardo->save();

            return response()->json(array(
                'status' => true
            ));
        }

        if ($articulos->tipo_resguardo_id == 1) {
            return response()->json(array(
                'status' => 'Es 1'
            ));
        }
        /********************************************************************************/
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /*Consulta los registros de empleados que tengan uno o mas resguardos a su cargo*/
        $empleados = DB::table('registro_resguardo')
            ->join('empleados AS e', 'e.id', '=', 'registro_resguardo.responsable_id')
            ->join('contratos AS c', 'c.empleado_id', '=', 'registro_resguardo.responsable_id')
            ->join('puestos AS pu', 'pu.id', '=', 'e.puesto_id')
            ->join('proyectos AS p', 'p.id', '=', 'c.proyecto_id')
            ->select('e.id AS idempleado','e.ap_paterno', 'e.ap_materno', 'e.nombre AS empleado', 'pu.nombre AS puesto')
            ->where([
                    ['e.condicion', '=', 1],
                    ['c.condicion', '=', 1],
                    ['c.proyecto_id', $id == 0 ? '>' : '=', $id]
                ])
            ->distinct()
            ->get();

        return response()->json($empleados);
        /************************************************************************/
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /*Obtiene los registros del reguardo dependiendo del empleado*/
        $resguardos = RegistroResguardo::orderBy('id', 'asc')
            ->join('tipo_resguardo AS tr', 'tr.id', '=', 'registro_resguardo.tipo_resguardo_id')
            ->join('tipo_salidas AS ts', 'ts.id', '=', 'registro_resguardo.tipo_salida_id')
            ->join('articulos AS ar', 'ar.id', '=', 'registro_resguardo.articulo_id')
            ->select('registro_resguardo.id', 'registro_resguardo.tipo_resguardo_id', 'registro_resguardo.salida_id', 'registro_resguardo.tipo_salida_id', 'registro_resguardo.responsable_id', 'registro_resguardo.articulo_id', 'registro_resguardo.cantidad', 'registro_resguardo.fecha_entrega', DB::raw('IFNULL(registro_resguardo.fecha_devolucion, "En Resguardo")fecha_devolucion'), 'tr.nombre AS tr_nombre', 'ts.nombre AS ts_nombre', 'ar.nombre AS ar_nombre', 'ar.codigo AS ar_codigo', 'ar.descripcion AS ar_descripcion')
            ->where('registro_resguardo.responsable_id', '=', $id)
            ->get();

        return response()->json($resguardos);
        /********************************************************************************/
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
