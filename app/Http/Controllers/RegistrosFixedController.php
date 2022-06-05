<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use DateInterval;
use DatePeriod;
use DateTime;
use App\Exports\EstatusMaterialExport;
use Maatwebsite\Excel\Facades\Excel;




class RegistrosFixedController extends Controller
{



  public function Actualizar(Request $request)
  {
    $fechas = $this->getInicioFinSemana($request->semana,$request->anio);
    $fechauno=($fechas['inicio']);
    $fechados=($fechas['fin']);

    // $this->eliminarReportesEncabezados($fechauno,$fechados);
    // return response()->json($fechas);

    $this->generalpro($fechauno.'&'.$fechados);
    // $this->admin($fechauno.'&'.$fechados);
    // $this->sinpro($fechauno.'&'.$fechados);
    // $this->gastos($fechauno.'&'.$fechados);
    // $this->gastosAdministracion($fechauno.'&'.$fechados);
    // $this->gastosFinanzas($fechauno.'&'.$fechados);
    // $this->gastosSeguridad($fechauno.'&'.$fechados);
    // $this->gastosVehiculos($fechauno.'&'.$fechados);
    // $this->gastosVentas($fechauno.'&'.$fechados);


    return response()->json(['status' => true]);
  }

  public function eliminarReportesEncabezados($fecha_uno,$fecha_dos)
  {
    try {
      $reporte_registros = \App\ReportesRegistros::where('rango',$fecha_uno.' - '.$fecha_dos)->delete();
      return true;
    } catch (\Throwable $e) {
        Utilidades::errors($e);
    }

  }

  public function getInicioFinSemana($week, $year)
  {
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $ret['inicio'] = $dto->format('Y-m-d');
    $dto->modify('+6 days');
    $ret['fin'] = $dto->format('Y-m-d');
    return $ret;
  }

  public function ObtenerFechas($id)
  {
    $valores= explode('&',$id);

    $fechauno = $valores[0];
    $fechados = $valores[1];

    $fechas = [];
    $fechas_siete = [];

    $fechaInicio=strtotime($fechauno);
    $fechaFin=strtotime($fechados);

    for($i=$fechaInicio; $i<=$fechaFin; $i+=604800){
      $fechas_siete [] = date("Y-m-d", $i);
    }
    for($i=($fechaInicio +518400); $i<=($fechaFin+604800); $i+=604800){
      $fechas_n [] = date("Y-m-d", $i);
    }

    $a = [];

    $tam = count($fechas_siete);

    for ($h=0; $h < $tam ; $h++) {
      $arreglo =[];
      $arreglo_fechas_mostrar = [];
      $arreglo_fechas_buscar = [];

      $fechaInicio=strtotime($fechauno);
      $fechaFin=strtotime($fechados);

        for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
        $arreglo_fechas [] = date("d-M-yy",$i);
        $arreglo_fechas_buscar [] = date('Y-m-d',$i);
      }

      $dia   = substr($fechas_siete[$h],8,2);
      $mes = substr($fechas_siete[$h],5,2);
      $anio = substr($fechas_siete[$h],0,4);
      $semana = date('W',  mktime(0,0,0,$mes,$dia,$anio));

      $a [] = [
        'ns' => $semana,
        'i' => $fechas_siete[$h],
        'f' => $fechas_n[$h],
      ];
    }

    return $a;
  }

  public function ObtenerFechasdias($id)
  {
    $valores= explode('&',$id);

    $fechauno = '2020-08-10';
    $fechados = '2021-03-06';

    $fechas = [];

    $fechaInicio=strtotime($fechauno);
    $fechaFin=strtotime($fechados);
    for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
      $fechas [] = date("Y-m-d", $i);
    }

    return $fechas;
  }

    public function rango($uno, $dos)
    {
      $meses = [' Q ENERO',' Q FEBRERO',' Q MARZO',' Q ABRIL',' Q MAYO',
      ' Q JUNIO',' Q JULIO',' Q AGOSTO',' Q SEP.',' Q OCT.',' Q NOV.',' Q DIC.'];
      $numero = ['1 Q ','2 Q '];
      $meses_arreglo = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEP.','OCT.','NOV.','DIC.'];

      $numero_uno = str_replace($meses,"",$uno);
      $numero_dos = str_replace($meses,"",$dos);
      $mes_uno = str_replace($numero,"",$uno);
      $mes_dos = str_replace($numero,"",$dos);

      $meses_busqueda = array_search($mes_dos,$meses_arreglo);
      $meses_busqueda_inicio = array_search($mes_uno,$meses_arreglo);

      $arreglo_rango = [];
      if ($numero_uno == 1) {
        $arreglo_rango [] = '1 Q '.$meses_arreglo[$meses_busqueda_inicio];
        $arreglo_rango [] = '2 Q '.$meses_arreglo[$meses_busqueda_inicio];
      }elseif ($numero_uno == 2) {
        $arreglo_rango [] = '2 Q '.$meses_arreglo[$meses_busqueda_inicio];
      }
      for ($i=$meses_busqueda_inicio + 1; $i < $meses_busqueda; $i++) {
        $arreglo_rango [] = '1 Q '.$meses_arreglo[$i];
        $arreglo_rango [] = '2 Q '.$meses_arreglo[$i];
      }
      if ($numero_dos == 1) {
        $arreglo_rango [] = '1 Q '.$meses_arreglo[$meses_busqueda];
      }elseif ($numero_dos == 2) {
        $arreglo_rango [] = '1 Q '.$meses_arreglo[$meses_busqueda];
        $arreglo_rango [] = '2 Q '.$meses_arreglo[$meses_busqueda];
      }

      return $arreglo_rango;
    }

    public function getInicioFinSemanad($data)
    {
      $meses = [' Q ENERO',' Q FEBRERO',' Q MARZO',' Q ABRIL',' Q MAYO',
      ' Q JUNIO',' Q JULIO',' Q AGOSTO',' Q SEP.',' Q OCT.',' Q NOV.',' Q DIC.'];
      $numero = ['1 Q ','2 Q '];
      $meses_arreglo = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEP.','OCT.','NOV.','DIC.'];
      $meses_arreglo_numero = ['01','02','03','04','05','06','07','08','09','10','11','12'];

      $numero_uno = str_replace($meses,"",$data);
      $mes_uno = str_replace($numero,"",$data);

      $meses_busqueda = array_search($mes_uno,$meses_arreglo);
      $ret = null;

      if($mes_uno === 'ENERO' && $numero_uno == 1){
        $ret ['inicio'] = (date('Y') - 2).'-12-26';
      }
      if($mes_uno != 'ENERO' && $numero_uno == 1) {
        $ret['inicio'] = (date('Y') - 1).'-'.$meses_arreglo_numero[$meses_busqueda - 1].'-26';
      }
      if ($numero_uno == 2) {
        $ret['inicio'] = (date('Y') - 1).'-'.$meses_arreglo_numero[$meses_busqueda].'-11';
      }

      if ($numero_uno == 1) {
        $ret['fin'] = (date('Y') - 1).'-'.$meses_arreglo_numero[$meses_busqueda].'-10';
      }
      if ($numero_uno == 2) {
        $ret['fin'] = (date('Y') - 1).'-'.$meses_arreglo_numero[$meses_busqueda].'-25';
      }
      return $ret;
    }

  public function generalpro($id)
  {
    // NOTE: Para el calculo de los tiempos
    // 1.- PRIMERO SE HACE EL CALCULO POR SEMANA Y AÑO  GP
    // 2.- SE CALCULA EL ADMINISTRATIVO
    // 3.- SE BORRAN LOS REGISTROS DEL SUELDOS GP
    // 4.- SE VUELVE A CALCULAR EL SUELDO GP


    try {
      $valores= explode('&',$id);

      $fechauno = $valores[0];
      $fechados = $valores[1];

      $a = $this->ObtenerFechas($id);

      //PROYECTOS ACTIVOS EN EL TIEMPO TRANSCURRIDO
      $proyectos = DB::table('control_tiempo')
      ->leftJoin('proyectos AS p','p.id','=','control_tiempo.proyecto_id')
      ->whereBetween('fecha',[$fechauno, $fechados])
      ->select('control_tiempo.proyecto_id','p.nombre_corto',DB::raw("COUNT(control_tiempo.id) AS c"))
      ->whereNotIn('control_tiempo.proyecto_id',[1,0,61,100,110,113])
      // ->where('control_tiempo.nave','=',null)
      ->groupBy('control_tiempo.proyecto_id')
      ->get();



      // return response()->json($a);



      $arreglos_final = [];
      $arreglo_proyectos = [];

      foreach ($proyectos as $key => $proyecto) {
        $apariciones = [];
        $tiempo = [];
        $total_s = 0;
        $prue_d = [];
        foreach ($a as $key => $value) {

          $empleados_tiempo = DB::table('control_tiempo')
          ->whereBetween('fecha',[$value['i'], $value['f']])
          ->where('proyecto_id',$proyecto->proyecto_id)
          ->where('nave','=',null)
          ->select('empleado_asignado_id','fecha')
          ->get();


          // $SueldoSemanaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::
          // whereBetween('fecha_i',[$value['i'], $value['f']])->update(['marca' => 0]);
          //
          // $SueldoQuincenaEmpleadoRegistros = \App\SueldoQuincenaEmpleadoRegistros::
          // whereBetween('fecha_i',[$value['i'], $value['f']])->update(['marca' => 0]);


          $f = [];
          $prue = [];
          foreach ($empleados_tiempo as $key => $vs) {
            $total_s = 0;
            $total_q = 0;

            $dia = $this->getDia($vs->fecha);

            if ($dia === 'Domingo') {
              $fecha = date("Y-m-d",strtotime($vs->fecha."- 1 days"));
              $SueldoSemanaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::
              join('sueldo_semana_empleado AS sse','sse.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
              ->where('sueldo_semana_empleado_registros.fecha_i',$fecha)
              ->where('sse.empleado_id',$vs->empleado_asignado_id)
              ->where('sse.aplica','1')
              // ->where('sueldo_semana_empleado_registros.marca','0')
              // ->where('sse.empresa','CONSERFLOW')
              ->select("total")
              ->first();
            //
              $SueldoQuincenaEmpleadoRegistros = \App\SueldoQuincenaEmpleadoRegistros::
              join('sueldo_quincena_empleado AS sse','sse.id','=','sueldo_quincena_empleado_registros.sueldo_quincena_empleado_id')
              ->where('sueldo_quincena_empleado_registros.fecha_i',$fecha)
              ->where('sse.empleado_id',$vs->empleado_asignado_id)
              ->where('sse.aplica','1')
              // ->where('sueldo_quincena_empleado_registros.marca','0')
              ->select("total")
              // ->where('sse.empresa','conserflow')
              ->first();
            //
              if (isset($SueldoSemanaEmpleadoRegistros) == true) {
                $total_s += $SueldoSemanaEmpleadoRegistros->total;
                // DB::table('sueldo_semana_empleado_registros')
                // ->join('sueldo_semana_empleado','sueldo_semana_empleado.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
                // ->where('fecha_i',$fecha)
                // ->where('empleado_id',$vs->empleado_asignado_id)->update(['marca' => 2]);
              }
              if (isset($SueldoQuincenaEmpleadoRegistros) == true) {
                $total_q += $SueldoQuincenaEmpleadoRegistros->total;
                // DB::table('sueldo_quincena_empleado_registros')
                // ->join('sueldo_quincena_empleado','sueldo_quincena_empleado.id','=','sueldo_quincena_empleado_registros.sueldo_quincena_empleado_id')
                // ->where('fecha_i',$fecha)
                // ->where('empleado_id',$vs->empleado_asignado_id)->update(['marca' => 2]);
              }
            //
            }
            else {
              $SueldoSemanaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::
              join('sueldo_semana_empleado AS sse','sse.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
              ->where('sueldo_semana_empleado_registros.fecha_i',$vs->fecha)
              // ->where('sueldo_semana_empleado_registros.fecha_i','>','2021-08-10')
              ->where('sse.empleado_id',$vs->empleado_asignado_id)
              ->where('sse.aplica','1')
              // ->where('sueldo_semana_empleado_registros.marca','0')
              // ->where('sse.empresa','CONSERFLOW')
              ->select("total")
              ->first();

              $SueldoQuincenaEmpleadoRegistros = \App\SueldoQuincenaEmpleadoRegistros::
              join('sueldo_quincena_empleado AS sse','sse.id','=','sueldo_quincena_empleado_registros.sueldo_quincena_empleado_id')
              ->where('sueldo_quincena_empleado_registros.fecha_i',$vs->fecha)
              ->where('sse.empleado_id',$vs->empleado_asignado_id)
              // ->where('sueldo_quincena_empleado_registros.marca','0')
              ->where('sse.aplica','1')
              // ->where('sse.empresa','conserflow')
              ->select("total")
              ->first();

              if (isset($SueldoSemanaEmpleadoRegistros) == true) {
                $total_s += $SueldoSemanaEmpleadoRegistros->total;

                DB::table('sueldo_semana_empleado_registros')
                ->join('sueldo_semana_empleado','sueldo_semana_empleado.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
                ->where('fecha_i',$vs->fecha)
                ->where('empleado_id',$vs->empleado_asignado_id)->update(['marca' => 2]);
              }

              if (isset($SueldoQuincenaEmpleadoRegistros) == true) {
                $total_q += $SueldoQuincenaEmpleadoRegistros->total;
                DB::table('sueldo_quincena_empleado_registros')
                ->join('sueldo_quincena_empleado','sueldo_quincena_empleado.id','=','sueldo_quincena_empleado_registros.sueldo_quincena_empleado_id')
                ->where('fecha_i',$vs->fecha)
                ->where('empleado_id',$vs->empleado_asignado_id)->update(['marca' => 2]);
              }
            }


            $f [] = $total_s + $total_q;
            // $prue [] = [
            //   'a' => $SueldoQuincenaEmpleadoRegistros,
            //   'b' => $vs,
            //   'c' => $SueldoSemanaEmpleadoRegistros,
            // ];
          }

          // $prue_d []= $prue;
          $apariciones []=array_sum($f);
          $tiempo [] = $value;
        }
        // return response()->json($prue_d);

        $arreglos_final [] = [
          'proyectos' => $proyecto,
          'apa' => $apariciones,
          't' => $tiempo,
        ];

        // return response()->json($arreglos_final);


        foreach ($arreglos_final as $key => $value) {
          $enca = \App\ReportesEncabezados::where('tipo','1')->where('proyecto',$value['proyectos']->nombre_corto)->first();
          if (isset($enca) == false) {
            $enca = new \App\ReportesEncabezados();
            $enca->tipo = 1;
            $enca->proyecto = $value['proyectos']->nombre_corto;
            $enca->proyecto_id = $value['proyectos']->proyecto_id;
            $enca->save();
          }

          foreach ($value['apa'] as $k => $val) {
            $re_b = \App\ReportesRegistros::where('reportes_encabezados_id',$enca->id)->where('semana','Semana '.$value['t'][$k]['ns'])
            ->where('rango',$value['t'][$k]['i'].' - '.$value['t'][$k]['f'])->first();
            if (isset($re_b) == true) {
              $re_u = \App\ReportesRegistros::where('reportes_encabezados_id',$enca->id)->where('semana','Semana '.$value['t'][$k]['ns'])
              ->where('rango',$value['t'][$k]['i'].' - '.$value['t'][$k]['f'])->update(['total' => $val]);
            }else {
              $re = new \App\ReportesRegistros();
              $re->reportes_encabezados_id = $enca->id;
              $re->total = $val;
              $re->semana = 'Semana '.$value['t'][$k]['ns'];
              $re->rango = $value['t'][$k]['i'].' - '.$value['t'][$k]['f'];
              $re->save();
            }
          }
        }

      }
              // return response()->json($arreglos_final);
      return response()->json(array('status' => true));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }
///CONSERFLOW 11
///CSCT 10
//conserflow 12
//CSCT 13
//CONSERFLOW 14
//CSCT 15

    public function getDia($fecha)
    {
      $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');

      $dia = $dias[(date('N', strtotime($fecha))) - 1];

      return $dia;
    }

  public function admin($id)
  {
    try {
      $valores= explode('&',$id);

      $fechauno = $valores[0];
      $fechados = $valores[1];

      $a = $this->ObtenerFechas($id);
      // return response()->json($a);

      $b = [];
      foreach ($a as $key => $value) {

        $SueldoQuincenaEmpleadoRegistros = \App\SueldoQuincenaEmpleadoRegistros::
        join('sueldo_quincena_empleado AS sse','sse.id','=','sueldo_quincena_empleado_registros.sueldo_quincena_empleado_id')
        ->whereBetween('sueldo_quincena_empleado_registros.fecha_i',[$value['i'], $value['f']])
        ->where('sueldo_quincena_empleado_registros.marca','0')
        ->where('sse.aplica','1')
        // ->where('sse.empresa','csct')
        ->select(DB::raw("SUM(total) AS total"))
        ->first();

        $sueldosEncabezados = DB::table('reportes_registros AS rr')
        ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
        ->leftJoin('proyectos AS p','p.id','re.proyecto_id')
        ->leftJoin('proyecto_subcategorias AS ps','ps.id','p.proyecto_subcategorias_id')
        ->where('rr.rango',$value['i'].' - '.$value['f'])
        ->where('rr.total' ,'!=','0')
        ->where('re.tipo','1')
        // ->whereNotIn('ps.proyecto_categoria_id',['1','2'])
        ->whereNotIn('re.proyecto_id',[0,61,100,110,113])
        ->select(DB::raw("SUM(total) AS total"))
        ->first();

        $proyectos = DB::table('reportes_registros AS rr')
        ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
        ->leftJoin('proyectos AS p','p.id','re.proyecto_id')
        ->leftJoin('proyecto_subcategorias AS ps','ps.id','p.proyecto_subcategorias_id')
        ->where('rr.rango',$value['i'].' - '.$value['f'])
        // ->where('rr.semana','Semana '.$value['ns'])
        ->where('rr.total' ,'!=','0')
        ->where('re.tipo','1')
        // ->whereIn('ps.proyecto_categoria_id',['1','2'])
        ->whereNotIn('re.proyecto_id',[0,61,100,110,113])
        ->select('rr.*','re.proyecto')
        ->get();

        $total_proyectos = 0;
        foreach ($proyectos as $pk => $pv) {
          $total_proyectos += $pv->total;
        }

        $promedios = [];
        foreach ($proyectos as $pkk => $pvv) {
          $promedios [] = [
            'proyecto' => $pvv->proyecto,
            'promedio' => ($pvv->total / $total_proyectos) * 100,
          ];
        }
        $promedios_movimiento = [];

        foreach ($promedios as $pkey => $pvalue) {
          if ($SueldoQuincenaEmpleadoRegistros->total != '') {
            $promedios_movimiento [] = [
              'proyecto' => $pvalue['proyecto'],
              // 'total' => ($pvalue['promedio'] * ((float)$SueldoQuincenaEmpleadoRegistros->total + $sueldosEncabezados->total))/100
              'total' => ( ((float)$SueldoQuincenaEmpleadoRegistros->total /100 ) * $pvalue['promedio'] ),
              ] ;
            }
          }

          $b [] = [
            'a' => $value,
            'total_promedios' => $promedios_movimiento,
          ];
        }
        // return response()->json($b);

        $re = DB::table('reportes_encabezados')->select('proyecto')->where('tipo','1')->get();
        foreach ($re as $key => $value) {
          $enca = \App\ReportesEncabezados::where('tipo','2')->where('proyecto',$value->proyecto)->first();
          if (isset($enca) == false) {
            $enca = new \App\ReportesEncabezados();
            $enca->tipo = 2;
            $enca->proyecto =$value->proyecto;
            $enca->save();
          }
        }
        $f = [];
        foreach ($b as $bkey => $bvalue) {

          if (count($bvalue['total_promedios']) != 0) {
            $f [] = $bvalue;
          }
          if (count($bvalue['total_promedios']) != 0) {
            foreach ($bvalue['total_promedios'] as $keyss => $valuess) {
              $re_b = \App\ReportesEncabezados::where('tipo','2')->where('proyecto',$valuess['proyecto'])->first();

              $rr_b = \App\ReportesRegistros::where('reportes_encabezados_id',$re_b->id)
              ->where('semana','Semana '.$bvalue['a']['ns'])
              ->first();

              if (isset($rr_b) == true) {
                $re_u = \App\ReportesRegistros::where('reportes_encabezados_id',$re_b->id)
                ->where('semana','Semana '.$bvalue['a']['ns'])->update(['total' => round($valuess['total'],2)]);
              }else {
                $re = new \App\ReportesRegistros();
                $re->reportes_encabezados_id = $re_b->id;
                $re->total = round($valuess['total'],2);
                $re->semana = 'Semana '.$bvalue['a']['ns'];
                $re->rango = $bvalue['a']['i'].' - '.$bvalue['a']['f'];
                $re->save();
              }
            }
          }
        }

        return response()->json(['status' => true]);
      }catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }


    function cargotopo($id)
    {
      try {
        $valores= explode('&',$id);

        $fechauno = $valores[0];
        $fechados = $valores[1];

        $a = $this->ObtenerFechasdias($id);
        foreach ($a as $key => $value) {
          $SueldoSemanaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::
          join('sueldo_semana_empleado AS sse','sse.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
          ->where('sueldo_semana_empleado_registros.fecha_i',$value)
          // ->where('sse.empleado_id',$vs->empleado_asignado_id)
          ->where('sueldo_semana_empleado_registros.marca','0')
          ->select("empleado_id")
          ->get();

          DB::beginTransaction();
          foreach ($SueldoSemanaEmpleadoRegistros as $k => $v) {
            $new = new \App\Controltiempo();
            $new->fecha = $value;
            $new->proyecto_id = 105;
            $new->empleado_asignado_id = $v->empleado_id;
            $new->supervisor_id = 119;
            $new->save();
          }
          DB::commit();
        }

        return response()->json($a);

      } catch (\Throwable $e) {
        DB::rollBack();
          Utilidades::errors($e);
      }
    }

    public function sinpro($id)
    {
      try{
        $valores= explode('&',$id);

        $fechauno = $valores[0];
        $fechados = $valores[1];

        $a = $this->ObtenerFechas($id);

        $b = [];
        foreach ($a as $key => $value) {

          $SueldoQuincenaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::
          join('sueldo_semana_empleado AS sse','sse.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
          ->whereBetween('sueldo_semana_empleado_registros.fecha_i',[$value['i'], $value['f']])
          ->where('sueldo_semana_empleado_registros.marca','0')
          // ->where('sse.aplica','1')
          // ->where('sse.empresa','CSCT')
          ->select(DB::raw("SUM(total) AS total"))
          ->first();
          // return response()->json($SueldoQuincenaEmpleadoRegistros);

          $sueldosEncabezados = DB::table('reportes_registros AS rr')
          ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
          ->leftJoin('proyectos AS p','p.id','re.proyecto_id')
          ->leftJoin('proyecto_subcategorias AS ps','ps.id','p.proyecto_subcategorias_id')
          // ->where('rr.semana',' Semana '.$value['ns'])
          ->where('rr.rango',$value['i'].' - '.$value['f'])

          ->where('rr.total' ,'!=','0')
          ->where('re.tipo','1')
          // ->whereNotIn('ps.proyecto_categoria_id',['1','2'])
          ->whereNotIn('re.proyecto_id',[0,61,100,110,113])
          ->select(DB::raw("SUM(total) AS total"))
          ->first();

          // return response()->json($sueldosEncabezados);

          $proyectos = DB::table('reportes_registros AS rr')
          ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
          ->leftJoin('proyectos AS p','p.id','re.proyecto_id')
          ->leftJoin('proyecto_subcategorias AS ps','ps.id','p.proyecto_subcategorias_id')
          ->where('rr.rango',$value['i'].' - '.$value['f'])
          // ->where('rr.semana','Semana '.$value['ns'])
          ->where('rr.total' ,'!=','0')
          ->where('re.tipo','1')
          ->whereNotIn('re.proyecto_id',[0,61,100,110,113])
          // ->whereIn('ps.proyecto_categoria_id',['1','2'])
          ->select('rr.*','re.proyecto')
          ->get();

          // return response()->json($proyectos);

          $total_proyectos = 0;
          foreach ($proyectos as $pk => $pv) {
            $total_proyectos += $pv->total;
          }
          // return response()->json($total_proyectos);

          $promedios = [];
          foreach ($proyectos as $pkk => $pvv) {
            $promedios [] = [
              'proyecto' => $pvv->proyecto,
              'promedio' => ($pvv->total / $total_proyectos) * 100,
            ];
          }
          // return response()->json($promedios);

          $promedios_movimiento = [];

          $promedios_movimiento = [];

          foreach ($promedios as $pkey => $pvalue) {
            if ($SueldoQuincenaEmpleadoRegistros->total != '') {
              $promedios_movimiento [] = [
                'proyecto' => $pvalue['proyecto'],
                // 'total' => ($pvalue['promedio'] * ((float)$SueldoQuincenaEmpleadoRegistros->total + $sueldosEncabezados->total))/100
                'total' => ( ((float)$SueldoQuincenaEmpleadoRegistros->total /100) * $pvalue['promedio']),
                ] ;
              }
            }
            // return response()->json($promedios_movimiento);

          // foreach ($promedios as $pkey => $pvalue) {
          //   if ($SueldoQuincenaEmpleadoRegistros->total != '') {
          //     $promedios_movimiento [] = [
          //       'proyecto' => $pvalue['proyecto'],
          //       'total' => ($pvalue['promedio'] * (float)$SueldoQuincenaEmpleadoRegistros->total)/100
          //       ] ;
          //     }
          //   }

            $b [] = [
              'a' => $value,
              'total_promedios' => $promedios_movimiento,
            ];

          }
          // return response()->json($b);

          $re = DB::table('reportes_encabezados')->select('proyecto')->where('tipo','1')->get();
          foreach ($re as $key => $value) {
            $enca = \App\ReportesEncabezados::where('tipo','3')->where('proyecto',$value->proyecto)->first();
            if (isset($enca) == false) {
              $enca = new \App\ReportesEncabezados();
              $enca->tipo = 3;
              $enca->proyecto =$value->proyecto;
              $enca->save();
            }
          }
          $f = [];
          foreach ($b as $bkey => $bvalue) {

            if (count($bvalue['total_promedios']) != 0) {
              $f [] = $bvalue;
            }
            if (count($bvalue['total_promedios']) != 0) {
              foreach ($bvalue['total_promedios'] as $keyss => $valuess) {
                $re_b = \App\ReportesEncabezados::where('tipo','3')->where('proyecto',$valuess['proyecto'])->first();
                $rr_b = \App\ReportesRegistros::where('reportes_encabezados_id',$re_b->id)
                ->where('semana','Semana '.$bvalue['a']['ns'])
                ->first();

                if (isset($rr_b) == true) {
                  $re_u = \App\ReportesRegistros::where('reportes_encabezados_id',$re_b->id)
                  ->where('semana','Semana '.$bvalue['a']['ns'])->update(['total' => round($valuess['total'],2)]);
                }else {
                  $re = new \App\ReportesRegistros();
                  $re->reportes_encabezados_id = $re_b->id;
                  $re->total = round($valuess['total'],2);
                  $re->semana = 'Semana '.$bvalue['a']['ns'];
                  $re->rango = $bvalue['a']['i'].' - '.$bvalue['a']['f'];
                  $re->save();
                }
              }
            }
          }

          return response()->json(['status' => true]);
        }catch (\Throwable $e) {
          Utilidades::errors($e);
        }
      }




      public function gastos($id)
      {
        try {
          $valores= explode('&',$id);

          $fechauno = $valores[0];
          $fechados = $valores[1];

          $a = $this->ObtenerFechas($id);

          $b = [];
          foreach ($a as $key => $value) {

            $GastosEmpresas = \App\GastosEmpresas::
            whereBetween('fecha',[$value['i'], $value['f']])
            ->select(DB::raw("SUM(cargo) AS total"))
            ->first();

            $proyectos = DB::table('reportes_registros AS rr')
            ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
            ->leftJoin('proyectos AS p','p.id','re.proyecto_id')
            ->leftJoin('proyecto_subcategorias AS ps','ps.id','p.proyecto_subcategorias_id')
            ->where('rr.semana','Semana '.$value['ns'])
            ->where('rr.total' ,'!=','0')
            ->where('re.tipo','1')
            ->whereIn('ps.proyecto_categoria_id',['1','2'])
            ->select('rr.*','re.proyecto')
            ->get();

            $total_proyectos = 0;
            foreach ($proyectos as $pk => $pv) {
              $total_proyectos += $pv->total;
            }

            $promedios = [];
            foreach ($proyectos as $pkk => $pvv) {
              $promedios [] = [
                'proyecto' => $pvv->proyecto,
                'promedio' => ($pvv->total / $total_proyectos) * 100,
              ];
            }
            $promedios_movimiento = [];

            foreach ($promedios as $pkey => $pvalue) {
              if ($GastosEmpresas->total != '') {
                $promedios_movimiento [] = [
                  'proyecto' => $pvalue['proyecto'],
                  'total' => ($pvalue['promedio'] * (float)$GastosEmpresas->total)/100
                  ] ;
                }
              }

              $b [] = [
                'a' => $value,
                'total_promedios' => $promedios_movimiento,
              ];

            }
            $re = DB::table('reportes_encabezados')->select('proyecto')->where('tipo','1')->get();
            foreach ($re as $key => $value) {
              $enca = \App\ReportesEncabezados::where('tipo','4')->where('proyecto',$value->proyecto)->first();
              if (isset($enca) == false) {
                $enca = new \App\ReportesEncabezados();
                $enca->tipo = 4;
                $enca->proyecto =$value->proyecto;
                $enca->save();
              }
            }
            $f = [];
            foreach ($b as $bkey => $bvalue) {

              if (count($bvalue['total_promedios']) != 0) {
                $f [] = $bvalue;
              }
              if (count($bvalue['total_promedios']) != 0) {
                foreach ($bvalue['total_promedios'] as $keyss => $valuess) {
                  $re_b = \App\ReportesEncabezados::where('tipo','4')->where('proyecto',$valuess['proyecto'])->first();
                  $rr_b = \App\ReportesRegistros::where('reportes_encabezados_id',$re_b->id)
                  ->where('semana','Semana '.$bvalue['a']['ns'])
                  ->first();

                  if (isset($rr_b) == true) {
                    $re_u = \App\ReportesRegistros::where('reportes_encabezados_id',$re_b->id)
                    ->where('semana','Semana '.$bvalue['a']['ns'])->update(['total' => round($valuess['total'],2)]);
                  }else {
                    $re = new \App\ReportesRegistros();
                    $re->reportes_encabezados_id = $re_b->id;
                    $re->total = round($valuess['total'],2);
                    $re->semana = 'Semana '.$bvalue['a']['ns'];
                    $re->rango = $bvalue['a']['i'].' - '.$bvalue['a']['f'];
                    $re->save();
                  }
                }
              }
            }
            return response()->json(['status' => true]);

          } catch (\Throwable $e) {
            Utilidades::errors($e);
          }
        }

        public function gastosAdministracion($id)
        {
          try {
            $valores= explode('&',$id);

            $fechauno = $valores[0];
            $fechados = $valores[1];

            $a = $this->ObtenerFechas($id);

            $b = [];
            foreach ($a as $key => $value) {

              $GastosEmpresas = \App\GastosEmpresas::
              whereBetween('fecha',[$value['i'], $value['f']])
              ->where('tipo_gasto','ADMINISTRACIÓN')
              ->select(DB::raw("SUM(cargo) AS total"))
              ->first();

              $proyectos = DB::table('reportes_registros AS rr')
              ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
              ->leftJoin('proyectos AS p','p.id','re.proyecto_id')
              ->leftJoin('proyecto_subcategorias AS ps','ps.id','p.proyecto_subcategorias_id')
              ->where('rr.semana','Semana '.$value['ns'])
              ->where('rr.total' ,'!=','0')
              ->where('re.tipo','1')
              ->whereIn('ps.proyecto_categoria_id',['1','2'])
              ->select('rr.*','re.proyecto')
              ->get();

              $total_proyectos = 0;
              foreach ($proyectos as $pk => $pv) {
                $total_proyectos += $pv->total;
              }

              $promedios = [];
              foreach ($proyectos as $pkk => $pvv) {
                $promedios [] = [
                  'proyecto' => $pvv->proyecto,
                  'promedio' => ($pvv->total / $total_proyectos) * 100,
                ];
              }
              $promedios_movimiento = [];

              foreach ($promedios as $pkey => $pvalue) {
                if ($GastosEmpresas->total != '') {
                  $promedios_movimiento [] = [
                    'proyecto' => $pvalue['proyecto'],
                    'total' => ($pvalue['promedio'] * (float)$GastosEmpresas->total)/100
                    ] ;
                  }
                }

                $b [] = [
                  'a' => $value,
                  'total_promedios' => $promedios_movimiento,
                ];

              }
              $re = DB::table('reportes_encabezados')->select('proyecto')->where('tipo','1')->get();
              foreach ($re as $key => $value) {
                $enca = \App\ReportesEncabezados::where('tipo','5')->where('proyecto',$value->proyecto)->first();
                if (isset($enca) == false) {
                  $enca = new \App\ReportesEncabezados();
                  $enca->tipo = 5;
                  $enca->proyecto =$value->proyecto;
                  $enca->save();
                }
              }
              $f = [];
              foreach ($b as $bkey => $bvalue) {

                if (count($bvalue['total_promedios']) != 0) {
                  $f [] = $bvalue;
                }
                if (count($bvalue['total_promedios']) != 0) {
                  foreach ($bvalue['total_promedios'] as $keyss => $valuess) {
                    $re_b = \App\ReportesEncabezados::where('tipo','5')->where('proyecto',$valuess['proyecto'])->first();
                    $rr_b = \App\ReportesRegistros::where('reportes_encabezados_id',$re_b->id)
                    ->where('semana','Semana '.$bvalue['a']['ns'])
                    ->first();

                    if (isset($rr_b) == true) {
                      $re_u = \App\ReportesRegistros::where('reportes_encabezados_id',$re_b->id)
                      ->where('semana','Semana '.$bvalue['a']['ns'])->update(['total' => round($valuess['total'],2)]);
                    }else {
                      $re = new \App\ReportesRegistros();
                      $re->reportes_encabezados_id = $re_b->id;
                      $re->total = round($valuess['total'],2);
                      $re->semana = 'Semana '.$bvalue['a']['ns'];
                      $re->rango = $bvalue['a']['i'].' - '.$bvalue['a']['f'];
                      $re->save();
                    }
                  }
                }
              }
              return response()->json(['status' => true]);

            } catch (\Throwable $e) {
              Utilidades::errors($e);
            }
          }

          public function gastosFinanzas($id)
          {
            try {
              $valores= explode('&',$id);

              $fechauno = $valores[0];
              $fechados = $valores[1];

              $a = $this->ObtenerFechas($id);

              $b = [];
              foreach ($a as $key => $value) {

                $GastosEmpresas = \App\GastosEmpresas::
                whereBetween('fecha',[$value['i'], $value['f']])
                ->where('tipo_gasto','FINANCIAMIENTO')
                ->select(DB::raw("SUM(cargo) AS total"))
                ->first();

                $proyectos = DB::table('reportes_registros AS rr')
                ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
                ->leftJoin('proyectos AS p','p.id','re.proyecto_id')
                ->leftJoin('proyecto_subcategorias AS ps','ps.id','p.proyecto_subcategorias_id')
                ->where('rr.semana','Semana '.$value['ns'])
                ->where('rr.total' ,'!=','0')
                ->where('re.tipo','1')
                ->whereIn('ps.proyecto_categoria_id',['1','2'])
                ->select('rr.*','re.proyecto')
                ->get();

                $total_proyectos = 0;
                foreach ($proyectos as $pk => $pv) {
                  $total_proyectos += $pv->total;
                }

                $promedios = [];
                foreach ($proyectos as $pkk => $pvv) {
                  $promedios [] = [
                    'proyecto' => $pvv->proyecto,
                    'promedio' => ($pvv->total / $total_proyectos) * 100,
                  ];
                }
                $promedios_movimiento = [];

                foreach ($promedios as $pkey => $pvalue) {
                  if ($GastosEmpresas->total != '') {
                    $promedios_movimiento [] = [
                      'proyecto' => $pvalue['proyecto'],
                      'total' => ($pvalue['promedio'] * (float)$GastosEmpresas->total)/100
                      ] ;
                    }
                  }

                  $b [] = [
                    'a' => $value,
                    'total_promedios' => $promedios_movimiento,
                  ];

                }
                $re = DB::table('reportes_encabezados')->select('proyecto')->where('tipo','1')->get();
                foreach ($re as $key => $value) {
                  $enca = \App\ReportesEncabezados::where('tipo','6')->where('proyecto',$value->proyecto)->first();
                  if (isset($enca) == false) {
                    $enca = new \App\ReportesEncabezados();
                    $enca->tipo = 6;
                    $enca->proyecto =$value->proyecto;
                    $enca->save();
                  }
                }
                $f = [];
                foreach ($b as $bkey => $bvalue) {

                  if (count($bvalue['total_promedios']) != 0) {
                    $f [] = $bvalue;
                  }
                  if (count($bvalue['total_promedios']) != 0) {
                    foreach ($bvalue['total_promedios'] as $keyss => $valuess) {
                      $re_b = \App\ReportesEncabezados::where('tipo','6')->where('proyecto',$valuess['proyecto'])->first();
                      $rr_b = \App\ReportesRegistros::where('reportes_encabezados_id',$re_b->id)
                      ->where('semana','Semana '.$bvalue['a']['ns'])
                      ->first();

                      if (isset($rr_b) == true) {
                        $re_u = \App\ReportesRegistros::where('reportes_encabezados_id',$re_b->id)
                        ->where('semana','Semana '.$bvalue['a']['ns'])->update(['total' => round($valuess['total'],2)]);
                      }else {
                        $re = new \App\ReportesRegistros();
                        $re->reportes_encabezados_id = $re_b->id;
                        $re->total = round($valuess['total'],2);
                        $re->semana = 'Semana '.$bvalue['a']['ns'];
                        $re->rango = $bvalue['a']['i'].' - '.$bvalue['a']['f'];
                        $re->save();
                      }
                    }
                  }
                }
                return response()->json(['status' => true]);

              } catch (\Throwable $e) {
                Utilidades::errors($e);
              }
            }

            public function gastosSeguridad($id)
            {
              try {
                $valores= explode('&',$id);

                $fechauno = $valores[0];
                $fechados = $valores[1];

                $a = $this->ObtenerFechas($id);

                $b = [];
                foreach ($a as $key => $value) {

                  $GastosEmpresas = \App\GastosEmpresas::
                  whereBetween('fecha',[$value['i'], $value['f']])
                  ->where('tipo_gasto','SEGURIDAD')
                  ->select(DB::raw("SUM(cargo) AS total"))
                  ->first();

                  $proyectos = DB::table('reportes_registros AS rr')
                  ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
                  ->leftJoin('proyectos AS p','p.id','re.proyecto_id')
                  ->leftJoin('proyecto_subcategorias AS ps','ps.id','p.proyecto_subcategorias_id')
                  ->where('rr.semana','Semana '.$value['ns'])
                  ->where('rr.total' ,'!=','0')
                  ->where('re.tipo','1')
                  ->whereIn('ps.proyecto_categoria_id',['1','2'])
                  ->select('rr.*','re.proyecto')
                  ->get();

                  $total_proyectos = 0;
                  foreach ($proyectos as $pk => $pv) {
                    $total_proyectos += $pv->total;
                  }

                  $promedios = [];
                  foreach ($proyectos as $pkk => $pvv) {
                    $promedios [] = [
                      'proyecto' => $pvv->proyecto,
                      'promedio' => ($pvv->total / $total_proyectos) * 100,
                    ];
                  }
                  $promedios_movimiento = [];

                  foreach ($promedios as $pkey => $pvalue) {
                    if ($GastosEmpresas->total != '') {
                      $promedios_movimiento [] = [
                        'proyecto' => $pvalue['proyecto'],
                        'total' => ($pvalue['promedio'] * (float)$GastosEmpresas->total)/100
                        ] ;
                      }
                    }

                    $b [] = [
                      'a' => $value,
                      'total_promedios' => $promedios_movimiento,
                    ];

                  }
                  $re = DB::table('reportes_encabezados')->select('proyecto')->where('tipo','1')->get();
                  foreach ($re as $key => $value) {
                    $enca = \App\ReportesEncabezados::where('tipo','7')->where('proyecto',$value->proyecto)->first();
                    if (isset($enca) == false) {
                      $enca = new \App\ReportesEncabezados();
                      $enca->tipo = 7;
                      $enca->proyecto =$value->proyecto;
                      $enca->save();
                    }
                  }
                  $f = [];
                  foreach ($b as $bkey => $bvalue) {

                    if (count($bvalue['total_promedios']) != 0) {
                      $f [] = $bvalue;
                    }
                    if (count($bvalue['total_promedios']) != 0) {
                      foreach ($bvalue['total_promedios'] as $keyss => $valuess) {
                        $re_b = \App\ReportesEncabezados::where('tipo','7')->where('proyecto',$valuess['proyecto'])->first();
                        $rr_b = \App\ReportesRegistros::where('reportes_encabezados_id',$re_b->id)
                        ->where('semana','Semana '.$bvalue['a']['ns'])
                        ->first();

                        if (isset($rr_b) == true) {
                          $re_u = \App\ReportesRegistros::where('reportes_encabezados_id',$re_b->id)
                          ->where('semana','Semana '.$bvalue['a']['ns'])->update(['total' => round($valuess['total'],2)]);
                        }else {
                          $re = new \App\ReportesRegistros();
                          $re->reportes_encabezados_id = $re_b->id;
                          $re->total = round($valuess['total'],2);
                          $re->semana = 'Semana '.$bvalue['a']['ns'];
                          $re->rango = $bvalue['a']['i'].' - '.$bvalue['a']['f'];
                          $re->save();
                        }
                      }
                    }
                  }
                  return response()->json(['status' => true]);

                } catch (\Throwable $e) {
                  Utilidades::errors($e);
                }
              }

              public function gastosVehiculos($id)
              {
                try {
                  $valores= explode('&',$id);

                  $fechauno = $valores[0];
                  $fechados = $valores[1];

                  $a = $this->ObtenerFechas($id);

                  $b = [];
                  foreach ($a as $key => $value) {

                    $GastosEmpresas = \App\GastosEmpresas::
                    whereBetween('fecha',[$value['i'], $value['f']])
                    ->where('tipo_gasto','VEHICULOS')
                    ->select(DB::raw("SUM(cargo) AS total"))
                    ->first();

                    $proyectos = DB::table('reportes_registros AS rr')
                    ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
                    ->leftJoin('proyectos AS p','p.id','re.proyecto_id')
                    ->leftJoin('proyecto_subcategorias AS ps','ps.id','p.proyecto_subcategorias_id')
                    ->where('rr.semana','Semana '.$value['ns'])
                    ->where('rr.total' ,'!=','0')
                    ->where('re.tipo','1')
                    ->whereIn('ps.proyecto_categoria_id',['1','2'])
                    ->select('rr.*','re.proyecto')
                    ->get();

                    $total_proyectos = 0;
                    foreach ($proyectos as $pk => $pv) {
                      $total_proyectos += $pv->total;
                    }

                    $promedios = [];
                    foreach ($proyectos as $pkk => $pvv) {
                      $promedios [] = [
                        'proyecto' => $pvv->proyecto,
                        'promedio' => ($pvv->total / $total_proyectos) * 100,
                      ];
                    }
                    $promedios_movimiento = [];

                    foreach ($promedios as $pkey => $pvalue) {
                      if ($GastosEmpresas->total != '') {
                        $promedios_movimiento [] = [
                          'proyecto' => $pvalue['proyecto'],
                          'total' => ($pvalue['promedio'] * (float)$GastosEmpresas->total)/100
                          ] ;
                        }
                      }

                      $b [] = [
                        'a' => $value,
                        'total_promedios' => $promedios_movimiento,
                      ];

                    }
                    $re = DB::table('reportes_encabezados')->select('proyecto')->where('tipo','1')->get();
                    foreach ($re as $key => $value) {
                      $enca = \App\ReportesEncabezados::where('tipo','8')->where('proyecto',$value->proyecto)->first();
                      if (isset($enca) == false) {
                        $enca = new \App\ReportesEncabezados();
                        $enca->tipo = 8;
                        $enca->proyecto =$value->proyecto;
                        $enca->save();
                      }
                    }
                    $f = [];
                    foreach ($b as $bkey => $bvalue) {

                      if (count($bvalue['total_promedios']) != 0) {
                        $f [] = $bvalue;
                      }
                      if (count($bvalue['total_promedios']) != 0) {
                        foreach ($bvalue['total_promedios'] as $keyss => $valuess) {
                          $re_b = \App\ReportesEncabezados::where('tipo','8')->where('proyecto',$valuess['proyecto'])->first();
                          $rr_b = \App\ReportesRegistros::where('reportes_encabezados_id',$re_b->id)
                          ->where('semana','Semana '.$bvalue['a']['ns'])
                          ->first();

                          if (isset($rr_b) == true) {
                            $re_u = \App\ReportesRegistros::where('reportes_encabezados_id',$re_b->id)
                            ->where('semana','Semana '.$bvalue['a']['ns'])->update(['total' => round($valuess['total'],2)]);
                          }else {
                            $re = new \App\ReportesRegistros();
                            $re->reportes_encabezados_id = $re_b->id;
                            $re->total = round($valuess['total'],2);
                            $re->semana = 'Semana '.$bvalue['a']['ns'];
                            $re->rango = $bvalue['a']['i'].' - '.$bvalue['a']['f'];
                            $re->save();
                          }
                        }
                      }
                    }
                    return response()->json(['status' => true]);

                  } catch (\Throwable $e) {
                    Utilidades::errors($e);
                  }
                }

                ///////////
                public function gastosVentas($id)
                {
                  try {

                    $valores= explode('&',$id);

                    $fechauno = $valores[0];
                    $fechados = $valores[1];

                    $a = $this->ObtenerFechas($id);

                    $b = [];
                    foreach ($a as $key => $value) {

                      $GastosEmpresas = \App\GastosEmpresas::
                      whereBetween('fecha',[$value['i'], $value['f']])
                      ->where('tipo_gasto','VENTAS')
                      ->select(DB::raw("SUM(cargo) AS total"))
                      ->first();

                      $proyectos = DB::table('reportes_registros AS rr')
                      ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
                      ->leftJoin('proyectos AS p','p.id','re.proyecto_id')
                      ->leftJoin('proyecto_subcategorias AS ps','ps.id','p.proyecto_subcategorias_id')
                      ->where('rr.semana','Semana '.$value['ns'])
                      ->where('rr.total' ,'!=','0')
                      ->where('re.tipo','1')
                      ->whereIn('ps.proyecto_categoria_id',['1','2'])
                      ->select('rr.*','re.proyecto')
                      ->get();

                      $total_proyectos = 0;
                      foreach ($proyectos as $pk => $pv) {
                        $total_proyectos += $pv->total;
                      }

                      $promedios = [];
                      foreach ($proyectos as $pkk => $pvv) {
                        $promedios [] = [
                          'proyecto' => $pvv->proyecto,
                          'promedio' => ($pvv->total / $total_proyectos) * 100,
                        ];
                      }
                      $promedios_movimiento = [];

                      foreach ($promedios as $pkey => $pvalue) {
                        if ($GastosEmpresas->total != '') {
                          $promedios_movimiento [] = [
                            'proyecto' => $pvalue['proyecto'],
                            'total' => ($pvalue['promedio'] * (float)$GastosEmpresas->total)/100
                            ] ;
                          }
                        }

                        $b [] = [
                          'a' => $value,
                          'total_promedios' => $promedios_movimiento,
                        ];

                      }
                      $re = DB::table('reportes_encabezados')->select('proyecto')->where('tipo','1')->get();
                      foreach ($re as $key => $value) {
                        $enca = \App\ReportesEncabezados::where('tipo','9')->where('proyecto',$value->proyecto)->first();
                        if (isset($enca) == false) {
                          $enca = new \App\ReportesEncabezados();
                          $enca->tipo = 9;
                          $enca->proyecto =$value->proyecto;
                          $enca->save();
                        }
                      }
                      $f = [];
                      foreach ($b as $bkey => $bvalue) {

                        if (count($bvalue['total_promedios']) != 0) {
                          $f [] = $bvalue;
                        }
                        if (count($bvalue['total_promedios']) != 0) {
                          foreach ($bvalue['total_promedios'] as $keyss => $valuess) {
                            $re_b = \App\ReportesEncabezados::where('tipo','9')->where('proyecto',$valuess['proyecto'])->first();
                            $rr_b = \App\ReportesRegistros::where('reportes_encabezados_id',$re_b->id)
                            ->where('semana','Semana '.$bvalue['a']['ns'])
                            ->first();

                            if (isset($rr_b) == true) {
                              $re_u = \App\ReportesRegistros::where('reportes_encabezados_id',$re_b->id)
                              ->where('semana','Semana '.$bvalue['a']['ns'])->update(['total' => round($valuess['total'],2)]);
                            }else {
                              $re = new \App\ReportesRegistros();
                              $re->reportes_encabezados_id = $re_b->id;
                              $re->total = round($valuess['total'],2);
                              $re->semana = 'Semana '.$bvalue['a']['ns'];
                              $re->rango = $bvalue['a']['i'].' - '.$bvalue['a']['f'];
                              $re->save();
                            }
                          }
                        }
                      }
                      return response()->json(['status' => true]);

                    } catch (\Throwable $e) {
                      Utilidades::errors($e);
                    }
                  }
                  ///

                  public function cajachicaproyectos($id)
                  {
                    try {
                      $valores= explode('&',$id);

                      $fechauno = $valores[0];
                      $fechados = $valores[1];

                      $a = $this->ObtenerFechas($id);

                      $b = [];
                      foreach ($a as $key => $value) {

                        $data = DB::table('comprobaciones_caja_chica AS ccc')
                        ->join('gastos_xml_oc AS gxoc','gxoc.id','ccc.factura_id')
                        ->join('proyectos AS p','p.id','ccc.centro_costos_id')
                        ->leftJoin('proyecto_subcategorias AS ps','ps.id','p.proyecto_subcategorias_id')
                        ->join('proyecto_categorias AS pc','pc.id','ps.proyecto_categoria_id')
                        ->whereBetween('gxoc.fecha',[$value['i'], $value['f']])
                        ->where('pc.nombre','LIKE','Proyectos')
                        ->where('p.id','!=','58')
                        ->select(DB::raw("SUM(gxoc.total) AS total_cc"), 'p.nombre_corto',
                       'p.id AS proyecto_did','pc.nombre AS nombre_c')
                        ->groupBy('p.id')
                        ->get();



                        $b [] = [
                          'a' => $value,
                          'b' => $data,
                        ];
                      }

                      foreach ($b as $key => $value) {

                        if (count($value['b']) != 0) {

                          foreach ($value['b'] as $keyb => $valueb) {
                            $enca = \App\ReportesEncabezados::where('tipo','10')
                            ->where('proyecto_id',$valueb->proyecto_did)->first();

                            if (isset($enca) == false) {
                              $enca = new \App\ReportesEncabezados();
                              $enca->tipo = 10;
                              $enca->proyecto = $valueb->nombre_corto;
                              $enca->proyecto_id = $valueb->proyecto_did;
                              $enca->save();
                            }

                            $re = new \App\ReportesRegistros();
                            $re->reportes_encabezados_id = $enca->id;
                            $re->total = $valueb->total_cc;
                            $re->semana = 'Semana '.$value['a']['ns'];
                            $re->rango = $value['a']['i'].' - '.$value['a']['f'];
                            $re->save();

                          }

                        }

                      }

                      return response()->json($b);
                    } catch (\Throwable $e) {
                      Utilidades::errors($e);
                    }
                  }

                  public function cajachica($id)
                  {
                    try {

                      $valores= explode('&',$id);

                      $fechauno = $valores[0];
                      $fechados = $valores[1];

                      $a = $this->ObtenerFechas($id);

                      $b = [];
                      foreach ($a as $key => $value) {

                        // $GastosEmpresas = \App\GastosEmpresas::
                        // whereBetween('fecha',[$value['i'], $value['f']])
                        // ->where('tipo_gasto','VENTAS')
                        // ->select(DB::raw("SUM(cargo) AS total"))
                        // ->first();

                        $GastosEmpresas = DB::table('comprobaciones_caja_chica AS ccc')
                        ->join('gastos_xml_oc AS gxoc','gxoc.id','ccc.factura_id')
                        ->join('proyectos AS p','p.id','ccc.centro_costos_id')
                        ->leftJoin('proyecto_subcategorias AS ps','ps.id','p.proyecto_subcategorias_id')
                        ->join('proyecto_categorias AS pc','pc.id','ps.proyecto_categoria_id')
                        ->whereBetween('gxoc.fecha',[$value['i'], $value['f']])
                        ->where('pc.nombre','!=','Proyectos')
                        ->orWhere('p.id','==','58')
                        ->select(DB::raw("SUM(gxoc.total) AS total"))
                        // ->groupBy('p.id')
                        ->first();

                        $proyectos = DB::table('reportes_registros AS rr')
                        ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
                        ->leftJoin('proyectos AS p','p.id','re.proyecto_id')
                        ->leftJoin('proyecto_subcategorias AS ps','ps.id','p.proyecto_subcategorias_id')
                        ->where('rr.semana','Semana '.$value['ns'])
                        ->where('rr.total' ,'!=','0')
                        ->where('re.tipo','10')
                        ->whereIn('ps.proyecto_categoria_id',['1','2'])
                        ->select('rr.*','re.proyecto')
                        ->get();

                        $total_proyectos = 0;
                        foreach ($proyectos as $pk => $pv) {
                          $total_proyectos += $pv->total;
                        }

                        $promedios = [];
                        foreach ($proyectos as $pkk => $pvv) {
                          $promedios [] = [
                            'proyecto' => $pvv->proyecto,
                            'promedio' => ($pvv->total / $total_proyectos) * 100,
                          ];
                        }
                        $promedios_movimiento = [];

                        foreach ($promedios as $pkey => $pvalue) {
                          if ($GastosEmpresas->total != '') {
                            $promedios_movimiento [] = [
                              'proyecto' => $pvalue['proyecto'],
                              'total' => ($pvalue['promedio'] * (float)$GastosEmpresas->total)/100
                              ] ;
                            }
                          }

                          $b [] = [
                            'a' => $value,
                            'total_promedios' => $promedios_movimiento,
                          ];

                        }
                        $re = DB::table('reportes_encabezados')->select('proyecto')->where('tipo','10')->get();
                        foreach ($re as $key => $value) {
                          $enca = \App\ReportesEncabezados::where('tipo','10')->where('proyecto',$value->proyecto)->first();
                          if (isset($enca) == false) {
                            $enca = new \App\ReportesEncabezados();
                            $enca->tipo = 10;
                            $enca->proyecto =$value->proyecto;
                            $enca->save();
                          }
                        }
                        $f = [];
                        foreach ($b as $bkey => $bvalue) {

                          if (count($bvalue['total_promedios']) != 0) {
                            $f [] = $bvalue;
                          }
                          if (count($bvalue['total_promedios']) != 0) {
                            foreach ($bvalue['total_promedios'] as $keyss => $valuess) {
                              $re_b = \App\ReportesEncabezados::where('tipo','10')
                              ->where('proyecto',$valuess['proyecto'])->first();
                              $rr_b = \App\ReportesRegistros::where('reportes_encabezados_id',$re_b->id)
                              ->where('semana','Semana '.$bvalue['a']['ns'])
                              ->first();

                              if (isset($rr_b) == true) {
                                // $re_u = \App\ReportesRegistros::where('reportes_encabezados_id',$re_b->id)
                                // ->where('semana','Semana '.$bvalue['a']['ns'])
                                // ->update(['total' => round($valuess['total'],2)]);

                                $re = new \App\ReportesRegistros();
                                $re->reportes_encabezados_id = $re_b->id;
                                $re->total = round($valuess['total'],2);
                                $re->semana = 'Semana '.$bvalue['a']['ns'];
                                $re->rango = $bvalue['a']['i'].' - '.$bvalue['a']['f'];
                                $re->save();
                              }else {
                                $re = new \App\ReportesRegistros();
                                $re->reportes_encabezados_id = $re_b->id;
                                $re->total = round($valuess['total'],2);
                                $re->semana = 'Semana '.$bvalue['a']['ns'];
                                $re->rango = $bvalue['a']['i'].' - '.$bvalue['a']['f'];
                                $re->save();
                              }
                            }
                          }
                        }
                        return response()->json(['status' => true]);

                      } catch (\Throwable $e) {
                        Utilidades::errors($e);
                      }
                    }

                    public function combustibleproyecto($id)
                    {
                      try {
                        $valores= explode('&',$id);

                        $fechauno = $valores[0];
                        $fechados = $valores[1];

                        $a = $this->ObtenerFechas($id);

                        $b = [];
                        foreach ($a as $key => $value) {

                          $data = DB::table('combustible AS ccc')
                          ->join('proyectos AS p','p.id','ccc.proyecto_id')
                          ->leftJoin('proyecto_subcategorias AS ps','ps.id','p.proyecto_subcategorias_id')
                          ->join('proyecto_categorias AS pc','pc.id','ps.proyecto_categoria_id')
                          ->whereBetween('ccc.fecha',[$value['i'], $value['f']])
                          ->where('p.id','!=','1')
                          // ->where('p.id','!=','58')
                          ->select(DB::raw("SUM(ccc.total) AS total_cc"), 'p.nombre_corto',
                         'p.id AS proyecto_did'
                         ,'pc.nombre AS nombre_c'
                         )
                          ->groupBy('p.id')
                          ->get();



                          $b [] = [
                            'a' => $value,
                            'b' => $data,
                          ];
                        }
                        //
                        foreach ($b as $key => $value) {

                          if (count($value['b']) != 0) {

                            foreach ($value['b'] as $keyb => $valueb) {
                              $enca = \App\ReportesEncabezados::where('tipo','11')
                              ->where('proyecto_id',$valueb->proyecto_did)->first();

                              if (isset($enca) == false) {
                                $enca = new \App\ReportesEncabezados();
                                $enca->tipo = 11;
                                $enca->proyecto = $valueb->nombre_corto;
                                $enca->proyecto_id = $valueb->proyecto_did;
                                $enca->save();
                              }

                              $re = new \App\ReportesRegistros();
                              $re->reportes_encabezados_id = $enca->id;
                              $re->total = $valueb->total_cc;
                              $re->semana = 'Semana '.$value['a']['ns'];
                              $re->rango = $value['a']['i'].' - '.$value['a']['f'];
                              $re->save();

                            }

                          }

                        }

                        return response()->json($b);
                      } catch (\Throwable $e) {
                        Utilidades::errors($e);
                      }
                    }

                    public function combustible($id)
                    {
                      try {

                        $valores= explode('&',$id);

                        $fechauno = $valores[0];
                        $fechados = $valores[1];

                        $a = $this->ObtenerFechas($id);

                        $b = [];
                        foreach ($a as $key => $value) {

                          // $GastosEmpresas = \App\GastosEmpresas::
                          // whereBetween('fecha',[$value['i'], $value['f']])
                          // ->where('tipo_gasto','VENTAS')
                          // ->select(DB::raw("SUM(cargo) AS total"))
                          // ->first();

                          // $GastosEmpresas = DB::table('comprobaciones_caja_chica AS ccc')
                          // ->join('gastos_xml_oc AS gxoc','gxoc.id','ccc.factura_id')
                          // ->join('proyectos AS p','p.id','ccc.centro_costos_id')
                          // ->leftJoin('proyecto_subcategorias AS ps','ps.id','p.proyecto_subcategorias_id')
                          // ->join('proyecto_categorias AS pc','pc.id','ps.proyecto_categoria_id')
                          // ->whereBetween('gxoc.fecha',[$value['i'], $value['f']])
                          // ->where('pc.nombre','!=','Proyectos')
                          // ->orWhere('p.id','==','58')
                          // ->select(DB::raw("SUM(gxoc.total) AS total"))
                          // // ->groupBy('p.id')
                          // ->first();

                          $GastosEmpresas = DB::table('combustible AS ccc')
                          ->join('proyectos AS p','p.id','ccc.proyecto_id')
                          ->leftJoin('proyecto_subcategorias AS ps','ps.id','p.proyecto_subcategorias_id')
                          ->leftJoin('proyecto_categorias AS pc','pc.id','ps.proyecto_categoria_id')
                          ->whereBetween('ccc.fecha',[$value['i'], $value['f']])
                          ->where('p.id','=','1')
                          // ->where('p.id','!=','58')
                          ->select(DB::raw("SUM(ccc.total) AS total"))
                          ->first();

                          $proyectos = DB::table('reportes_registros AS rr')
                          ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
                          ->leftJoin('proyectos AS p','p.id','re.proyecto_id')
                          ->leftJoin('proyecto_subcategorias AS ps','ps.id','p.proyecto_subcategorias_id')
                          ->where('rr.semana','Semana '.$value['ns'])
                          ->where('rr.total' ,'!=','0')
                          ->where('re.tipo','11')
                          ->whereIn('ps.proyecto_categoria_id',['1','2'])
                          ->select('rr.*','re.proyecto')
                          ->get();

                          $total_proyectos = 0;
                          foreach ($proyectos as $pk => $pv) {
                            $total_proyectos += $pv->total;
                          }

                          $promedios = [];
                          foreach ($proyectos as $pkk => $pvv) {
                            $promedios [] = [
                              'proyecto' => $pvv->proyecto,
                              'promedio' => ($pvv->total / $total_proyectos) * 100,
                            ];
                          }
                          $promedios_movimiento = [];

                          foreach ($promedios as $pkey => $pvalue) {
                            if ($GastosEmpresas->total != '') {
                              $promedios_movimiento [] = [
                                'proyecto' => $pvalue['proyecto'],
                                'total' => ($pvalue['promedio'] * (float)$GastosEmpresas->total)/100
                                ] ;
                              }
                            }

                            $b [] = [
                              'a' => $value,
                              'total_promedios' => $promedios_movimiento,
                            ];

                          }
                          $re = DB::table('reportes_encabezados')->select('proyecto')->where('tipo','11')->get();
                          foreach ($re as $key => $value) {
                            $enca = \App\ReportesEncabezados::where('tipo','11')->where('proyecto',$value->proyecto)->first();
                            if (isset($enca) == false) {
                              $enca = new \App\ReportesEncabezados();
                              $enca->tipo = 11;
                              $enca->proyecto =$value->proyecto;
                              $enca->save();
                            }
                          }
                          $f = [];
                          foreach ($b as $bkey => $bvalue) {

                            if (count($bvalue['total_promedios']) != 0) {
                              $f [] = $bvalue;
                            }
                            if (count($bvalue['total_promedios']) != 0) {
                              foreach ($bvalue['total_promedios'] as $keyss => $valuess) {
                                $re_b = \App\ReportesEncabezados::where('tipo','11')
                                ->where('proyecto',$valuess['proyecto'])->first();
                                $rr_b = \App\ReportesRegistros::where('reportes_encabezados_id',$re_b->id)
                                ->where('semana','Semana '.$bvalue['a']['ns'])
                                ->first();

                                if (isset($rr_b) == true) {
                                  // $re_u = \App\ReportesRegistros::where('reportes_encabezados_id',$re_b->id)
                                  // ->where('semana','Semana '.$bvalue['a']['ns'])
                                  // ->update(['total' => round($valuess['total'],2)]);
                                  $re = new \App\ReportesRegistros();
                                  $re->reportes_encabezados_id = $re_b->id;
                                  $re->total = round($valuess['total'],2);
                                  $re->semana = 'Semana '.$bvalue['a']['ns'];
                                  $re->rango = $bvalue['a']['i'].' - '.$bvalue['a']['f'];
                                  $re->save();
                                }else {
                                  $re = new \App\ReportesRegistros();
                                  $re->reportes_encabezados_id = $re_b->id;
                                  $re->total = round($valuess['total'],2);
                                  $re->semana = 'Semana '.$bvalue['a']['ns'];
                                  $re->rango = $bvalue['a']['i'].' - '.$bvalue['a']['f'];
                                  $re->save();
                                }
                              }
                            }
                          }
                          return response()->json(['status' => true]);

                        } catch (\Throwable $e) {
                          Utilidades::errors($e);
                        }
                      }

                      public function cargarPro($id)
                      {
                        return Excel::download(new EstatusMaterialExport($id), 'Reportes.xlsx');

                        // $valores= explode('&',$id);

                        $valores= explode('&',$id);

                        $fechauno = $valores[0];
                        $fechados = $valores[1];

                        $fechaInicio=strtotime($fechauno);
                        $fechaFin=strtotime($fechados);
                        $a = [];
                        for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
                          $a [] = date("Y-m-d", $i);
                        }



                        $empleados = DB::table('sueldo_semana_empleado')->get();

                        $arreglo = [];
                        foreach ($empleados as $key_e => $value_e) {
                          $reg = [];
                          foreach ($a as $k => $v) {
                            $r = DB::table('sueldo_semana_empleado_registros')
                            ->where('sueldo_semana_empleado_id',$value_e->id)
                            ->where('fecha_i',$v)->first();
                            $c = 0;
                            if (isset($r) == true) {
                              $c= 1;
                            }else {
                              $c = 0;
                            }

                            $reg [] = [
                              'a' => $v,
                              'b' => $c,
                            ];
                          }
                          $arreglo [] = [
                            'x' => $value_e,
                            'y' => $reg,
                          ];


                        }
                        return response()->json($arreglo);

                        //
                        // $fechas = $this->getInicioFinSemana($valores[0],$valores[1]);
                        //
                        //
                        //                           ///CALCULO DE PROMEDIOS DE ACTIVIDAD POR SEMANA
                        //                           $proyectos = DB::table('reportes_registros AS rr')
                        //                           ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
                        //                           ->leftJoin('proyectos AS p','p.id','re.proyecto_id')
                        //                           ->leftJoin('proyecto_subcategorias AS ps','ps.id','p.proyecto_subcategorias_id')
                        //                           ->where('rr.rango','LIKE',$fechas['inicio'].' - '.$fechas['fin'])
                        //                           ->where('rr.total' ,'!=','0')
                        //                           ->where('re.tipo','1')
                        //                           ->whereIn('ps.proyecto_categoria_id',['1','2'])
                        //                           ->select('rr.*','re.proyecto')
                        //                           ->get();
                        //
                        //
                        //                           $total_proyectos = 0;
                        //                           foreach ($proyectos as $pk => $pv) {
                        //                             $total_proyectos += $pv->total;
                        //                           }
                        //
                        //                           $promedios = [];
                        //                           foreach ($proyectos as $pkk => $pvv) {
                        //                             $promedios [] = [
                        //                               'proyecto' => $pvv->proyecto,
                        //                               'promedio' => round(($pvv->total / $total_proyectos) * 100),
                        //                             ];
                        //                           }
                        //                           ///CALCULO DE PROMEDIOPS DE ACTIVIDAD POR SEMANA
                        //                           // return response()->json($promedios);
                        //
                        //
                        //                           $semana = DB::table('sueldo_semana_empleado_registros AS sser')
                        //                           ->whereBetween('fecha_i',[$fechas['inicio'],$fechas['fin']])
                        //                           ->where('marca','0')
                        //                           ->get();
                        //
                        //
                        //                           // return response()->json(count($semana));
                        //
                        //                           $cantidad_po = [];
                        //
                        //                           foreach ($promedios as $key_p => $value_p) {
                        //                             $cant = 0;
                        //
                        //                             $cant = round( (count($semana) / 100) * $value_p['promedio']);
                        //                             $id_pro = DB::table('proyectos as p')->where('p.nombre_corto','LIKE',$value_p['proyecto'])->first();
                        //                             $cantidad_po [] = [
                        //                               'nom' => $value_p['proyecto'],
                        //                               'proyecto' => $id_pro->id,
                        //                               'cantidad' => $cant,
                        //                             ];
                        //                           }
                        //
                        //                           // foreach ($variable as $key => $value) {
                        //                           //   // code...
                        //                           // }
                        //
                        //
                        //                           return response()->json($cantidad_po);


                      }

                  public function arreglos_final_d($a )
                  {
                    $apariciones = [];
                    $total_s = 0;
                    foreach ($a as $key => $value) {
                      $empleados_tiempo = DB::table('control_tiempo')
                      ->whereBetween('fecha',[$value['i'], $value['f']])
                      ->select('empleado_asignado_id','fecha')
                      ->get();
                      $f = [];
                      foreach ($empleados_tiempo as $key => $vs) {
                        $total_s = 0;

                        $empleado_ = \App\Empleado::where('id',$vs->empleado_asignado_id)
                        ->select(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre) AS empleado_imss"))->first();

                        // $SueldoSemanaEmpleado = \App\SueldoSemanaEmpleado::where('nombre',$empleado_['empleado_imss'])->select('id')->get();
                        $SueldoSemanaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::
                        join('sueldo_semana_empleado AS sqe','sqe.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
                        ->where('sueldo_semana_empleado_registros.fecha_i',$vs->fecha)
                        ->where('sqe.nombre','LIKE',$empleado_['empleado_imss'].'%')
                        ->select("total")
                        ->first();


                        if (isset($SueldoSemanaEmpleadoRegistros) == true) {
                          $total_s += (($SueldoSemanaEmpleadoRegistros->total * 7)/6);
                        }

                        $f [] = $total_s;
                      }
                      $apariciones []=array_sum($f);
                    }
                    return $apariciones;
                  }

                  public function getObraOperativos($id)
                  {
                    $proyecto = DB::table('proyectos')->where('id',$id)->first();

                    $mano_operativos = DB::table('reportes_registros AS rr')
                    ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
                    ->select(DB::raw("SUM(rr.total) AS total_operativos"))
                    ->where('re.proyecto',$proyecto->nombre_corto)
                    ->where('re.tipo','1')
                    ->first();

                    return response()->json($mano_operativos->total_operativos);
                  }

                  public function getSumaAdministrativos($id)
                  {
                    $proyecto = DB::table('proyectos')->where('id',$id)->first();

                    $mano_admin = DB::table('reportes_registros AS rr')
                    ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
                    ->select(DB::raw("SUM(rr.total) AS total_admin"))
                    ->where('re.proyecto',$proyecto->nombre_corto)
                    ->where('re.tipo','2')
                    ->first();

                    return response()->json($mano_admin->total_admin);
                  }

                  public function getSumaSinProyecto($id)
                  {
                    $proyecto = DB::table('proyectos')->where('id',$id)->first();

                    $mano_sin_pro = DB::table('reportes_registros AS rr')
                    ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
                    ->select(DB::raw("SUM(rr.total) AS total_sin_pro"))
                    ->where('re.proyecto',$proyecto->nombre_corto)
                    ->where('re.tipo','3')
                    ->first();

                    return response()->json($mano_sin_pro->total_sin_pro);
                  }

                  public function getSumaGastos($id)
                  {
                    $proyecto = DB::table('proyectos')->where('id',$id)->first();

                    $gastos = DB::table('reportes_registros AS rr')
                    ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
                    ->select(DB::raw("SUM(rr.total) AS gastos_total"))
                    ->where('re.proyecto',$proyecto->nombre_corto)
                    ->where('re.tipo','4')
                    ->first();

                    return response()->json($gastos->gastos_total);
                  }

                  public function getSumaGastosAdmin($id)
                  {
                    $proyecto = DB::table('proyectos')->where('id',$id)->first();

                    $gastos = DB::table('reportes_registros AS rr')
                    ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
                    ->select(DB::raw("SUM(rr.total) AS gastos_total"))
                    ->where('re.proyecto',$proyecto->nombre_corto)
                    ->where('re.tipo','5')
                    ->first();

                    return response()->json($gastos->gastos_total);
                  }

                  public function getSumaGastosFinan($id)
                  {
                    $proyecto = DB::table('proyectos')->where('id',$id)->first();

                    $gastos = DB::table('reportes_registros AS rr')
                    ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
                    ->select(DB::raw("SUM(rr.total) AS gastos_total"))
                    ->where('re.proyecto',$proyecto->nombre_corto)
                    ->where('re.tipo','6')
                    ->first();

                    return response()->json($gastos->gastos_total);
                  }

                  public function getSumaGastosSegu($id)
                  {
                    $proyecto = DB::table('proyectos')->where('id',$id)->first();

                    $gastos = DB::table('reportes_registros AS rr')
                    ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
                    ->select(DB::raw("SUM(rr.total) AS gastos_total"))
                    ->where('re.proyecto',$proyecto->nombre_corto)
                    ->where('re.tipo','7')
                    ->first();

                    return response()->json($gastos->gastos_total);
                  }

                  public function getSumaGastosVehi($id)
                  {
                    $proyecto = DB::table('proyectos')->where('id',$id)->first();

                    $gastos = DB::table('reportes_registros AS rr')
                    ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
                    ->select(DB::raw("SUM(rr.total) AS gastos_total"))
                    ->where('re.proyecto',$proyecto->nombre_corto)
                    ->where('re.tipo','8')
                    ->first();

                    return response()->json($gastos->gastos_total);
                  }

                  public function getSumaGastosVen($id)
                  {
                    $proyecto = DB::table('proyectos')->where('id',$id)->first();

                    $gastos = DB::table('reportes_registros AS rr')
                    ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
                    ->select(DB::raw("SUM(rr.total) AS gastos_total"))
                    ->where('re.proyecto',$proyecto->nombre_corto)
                    ->where('re.tipo','9')
                    ->first();

                    return response()->json($gastos->gastos_total);
                  }

                  public function getSumaCajaChica($id)
                  {
                    $proyecto = DB::table('proyectos')->where('id',$id)->first();

                    $gastos = DB::table('reportes_registros AS rr')
                    ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
                    ->select(DB::raw("SUM(rr.total) AS gastos_total"))
                    ->where('re.proyecto',$proyecto->nombre_corto)
                    ->where('re.tipo','10')
                    ->first();
                    $t = 0;
                    if (isset($gastos) == true) {
                      $t += $gastos->gastos_total;
                    }else {
                      $t = 0;
                    }

                    return response()->json($t);
                  }

                  public function getSumaCombustible($id)
                  {
                    $proyecto = DB::table('proyectos')->where('id',$id)->first();

                    $gastos = DB::table('reportes_registros AS rr')
                    ->join('reportes_encabezados AS re','re.id','rr.reportes_encabezados_id')
                    ->select(DB::raw("SUM(rr.total) AS gastos_total"))
                    ->where('re.proyecto',$proyecto->nombre_corto)
                    ->where('re.tipo','11')
                    ->first();

                    $t = 0;
                    if (isset($gastos) == true) {
                      $t = $gastos->gastos_total;
                    }else {
                      $t=0;
                    }

                    return response()->json($t);
                  }

                  public function getMaterialAlmacen($id)
                  {
                    // return response()->json(['status' => 'hola']);

                    $partidas = DB::table('partidas AS pa')
                    ->join('salidas AS s','s.id','pa.salida_id')
                    ->join('lote_almacen AS la','pa.lote_id','la.id')
                    ->join('lotes AS l','l.id','la.lote_id')
                    ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
                    ->join('articulos AS a','a.id','la.articulo_id')
                    ->where('pa.tiposalida_id','1')
                    ->where('s.proyecto_id',$id)
                    ->where('la.stocke_id','1')
                    ->select('la.id','la.stocke_id','pa.id AS par_id','la.articulo_id',
                    DB::raw("SUM(pa.cantidad) AS cantidad_salida"),
                    'a.descripcion','a.unidad','l.id AS l_id','pe.id AS pe_di','pe.req_com_id'
                    )
                    ->get()->toArray();

                    $partidas_r = DB::table('partidas AS pa')
                    ->join('salidassitio AS s','s.id','pa.salida_id')
                    ->join('lote_almacen AS la','pa.lote_id','la.id')
                    ->join('lotes AS l','l.id','la.lote_id')
                    ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
                    ->join('articulos AS a','a.id','la.articulo_id')
                    ->where('pa.tiposalida_id','1')
                    ->where('s.proyecto_id',$id)
                    ->where('la.stocke_id','1')
                    ->select('la.id','la.stocke_id','pa.id AS par_id','la.articulo_id',
                    DB::raw("SUM(pa.cantidad) AS cantidad_salida"),
                    'a.descripcion','a.unidad','l.id AS l_id','pe.id AS pe_di','pe.req_com_id'
                    )
                    ->get()->toArray();

                    $par_uno = array_merge($partidas, $partidas_r);

                    $partidas_s = DB::table('partidas AS pa')
                    ->join('salidasresguardo AS s','s.id','pa.salida_id')
                    ->join('lote_almacen AS la','pa.lote_id','la.id')
                    ->join('lotes AS l','l.id','la.lote_id')
                    ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
                    ->join('articulos AS a','a.id','la.articulo_id')
                    ->where('pa.tiposalida_id','1')
                    ->where('s.proyecto_id',$id)
                    ->where('la.stocke_id','1')
                    ->select('la.id','la.stocke_id','pa.id AS par_id','la.articulo_id',
                    DB::raw("SUM(pa.cantidad) AS cantidad_salida"),
                    'a.descripcion','a.unidad','l.id AS l_id','pe.id AS pe_di','pe.req_com_id'
                    )
                    ->get()->toArray();

                    $par = array_merge($par_uno, $partidas_s);
                    return response()->json($par);

                    $arreglo = [];

                    foreach ($par as $key => $value) {
                      $data_uno = DB::table('precios_almacen')
                      ->where('articulo_id',$value->articulo_id)->where('partida_id',$value->par_id)->first();

                      $arreglo [] = [
                        'a' => $value,
                        'b' => $data_uno,
                      ];
                    }


                  }

                  public function getSumaMaterialAlmacen($id)
                  {

                    $valores = explode('&',$id);
                    if ($valores[0] == 139) {
                      $t =  218459.47;
                    }else {

                    $data = DB::table('partidas_requisiciones AS pr')
                    ->join('requisiciones AS r','r.id','pr.requisicione_id')
                    ->where('r.proyecto_id',$valores[0])
                    ->where('pr.precio_compras','1')
                    ->get();
                    $arreglo = [];

                    foreach ($data as $key => $value) {
                      $data_uno = DB::table('precios_almacen')
                      ->where('articulo_id',$value->articulo_id)->where('req_id',$value->requisicione_id)->first();

                      $arreglo [] = [
                        'a' => $value,
                        'b' => $data_uno,
                      ];
                    }
                    $t = 0;
                    foreach ($arreglo as $key => $value) {
                      if ($value['b']->moneda === 'USD') {
                        $t += ($value['b']->precio * $valores[1]) * $value['a']->cantidad_almacen;
                      }elseif ($value['b']->moneda === 'MX') {
                        $t += $value['b']->precio * $value['a']->cantidad_almacen;
                      }
                    }
                  }

                    return response()->json($t);

                  }

                }
