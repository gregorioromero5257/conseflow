<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Helpers\Utilidades;

class DirectorioController extends Controller
{
    //

    
  protected $rules = array(
    'nombre' => 'required|max:6',


);


public function index()
{


  {
    $extensiones = DB::table('extensiones')
        ->join('departamentos', 'departamentos.id', '=', 'extensiones.departamento_id')
       ->select("extensiones.*" 
       ,DB::raw("CONCAT(departamentos.nombre) as departamentos"))
      
        ->get();

    return response()->json(
        $extensiones->toArray()
    );
}

}

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

  $Extensio = new Extension();
  $Extensio->fill($request->all());
  Utilidades::auditar($Extensio, $Extensio->id);
  $Extensio->save();

  return response()->json(array(
    'status' => true
  ));
   } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
}

public function update(Request $request)
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

  $Extensio = Extension::findOrFail($request->id);
  $Extensio->fill($request->all());
  Utilidades::auditar($Extensio, $Extensio->id);
  $Extensio->save();

  return response()->json(array(
    'status' => true
  ));
  } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
}

public function edit($id)
{
  try{
  $Extensio = Extension::findOrFail($id);
      if ($Extensio->condicion==0) {
          $Extensio->condicion = 1;
      }else{
          $Extensio->condicion = 0;
      }
      Utilidades::auditar($Extensio, $Extensio->id);
      $Extensio->update();
      return $Extensio;
      } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
}
public function show($id)
{
  //  return response()->json(Familiare::findOrFail($id));
}

public function activar(Request $request)
{
    if (!$request->ajax()) return redirect('/');
    $Extensio = Extension::findOrFail($request->id);
    $Extensio->condicion = '1';
    $Extensio->save();
}


}
