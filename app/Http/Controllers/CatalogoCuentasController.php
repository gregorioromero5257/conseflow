<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;


class CatalogoCuentasController extends Controller
{
    //
    public function getI()
    {
      try {

        $iniciales = DB::table('codigo_agrupador')
        ->where('pertenece_id','0')
        ->get();

        return response()->json($iniciales);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function getC($id)
    {
      try {

        $iniciales = DB::table('codigo_agrupador')
        ->where('pertenece_id',$id)
        ->get();

        return response()->json($iniciales);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function Guardar(Request $request)
    {
      try {
        $ca = new \App\CodigoAgrupador();
        $ca->pertenece_id = $request->pertenece_id;
        $ca->codigo_agrupador = $request->codigo_agrupador;
        $ca->nombre_cuenta_sub = $request->nombre_cuenta_sub;
        $ca->save();

        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function Actualizar(Request $request)
    {
      try {
        $ca = \App\CodigoAgrupador::where('id',$request->id)->first();
        // $ca->pertenece_id = $request->pertenece_id;
        $ca->codigo_agrupador = $request->codigo_agrupador;
        $ca->nombre_cuenta_sub = $request->nombre_cuenta_sub;
        $ca->save();

        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }


}
