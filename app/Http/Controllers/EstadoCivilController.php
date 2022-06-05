<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\EstadoCivil;
use \App\Http\Helpers\Utilidades;


class EstadoCivilController extends Controller
{


  public function index()
  {

    $dias = DB::table('estados_civiles')
    ->select('estados_civiles.*')
    ->get();
    return response()->json(
      $dias->toArray()
    );

  }

  public function store(Request $request)
  {
    try{
    if (!$request->ajax()) return redirect('/');

    $EdoCivil = new EstadoCivil();
    $EdoCivil->fill($request->all());
    Utilidades::auditar($EdoCivil, $EdoCivil->id);
    $EdoCivil->save();

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

    $EdoCivil = EstadoCivil::findOrFail($request->id);
    $EdoCivil->fill($request->all());
    Utilidades::auditar($EdoCivil, $EdoCivil->id);
    $EdoCivil->save();

    return response()->json(array(
      'status' => true
    ));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function edit($id)
  {

  }
  public function show($id)
  {
    //  return response()->json(Familiare::findOrFail($id));
  }



}
