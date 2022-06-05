<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\EdenRedUserImport;
use App\Imports\EdenRedMovimientosImport;
use App\Imports\EdenRedEncabezadoMovImport;
use App\Empleado;
use Maatwebsite\Excel\Facades\Excel;
use App\EdenRedUser;
use App\EdenRedMovimiento;
use App\DatosBancariosEmpleado;
use App\Http\Helpers\Utilidades;

class EdenRedUserController extends Controller
{
  /**
  * Cargar los empleados en las tablas:
  * Empleados, Direcciones_empleados, Contacto_empleados, Telefonos_corporativos, Coreeos_corporativos, datos_bancarios_empleados
  * Recorrer los registros del archivo con FastExcel
  * Si no se encuentra el Puesto se agrega, si el departamento no se encuentra en el catalogo se agrega.
  * @return array Regresa el numero de los registros nuevos, repetidos, la fecha y hora de inicio y fin del script
  */
  public function uploadEmpleados(Request $request)
  {
    try {
      if (!$request->ajax()) return redirect('/');
      if($request->hasFile('import_file')){
        ini_set('max_execution_time', 300);
        $collection = Excel::import(new EdenRedUserImport, request()->file('import_file'));
        return response()->json($collection);

      }
    } catch (\Exception $e) {
      return response()->json($e);
    }

  }

  /**
  * Cargar los empleados en las tablas:
  * Empleados, Direcciones_empleados, Contacto_empleados, Telefonos_corporativos, Coreeos_corporativos, datos_bancarios_empleados
  * Recorrer los registros del archivo con FastExcel
  * Si no se encuentra el Puesto se agrega, si el departamento no se encuentra en el catalogo se agrega.
  * @return array Regresa el numero de los registros nuevos, repetidos, la fecha y hora de inicio y fin del script
  */
  public function uploadMovimientos(Request $request)
  {
    try {
      if (!$request->ajax()) return redirect('/');
      if($request->hasFile('import_file')){
        ini_set('max_execution_time', 300);
        $collection = Excel::import(new EdenRedMovimientosImport, request()->file('import_file'));
        return response()->json($collection);

      }
    } catch (\Exception $e) {
      return response()->json($e);
    }

  }

  public function uploadEnc(Request $request)
  {
    try {
      if (!$request->ajax()) return redirect('/');
      if($request->hasFile('import_file')){
        ini_set('max_execution_time', 300);
        $collection_uno = Excel::import(new EdenRedEncabezadoMovImport, request()->file('import_file'));
        return response()->json($collection_uno);
      }
    } catch (\Exception $e) {
      return response()->json($e);
    }

  }

  /**
      * [Query del lado del servidor de el modelo Articulo]
      * @return Array [Array que contiene data y count]
      */
     public function get()
     {
       $this->completeempleado();
       $dato = EdenRedUser::select(
           [
           'id', 'numero_nomina', 'nombre_empleado', 'empleado_id', 'numero_cuenta','numero_tarjeta','status','correo','puesto','telefono','direccion_entrega','saldo','buzon_usuario','asignado'
           ]);
           return $this->busqueda($dato);
     }

     public function show($id)
     {
       $dato = EdenRedMovimiento::join('encabezado_movimiento_edenred AS EME','EME.id','=','movimientos_edenred.encabezado_movimiento_id')
       ->select('movimientos_edenred.*','EME.*')
       ->where('movimientos_edenred.numero_nomina','=',$id);
       return $this->busqueda($dato);
     }

     public function busqueda($dato)
     {
       extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));

       $data = $dato;

       if (isset($query) && $query) {
           $data = $byColumn == 1 ?
               Utilidades::busqueda_filterByColumn($data, $query) :
               Utilidades::busqueda_filter($data, $query, $fields);
       }

       $count = $data->count();

      $data->limit($limit)
          ->skip($limit * ($page - 1));

       if (isset($orderBy)) {
           $direction = $ascending == 1 ? 'ASC' : 'DESC';
           $data->orderBy($orderBy, $direction);
       }
       // leftJoin('tipo_calidad AS TC','TC.id','=','articulos.calidad_id')
       // ->
       $results = $data->get();

       return [
           'data' => $results,
           'count' => $count,
       ];
     }

     public function completeempleado()
     {
       $empleados = Empleado::select('empleados.*',\DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) AS empleado_nombre"))->get();
       foreach ($empleados as $key => $value) {
         $edenreduser = EdenRedUser::where('nombre_empleado','=',$value->empleado_nombre)->where('asignado','0')->first();
         if (isset($edenreduser) == true) {
           $edenreduser->empleado_id = $value->id;
           $edenreduser->asignado = 1;
           $edenreduser->save();

           $datos_banc = DatosBancariosEmpleado::join('catalogo_bancos AS CB','CB.id','=','datos_bancarios_empleados.banco_id')
           ->where('nombre','=','EDENRED')->where('empleado_id','=',$value->id)->first();
           if(isset($datos_banc) != true){
             $bancos = \App\CatalogoBanco::where('nombre','EDENRED')->first();
             $dato_banc = new DatosBancariosEmpleado();
             $dato_banc->numero_tarjeta = $edenreduser->numero_de_tarjeta;
             $dato_banc->numero_cuenta = $edenreduser->numero_de_cuenta;
             $dato_banc->banco_id = $bancos->id;
             $dato_banc->empleado_id = $value->id;
             $dato_banc->save();
           }
         }

       }
       return true;
     }




}
