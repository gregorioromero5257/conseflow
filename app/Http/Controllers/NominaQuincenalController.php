<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
Use App\Nomina;
Use App\Contrato;
Use App\Empleado;
Use App\Sueldo;
Use App\MovimientosNomina;
Use Validator;
use Illuminate\Validation\Rule;
use App\Exports\QuincenaExport;
use App\Exports\QuincenaExportFin;
use App\Exports\QuincenaExportCon;
use App\Exports\NominaQExport;
use Maatwebsite\Excel\Facades\Excel;
use \App\Http\Helpers\Utilidades;


class NominaQuincenalController extends Controller
{

  public function index()
  {
    $nomina = \App\Nomina::where('tipo_nomina','=','1')->get();
    return response()->json($nomina);
  }
  public function excel($id)
  {
      return Excel::download(new QuincenaExport($id), 'ReporteQuincenal.xlsx' );
      // return (new QuincenaExport)->download('invoices.xlsx');
  }
  public function exceluno($id)
  {
      return Excel::download(new QuincenaExportFin($id), 'ReporteQuincenalAdmiFinan.xlsx' );
      // return (new QuincenaExport)->download('invoices.xlsx');
  }
  public function exceldos($id)
  {
      return Excel::download(new QuincenaExportCon($id), 'ReporteQuincenalContabilidad.xlsx' );
      // return (new QuincenaExport)->download('invoices.xlsx');
  }
  public function nominaexcel($id)
  {
    return Excel::download(new NominaQExport($id), 'ReporteNominaQuincenal.xlsx' );
  }
  public function proyecto($id)
  {
    $mov_nomi = DB::table('nominas_movimientos')
    ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
    ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
    ->select('P.*')
    ->where('C.tipo_nomina_id','=','2')->where('nominas_movimientos.nomina_id','=',$id)->distinct()->get();


    return response()->json($mov_nomi);
  }


  public function busqueda_por_proyecto($id){

    $mov_nomi = DB::table('nominas_movimientos AS nm')
    ->join('nomina AS n','n.id','nm.nomina_id')
    ->join('empleados AS e','e.id','nm.empleado_id')
    ->join('puestos AS p','p.id','e.puesto_id')
    ->leftjoin('proyectos AS pr','pr.id','nm.proyecto_id')
    ->select('nm.*',
    DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS nombre")
    ,'p.nombre AS nombre_puesto',
    'pr.nombre_corto','n.empresa'
    )
    ->orderBy('nm.id','ASC')
    ->where('nm.nomina_id',$id)
    ->get();

    $arreglo = [];

    foreach ($mov_nomi as $key => $value) {
      if($value->empresa == 2){
        $bancos = DB::table('datos_bancarios_empleados AS dbe')
        ->join('catalogo_bancos AS b','b.id','dbe.banco_id')
        ->select('dbe.*','b.nombre')
        ->where('empleado_id',$value->empleado_id)
        ->orderBy('id','DESC')
        ->get();

        $bancos_selecionado = DB::table('datos_bancarios_empleados AS dbe')
        ->join('catalogo_bancos AS b','b.id','dbe.banco_id')
        ->select('dbe.*','b.nombre')
        ->where('dbe.id',$value->banco_id)
        ->first();


      }elseif($value->empresa == 4) {
        $empleado_csct = $this->empleado_csct($value->empleado_id);

        $bancos = \App\DatosBancariosEmpleadoDBP::
        join('catalogo_bancos AS b','b.id','datos_bancarios_empleados.banco_id')
        ->select('datos_bancarios_empleados.*','b.nombre')
        ->where('empleado_id',$empleado_csct)
        ->orderBy('id','DESC')
        ->get();

        $bancos_selecionado = \App\DatosBancariosEmpleadoDBP::
        join('catalogo_bancos AS b','b.id','datos_bancarios_empleados.banco_id')
        ->select('datos_bancarios_empleados.*','b.nombre')
        ->where('datos_bancarios_empleados.id',$value->banco_id)
        ->first();
      }

      $arreglo [] = [
        'data' => $value,
        'datos_bancarios' => $bancos,
        'banco' => $bancos_selecionado,
      ];
    }

    return response()->json($arreglo);


  }

  public function calculo_dias_t()
  {
    $var = 0;
    $mes = date("n");
    $anio = date("Y");
    $dia = date("j");

    if ($dia <= 15) {
      $var = 15;
    }
    else {
      $numero = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
      if ($numero == 31) {
        $var = 16;
      }
      elseif($numero == 30){
        $var = 15;
      }
      else {
        $var = 15;
      }
    }

    return $var;
  }

  public function store(Request $request)
  {
    try {



      if (!$request->ajax()) return redirect('/');



      //1 conserflow Semanal y 3 csct semanal


      $contro_tiempo = DB::table('empleados AS e')
      ->select('e.id',
      DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado")
      ,'e.id_checador')
      ->where('e.id_checador',$request->empresa)
      ->get();

      $arreglo = [];
      foreach ($contro_tiempo as $key => $value) {
        $asistencias = 0;
        $proyectos = [];
        $r = [];

        if ($request->empresa == 4) {//csct

          $empleado_csct = $this->empleado_csct($value->id);

          $contrato = \App\ContratoDBP::
          leftJoin('sueldos AS s','s.contrato_id','contratos.id')
          ->select('contratos.*','s.sueldo_diario_integral','s.sueldo_mensual','s.sueldo_diario_real')
          ->where('contratos.empleado_id',$empleado_csct)
          ->where('contratos.condicion','1')
          ->first();

          $banco = \App\DatosBancariosEmpleadoDBP::where('empleado_id',$empleado_csct)
          ->orderBy('id','DESC')
          ->first();

        }elseif ($request->empresa == 2) {//conserflow
          $contrato = DB::table('contratos AS c')
          ->leftJoin('sueldos AS s','s.contrato_id','c.id')
          ->select('c.*','s.sueldo_diario_integral','s.sueldo_mensual','sueldo_diario_real')
          ->where('c.empleado_id',$value->id)
          ->where('c.condicion','1')
          ->first();

          $banco = \App\DatosBancariosEmpleado::where('empleado_id',$value->id)
          ->orderBy('id','DESC')
          ->first();
        }

        $contro_tiempo_registros = DB::table('control_tiempo AS ct')
        ->join('empleados AS ea','ea.id','ct.empleado_asignado_id')
        ->select('ct.empleado_asignado_id','ct.fecha',
        DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado")
        ,'ea.id_checador','ct.proyecto_id')
        ->where('ct.empleado_asignado_id',$value->id)
        ->whereBetween('fecha',[$request->fecha_inicial,$request->fecha_final])
        ->get();

        foreach ($contro_tiempo_registros as $keyt => $valuet) {
          $proyectos [] = $valuet->proyecto_id;
        }


        $checador_registros = DB::table('asistencias AS a')
        ->join('registros AS r','r.id','a.registro_id')
        ->leftjoin('estado_asistencia_registros AS ear','ear.id','r.estado_asistencia_id')
        ->select('a.*','r.hora_entrada','r.hora_salida','r.estado_asistencia_id','ear.nombre AS ea_nombre')
        ->where('a.empleado_id',$value->id)
        ->whereBetween('a.fecha',[$request->fecha_inicial,$request->fecha_final])
        ->orderBy('a.fecha','ASC')
        ->get();
        //2 ,4
        foreach ($checador_registros as $keyc => $valuec) {

          $dia = $this->getDia($valuec->fecha);
          if ($dia === 'Domingo') {
            $asistencias = $asistencias + 1;
            $r [] = $valuec->estado_asistencia_id ;

          }else {
            if ($valuec->estado_asistencia_id == '0' ) {
            }elseif ($valuec->estado_asistencia_id == '2' ) {
            }elseif ($valuec->estado_asistencia_id == '4' ) {
            }else {
              $asistencias = $asistencias + 1;
              $r [] = $valuec->estado_asistencia_id ;
            }
          }
          // if ($valuec->) {
          //   // code...
          // }
        }
        $sd = 0;
        $total = 0;

        if(isset($contrato) == true){
          $sueldo_real = (float) $contrato->sueldo_diario_real;
          $total = $asistencias * $sueldo_real;
          // $total = 0 * $sueldo_real;
          $sd = (float) $contrato->sueldo_diario_real;
        }
        $pro_final = [];
        // $pro = (array_values(array_unique($proyectos)));
        // $pro_final = 0;
        if ($proyectos != null) {
          $arr = array_count_values($proyectos);
          arsort($arr);

          foreach ($arr as $c => $v) {
            $pro_final [] = $c;
          }
        }


        // if ($pro != null) {
        // rsort($pro);
        // $pro_final = $pro[0];
        // }

        $arreglo [] = [
          'empleado' => $value,
          'c_tiempo_r' => $contro_tiempo_registros,
          'checador' => $checador_registros,
          'contrato' => $contrato,
          'banco' => $banco,
          'r' => $r,
          'asistencias' => $asistencias ,
          // 'proyectos' => array_key_first(array_count_values($proyectos)),
          'proyectos' => $pro_final == null ? 0 : $pro_final[0],
          // 'proyectos' => $pro_final,
          // 'proyectos' => $pro,
          'total' => $total,
          'sd' => $sd,
        ];
      }
      // return response()->json($arreglo);
      DB::beginTransaction();

      $nomina = new \App\Nomina();
      $nomina->fecha_inicial = $request->fecha_inicial;
      $nomina->fecha_final = $request->fecha_final;
      $nomina->periodo = $request->periodo;
      $nomina->tipo_nomina = 1;
      $nomina->empresa = $request->empresa;
      $nomina->save();

      foreach ($arreglo as $key_ => $value_) {

        $mov_nomi = new \App\MovimientosNomina();
        $mov_nomi->dias_trabajados = $value_['asistencias'];
        $mov_nomi->transferencias = 0;
        $mov_nomi->efectivos = 0;
        $mov_nomi->otros = 0;
        $mov_nomi->descuento = 0;
        $mov_nomi->totales = $value_['total'];
        $mov_nomi->empleado_id = $value_['empleado']->id;
        $mov_nomi->contrato_id = $value_['contrato'] != null ? $value_['contrato']->id : 0;
        $mov_nomi->nomina_id = $nomina->id;
        $mov_nomi->banco_id = $value_['banco'] != null ? $value_['banco']->id : 0;
        $mov_nomi->proyecto_id = $value_['proyectos'];
        $mov_nomi->sueldo_diario = $value_['sd'];
        $mov_nomi->save();

      }
      DB::commit();


      // return response()->json($arreglo);
      return response()->json(['status' => true]);
      //

    } catch (\Throwable $e){
      DB::rollBack();
      Utilidades::errors($e);
    }

    //
    // return response()->json(array(
    //   'status' => true
    // ));
  }
  //lunes a domingo semanal
  public function editarnominaquincenal(Request $request){
    if (!$request->ajax()) return redirect('/');

    $mov_nominas = DB::table('nominas_movimientos')->select('nominas_movimientos.*')
    ->where('nominas_movimientos.contrato_id','=', $request->contrato_id)
    ->where('nominas_movimientos.nomina_id','=', $request->nomina_id)
    ->where('nominas_movimientos.empleado_id','=',$request->empleado)->first();//p
    $a=floatval($request->sueldo_diario_integral);
    $b=floatval($request->diast);
    $c=floatval($request->infonavit);
    $d=floatval($request->sueldo_diario_neto);
    $e=floatval($request->otro);
    $mz = floatval($request->descuento);
    $transferencia=($a*$b)-$c;
    $total=(($d*$b)+$e)-$mz;
    $efectivo=($total-($a * $b));
    $mov_nomi = \App\MovimientosNomina::
    where('nomina_id','=', $request->nomina_id)
    ->where('contrato_id','=',$request->contrato_id)
    ->where('empleado_id','=',$request->empleado)->first();
  //  $mov_nomi = new \App\MovimientosNomina();
    $mov_nomi->dias_trabajados = $b;
    $mov_nomi->transferencias = $transferencia;
    $mov_nomi->efectivos = $efectivo;
    $mov_nomi->otros = $e;
    $mov_nomi->descuento = $mz;
    $mov_nomi->totales = $total;
    $mov_nomi->empleado_id = $request->empleado;
    $mov_nomi->contrato_id =  $request->contrato_id;
    $mov_nomi->nomina_id = $request->nomina_id;
    $mov_nomi->banco_id = $request->banco_id;
    // $mov_nomi->proyecto_id = $request->proyecto_id;
    $mov_nomi->save();

    $this->RestarDias($mov_nomi->id,$request);

    return response()->json(array('status' => true ));
  }

  public function RestarDias($id,$request)
  {
    $mov_nomi = \App\MovimientosNomina::where('id','=',$id)->first();
    $a=floatval($request->sueldo_diario_integral);
    $b=floatval($request->dias_restados);
    $c=floatval($request->infonavit);
    $d=floatval($request->sueldo_diario_neto);
    $e=floatval($request->otro);
    $mz = floatval($request->descuento);
    $transferencia=($a*$b)-$c;
    $total=(($d*$b)+$e)-$mz;
    $efectivo=($total-($a * $b));

    $mov_nomina = new \App\MovimientosNomina();
    $mov_nomina->dias_trabajados = $b;
    $mov_nomina->transferencias = $transferencia;
    $mov_nomina->efectivos = $efectivo;
    $mov_nomina->otros = $e;
    $mov_nomina->descuento = $mz;
    $mov_nomina->totales = $total;
    $mov_nomina->empleado_id = $mov_nomi->empleado_id;
    $mov_nomina->contrato_id = $mov_nomi->contrato_id;
    $mov_nomina->nomina_id = $mov_nomi->nomina_id;
    $mov_nomina->banco_id = $mov_nomi->banco_id;
    $mov_nomina->proyecto_id = $request->proyecto_id;
    $mov_nomina->save();

    return response()->json(array('status' => true ));
  }

  public function edit($id)
  {

  }

  public function show($id)
  {
    $valores = explode('&',$id);
    $tiposContrato = DB::table('nominas_movimientos')
    ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
    ->leftJoin('sueldos AS S','S.contrato_id','=','nominas_movimientos.contrato_id')
    ->select('nominas_movimientos.*','S.sueldo_diario_integral','S.sueldo_diario_neto','S.infonavit')
    ->where('nominas_movimientos.id','=',$valores[1])
    ->get();

    return response()->json($tiposContrato);
  }
  public function update(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    return response()->json(array(
      'status' => true
    ));
  }

  public function getQuincenas()
  {
    $resultado = DB::select("SELECT semana FROM  sueldo_quincena_empleado_registros GROUP BY semana");

    return response()->json($resultado);
    // code...
  }

  public function getQuincenasEmpleado($id)
  {

    $semana = DB::select("SELECT semana,sueldo_quincena_empleado_id,e.nombre as nombre FROM sueldo_quincena_empleado_registros as sr
      JOIN sueldo_quincena_empleado as e ON e.id = sr.sueldo_quincena_empleado_id
      WHERE sr.semana = '$id' AND sr.total != 0
      GROUP BY sr.sueldo_quincena_empleado_id");

    $arreglo = [];
    foreach ($semana as $key => $value) {
      $totales = DB::table('sueldo_quincena_empleado_registros')
      ->select(DB::raw("SUM(total) AS total"))
      ->where('sueldo_quincena_empleado_id',$value->sueldo_quincena_empleado_id)
      ->where('semana',$id)->first();

      $totales_resta = DB::table('sueldo_quincena_empleado_registros')
      ->select("total")
      ->where('sueldo_quincena_empleado_id',$value->sueldo_quincena_empleado_id)
      ->where('semana',$id)->get();

      $valo = 0;
      if (count($totales_resta) == 14) {
        $valo = 1;
      }elseif (count($totales_resta) == 15) {
        $valo = 2;
      }elseif (count($totales_resta) == 16) {
        $valo = 3;
      }

      $arreglo [] = [
        'val' => $value,
        't' => $totales,
        't_r' => $totales_resta[0],
        't_c' => $valo,
      ];
    }

    return response()->json($arreglo);
  }

  public function empleado_csct($value)
  {
    try {

      $empleado_cfw = \App\Empleado::where('id',$value)->first();
      $empleado_csct = \App\EmpleadoDBP::where('nombre',$empleado_cfw->nombre)
      ->where('ap_paterno',$empleado_cfw->ap_paterno)
      ->where('ap_materno',$empleado_cfw->ap_materno)->first();

      return $empleado_csct->id;

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function getDia($fecha)
  {
    $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');

    $dia = $dias[(date('N', strtotime($fecha))) - 1];

    return $dia;
  }

  public function saveSD(Request $request)
  {
    try {
      DB::beginTransaction();
      $nm = \App\MovimientosNomina::where('id',$request->id)->first();
      $nm->sueldo_diario = $request->total;
      $nm->save();

      $n = \App\Nomina::where('id',$nm->nomina_id)->first();

      if ($nm->contrato_id == 0) {
        $c = $this->crearContratoSueldo($nm, $n);

        if ($n->empresa == 4) {//CSCT

          $sueldo = \App\SueldoDBP::where('contrato_id',$c->id)->first();

          $sueldo_nuevo = new \App\SueldoDBP();
          $sueldo_nuevo->sueldo_diario_integral = 0;
          $sueldo_nuevo->sueldo_mensual = round(($request->total * 7) * 4);
          $sueldo_nuevo->infonavit = 0;
          $sueldo_nuevo->viaticos_mensuales = 0;
          $sueldo_nuevo->sueldo_diario_neto = 0;
          $sueldo_nuevo->contrato_id = $c->id;
          $sueldo_nuevo->sueldo_diario_real = $request->total;
          $sueldo_nuevo->fecha_act = $n->fecha_inicial;
          $sueldo_nuevo->save();
        }elseif ($n->empresa == 2) {//CONSERFLOW
          $sueldo = \App\Sueldo::where('contrato_id',$c->id)->first();

          $sueldo_nuevo = new \App\Sueldo();
          $sueldo_nuevo->sueldo_diario_integral = 0;
          $sueldo_nuevo->sueldo_mensual = round(($request->total * 7) * 4);
          $sueldo_nuevo->infonavit = 0;
          $sueldo_nuevo->viaticos_mensuales = 0;
          $sueldo_nuevo->sueldo_diario_neto = 0;
          $sueldo_nuevo->contrato_id = $c->id;
          $sueldo_nuevo->sueldo_diario_real = $request->total;
          $sueldo_nuevo->fecha_act = $n->fecha_inicial;
          $sueldo_nuevo->save();
        }

        $nm->contrato_id = $c->id;
        $nm->save();
      }else {

        if ($n->empresa == 4) {//CSCT

          $sueldo = \App\SueldoDBP::where('contrato_id',$nm->contrato_id)->first();

          $sueldo_nuevo = new \App\SueldoDBP();
          $sueldo_nuevo->sueldo_diario_integral = 0;
          $sueldo_nuevo->sueldo_mensual = round(($request->total * 15) * 2);
          $sueldo_nuevo->infonavit = 0;
          $sueldo_nuevo->viaticos_mensuales = 0;
          $sueldo_nuevo->sueldo_diario_neto = 0;
          $sueldo_nuevo->contrato_id = $nm->contrato_id;
          $sueldo_nuevo->sueldo_diario_real = $request->total;
          $sueldo_nuevo->fecha_act = $n->fecha_inicial;
          $sueldo_nuevo->save();
        }elseif ($n->empresa == 2) {//CONSERFLOW
          $sueldo = \App\Sueldo::where('contrato_id',$nm->contrato_id)->first();

          $sueldo_nuevo = new \App\Sueldo();
          $sueldo_nuevo->sueldo_diario_integral = 0;
          $sueldo_nuevo->sueldo_mensual = round(($request->total * 15) * 2);
          $sueldo_nuevo->infonavit = 0;
          $sueldo_nuevo->viaticos_mensuales = 0;
          $sueldo_nuevo->sueldo_diario_neto = 0;
          $sueldo_nuevo->contrato_id = $nm->contrato_id;
          $sueldo_nuevo->sueldo_diario_real = $request->total;
          $sueldo_nuevo->fecha_act = $n->fecha_inicial;
          $sueldo_nuevo->save();
        }

      }
      DB::commit();
      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      DB::rollBack();
      Utilidades::errors($e);
    }
  }

  public function saveDT(Request $request)
  {
    try {
      $nm = \App\MovimientosNomina::where('id',$request->id)->first();
      $nm->dias_trabajados = $request->total;
      $nm->save();

      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function saveD(Request $request)
  {
    try {
      $nm = \App\MovimientosNomina::where('id',$request->id)->first();
      $nm->descuento = $request->total;
      $nm->save();

      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function saveI(Request $request)
  {
    try {
      $nm = \App\MovimientosNomina::where('id',$request->id)->first();
      $nm->infonavit = $request->total;
      $nm->save();

      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function saveVA(Request $request)
  {
    try {
      $nm = \App\MovimientosNomina::where('id',$request->id)->first();
      $nm->viaticos_alimentos = $request->total;
      $nm->save();

      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function saveT(Request $request)
  {
    try {
      $nm = \App\MovimientosNomina::where('id',$request->id)->first();
      $nm->totales = $request->total;
      $nm->save();

      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function saveB(Request $request)
  {
    try {
      $nm = \App\MovimientosNomina::where('id',$request->id)->first();
      $nm->banco_id = $request->banco;
      $nm->save();

      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function saveNB(Request $request)
  {
    try {
      $nm = \App\MovimientosNomina::
      join('nomina AS n','n.id','nominas_movimientos.nomina_id')
      ->select('nominas_movimientos.*','n.empresa')
      ->where('nominas_movimientos.id',$request->id)->first();

      // $nm->banco_id = $request->banco;
      // $nm->save();

      if ($nm->empresa == 4) {//CSCT
        $empleado_csct = $this->empleado_csct($nm->empleado_id);

        $dbe = new \App\DatosBancariosEmpleado();
        $dbe->numero_tarjeta= $request->tarjeta;
        $dbe->numero_cuenta= $request->numero;
        $dbe->clabe= $request->clabe;
        $dbe->banco_id= $request->banco;
        $dbe->empleado_id = $empleado_csct;
        $dbe->save();

        $nm->banco_id = $dbe->id;
        $nm->save();
      }elseif ($nm->empresa == 2) {//CONSERFLOW

        $dbe = new \App\DatosBancariosEmpleado();
        $dbe->numero_tarjeta= $request->tarjeta;
        $dbe->numero_cuenta= $request->numero;
        $dbe->clabe= $request->clabe;
        $dbe->banco_id= $request->banco;
        $dbe->empleado_id = $nm->empleado_id;
        $dbe->save();

        $nm->banco_id = $dbe->id;
        $nm->save();
      }

      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function saveANB(Request $request)
  {
    try {
      $nm = \App\MovimientosNomina::
      join('nomina AS n','n.id','nominas_movimientos.nomina_id')
      ->select('nominas_movimientos.*','n.empresa')
      ->where('nominas_movimientos.id',$request->id)->first();

      // $nm->banco_id = $request->banco;
      // $nm->save();

      if ($nm->empresa == 4) {//CSCT
        $empleado_csct = $this->empleado_csct($nm->empleado_id);

        $dbe = new \App\DatosBancariosEmpleado();
        $dbe->numero_tarjeta= $request->tarjeta;
        $dbe->numero_cuenta= $request->numero;
        $dbe->clabe= $request->clabe;
        $dbe->banco_id= $request->banco;
        $dbe->empleado_id = $empleado_csct;
        $dbe->save();

        $nm->banco_id = $dbe->id;
        $nm->save();
      }elseif ($nm->empresa == 2) {//CONSERFLOW

        $dbe = new \App\DatosBancariosEmpleado();
        $dbe->numero_tarjeta= $request->tarjeta;
        $dbe->numero_cuenta= $request->numero;
        $dbe->clabe= $request->clabe;
        $dbe->banco_id= $request->banco;
        $dbe->empleado_id = $nm->empleado_id;
        $dbe->save();

        $nm->banco_id = $dbe->id;
        $nm->save();
      }

      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function crearContratoSueldo($nm, $n)
  {
    try {
      // 4 CSCT y 2 CONSERFLOW
      if ($n->empresa == 4) {
        DB::beginTransaction();

        $empleado_id_csct = $this->empleado_csct($nm->empleado_id);

        $antiguedad = new \App\AntiguedadDBP();
        $antiguedad->fecha_inicial = $n->fecha_inicial;
        // $antiguedad->fecha_final =;
        $antiguedad->empleado_id = $empleado_id_csct;
        $antiguedad->condicion = 1;
        Utilidades::auditar($antiguedad, $antiguedad->id);
        $antiguedad->save();

        $contrato = new \App\ContratoDBP();
        $contrato->tipo_ubicacion_id = 1;
        $contrato->fecha_ingreso = $n->fecha_inicial;
        // $contrato->fecha_fin
        $contrato->tipo_nomina_id = 2;
        $contrato->empleado_id = $empleado_id_csct;
        $contrato->horario_id = 1;
        $contrato->tipo_contrato_id = 2;
        $contrato->proyecto_id = 0;
        $contrato->antiguedad_id = $antiguedad->id;
        $contrato->condicion = 0;
        Utilidades::auditar($contrato, $contrato->id);
        $contrato->save();
        DB::commit();

      }elseif ($n->empresa == 2) {
        DB::beginTransaction();
        $antiguedad = new \App\Antiguedad();
        $antiguedad->fecha_inicial = $n->fecha_inicial;
        // $antiguedad->fecha_final =;
        $antiguedad->empleado_id = $nm->empleado_id;
        $antiguedad->condicion = 1;
        Utilidades::auditar($antiguedad, $antiguedad->id);
        $antiguedad->save();

        $contrato = new \App\Contrato();
        $contrato->tipo_ubicacion_id = 1;
        $contrato->fecha_ingreso = $n->fecha_inicial;
        // $contrato->fecha_fin
        $contrato->tipo_nomina_id = 1;
        $contrato->empleado_id = $nm->empleado_id;
        $contrato->horario_id = 1;
        $contrato->tipo_contrato_id = 2;
        $contrato->proyecto_id = 0;
        $contrato->antiguedad_id = $antiguedad->id;
        $contrato->condicion = 0;
        Utilidades::auditar($contrato, $contrato->id);
        $contrato->save();
        DB::commit();
      }

      return $contrato;
    } catch (\Throwable $e) {
      DB::rollBack();
      Utilidades::errors($e);
    }
  }

}
