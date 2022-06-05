<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InspeccionController extends Controller
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
        $datos_examinacion = new \App\DatosExaminacion();
        $datos_examinacion->distancia_al_objeto = $request->distancia_al_objeto;
        $datos_examinacion->angulo_aprox_superficie = $request->angulo_aprox_superficie;
        $datos_examinacion->condicion_superficial = $request->condicion_superficial;
        $datos_examinacion->save();

        $inspeccion = new \App\Inspeccion();
        $inspeccion->proyecto = $request->proyecto;
        $inspeccion->lugar_fabricacion = $request->lugar_fabricacion;
        $inspeccion->fecha = $request->fecha;
        $inspeccion->num_reporte = $request->num_reporte;
        $inspeccion->procedimento_wps = $request->procedimento_wps;
        $inspeccion->procedimento_pqr = $request->procedimento_pqr;
        $inspeccion->numero_plano = $request->numero_plano;
        $inspeccion->elemento_servicios_id = $request->elemento_servicios_id;
        $inspeccion->datos_examinacion_id = $datos_examinacion->id;
        $inspeccion->supervisor = $request->supervisor;
        $inspeccion->inspecciono = $request->inspecciono;
        $inspeccion->supervision = $request->supervision;
        $inspeccion->save();

        return response()->json(array('status' => true));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(Request $request, $id)
    {
        //
        $datos_examinacion = \App\DatosExaminacion::where('id','=',$request->datos_examinacion_id)->first();
        $datos_examinacion->distancia_al_objeto = $request->distancia_al_objeto;
        $datos_examinacion->angulo_aprox_superficie = $request->angulo_aprox_superficie;
        $datos_examinacion->condicion_superficial = $request->condicion_superficial;
        $datos_examinacion->save();

        $inspeccion = \App\Inspeccion::where('id','=',$id)->first();
        $inspeccion->proyecto = $request->proyecto;
        $inspeccion->lugar_fabricacion = $request->lugar_fabricacion;
        $inspeccion->fecha = $request->fecha;
        $inspeccion->num_reporte = $request->num_reporte;
        $inspeccion->procedimento_wps = $request->procedimento_wps;
        $inspeccion->procedimento_pqr = $request->procedimento_pqr;
        $inspeccion->numero_plano = $request->numero_plano;
        $inspeccion->elemento_servicios_id = $request->elemento_servicios_id;
        $inspeccion->datos_examinacion_id = $datos_examinacion->id;
        $inspeccion->supervisor = $request->supervisor;
        $inspeccion->inspecciono = $request->inspecciono;
        $inspeccion->supervision = $request->supervision;
        $inspeccion->save();

        return response()->json(array('status' => true));
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

    public function proyecto($id)
    {
      $pro = '';
      if($id == '1'){ $pro = 'TERMINAL DE PETROLÍFEROS VALLE DE MÉXICO'; }else {
        $pro = 'TERMINAL DE PETROLÍFEROS PUEBLA';
      }
      $inspeccion = \App\Inspeccion::join('servicios_inspeccion AS SI','SI.id','=','inspeccion.elemento_servicios_id')
      ->join('especimen AS E','E.id','=','inspeccion.especimen_id')
      ->join('datos_examinacion AS DE','DE.id','=','inspeccion.datos_examinacion_id')
      ->select('inspeccion.*','SI.nombre AS nombre_si','E.nombre AS nombre_e','E.especificacion_material','DE.distancia_al_objeto',
      'angulo_aprox_superficie','condicion_superficial')
      ->where('proyecto','=',$pro)
      ->get();

      return response()->json($inspeccion);
    }

    public function servicioselementos()
    {
      $servicios = \App\ServiciosInspeccion::all();
      return response()->json($servicios);
    }
}
