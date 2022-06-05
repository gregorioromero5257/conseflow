<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;



class PreciosController extends Controller
{
    /**
     * [index description]
     * @return [type] [description]
     */
    public function index()
    {
      // code...
    }

    public function obtenerporProyecto($id)
    {
      // code...

        $partidasTaller = DB::table('partidas')
        ->leftJoin('lote_almacen AS LA','LA.id','=','partidas.lote_id')
        ->leftJoin('articulos AS A','A.id','=','LA.articulo_id')
        ->leftJoin('stocks AS S','S.id','=','LA.stocke_id')
        ->leftJoin('salidas AS sl', 'sl.id', '=', 'partidas.salida_id')
        ->leftJoin('proyectos AS PR','PR.id','=','sl.proyecto_id')
        ->leftJoin('precios AS P','P.lote_id','=','LA.id')
        ->leftJoin('tipo_salidas AS TS','TS.id','=','partidas.tiposalida_id')
        ->select('partidas.*','S.proyecto_id','P.precio_unitario','A.descripcion','PR.nombre_corto AS nombre','TS.nombre AS tipo_salida_nombre',DB::raw('(partidas.cantidad * P.precio_unitario) AS total'))
        ->where('sl.proyecto_id','=',$id)
        ->where('partidas.tiposalida_id','=','1')
        ->get()->toArray();

        $partidasSitio = DB::table('partidas')
        ->leftJoin('lote_almacen AS LA','LA.id','=','partidas.lote_id')
        ->leftJoin('articulos AS A','A.id','=','LA.articulo_id')
        ->leftJoin('stocks AS S','S.id','=','LA.stocke_id')
        ->leftJoin('salidassitio AS sl', 'sl.id', '=', 'partidas.salida_id')
        ->leftJoin('proyectos AS PR','PR.id','=','sl.proyecto_id')
        ->leftJoin('precios AS P','P.lote_id','=','LA.id')
        ->leftJoin('tipo_salidas AS TS','TS.id','=','partidas.tiposalida_id')
        ->select('partidas.*','S.proyecto_id','P.precio_unitario','A.descripcion','PR.nombre_corto AS nombre','TS.nombre AS tipo_salida_nombre',DB::raw('(partidas.cantidad * P.precio_unitario) AS total'))
        ->where('sl.proyecto_id','=',$id)
        ->where('partidas.tiposalida_id','=','2')
        ->get()->toArray();

        $partidasResguardo = DB::table('partidas')
        ->leftJoin('lote_almacen AS LA','LA.id','=','partidas.lote_id')
        ->leftJoin('articulos AS A','A.id','=','LA.articulo_id')
        ->leftJoin('stocks AS S','S.id','=','LA.stocke_id')
        ->leftJoin('salidasresguardo AS sl', 'sl.id', '=', 'partidas.salida_id')
        ->leftJoin('proyectos AS PR','PR.id','=','sl.proyecto_id')
        ->leftJoin('precios AS P','P.lote_id','=','LA.id')
        ->leftJoin('tipo_salidas AS TS','TS.id','=','partidas.tiposalida_id')
        ->select('partidas.*','S.proyecto_id','P.precio_unitario','A.descripcion','PR.nombre_corto AS nombre','TS.nombre AS tipo_salida_nombre',DB::raw('(partidas.cantidad * P.precio_unitario) AS total'))
        ->where('sl.proyecto_id','=',$id)
        ->where('partidas.tiposalida_id','=','3')
        ->get()->toArray();

        $partidas = array_merge($partidasTaller, $partidasSitio, $partidasResguardo);
      return $partidas;
    }

    /**
     * [show description]
     * @param  [Int] $id [description]
     * @return [Response]     [description]
     */

    public function show($id)
    {
      $partidas = $this->obtenerporProyecto($id);
      return response()->json($partidas);
    }

  /**
   * [descargar Exporta en formato pdf la consulta determinada]
   * @param  [Int] $id [description]
   * @return [Stream]     [description]
   */
    public function descargar($id)
    {
      $total = 0;
      $partidas = $this->obtenerporProyecto($id);

    foreach ($partidas as $key => $value) {
    $total += $value->total;

}

      $pdf = PDF::loadView('pdf.materiales', compact('partidas','total'));
      $pdf->setPaper("A4", "landscape");
      return $pdf->stream();
    }


}
