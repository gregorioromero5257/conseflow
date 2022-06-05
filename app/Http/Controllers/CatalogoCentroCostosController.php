<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CatalogoCentroCostos;
use \App\Http\Helpers\Utilidades;

class CatalogoCentroCostosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $catalogo = CatalogoCentroCostos::leftJoin('catalogo_centro_costos AS CCC','CCC.id','=','catalogo_centro_costos.catalogo_centro_costos_id')
        ->select('catalogo_centro_costos.*','CCC.nombre AS nombre_sub')
        ->where('catalogo_centro_costos.sub','!=','3')
        ->get();
        return response()->json($catalogo);
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
        if ($request->catalogo_centro_costos_id != '') {
          $catalogo_act = CatalogoCentroCostos::where('id',$request->catalogo_centro_costos_id)->first();
          $catalogo_act->sub = 3;
          $catalogo_act->save();
        }
        $catalogo = new CatalogoCentroCostos();
        $catalogo->fill($request->all());
         Utilidades::auditar($catalogo, $catalogo->id);
        $catalogo->save();
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
    public function show()
    {
      $catalogo = CatalogoCentroCostos::leftJoin('catalogo_centro_costos AS CCC','CCC.id','=','catalogo_centro_costos.catalogo_centro_costos_id')
      ->select('catalogo_centro_costos.*','CCC.nombre AS nombre_sub')
      ->where('catalogo_centro_costos.sub','!=','1')
      ->get();
      return response()->json($catalogo);
    }

    public function activardesactivar($id)
    {
      try{
      $valores = explode('&',$id);
      $catalogo = CatalogoCentroCostos::where('id',$valores[0])->first();
      $catalogo->estado = $valores[1];
       Utilidades::auditar($catalogo, $catalogo->id);
      $catalogo->save();
      return response()->json(array('status' => true));
       } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
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
      if ($request->catalogo_centro_costos_id != '') {
        $catalogo_act = CatalogoCentroCostos::where('id',$request->catalogo_centro_costos_id)->first();
        $catalogo_act->sub = 3;
        $catalogo_act->save();
      }
      $catalogo = CatalogoCentroCostos::where('id',$id)->first();
      $catalogo->fill($request->all());
      Utilidades::auditar($catalogo, $catalogo->id);
      $catalogo->save();
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
        //
    }
}
