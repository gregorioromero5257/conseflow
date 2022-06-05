<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Controltiempo;
use App\Compras;
use App\ComprasDBP;
use App\Proveedor;
use App\ProveedorDBP;
use App\Empleado;
use App\RequisicionhasordencomprasDBP;
use App\requisicionhasordencompras;
use App\ArticuloDBP;
use App\Articulo;
use App\CatServicios;
use App\CatServiciosDBP;
use App\Requisicion;
use App\RequisicionDBP;
use App\PartidaRe;
use App\Adicionales;
use \App\Http\Helpers\Utilidades;
use App\CostosProyectosServicios;
use App\LoteTemporal;

class HelpController extends Controller
{
  //
  public function busqueda()
  {
    // code...

    // $partidas_entradas = DB::table('partidas_entradas')
    // ->leftJoin('entradas AS E', 'E.id', '=', 'partidas_entradas.entrada_id')
    // ->leftJoin('articulos AS A', 'A.id', '=', 'partidas_entradas.articulo_id')
    // ->leftJoin('requisicion_has_ordencompras AS RHC','RHC.id','=','partidas_entradas.req_com_id')
    // ->leftJoin('adicionales AS AD','AD.req_has_comp','=','RHC.id')
    // ->leftJoin('ordenes_compras AS OC', 'OC.id' , '=', 'RHC.orden_compra_id')
    // ->leftJoin('requisiciones AS p', 'p.id', '=' , 'RHC.requisicione_id')
    // ->leftJoin('proyectos AS pr','pr.id','=','p.proyecto_id')
    // ->leftJoin('lotes AS l','l.entrada_id','=','partidas_entradas.id')
    // ->leftJoin('lote_almacen AS la','la.lote_id','=','l.id')
    // ->leftJoin('movimientos AS mov','mov.lote_id','=','la.id')
    //  ->leftJoin('stocks AS S','S.proyecto_id','=','pr.id')
    //
    // ->select('partidas_entradas.*',
    // 'A.id AS aid',
    // 'A.descripcion',
    // 'A.codigo',
    // 'A.marca',
    // 'OC.id AS idcompra',
    // 'OC.folio AS foliocompra',
    // 'RHC.cantidad AS cantidadcompra',
    // 'RHC.precio_unitario',
    // 'E.id AS entradaid',
    // 'mov.fecha AS fechaentrada',
    // 'p.proyecto_id AS proyecto_id',
    // 'pr.nombre AS proyectonombre',
    // 'S.nombre AS stokenombre',
    // 'S.id AS stokeid',
    // 'RHC.tipo_entrada',
    // 'AD.cantidad AS cantidad_adicional' )
    // ->where('partidas_entradas.entrada_id','=','1')->orderByRaw('id DESC')->where('RHC.articulo_id','!=','null')->get();


    // $partidas_entradas = DB::table('partidas_entradas')
    // ->leftJoin('entradas AS E', 'E.id', '=', 'partidas_entradas.entrada_id')
    // ->leftJoin('articulos AS A', 'A.id', '=', 'partidas_entradas.articulo_id')
    // ->leftJoin('requisicion_has_ordencompras AS RHC','RHC.id','=','partidas_entradas.req_com_id')
    // ->leftJoin('adicionales AS AD','AD.req_has_comp','=','RHC.id')
    // ->leftJoin('ordenes_compras AS OC', 'OC.id' , '=', 'RHC.orden_compra_id')
    // ->leftJoin('requisiciones AS p', 'p.id', '=' , 'RHC.requisicione_id')
    // ->leftJoin('proyectos AS pr','pr.id','=','p.proyecto_id')
    // ->leftJoin('lotes AS l','l.entrada_id','=','partidas_entradas.id')
    // ->leftJoin('lote_almacen AS la','la.lote_id','=','l.id')
    // ->leftJoin('movimientos AS mov','mov.lote_id','=','la.id')
    //  ->leftJoin('stocks AS S','S.proyecto_id','=','pr.id')
    // ->select(
    // 'mov.fecha AS fechaentrada'
    //   )
    // ->where('partidas_entradas.entrada_id','=','1')->groupBy('mov.fecha')->where('RHC.articulo_id','!=','null')->get();
    // $req = requisicionhasordencompras::join('lote_temporal AS lt', function ($join){
    //     $join->on('requisicion_has_ordencompras.requisicione_id','=','lt.requisicion_id')
    //     ->on('requisicion_has_ordencompras.articulo_id','=','lt.articulo_id');
    //   })->select('requisicion_has_ordencompras.*')->get();

    $arreglo = [];
    $ordenes_compras = Compras::Join('proveedores AS p','p.id','=','ordenes_compras.proveedore_id')
    // ->leftJoin('')
    ->select('p.razon_social','total','moneda','folio','ordenes_compras.id AS id_c','conrequisicion','fecha_orden')
    ->get();

    foreach ($ordenes_compras as $key => $value) {
      $partidas_compras = requisicionhasordencompras::leftJoin('requisiciones AS r','r.id','=','requisicion_has_ordencompras.requisicione_id')
      ->select('r.folio','r.descripcion_uso')
      ->where('orden_compra_id',$value->id_c)->where('r.folio','NOT LIKE','%SR')->distinct('requisicione_id')->get();
      $arreglo [] = [
        'compras' => $value,
        'requi' => $partidas_compras
      ];
    }


    return response()->json($arreglo);
  }


  /**
  * Para arreglar las entradas internas dentro de conserflow con material perteneciente aconstructora se hace los siguiente:
  * Se crea el proyecto conserflow dentro de aconstructora
  * Se crear una orden de comprasc con el articulos dado de alta
  *
  */
  public function crearEntradaInterna()
  {
    try {


      $partidas_entradas = DB::table('partidas_entradas')
      ->leftJoin('entradas AS E', 'E.id', '=', 'partidas_entradas.entrada_id')
      ->leftJoin('articulos AS A', 'A.id', '=', 'partidas_entradas.articulo_id')
      ->leftJoin('requisicion_has_ordencompras AS RHC','RHC.id','=','partidas_entradas.req_com_id')
      ->leftJoin('adicionales AS AD','AD.req_has_comp','=','RHC.id')
      ->leftJoin('ordenes_compras AS OC', 'OC.id' , '=', 'RHC.orden_compra_id')
      ->leftJoin('requisiciones AS p', 'p.id', '=' , 'RHC.requisicione_id')
      ->leftJoin('proyectos AS pr','pr.id','=','p.proyecto_id')
      ->leftJoin('lotes AS l','l.entrada_id','=','partidas_entradas.id')
      ->leftJoin('lote_almacen AS la','la.lote_id','=','l.id')
      ->leftJoin('movimientos AS mov','mov.lote_id','=','la.id')
      ->leftJoin('stocks AS S','S.proyecto_id','=','pr.id')
      ->select(
        'mov.fecha AS fechaentrada'
        )
        ->where('partidas_entradas.entrada_id','=','1')->groupBy('mov.fecha')->where('RHC.articulo_id','!=','null')->get();

        foreach ($partidas_entradas as $key => $value) {

          $compras_new = new ComprasDBP();
          $compras_new->folio = 'OC-CONSERFLOW-020-CONSERFLOW-'.str_pad(($key + 1), 3, "0", STR_PAD_LEFT);
          $compras_new->proyecto_id = 102;//
          $compras_new->condicion_pago_id = 1;
          $compras_new->periodo_entrega = 'INMEDIATO';
          $compras_new->fecha_orden = $value->fechaentrada;
          $compras_new->lugar_entrega = 'TEHUACÁN PUEBLA';
          $compras_new->observaciones = '';
          $compras_new->descuento = 0;
          $compras_new->total = 0;
          $compras_new->tipo_cambio = 0;
          $compras_new->moneda = 2;
          $compras_new->referencia = '';
          $compras_new->cie = '';
          $compras_new->sucursal = '';
          $compras_new->proveedore_id = 1;//
          $compras_new->elabora_empleado_id = 148;//
          $compras_new->autoriza_empleado_id = 148;//
          $compras_new->comentario_condicion_pago = '';
          $compras_new->conrequisicion = 0;
          Utilidades::auditar($compras_new, $compras_new->id);
          $compras_new->save();

          $requi = RequisicionDBP::where('descripcion_uso','=','ORDEN DE COMPRA SIN REQUISCION CON FOLIO: '.  $compras_new->folio)
          ->where('estado_id','=','11')->first();



          if ($requi == null) {
            $req = new RequisicionDBP();
            $req->folio = $compras_new->folio.'-SR';
            $req->area_solicita_id = 1;
            $req->formato_requisiciones = '---';
            $req->fecha_solicitud = $compras_new->fecha_orden;
            $req->descripcion_uso = 'ORDEN DE COMPRA SIN REQUISCION CON FOLIO: '.$compras_new->folio;
            $req->tipo_compra = 1;
            $req->proyecto_id = $compras_new->proyecto_id;
            $req->estado_id = 11;//sin requisicion
            $req->condicion = 2;
            $req->solicita_empleado_id = $compras_new->elabora_empleado_id;//cambiar por compras
            $req->autoriza_empleado_id = $compras_new->autoriza_empleado_id;
            $req->valida_empleado_id = $compras_new->elabora_empleado_id;
            Utilidades::auditar($req, $req->id);
            $req->save();

            $articulos = DB::table('partidas_entradas AS pe')
            ->leftJoin('articulos AS A', 'A.id', '=', 'pe.articulo_id')
            ->join('lotes AS l','l.entrada_id','=','pe.id')
            ->join('lote_almacen AS la','la.lote_id','=','l.id')
            ->join('movimientos AS m','m.lote_id','=','la.id')
            ->select('A.*','pe.id AS id_pe','pe.entrada_id','pe.req_com_id','pe.articulo_id','pe.validacion_calidad','pe.caducidad','pe.pendiente'
            ,'pe.stocke_id','pe.almacene_id','pe.nivel_id','pe.stand_id','pe.status AS pe_status','pe.precio_unitario','pe.factura_id'  )
            ->where('m.fecha','LIKE',$fechaentrada)
            ->get();

            foreach ($articulos as $key => $valor) {
              // $articulo_csct = ArticuloDBP::where('id',$valor->articulo_id)->first();
              $articulo_csct = ArticuloDBP::where('nombre','like','%'.$valor->nombre.'%')->first();
              $articulo_id = (isset($articulo_csct) == true) ? $articulo_csct : $this->crearArticulo($valor);
              $result =  $this->llenarPartidaRe($req, $request, $ordencompra);
            }



          }
          else {
            $articulos = DB::table('partidas_entradas AS pe')
            ->leftJoin('articulos AS A', 'A.id', '=', 'pe.articulo_id')
            ->join('lotes AS l','l.entrada_id','=','pe.id')
            ->join('lote_almacen AS la','la.lote_id','=','l.id')
            ->join('movimientos AS m','m.lote_id','=','la.id')
            ->select('A.*','pe.id AS id_pe','pe.entrada_id','pe.req_com_id','pe.articulo_id','pe.validacion_calidad','pe.caducidad','pe.pendiente'
            ,'pe.stocke_id','pe.almacene_id','pe.nivel_id','pe.stand_id','pe.status AS pe_status','pe.precio_unitario','pe.factura_id'  )
            ->where('m.fecha','LIKE',$fechaentrada)
            ->get();

            foreach ($articulos as $key => $valor) {
              // $articulo_csct = ArticuloDBP::where('id',$valor->articulo_id)->first();
              $articulo_csct = ArticuloDBP::where('nombre','like','%'.$valor->nombre.'%')->first();
              $articulo_id = (isset($articulo_csct) == true) ? $articulo_csct : $this->crearArticulo($valor);
              $result =  $this->llenarPartidaRe($req, $request, $ordencompra);
            }
            $result =  $this->llenarPartidaRe($requi, $request, $ordencompra);
          }
          $this->compraEC($result,$request);


          }



        } catch (\Throwable $e) {
          Utilidades::errors($e);
        }
      }

      public function crearArticulo($data)
      {
        $articulo = new ArticuloDBP();
        $articulo->grupo_id = $data->grupo_id;
        $articulo->tipo_resguardo_id=$data->trid;
        $articulo->calidad_id = $data->calidad_id;
        $articulo->codigo = $data->codigo;
        $articulo->nombre = $data->nombre;
        $articulo->descripcion = $data->descripcion;
        $articulo->nombreproveedor = $data->nombreproveedor;
        $articulo->marca = $data->marca;
        $articulo->unidad = $data->unidad;
        $articulo->comentarios = $data->comentarios;
        $articulo->minimo = $data->minimo;
        $articulo->maximo = $data->maximo;
        $articulo->condicion = '1';
        $articulo->centro_costo_id = $data->centro_costo_id;
        Utilidades::auditar($articulo, $articulo->id);
        $articulo->codigo_producto=$data->codigoProductoSat;
        $articulo->save();

        return $articulo;
      }


    }
