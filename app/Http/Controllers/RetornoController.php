<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use App\Salida;
use Auth;
use App\Empleado;
use Illuminate\Validation\Rule;
use App\Requisicion;
use App\Compras;
use App\Entrada;

class RetornoController extends Controller
{


    public function index()
    {

    }

    /**
    * [partidasPorProyecto Obtiene de la Bd los registros de las partidas de taller y sitio]
    * @param  $id  [Id del proyecto] 
    * @return Response          [partidas]
    */
    public function partidasPorProyecto($id)
    {
        // Partidas taller
        $partidasTaller = DB::table('partidas AS p')
        ->join('salidas AS s', function ($join) {
            $join->on('p.salida_id', '=', 's.id')
                 ->where('p.tiposalida_id', '=', 1);
        })
         // ->join('salidas AS s','s.id','=','p.salida_id')
        ->join('lote_almacen AS l', 'p.lote_id', '=', 'l.id')
        ->join('proyectos AS pro','pro.id','=','s.proyecto_id')
        ->join('articulos AS a', 'l.articulo_id', '=', 'a.id')
        ->select('p.*','s.id as sid','a.id AS articulo_id','p.cantidad','a.nombre','a.codigo','a.descripcion',
           'a.marca','s.proyecto_id','pro.nombre_corto','a.unidad',DB::Raw('"Taller" AS tipo'),'s.folio')
       ->where('s.proyecto_id', '=', $id)
       ->where('p.cantidad','>','0')
       // ->where('p.tiposalida_id','=',1)
        ->get();

        // Partidas a sitio
        $partidasSitio = DB::table('partidas AS p')
        ->join('salidassitio AS s', function ($join) {
            $join->on('p.salida_id', '=', 's.id')
        ->where('p.tiposalida_id', '=', 2);
        })
        // ->join('salidassitio AS s','s.id','=','p.salida_id')
        ->join('lote_almacen AS l', 'p.lote_id', '=', 'l.id')
        ->join('articulos AS a', 'l.articulo_id', '=', 'a.id')
        ->join('proyectos AS pro','pro.id','=','s.proyecto_id')
        ->select('p.*','s.id as sid','a.id AS articulo_id','p.cantidad', 'a.nombre', 'a.codigo', 'a.descripcion',
         'a.marca','s.proyecto_id', 'pro.nombre_corto','a.unidad', DB::Raw('"Sitio" AS tipo'), 's.folio')
        ->where('s.proyecto_id', '=', $id)
        ->where('p.cantidad','>','0')
        // ->where('p.tiposalida_id', '=', 2)
        ->get();

        $partidas = array_merge($partidasTaller->toArray(), $partidasSitio->toArray());

        return response()->json(
            $partidas
        );

    }

    /**
    * [getRetornoProyecto Crear la requisicion, orden de compra y entrada para el retorno de articulos por
    * proyecto]
    * @param  $id  [Id del proyecto] 
    * @return Response          [Array 'requisicion' => $req,
    *                           'compra' => $ordenCompra,
    *                           'entrada' => $entrada]
    */
    public function getRetornoProyecto($id)
    {
        $req = Requisicion::where('folio','REQ-RETORNO')
                ->where('proyecto_id',$id)
                ->first();


        if (is_null($req)) {
            $req = new Requisicion();
            $req->folio = 'REQ-RETORNO';
            $req->area_solicita_id = 1;
            $req->fecha_solicitud = date('Y-m-d');
            $req->descripcion_uso = 'RETORNO';
            $req->tipo_compra = 1; // revisar
            $req->proyecto_id = $id;
            $req->estado_id = 1;
            $req->solicita_empleado_id = Auth::id();
            $req->autoriza_empleado_id = Auth::id();
            $req->valida_empleado_id = Auth::id();
            $req->save();

            $ordenCompra = new Compras();
            $ordenCompra->folio = 'COMPRA-RETORNO';
            $ordenCompra->ordenes_formato = '';
            $ordenCompra->condicion_pago_id = 1;
            $ordenCompra->periodo_entrega = '';
            $ordenCompra->fecha_orden = date('Y-m-d');
            $ordenCompra->lugar_entrega = '';
            $ordenCompra->observaciones = '';
            $ordenCompra->descuento = 0;
            $ordenCompra->tipo_cambio = 1;
            $ordenCompra->moneda = 2;
            $ordenCompra->referencia = '';
            $ordenCompra->proyecto_id = $id;
            $ordenCompra->proveedore_id = 1;
            // $ordenCompra->cotizacione_id = 1;
            $ordenCompra->estado_id = 3;
            $ordenCompra->elabora_empleado_id = Auth::id();
            $ordenCompra->autoriza_empleado_id = Auth::id();
            $ordenCompra->save();

            $tipo_entrada = \App\TipoEntrada::where('nombre','=','Retorno')->first();
            if(is_null($tipo_entrada)){
              $tipo_entrada = new \App\TipoEntrada();
              $tipo_entrada->nombre = 'Retorno';
              $tipo_entrada->save();
            }

            $entrada = new Entrada();
            $entrada->fecha = date('Y-m-d');
            $entrada->comentarios = 'RETORNO';
            $entrada->formato_entrada = '';
            $entrada->tipo_adq_id = 1;
            $entrada->tipo_entrada_id = $tipo_entrada->id;
            $entrada->empleado_almacen_id = Auth::id();
            $entrada->empleado_usuario_id = Auth::id();
            $entrada->orden_compra_id = $ordenCompra->id;
            $entrada->save();

            return response()->json(
                [
                    'requisicion' => $req,
                    'compra' => $ordenCompra,
                    'entrada' => $entrada
                ]
            );

        } else {


            $ordenCompra = Compras::where('folio','COMPRA-RETORNO')
                ->where('proyecto_id',$id)
                ->first();
            // if(!is_null($ordenCompra))
            $tipo_entrada = \App\TipoEntrada::where('nombre','=','Retorno')->first();

            $entrada = Entrada::where('orden_compra_id',$ordenCompra->id)
                ->where('tipo_entrada_id',$tipo_entrada->id)
                ->first();

            return response()->json(
                [
                    'requisicion' => $req,
                    'compra' => $ordenCompra,
                    'entrada' => $entrada,
                ]
            );


        }
    }

    /**
    * [guardarPartidaRetorno Genera un nuevo registro de una partida en la tabla Requisicionhasordencompras
    * de la BD]
    * @param  Request $request  [Datos de la partida] 
    * @return Response          [status => true]
    */
    public function guardarPartidaRetorno(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $articulo_id = $request->articulo_id;
        $orden_compra_id = $request->orden_compra_id;

        $requisicion_has_compra = new \App\Requisicionhasordencompras();
        $requisicion_has_compra->requisicione_id = $request->requisicion_id;
        $requisicion_has_compra->orden_compra_id  = $request->orden_compra_id;
        $requisicion_has_compra->articulo_id = $request->articulo_id;
        $requisicion_has_compra->cantidad = $request->cantidad;
        $requisicion_has_compra->precio_unitario = $request->precio;
        $requisicion_has_compra->condicion = 0;
        $requisicion_has_compra->tipo_entrada = $request->tipo_entrada;
        $requisicion_has_compra->save();

        if ($request->adicionales > 0) {
        $adicionales = new \App\Adicionales();
        $adicionales->req_has_comp = $requisicion_has_compra->id;
        $adicionales->cantidad = $request->adicionales;
        $adicionales->save();
        }

        $stock = \App\Stock::where('proyecto_id',$request->proyecto_id)->first();

        $partidaentrada = new \App\PartidaEntrada();
        $partidaentrada->entrada_id = $request->entrada_id;
        $partidaentrada->articulo_id = $request->articulo_id;
        $partidaentrada->req_com_id = $requisicion_has_compra->id;
        if ($request->caducidad)
        $partidaentrada->caducidad = $request->caducidad;
        $partidaentrada->cantidad = $request->cantidad;
        $partidaentrada->stocke_id = $stock->id;
        $partidaentrada->save();

        // $partida = \App\Partidas::where('id',$request->id)->first();
        // $partida->cantidad = $partida->cantidad - $request->cantidad;
        // $partida->save();

        return response()->json(array('status' => true));

    }

}
