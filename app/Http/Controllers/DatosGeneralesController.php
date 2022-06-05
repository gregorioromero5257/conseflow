<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Helpers\Utilidades;

class DatosGeneralesController extends Controller
{
    //
    /**
    * [index funcion que lista todos los registros del modelo DatosGenerales]
    */
    public function index($value='')
    {
      $datos = \App\DatosGenerales::get();
      return response()->json($datos);
    }

    /**
    * [store Funcion que guardar un registro en el modelo DatosGenerales]
    * @param request
    * @return response
    */
    public function store(Request $request)
    {
      try{
      $factura = new \App\DatosGenerales();
      $factura->fill($request->all());
      Utilidades::auditar($factura, $factura->id);
      $factura->save();

      return response()->json(array('status' => true));
       } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    /**
    * [update Funcion que actualiza en el modelo DatosGenerales ]
    * @param request
    * @param int $id
    * @return response
    */
    public function update(Request $request,$id)
    {
      try{
      $factura = \App\DatosGenerales::where('id',$id)->first();
      $factura->fill($request->all());
      Utilidades::auditar($factura, $factura->id);
      $factura->save();

      return response()->json(array('status' => true));
       } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function show($id)
    {
        $factura = \App\DatosGenerales::where('id',$id)->first();
        return response()->json($factura);

    }

}
