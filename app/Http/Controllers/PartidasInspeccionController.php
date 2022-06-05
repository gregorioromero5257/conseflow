<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PartidasInspeccionController extends Controller
{
    //
    public function juntas($id)
    {
      $valores = explode('&',$id);
      $inspeccion = \App\MaterialServicioSoldadura::where('servicio_id','=',$valores[0])->where('proyecto','=',$valores[1])->get();

      return response()->json($inspeccion);
    }

    public function get($id){
      $inspeccion = \App\PartidasInspeccion::leftJoin('soldadores AS S','S.id','=','partidas_inspeccion.id_soldador')
      ->leftJoin('materiales_servicios_sol AS MSS','MSS.id','=','partidas_inspeccion.materiales_servicios_sol_id')
      ->select('partidas_inspeccion.*','S.num','MSS.soldadura','MSS.material_uno AS material_uno_n','MSS.material_dos AS material_dos_n')
      ->where('partidas_inspeccion.inspeccion_id',$id)->get();

      return response()->json($inspeccion);
    }



        public function store(Request $request)
        {
          $partidas = new \App\PartidasInspeccion();
          $partidas->fill($request->all());
          $partidas->save();

          return response()->json(array('status' => true));
        }


                public function actualizar(Request $request,$id)
                {
                  $partidas = \App\PartidasInspeccion::where('id',$id)->first();
                  $partidas->fill($request->all());
                  $partidas->save();

                  return response()->json(array('status' => true));
                }
}
