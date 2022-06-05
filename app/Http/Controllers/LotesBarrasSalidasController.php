<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use App\Salida;
use App\SalidaSitio;
use App\SalidasResguardo;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;




class LotesBarrasSalidasController extends Controller
{
  //

  public function getN($codigo)
  {
    //SE HACE UN EXPLODE DEL CODIGO RECIBIDO PARA DEFINIR SI ES NUEVO CODIGO (SIN ESPACIOS) O ANTIGUO CODIGO (CON ESPACIOS)
    $inicio = explode(' ',$codigo);
    //NUEVO CODIGO
    if (count($inicio) == 1) {

      $aux="MCF";
      $pos=substr($codigo, 0,3);

      if ($pos === 'MCF')
      {
        // CODIGO INTERNO
        $num = str_replace("MCF", "", $codigo);

        $l_uno = DB::table('lote_almacen AS la')
        ->join('lotes as l','l.id','=','la.lote_id')
        ->join('articulos AS a','a.id','=','la.articulo_id')
        ->join('stocks AS s','s.id','=','la.stocke_id')
        ->join('proyectos AS p','p.id','=','s.proyecto_id')
        ->leftJoin('almacenes AS al','al.id','la.almacene_id')
        ->leftJoin('niveles AS niv','niv.id','la.nivel_id')
        ->leftJoin('stands AS st','st.id','la.stand_id')
        ->select('la.*','a.unidad','a.descripcion','a.grupo_id','l.nombre AS lote_nombre','p.id AS proyecto_id','p.nombre_corto','al.nombre as almacen_nombre'
        ,'niv.nombre as nivel_nombre','st.nombre AS stand_nombre')
        // ->where('la.codigo_barras','LIKE',$aux.'%')
        ->where('la.codigo_barras','LIKE','%'.$codigo.'%')
        ->where('la.cantidad','>','0')
        ->get()->toArray();

        $array_ids = [];
        foreach ($l_uno as $key_l => $value_l) {
          $array_ids [] = $value_l->id;
        }

        $l_dos = [];
        if (count($l_uno) > 0) {
          $l_dos = DB::table('lote_almacen AS la')
          ->join('lotes as l','l.id','=','la.lote_id')
          ->join('articulos AS a','a.id','=','la.articulo_id')
          ->join('stocks AS s','s.id','=','la.stocke_id')
          ->join('proyectos AS p','p.id','=','s.proyecto_id')
          ->leftJoin('almacenes AS al','al.id','la.almacene_id')
          ->leftJoin('niveles AS niv','niv.id','la.nivel_id')
          ->leftJoin('stands AS st','st.id','la.stand_id')
          ->select('la.*','a.unidad','a.descripcion','a.grupo_id','l.nombre AS lote_nombre','p.id AS proyecto_id','p.nombre_corto','al.nombre as almacen_nombre'
          ,'niv.nombre as nivel_nombre','st.nombre AS stand_nombre')
          ->where('la.articulo_id',$l_uno[0]->articulo_id)
          ->whereNotIn('la.id',$array_ids)
          ->where('la.cantidad','>','0')
          ->get()->toArray();
        }
        $l = array_merge($l_uno,$l_dos);
      }
      else
      {

        // Proveedor CODIGO DADO DEL PROVEEDOR
        $l = DB::table('lote_almacen AS la')
        ->join('lotes as l','l.id','=','la.lote_id')
        ->join('articulos AS a','a.id','=','la.articulo_id')
        ->join('stocks AS s','s.id','=','la.stocke_id')
        ->join('proyectos AS p','p.id','=','s.proyecto_id')
        ->leftJoin('almacenes AS al','al.id','la.almacene_id')
        ->leftJoin('niveles AS niv','niv.id','la.nivel_id')
        ->leftJoin('stands AS st','st.id','la.stand_id')
        ->select('la.*','a.unidad','a.descripcion','a.grupo_id','l.nombre AS lote_nombre','p.id AS proyecto_id','p.nombre_corto','al.nombre as almacen_nombre'
        ,'niv.nombre as nivel_nombre','st.nombre AS stand_nombre')
        ->where('la.codigo_barras',$codigo)
        ->where('la.cantidad','>','0')
        ->get();
      }




    }else {
      //CODIGOS ANTIGUOS DEL SISTEMA
      $solo_numeros = str_replace('MCF ', '', $codigo);
      $valores = explode(' ', $solo_numeros);
      $texto_busqueda = ' ' . $valores[1] . ' ';
      // $nombre_l =  'lote '.(int)$valores[0].'-'.(int)$valores[1].'-'.(int)$valores[2];
      $l_uno = DB::table('lote_almacen AS la')
      ->join('lotes as l','l.id','=','la.lote_id')
      ->join('articulos AS a','a.id','=','la.articulo_id')
      ->join('stocks AS s','s.id','=','la.stocke_id')
      ->join('proyectos AS p','p.id','=','s.proyecto_id')
      ->leftJoin('almacenes AS al','al.id','la.almacene_id')
      ->leftJoin('niveles AS niv','niv.id','la.nivel_id')
      ->leftJoin('stands AS st','st.id','la.stand_id')
      ->select('la.*','a.unidad','a.descripcion','a.grupo_id','l.nombre AS lote_nombre','p.id AS proyecto_id','p.nombre_corto','al.nombre as almacen_nombre'
      ,'niv.nombre as nivel_nombre','st.nombre AS stand_nombre')
      ->where('la.codigo_barras',$codigo)
      ->where('la.cantidad','>','0')
      ->get()->toArray();

      $l_dos = DB::table('lote_almacen AS la')
      ->join('lotes as l','l.id','=','la.lote_id')
      ->join('articulos AS a','a.id','=','la.articulo_id')
      ->join('stocks AS s','s.id','=','la.stocke_id')
      ->join('proyectos AS p','p.id','=','s.proyecto_id')
      ->leftJoin('almacenes AS al','al.id','la.almacene_id')
      ->leftJoin('niveles AS niv','niv.id','la.nivel_id')
      ->leftJoin('stands AS st','st.id','la.stand_id')
      ->select('la.*','a.unidad','a.descripcion','a.grupo_id','l.nombre AS lote_nombre','p.id AS proyecto_id','p.nombre_corto','al.nombre as almacen_nombre'
      ,'niv.nombre as nivel_nombre','st.nombre AS stand_nombre')
      ->where('la.codigo_barras','LIKE','%'.$texto_busqueda.'%')
      ->where('la.codigo_barras','NOT LIKE','MCF '.$valores[1].'%')
      ->where('la.codigo_barras', '!=', $codigo)
      ->where('la.cantidad','>','0')
      ->get()->toArray();

      $l = array_merge($l_uno,$l_dos);

    }
    return response()->json($l);

  }

  /**
  *
  */
  public function getNR($id)
  {

    $valores = explode(' ',$id);
    $nombre_l =  'lote '.(int)$valores[0].'-'.(int)$valores[1].'-'.(int)$valores[2];

    $salidas = DB::select("
    select la.*,a.unidad,a.descripcion,a.grupo_id,lt.nombre AS lote_nombre,pr.id AS proyecto_id,pr.nombre_corto,
    p.id as partidaId,p.salida_id, p.lote_id, (p.cantidad-p.cantidad_retorno) as cantidad_r
    from partidas as p
    join lote_almacen as la on p.lote_id = la.id
    left join lotes as lt on p.lote_id = lt.id
    join articulos as a on a.id=la.articulo_id
    join salidas as s on p.salida_id =s.id
    join proyectos  as pr on pr.id = s.proyecto_id
    where p.cantidad_retorno < p.cantidad AND lt.nombre LIKE '$nombre_l'"
  );
  return response()->json($salidas);
}
/**
*
*/
public function getNL($id)
{

  $l = DB::table('lote_almacen AS la')
  ->join('lotes as l','l.id','=','la.lote_id')
  ->join('articulos AS a','a.id','=','la.articulo_id')
  ->join('stocks AS s','s.id','=','la.stocke_id')
  ->join('proyectos AS p','p.id','=','s.proyecto_id')
  ->leftJoin('almacenes AS al','al.id','la.almacene_id')
  ->leftJoin('niveles AS niv','niv.id','la.nivel_id')
  ->leftJoin('stands AS st','st.id','la.stand_id')
  ->select('la.*','a.unidad','a.descripcion','a.grupo_id','l.nombre AS lote_nombre','p.id AS proyecto_id','p.nombre_corto','al.nombre as almacen_nombre'
  ,'niv.nombre as nivel_nombre','st.nombre AS stand_nombre')
  ->where('l.nombre','LIKE','%'.$id.'%')
  ->first();

  return response()->json($l);
}
/**
**
*/
public function store(Request $request)
{
  try {
    DB::beginTransaction();


    $salida = $this->GuardarEncabezado($request);
    // return response()->json($salida);
    $partidas = new \App\Partidas();
    $partidas->salida_id = $salida->id;
    $partidas->tiposalida_id = $request->salida_tipo;
    $partidas->lote_id = $request->lote_id;
    $partidas->cantidad = $request->cantidad;
    Utilidades::auditar($partidas, $partidas->id);
    $partidas->save();



    $tipo_salida_nombre = \App\TipoSalida::where('id','=',$request->salida_tipo)->first();

    $lote_almacen = \App\LoteAlmacen::where('id','=',$partidas->lote_id)->first();
    $cantidadresta = $lote_almacen->cantidad - $request->cantidad;
    $lote_almacen->cantidad = $cantidadresta;
    Utilidades::auditar($lote_almacen, $lote_almacen->id);
    $lote_almacen->update();

    $stokearticulo = \App\StockArticulo::where('articulo_id','=',$lote_almacen->articulo_id)
    ->where('stocke_id','=',$lote_almacen->stocke_id)->first();
    $cantidadrestastoke = $stokearticulo->cantidad - $request->cantidad;
    $stokearticulo->cantidad = $cantidadrestastoke;
    Utilidades::auditar($stokearticulo, $stokearticulo->id);
    $stokearticulo->update();

    $existencias = \App\Existencia::where('id_lote','=',$lote_almacen->lote_id)->where('articulo_id','=',$lote_almacen->articulo_id)->first();
    $cantidadrestaexistencia = $existencias->cantidad - $request->cantidad;
    $existencias->cantidad = $cantidadrestaexistencia;
    Utilidades::auditar($existencias, $existencias->id);
    $existencias->update();

    $stok_request = DB::table('stocks')->where('proyecto_id',$request->proyecto_id)->first();
    $stocks = \App\Stock::where('id','=',$stokearticulo->stocke_id)->first();
    $stk_id = 0;
    if ($stok_request->id != $stocks->id ) {
      $stk_id = $stok_request->id;
      $proyectos = \App\Proyecto::where('id','=',$stok_request->proyecto_id)->first();
    }else {
      $stk_id = $stocks->id;
      $proyectos = \App\Proyecto::where('id','=',$stocks->proyecto_id)->first();
    }


    $hoy = date("Y-m-d");
    $hora = date("H:i:s");
    $movimiento = new \App\Movimiento();
    $movimiento->cantidad = $request->cantidad;
    $movimiento->fecha = $hoy;
    $movimiento->hora = $hora;
    $movimiento->tipo_movimiento = 'Salida ';
    $movimiento->folio = 'Salida-'.$lote_almacen->articulo_id.' a '.$tipo_salida_nombre->nombre;
    $movimiento->proyecto_id = $proyectos->id;
    $movimiento->lote_id =  $lote_almacen->id;
    $movimiento->stocke_id =  $stk_id;
    $movimiento->almacene_id = $lote_almacen->almacene_id;
    $movimiento->stand_id = ($lote_almacen->stand_id == 'null') ? null:$lote_almacen->stand_id;
    $movimiento->nivel_id = ($lote_almacen->nivel_id == 'null') ? null:$lote_almacen->nivel_id;
    $movimiento->articulo_id = $lote_almacen->articulo_id;
    Utilidades::auditar($movimiento, $movimiento->id);
    $movimiento->save();
    DB::commit();

    return response()->json(array('status' => true));
  } catch (\Throwable $e) {
    DB::rollBack();
    Utilidades::errors($e);
  }
}

/**
*
*/
public function GuardarEncabezado($request)
{
  try {

    $user = Auth::user();

    if ($request->salida_tipo == 1) //Taller
    {

      $hoy = date("Y-m-d");
      // Obtener Salida
      $salida = DB::table('salidas')
      ->where('fecha', 'like', date('Y-m-d'))
      ->where('proyecto_id', $request->proyecto_id)
      ->where('empleado_id', $request->empleado_id)
      ->first();

      if ($salida == null)
      {
        // Folio
        $folios = DB::table("salidas as s")
        ->where("proyecto_id", $request->proyecto_id)
        ->get();
        $n = count($folios);
        $folio = str_pad(($n + 1), 4, "0", STR_PAD_LEFT);

        // Empleado

        $id_empleado = $user->empleado_id;
        // Nueva salida
        $salida = new Salida();
        $salida->fecha = date('Y-m-d');
        $salida->folio = $folio;
        $salida->ubicacion = $request->ubicacion;
        $salida->proyecto_id = $request->proyecto_id;
        $salida->tiposalida_id = $request->salida_tipo;
        $salida->empleado_id = $request->empleado_id;
        $salida->empleado_entrega_id = $id_empleado;
        $supervisor=$this->getSupervisor($request->proyecto_id);
        $salida->supervisores_proyectos_id = $supervisor==null? "0":$supervisor->id;
        Utilidades::auditar($salida, $salida->id);
        $salida->save();
      }
      return $salida;

    }

    if ($request->salida_tipo == 2) //sitio
    {
      $s = SalidaSitio::select('id')->get();
      $total_salidas = count($s);

      $folio = $this->getFolio($request->lote_id,$total_salidas);
      $tipo_material = $this->TipoMat($request->lote_id);
      $salidas = DB::table('salidas')
      ->where('fecha','like',date('Y-m-d'))
      ->where('proyecto_id',$request->proyecto_id)
      ->where('empleado_id',$request->empleado_id)
      ->where('folio','LIKE','%'.$tipo_material.'%')
      ->first();
      if (isset($salidas) == false) {
        $salida = new SalidaSitio();
        $salida->fecha = date('Y-m-d');
        $salida->folio = $folio;
        $salida->ubicacion = $request->ubicacion;
        $salida->proyecto_id = $request->proyecto_id;
        $salida->tiposalida_id = $request->salida_tipo;
        $salida->empleado_solicita_id = $request->empleado_id;
        $salida->empleado_entrega_id =  $user->empleado_id;
        $salida->empleado_autoriza_id = $request->empleado_dos;
        $salida->empleado_recibe_id = $request->empleado_tres;
        $salida->supervisores_proyectos_id = $this->getSupervisor($request->proyecto_id);
        Utilidades::auditar($salida, $salida->id);
        $salida->save();
        return $salida;
      }else {
        return $salidas;
      }

    }
    if ($request->salida_tipo == 3) //Resguardo
    {
      $s = SalidasResguardo::select('id')->get();
      $total_salidas = count($s);

      $folio = $this->getFolio($request->lote_id,$total_salidas);
      $tipo_material = $this->TipoMat($request->lote_id);
      $salidas = DB::table('salidas')
      ->where('fecha','like',date('Y-m-d'))
      ->where('proyecto_id',$request->proyecto_id)
      ->where('empleado_id',$request->empleado_id)
      ->where('folio','LIKE','%'.$tipo_material.'%')
      ->first();
      if (isset($salidas) == false) {
        $salida = new SalidasResguardo();
        $salida->fecha = date('Y-m-d');
        $salida->folio = $folio;
        $salida->ubicacion = $request->ubicacion;
        $salida->proyecto_id = $request->proyecto_id;
        $salida->tiposalida_id = $request->salida_tipo;
        $salida->empleado_solicita_id = $request->empleado_id;
        $salida->empleado_entrega_id =  $user->empleado_id;
        $salida->empleado_supervisor_id = $request->empleado_dos;
        $salida->supervisores_proyectos_id = $this->getSupervisor($request->proyecto_id);
        Utilidades::auditar($salida, $salida->id);
        $salida->save();
        return $salida;
      }else {
        return $salidas;
      }
    }


  } catch (\Throwable $e) {
    Utilidades::errors($e);
  }
}

/**
*
**/
public function getFolio($lote_id, $total_salidas)
{
  try {
    $articulo_gpo = DB::table('lote_almacen AS lt')
    ->join('articulos AS a','a.id','lt.articulo_id')
    ->leftJoin('grupos AS g','g.id','a.grupo_id')
    ->select('a.id','g.nombre')
    ->where('lt.id',$lote_id)
    ->first();

    $folio = 'M';

    if (isset($articulo_gpo) == true) {
      $folio .= $articulo_gpo->nombre[0].$articulo_gpo->nombre[strlen($articulo_gpo->nombre)-1].'-'.str_pad(($total_salidas + 1), 4, "0", STR_PAD_LEFT);
    }else {
      $folio .='PENDIENTE'.str_pad(($total_salidas + 1), 4, "0", STR_PAD_LEFT);
    }

    return $folio;
  } catch (\Throwable $e) {
    Utilidades::errors($e);
  }
}

/**
**
*/
public function TipoMat($lote_id)
{
  try {
    $articulo_gpo = DB::table('lote_almacen AS lt')
    ->join('articulos AS a','a.id','lt.articulo_id')
    ->leftJoin('grupos AS g','g.id','a.grupo_id')
    ->select('a.id','g.nombre')
    ->where('lt.id',$lote_id)
    ->first();

    $folio = 'M';

    if (isset($articulo_gpo) == true) {
      $folio .= $articulo_gpo->nombre[0].$articulo_gpo->nombre[strlen($articulo_gpo->nombre)-1];
    }else {
      $folio .= 'PENDIENTE';
    }

    return $folio;
  } catch (\Throwable $e) {
    Utilidades::errors($e);
  }
}

/**
*
**/
public function getSalidasDia()
{
  $hoy = date('Y-m-d');
  $salidas_t = DB::table('partidas AS p')
  ->join('lote_almacen AS la','la.id','p.lote_id')
  ->join('lotes AS l','l.id','la.lote_id')
  ->join('articulos AS a','a.id','la.articulo_id')
  ->join('salidas AS s','s.id','p.salida_id')
  ->join('proyectos AS pr','pr.id','s.proyecto_id')
  ->join('empleados AS e','e.id','s.empleado_id')
  ->where('p.tiposalida_id','1')
  ->where('s.fecha',$hoy)
  ->select('p.*','s.fecha','s.folio','pr.nombre_corto','a.descripcion AS articulo','l.nombre as lote',
  DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_name"))
  ->get();
  return response()->json($salidas_t);
}

/**
*
*/
public function getBarras($id)
{
  $pe = DB::table('partidas_entradas AS pe')
  ->join('lotes AS l','l.entrada_id','pe.id')
  ->join('articulos AS a','a.id','l.articulo_id')
  ->select('l.nombre','a.descripcion')
  ->where('pe.id',$id)
  ->first();

  $solo_numeros = str_replace('lote ','',$pe->nombre);
  $valores = explode('-',$solo_numeros);
  $folio_codigo =  str_pad($valores[0], 4, "0", STR_PAD_LEFT).str_pad($valores[1], 4, "0", STR_PAD_LEFT).str_pad($valores[2], 4, "0", STR_PAD_LEFT);
  // $folio_codigo =  str_pad($valores[2], 4, "0", STR_PAD_LEFT).' 1';
  $barras = $valores[2];
  $des = $pe->descripcion;

  $pdf = PDF::loadView('pdf.codigoentrada', compact('barras','folio_codigo'));
  // $pdf->setPaper('A4', 'landscape');
  $pdf->setPaper(array(0,0,216,144));
  return $pdf->stream();
}

public function getBarrasC($id)
{

  // $pe = DB::table('partidas_entradas AS pe')
  // ->join('lotes AS l','l.entrada_id','pe.id')
  // ->join('articulos AS a','a.id','l.articulo_id')
  // ->select('l.nombre','a.descripcion')
  // ->where('pe.id',$id)
  // ->first();
  // $barras = [];
  // foreach ($request['data'] as $key => $value) {
  //   $barras [] = $value['name'];
  //     // code...
  // }
  $barras = explode('&',$id);
  $tamanio_d = count($barras);

  // var_export ($barras);
  // return response()->json(($barras[6]));

  // $solo_numeros = str_replace('lote ','',$pe->nombre);
  // $valores = explode('-',$solo_numeros);
  // $folio_codigo =  str_pad($valores[0], 4, "0", STR_PAD_LEFT).str_pad($valores[1], 4, "0", STR_PAD_LEFT).str_pad($valores[2], 4, "0", STR_PAD_LEFT);
  $folio_codigo = '-';
  $array_codigos =  [];
  $array_codigos_num =  [];
  // $folio_codigo =  str_pad($valores[2], 4, "0", STR_PAD_LEFT).' 1';
  // $barras = $valores[2];
  // $des = $pe->descripcion;
  for ($i = 0;$i < count($barras)-1;$i++) {
    $rhoc = DB::table('lote_almacen AS la')
    ->join('lotes AS l','l.id','la.lote_id')
    ->leftJoin('partidas_entradas AS pe','pe.id','l.entrada_id')
    ->leftjoin('requisicion_has_ordencompras AS rhoc','rhoc.id','pe.req_com_id')
    ->leftjoin('articulos AS a','a.id','rhoc.articulo_id')
    ->select('l.nombre')
    // ->where('rhoc.articulo_id','!=','NULL')
    ->where('la.id', $barras[$i])
    ->first();

    // dd($rhoc);

    $solo_numeros = str_replace('lote ','',$rhoc->nombre);
    $valores = explode('-',$solo_numeros);
    $folio_codigo = str_pad($valores[2], 4, "0", STR_PAD_LEFT);



    $codigo = 'MCF'.$folio_codigo;
    $array_codigos [] = $this->Codigo($codigo);
    $array_codigos_num [] = $barras[$i];
  }
  $array_descripciones = [];

  for ($j=0; $j < count($array_codigos_num) ; $j++) {


    $rhoc = DB::table('lote_almacen AS la')
    ->join('lotes AS l','l.id','la.lote_id')
    ->leftjoin('partidas_entradas AS pe','pe.id','l.entrada_id')
    ->leftjoin('requisicion_has_ordencompras AS rhoc','rhoc.id','pe.req_com_id')
    ->leftjoin('articulos AS a','a.id','rhoc.articulo_id')
    ->select('a.descripcion')
    // ->where('rhoc.articulo_id','!=','NULL')
    ->where('la.id',$array_codigos_num[$j])
    ->first();

    $array_descripciones [] = substr($rhoc->descripcion, 0, 1000);
  }
  // return response()->json($array_codigos_num);

  $pdf = PDF::loadView('pdf.codigoentrada', compact('tamanio_d','array_codigos','barras','folio_codigo','array_descripciones'));
  // $pdf->setPaper('A4', 'portrait');
  $pdf->setPaper("letter", "portrait");
  // $pdf->setPaper(array(0,0,216,144));
  return $pdf->stream();
}

public function Codigo($nombre)
{
  $barcode = new BarcodeGenerator();
  // $barcode->setText("ABCD");
  $barcode->setText($nombre);
  // $barcode->setType(BarcodeGenerator::Ean128);
  $barcode->setType(BarcodeGenerator::Code39);
  $barcode->setScale(4);
  $barcode->setThickness(25);
  $barcode->setFontSize(15);
  $code=$barcode->generate();
  return $code;



  // <div align="center" >
  // <div class="position">
  //   <img src="data:image/png;base64,'.{{$barras}}.'" alt="Goyo" width="200px" heigth="400px" style="padding-left: -25px;"/>
  // <br>
  // <label style="font-size: 8px; padding-top: -10px;
  //   font-family: Arial, Helvetica, sans-serif;
  //   text-align: justify;">{{$des}}</label>
  // </div>
  // </div>

}

public function getSupervisor($id)
{
  $supervisor = DB::table('supervisores_proyectos')
  ->where('proyecto_id',$id)
  ->where('activo','1')
  ->first();
  return $supervisor;
}

}
