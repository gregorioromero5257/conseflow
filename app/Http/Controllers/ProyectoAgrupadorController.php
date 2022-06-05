<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Validator;



class ProyectoAgrupadorController extends Controller
{
    //

    public function guardaAgrupador(Request $request){

       // if(!$request->ajax()) return redirect ('/');

        $guardar = new \App\ProyectoAgrupador();
        $guardar->nombre=$request->nombre;
        $guardar->save();

        return response()->json(array('status'=>true));

    }

    public function listaProyectos(){
        $lista=DB::table('proyecto_agrupador')
            ->select('proyecto_agrupador.*')
            ->get();
        return response()->json($lista);
    }
}
