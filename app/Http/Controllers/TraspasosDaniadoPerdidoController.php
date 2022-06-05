<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traspaso;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use Validator;
use Illuminate\Validation\Rule;

class TraspasosDaniadoPerdidoController extends Controller
{
    /**
    * [index Obtiene las partidas de los traspasos que se encuentran es estado dañado ]
    * @return Response          [partida traspaso]
    */
    public function index()
    {
      $partida_traspaso = DB::table('partidas_traspasos')
      ->leftJoin('traspasos AS T','T.id','=','partidas_traspasos.traspaso_id')
      ->leftJoin('lote_almacen AS LA','LA.id','=','partidas_traspasos.lote_almacen_id')
      ->leftJoin('lotes AS L','L.id','=','LA.lote_id')
      ->leftJoin('almacenes AS AL','AL.id','=','LA.almacene_id')
      ->leftJoin('tipo_ubicacion AS TU','TU.id','=','AL.ubicacion_id')
      ->leftJoin('niveles AS N','N.id','=','LA.nivel_id')
      ->leftJoin('stands AS ST','ST.id','=','LA.stand_id')
      ->leftJoin('stocks AS S','S.id','=','LA.stocke_id')
      ->leftJoin('proyectos AS P','P.id','=','S.proyecto_id')
      ->leftJoin('articulos AS A','A.id','=','LA.articulo_id')
      ->select('partidas_traspasos.*','T.fecha_salida AS fechatraspaso','TU.nombre AS nombre_ubicacion','S.nombre AS nombre_stock','A.nombre AS anombre','A.codigo AS acodigo',
      'A.descripcion AS adescripcion','A.marca AS amarca','A.unidad AS aunidad')
      ->whereIn('partidas_traspasos.estado',[2, 3])->get();


      return response()->json($partida_traspaso);

    }

}
