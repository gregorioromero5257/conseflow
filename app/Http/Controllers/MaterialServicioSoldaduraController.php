<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaterialServicioSoldaduraController extends Controller
{
    //

    public function get()
    {
      $material = \App\MaterialServicioSoldadura::join('servicios_inspeccion as s','s.id','=','materiales_servicios_sol.servicio_id')
      ->select('materiales_servicios_sol.*','s.nombre as servicio')->get();
      return response()->json($material);

      // code...
    }

    public function registrar(Request $request)
    {
      $soldadores = new \App\MaterialServicioSoldadura();
      $soldadores->fill($request->all());
      $soldadores->save();

      return response()->json(array('status' => true));
      // code...
    }

    public function actualizar(Request $request,$id)
    {

      // code...

            $soldadores = \App\MaterialServicioSoldadura::where('id',$id)->first();
            $soldadores->fill($request->all());
            $soldadores->save();

            return response()->json(array('status' => true));
    }


}
