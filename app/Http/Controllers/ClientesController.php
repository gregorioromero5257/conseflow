<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class ClientesController extends Controller
{
  /**
  * [protected Creacion de las reglas para la utilizacioón en el guardado o actualización]
  * @var [type]
  */
   protected $rules = array(
     'nombre' => 'required|max:75',
     'rfc' => 'required|max:13',
     // 'domicilio_fiscal' => 'required|max:255',
     'contacto' => 'max:150',


   );

/**
 * [index Consulta en la base de datos de todos los registro de la tabla]
 * @return Response [description]
 */
public function index()
{
  $clientes = DB::table('clientes')
  ->leftJoin('empleados AS E','E.id','=','clientes.ejecutivo_asignado_id')

  ->select('clientes.*',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS nombre_empleado"))
      ->get();
  return response()->json($clientes);
}

/**
* [show funcion que consulta un registro de un id especifico]
* @param Int $id
* @return Response
*/
public function show($id)
{
  $clientes = \App\Clientes::where('id',$id)->first();
  return response()->json($clientes);
}

/**
 * [store Crea un registo en la BD]
 * @param  Request $request [description]
 * @return Response          [description]
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
  $clientes = new \App\Clientes();
  $clientes->fill($request->all());
   Utilidades::auditar($clientes, $clientes->id);
  $clientes->save();
  return response()->json(array('status' => true));
   } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
}

/**
 * [update Actualiza un registro buscado por el id en la base de datos]
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
  $clientes = \App\Clientes::where('id','=',$id)->first();
  $clientes->fill($request->all());
  Utilidades::auditar($clientes, $clientes->id);
  $clientes->save();
  return response()->json(array('status' => true));
  } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
}

}
