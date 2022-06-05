<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class VentasArticulosController extends Controller
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
        //
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

    public function getArticulos()
    {
        $datos = DB::table('lote_almacen')
            ->leftJoin('lotes AS L','L.id','=','lote_almacen.lote_id')
            ->leftJoin('almacenes AS AL','AL.id','=','lote_almacen.almacene_id')
            ->leftJoin('niveles AS N','N.id','=','lote_almacen.nivel_id')
            ->leftJoin('stands AS ST','ST.id','=','lote_almacen.stand_id')
            ->leftJoin('stocks AS S','S.id','=','lote_almacen.stocke_id')
            ->leftJoin('articulos AS A','A.id','=','lote_almacen.articulo_id')
            ->select('lote_almacen.*','L.nombre AS lote_nombre','A.id AS ida','A.nombre AS anombre','A.descripcion AS adescripcion','A.codigo AS acodigo','A.marca AS amarca','A.unidad AS aunidad')
            ->where('lote_almacen.stocke_id', '=', 1)
            ->get();

        return response()->json($datos);
    }
}
