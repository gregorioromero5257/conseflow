<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;

class InformeController extends Controller
{
    //

    public function index (Request $request,$id)
    {
         $informe = DB::table('partidas_entradas')
             ->select('partidas_entradas.*','rho.precio_unitario as precio','articulos.descripcion as articulo')
             ->leftJoin('articulos','articulos.id','=','partidas_entradas.articulo_id')
             ->leftJoin('requisicion_has_ordencompras as rho','rho.id','=','partidas_entradas.req_com_id')
             ->leftJoin('stocks','stocks.id','=','partidas_entradas.stocke_id')
             -> where('partidas_entradas.pendiente','=',0)
             ->where('stocks.proyecto_id','=',$id)
             ->get();

         return response()->json($informe);
    }

}
