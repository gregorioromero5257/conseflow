<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use App\Salida;
use Auth;
use App\Empleado;
use \App\Http\Helpers\Utilidades;
use Illuminate\Validation\Rule;
use App\Exports\SalidaExport;
use Maatwebsite\Excel\Facades\Excel;


class SalidaController extends Controller
{

    /**
    * [index Realiza una consulta a la BD de todos los empleados]
    * @return Response [empleados]
    */
    public function index()
    {
      $id = Auth::user();
      $puestos = 0;
      $empleados = Empleado::select('empleados.*',
        DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) AS empleado"))
        ->where('empleados.id', '=',$id->empleado_id)
      ->orderBy('id', 'asc')->get();

      return response()->json($empleados);
      /*$salidas = DB::table('salidas')
      ->join('proyectos', 'proyectos.id', '=', 'salidas.proyecto_id')
      ->join('tipo_salidas', 'tipo_salidas.id', '=', 'salidas.tiposalida_id')
      ->join('empleados', 'empleados.id', '=', 'salidas.empleado_id')
      // ->join('empleados AS EE', 'empleados.id', '=', 'salidas.empleado_entrega_id')
      // ->join('empleados AS ER', 'empleados.id', '=', 'salidas.empleado_recibe_id')
      // ->join('empleados AS EA', 'empleados.id', '=', 'salidas.empleado_autoriza_id')
      ->select('salidas.*','proyectos.nombre AS nombrep','proyectos.nombre_corto AS nombrec',
      'tipo_salidas.nombre AS salidan',
      DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) AS empleado")
      // DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS empleadoEE"),
      // DB::raw("CONCAT(ER.nombre,' ',ER.ap_paterno,' ',ER.ap_materno) AS empleadoER"),
      // DB::raw("CONCAT(EA.nombre,' ',EA.ap_paterno,' ',EA.ap_materno) AS empleadoEA")
      )
      ->get();

      return response()->json(
          $salidas->toArray()
      );*/

    }

    /**
    * [create Realiza una consulta a la tabla salidasresguardo y obtiene los empleados]
    * @return Response [array]
    */
    public function create(){
      $puestos = 0;
      $departamentos = 0;
      $arreglo = [];
      $empleados = DB::table('salidasresguardo')
          ->leftJoin('empleados', 'empleados.id', '=', 'salidasresguardo.empleado_supervisor_id')
          ->select('empleados.id', 'empleados.nombre','empleados.ap_paterno','empleados.ap_materno','empleados.condicion','empleados.puesto_id', 'empleados.edo_civil_id')
          ->orderBy('empleados.id', 'asc')
          ->distinct()
          ->get();
        foreach ($empleados as $key => $empleado) {
            $puestoid = $empleado->puesto_id;

            if (!is_null($puestoid)) {
                $puestos = DB::table('puestos')
                    ->select('puestos.id', 'puestos.nombre','puestos.area','puestos.departamento_id')
                    ->where('puestos.id', '=', $puestoid)
                    ->get();
                $departamentoid = $puestos[0]->departamento_id;
                if (!is_null($departamentoid)) {
                    $departamentos = DB::table('departamentos')
                        ->select('departamentos.id','departamentos.nombre','departamentos.direccion_administrativa_id')
                        ->where('departamentos.id', '=', $departamentoid)
                        ->get();
                }
            }

            $arreglo[] = [
                'empleado' => $empleado,
                'puesto' => $puestos[0],
                'departamento' => $departamentos[0],
            ];
        }
        return response()->json($arreglo);
    }
    /**
    * [sitios Realiza una busqueda de los empleados registrados en la tabla salidassitio]
    * @param  Request $request [ID del proyecto]
    * @return Response           [salidas]
    */
    public function sitios($id)
    {

      $salidas = DB::table('salidassitio')
      ->join('proyectos', 'proyectos.id', '=', 'salidassitio.proyecto_id')
      ->join('tipo_salidas', 'tipo_salidas.id', '=', 'salidassitio.tiposalida_id')
      ->join('empleados AS ES', 'ES.id', '=', 'salidassitio.empleado_solicita_id')
      ->join('empleados AS EE', 'EE.id', '=', 'salidassitio.empleado_entrega_id')
      ->join('empleados AS EA', 'EA.id', '=', 'salidassitio.empleado_autoriza_id')
      ->join('empleados AS ER', 'ER.id', '=', 'salidassitio.empleado_recibe_id')
      ->select('salidassitio.*','proyectos.nombre AS nombrep','proyectos.nombre_corto AS nombrec',
      'tipo_salidas.nombre AS salidan'
      ,DB::raw("CONCAT(ES.nombre,' ',ES.ap_paterno,' ',ES.ap_materno) AS empleadoES"),
      DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS empleadoEE"),
      DB::raw("CONCAT(EA.nombre,' ',EA.ap_paterno,' ',EA.ap_materno) AS empleadoEA"),
      DB::raw("CONCAT(ER.nombre,' ',ER.ap_paterno,' ',ER.ap_materno) AS empleadoER")

      )
      ->where('salidassitio.proyecto_id', '=', $id)
      ->get();
      return response()->json(
          $salidas->toArray()
      );

    }
    /**
    * [resguardo Realiza una busqueda de los empleado registrados en la tabla salidasresguardo]
    * @param  Request $request [ID del supervisor]
    * @return Response           [salidas]
    */
    public function resguardo($id)
    {
      $salidas = DB::table('salidasresguardo')
      ->join('proyectos', 'proyectos.id', '=', 'salidasresguardo.proyecto_id')
      ->join('tipo_salidas', 'tipo_salidas.id', '=', 'salidasresguardo.tiposalida_id')
      ->join('empleados AS EE', 'EE.id', '=', 'salidasresguardo.empleado_entrega_id')
      ->join('empleados AS ES', 'ES.id', '=', 'salidasresguardo.empleado_supervisor_id')
      ->join('empleados AS ESL', 'ESL.id', '=', 'salidasresguardo.empleado_solicita_id')
      ->select('salidasresguardo.*','proyectos.nombre AS nombrep','proyectos.nombre_corto AS nombrec',
      'tipo_salidas.nombre AS salidan',
      DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS empleadoEE"),
      DB::raw("CONCAT(ES.nombre,' ',ES.ap_paterno,' ',ES.ap_materno) AS empleadoES"),
      DB::raw("CONCAT(ESL.nombre,' ',ESL.ap_paterno,' ',ESL.ap_materno) AS empleadoESL"))
      ->where('salidasresguardo.empleado_supervisor_id', '=', $id)
      ->get();

      return response()->json(
          $salidas->toArray()
      );

    }

    /**
    * [store Inserta un nuevo registro en la BD dependiendo del tipo de salida seleccionada]
    * @param  Request $request [request]
    * @return Response           [status => true]
    */

    public function store(Request $request)
    {
      try {

      $id = Auth::user();

      if ($request->tiposalida_id == 1) //Taller
      {
        $salidas = new Salida();
        $salidas->fecha = $request->fecha;
        $salidas->folio = $this->getFolio("taller",$request->proyecto_id);
        $salidas->ubicacion = $request->ubicacion;
        $salidas->proyecto_id = $request->proyecto_id;
        $salidas->tiposalida_id = $request->tiposalida_id;
        $salidas->empleado_id = $request->empleado_id;
        $salidas->empleado_entrega_id = $id->empleado_id;
        $salidas->supervisores_proyectos_id = $this->getSupervisor($request->proyecto_id);
        $salidas->save();
      }
      if ($request->tiposalida_id == 2) //sitio
      {
        $salidasitio = new \App\SalidaSitio();
        $salidasitio->fecha = $request->fecha;
        $salidasitio->folio = $this->getFolio("sitio",$request->proyecto_id);
        $salidasitio->ubicacion = $request->ubicacion;
        $salidasitio->proyecto_id = $request->proyecto_id;
        $salidasitio->tiposalida_id = $request->tiposalida_id;
        $salidasitio->empleado_solicita_id = $request->empleado_id;
        $salidasitio->empleado_entrega_id =  $id->empleado_id;
        $salidasitio->empleado_autoriza_id = $request->empleado_dos;
        $salidasitio->empleado_recibe_id = $request->empleado_tres;
        $salidasitio->supervisores_proyectos_id = $this->getSupervisor($request->proyecto_id);
        $salidasitio->save();
      }
      if ($request->tiposalida_id == 3) //Resguardo
      {
        $salidaresguardo = new \App\SalidasResguardo();
        $salidaresguardo->fecha = $request->fecha;
        $salidaresguardo->folio = $this->getFolio("resguardo",$request->proyecto_id);
        $salidaresguardo->ubicacion = $request->ubicacion;
        $salidaresguardo->proyecto_id = $request->proyecto_id;
        $salidaresguardo->tiposalida_id = $request->tiposalida_id;
        $salidaresguardo->empleado_solicita_id = $request->empleado_id;
        $salidaresguardo->empleado_entrega_id =  $id->empleado_id;
        $salidaresguardo->empleado_supervisor_id = $request->empleado_dos;
        $salidaresguardo->supervisores_proyectos_id = $this->getSupervisor($request->proyecto_id);
        $salidaresguardo->save();
      }


      return response()->json(array(
          'status' => true
      ));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return view('errors.500');
    }
    }

    /**
    * [update Actualia un registro en la BD de acuerdo a la tabla seleccionada]
    * @param  Request $request [description]
    * @return Response           [status => true]
    */
    public function update(Request $request)
    {
      if (!$request->ajax()) return redirect('/');


      if ($request->tiposalida_id == 1) //Taller
      {
        $salidas =Salida::where('id','=',$request->id)->first();
        $salidas->fecha = $request->fecha;
        $salidas->folio = $request->folio;
        $salidas->ubicacion = $request->ubicacion;
        $salidas->proyecto_id = $request->proyecto_id;
        $salidas->empleado_id = $request->empleado_id;
        $salidas->update();
      }
      if ($request->tiposalida_id == 2) //sitio
      {
        $salidasitio = \App\SalidaSitio::where('id','=',$request->id)->first();
        $salidasitio->fecha = $request->fecha;
        $salidasitio->folio = $request->folio;
        $salidasitio->ubicacion = $request->ubicacion;
        $salidasitio->proyecto_id = $request->proyecto_id;
        $salidasitio->update();
      }
      if ($request->tiposalida_id == 3) //Resguardo
      {
        $salidaresguardo = \App\SalidasResguardo::where('id','=',$request->id)->first();
        $salidaresguardo->fecha = $request->fecha;
        $salidaresguardo->folio = $request->folio;
        $salidaresguardo->ubicacion = $request->ubicacion;
        $salidaresguardo->proyecto_id = $request->proyecto_id;
        $salidaresguardo->update();
      }

      return response()->json(array(
          'status' => true
      ));

    }

    /**
    * [edit Acutualiza el campo condicion de un registro en la tabla Salidas]
    * @param  Int $id [ID de salida]
    * @return Response     [salida]
    */
    public function edit($id)
    {
        $salida = Salida::findOrFail($id);
        if ($salida->condicion==0) {
            $salida->condicion = 1;
        }else{
            $salida->condicion = 0;
        }
        $salida->update();
        return $salida;
    }

    /**
    * [show Realiza una busqueda de los empleados registrados en la tabal salidas de acuerdo a la condicion del proyecto]
    * @param  Int $id [ID del proyecto]
    * @return Response     [salidas]
    */
    public function show($id)
    {
        $salidas = DB::table('salidas')
        ->join('proyectos', 'proyectos.id', '=', 'salidas.proyecto_id')
        ->join('tipo_salidas', 'tipo_salidas.id', '=', 'salidas.tiposalida_id')
        ->join('empleados', 'empleados.id', '=', 'salidas.empleado_id')
        ->select('salidas.*','proyectos.nombre AS nombrep','proyectos.nombre_corto AS nombrec',
        'tipo_salidas.nombre AS salidan',
        DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) AS empleado")
        )
        ->where([
                ['proyectos.id', $id == 0 ? '>' : '=', $id]
          ])
        ->orderBy('salidas.fecha','DESC')
        ->orderBy('salidas.folio','DESC')
        ->get();

        return response()->json($salidas);
    }



    /**
    * [activar Acutualiza el campo condicion en la tabla Salidas]
    * @param  Request $request [request]
    * @return Response     [save]
    */
    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $salida = Salida::findOrFail($request->id);
        $salida->condicion = '1';
        $salida->save();
    }

    public function getFolio($table,$proyecto_id)
    {

      if ($table === 'taller') {
        $partidas = DB::table('salidas')->where('proyecto_id',$proyecto_id)->get();
        return str_pad((count($partidas) + 1), 4, "0", STR_PAD_LEFT);
      }
      if ($table === 'sitio') {
        $sitio = \App\SalidaSitio::where('proyecto_id',$proyecto_id)->get();
        return str_pad((count($sitio) + 1), 4, "0", STR_PAD_LEFT);
      }
      if ($table === 'resguardo') {
        $resguardo = \App\SalidasResguardo::where('proyecto_id',$proyecto_id)->get();
        return str_pad((count($resguardo) + 1), 4, "0", STR_PAD_LEFT);
      }
      //Generar folio
      // $ultimo=DB::table($table)->orderBy('folio', 'desc')->first();
      // if($ultimo)
      // {
      //   $aux_f=(int)$ultimo->folio;
      //   $aux=$aux_f + 1;
      //   $folio="";
      //   if($aux<10)
      //     $folio="000$aux";
      //   else if($aux<100)
      //     $folio="00$aux";
      //   else if($aux<1000)
      //     $folio="0$aux";
      //   else
      //    $folio=$aux;
      //   return $folio;
      // }
      // return "0001";
    }

    public function getSupervisor($id)
    {
      $supervisor_id = 11;
      $supervisor = DB::table('supervisores_proyectos')
      ->where('proyecto_id',$id)
      ->where('activo','1')
      ->first();

      if (isset($supervisor) == true) {
        $supervisor = $supervisor->id;
      }

      return $supervisor_id;
    }

    public function descargarExcel($id)
    {
      ini_set('max_execution_time', 3000);
      return Excel::download(new SalidaExport($id), 'ReporteSalidas.xlsx');
    }

    public function getProyectos()
    {
      $salidasitio = DB::table('salidassitio')
      ->select('proyecto_id')
      ->groupBy('proyecto_id')
      ->get();

      $proyectos_array = [];
      foreach ($salidasitio as $kp => $vp) {
        $proyectos_array [] = $vp->proyecto_id;
      }

      $arreglo=[];
        /*Obtiene los registros de Proyectos*/
        $proyectos = DB::table('proyectos')
        ->select('proyectos.*')
        ->whereIn('proyectos.id',$proyectos_array)
        ->orderBy('proyectos.id', 'DESC')
        ->get();

        foreach ($proyectos as $key => $proyecto)
        {

            $arreglo[] =
            [
                'proyecto' => $proyecto,
            ];
        }

        return response()->json($arreglo);
    }
}
