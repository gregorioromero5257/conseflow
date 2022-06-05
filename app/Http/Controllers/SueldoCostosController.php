<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SueldoCostos;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\DB;


class SueldoCostosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $sueldos = DB::table('sueldos_costos AS sc')
      ->leftJoin('puestos AS P','P.id','=','sc.puesto_id')
      ->select('sc.*','P.nombre')
      ->where('sc.estado','0')->get();

      return response()->json($sueldos);
    }

    public function delete($id)
    {
      try {
        $sueldos = SueldoCostos::where('id',$id)->delete();
        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
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
      // try {
        $sueldos_buscar = DB::table('sueldos_costos')->where('puesto_id','=',$request->puesto_id)->where('estado','=','0')->first();
        if (isset($sueldos_buscar) == true) {
          $sueldos_act = \App\SueldoCostos::where('id',$sueldos_buscar->id)->first();
          $sueldos_act->estado = 1;
          Utilidades::auditar($sueldos_act, $sueldos_act->id);
          $sueldos_act->save();
        }


        $actualizado = date("Y-m-d");

        $sueldos = new SueldoCostos();
        $sueldos->puesto_id = $request->puesto_id;

        $sueldos->salario_semana = $request->salario_semana;
        $sueldos->salario_diario = $request->salario_diario;
        $sueldos->salario_mensual = $request->salario_mensual;
        $sueldos->isr_mensual = $request->isr_mensual;
        $sueldos->imss_infonavit_empresa = $request->imss_infonavit_empresa;
        $sueldos->imss_infonavit_trabajador = $request->imss_infonavit_trabajador;
        $sueldos->finiquito_mensual = $request->finiquito_mensual;
        $sueldos->impuesto_estado = $request->impuesto_estado;
        $sueldos->salario_mensual_cal = $request->salario_mensual_cal;
        $sueldos->salario_prestaciones = $request->salario_prestaciones;
        $sueldos->salario_imss = $request->salario_imss;
        $sueldos->diferencia = $request->diferencia;

        $sueldos->actualizado = $actualizado;
        Utilidades::auditar($sueldos,$sueldos->id);
        $sueldos->save();

        return response()->json(array('status' => true));
      // } catch (\Throwable $e) {
      //   Utilidades::errors($e);
      // }
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
