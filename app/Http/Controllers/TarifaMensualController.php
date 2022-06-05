<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use \App\TarifaMensual;
use Validator;

class TarifaMensualController extends Controller
{
  /**
   * [protected Se definen las reglas para guardado y actualizacion de registro]
   * @var [type]
   */
  protected $rules = array(
    'limite_inferior' => 'required',
    'limite_superior' => 'required',
    'cuota_fija' => 'required',
    'porcentaje' => 'required',
  );

  /**
   * [index Obtiene todos los registros del modelo TarifaMensual]
   * @return Response [description]
   */
  public function index()
  {
    $tarifamensual = TarifaMensual::all();
    return response()->json($tarifamensual);
  }

  /**
   * [store Crea un nuevo registro en el modelo TarifaMensual]
   * @param  Request $request [description]
   * @return Response         [description]
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
    $tarifamensual = new TarifaMensual();
    $tarifamensual->limite_inferior = $request->limite_inferior;
    $tarifamensual->limite_superior = $request->limite_superior;
    $tarifamensual->cuota_fija = $request->cuota_fija;
    $tarifamensual->porcentaje = $request->porcentaje;
    $tarifamensual->save();
    return response()->json(array('status' => true));
  }

  /**
   * [update Actualiza un registro en el modelo TarifaMensual]
   * @param  Request $request [description]
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
    $tarifamensual = TarifaMensual::where('id','=',$id)->first();
    $tarifamensual->limite_inferior = $request->limite_inferior;
    $tarifamensual->limite_superior = $request->limite_superior;
    $tarifamensual->cuota_fija = $request->cuota_fija;
    $tarifamensual->porcentaje = $request->porcentaje;
    $tarifamensual->save();
    return response()->json(array('status' => true));
  }


  public function edit($id)
  {

  }

}
