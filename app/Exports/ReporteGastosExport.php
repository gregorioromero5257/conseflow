<?php

namespace App\Exports;

use App\Existencia;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Contrato;
use App\ContratoDBP;
use App\Sueldo;
use App\SueldoDBP;

class ReporteGastosExport implements FromView, ShouldAutoSize, WithEvents
{
  protected $id;

  public function __construct($id)
  {
    $this->id = $id;
  }

  public function registerEvents(): array
  {
    $valores= explode('&',$this->id);
    $fechauno = $valores[0];
    $fechados = $valores[1];
    // $user = Auth::user();
    $emplados = DB::table('control_tiempo')
    ->whereBetween('fecha',[$fechauno, $fechados])
    // ->where('supervisor_id',$user->empleado_id)
    ->select('empleado_asignado_id')
    ->groupBy('empleado_asignado_id')
    ->get();
    $tam = count($emplados) + 3;
    $styleArray = [
      /****Inicia la posicion 0****/
      [
        'font' => [
          'bold' => true,
          'color' => ['argb' => '0489B1'],

        ],
      ],
      /****fin la posicion 0****/
      /****Inicia la posicion 1****/
      [
        'font' => [
          'bold' => true,
          'color' => ['argb' => 'ffffff   '],

        ],
      ], /****fin la posicion 1****/
      [
        'font' => [
          'bold' => true,
          'color' => ['argb' => 'ffffff'],

        ],
      ],
      /*
      [   ]
      puedes crear mas posiciones aqui en el array y llamarlos en el AfterSheet
      */
    ];
    return [
      //todos los estilos los cargas aqui
      AfterSheet::class => function (AfterSheet $event) use ($styleArray,$tam) {

        // $event->sheet->mergeCells("a".(1).":B".(1));
        // $this->parseWidth(0, 0,'A', 100);
        // $event->sheet->getStyle('A4:EE100')->applyFromArray($styleArray[1]);
        // $event->sheet->getStyle('A3:EE3')->applyFromArray($styleArray[2]);

        // $event->sheet->getStyle('A1:B1')->applyFromArray($styleArray[2]);
        // $event->sheet->getStyle('')->getFill()->setFillType('solid')->getStartColor()->setARGB('000000');

        $event->sheet->getStyle('A2:CZ2')->applyFromArray($styleArray[1]);//se llama el array de la posicion 0
        $event->sheet->getStyle('A3:CZ3')->applyFromArray($styleArray[1]);//se llama el array de la posicion 0
        $event->sheet->getStyle('A4:CZ4')->applyFromArray($styleArray[1]);//se llama el array de la posicion 0
        // $event->sheet->getStyle('A4:A'.$tam)->applyFromArray($styleArray[1]);//se llama el array de la posicion 0
        // $event->sheet->getStyle('A3:BB3')->getFill()->setFillType('solid')->getStartColor()->setARGB('e68a00');

        /*getFill()->setFillType('solid')->getStartColor()->setARGB('5882FA');*/
      },
    ];
  }

  public function view(): View
  {
    // ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 3000);
          //
          //
          // $valores= explode('&',$this->id);
          // $fechauno = $valores[0];
          // $fechados = $valores[1];
          //
          // $fechas = [];
          // $fechas_siete = [];
          // $fechaInicio=strtotime($fechauno);
          // $fechaFin=strtotime($fechados);
          // // for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
          // //  $fechas [] = date("d-m-Y", $i);
          // // }
          // for($i=$fechaInicio; $i<=$fechaFin; $i+=604800){
          //   $fechas_siete [] = date("Y-m-d", $i);
          // }
          // for($i=($fechaInicio +518400); $i<=($fechaFin+604800); $i+=604800){
          //   $fechas_n [] = date("Y-m-d", $i);
          // }
          // $a = [];
          // $tam = count($fechas_siete);
          //
          //
          // for ($h=0; $h < $tam ; $h++) {
          //   $arreglo =[];
          //   $arreglo_fechas_mostrar = [];
          //   $arreglo_fechas_buscar = [];
          //   $fechaInicio=strtotime($fechauno);
          //   $fechaFin=strtotime($fechados);
          //
          //   for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
          //     $arreglo_fechas [] = date("d-M-yy",$i);
          //     $arreglo_fechas_buscar [] = date('Y-m-d',$i);
          //   }
          //   $dia   = substr($fechas_siete[$h],8,2);
          //   $mes = substr($fechas_siete[$h],5,2);
          //   $anio = substr($fechas_siete[$h],0,4);
          //   $semana = date('W',  mktime(0,0,0,$mes,$dia,$anio));
          //
          //   $a [] = [
          //     'ns' => $semana,
          //     'i' => $fechas_siete[$h],
          //     'f' => $fechas_n[$h],
          //   ];
          // }
          //
          //
          // $encabezados = DB::table('reportes_encabezados')->where('tipo','2')->get();
          // $arreglo_uno = [];$arreglo_uno_dos = [];
          // foreach ($encabezados as $key => $encabezado) {
          //   $arreglo_dos = [];
          //   $arreglo_dos_dos = 0;
          //   foreach ($a as $key => $aa) {
          //     $registros = DB::table('reportes_registros')
          //     ->where('rango','like','%'.$aa['i'].' - '.$aa['f'].'%')
          //     ->where('reportes_encabezados_id',$encabezado->id)->select('total')->first();
          //
          //     $arreglo_dos [] = $registros;
          //     $arreglo_dos_dos += isset($registros) == true ?  $registros->total : 0;
          //   }
          //   $arreglo_uno [] =
          //   ['uno' => $encabezado,
          //   'dos' => $arreglo_dos];
          //   $arreglo_uno_dos [] =
          //   ['uno' => $encabezado,
          //   'dos' => $arreglo_dos_dos];
          //
          // }



                  $valores= explode('&',$this->id);
                  $fechauno = $valores[0];
                  $fechados = $valores[1];

                  $fechas = [];
                  $fechas_siete = [];
                  $fechaInicio=strtotime($fechauno);
                  $fechaFin=strtotime($fechados);
                  // for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
                  //  $fechas [] = date("d-m-Y", $i);
                  // }
                  for($i=$fechaInicio; $i<=$fechaFin; $i+=604800){
                    $fechas_siete [] = date("Y-m-d", $i);
                  }
                  for($i=($fechaInicio +518400); $i<=($fechaFin+604800); $i+=604800){
                    $fechas_n [] = date("Y-m-d", $i);
                  }
                  // return response()->json(count($fechas_n));

                  $a = [];
                  $user = Auth::user();
                  $tam = count($fechas_siete);
                  $proyectos = DB::table('control_tiempo')
                  ->leftJoin('proyectos AS p','p.id','=','control_tiempo.proyecto_id')
                  ->whereBetween('fecha',[$fechauno, $fechados])
                  // ->where('control_tiempo.supervisor_id',$user->empleado_id)
                  ->select('control_tiempo.proyecto_id','p.nombre_corto')
                  ->groupBy('control_tiempo.proyecto_id')
                  ->get();

                  $arreglo_proyectos = [];
                  foreach ($proyectos as $key => $proyecto) {
                    $empleados_p = DB::table('control_tiempo')
                    ->whereBetween('fecha',[$fechauno, $fechados])
                    // ->where('control_tiempo.supervisor_id',$user->empleado_id)
                    ->where('proyecto_id',$proyecto->proyecto_id)->get();

                    $total = 0;

                    foreach ($empleados_p as $key => $empleado) {
                      $contratos = \App\Contrato::where('empleado_id',$empleado->empleado_asignado_id)->orderBy('id', 'desc')->get();
                      if (count($contratos) > 0) {
                        $sueldos = DB::table('sueldos')->where('contrato_id', $contratos[0]['id'])->orderBy('id', 'desc')->get();
                        if (count($sueldos) > 0) {
                          $total += $sueldos[0]->sueldo_diario_real;
                        }
                        $total += 0;
                      }else {
                        $total += 0;
                      }
                    }
                    $arreglo_proyectos [] =
                    [
                      'proyecto' => $proyecto,
                      'total' => $total,
                    ];
                  }
                  $emplados = DB::table('control_tiempo')
                  ->whereBetween('fecha',[$fechauno, $fechados])
                  // ->where('supervisor_id',$user->empleado_id)
                  ->select('empleado_asignado_id')
                  ->groupBy('empleado_asignado_id')
                  ->get();

                  for ($h=0; $h < $tam ; $h++) {
                    $arreglo =[];


                    $emplado_supervisor = DB::table('empleados AS e')->
                    select(DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_nombre"))
                    ->where('id',$user->empleado_id)
                    ->first();

                    $arreglo_fechas_mostrar = [];
                    $arreglo_fechas_buscar = [];
                    $fechaInicio=strtotime($fechauno);
                    $fechaFin=strtotime($fechados);

                    for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
                      $arreglo_fechas [] = date("d-M-yy",$i);
                      $arreglo_fechas_buscar [] = date('Y-m-d',$i);
                      // $arreglo_dias [] = $this->get_nombre_dia(date('Y-m-d',$i));
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


                    //
                  }

                  $proyectos = DB::table('control_tiempo')
                  ->leftJoin('proyectos AS p','p.id','=','control_tiempo.proyecto_id')
                  ->whereBetween('fecha',[$fechauno, $fechados])
                  ->select('control_tiempo.proyecto_id','p.nombre_corto')
                  ->groupBy('proyecto_id')
                  ->get();
                  $arreglos_final = [];
                  foreach ($proyectos as $key => $proyecto) {
                    $apariciones = [];

                    $total_s = 0;
                    foreach ($a as $key => $value) {
                      $empleados_tiempo = DB::table('control_tiempo')
                      ->whereBetween('fecha',[$value['i'], $value['f']])
                      ->where('proyecto_id',$proyecto->proyecto_id)
                      ->select('empleado_asignado_id','fecha')
                      ->get();
                      $f = [];
                      foreach ($empleados_tiempo as $key => $vs) {
                        $total_s = 0;

                        $empleado_ = \App\Empleado::where('id',$vs->empleado_asignado_id)
                        ->select(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre) AS empleado_imss"))->first();

                        $SueldoSemanaEmpleado = \App\SueldoSemanaEmpleado::where('nombre',$empleado_->empleado_imss)->get();

                        foreach ($SueldoSemanaEmpleado as $key => $value_s) {
                          $SueldoSemanaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::where('fecha_i',$vs->fecha)
                          ->whereIn('sueldo_semana_empleado_id',$value_s)
                          ->first();

                          if (isset($SueldoSemanaEmpleadoRegistros) == true) {
                            $total_s += (($SueldoSemanaEmpleadoRegistros->total*7)/6);
                          }
                        }


                        $f [] = $total_s;
                      }



                      $apariciones []=array_sum($f);
                      // $apariciones []= $total_s * count($empleados_tiempo);
                    }

                    $arreglos_final [] = [
                      'proyectos' => $proyecto,
                      'apa' => $apariciones,
                    ];
                    // $total_suman [] = array_sum($apariciones);
                  }



    return view('excel.reportegastosgeneral', compact('sueldos_contratos','arreglo_uno_dos','arreglos_final_data','arreglo_proyectos_complete','arreglos_final','a'));
  }

  function get_nombre_dia($fecha){
    $fechats = strtotime($fecha); //pasamos a timestamp

    //el parametro w en la funcion date indica que queremos el dia de la semana
    //lo devuelve en numero 0 domingo, 1 lunes,....
    switch (date('w', $fechats)){
      case 0: return "Domingo"; break;
      case 1: return "Lunes"; break;
      case 2: return "Martes"; break;
      case 3: return "Miercoles"; break;
      case 4: return "Jueves"; break;
      case 5: return "Viernes"; break;
      case 6: return "Sabado"; break;
    }
  }

  public function arreglos_final_d($a )
  {
    $apariciones = [];
    $total_s = 0;
    foreach ($a as $key => $value) {
      $empleados_tiempo = DB::table('control_tiempo')
      ->whereBetween('fecha',[$value['i'], $value['f']])
      ->select('empleado_asignado_id')
      ->get();
      $f = [];
      foreach ($empleados_tiempo as $key => $vs) {
        $total_s = 0;
        $contratos = DB::table('control_tiempo')
        ->leftJoin('contratos AS c', 'c.empleado_id', '=', 'control_tiempo.empleado_asignado_id')
        ->select('c.*')
        ->where('control_tiempo.empleado_asignado_id',$vs->empleado_asignado_id)
        ->orderBy('id', 'desc')->get();
        if (count($contratos) > 0) {
          $sueldos = DB::table('sueldos')->where('contrato_id', $contratos[0]->id)->orderBy('id', 'desc')->get();
          if (count($sueldos) > 0) {
            $total_s += $sueldos[0]->sueldo_diario_real;
          }else {
            $total_s += 0;
          }
        }else {
          $total_s += 0;
        }
        $f [] = $total_s;
      }
      $apariciones []=array_sum($f);
    }
    return $apariciones;
  }

  public function sueldos_contratos($fechauno,$fechados)
  {
      $arreglo_fechas_buscar = [];
      $fechaInicio=strtotime($fechauno);
      $fechaFin=strtotime($fechados);
      for($i=$fechaInicio; $i<=($fechaFin+86400); $i+=86400){
      $arreglo_fechas_buscar [] = date('Y-m',$i);
      }
      $fechas = array_values(array_unique($arreglo_fechas_buscar));
      $fechas_final = [];
      foreach ($fechas as $key => $value) {
        $fechas_final [] = substr($value,5,2);
      }
        $contratos_conserflow = Contrato::join('empleados AS e','e.id','=','contratos.empleado_id')
        ->select(DB::raw("CONCAT(e.ap_paterno,' ',e.ap_materno,' ',e.nombre) AS nombre_empleado"),
        'contratos.id','fecha_ingreso','fecha_fin','empleado_id'
        )->where('tipo_nomina_id','2')->where('contratos.condicion','1')->orderBy('empleado_id')->get();
        $contratos_sueldos = [];
        $total_conserflow = 0;
        $total_ema_final = 0;
        $total_eba_final = 0;
        $total_ema_final_c_csct = 0;
        $total_eba_final_c_csct = 0;
        foreach ($contratos_conserflow as $key => $value) {
          $sueldos_conserflow = Sueldo::where('contrato_id',$value->id)->orderBy('id', 'desc')->get();
          $contratos_sueldos [] =[$value,$sueldos_conserflow];
            if (count($sueldos_conserflow) > 0) {
              $total_conserflow += $sueldos_conserflow[0]->sueldo_diario_real;
            }else {
              $total_conserflow += 0;
            }

            $ema = \App\CoutaImssEMA::where('nombre',$value->nombre_empleado)
            ->whereIn('mes',$fechas_final)
            ->where('anio',date('Y'))
            ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
            ->get();
            if (count($ema) > 0) {
              if ($ema[0]->subtotal != 0 ) {
                $total_ema_dia =  $ema[0]->subtotal/$ema[0]->dias;
                $total_ema_final += $total_ema_dia;
              }
            }
            $ema_c_csct = \App\CoutaImssEMACSCT::where('nombre',$value->nombre_empleado)
            ->whereIn('mes',$fechas_final)
            ->where('anio',date('Y'))
            ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
            ->get();
            if (count($ema_c_csct) > 0) {
              if ($ema_c_csct[0]->subtotal != 0 ) {
                $total_ema_dia_c_csct =  $ema_c_csct[0]->subtotal/$ema_c_csct[0]->dias;
                $total_ema_final_c_csct += $total_ema_dia_c_csct;
              }
            }
            $eba = \App\CoutaImssEBA::where('nombre',$value->nombre_empleado)
            // ->whereIn('mes',$fechas_final)
            ->where('anio',date('Y'))
            ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
            ->get();
            if (count($eba) > 0) {
              if ($eba[0]->subtotal != 0 ) {
                $total_eba_dia =  $eba[0]->subtotal/$eba[0]->dias;
                $total_eba_final += $total_eba_dia;
              }
            }
            $eba_c_csct = \App\CoutaImssEBACSCT::where('nombre',$value->nombre_empleado)
            // ->whereIn('mes',$fechas_final)
            ->where('anio',date('Y'))
            ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
            ->get();
            if (count($eba_c_csct) > 0) {
              if ($eba_c_csct[0]->subtotal != 0 ) {
                $total_eba_dia_c_csct =  $eba_c_csct[0]->subtotal/$eba_c_csct[0]->dias;
                $total_eba_final_c_csct += $total_eba_dia_c_csct;
              }
            }

        }

        $contratos_csct = ContratoDBP::join('empleados AS e','e.id','=','contratos.empleado_id')
        ->select(DB::raw("CONCAT(e.ap_paterno,' ',e.ap_materno,' ',e.nombre) AS nombre_empleado"),
        'contratos.id','fecha_ingreso','fecha_fin','empleado_id'
        )->where('tipo_nomina_id','2')->where('contratos.condicion','1')->orderBy('empleado_id')->get();
        $contratos_sueldos_csct = [];
        $total_csct = 0;
        $total_ema_final_csct = 0;
        $total_eba_final_csct = 0;
        $total_ema_final_csct_c = 0;
        $total_eba_final_csct_c = 0;

        foreach ($contratos_csct as $key => $value) {
          $sueldos_csct = SueldoDBP::where('contrato_id',$value->id)->orderBy('id', 'desc')->get();
          $contratos_sueldos_csct [] =[$value,$sueldos_csct];
            if (count($sueldos_csct) > 0) {
              $total_csct += $sueldos_csct[0]->sueldo_diario_real;
            }else {
              $total_csct += 0;
            }
            $ema_csct = \App\CoutaImssEMA::where('nombre',$value->nombre_empleado)
            ->whereIn('mes',$fechas_final)
            ->where('anio',date('Y'))
            ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
            ->get();
            if (count($ema_csct) > 0) {
              if ($ema_csct[0]->subtotal != 0 ) {
                $total_ema_dia_csct =  $ema_csct[0]->subtotal/$ema_csct[0]->dias;
                $total_ema_final_csct += $total_ema_dia_csct;
              }
            }
            $ema_csct_c = \App\CoutaImssEMACSCT::where('nombre',$value->nombre_empleado)
            ->whereIn('mes',$fechas_final)
            ->where('anio',date('Y'))
            ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
            ->get();
            if (count($ema_csct_c) > 0) {
              if ($ema_csct_c[0]->subtotal != 0 ) {
                $total_ema_dia_csct_c =  $ema_csct_c[0]->subtotal/$ema_csct_c[0]->dias;
                $total_ema_final_csct_c += $total_ema_dia_csct_c;
              }
            }
            $eba_csct = \App\CoutaImssEBA::where('nombre',$value->nombre_empleado)
            // ->whereIn('mes',$fechas_final)
            ->where('anio',date('Y'))
            ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
            ->get();
            if (count($eba_csct) > 0) {
              if ($eba_csct[0]->subtotal != 0 ) {
                $total_eba_dia_csct =  $eba_csct[0]->subtotal/$eba_csct[0]->dias;
                $total_eba_final_csct += $total_eba_dia_csct;
              }
            }
            $eba_csct_c = \App\CoutaImssEBACSCT::where('nombre',$value->nombre_empleado)
            // ->whereIn('mes',$fechas_final)
            ->where('anio',date('Y'))
            ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
            ->get();
            if (count($eba_csct_c) > 0) {
              if ($eba_csct_c[0]->subtotal != 0 ) {
                $total_eba_dia_csct_c =  $eba_csct_c[0]->subtotal/$eba_csct_c[0]->dias;
                $total_eba_final_csct_c += $total_eba_dia_csct_c;
              }
            }
        }
        $total_sueldos = ($total_conserflow + $total_ema_final + $total_eba_final + $total_ema_final_c_csct + $total_eba_final_c_csct) + ($total_csct + $total_ema_final_csct + $total_eba_final_csct + $total_eba_final_csct_c + $total_ema_final_csct_c);
        return $total_sueldos * 7;
  }

  public function original($value='')
  {

        $valores= explode('&',$this->id);
        $fechauno = $valores[0];
        $fechados = $valores[1];

        $fechas = [];
        $fechas_siete = [];
        $fechaInicio=strtotime($fechauno);
        $fechaFin=strtotime($fechados);
        // for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
        //  $fechas [] = date("d-m-Y", $i);
        // }
        for($i=$fechaInicio; $i<=$fechaFin; $i+=604800){
          $fechas_siete [] = date("Y-m-d", $i);
        }
        for($i=($fechaInicio +518400); $i<=($fechaFin+604800); $i+=604800){
          $fechas_n [] = date("Y-m-d", $i);
        }
        // return response()->json(count($fechas_n));

        $a = [];
        $user = Auth::user();
        $tam = count($fechas_siete);
        $proyectos = DB::table('control_tiempo')
        ->leftJoin('proyectos AS p','p.id','=','control_tiempo.proyecto_id')
        ->whereBetween('fecha',[$fechauno, $fechados])
        // ->where('control_tiempo.supervisor_id',$user->empleado_id)
        ->select('control_tiempo.proyecto_id','p.nombre_corto')
        ->groupBy('control_tiempo.proyecto_id')
        ->get();

        $arreglo_proyectos = [];
        foreach ($proyectos as $key => $proyecto) {
          $empleados_p = DB::table('control_tiempo')
          ->whereBetween('fecha',[$fechauno, $fechados])
          // ->where('control_tiempo.supervisor_id',$user->empleado_id)
          ->where('proyecto_id',$proyecto->proyecto_id)->get();

          $total = 0;

          foreach ($empleados_p as $key => $empleado) {
            $contratos = \App\Contrato::where('empleado_id',$empleado->empleado_asignado_id)->orderBy('id', 'desc')->get();
            if (count($contratos) > 0) {
              $sueldos = DB::table('sueldos')->where('contrato_id', $contratos[0]['id'])->orderBy('id', 'desc')->get();
              if (count($sueldos) > 0) {
                $total += $sueldos[0]->sueldo_diario_real;
              }
              $total += 0;
            }else {
              $total += 0;
            }
          }
          $arreglo_proyectos [] =
          [
            'proyecto' => $proyecto,
            'total' => $total,
          ];
        }
        $emplados = DB::table('control_tiempo')
        ->whereBetween('fecha',[$fechauno, $fechados])
        // ->where('supervisor_id',$user->empleado_id)
        ->select('empleado_asignado_id')
        ->groupBy('empleado_asignado_id')
        ->get();

        for ($h=0; $h < $tam ; $h++) {
          $arreglo =[];


          $emplado_supervisor = DB::table('empleados AS e')->
          select(DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_nombre"))
          ->where('id',$user->empleado_id)
          ->first();

          $arreglo_fechas_mostrar = [];
          $arreglo_fechas_buscar = [];
          $fechaInicio=strtotime($fechauno);
          $fechaFin=strtotime($fechados);

          for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
            $arreglo_fechas [] = date("d-M-yy",$i);
            $arreglo_fechas_buscar [] = date('Y-m-d',$i);
            // $arreglo_dias [] = $this->get_nombre_dia(date('Y-m-d',$i));
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


          //
        }

        $proyectos = DB::table('control_tiempo')
        ->leftJoin('proyectos AS p','p.id','=','control_tiempo.proyecto_id')
        ->whereBetween('fecha',[$fechauno, $fechados])
        ->select('control_tiempo.proyecto_id','p.nombre_corto')
        ->groupBy('proyecto_id')
        ->get();
        $arreglos_final = [];
        foreach ($proyectos as $key => $proyecto) {
          $apariciones = [];

          $total_s = 0;
          foreach ($a as $key => $value) {
            $empleados_tiempo = DB::table('control_tiempo')
            ->whereBetween('fecha',[$value['i'], $value['f']])
            ->where('proyecto_id',$proyecto->proyecto_id)
            ->select('empleado_asignado_id','fecha')
            ->get();
            $f = [];
            foreach ($empleados_tiempo as $key => $vs) {
              $total_s = 0;

              $empleado_ = \App\Empleado::where('id',$vs->empleado_asignado_id)
              ->select(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre) AS empleado_imss"))->first();

              $SueldoSemanaEmpleado = \App\SueldoSemanaEmpleado::where('nombre',$empleado_->empleado_imss)->get();

              foreach ($SueldoSemanaEmpleado as $key => $value_s) {
                $SueldoSemanaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::where('fecha_i',$vs->fecha)
                ->whereIn('sueldo_semana_empleado_id',$value_s)
                ->first();

                if (isset($SueldoSemanaEmpleadoRegistros) == true) {
                  $total_s += $SueldoSemanaEmpleadoRegistros->total;
                }
              }
              // $contratos = DB::table('control_tiempo')
              // ->leftJoin('contratos AS c', 'c.empleado_id', '=', 'control_tiempo.empleado_asignado_id')
              // ->select('c.*')
              // // ->whereBetween('control_tiempo.fecha',[$value['i'], $value['f']])
              // ->where('control_tiempo.empleado_asignado_id',$vs->empleado_asignado_id)
              // ->orderBy('id', 'desc')->get();
              // if (count($contratos) > 0) {
              //   $sueldos = DB::table('sueldos')->where('contrato_id', $contratos[0]->id)->orderBy('id', 'desc')->get();
              //   if (count($sueldos) > 0) {
              //     $total_s += $sueldos[0]->sueldo_diario_real;
              //   }else {
              //     $total_s += 0;
              //   }
              // }else {
              //   $total_s += 0;
              // }

              $f [] = $total_s;
            }



            $apariciones []=array_sum($f);
            // $apariciones []= $total_s * count($empleados_tiempo);
          }

          $arreglos_final [] = [
            'proyectos' => $proyecto,
            'apa' => $apariciones,
          ];
        }

        $arreglos_final_data = $this->arreglos_final_d($a);

        $sueldos_contratos = $this->sueldos_contratos($fechauno,$fechados);
  }
}
