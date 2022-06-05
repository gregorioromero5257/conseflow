<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Unidades;
use App\ServicioUnidades;
use App\MantenimientoUnidades;
use App\PartidasServicioUnidades;
use App\PartidasMantenimientoUnidades;
use App\PolizaUnidades;
use App\VerificacionUnidades;
use App\TenenciaUnidades;
use Barryvdh\DomPDF\Facade as PDF;


class UnidadesReporteController extends Controller
{
    //
    public function buscar($request)
    {
      $valores = explode('&',$request);
      $arreglo_final = [];
      //TODOS
      if ($valores[0] == '0' && $valores[1] == '0') {
        $unidades = Unidades::leftJoin('unidad_propio AS UP','UP.unidad_id','=','unidades.id')
        ->leftJoin('unidad_renta AS UR','UR.unidad_id','=','unidades.id')
        ->select('unidades.*','UP.id AS unidad_propio_id','UP.propietario','UR.id AS unidad_renta_id','UR.proveedor',
        'UR.tiempo','UR.costo_renta')->get();
      }
      //TENGO TODOS LOS PROPIETARIOS Y UN VEHICULO
      if ($valores[0] == '0' && $valores[1] != '0') {
        $unidades = Unidades::leftJoin('unidad_propio AS UP','UP.unidad_id','=','unidades.id')
        ->leftJoin('unidad_renta AS UR','UR.unidad_id','=','unidades.id')
        ->select('unidades.*','UP.id AS unidad_propio_id','UP.propietario','UR.id AS unidad_renta_id','UR.proveedor',
        'UR.tiempo','UR.costo_renta')->where('unidades.id',$valores[1])->get();
      }
      //TENGO PROPIETARIO Y TODOS LOS VEHICULOS
      if ($valores[0] != '0' && $valores[1] == '0') {
        $unidades = Unidades::leftJoin('unidad_propio AS UP','UP.unidad_id','=','unidades.id')
        ->leftJoin('unidad_renta AS UR','UR.unidad_id','=','unidades.id')
        ->select('unidades.*','UP.id AS unidad_propio_id','UP.propietario','UR.id AS unidad_renta_id','UR.proveedor',
        'UR.tiempo','UR.costo_renta')->where('propietario','=',$valores[0])->orWhere('proveedor','=',$valores[0])
        ->get();
      }
      if ($valores[0] != '0' && $valores[1] != '0') {
        $unidades = Unidades::leftJoin('unidad_propio AS UP','UP.unidad_id','=','unidades.id')
        ->leftJoin('unidad_renta AS UR','UR.unidad_id','=','unidades.id')
        ->select('unidades.*','UP.id AS unidad_propio_id','UP.propietario','UR.id AS unidad_renta_id','UR.proveedor',
        'UR.tiempo','UR.costo_renta')->where('propietario','=',$valores[0])->orWhere('proveedor','=',$valores[0])
        ->where('unidades.id',$valores[1])
        ->get();
      }

      foreach ($unidades as $key => $value_parent) {
        $arreglo_s = [];
        $arreglo_m = [];
        $poliza = [];
        $verificacion_unidades = [];
        $tenencia_unidades = [];
        //SI EL request TIENE UN  SERVCIO
        if($valores[2] == 'true'){
          $servicioUnidades = ServicioUnidades::where('unidad_id',$value_parent->id)->get();
          foreach ($servicioUnidades as $key => $value) {
            $partida_servicio = PartidasServicioUnidades::join('catalogo_trafico AS CT','CT.id','=','partidas_servicios_unidades.catalogo_trafico_id')
            ->select('partidas_servicios_unidades.*','CT.operacion_id','CT.nombre')
            ->where('unidad_id',$value_parent->id)
            ->where('servicio_id',$value->id)->get();
            $arreglo_s [] = [
              'servicio' => $value,
              'partidas' => $partida_servicio,
            ];
          }
          $mantenimientoUnidades = MantenimientoUnidades::where('unidad_id',$value_parent->id)->get();
          foreach ($mantenimientoUnidades as $key => $value) {
            $partida_mantenimiento = PartidasMantenimientoUnidades::join('catalogo_trafico AS CT','CT.id','=','partidas_mantenimiento_unidades.catalogo_trafico_id')
            ->select('partidas_mantenimiento_unidades.*','CT.operacion_id','CT.nombre')
            ->where('unidad_id',$value_parent->id)
            ->where('mantenimiento_id',$value->id)->get();
            $arreglo_m [] = [
              'mantenimiento' => $value,
              'partidas' => $partida_mantenimiento,
            ];
          }
        }
        if($valores[3] == 'true'){
          $poliza = PolizaUnidades::where('unidad_id',$value_parent->id)->get();
        }
        if ($valores[4] == 'true') {
          $verificacion_unidades = VerificacionUnidades::where('unidad_id',$value_parent->id)->get();
        }
        if ($valores[5] == 'true') {
          $tenencia_unidades = TenenciaUnidades::where('unidad_id',$value_parent->id)->get();
        }
        $arreglo_final [] = [
          'unidades' => $value_parent,
          'servicios' => $arreglo_s,
          'mantenimiento' => $arreglo_m,
          'poliza' => $poliza,
          'verificacion' => $verificacion_unidades,
          'tenencia' => $tenencia_unidades,
        ];
      }
      $pdf = PDF::loadView('pdf.trafico', compact('arreglo_final'));
      $pdf->setPaper('A4', 'landscape');
      return $pdf->stream();
      // return response()->json($arreglo_final);
    }
}
