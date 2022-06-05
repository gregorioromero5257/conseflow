<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use \App\SubsidioEmpleo;
use Validator;

class SubsidioEmpleoController extends Controller
{
  /**
   * [protected Se definen las reglas para guardado y actualizacion de registro]
   * @var [type]
   */
  protected $rules = array(
    'limite_inferior' => 'required',
    'limite_superior' => 'required',
    'cuota_fija' => 'required',
  );

  /**
   * [index Obtiene todos los registros del modelo SubsidioEmpleo]
   * @return Response [description]
   */
  public function index()
  {
    $subsidio_empleo = SubsidioEmpleo::all();
    return response()->json($subsidio_empleo);
  }

  /**
   * [store Crea un nuevo registro en el modelo SubsidioEmpleo]
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
    $subsidio_empleo = new SubsidioEmpleo();
    $subsidio_empleo->limite_inferior = $request->limite_inferior;
    $subsidio_empleo->limite_superior = $request->limite_superior;
    $subsidio_empleo->cuota_fija = $request->cuota_fija;
    $subsidio_empleo->save();
    return response()->json(array('status' => true));
  }

  /**
   * [update Actualiza un registro en el modelo SubsidioEmpleo]
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
    $subsidio_empleo = SubsidioEmpleo::where('id','=',$id)->first();
    $subsidio_empleo->limite_inferior = $request->limite_inferior;
    $subsidio_empleo->limite_superior = $request->limite_superior;
    $subsidio_empleo->cuota_fija = $request->cuota_fija;
    $subsidio_empleo->save();
    return response()->json(array('status' => true));
  }


  public function edit($id)
  {

  }
}
