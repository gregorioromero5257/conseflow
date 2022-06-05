<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CentroCostos;
use App\PartidasCostos;
use \App\Http\Helpers\Utilidades;


class CentroCostosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $centro_costos = CentroCostos::get();
        return response()->json($centro_costos);
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
        try{
        $centro_costos = new CentroCostos();
        $centro_costos->fill($request->all());
        $centro_costos->save();

        $partidas_costos = PartidasCostos::where('id',$request->partida_costos_id)->first();
        $partidas_costos->asignado = 1;
        Utilidades::auditar($partidas_costos, $partidas_costos->id);
        $partidas_costos->save();

        return response()->json(array('status' => true));
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $valores = explode('&',$id);
        $costos = CentroCostos::join('partidas_costos AS PC','PC.id','=','centro_costos.partida_costos_id')
        ->where('centro_costos.catalogo_centro_costos_id',$valores[0])
        ->where('centro_costos.proyecto_id',$valores[1])
        ->select('centro_costos.*','PC.nombre')->get();
        return response()->json($costos);
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
        try{
        $centro_costos = CentroCostos::where('id',$id)->first();
        $centro_costos->nombre_partida = $request->nombre_partida;
        Utilidades::auditar($centro_costos, $centro_costos->id);
        $centro_costos->save();

        return response()->json(array('status' => true));
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
        $centro_costos = CentroCostos::where('id',$id)->first();

        $partidas_costos = PartidasCostos::where('id',$centro_costos->partida_costos_id)->first();
        $partidas_costos->asignado = 0;
        Utilidades::auditar($partidas_costos, $partidas_costos->id);
        $partidas_costos->save();

        $centro_costos_delete = CentroCostos::where('id',$id)->delete();
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

    }
}
