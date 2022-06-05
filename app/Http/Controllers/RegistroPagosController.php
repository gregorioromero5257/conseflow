<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;


class RegistroPagosController extends Controller
{
  /**
  * [protected Reglas para el guardado y actualización de datos]
  * @var [type]
  */
  protected $rules = array(
    'dias_credito' => 'required|max:5',
  );

  /**
  * [index Consulta en la BD de los registro de la tabla registro_pagos]
  * @return Response [description]
  */
  public function index()
  {
    $registro_pagos = DB::table('registro_pagos')
    ->leftJoin('clientes AS C','C.id','=','registro_pagos.cliente_id')
    ->leftJoin('bancos_contabilidad AS BC','BC.id','=','registro_pagos.banco_contabilidad_id')
    ->leftJoin('catalogo_bancos AS CB','CB.id','=','BC.catalogo_banco_id')
    ->select('registro_pagos.*','C.nombre AS nombre_cliente','BC.numero_cuenta','CB.nombre AS nombre_banco')
    ->get();
    return response()->json($registro_pagos);
  }

  /**
  * [store Inserción de un nuevo registro en la BD]
  * @param  Request $request [description]
  * @return Response           [description]
  */
  public function store(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    $validator = Validator::make($request->all(), $this->rules);

    if ($validator->fails()) {
        return response()->json(array(
            'status' => false,
            'errors' => $validator->errors()->all()
        ));
    }
    $registro_pagos = new \App\RegistroPagos();
    $registro_pagos->fill($request->all());
    $registro_pagos->save();

    $fecha_problabe_pago= $this->condicionDias($request);

    $registro_pagos->fecha_pago = $fecha_problabe_pago;
    $registro_pagos->save();

    return response()->json(array('status' => true));


    // return response()->json($var);
  }

  /**
  * [condicionDias Calcular la fecha de pago dependiendo del los dias de credito y el tipo de dia]
  * @param  Array $registro_pagos [description]
  * @return Response                 [description]
  */
  public function condicionDias($registro_pagos)
  {
    $tipo_dia = $registro_pagos->tipo_dias;
    $fecha1 = $registro_pagos->fecha_corte;
    $dias = $registro_pagos->dias_credito;
    if ($tipo_dia == 1) {//Naturales
    $fecha_pago=  $this->diasNaturales($fecha1,$dias);
    }
    elseif ($tipo_dia == 2) {//Habiles
      $fecha_pago = date("Y-m-d", strtotime("$fecha1 + $dias days"));
    }
    return $fecha_pago;
  }

  /**
   * [diasNaturales Met]
   * @param  Date $fecha1 [description]
   * @param  Int $dias   [description]
   * @return Date_||_Function        [description]
   */
  public function diasNaturales($fecha1,$dias)
  {
    $contador = 0;
    $fecha2 = date("Y-m-d", strtotime("$fecha1 + $dias days"));
     for($i = date("Y-m-d", strtotime($fecha1 ."+ 1 days"));
      $i <= $fecha2;
      $i = date("Y-m-d", strtotime($i ."+ 1 days")) )
     {
       $fecha = date("w",strtotime($i));
      if ($fecha == 0 || $fecha == 6) {
       $contador ++;
       }

     }
     if ($contador != 0) {
      return $this->diasNaturales($fecha2, ($contador));
     }
     else {
       $fecha_pago = date("Y-m-d", strtotime("$fecha2 + $contador days"));
       return $fecha_pago;
     }
  }

  /**
  * [update Actualización de un registro buscado por id ]
  * @param  Request $request [description]
  * @param  Int  $id      [description]
  * @return Response           [description]
  */
  public function update(Request $request, $id)
  {
    if (!$request->ajax()) return redirect('/');

    $validator = Validator::make($request->all(), $this->rules);

    if ($validator->fails()) {
      return response()->json(array(
        'status' => false,
        'errors' => $validator->errors()->all()
      ));
    }
    $registro_pagos = \App\RegistroPagos::where('id','=',$id)->first();
    $registro_pagos->fill($request->all());
    $registro_pagos->save();

    $fecha_problabe_pago= $this->condicionDias($request);

    $registro_pagos->fecha_pago = $fecha_problabe_pago;
    $registro_pagos->save();
    return response()->json(array('status' => true));
  }

  /**
  * [show Consulta de un registro especifico en la BD]
  * @param  Int $id [description]
  * @return Response     [description]
  */
  public function show($id)
  {
    $registro_pagos = DB::table('registro_pagos')
    ->leftJoin('clientes AS C','C.id','=','registro_pagos.cliente_id')
    ->leftJoin('bancos_contabilidad AS BC','BC.id','=','registro_pagos.banco_contabilidad_id')
    ->leftJoin('catalogo_bancos AS CB','CB.id','=','BC.catalogo_banco_id')
    ->select('registro_pagos.*','C.nombre AS nombre_cliente','BC.numero_cuenta','CB.nombre AS nombre_banco')
    ->where('registro_pagos.id','=',$id)
    ->first();
    return response()->json($registro_pagos);
    // code...
  }

  /**
   * [clientepago Actualización del campo condicion del modelo RegistroPagos]
   * @param Int $id [description]
   * @return Response     [description]
   */
  public function clientepago($id)
  {
    $registro_pagos = \App\RegistroPagos::where('id','=',$id)->first();
    $registro_pagos->condicion = 0;
    $registro_pagos->save();

    $banco_contabilidad = \App\BancosContabilidad::where('id','=',$registro_pagos->banco_contabilidad_id)->first();
    $banco_contabilidad->total = $banco_contabilidad->total + $registro_pagos->total;
    $banco_contabilidad->save();
    return response()->json(array('status' => true));
  }

  /**
   * [registropagosdasboard consulta en la BD de la tabla registro_pagos para utilizar en el dashboard]
   * @return Response [description]
   */
  public function registropagosdasboard()
  {
    $today = date("Y-m-d");
    $nuevafecha = strtotime ( '+31 day' , strtotime ( $today ) ) ;
    $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
    $registro_pagos = DB::table('registro_pagos')
    ->leftJoin('clientes AS C','C.id','=','registro_pagos.cliente_id')
    ->leftJoin('bancos_contabilidad AS BC','BC.id','=','registro_pagos.banco_contabilidad_id')
    ->leftJoin('catalogo_bancos AS CB','CB.id','=','BC.catalogo_banco_id')
    ->select('registro_pagos.*','C.nombre AS nombre_cliente','BC.numero_cuenta','CB.nombre AS nombre_banco')
     ->whereBetween('registro_pagos.fecha_pago',[$today, $nuevafecha])
    ->orWhere('registro_pagos.condicion','=','1')
    ->get();
    return response()->json($registro_pagos);
  }
}
