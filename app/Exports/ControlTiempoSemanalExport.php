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

class ControlTiempoSemanalExport implements FromView, ShouldAutoSize, WithEvents
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
    $semana = $valores[2];
    // $user = Auth::user();

    $arreglo =[];
    $emplados = DB::table('control_tiempo')
    ->whereBetween('fecha',[$fechauno, $fechados])
    // ->where('supervisor_id',$user->empleado_id)
    ->select('empleado_asignado_id')
    ->groupBy('empleado_asignado_id')
    ->get();


    $emplado_supervisor = '';
    // DB::table('empleados AS e')->
    // select(DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_nombre"))
    // // ->where('id',$user->empleado_id)
    // ->first();

    $arreglo_fechas_mostrar = [];
    $arreglo_fechas_buscar = [];
    $arreglo_dias = [];

    $fechaInicio=strtotime($fechauno);
    $fechaFin=strtotime($fechados);

    for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
      $arreglo_fechas [] = date("d-M-yy",$i);
      $arreglo_fechas_buscar [] = date('Y-m-d',$i);
      $arreglo_dias [] = $this->get_nombre_dia(date('Y-m-d',$i));
    }

    // foreach($daterange as $date){
    //   $arreglo_fechas [] =$date->format("d-M-yy");
    //   $arreglo_fechas_buscar [] = $date->format('Y-m-d');
    //   $arreglo_dias [] = $this->get_nombre_dia($date->format('Y-m-d'));
    // }
    //
    // foreach ($emplados as $key => $empleado) {
    //   $arreglo_f = [];
    //   $emplado_nombre = DB::table('empleados AS e')->
    //   select(DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_nombre"))
    //   ->where('id',$empleado->empleado_asignado_id)
    //   ->first();
    //   for ($i=0; $i < count($arreglo_fechas_buscar); $i++) {
    //     $controltiempo = DB::table('control_tiempo')
    //     ->join('empleados as E', 'E.id', '=', 'control_tiempo.empleado_asignado_id')
    //     ->join('empleados as EE','EE.id','=','control_tiempo.supervisor_id')
    //     ->leftJoin('proyectos', 'proyectos.id', '=', 'control_tiempo.proyecto_id')
    //     ->select('control_tiempo.*',
    //     'proyectos.nombre_corto',
    //     DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS asignado_nombre"),
    //     DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS supervisor_nombre"))
    //     ->where('control_tiempo.fecha',$arreglo_fechas_buscar[$i])
    //     ->where('supervisor_id',$user->empleado_id)
    //     ->where('control_tiempo.empleado_asignado_id',$empleado->empleado_asignado_id)
    //     ->first();
    //     $arreglo_f [] = [(isset($controltiempo) == true) ? $controltiempo->nombre_corto :'-'];
    //   }
    //   $arreglo [] =[
    //     'empleado' => $emplado_nombre->empleado_nombre,
    //     'data' => $arreglo_f,
    //   ];
    // }
    //
    foreach ($emplados as $key => $empleado) {
      $arreglo_f = [];
      // $emplado_nombre = DB::table('empleados AS e')->select(DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_nombre"))->first();
      $emplado_nombre = DB::table('empleados AS e')->
      select(DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_nombre"))
      ->where('id',$empleado->empleado_asignado_id)
      ->first();
      for ($i=0; $i < count($arreglo_fechas_buscar); $i++) {
        $total_s = 0;
        $contratos = DB::table('control_tiempo')
        ->leftJoin('contratos AS c', 'c.empleado_id', '=', 'control_tiempo.empleado_asignado_id')
        ->select('c.*')
        ->where('control_tiempo.fecha',$arreglo_fechas_buscar[$i])
        ->where('control_tiempo.empleado_asignado_id',$empleado->empleado_asignado_id)
        ->orderBy('id', 'desc')->get();
        // $contratos = \App\Contrato::where('empleado_id',$empleado->empleado_asignado_id)->orderBy('id', 'desc')->get();
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
        $arreglo_f [] = [$total_s];
      }
      $arreglo [] =[
        'empleado' => $emplado_nombre->empleado_nombre,
        'data' => $arreglo_f,
      ];
    }


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

    return view('excel.controltiemposemanal', compact('arreglo_proyectos','arreglo','arreglo_dias','arreglo_fechas','emplado_supervisor','semana'));
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
