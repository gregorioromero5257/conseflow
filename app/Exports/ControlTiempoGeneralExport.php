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

class ControlTiempoGeneralExport implements FromView, ShouldAutoSize, WithEvents
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

    $empleados_tiempo = DB::table('control_tiempo')
    ->join('empleados AS e','e.id','=','control_tiempo.empleado_asignado_id')
    ->whereBetween('fecha',[$fechauno, $fechados])
    ->select('empleado_asignado_id',DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_nombre"))
    ->groupBy('empleado_asignado_id')
    ->get();
    $arreglos_final = [];
    foreach ($empleados_tiempo as $key => $employe) {
      $apariciones = [];

      $total_s = 0;
      foreach ($a as $key => $value) {
        $empleados_tiempo = DB::table('control_tiempo')
        ->whereBetween('fecha',[$value['i'], $value['f']])
        ->where('empleado_asignado_id',$employe->empleado_asignado_id)
        ->select('empleado_asignado_id')
        ->get();

        $contratos = DB::table('control_tiempo')
         ->leftJoin('contratos AS c', 'c.empleado_id', '=', 'control_tiempo.empleado_asignado_id')
         ->select('c.*')
         ->whereBetween('control_tiempo.fecha',[$value['i'], $value['f']])
         ->where('control_tiempo.empleado_asignado_id',$employe->empleado_asignado_id)
         ->orderBy('id', 'desc')->get();
         if (count($contratos) > 0) {
           $sueldos = DB::table('sueldos')->where('contrato_id', $contratos[0]->id)->orderBy('id', 'desc')->get();
           if (count($sueldos) > 0) {
             $total_s = $sueldos[0]->sueldo_diario_real;
           }else {
             $total_s = 0;
           }
         }else {
           $total_s = 0;
         }

        $apariciones []= $total_s * count($empleados_tiempo);
      }
      $arreglos_final [] = [
        'empleados' => $employe,
        'apa' => $apariciones,
      ];
    }



    return view('excel.controltiempogeneral', compact('arreglo_proyectos','arreglos_final','a'));
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
