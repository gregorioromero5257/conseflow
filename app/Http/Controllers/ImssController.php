<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use \App\Imss;
use Validator;
use \App\Http\Helpers\Utilidades;


class ImssController extends Controller
{
    /**
     * [protected Se definen las reglas para guardado y actualizacion de registro]
     * @var [type]
     */
    protected $rules = array(
      'ramo' => 'required|max:45',
      'prestacion' => 'required|max:45',
      'base_calculo' => 'required|max:100',
      'porcentaje_trabajador' => 'required',
      'porcentaje_patron' => 'required',
    );

    /**
     * [index Obtiene todos los registros del modelo Imss]
     * @return Response [description]
     */
    public function index()
    {
      $imss = Imss::all();
      return response()->json($imss);
    }

    /**
     * [store Crea un nuevo registro en el modelo Imss]
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
      $imss = new Imss();
      $imss->ramo = $request->ramo;
      $imss->prestacion = $request->prestacion;
      $imss->base_calculo = $request->base_calculo;
      $imss->porcentaje_trabajador = $request->porcentaje_trabajador;
      $imss->porcentaje_patron = $request->porcentaje_patron;
      Utilidades::auditar($imss, $imss->id);
      $imss->save();
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
      $imss = Imss::where('id','=',$id)->first();
      $imss->ramo = $request->ramo;
      $imss->prestacion = $request->prestacion;
      $imss->base_calculo = $request->base_calculo;
      $imss->porcentaje_trabajador = $request->porcentaje_trabajador;
      $imss->porcentaje_patron = $request->porcentaje_patron;
      Utilidades::auditar($imss, $imss->id);
      $imss->save();
      return response()->json(array('status' => true));
       } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }


    public function edit($id)
    {

    }


}
