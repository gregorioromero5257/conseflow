<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class BancosContabilidadController extends Controller
{

 /**
 * [protected declaracion de las reglas oantes de guardar o actualizar]
 * @var [type]
 */
  protected $rules = array(
    'catalogo_banco_id' => 'required',
    'numero_cuenta' => 'required|max:25',
    'numero_clave' => 'required|max:25',
    'total' => 'required',
  );

/**
 * [index Consulta en la BD  a la tabla bancoscontabilidad]
 * @return Response [Objeto generado apartir de la consulta]
 */
public function index()
{
  $bancos_contabilidad = DB::table('bancos_contabilidad')
  ->leftJoin('catalogo_bancos AS CB','CB.id','=','bancos_contabilidad.catalogo_banco_id')
  ->select('bancos_contabilidad.*','CB.nombre')
  ->get();
  return response()->json($bancos_contabilidad);
}

/**
 * [store Cree un nuevo registro en la BD]
 * @param  Request $request [description]
 * @return Response           [description]
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
  $bancos_contabilidad = new \App\BancosContabilidad();
  $bancos_contabilidad->catalogo_banco_id = $request->catalogo_banco_id;
  $bancos_contabilidad->numero_cuenta = $request->numero_cuenta;
  $bancos_contabilidad->numero_clave = $request->numero_clave;
  $bancos_contabilidad->total = $request->total;
  Utilidades::auditar($bancos_contabilidad, $bancos_contabilidad->id);
  $bancos_contabilidad->save();
  // $bancos_contabilidad->fill($request->all());
  return response()->json(array('status' => true));
   } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
}

/**
 * [update Actualiza un registro buscado por un id en la base de datos]
 * @param  Request $request [description]
 * @param  Int  $id      [description]
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
  $bancos_contabilidad = \App\BancosContabilidad::where('id','=',$id)->first();
  $bancos_contabilidad->catalogo_banco_id = $request->catalogo_banco_id;
  $bancos_contabilidad->numero_cuenta = $request->numero_cuenta;
  $bancos_contabilidad->numero_clave = $request->numero_clave;
  $bancos_contabilidad->total = $request->total;
  Utilidades::auditar($bancos_contabilidad, $bancos_contabilidad->id);
  $bancos_contabilidad->save();
  return response()->json(array('status' => true));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
}

/**
 * [show Busqueda de un registro en la BD por el id recibido]
 * @param  Int $id [description]
 * @return Response     [description]
 */
public function show($id)
{
  $bancos_contabilidad = \App\BancosContabilidad::where('id','=',$id)->first();
  return response()->json($bancos_contabilidad);
}


}
