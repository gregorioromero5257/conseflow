<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use \App\FactorIntegracion;
use Validator;
use \App\Http\Helpers\Utilidades;

class FactorIntegracionController extends Controller
{
  /**
   * [protected Se definen las reglas para guardado y actualizacion de registro]
   * @var [type]
   */
  protected $rules = array(
    'anio_servicio' => 'required|max:3',
    'dias_aguinaldo' => 'required|max:2',
    'dias_vacaciones' => 'required|max:2',
    'prima_vacacional' => 'required',
    'factor_integracion' => 'required',
  );

  /**
   * [index Obtiene todos los registros del modelo FactorIntegracion]
   * @return Response [description]
   */
  public function index()
  {
    $factor_integracion = FactorIntegracion::all();
    return response()->json($factor_integracion);
  }

  /**
   * [store Crea un nuevo registro en el modelo FactorIntegracion]
   * @param  Request $request [description]
   * @return Response         [description]
   */
  public function store(Request $request)
  {
    try{
    if (!$request->ajax()) return redirect('/');
    $validator = Validator::make($request->all(), $this->rules);

    if ($validator->fails()) {
        return response()->json(array(
            'status' => false,
            'errors' => $validator->errors()->all()
        ));
    }
    $factor_integracion = new FactorIntegracion();
    $factor_integracion->anio_servicio = $request->anio_servicio;
    $factor_integracion->dias_aguinaldo = $request->dias_aguinaldo;
    $factor_integracion->dias_vacaciones = $request->dias_vacaciones;
    $factor_integracion->prima_vacacional = $request->prima_vacacional;
    $factor_integracion->factor_integracion = $request->factor_integracion;
    Utilidades::auditar($factor_integracion, $factor_integracion->id);
    $factor_integracion->save();
    return response()->json(array('status' => true));
     } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }

  /**
   * [update Actualiza un registro en el modelo FactorIntegracion]
   * @param  Request $request [description]
   * @return Response           [description]
   */
  public function update(Request $request, $id)
  {
    try{
    if (!$request->ajax()) return redirect('/');
    $validator = Validator::make($request->all(), $this->rules);

    if ($validator->fails()) {
        return response()->json(array(
            'status' => false,
            'errors' => $validator->errors()->all()
        ));
    }
    $factor_integracion = FactorIntegracion::where('id','=',$id)->first();
    $factor_integracion->anio_servicio = $request->anio_servicio;
    $factor_integracion->dias_aguinaldo = $request->dias_aguinaldo;
    $factor_integracion->dias_vacaciones = $request->dias_vacaciones;
    $factor_integracion->prima_vacacional = $request->prima_vacacional;
    $factor_integracion->factor_integracion = $request->factor_integracion;
    Utilidades::auditar($factor_integracion, $factor_integracion->id);
    $factor_integracion->save();
    return response()->json(array('status' => true));
     } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }


  public function edit($id)
  {

  }

}
