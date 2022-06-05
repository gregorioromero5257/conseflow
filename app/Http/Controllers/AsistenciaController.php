<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use App\Asistencia;
use App\Registro;
use App\Dia;
use Illuminate\Validation\Rule;
use App\Imports\NominaImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RHExport;
use \App\Http\Helpers\Utilidades;
use App\EstadosAsistencia;
use App\Contrato;
use App\ContratoDBP;
use App\Empleado;
use App\EmpleadoDBP;
use Exception;
use Monolog\Handler\FirePHPHandler;

class AsistenciaController extends Controller
{

  /**
   * [index Consulta en la BD de la tabla asistencias]
   * @return Response [description]
   */
  public function index()
  {
    $asistencia = DB::table('asistencias')
      ->join('dias AS Dia', 'Dia.id', '=', 'asistencias.dia_id')
      ->join('registros AS Registro', 'Registro.id', '=', 'asistencias.registro_id')
      ->join('empleados AS Empleado', 'Empleado.id', '=', 'asistencias.empleado_id')
      ->select(
        'asistencias.*',
        'Dia.nombre AS dia',
        'Registro.hora_entrada AS hora_entrada',
        DB::raw("CONCAT(Empleado.nombre,' ',Empleado.ap_paterno,' ',Empleado.ap_materno) AS nombrec")
      )
      ->get();
    return response()->json($asistencia->toArray());
  }

  /**
   * [store guarda un registro en la base de datos]
   * @param  Request $request [description]
   * @return Response           [description]
   */
  public function store(Request $request)
  {
    try
    {
      if (!$request->ajax()) return redirect('/');
      $asistencia = new Asistencia();
      $asistencia->fill($request->all());
      Utilidades::auditar($asistencia, $asistencia->id);
      $asistencia->save();

      return response()->json(array('status' => true));
    }
    catch (\Throwable $e)
    {
      Utilidades::errors($e);
    }
  }

  public function update(Request $request)
  {
  }

  public function edit($id)
  {
  }
  public function show($id)
  {
    try
    {
     // $this->cargarDatos($id);
      $ms="";
      $TOTAL_POR_PAGINA = 20;
      DB::beginTransaction();

      // Fechas
      $dts = explode('&', $id);
      if (count($dts) != 3) return response()->json(["status" => false, "mensaje" => "Datos incompletos"]);
      $fecha_uno = $dts[0];
      $fecha_dos = $dts[1];
      $index = $dts[2];
      $fechas = [];

      $empleados = \App\RegChecador::
        select('empleado_id','empleado')
      ->orderBy('empleado')
      ->whereBetween('reg_checador.fecha',[$fecha_uno,$fecha_dos])
      ->groupBy('empleado_id','empleado')
      ->offset($index * $TOTAL_POR_PAGINA)
      ->limit($TOTAL_POR_PAGINA)
      ->get();

      // Obtener todos los empleados activos
      // $empleados = Empleado::whereIn('id_checador', ['1', '2', '3', '4'])
      //   ->select(
      //     'empleados.id as id',
      //     DB::raw("CONCAT(nombre,' ',ap_paterno,' ',ap_materno) as nombre")
      //   )
      //   ->orderBy('nombre')
      //   ->offset($index * $TOTAL_POR_PAGINA)
      //   ->limit($TOTAL_POR_PAGINA)
      //   ->get();

      // $aux = Empleado::whereIn('id_checador', ['1', '2', '3', '4'])->count();
      $a = \App\RegChecador::
        select('empleado_id')
      ->whereBetween('fecha',[$fecha_uno,$fecha_dos])
      ->groupBy('empleado_id')
      ->get();

      $aux =count($a);

      // return response()->json();

      $paginas = [];
      $n = ceil($aux / $TOTAL_POR_PAGINA);

      for ($i = 0; $i < $n; $i++)
      {
        array_push($paginas, ["index" => $i, "nombre" => $i + 1]);
      }

      $todo = [];
      // Obtener registros
      foreach ($empleados as $empleado)
      {
        $fecha_inicio = strtotime($fecha_uno);
        $fecha_fin = strtotime($fecha_dos);
        $dia = [];
        for ($i = $fecha_inicio; $i <= $fecha_fin; $i += 86400)
        {
          $fecha_actual = date("Y-m-d", $i);
          $asistencia = \App\RegChecador::
            join('empleados AS e','e.id','reg_checador.empleado_id')
            ->select('reg_checador.*',DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS nombre"))
            ->where("empleado_id", $empleado->empleado_id)
            ->where("fecha", $fecha_actual)->get();

          $horario = "";
          $registro = null;
          // if ($asistencia == null)
          // {
          //   // Falta. Agregrar registro vacío
          //   // Crear registro ???
          //   $registro = new Registro();
          //   $registro->hora_entrada = "00:00:00";
          //   $registro->hora_salida = "00:00:00";
          //   // $registro->estado = 0;
          //   $registro->pendiente = 0;
          //   $registro->estado_asistencia_id=0;
          //   $registro->save();
          //
          //   // Crear asistencia ???
          //   $nueva_asis = new Asistencia();
          //   $nueva_asis->fecha = $fecha_actual;
          //   $nueva_asis->registro_id = $registro->id;
          //   $nueva_asis->registro_id = $registro->id;
          //   $nueva_asis->empleado_id = $empleado->id;
          //   $nueva_asis->save();
          //   $horario = "00:00 - 00:00";
          // }
          // else
          // {
            // Asistencia
            // $registro = Registro::find($asistencia->registro_id);
            foreach ($asistencia as $ka => $va) {
              $horario .= (substr($va->hora,0,5)).'-';
            }
          // }
          $ms=0;
          array_push($dia, [
            "fecha" => $fecha_actual,
             "horario" => $horario,
            "reg_id" => 0,
            "estado" => 1,
            "comentarios" => '',
          ]);
        }
        array_push(
          $todo,
          ["asistencias" => $dia, "datos_empleado" =>
          ["id" => $empleado->empleado_id, "nombre" => $empleado->empleado]]
        );
      }

      // Obtener todas las fechas
      for ($i = $fecha_inicio; $i <= $fecha_fin; $i += 86400)
      {
        $dia_nombre=$this->get_nombre_dia(date('Y-m-d', $i));
        array_push($fechas,[$dia_nombre,date('d/m/Y', $i)]);
      }
      DB::commit();
      return response()->json(
        [
          "status" => true, "asistencias" => $todo,
          "fechas" => $fechas,
          "paginas" => $paginas,
          "ms"=>$ms,
        ]
      );
    }
    catch (Exception $e)
    {
      DB::rollBack();
      Utilidades::errors($e);
      return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
    }
  }

  public function findE(Request $request)
  {
    try
    {
     // $this->cargarDatos($id);
      $ms="";
      $TOTAL_POR_PAGINA = 20;
      DB::beginTransaction();

      // Fechas
      // $dts = explode('&', $id);
      // if (count($dts) != 3) return response()->json(["status" => false, "mensaje" => "Datos incompletos"]);
      $fecha_uno = $request->fecha_uno;
      $fecha_dos = $request->fecha_dos;
      $index = $request->index;
      $fechas = [];

      $empleados = \App\RegChecador::
        select('empleado_id','empleado')
      ->orderBy('empleado')
      ->where('empleado','LIKE','%'.$request->data.'%')
      ->whereBetween('reg_checador.fecha',[$fecha_uno,$fecha_dos])
      ->groupBy('empleado_id','empleado')
      ->offset($index * $TOTAL_POR_PAGINA)
      ->limit($TOTAL_POR_PAGINA)
      ->get();

      // Obtener todos los empleados activos
      // $empleados = Empleado::whereIn('id_checador', ['1', '2', '3', '4'])
      //   ->select(
      //     'empleados.id as id',
      //     DB::raw("CONCAT(nombre,' ',ap_paterno,' ',ap_materno) as nombre")
      //   )
      //   ->orderBy('nombre')
      //   ->offset($index * $TOTAL_POR_PAGINA)
      //   ->limit($TOTAL_POR_PAGINA)
      //   ->get();

      // $aux = Empleado::whereIn('id_checador', ['1', '2', '3', '4'])->count();
      $a = \App\RegChecador::
        select('empleado_id')
        ->where('empleado','LIKE','%'.$request->data.'%')
      ->whereBetween('fecha',[$fecha_uno,$fecha_dos])
      ->groupBy('empleado_id')
      ->get();

      $aux =count($a);

      // return response()->json();

      $paginas = [];
      $n = ceil($aux / $TOTAL_POR_PAGINA);

      for ($i = 0; $i < $n; $i++)
      {
        array_push($paginas, ["index" => $i, "nombre" => $i + 1]);
      }

      $todo = [];
      // Obtener registros
      foreach ($empleados as $empleado)
      {
        $fecha_inicio = strtotime($fecha_uno);
        $fecha_fin = strtotime($fecha_dos);
        $dia = [];
        for ($i = $fecha_inicio; $i <= $fecha_fin; $i += 86400)
        {
          $fecha_actual = date("Y-m-d", $i);
          $asistencia = \App\RegChecador::
            join('empleados AS e','e.id','reg_checador.empleado_id')
            ->select('reg_checador.*',DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS nombre"))
            ->where("empleado_id", $empleado->empleado_id)
            ->where("fecha", $fecha_actual)->get();

          $horario = "";
          $registro = null;
          // if ($asistencia == null)
          // {
          //   // Falta. Agregrar registro vacío
          //   // Crear registro ???
          //   $registro = new Registro();
          //   $registro->hora_entrada = "00:00:00";
          //   $registro->hora_salida = "00:00:00";
          //   // $registro->estado = 0;
          //   $registro->pendiente = 0;
          //   $registro->estado_asistencia_id=0;
          //   $registro->save();
          //
          //   // Crear asistencia ???
          //   $nueva_asis = new Asistencia();
          //   $nueva_asis->fecha = $fecha_actual;
          //   $nueva_asis->registro_id = $registro->id;
          //   $nueva_asis->registro_id = $registro->id;
          //   $nueva_asis->empleado_id = $empleado->id;
          //   $nueva_asis->save();
          //   $horario = "00:00 - 00:00";
          // }
          // else
          // {
            // Asistencia
            // $registro = Registro::find($asistencia->registro_id);
            foreach ($asistencia as $ka => $va) {
              $horario .= (substr($va->hora,0,5)).'-';
            }
          // }
          $ms=0;
          array_push($dia, [
            "fecha" => $fecha_actual,
             "horario" => $horario,
            "reg_id" => 0,
            "estado" => 1,
            "comentarios" => '',
          ]);
        }
        array_push(
          $todo,
          ["asistencias" => $dia, "datos_empleado" =>
          ["id" => $empleado->empleado_id, "nombre" => $empleado->empleado]]
        );
      }

      // Obtener todas las fechas
      for ($i = $fecha_inicio; $i <= $fecha_fin; $i += 86400)
      {
        $dia_nombre=$this->get_nombre_dia(date('Y-m-d', $i));
        array_push($fechas,[$dia_nombre,date('d/m/Y', $i)]);
      }
      DB::commit();
      return response()->json(
        [
          "status" => true, "asistencias" => $todo,
          "fechas" => $fechas,
          "paginas" => $paginas,
          "ms"=>$ms,
        ]
      );
    }
    catch (Exception $e)
    {
      DB::rollBack();
      Utilidades::errors($e);
      return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
    }
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function upload(Request $request)
  {
    try
    {
      if (!$request->ajax()) return redirect('/');
      if ($request->hasFile('import_file'))
      {
        ini_set('max_execution_time', 300);
        $fechas = $request->fecha_uno . '&' . $request->fecha_dos;
        $collection = Excel::import(new NominaImport($fechas), request()->file('import_file'));
        return response()->json($collection);
      }
    }
    catch (\Exception $e)
    {
      return response()->json($e);
    }
  }

  public function generareporte($id)
  {
    $valores = explode('&', $id);
    $tipo = '';
    if ($valores[2] === '3')
    {
      $tipo = 'SemanalCSCT';
    }
    if ($valores[2] === '1')
    {
      $tipo = 'SemanalConserflow';
    }
    if ($valores[2] === '4')
    {
      $tipo = 'QuincenaCSCT';
    }
    if ($valores[2] === '2')
    {
      $tipo = 'QuincenaConserflow';
    }
    return Excel::download(new RHExport($id), 'Asistencia' . $tipo . '.xlsx');
  }

  public function registro($id)
  {
    $valores = explode('&', $id);
    $arreglo = [];

    $fechaInicio = strtotime($valores[1]);
    $fechaFin = strtotime($valores[2]);

    for ($i = $fechaInicio; $i <= $fechaFin; $i += 86400)
    {
      $arreglo_fechas_buscar[] = date('Y-m-d', $i);
    }

    foreach ($arreglo_fechas_buscar as $key => $value)
    {
      $asistencias = Asistencia::join('registros AS R', 'R.id', '=', 'asistencias.registro_id')
        ->leftJoin('estado_asistencia_registros AS ear', 'ear.id', 'R.estado_asistencia_id')
        ->select('R.*', 'asistencias.fecha', 'asistencias.dia_id', 'asistencias.registro_id', 'ear.nombre AS estado_nombre')
        ->where('asistencias.empleado_id', $valores[0])
        ->where('asistencias.fecha', $value)
        ->first();

      $retardo = '';
      if (isset($asistencias) == true)
      {
        if ($asistencias->hora_entrada != '')
        {
          $hora1 = strtotime($asistencias->hora_entrada);
          $hora2 = strtotime("08:10:00");
          if ($hora1 > $hora2)
          {
            $retardo = 'Retardo';
          }
        }
        if ($asistencias->hora_entrada != '00:00:00' && $asistencias->hora_salida != '00:00:00')
        {
          $reg_change = Registro::where('id', $asistencias->id)->first();
          $reg_change->estado_asistencia_id = 1;
          $reg_change->update();
        }

        $v = $asistencias;
      }
      else
      {

        $registros = new Registro();
        $registros->hora_entrada = '00:00:00';
        $registros->hora_salida_comida = '00:00:00';
        $registros->hora_entrada_comida = '00:00:00';
        $registros->hora_salida = '00:00:00';
        $registros->pendiente = 1;
        $registros->save();

        $asistencia = new Asistencia();
        $asistencia->fecha = $value;
        $asistencia->registro_id = $registros->id;
        $asistencia->empleado_id = $valores[0];
        Utilidades::auditar($asistencia, $asistencia->id);
        $asistencia->save();

        $asistencias = Asistencia::join('registros AS R', 'R.id', '=', 'asistencias.registro_id')
          ->leftJoin('estado_asistencia_registros AS ear', 'ear.id', 'R.estado_asistencia_id')
          ->select('R.*', 'asistencias.fecha', 'asistencias.dia_id', 'asistencias.registro_id', 'ear.nombre AS estado_nombre')
          ->where('asistencias.empleado_id', $valores[0])
          ->where('asistencias.fecha', $value)
          ->first();

        $v = $asistencias;
      }

      $arreglo[] = [
        'registros' => $v,
        'retardo' => $retardo,
        'dia' => $this->get_nombre_dia($asistencias->fecha),
      ];
    }
    return response()->json($arreglo);
  }

  public function get_nombre_dia($fecha)
  {
    $fechats = strtotime($fecha); //pasamos a timestamp

    //el parametro w en la funcion date indica que queremos el dia de la semana
    //lo devuelve en numero 0 domingo, 1 lunes,....
    switch (date('w', $fechats))
    {
      case 0:
        return "Domingo";
        break;
      case 1:
        return "Lunes";
        break;
      case 2:
        return "Martes";
        break;
      case 3:
        return "Miercoles";
        break;
      case 4:
        return "Jueves";
        break;
      case 5:
        return "Viernes";
        break;
      case 6:
        return "Sabado";
        break;
    }
  }

  public function getEstados()
  {
    $estados = EstadosAsistencia::orderBy('nombre')->get();
    return response()->json(["estados"=>$estados]);
  }

  public function DetallesHorario($id)
  {
    try
    {
      $r=Registro::find($id);
      $r->estado_asistencia_id=$r->estado_asistencia_id==null? -1:$r->estado_asistencia_id;
      return response()->json(["status"=>true,"registro"=>$r]);
    }
    catch (Exception $e)
    {
      return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
    }
  }

  public function Actualizar(Request $r)
  {
    try
    {
      $registro=Registro::find($r->registro_id);
      $registro->estado_asistencia_id=$r->tipo_estado;
      $registro->observaciones=$r->comentarios;
      $registro->update();
      return response()->json(["status"=>true]);
    }
    catch (Exception $e)
    {
      return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
    }
  }
  public function guardarEstado(Request $request)
  {
    try
    {

      $registro = Registro::where('id', $request->registro_id)->first();
      $registro->estado_asistencia_id = $request->valor;
      $registro->update();

      return response()->json(['status' => true]);
    }
    catch (\Throwable $e)
    {
      Utilidades::errors($e);
    }
  }

  public function guardarNuevoEstado(Request $request)
  {
    try
    {
      $estado = new EstadosAsistencia();
      $estado->nombre = $request->nombre_estado;
      $estado->save();
      return response()->json(['status' => true]);
    }
    catch (\Throwable $e)
    {
      return response()->json(["status"=>false,"mensaje"=>$e->getMessage()]);
      Utilidades::errors($e);
    }
  }

  public function guardarNuevoObservacion(Request $request)
  {
    try
    {
      $registro = Registro::where('id', $request->registro_id)->first();
      $registro->observaciones = $request->valor;
      $registro->update();

      return response()->json(['status' => true]);
    }
    catch (\Throwable $e)
    {
      Utilidades::errors($e);
    }
  }

  public function cargarDatos($id){
    try {
      $reg = \App\RegChecador::where('estado','0')->get();

      foreach ($reg as $key => $value) {
        $asistencia = DB::table('asistencias')->where('fecha',$value->fecha)->where('empleado_id',$value->empleado_id)->first();
        // NOTE: CASOS PARA REGISTRAR LA ASISITENCIA
        /**
        * 1.- NO EXIXTE REGISTROS SE TOMA COMO ENTRADA SIN IMPORTAR LA Hora
        * 2.- EXISTE UN REGISTRO DE ENTRADA, EL
        * 3.-
        **/

        if (isset($asistencia) == true) {

        }else {

        }
      }

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

}
