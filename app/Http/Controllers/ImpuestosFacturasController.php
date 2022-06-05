<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use \App\ImpuestoFactura;
use Validator;
use \App\Http\Helpers\Utilidades;

class ImpuestosFacturasController extends Controller
{


    /**
     * [index Obtiene todos los registros del modelo Imss]
     * @return Response [description]
     */
    public function index()
    {
      $impuestos = ImpuestoFactura::all();
      return response()->json($impuestos);
    }

    /**
     * [store Crea un nuevo registro en el modelo Imss]
     * @param  Request $request [description]
     * @return Response         [description]
     */
    public function store(Request $request)
    {
      try{
      $impuestos = new ImpuestoFactura();
      $impuestos->tipo = $request->tipo;
      $impuestos->nombre = $request->nombre;
      $impuestos->porcentaje = $request->porcentaje;
      Utilidades::auditar($impuestos, $impuestos->id);
      $impuestos->save();

      return response()->json(array('status' => true));
      } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    /**
     * [update Actualiza un registro en el modelo Imss]
     * @param  Request $request [description]
     * @return Response           [description]
     */
    public function update(Request $request)
    {
      try{
      $impuestos = ImpuestoFactura::where('id','=',$request->id)->first();
      $impuestos->tipo = $request->tipo;
      $impuestos->nombre = $request->nombre;
      $impuestos->porcentaje = $request->porcentaje;
      Utilidades::auditar($impuestos, $impuestos->id);
      $impuestos->save();

      return response()->json(array('status' => true));
       } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function eliminar($id)
    {
      $impuestos = ImpuestoFactura::where('id','=',$id)->delete();
      return response()->json(array('status' => true));
    }

    

}
