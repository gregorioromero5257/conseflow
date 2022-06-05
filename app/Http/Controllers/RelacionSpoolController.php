<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RelacionSpool;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;


class RelacionSpoolController extends Controller
{
    //



    public function index(Request $request){

            $listaJuntas = DB::table('relacion_spool')
            ->Join('proyectos','proyectos.id','=','relacion_spool.proyecto_id')
            ->select('relacion_spool.*','proyectos.nombre_corto as corto')
            ->get();

            return response()->json($listaJuntas);
    }

    public function store (Request $request){

      try {
        if (!$request->ajax()) return redirect('/');

          $nuevaJunta = new RelacionSpool();
          $nuevaJunta->id = $request->id;
          $nuevaJunta->proyecto_id = $request->proyecto;
          $nuevaJunta->sistema = $request->sistema;
          $nuevaJunta->servicio = $request->servicio;
          $nuevaJunta->spool = $request->spool;
          Utilidades::auditar($nuevaJunta, $nuevaJunta->id);
          $nuevaJunta->save();
          
          return response()->json(array('status'=>true));
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function update (Request $request,$id){

      if (!$request->ajax()) return redirect('/');

        $nuevaJunta = RelacionSpool :: where ('id',$id)->first();
        $nuevaJunta->id = $request->id;
        $nuevaJunta->proyecto_id = $request->proyecto;
        $nuevaJunta->sistema = $request->sistema;
        $nuevaJunta->servicio = $request->servicio;
        $nuevaJunta->spool = $request->spool;
        $nuevaJunta->save();

        return response()->json(array('status'=>true));

    }

}
