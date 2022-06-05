<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use \App\CaracteristicasVehiculo;


class CaracteristicasVehiculoController extends Controller
{
    //
      public function listarCaracteristicasV(Request $request){

        $vehiculo = DB::table('caracteristicas_vehiculo')
        ->leftJoin('unidades','unidades.id','=','caracteristicas_vehiculo.unidad_id')
        ->select('caracteristicas_vehiculo.*','unidades.unidad as nombre')
        ->get();

        return response()->json($vehiculo);
      }

      public function store (Request $request){

        try{
          if (!$request->ajax()) return redirect('/');

          $guardarCaracteristicas = new CaracteristicasVehiculo();
          $guardarCaracteristicas->id = $request->id;
          $guardarCaracteristicas->numero_puertas = $request->numero_puertas;
          $guardarCaracteristicas->clave_vehicular = $request->clave_vehicular;
          $guardarCaracteristicas->capacidad = $request->capacidad;
          $guardarCaracteristicas->numero_motor = $request->numero_motor;
          $guardarCaracteristicas->colores = $request->colores;
          $guardarCaracteristicas->cilindros = $request->cilindros;
          $guardarCaracteristicas->transporte = $request->transporte;
          $guardarCaracteristicas->unidad_id = $request->unidad_id;
          Utilidades::auditar($guardarCaracteristicas, $guardarCaracteristicas->id);
          $guardarCaracteristicas->save();

          return response()->json(array('status'=>true));
           } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
      }

      public function update (Request $request, $id){
        try{
        if (!$request->ajax()) return redirect('/');

        $actualizarCaracteristica = CaracteristicasVehiculo :: where ('id',$id)->first();
        $actualizarCaracteristica->id = $request->id;
        $actualizarCaracteristica->numero_puertas = $request->numero_puertas;
        $actualizarCaracteristica->clave_vehicular = $request->clave_vehicular;
        $actualizarCaracteristica->capacidad = $request->capacidad;
        $actualizarCaracteristica->numero_motor = $request->numero_motor;
        $actualizarCaracteristica->colores = $request->colores;
        $actualizarCaracteristica->cilindros = $request->cilindros;
        $actualizarCaracteristica->transporte = $request->transporte;
        $actualizarCaracteristica->unidad_id = $request->unidad_id;
        Utilidades::auditar($compra, $compra->id);
        $actualizarCaracteristica->save();

        return response()->json(array('status'=>true));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

        }

        public function listadoUnidades(Request $request){

          $listadoUnidades = DB::table('unidades')
          ->select('unidades.*')
          ->get();

          return response()->json($listadoUnidades);

        }
}
