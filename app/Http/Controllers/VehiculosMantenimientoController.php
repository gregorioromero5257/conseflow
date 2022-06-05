<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\DB;


class VehiculosMantenimientoController extends Controller
{
    /**
    ** OBTIENE TODOS LOS REGISTROS DE MANTENIMIENTO EXISTENTES
    **/
    public function getAll()
    {
      try {
        $mantenimiento = \App\MantenimientoVehiculo::
        join('empleados AS es','es.id','mantenimiento_vehiculos.solicita')
        ->join('empleados AS er','er.id','mantenimiento_vehiculos.recibe')
        ->join('empleados AS ee','ee.id','mantenimiento_vehiculos.entrega')
        ->join('empleados AS ere','ere.id','mantenimiento_vehiculos.recibe_empleado')
        ->join('unidades AS u','u.id','mantenimiento_vehiculos.unidad_id')
        ->select('mantenimiento_vehiculos.*',
        DB::raw("CONCAT(es.nombre,' ',es.ap_paterno,' ',es.ap_materno) AS empleado_solicita"),
        DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_recibe"),
        DB::raw("CONCAT(ee.nombre,' ',ee.ap_paterno,' ',ee.ap_materno) AS empleado_entrega"),
        DB::raw("CONCAT(ere.nombre,' ',ere.ap_paterno,' ',ere.ap_materno) AS empleado_recibe_uno"),
        'u.unidad','u.marca','u.modelo','u.placas'
        )
        ->get();

        return response()->json($mantenimiento);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    /**
    ** OBTIENE un registro de la base de datos por id
    **/
    public function getById($id)
    {
      try {
        $mantenimiento = \App\MantenimientoVehiculo::
        join('empleados AS es','es.id','mantenimiento_vehiculos.solicita')
        ->join('empleados AS er','er.id','mantenimiento_vehiculos.recibe')
        ->join('empleados AS ee','ee.id','mantenimiento_vehiculos.entrega')
        ->join('empleados AS ere','ere.id','mantenimiento_vehiculos.recibe_empleado')
        ->join('unidades AS u','u.id','mantenimiento_vehiculos.unidad_id')
        ->select('mantenimiento_vehiculos.*',
        DB::raw("CONCAT(es.nombre,' ',es.ap_paterno,' ',es.ap_materno) AS empleado_solicita"),
        DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_recibe"),
        DB::raw("CONCAT(ee.nombre,' ',ee.ap_paterno,' ',ee.ap_materno) AS empleado_entrega"),
        DB::raw("CONCAT(ere.nombre,' ',ere.ap_paterno,' ',ere.ap_materno) AS empleado_recibe_uno"),
        'u.unidad','u.marca','u.modelo','u.placas'
        )
        ->where('mantenimiento_vehiculos.id',$id)->first();

        return response()->json($mantenimiento);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    /**
    ** Guarda un registro en la base de datoss
    **/
    public function store(Request $request)
    {
      try {

        $mantenimiento = new \App\MantenimientoVehiculo();
        $mantenimiento->unidad_id = $request->unidad_id;
        $mantenimiento->tipo = $request->tipo;
        $mantenimiento->descripcion = $request->descripcion;
        $mantenimiento->solicita = $request->solicita;
        $mantenimiento->recibe = $request->recibe;
        $mantenimiento->fecha_inicio = $request->fecha_inicio;
        $mantenimiento->fecha_salida = $request->fecha_salida;
        $mantenimiento->proveedor = $request->proveedor;
        $mantenimiento->detalle = $request->detalle;
        $mantenimiento->materiales = $request->materiales;
        $mantenimiento->quimicos = $request->quimicos;
        $mantenimiento->entrega = $request->entrega;
        $mantenimiento->recibe_empleado = $request->recibe_empleado;
        $mantenimiento->empresa = $request->empresa;
        Utilidades::auditar($mantenimiento, $mantenimiento->id);
        $mantenimiento->save();

        return response()->json($mantenimiento);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    /**
    ** Acutualiza un registro en la base de datos apatertir de modelo
    **/
    public function update(Request $request)
    {
      try {

        $mantenimiento = \App\MantenimientoVehiculo::where('id',$request->id)->first();
        $mantenimiento->unidad_id = $request->unidad_id;
        $mantenimiento->tipo = $request->tipo;
        $mantenimiento->descripcion = $request->descripcion;
        $mantenimiento->solicita = $request->solicita;
        $mantenimiento->recibe = $request->recibe;
        $mantenimiento->fecha_inicio = $request->fecha_inicio;
        $mantenimiento->fecha_salida = $request->fecha_salida;
        $mantenimiento->proveedor = $request->proveedor;
        $mantenimiento->detalle = $request->detalle;
        $mantenimiento->materiales = $request->materiales;
        $mantenimiento->quimicos = $request->quimicos;
        $mantenimiento->entrega = $request->entrega;
        $mantenimiento->recibe_empleado = $request->recibe_empleado;
        $mantenimiento->empresa = $request->empresa;
        Utilidades::auditar($mantenimiento, $mantenimiento->id);
        $mantenimiento->save();

        return response()->json($mantenimiento);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    /**
    ** Elimina un registro en la base de datos por id
    **/
    public function delete($id)
    {
      try {
        $mantenimiento = \App\MantenimientoVehiculo::where('id',$id)->delete();
        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

}
