<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CorreosProgramados;
use App\Contacto;
use \App\Http\Helpers\Utilidades;


class CorreosProgramadosController extends Controller
{
    //
    public function index()
    {
      $correos = CorreosProgramados::select('correos_programados.*','u.name as name_user'
      ,\DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_nombre"))
      ->leftJoin('empleados AS e','e.id','=','correos_programados.empleado_id')
      ->leftJoin('users AS u','u.id','=','correos_programados.user_id')
      ->leftJoin('contacto_empleados as ce','ce.id','=','correos_programados.contacto_id')
      ->get()->toArray();
      return response()->json($correos);
    }

    /**
    * [Guarda u registro en la base de datos y se hacen los calculos para ek tipo de correo a enviar]
    * @param Request $request [Datos del post]
    * @return Response        [Array de tipo JSON con status true]
    */
    public function store(Request $request)
    {
      try{
      $contacto_vacio = null;
      if($request->empleado != null)
      {
      $contacto = Contacto::where(
          'empleado_id', $request->empleado
      )->first();
      }
      $tiempo_rango = '';
      switch ($request->frecuencia) {
        case 6:
        $tiempo_rango = $request->seis_minutos;
        break;
        case 8:
        $tiempo_rango = $request->ocho_hora.':'.$request->ocho_minuto;
        break;
        case 9:
        $tiempo_rango = $request->nueve_hora_uno.'&'.$request->nueve_hora_dos;
        break;
        case 11:
        $tiempo_rango = $request->once_dias.'&'.$request->once_hora.':'.$request->once_minuto;
        break;
        case 13:
        $tiempo_rango = $request->trece_num_dia.'&'.$request->trece_hora.':'.$request->trece_minuto;
        break;
      }
      // code...
      $correos = new CorreosProgramados();
      $correos->user_id = $request->usuario;
      $correos->empleado_id = $request->empleado;
      $correos->contacto_id = $request->empleado == null ? $contacto_vacio : $contacto->id;
      $correos->correo = $request->correo;
      $correos->frecuencia = $request->frecuencia;
      $correos->tiempo_rango = $tiempo_rango;
      $correos->tipo_correo = $request->tipo_correo;
      Utilidades::auditar($correos, $correos->id);
      $correos->save();

      return response()->json(array('status' => true, 'respuesta' => $request));
       } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function update(Request $request)
    {
      try{
      $contacto_vacio = null;
      if($request->empleado != null)
      {
      $contacto = Contacto::where(
          'empleado_id', $request->empleado
      )->first();
      }
      $tiempo_rango = '';
      switch ($request->frecuencia) {
        case 6:
        $tiempo_rango = $request->seis_minutos;
        break;
        case 8:
        $tiempo_rango = $request->ocho_hora.':'.$request->ocho_minuto;
        break;
        case 9:
        $tiempo_rango = $request->nueve_hora_uno.'&'.$request->nueve_hora_dos;
        break;
        case 11:
        $tiempo_rango = $request->once_dias.'&'.$request->once_hora.':'.$request->once_minuto;
        break;
        case 13:
        $tiempo_rango = $request->trece_num_dia.'&'.$request->trece_hora.':'.$request->trece_minuto;
        break;
      }
      // code...
      $correos = CorreosProgramados::findOrFail($request->id);
      // $correos->user_id = $request->usuario;
      // $correos->empleado_id = $request->empleado;
      // $correos->contacto_id = $request->empleado == null ? $contacto_vacio : $contacto->id;
      $correos->correo = $request->correo;
      $correos->frecuencia = $request->frecuencia;
      $correos->tiempo_rango = $tiempo_rango;
      $correos->tipo_correo = $request->tipo_correo;
      Utilidades::auditar($compra, $compra->id);
      $correos->save();

      return response()->json(array('status' => true, 'respuesta' => $request));
       } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function show($id)
    {
      try{
        $correos = CorreosProgramados::where('id',$id)->first();
        if($correos->estado == 1){
          $correos->estado = 0;
          $correos->save();
        }else{
          $correos->estado = 1;
          Utilidades::auditar($compra, $compra->id);
          $correos->save();
        }
        return response()->json(array('status' => true));
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }



}
