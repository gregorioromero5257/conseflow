<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traspaso;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use Validator;
use Illuminate\Validation\Rule;


class TraspasoController extends Controller
{
    /**
    * [Consulta en BD los registros de los traspasos en la tabla traspasos]
    * @return Response [arreglo]
    */
    public function index()
    {
      $arreglo = [];
      $ubicacion = Utilidades::ubicacion();
      if ($ubicacion == null) {
        $traspasos = DB::table('traspasos')
        ->leftJoin('empleados AS E','E.id','=','traspasos.empleado_transporta_id')
        ->leftJoin('stocks AS S','S.id','=','traspasos.stock_id')
        ->leftJoin('tipo_ubicacion AS TU','TU.id','=','traspasos.tipo_ubicacion_id')
        ->leftJoin('tipo_ubicacion AS O','O.id','=','traspasos.origen_id')
        ->select('traspasos.*','O.nombre AS origen','TU.nombre AS destino','S.nombre AS stoke_nombre',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS nombre_empledo"))->get();
        foreach ($traspasos as $key => $value) {
          $partida_traspaso = DB::table('partidas_traspasos')
          ->select(DB::raw("COUNT(partidas_traspasos.id) AS total"))->where('traspaso_id','=',$value->id)->where('cantidad' , '>' ,'0')->first();
          $arreglo [] = [
            'traspasos' => $value,
            'contador' => $partida_traspaso->total,
          ];
        }
      }
      else {
        $traspasos = DB::table('traspasos')
        ->leftJoin('empleados AS E','E.id','=','traspasos.empleado_transporta_id')
        ->leftJoin('stocks AS S','S.id','=','traspasos.stock_id')
        ->leftJoin('tipo_ubicacion AS TU','TU.id','=','traspasos.tipo_ubicacion_id')
        ->leftJoin('tipo_ubicacion AS O','O.id','=','traspasos.origen_id')
        ->select('traspasos.*','O.nombre AS origen','TU.nombre AS destino',
        'S.nombre AS stoke_nombre',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS nombre_empledo"))
        ->where('traspasos.origen_id','=',$ubicacion)->get();
        foreach ($traspasos as $key => $value) {
          $partida_traspaso = DB::table('partidas_traspasos')
          ->select(DB::raw("COUNT(partidas_traspasos.id) AS total"))->where('traspaso_id','=',$value->id)->where('cantidad' , '>' ,'0')->first();
          $arreglo [] = [
            'traspasos' => $value,
            'contador' => $partida_traspaso->total,
          ];
        }
      }
      return response()->json($arreglo);
    }

    /**
    * [Guarda en BD los encabezados en la tabla traspasos de acuerdo a los datos proporcionados]
    * @param Request   $request [Objeto de datos del POST]
    * @return Response          [Array con estatus true]
    */
    public function store(Request $request)
    {
      $ubicacion = Utilidades::ubicacion();
      $traspaso = new Traspaso();
      $traspaso->fecha_salida = $request->fecha_salida;
      $traspaso->empleado_transporta_id = $request->empleado_transporta_id;
      $traspaso->stock_id = $request->stock_id;
      $traspaso->comentarios = $request->comentarios;
      $traspaso->tipo_ubicacion_id = $request->tipo_ubicacion_id;
      $traspaso->origen_id = $ubicacion == null ? null : $ubicacion;
      $traspaso->save();

      return response()->json(array('status' => true));
    }

    /**
    * [Actualiza en BD los registros de la tabla Traspaso apartir del id]
    * @param  Request  $request [Objeto de datos del PUT]
    * @param  Int      $id      [id del traspaso] 
    * @return Response          [Array con estatus true]
    */
    public function update(Request $request, $id)
    {
      $ubicacion = Utilidades::ubicacion();
      $traspaso = Traspaso::where('id','=',$id)->first();
      $traspaso->fecha_salida = $request->fecha_salida;
      $traspaso->empleado_transporta_id = $request->empleado_transporta_id;
      $traspaso->stock_id = $request->stock_id;
      $traspaso->comentarios = $request->comentarios;
      $traspaso->tipo_ubicacion_id = $request->tipo_ubicacion_id;
      $traspaso->origen_id = $ubicacion == null ? null : $ubicacion;
      $traspaso->save();

      return response()->json(array('status' => true));
    }

    /**
    * [stockubicacion Filtra los lotes definidos en almacen así como cada stock ligado al mismo]
    * @param  Int      $id      [id del PUT] 
    * @return Response          [lote_almacen]
    */
    public function stockubicacion($id)
    {
      $ubicacion = Utilidades::ubicacion();
      if ($ubicacion == null) {
        $lote_almacen = DB::table('lote_almacen')
        ->leftJoin('lotes AS L','L.id','=','lote_almacen.lote_id')
        ->leftJoin('almacenes AS AL','AL.id','=','lote_almacen.almacene_id')
        ->leftJoin('tipo_ubicacion AS TU','TU.id','=','AL.ubicacion_id')
        ->leftJoin('niveles AS N','N.id','=','lote_almacen.nivel_id')
        ->leftJoin('stands AS ST','ST.id','=','lote_almacen.stand_id')
        ->leftJoin('stocks AS S','S.id','=','lote_almacen.stocke_id')
        ->leftJoin('proyectos AS P','P.id','=','S.proyecto_id')
        ->leftJoin('articulos AS A','A.id','=','lote_almacen.articulo_id')
        ->select('lote_almacen.*','TU.nombre AS nombre_ubicacion','S.nombre AS nombre_stock','L.nombre AS lote_nombre','A.id AS ida','A.nombre AS anombre','A.descripcion AS adescripcion','A.codigo AS acodigo'
        ,'A.marca AS amarca','A.unidad AS aunidad','P.nombre AS proyecton','P.id AS proyectoi','AL.nombre AS nombre_almacen')
        ->where('lote_almacen.stocke_id','=',$id)
        ->where('lote_almacen.cantidad','>','0')->get();
      }
      else {
        $lote_almacen = DB::table('lote_almacen')
        ->leftJoin('lotes AS L','L.id','=','lote_almacen.lote_id')
        ->leftJoin('almacenes AS AL','AL.id','=','lote_almacen.almacene_id')
        ->leftJoin('tipo_ubicacion AS TU','TU.id','=','AL.ubicacion_id')
        ->leftJoin('niveles AS N','N.id','=','lote_almacen.nivel_id')
        ->leftJoin('stands AS ST','ST.id','=','lote_almacen.stand_id')
        ->leftJoin('stocks AS S','S.id','=','lote_almacen.stocke_id')
        ->leftJoin('proyectos AS P','P.id','=','S.proyecto_id')
        ->leftJoin('articulos AS A','A.id','=','lote_almacen.articulo_id')
        ->select('lote_almacen.*','TU.nombre AS nombre_ubicacion','S.nombre AS nombre_stock','L.nombre AS lote_nombre','A.id AS ida','A.nombre AS anombre','A.descripcion AS adescripcion','A.codigo AS acodigo'
        ,'A.marca AS amarca','A.unidad AS aunidad','P.nombre AS proyecton','P.id AS proyectoi','AL.nombre AS nombre_almacen')
        ->where('lote_almacen.stocke_id','=',$id)
        ->where('AL.ubicacion_id','=',$ubicacion)
        ->where('lote_almacen.cantidad','>','0')->get();
      }


      return response()->json($lote_almacen);
    }

    /**
    * [ubicaciontraspaso Filtra las ubicaciones registradas en la BD]
    * @return Response [Array con estatus true]
    */
    public function ubicaciontraspaso()
    {
      $ubicacion = Utilidades::ubicacion();
      if ($ubicacion == null) {
        $tipo_ubicacion = \App\TipoUbicacion::all();
      }
      else {
        $tipo_ubicacion = \App\TipoUbicacion::where('id','!=',$ubicacion)->get();
      }

      return response()->json($tipo_ubicacion);
    }

    /**
  * [enviartraspaso Cambia el estado del traspaso en la BD]
  * @param  Int      $id      [id del traspaso] 
  * @return Response          [Array con estatus true]
  */
    public function enviartraspaso($id)
    {
      $traspaso = Traspaso::where('id','=',$id)->first();
      $traspaso->estado = 2;
      $traspaso->save();

      return response()->json(array('status' => true));
    }

    /**
    * [verpartidasvalidacion Realiza un conteo de las partidas de traspasos registradas]
    * @param  Int      $id      [id de la partida] 
    * @return Response          [cantidad]
    */
    public function verpartidasvalidacion($id)
    {
      $partida_traspaso = \App\PartidasTraspasos::where('traspaso_id','=',$id)->where('cantidad','>','0')->get();
      $cantidad = count($partida_traspaso);
      return response()->json($cantidad);
    }

    /**
    * [traspasosentransito Consulta los traspasos registrados y el empleado quien se encuentra realizandolo]
    * @return Response          [traspaso]
    */
    public function traspasosentransito()
    {
      $traspaso = DB::table('traspasos')
      ->leftJoin('empleados AS E','E.id','=','traspasos.empleado_transporta_id')
      ->leftJoin('stocks AS S','S.id','=','traspasos.stock_id')
      ->leftJoin('tipo_ubicacion AS TU','TU.id','=','traspasos.tipo_ubicacion_id')
      ->select('traspasos.*','TU.nombre AS destino','S.nombre AS stoke_nombre',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS nombre_empledo"))
      ->where('traspasos.estado','>','1')->get();
      return response()->json($traspaso);
    }

    /**
    * [almacenartraspaso Genera un nuevo registro del traspaso realizado de acuerdo a la partida y lote
    * definidos]
    * @param  Request $request  [Datos del traspaso] 
    * @return Response          [lote-nombre]
    */
    public function almacenartraspaso(Request $request)
    {
      $partida_traspaso = \App\PartidasTraspasos::where('id','=',$request->id_partida_traspaso)->first();
      $partida_traspaso->estado = 1;
      $partida_traspaso->save();

      $lote_almacenes = \App\LoteAlmacen::where('id','=',$partida_traspaso->lote_almacen_id)->first();
      $lote = \App\Lote::where('id','=',$lote_almacenes->lote_id)->first();
      $valores = explode('-',$lote->nombre);

      $lotes = new \App\Lote();
      $lotes->nombre = 'lote ';
      $lotes->entrada_id = $lote->entrada_id;
      $lotes->articulo_id = $lote->articulo_id;
      $lotes->cantidad = $lote->cantidad;
      $lotes->caducidad = ($lote->caducidad == 'null') ? null:$lote->caducidad;
      $lotes->save();
      $lotes->nombre = $valores[0].'-'.$valores[1].'-'.$lotes->id;
      $lotes->save();

      $lote_almacen = new \App\LoteAlmacen();
      $lote_almacen->lote_id = $lotes->id;
      $lote_almacen->almacene_id = $request->id_almacen;
      $lote_almacen->nivel_id = ($request->nivel_id == 'null') ? null:$request->nivel_id;
      $lote_almacen->stand_id = ($request->stand_id == 'null') ? null:$request->stand_id;
      $lote_almacen->cantidad = $partida_traspaso->cantidad;
      $lote_almacen->caducidad = ($lote->caducidad == 'null') ? null:$lote->caducidad;
      $lote_almacen->stocke_id = $lote_almacenes->stocke_id;
      $lote_almacen->articulo_id = $lote_almacenes->articulo_id;
      $lote_almacen->save();

      $this->guardarPrecio($lote_almacen);

      $existencias = new \App\Existencia();
      $existencias->id_lote = $lotes->id;
      $existencias->articulo_id = $lote_almacen->articulo_id;
      $existencias->almacene_id = $request->id_almacen;
      $existencias->nivel_id = ($request->nivel_id == 'null') ? null:$request->nivel_id;
      $existencias->stand_id = ($request->stand_id == 'null') ? null:$request->stand_id;
      $existencias->cantidad = $partida_traspaso->cantidad;
      $existencias->save();


      $datos = DB::table('lote_almacen')
      ->leftJoin('lotes AS L','L.id','=','lote_almacen.lote_id')
      ->leftJoin('partidas_entradas AS PE','PE.id','=','L.entrada_id')
      ->leftJoin('requisicion_has_ordencompras AS RHC','RHC.id','=','PE.req_com_id')
      ->leftJoin('requisiciones AS R','R.id','=','RHC.requisicione_id')
      ->select('RHC.*','R.proyecto_id','lote_almacen.stocke_id AS stokeid')->where('lote_almacen.id','=',$lote_almacen->id)->first();

      $hoy = date("Y-m-d");
      $hora = date("H:i:s");
      $movimiento = new \App\Movimiento();
      $movimiento->cantidad = $partida_traspaso->cantidad;
      $movimiento->fecha = $hoy;
      $movimiento->hora = $hora;
      $movimiento->tipo_movimiento = 'Traspaso';
      $movimiento->folio = 'Traspaso-'.$datos->proyecto_id.$datos->stokeid;
      $movimiento->proyecto_id =  $datos->proyecto_id;
      $movimiento->lote_id =  $lote_almacen->id;
      $movimiento->stocke_id =  $datos->stokeid;
      $movimiento->almacene_id = $request->id_almacen;
      $movimiento->stand_id = ($request->stand_id == 'null') ? null:$request->stand_id;
      $movimiento->nivel_id = ($request->nivel_id == 'null') ? null:$request->nivel_id;
      $movimiento->articulo_id = $lote_almacen->articulo_id;
      $movimiento->save();

      $stoke_art = DB::table('stock_articulos')->select('stock_articulos.*')
      ->where('stock_articulos.articulo_id','=',$lote_almacen->articulo_id)->where('stock_articulos.stocke_id','=',$datos->stokeid)
      ->first();
      if (!is_null($stoke_art)) {
        $cantida_n =$stoke_art->cantidad + $partida_traspaso->cantidad;
        DB::table('stock_articulos')
        ->where([
          ['articulo_id',	'=',	$lote_almacen->articulo_id],
          ['stocke_id', '=', $datos->stokeid],
        ])
        ->update(['cantidad' => $cantida_n]);
      }else {
        $stokearticulo = new \App\StockArticulo();
        $stokearticulo->cantidad = $partida_traspaso->cantidad;
        $stokearticulo->articulo_id = $lote_almacen->articulo_id;
        $stokearticulo->stocke_id = $datos->stokeid;
        $stokearticulo->save();
      }

    return $lote->nombre;
    }

    /**
    * [guardarPrecio Genera un nuevo registro con el nuevo lote generado y el precio unitario del mismo]
    * @param  Request $request  [lote] 
    * @return Response          [status => true]
    */
    public function guardarPrecio($lote){

      $datos = DB::table('lote_almacen')
      ->leftJoin('lotes AS L','L.id','=','lote_almacen.lote_id')
      ->leftJoin('partidas_entradas AS PE','PE.id','=','L.entrada_id')
      ->leftJoin('requisicion_has_ordencompras AS RHC','RHC.id','=','PE.req_com_id')
      ->select('RHC.*')->where('lote_almacen.id','=',$lote->id)->first();
      $precio_unitario = $datos->precio_unitario;
      $lote_id = $lote->id;
      $orden_compra = \App\Compras::where('id','=',$datos->orden_compra_id)->first();
      $porcentaje_impuestos = 0;
      $porcentaje_descuento = 0;
      $precio_unitario_total = 0;
      $impuestos = \App\Impuesto::where('orden_compra_id','=',$orden_compra->id)->first();
      if ($impuestos != null) {
        $cantidad_impuestos = \App\Impuesto::where('orden_compra_id','=',$orden_compra->id)->select(DB::raw("SUM(porcentaje) AS total_impuestos"))->first();
        $porcentaje_impuestos = $cantidad_impuestos->total_impuestos;
      }
      $porcentaje_descuento = $orden_compra->descuento;

      $precio_unitario_total = ($precio_unitario - (($precio_unitario/100) * $porcentaje_descuento));
      $precio_unitario_total = ($precio_unitario_total + (($precio_unitario_total/100) * $porcentaje_impuestos)) ;
      $precio = new \App\Precios();
      $precio->precio_unitario = $precio_unitario_total;
      $precio->lote_id = $lote_id;
      $precio->save();

      return response()->json(array('status' => true));
    }

    /**
    * [perdido Cambia el estado del traspaso para indicar que está perdido]
    * @param  $id  [Id del traspaso] 
    * @return Response          [status => true]
    */
    public function perdido($id)
    {
      $partida_traspaso = \App\PartidasTraspasos::where('id','=',$id)->first();
      $partida_traspaso->estado = 3;
      $partida_traspaso->save();
      return response()->json(array('status' => true));

    }

    /**
    * [daniado Cambia el estado del traspaso para indicar que está dañado]
    * @param  $id  [Id del traspaso] 
    * @return Response          [status => true]
    */
    public function daniado($id)
    {
      $partida_traspaso = \App\PartidasTraspasos::where('id','=',$id)->first();
      $partida_traspaso->estado = 2;
      $partida_traspaso->save();
      return response()->json(array('status' => true));

    }

    /**
    * [veratendidos Obtiene la cantidad de partidas de traspaso]
    * @param  $id  [Id del traspaso] 
    * @return Response          [count]
    */
    public function veratendidos($id)
    {
      $partida_traspaso = \App\PartidasTraspasos::where('traspaso_id','=',$id)->where('estado','=','0')->where('cantidad' ,'>','0')->get();
      $count = count($partida_traspaso);
      return response()->json($count);
    }

    /**
    * [finalizar Cambia el estado del traspaso para indicar que está finalizado]
    * @param  $id  [Id del traspaso] 
    * @return Response          [status => true]
    */
    public function finalizar($id)
    {
      $traspaso = \App\Traspaso::where('id','=',$id)->first();
      $traspaso->estado = 3;
      $traspaso->save();
      return response()->json(array('status' => true));
    }
}
