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
// use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
// use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
// use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class ControlTiempoExport implements FromView, ShouldAutoSize, WithEvents
// WithStrictNullComparison
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
        // set dropdown column
              $drop_column = 'A';

              // set dropdown options
              $options = [
                  'option 1',
                  'option 2',
                  'option 3',
              ];

              // set dropdown list for first data row
              // $validation = $event->sheet->getCell("{$drop_column}7")->getDataValidation();
              // $validation->setType(DataValidation::TYPE_LIST );
              // $validation->setErrorStyle(DataValidation::STYLE_INFORMATION );
              // $validation->setAllowBlank(false);
              // $validation->setShowInputMessage(true);
              // $validation->setShowErrorMessage(true);
              // $validation->setShowDropDown(true);
              // $validation->setErrorTitle('Input error');
              // $validation->setError('Value is not in list.');
              // $validation->setPromptTitle('Pick from list');
              // $validation->setPrompt('Please pick a value from the drop-down list.');
              // $validation->setFormula1(sprintf('"%s"',implode(',',$options)));

        // $event->sheet->getStyle('A1:B1')->applyFromArray($styleArray[2]);
        // $event->sheet->getStyle('')->getFill()->setFillType('solid')->getStartColor()->setARGB('000000');

        $event->sheet->getStyle('A2:H2')->applyFromArray($styleArray[1]);//se llama el array de la posicion 0
        $event->sheet->getStyle('A3:H3')->applyFromArray($styleArray[1]);//se llama el array de la posicion 0
        $event->sheet->getStyle('A4:I4')->applyFromArray($styleArray[1]);//se llama el array de la posicion 0
        $event->sheet->setCellValue('D6','=SUM(B6:C6)');
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
    $semana_nim = $valores[0];
    $anio_nim = $valores[1];
    $semana = $valores[0];
    // $user = Auth::user();

    $a = $this->getInicioFinSemana($semana_nim,$anio_nim);

    $fechauno=($a['inicio']);
    $fechados=($a['fin']);











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
    $fechas = array_values(array_unique($arreglo_fechas_buscar));
    $fechas_final = [];
    foreach ($fechas as $key => $value) {
      $fechas_final [] = substr($value,5,2);
    }

    $SSER = \App\SueldoSemanaEmpleadoRegistros::
    join('sueldo_semana_empleado AS sse','sse.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
    ->whereBetween('sueldo_semana_empleado_registros.fecha_i',[$fechauno, $fechados])
    ->select('sse.nombre')
    ->groupBy('sse.nombre')
    ->get();


    $arreglo_sin_p = [];
    //
    // $fechaInicio=strtotime($fechauno);
    // $fechaFin=strtotime($fechados);
    //
    // for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
    //   $arreglo_fechas_buscar [] = date('Y-m-d',$i);
    // }
    $total_sueldos_sin_proyecto = 0;
    $total_sueldos_sin_proyecto_eba = 0;
    $total_sueldos_sin_proyecto_ema = 0;
    foreach ($SSER as $key => $sser_) {

      $sueldo_periodo = \App\SueldoSemanaEmpleadoRegistros::
        join('sueldo_semana_empleado AS sse','sse.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
      ->whereBetween('sueldo_semana_empleado_registros.fecha_i',[$fechauno, $fechados])
      ->where('nombre',$sser_->nombre)
      ->select(DB::raw("SUM(total) AS total"))
      ->first();


      $empleado = \App\Empleado::
      where(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre)"),'LIKE','%'.trim(str_replace('  ',' ',$sser_->nombre)).'%')
      ->select('id','nombre','ap_paterno','ap_materno')
      ->first();

      $data = [];
      $contador = 0;
      $total = 0;
      $total_ema_final = 0;
      $total_ema_final_csct = 0;
      $total_eba_final = 0;
      $total_eba_final_csct = 0;
      $total_ema_sin_pro = 0;
      $total_eba_sin_pro = 0;
      foreach ($arreglo_fechas_buscar as $key => $valor_fecha) {

        if (isset($empleado) == true) {

        $ct = DB::table('control_tiempo')
        ->leftJoin('proyectos AS p','p.id','=','control_tiempo.proyecto_id')
        ->where('fecha',$valor_fecha)
        ->where('control_tiempo.empleado_asignado_id',$empleado->id)
        ->select('control_tiempo.id')
        ->first();

        $fechas_final_meses = substr($valor_fecha,5,2);

        if (isset($ct) == true) {
          if (date('l',strtotime($valor_fecha)) != 'Sunday') {
            $contador ++;
          }
          $data [] = ['fecha' => $valor_fecha, 'data' => $ct,'total' => ($sueldo_periodo->total)];
        }else {
          if (date('l',strtotime($valor_fecha)) != 'Sunday') {
            // $data [] = ['fecha' => $valor_fecha, 'data' => null,'total' => ($sueldo_periodo->total)];
            $ema = \App\CoutaImssEMA::where('nombre',$empleado->ap_paterno.' '.$empleado->ap_materno.' '.$empleado->nombre)
            ->where('mes',$fechas_final_meses)
            ->where('anio',date('Y'))
            ->select(DB::raw("SUM(subtotal) AS subtotal"),DB::raw("SUM(dias) AS dias"))
            ->first();

            if (isset($ema) == true) {
                if ($ema->subtotal != 0) {
                  $total_ema_dia =  $ema->subtotal/$ema->dias;
                  $total_ema_final = $total_ema_dia;
                }
            }

            $ema_c_csct = \App\CoutaImssEMACSCT::where('nombre',$empleado->ap_paterno.' '.$empleado->ap_materno.' '.$empleado->nombre)
            ->where('mes',$fechas_final_meses)
            ->where('anio',date('Y'))
            ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
            ->first();

            if (isset($ema_c_csct) == true) {
              if ($ema_c_csct->subtotal != 0) {
                $total_ema_dia_c_csct =  $ema_c_csct->subtotal/$ema_c_csct->dias;
                $total_ema_final_csct = $total_ema_dia_c_csct;
              }
            }

            $eba = \App\CoutaImssEBA::where('nombre',$empleado->ap_paterno.' '.$empleado->ap_materno.' '.$empleado->nombre)
            ->where('meses','like','%'.$fechas_final_meses.'%')
            ->where('anio',date('Y'))
            ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
            ->first();

            if (isset($eba) == true) {
              if ($eba->subtotal != 0 ) {
                $total_eba_dia =  $eba->subtotal/$eba->dias;
                $total_eba_final = $total_eba_dia;
              }
            }

            $eba_c_csct = \App\CoutaImssEBACSCT::where('nombre',$empleado->ap_paterno.' '.$empleado->ap_materno.' '.$empleado->nombre)
            ->where('meses','like','%'.$fechas_final_meses.'%')
            ->where('anio',date('Y'))
            ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
            ->first();
            if (isset($eba_c_csct) == true) {
              if ($eba_c_csct->subtotal != 0 ) {
                $total_eba_dia_c_csct =  $eba_c_csct->subtotal/$eba_c_csct->dias;
                $total_eba_final_csct = $total_eba_dia_c_csct;
              }
            }

            $total += $sueldo_periodo->total/6;

            $total_ema_sin_pro += $total_ema_final + $total_ema_final_csct;
            $total_eba_sin_pro += $total_eba_final + $total_eba_final_csct;

            $data [] = ['fecha' => $valor_fecha, 'data' => null,
            'total' => ($sueldo_periodo->total + $total_ema_final + $total_ema_final_csct + $total_eba_final + $total_eba_final_csct),
            ];
          }else {
            $data [] = ['fecha' => $valor_fecha, 'data' => null,'total' => null];
          }
        }
        }
      }
      if($contador <= 5){
        $arreglo_sin_p [] = ['e' => trim(str_replace('  ','',$sser_->nombre)),
        'em  ' => $empleado,'ct' => $data,'contador' => $contador,'total' => $total + $total_ema_sin_pro + $total_eba_sin_pro];
      }
      $total_sueldos_sin_proyecto += $total;
      $total_sueldos_sin_proyecto_eba += $total_eba_sin_pro;
      $total_sueldos_sin_proyecto_ema += $total_ema_sin_pro;
    }



    $proyectos_ = DB::table('control_tiempo')
    ->leftJoin('proyectos AS p','p.id','=','control_tiempo.proyecto_id')
    ->whereBetween('fecha',[$fechauno, $fechados])
    ->select('control_tiempo.proyecto_id','p.nombre_corto')
    ->groupBy('control_tiempo.proyecto_id')
    ->get();

    $arreglo_proyectos_new = [];

    foreach ($proyectos_ as $key => $proyecto) {
      $arreglo =[];

      $emplados = DB::table('control_tiempo')
      ->join('empleados AS e','e.id','=','control_tiempo.empleado_asignado_id')
      ->whereBetween('fecha',[$fechauno, $fechados])
      ->where('proyecto_id',$proyecto->proyecto_id)
      ->select('empleado_asignado_id',
      DB::raw("CONCAT(e.ap_paterno,' ',e.ap_materno,' ',e.nombre) AS nombre"))
      ->groupBy('empleado_asignado_id')
      ->get();

      foreach ($emplados as $key => $empleado) {
        $arreglo_dos = [];
        $totales_periodo = [];
        $emplados_dos = DB::table('control_tiempo')
        ->whereBetween('fecha',[$fechauno, $fechados])
        ->where('proyecto_id',$proyecto->proyecto_id)
        ->where('empleado_asignado_id',$empleado->empleado_asignado_id)
        ->select('empleado_asignado_id','fecha')
        ->orderBy('fecha')
        ->get();

        foreach ($emplados_dos as $key => $a) {

          $total_csct = 0;
          $total_ema_final_csct = 0;
          $total_eba_final_csct = 0;
          $total_ema_final_csct_c = 0;
          $total_eba_final_csct_c = 0;

          $empleado_ = \App\Empleado::where('id',$a->empleado_asignado_id)
          ->select(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre) AS empleado_imss"))->first();

          $SueldoSemanaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::
          join('sueldo_semana_empleado AS sse','sse.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
          ->where('sueldo_semana_empleado_registros.fecha_i',$a->fecha)
          ->where('sse.nombre','LIKE',$empleado_->empleado_imss.'%')
          ->select("total")
          ->first();

          $gastos_e = DB::table('gastos_e_semana_empleado_registros AS ger')
          ->join('gastos_e_semana_empleado AS gee','gee.id','=','ger.gastos_e_semana_empleado_id')
          ->where('ger.fecha_i',$a->fecha)
          ->where('gee.nombre','LIKE',$empleado_->empleado_imss.'%')
          ->select('total')
          ->first();

          $fechas_final_meses = substr($a->fecha,5,2);


          $ema_csct = \App\CoutaImssEMA::where('nombre',$empleado_->empleado_imss)
          ->whereIn('mes',$fechas_final)
          ->where('anio',date('Y'))
          ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
          ->get();
          if (count($ema_csct) > 0) {
            if ($ema_csct[0]->subtotal != 0 ) {
              $total_ema_dia_csct =  $ema_csct[0]->subtotal/$ema_csct[0]->dias;
              $total_ema_final_csct = $total_ema_dia_csct;
            }
          }
          $ema_csct_c = \App\CoutaImssEMACSCT::where('nombre',$empleado_->empleado_imss)
          ->whereIn('mes',$fechas_final)
          ->where('anio',date('Y'))
          ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
          ->get();
          if (count($ema_csct_c) > 0) {
            if ($ema_csct_c[0]->subtotal != 0 ) {
              $total_ema_dia_csct_c =  $ema_csct_c[0]->subtotal/$ema_csct_c[0]->dias;
              $total_ema_final_csct_c = $total_ema_dia_csct_c;
            }
          }
          $eba_csct = \App\CoutaImssEBA::where('nombre',$empleado_->empleado_imss)
          ->where('meses','like','%'.$fechas_final_meses.'%')
          ->where('anio',date('Y'))
          ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
          ->get();
          if (count($eba_csct) > 0) {
            if ($eba_csct[0]->subtotal != 0 ) {
              $total_eba_dia_csct =  $eba_csct[0]->subtotal/$eba_csct[0]->dias;
              $total_eba_final_csct = $total_eba_dia_csct;
            }
          }
          $eba_csct_c = \App\CoutaImssEBACSCT::where('nombre',$empleado_->empleado_imss)
          ->where('meses','like','%'.$fechas_final_meses.'%')
          ->where('anio',date('Y'))
          ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
          ->get();
          if (count($eba_csct_c) > 0) {
            if ($eba_csct_c[0]->subtotal != 0 ) {
              $total_eba_dia_csct_c =  $eba_csct_c[0]->subtotal/$eba_csct_c[0]->dias;
              $total_eba_final_csct_c = $total_eba_dia_csct_c;
            }
          }


          $total = 0;
          if (isset($SueldoSemanaEmpleadoRegistros) == true) {
            $total = ($SueldoSemanaEmpleadoRegistros->total * 7)/6;
          }
          $total_gastos_e = 0;
          if (isset($gastos_e) == true) {
            $total_gastos_e = ($gastos_e->total);
          }

          $arreglo_dos [] =
          ['fecha' => $a->fecha,
          'total' => $total == 0 ? $total : $total_gastos_e + $total + $total_ema_final_csct + $total_ema_final_csct_c + $total_eba_final_csct +$total_eba_final_csct_c,
        ];


        $totales_periodo [] = $total == 0 ? $total : $total_gastos_e + $total + $total_ema_final_csct + $total_ema_final_csct_c + $total_eba_final_csct +$total_eba_final_csct_c;

        $date_1a = new DateTime($fechauno);
        $date_2a = new DateTime($arreglo_dos[0]['fecha']);
        $diff = $date_1a->diff($date_2a);


        $f_f = (7 - count($arreglo_dos)) - $diff->days;
        $total_dia =  $total == 0 ? $total : $total_gastos_e + $total + $total_ema_final_csct + $total_ema_final_csct_c + $total_eba_final_csct +$total_eba_final_csct_c;
      }


      $arreglo [] =
      ['empleados' => $empleado->nombre,
      'registros' => $arreglo_dos,
      'd' => $diff->days,
      'f' => $f_f,
      'total_dia' => $total_dia,
      'total' => array_sum($totales_periodo),]
      ;
    }




    $arreglo_proyectos_new [] =
    [
      'proyecto' => $proyecto->nombre_corto,
      'total' => $arreglo,
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
    ->where('proyecto_id',$proyecto->proyecto_id)
    ->select('empleado_asignado_id','fecha')
    ->get();


    $total = 0;
    $total_imss = 0;
    $total_infonavit = 0;
    $subtotal = 0;

    foreach ($empleados_p as $key => $empleado) {

      $empleado_ = \App\Empleado::where('id',$empleado->empleado_asignado_id)
      ->select(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre) AS empleado_imss"))->first();
      if (isset($empleado_) == false) {
        $empleado_ =  \App\EmpleadoDBP::where('id',$empleado->empleado_asignado_id)
        ->select(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre) AS empleado_imss"))->first();
      }

      $SueldoSemanaEmpleado = \App\SueldoSemanaEmpleado::where('nombre',$empleado_->empleado_imss)->select('id','nombre')->get();

      foreach ($SueldoSemanaEmpleado as $key => $value_s) {
        $SueldoSemanaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::where('fecha_i',$empleado->fecha)
        ->whereIn('sueldo_semana_empleado_id',$value_s)
        ->select('total')
        ->first();
        $total_conserflow = 0;
        $total_ema_final = 0;
        $total_eba_final = 0;
        $total_ema_final_c_csct = 0;
        $total_eba_final_c_csct = 0;
        $total_gastos_e_ = 0;

        $fechas_final_meses_dos = substr($empleado->fecha,5,2);

        if (isset($SueldoSemanaEmpleadoRegistros) == true) {

          $gastos_e_ = DB::table('gastos_e_semana_empleado_registros AS ger')
          ->join('gastos_e_semana_empleado AS gee','gee.id','=','ger.gastos_e_semana_empleado_id')
          ->where('ger.fecha_i',$empleado->fecha)
          ->where('gee.nombre','LIKE',$value_s->nombre.'%')
          ->select('total')
          ->first();
          if (isset($gastos_e_) == true) {
            $total_gastos_e_ = $gastos_e_->total;
          }

          $ema = \App\CoutaImssEMA::where('nombre',$value_s->nombre)
          ->whereIn('mes',$fechas_final)
          ->where('anio',date('Y'))
          ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
          ->get();
          if (count($ema) > 0) {
            if ($ema[0]->subtotal != 0 ) {
              $total_ema_dia =  $ema[0]->subtotal/$ema[0]->dias;
              $total_ema_final = $total_ema_dia;
            }
          }
          $ema_c_csct = \App\CoutaImssEMACSCT::where('nombre',$value_s->nombre)
          ->whereIn('mes',$fechas_final)
          ->where('anio',date('Y'))
          ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
          ->get();
          if (count($ema_c_csct) > 0) {
            if ($ema_c_csct[0]->subtotal != 0 ) {
              $total_ema_dia_c_csct =  $ema_c_csct[0]->subtotal/$ema_c_csct[0]->dias;
              $total_ema_final_c_csct = $total_ema_dia_c_csct;
            }
          }
          $eba = \App\CoutaImssEBA::where('nombre',$value_s->nombre)
          ->where('meses','like','%'.$fechas_final_meses_dos.'%')
          ->where('anio',date('Y'))
          ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
          ->get();
          if (count($eba) > 0) {
            if ($eba[0]->subtotal != 0 ) {
              $total_eba_dia =  $eba[0]->subtotal/$eba[0]->dias;
              $total_eba_final = $total_eba_dia;
            }
          }
          $eba_c_csct = \App\CoutaImssEBACSCT::where('nombre',$value_s->nombre)
          ->where('meses','like','%'.$fechas_final_meses_dos.'%')
          ->where('anio',date('Y'))
          ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
          ->get();
          if (count($eba_c_csct) > 0) {
            if ($eba_c_csct[0]->subtotal != 0 ) {
              $total_eba_dia_c_csct =  $eba_c_csct[0]->subtotal/$eba_c_csct[0]->dias;
              $total_eba_final_c_csct = $total_eba_dia_c_csct;
            }
          }

          $total += $total_gastos_e_ + (($SueldoSemanaEmpleadoRegistros->total * 7)/6) + $total_ema_final + $total_ema_final_c_csct + $total_eba_final + $total_eba_final_c_csct;
          $total_imss +=  $total_ema_final + $total_ema_final_c_csct;
          $total_infonavit += $total_eba_final + $total_eba_final_c_csct;
          $subtotal += (($SueldoSemanaEmpleadoRegistros->total * 7)/6) + $total_gastos_e_;
        }
      }

      // $contratos = \App\Contrato::where('empleado_id',$empleado->empleado_asignado_id)->orderBy('id', 'desc')->get();
      // if (count($contratos) > 0) {
      //   $sueldos = DB::table('sueldos')->where('contrato_id', $contratos[0]['id'])->orderBy('id', 'desc')->get();
      //   if (count($sueldos) > 0) {
      //     $total += $sueldos[0]->sueldo_diario_real;
      //   }
      //   $total += 0;
      // }else {
      //   $total += 0;
      // }
    }
    $arreglo_proyectos [] =
    [
      'proyecto' => $proyecto,
      'total_imss' => $total_imss,
      'total_infonavit' => $total_infonavit,
      'subtotal' => $subtotal,
      'total' => $total,
    ];
  }

  return view('excel.controltiempo', compact('total_sueldos_sin_proyecto_ema','total_sueldos_sin_proyecto_eba','total_sueldos_sin_proyecto','arreglo_sin_p','arreglo_proyectos','arreglo_proyectos_new','arreglo','arreglo_dias','arreglo_fechas','emplado_supervisor','semana'));
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
