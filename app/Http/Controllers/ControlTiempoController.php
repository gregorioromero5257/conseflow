<?php

namespace App\Http\Controllers;

use App\Controltiempo;
use App\Exports\ControlTiempoExport;
use App\Exports\ControlTiempoCompletoExport;
use App\Exports\ControlTiempoGeneralExport;
use App\Exports\ControlTiempoGeneralProyectoExport;
use App\Exports\ControlTiempoGeneralAdminExport;
use App\Exports\ControlTiempoSemanalExport;
use App\Exports\ControlTiempoGeneralSinProExport;
use App\Exports\ReporteListasExport;
use App\Exports\ReporteTiempoExport;
use App\Exports\InventarioExport;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use \App\Http\Helpers\Utilidades;
use App\Contrato;
use App\ControlTiempoIncidencias;


class ControlTiempoController extends Controller
{
  //

  public function index(Request $request)
  {
    $ids = Auth::user();
    // /*Obtiene todos los registros del tipo de percepciones*/
    $empleado = DB::table('empleados')->where('id',$ids->empleado_id)->first();
    return response()->json($empleado);
  }

  public function getEmp()
  {
    $ids = Auth::user();
    // /*Obtiene todos los registros del tipo de percepciones*/
    if($ids->id == 37 || $ids->id == 67 || $ids->empleado_id == 119 || $ids->id == 32 || $ids->empleado_id == 226 ){
      $controltiempo = DB::table('control_tiempo')
      ->join('empleados as E', 'E.id', '=', 'control_tiempo.empleado_asignado_id')
      ->join('empleados as EE','EE.id','=','control_tiempo.supervisor_id')
      ->leftJoin('proyectos', 'proyectos.id', '=', 'control_tiempo.proyecto_id')
      // ->join('contratos', 'contratos.id', '=', 'contratos.proyecto_id')
      ->select('control_tiempo.*',
      'proyectos.nombre_corto',
      DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS asignado_nombre"),
      DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS supervisor_nombre"))
      // ->whereBetween('control_tiempo.fecha',[$request->fechauno, $request->fechados])
      ->orderBy('control_tiempo.id','desc')
      ->get();

      return response()->json($controltiempo);

    }elseif ($ids->id == 80) {
      $controltiempo = DB::table('control_tiempo')
      ->join('empleados as E', 'E.id', '=', 'control_tiempo.empleado_asignado_id')
      ->join('empleados as EE','EE.id','=','control_tiempo.supervisor_id')
      ->leftJoin('proyectos', 'proyectos.id', '=', 'control_tiempo.proyecto_id')
      // ->join('contratos', 'contratos.id', '=', 'contratos.proyecto_id')
      ->select('control_tiempo.*',
      'proyectos.nombre_corto',
      DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS asignado_nombre"),
      DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS supervisor_nombre"))
      // ->whereBetween('control_tiempo.fecha',[$request->fechauno, $request->fechados])
      ->where('control_tiempo.supervisor_id','10')
      ->orWhere('control_tiempo.supervisor_id','165')
      ->orderBy('control_tiempo.id','desc')
      ->get();

      return response()->json($controltiempo);
    } else{
      $controltiempo = DB::table('control_tiempo')
      ->join('empleados as E', 'E.id', '=', 'control_tiempo.empleado_asignado_id')
      ->join('empleados as EE','EE.id','=','control_tiempo.supervisor_id')
      ->leftJoin('proyectos', 'proyectos.id', '=', 'control_tiempo.proyecto_id')
      // ->join('contratos', 'contratos.id', '=', 'contratos.proyecto_id')
      ->select('control_tiempo.*',
      'proyectos.nombre_corto',
      DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS asignado_nombre"),
      DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS supervisor_nombre"))
      // ->whereBetween('control_tiempo.fecha',[$request->fechauno, $request->fechados])

      ->where('control_tiempo.supervisor_id',$ids->empleado_id)
      ->orderBy('control_tiempo.id','desc')->get();

      return response()->json($controltiempo);
    }
  }

  public function getTH($id)
  {
    $valores = explode('&',$id);
    // $ids = Auth::user();
    // /*Obtiene todos los registros del tipo de percepciones*/
      $controltiempo = DB::table('control_tiempo')
      ->join('empleados as E', 'E.id', '=', 'control_tiempo.empleado_asignado_id')
      ->join('empleados as EE','EE.id','=','control_tiempo.supervisor_id')
      ->leftJoin('horas_extras AS he','he.control_tiempo_id','=','control_tiempo.id')
      ->leftJoin('proyectos AS ps', 'ps.id', '=', 'he.proyecto_id')
      ->leftJoin('proyectos', 'proyectos.id', '=', 'control_tiempo.proyecto_id')
      ->select('control_tiempo.*',
        'ps.nombre_corto AS nombre_corto_he','ps.id AS proyecto_id_he',
        'proyectos.nombre_corto',
        DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS asignado_nombre"),
        DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS supervisor_nombre"))
      ->whereBetween('control_tiempo.fecha',[$valores[0],$valores[1]])
      ->orderBy('control_tiempo.id','desc')
      ->get();

      return response()->json($controltiempo);


  }

  public function busqueda()
  {
    extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));

    $data = $this->getDataC();

    if (isset($query) && $query) {
      $data = $byColumn == 1 ?
      $this->busqueda_filterByColumn($data, $query) :
      $this->busqueda_filter($data, $query, $fields);
    }

    $count = $data->count();

    $data->limit($limit)
    ->skip($limit * ($page - 1));

    if (isset($orderBy)) {
      $direction = $ascending == 1 ? 'ASC' : 'DESC';
      $data->orderBy($orderBy, $direction);
    }

    $results = $data->get();

    return [
      'data' => $results,
      'count' => $count,
    ];
  }

  protected function busqueda_filterByColumn($data, $queries)
  {
    $queries = json_decode($queries, true);

    return $data->where(function ($q) use ($queries) {
      foreach ($queries as $field => $query) {
        $_field = $field;

        if (is_string($query)) {
          $q->where($_field, 'LIKE', "%{$query}%");
        } else {
          $start = Carbon::createFromFormat('Y-m-d', substr($query['start'], 0, 10))->startOfDay();
          $end = Carbon::createFromFormat('Y-m-d', substr($query['end'], 0, 10))->endOfDay();

          $q->whereBetween($_field, [$start, $end]);
        }
      }
    });
  }

  protected function busqueda_filter($data, $query, $fields)
  {
    return $data->where(function ($q) use ($query, $fields) {
      foreach ($fields as $index => $field) {
        $method = $index ? 'orWhere' : 'where';
        $q->{$method}($field, 'LIKE', "%{$query}%");
      }
    });
  }

  public function getDataC()
  {
    $ids = Auth::user();
    // /*Obtiene todos los registros del tipo de percepciones*/
    $controltiempo = DB::table('control_tiempo')
    ->join('empleados as E', 'E.id', '=', 'control_tiempo.empleado_asignado_id')
    ->join('empleados as EE','EE.id','=','control_tiempo.supervisor_id')
    ->leftJoin('proyectos', 'proyectos.id', '=', 'control_tiempo.proyecto_id')
    // ->join('contratos', 'contratos.id', '=', 'contratos.proyecto_id')
    ->select('control_tiempo.*',
    'proyectos.nombre_corto','E.nombre','E.ap_paterno','E.ap_materno',
    DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS asignado_nombre"),
    DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS supervisor_nombre"))
    // ->whereBetween('control_tiempo.fecha',[$request->fechauno, $request->fechados])
    ->where('control_tiempo.supervisor_id',$ids->empleado_id);

    return $controltiempo;
  }


  public function store(Request $request)
  {

    try {

      $status = true;
      $supervisor_id = $request->supervisor_id['id'];
      $fecha= $request->fecha;
      $proyecto = $request->proyecto_id;
      $horas_extra = $horas_extras = $request->horas_extras;
      $empresa = $request->empresa;
      $arreglo  = $this->elementosUnicos($request->empleado_asignado_id);
      $tamaño = count($arreglo);

      if($tamaño != 0){
        $arreglo_ = [];
        foreach ($arreglo as $key => $value) {

          $resultado =  $this->guardaEmpleadoAsignado(
            $supervisor_id,
            $value['id'],
            $fecha,
            $proyecto,
            $horas_extra,
            $empresa
          );

          if ($resultado != 'NA') {
            $arreglo_ [] = $resultado;
          }

        }
      }
      return response()->json(array('status'=>true));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return response()->json(array('status' => 'error'));
    }


  }


  public function update(Request $request)
  {
    try
    {
      // Buscar control
      $control=Controltiempo::findOrFail($request->id_control);
      if($control==null) return response()->json(array('status'=>"error","mensaje"=>" No encontrado"));
      // Actualizar
      $control->fecha=$request->fecha;
      $control->update();
      return response()->json(array('status'=>true));
    }
    catch (\Throwable $e)
    {
      Utilidades::errors($e);
      return response()->json(array('status' => 'error'));
    }
  }

  public function elementosUnicos($array)
  {
    $arraySinDuplicados = [];
    foreach($array as $elemento) {
      if (!in_array($elemento, $arraySinDuplicados)) {
        $arraySinDuplicados[] = $elemento;
      }
    }
    return $arraySinDuplicados;
  }



  public function guardaEmpleadoAsignado ($supervisor_id,$empleado_asignado_id,$fecha,$proyecto,$horas_extra,$empresa ){
    try {
      $data = \App\Controltiempo::where('empleado_asignado_id',$empleado_asignado_id)->where('fecha',$fecha)
      ->where('nave','NULL')->first();

      if (isset($data) == true) {
        $empleado = DB::table('empleados')
        ->select(DB::raw("CONCAT(nombre,' ',ap_paterno,' ',ap_materno) AS nombre"))
        ->where('id',$empleado_asignado_id)->first();
        if($data->supervisor_id != $supervisor_id){
          $incidencias_control_tiempo  = new ControlTiempoIncidencias();
          $incidencias_control_tiempo->supervisor_id = $supervisor_id;
          $incidencias_control_tiempo->empleado_asignado_id = $empleado_asignado_id;
          $incidencias_control_tiempo->fecha = $fecha;
          $incidencias_control_tiempo->proyecto_id = $data->proyecto_id;
          $incidencias_control_tiempo->supervisor_conflicto_id = $data->supervisor_id;
          Utilidades::auditar($incidencias_control_tiempo, $incidencias_control_tiempo->id);
          $incidencias_control_tiempo->save();
        }
        return $empleado->nombre;

      }else {
        $guardaSupervisor = new \App\Controltiempo();
        $guardaSupervisor->supervisor_id = $supervisor_id;
        $guardaSupervisor->empleado_asignado_id = $empleado_asignado_id;
        $guardaSupervisor->fecha  = $fecha;
        $guardaSupervisor->proyecto_id = $proyecto;
        $guardaSupervisor->horas_extras =$horas_extra;
        $guardaSupervisor->empresa = $empresa;

        Utilidades::auditar($guardaSupervisor, $guardaSupervisor->id);
        $guardaSupervisor->save();

        return 'NA';
      }

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }

  public function comparaEmpleado(Request $request)
  {
    $validator = Validator::make($request->all(), $this->rules);

    $comparar = DB::table('control_tiempo')
    ->select('control_tiempo.empleado_asignado_id')
    ->get()->toArray();

    if ($comparar == $request->empleado_asignado_id) {
      return response()->json(array(
        'status' => false,
        'errors' => $validator->errors()->all()
      ));
    }
    else{
      return response()->json(array('status'=>true));
    }
  }


  public function compararFechas(Request $request)
  {
    $ids = Auth::user();
    // $intervalo = DB::select ("SELECT s.*, FROM control_tiempo as s WHERE (fecha >= '$request->fechauno'  AND fecha <= '$request->fechados')");
    $arreglo = [];
    if ($ids->id == 37 || $ids->id == 67 || $ids->empleado_id == 119 || $ids->id == 32){

      $controltiempo = DB::table('control_tiempo')
      ->join('empleados as E', 'E.id', '=', 'control_tiempo.empleado_asignado_id')
      ->join('empleados as EE','EE.id','=','control_tiempo.supervisor_id')
      ->leftJoin('proyectos', 'proyectos.id', '=', 'control_tiempo.proyecto_id')
      ->select('control_tiempo.*',
      'proyectos.nombre_corto',
      DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS asignado_nombre"),
      DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS supervisor_nombre"))
      ->whereBetween('control_tiempo.fecha',[$request->fechauno, $request->fechados])
      ->orderBy('control_tiempo.id','desc')
      ->get();



      return response()->json($controltiempo);

    }elseif ($ids->id == 80) {
      $controltiempo = DB::table('control_tiempo')
      ->join('empleados as E', 'E.id', '=', 'control_tiempo.empleado_asignado_id')
      ->join('empleados as EE','EE.id','=','control_tiempo.supervisor_id')
      ->leftJoin('proyectos', 'proyectos.id', '=', 'control_tiempo.proyecto_id')
      // ->join('contratos', 'contratos.id', '=', 'contratos.proyecto_id')
      ->select('control_tiempo.*',
      'proyectos.nombre_corto',
      DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS asignado_nombre"),
      DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS supervisor_nombre"))
      ->whereBetween('control_tiempo.fecha',[$request->fechauno, $request->fechados])
      ->where('control_tiempo.supervisor_id','10')
      ->orWhere('control_tiempo.supervisor_id','165')
      ->whereBetween('control_tiempo.fecha',[$request->fechauno, $request->fechados])
      ->orderBy('control_tiempo.id','desc')
      ->get();

      $id = Auth::user();
      $rict = DB::table('incidencias_control_tiempo')
      ->join('empleados as E', 'E.id', '=', 'incidencias_control_tiempo.empleado_asignado_id')
      ->join('empleados as EE','EE.id','=','incidencias_control_tiempo.supervisor_conflicto_id')
      ->leftJoin('proyectos', 'proyectos.id', '=', 'incidencias_control_tiempo.proyecto_id')
      ->select('incidencias_control_tiempo.*',
      'proyectos.nombre_corto',
      DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS asignado_nombre"),
      DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS supervisor_nombre"))
      ->where('supervisor_id',$id->empleado_id)
      // ->where('fecha', date('Y-m-d'))
      ->whereBetween('fecha',[$request->fechauno, $request->fechados])
      ->get();

      $arreglo = [
        'datos' => $controltiempo,
        'incidencia' => $rict,
      ];
      return response()->json($arreglo);
    }
    else{

      $controltiempo = DB::table('control_tiempo')
      ->join('empleados as E', 'E.id', '=', 'control_tiempo.empleado_asignado_id')
      ->join('empleados as EE','EE.id','=','control_tiempo.supervisor_id')
      ->leftJoin('proyectos', 'proyectos.id', '=', 'control_tiempo.proyecto_id')
      // ->join('contratos', 'contratos.id', '=', 'contratos.proyecto_id')
      ->select('control_tiempo.*',
      'proyectos.nombre_corto',
      DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS asignado_nombre"),
      DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS supervisor_nombre"))
      ->whereBetween('control_tiempo.fecha',[$request->fechauno, $request->fechados])
      ->where('control_tiempo.supervisor_id',$ids->empleado_id)
      ->orderBy('control_tiempo.id','desc')->get();

      $id = Auth::user();
      $rict = DB::table('incidencias_control_tiempo')
      ->join('empleados as E', 'E.id', '=', 'incidencias_control_tiempo.empleado_asignado_id')
      ->join('empleados as EE','EE.id','=','incidencias_control_tiempo.supervisor_conflicto_id')
      ->leftJoin('proyectos', 'proyectos.id', '=', 'incidencias_control_tiempo.proyecto_id')
      ->select('incidencias_control_tiempo.*',
      'proyectos.nombre_corto',
      DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS asignado_nombre"),
      DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS supervisor_nombre"))
      ->where('supervisor_id',$id->empleado_id)
      // ->where('fecha', date('Y-m-d'))
      ->whereBetween('fecha',[$request->fechauno, $request->fechados])
      ->get();

      $arreglo = [
        'datos' => $controltiempo,
        'incidencia' => $rict,
      ];
      return response()->json($arreglo);
    }
  }

  public function getEmpleadoste($id)
  {
    $ids = Auth::user();

    $controltiempo = DB::table('control_tiempo')
    ->join('empleados as E', 'E.id', '=', 'control_tiempo.empleado_asignado_id')
    ->join('empleados as EE','EE.id','=','control_tiempo.supervisor_id')
    ->leftJoin('proyectos', 'proyectos.id', '=', 'control_tiempo.proyecto_id')
    ->select('control_tiempo.*',
    'proyectos.nombre_corto',
    DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS asignado_nombre"),
    DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS supervisor_nombre"))
    ->where('control_tiempo.fecha',$id)
    ->where('control_tiempo.supervisor_id',$ids->empleado_id)
    ->get();

    return response()->json($controltiempo);
  }


  public function excel($id)
  {
    ini_set('max_execution_time', 3000);
    return Excel::download(new ControlTiempoExport($id), 'ReporteControlTiempo.xlsx');
  }

  public function excelCompleto($id)
  {
    ini_set('max_execution_time', 3000);
    return Excel::download(new ControlTiempoCompletoExport($id), 'ReporteControlTiempoCompleto.xlsx');
  }


  public function excelGeneral($id)
  {
    ini_set('max_execution_time', 3000);
    return Excel::download(new ControlTiempoGeneralExport($id), 'ReporteControlTiempoGeneral.xlsx');
  }


  public function excelSemanal($id)
  {
    ini_set('max_execution_time', 3000);
    return Excel::download(new ControlTiempoSemanalExport($id), 'ReporteControlTiempoSemanal.xlsx');
  }


  public function excelGeneralProyecto($id)
  {
    ini_set('max_execution_time', 3000);
    return Excel::download(new ControlTiempoGeneralProyectoExport($id), 'ReporteControlTiempoGeneralProyecto.xlsx');
  }

  public function excelGeneraAdmin($id)
  {
    ini_set('max_execution_time', 3000);
    return Excel::download(new ControlTiempoGeneralAdminExport($id), 'ReporteControlTiempoGeneralAdmin.xlsx');
  }

  public function excelGeneraProsin($id)
  {
    ini_set('max_execution_time', 3000);
    return Excel::download(new ControlTiempoGeneralSinProExport($id), 'ReporteControlTiempoGeneralSinProyecto.xlsx');
  }

  public function excelReporteListas($id)
  {
    ini_set('max_execution_time', 3000);
    return Excel::download(new ReporteListasExport($id), 'ReporteListasExport.xlsx');
  }

  public function excelReporteTiempo($id)
  {
    ini_set('max_execution_time', 3000);
    return Excel::download(new ReporteTiempoExport($id), 'ReporteTiempoExport.xlsx');
  }

  public function GuardarHE(Request $request)
  {
    $ct = \App\Controltiempo::where('id',$request->id)->first();
    $ct->horas_extras = $request->horas_extras;
    $ct->contador = $ct->contador + 1;
    $ct->save();

    return response()->json(array('status' => true));
  }

  public function buscarSuper(Request $value)
  {
    $empleado_supervisor = DB::table('empleados')
    ->where('nombre','LIKE','%'.$value->name.'%')
    ->orWhere('ap_paterno','LIKE','%'.$value->name.'%')
    ->orWhere('ap_materno','LIKE','%'.$value->name.'%')
    ->first();

    if (isset($empleado_supervisor)==true) {
      $controltiempo = DB::table('control_tiempo')
      ->join('empleados as E', 'E.id', '=', 'control_tiempo.empleado_asignado_id')
      ->join('empleados as EE','EE.id','=','control_tiempo.supervisor_id')
      ->leftJoin('proyectos', 'proyectos.id', '=', 'control_tiempo.proyecto_id')
      // ->join('contratos', 'contratos.id', '=', 'contratos.proyecto_id')
      ->select('control_tiempo.*',
      'proyectos.nombre_corto',
      DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS asignado_nombre"),
      DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS supervisor_nombre"))
      ->where('supervisor_id',$empleado_supervisor->id)
      // ->whereBetween('control_tiempo.fecha',[$request->fechauno, $request->fechados])
      ->get();

      return response()->json($controltiempo);
    }else {
      return response()->json(array('edo' => 'No'));
    }
  }

  public function getIncidencias()
  {
    $id = Auth::user();
    $rict = DB::table('incidencias_control_tiempo')
    ->join('empleados as E', 'E.id', '=', 'incidencias_control_tiempo.empleado_asignado_id')
    ->join('empleados as EE','EE.id','=','incidencias_control_tiempo.supervisor_conflicto_id')
    ->leftJoin('proyectos', 'proyectos.id', '=', 'incidencias_control_tiempo.proyecto_id')
    ->select('incidencias_control_tiempo.*',
    'proyectos.nombre_corto',
    DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS asignado_nombre"),
    DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS supervisor_nombre"))
    ->where('supervisor_id',$id->empleado_id)
    ->where('fecha', date('Y-m-d'))->get();
    return response()->json($rict);
  }

  public function horas(Request $request)
  {
    try {
      $ct = \App\Controltiempo::where('id',$request->id)->first();
      $ct->horas_extras = $request->horas;
      Utilidades::auditar($ct, $ct->id);
      $ct->save();

      return response()->json(array('status' => true));
    } catch (\Exception $e) {
      Utilidades::errors($e);
    }

  }

  public function proyecto(Request $request)
  {
    try {
      // return response()->json($request);
      $he = \App\HorasExtra::where('control_tiempo_id',$request->id)->first();
      if (isset($he) == false) {
        $he_new = new \App\HorasExtra();
        $he_new->proyecto_id = $request['proyecto_id'];
        $he_new->control_tiempo_id = $request->id;
        Utilidades::auditar($he_new, $he_new->id);
        $he_new->save();
      }
        $he_update = \App\HorasExtra::where('control_tiempo_id',$request->id)->first();
        $he_update->proyecto_id = $request['proyecto_id'];
        Utilidades::auditar($he_update, $he_update->id);
        $he_update->save();

      return response()->json(array('status' => true));
    } catch (\Exception $e) {
      Utilidades::errors($e);
    }

  }

  public function FunctionName($value='')
  {
    // code...
  }

}
