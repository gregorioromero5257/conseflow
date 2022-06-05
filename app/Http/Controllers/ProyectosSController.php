<?php

namespace App\Http\Controllers;

use App\ProyectoS;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ProyectosSController extends Controller
{

  public function get()
  {

    $proyectos = ProyectoS::get();
    return response()->json($proyectos);
  }

  public function show($id)
  {
    $proyectos = ProyectoS::where('id',$id)->first();

    return response()->json(array('status' => true, 'proyecto' => $proyectos));

  }

  public function store(Request $request)
  {
    $proyectos = new ProyectoS();
    $proyecto->Nombre = $request->nom;
    $proyectos->fill($request->all());
    $proyectos->save();
    return response()->json(array('status' => true));
  }

  public function update(Request $request)
  {
      $proyecto = ProyectoS::where('id',$request->id)->first();
      $proyectos->fill($request->all());
      $proyectos->save();
      return response()->json(array('status' => true));

  }

  public function delete($id)
  {
      $proyecto = ProyectoS::where('id',$id)->delete();
      return response()->json(array('status' => true));

  }
}
