<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class PartidasFacturasPagosController extends Controller
{
  protected $rules = array();

  /**
   * [Guarda en BD las partidas en la tabla partidas_facturas_pagos repentando las reglas establecidas]
   * @param  Request  $request [Objeto de datos del POST]
   * @return Response          [Array en formato JSON]
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), $this->rules);

    if ($validator->fails()) {
      return response()->json(array(
        'status' => false,
        'errors' => $validator->errors()->all()
      ));
    }

    $partidas_pagos = new \App\PartidasFacturasPagos();
    $partidas_pagos->fill($request->all());
    $partidas_pagos->save();
    // // code...
    $partidas_facturas = \App\PartidasFactura::where('id', $request->partidas_facturas_id)->first();
    $facturas = \App\Factura::where('id', $partidas_facturas->factura_id)->first();
    // return response()->json(array('status' => true));
    return response()->json($facturas);
  }

  /**
   * [Consulta en BD de la tabla partidas_facturas_pagos donde id = $id proporcionado]
   * @param  Int      $id [id del GET]
   * @return Response     [Array en formato JSON]
   */
  public function show($id)
  {
    // echo $id;
    $partidas = \App\PartidasFactura::where('factura_id', $id)->first();
    $partidas_pagos = \App\PartidasFacturasPagos::where('partidas_facturas_id', $partidas->id)->get();
    return response()->json($partidas_pagos);
  }

  /**
   * [Elimina en BD de la tabla partidas_facturas_pagos un registro donde id = $id porporcionado]
   * @param  String   $id [Cadena concatenada]
   * @return Response     [Array en formato JSON]
   */
  public function destroy($id)
  {
    $valores = explode('&', $id);
    $partida_factura = \App\PartidasFacturasPagos::where('id', '=', $valores[0])->delete();

    $factura = \App\Factura::where('id', $valores[1])->first();
    return response()->json($factura);
  }
}
