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

class ControlTiempoCompletoExport implements FromView, ShouldAutoSize, WithEvents
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
    $user = Auth::user();
    $emplados = DB::table('control_tiempo')
    ->whereBetween('fecha',[$fechauno, $fechados])
    ->where('supervisor_id',$user->empleado_id)
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

        $event->sheet->getStyle('A2:H2')->applyFromArray($styleArray[1]);//se llama el array de la posicion 0
        $event->sheet->getStyle('A3:AG3')->applyFromArray($styleArray[1]);//se llama el array de la posicion 0
        $event->sheet->getStyle('A4:AG4')->applyFromArray($styleArray[1]);//se llama el array de la posicion 0
        // $event->sheet->getStyle('A4:A'.$tam)->applyFromArray($styleArray[1]);//se llama el array de la posicion 0
        // $event->sheet->getStyle('A3:BB3')->getFill()->setFillType('solid')->getStartColor()->setARGB('e68a00');

        /*getFill()->setFillType('solid')->getStartColor()->setARGB('5882FA');*/
      },
    ];
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

  public function view(): View
  {
    $valores= explode('&',$this->id);
    $fechauno = $valores[0];
    $fechados = $valores[1];
    // $user = Auth::user();


              $fechas_siete = [];
              $fechaInicio=strtotime($fechauno);
              $fechaFin=strtotime($fechados);

          // $arreglo_fechas_mostrar = [];
          // $arreglo_fechas_buscar = [];
          // $arreglo_dias = [];
          // //
          // // $fechaInicio=strtotime($fechauno);
          // // $fechaFin=strtotime($fechados);
          // //
          // for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
          //   $arreglo_fechas [] = date("d-M-yy",$i);
          //   // $arreglo_fechas_buscar [] = date('Y-m-d',$i);
          //   $arreglo_dias [] = $this->get_nombre_dia(date('Y-m-d',$i));
          // }
          // $fechas = array_values(array_unique($arreglo_fechas_buscar));
          // $fechas_final = [];
          // foreach ($fechas as $key => $value) {
          //   $fechas_final [] = substr($value,5,2);
          // }


          ////

          $a = [];

          // for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
          //  $fechas [] = date("d-m-Y", $i);
          // }
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
            $fechaInicio_=strtotime($fechauno);
            $fechaFin_=strtotime($fechados);

            for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
              $arreglo_fechas [] = date("d-M-yy",$i);
              $arreglo_fechas_buscar [] = date('Y-m-d',$i);
            }
            $fechas = array_values(array_unique($arreglo_fechas_buscar));
            $fechas_final = [];
            foreach ($fechas as $key => $value) {
              $fechas_final [] = substr($value,5,2);
            }

            $dia   = substr($fechas_siete[$h],8,2);
            $mes = substr($fechas_siete[$h],5,2);
            $anio = substr($fechas_siete[$h],0,4);
            $semana = date('W',  mktime(0,0,0,$mes,$dia,$anio));

            $a [] = [
              'ns' => $semana,
              'i' => $fechas_siete[$h],
              'f' => $fechas_n[$h],
              'd' => $fechas_final,
            ];
          }


          $proyectos_ = DB::table('control_tiempo')
          ->leftJoin('proyectos AS p','p.id','=','control_tiempo.proyecto_id')
          ->whereBetween('fecha',[$fechauno, $fechados])
          // ->where('control_tiempo.supervisor_id',$user->empleado_id)
          ->select('control_tiempo.proyecto_id','p.nombre_corto')
          ->groupBy('control_tiempo.proyecto_id')
          ->get();
          $arreglo_dos = [];
          $arreglo_proyectos_suma = [];
          foreach ($proyectos_ as $key => $pro) {

            $emplados = DB::table('control_tiempo')
            ->join('empleados AS e','e.id','=','control_tiempo.empleado_asignado_id')
            ->whereBetween('fecha',[$fechauno, $fechados])
            ->where('proyecto_id',$pro->proyecto_id)
            ->select('empleado_asignado_id',
            DB::raw("CONCAT(e.ap_paterno,' ',e.ap_materno,' ',e.nombre) AS nombre"))
            ->groupBy('empleado_asignado_id')
            ->get();

            $arreglo_tres = [];
            $sumatoria_dos = 0;
            $sumatoria_dos_imss = 0;
            $sumatoria_dos_infonavit = 0;
            $sumatoria_dos_subtotal = 0;
            foreach ($emplados as $key => $emplado) {
              $arreglo_cuatro = [];
              $arreglo_cuatro_imss = [];
              $arreglo_cuatro_infonavit = [];
              $arreglo_cuatro_subtotal = [];
              foreach ($a as $key => $aa) {
                $empleado_dos = DB::table('control_tiempo')
                ->join('empleados AS e','e.id','=','control_tiempo.empleado_asignado_id')
                ->whereBetween('fecha',[$aa['i'], $aa['f']])
                ->where('proyecto_id',$pro->proyecto_id)
                ->where('empleado_asignado_id',$emplado->empleado_asignado_id)
                ->select('fecha',
                DB::raw("CONCAT(e.ap_paterno,' ',e.ap_materno,' ',e.nombre) AS nombre"))
                ->get();


                $sumatoria = 0;
                $sumatoria_imss = 0;
                $sumatoria_infonavit = 0;
                $sumatoria_subtotal = 0;
                foreach ($empleado_dos as $key => $empleado_dos_n) {
                  $SueldoSemanaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::
                  join('sueldo_semana_empleado AS sse','sse.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
                  ->where('sueldo_semana_empleado_registros.fecha_i',$empleado_dos_n->fecha)
                  ->where('sse.nombre','LIKE',$empleado_dos_n->nombre.'%')
                  ->select("total")
                  ->first();
                  $total_csct = 0;
                  $total_ema_final_csct = 0;
                  $total_eba_final_csct = 0;
                  $total_ema_final_csct_c = 0;
                  $total_eba_final_csct_c = 0;
                  $total_gastos_e_ = 0;


                  $gastos_e_ = DB::table('gastos_e_semana_empleado_registros AS ger')
                  ->join('gastos_e_semana_empleado AS gee','gee.id','=','ger.gastos_e_semana_empleado_id')
                  ->where('ger.fecha_i',$empleado_dos_n->fecha)
                  ->where('gee.nombre','LIKE',$empleado_dos_n->nombre.'%')
                  ->select('total')
                  ->first();
                  if (isset($gastos_e_) == true) {
                    $total_gastos_e_ = $gastos_e_->total;
                  }


                  $ema_csct = \App\CoutaImssEMA::where('nombre',$empleado_dos_n->nombre)
                  ->whereIn('mes',$aa['d'])
                  ->where('anio',date('Y'))
                  ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
                  ->get();
                  if (count($ema_csct) > 0) {
                    if ($ema_csct[0]->subtotal != 0 ) {
                      $total_ema_dia_csct =  $ema_csct[0]->subtotal/$ema_csct[0]->dias;
                      $total_ema_final_csct = $total_ema_dia_csct;
                    }
                  }
                  $ema_csct_c = \App\CoutaImssEMACSCT::where('nombre',$empleado_dos_n->nombre)
                  ->whereIn('mes',$aa['d'])
                  ->where('anio',date('Y'))
                  ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
                  ->get();
                  if (count($ema_csct_c) > 0) {
                    if ($ema_csct_c[0]->subtotal != 0 ) {
                      $total_ema_dia_csct_c =  $ema_csct_c[0]->subtotal/$ema_csct_c[0]->dias;
                      $total_ema_final_csct_c = $total_ema_dia_csct_c;
                    }
                  }
                  $eba_csct = \App\CoutaImssEBA::where('nombre',$empleado_dos_n->nombre)
                  // ->whereIn('mes',$fechas_final)
                  ->where('anio',date('Y'))
                  ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
                  ->get();
                  if (count($eba_csct) > 0) {
                    if ($eba_csct[0]->subtotal != 0 ) {
                      $total_eba_dia_csct =  $eba_csct[0]->subtotal/$eba_csct[0]->dias;
                      $total_eba_final_csct = $total_eba_dia_csct;
                    }
                  }
                  $eba_csct_c = \App\CoutaImssEBACSCT::where('nombre',$empleado_dos_n->nombre)
                  // ->whereIn('mes',$fechas_final)
                  ->where('anio',date('Y'))
                  ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
                  ->get();
                  if (count($eba_csct_c) > 0) {
                    if ($eba_csct_c[0]->subtotal != 0 ) {
                      $total_eba_dia_csct_c =  $eba_csct_c[0]->subtotal/$eba_csct_c[0]->dias;
                      $total_eba_final_csct_c = $total_eba_dia_csct_c;
                    }
                  }



                  $sumatoria += isset($SueldoSemanaEmpleadoRegistros) == true ? (($SueldoSemanaEmpleadoRegistros->total * 7)/6) + $total_gastos_e_ +$total_ema_final_csct + $total_ema_final_csct_c + $total_eba_final_csct + $total_eba_final_csct_c  : 0;
                  $sumatoria_imss += $total_ema_final_csct + $total_ema_final_csct_c;
                  $sumatoria_infonavit += $total_eba_final_csct + $total_eba_final_csct_c;
                  $sumatoria_subtotal += isset($SueldoSemanaEmpleadoRegistros) == true ? (($SueldoSemanaEmpleadoRegistros->total * 7)/6) + $total_gastos_e_: 0;
                }


                $arreglo_cuatro [] = $sumatoria;
                $arreglo_cuatro_imss [] = $sumatoria_imss;
                $arreglo_cuatro_infonavit [] = $sumatoria_infonavit;
                $arreglo_cuatro_subtotal [] = $sumatoria_subtotal;
              }
              $arreglo_tres [] = ['nombre' => $emplado->nombre,'datos' => $arreglo_cuatro,'total' => array_sum($arreglo_cuatro)];
              $sumatoria_dos += array_sum($arreglo_cuatro);
              $sumatoria_dos_imss += array_sum($arreglo_cuatro_imss);
              $sumatoria_dos_infonavit += array_sum($arreglo_cuatro_infonavit);
              $sumatoria_dos_subtotal += array_sum($arreglo_cuatro_subtotal);

            }
            // $arreglo_tres = [];
            // foreach ($a as $key => $as) {
            //     $emplados = DB::table('control_tiempo')
            //     ->join('empleados AS e','e.id','=','control_tiempo.empleado_asignado_id')
            //     ->whereBetween('fecha',[$as['i'], $as['f']])
            //     ->where('proyecto_id',$pro->proyecto_id)
            //     ->select('empleado_asignado_id',
            //     DB::raw("CONCAT(e.ap_paterno,' ',e.ap_materno,' ',e.nombre) AS nombre"))
            //     ->groupBy('empleado_asignado_id')
            //     ->get();
            //     // $arreglo_cuatro = [];
            //     // foreach ($emplados as $key => $e) {
            //     //   $arreglo_cuatro [] = $e;
            //     // }
            //     $arreglo_tres [] = ['uno' => $emplados];
            // }
            $arreglo_dos [] = ['proyecto' => $pro,'empleados' => $arreglo_tres];
            $arreglo_proyectos_suma  [] = ['proyecto' => $pro,
            'total_imss' => $sumatoria_dos_imss,
            'total_infonavit' => $sumatoria_dos_infonavit,
            'subtotal' => $sumatoria_dos_subtotal,
            'total' => $sumatoria_dos];
            // $arreglo_proyectos_suma  [] = ['proyecto' => $pro, 'total' => $sumatoria];

          }
          ///







    return view('excel.controltiempocompleto', compact('arreglo_proyectos_suma','a','arreglo_proyectos_suma','arreglo_dos','arreglo','arreglo_dias','arreglo_fechas','emplado_supervisor','semana'));
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
}
