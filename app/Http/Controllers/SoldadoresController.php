<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SoldadoresController extends Controller
{
    //

    public function obtener()
    {
      $soldadores = \App\Soldadores::all();

      return response()->json($soldadores);
    }


    public function registrar(Request $request)
    {
      $soldadores = new \App\Soldadores();
      $soldadores->fill($request->all());
      $soldadores->save();

      return response()->json(array('status' => true));

    }

    public function actualizar(Request $request,$id)
    {
      // code...

      $soldadores = \App\Soldadores::where('id',$id)->first();
      $soldadores->fill($request->all());
      $soldadores->save();
      
      return response()->json(array('status' => true));

    }


}
