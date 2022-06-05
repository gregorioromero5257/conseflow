<?php
namespace App\Exports;

use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use \App\Http\Helpers\Utilidades;
use App\Asistencia;
use App\Empleado;



class RHExport implements FromView, ShouldAutoSize,WithEvents
{
  protected $id;

  public function __construct(string $id)
  {
    $this->id = $id;
  }


  /**
  * @return array
  */
  public function registerEvents(): array
  {
    $valores= explode('&',$this->id);
    $fecha_inicial = $valores[0];
    $fecha_final = $valores[1];
    $tipo = $valores[2];
    //obtener empleado con el tipo de reporte_estado


    $asistencias = Asistencia::join('registros AS r','r.id','asistencias.registro_id')
    ->join('empleados AS e','e.id','asistencias.empleado_id')
    ->select('r.*','asistencias.fecha','asistencias.empleado_id')
    ->whereBetween('asistencias.fecha',[$fecha_inicial,$fecha_final])
    ->where('r.observaciones','!=','null')
    ->where('e.id_checador',$tipo)
    ->get();

    $styleArray = [
      /****Inicia la posicion 0****/
      [
        'font' => [
          'bold'       =>  true,
          'color' => ['argb' => '0489B1'],
        ],
      ],
      /****fin la posicion 0****/
      /****Inicia la posicion 1****/
      [
        'font' => [
          'bold'       =>  true,
          'color' => ['argb' => '000000'],
        ],
      ],
      [
        'font' => [
          'bold'       =>  true,
          'color' => ['argb' => '08298A'],
        ],
      ],
      [
        'alignment' =>
        [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
        ],
      ]
      /****fin la posicion 1****/
      /*
      [   ]
      puedes crear mas posiciones aqui en el array y llamarlos en el AfterSheet
      */
    ];

    return [
      //todos los estilos los cargas aqui
      AfterSheet::class => function (AfterSheet $event) use ($styleArray,$asistencias) {


        // $event->sheet->getStyle('C7:C'.$total)->applyFromArray($styleArray[3]);
        // $event->sheet->getStyle('J7:J'.$total)->applyFromArray($styleArray[3]);
        // $event->sheet->getStyle('K7:K'.$total)->applyFromArray($styleArray[3]);
        // $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(20);
        // $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(50);
        // for ($i=7; $i < 5000; $i++) {
        //   $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(50);
        // }
        // $event->sheet->getDelegate()->getRowDimension(6)->setRowHeight(50);
        foreach ($asistencias as $key => $value) {
          $pos_horizontal = $this->horizontal($value);
          $pos_vertical = $this->vertical($value);
          $cordenada = $pos_horizontal.$pos_vertical;

          $event->sheet->getDelegate()->getComment($cordenada)->getText()->createTextRun($value->observaciones);
        }

        // $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(20);
        // $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
        // $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(10);
        // $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(12);
        // $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(10);
        // $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(10);
        // $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(20);
        // $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(50);
        // $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(20);
        // $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(20);
        // $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(15);
        // $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(10);
        // $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(20);
        // $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(20);
        // $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(20);
        // $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(20);
        // $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(20);
        // $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(20);
        $event->sheet->getStyle('A1:AA1')->applyFromArray($styleArray[1]);//se lllama el array de la posicion 0
        $event->sheet->getStyle('A1:AA2')->applyFromArray($styleArray[1]);//se lllama el array de la posicion 0
        // $event->sheet->getActiveSheet(0)->freezePaneByColumnAndRow(0,6); // donde 0 es la columna desde donde se congela y 4 es la fila hasta donde se congela.

      },
    ];
  }


  public function horizontal($value)
  {
    $valores= explode('&',$this->id);
    $fecha_inicial = $valores[0];
    $fecha_final = $valores[1];
    $tipo = $valores[2];

    $fechaInicio=strtotime($fecha_inicial);
    $fechaFin=strtotime($fecha_final);

    for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
      $arreglo_fechas_buscar [] = date('Y-m-d',$i);
      // $arreglo_nombre_dia [] = $this->get_nombre_dia(date('Y-m-d',$i));
    }

    $array_valores = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','V','W','X','Y','Z'];

    $posicion_busqueda = array_search($value->fecha,$arreglo_fechas_buscar);

    return $array_valores[$posicion_busqueda + 2];

  }

  public function vertical($value)
  {

    $valores= explode('&',$this->id);
    $fecha_inicial = $valores[0];
    $fecha_final = $valores[1];
    $tipo = $valores[2];

    $empleados = Empleado::
    where('id_checador',$tipo)
    ->select('id')
    ->get();
    $e_num = [];
    foreach ($empleados as $key => $vae) {
      $e_num [] = $vae->id;
    }

    $posicion_busqueda = array_search($value->empleado_id,$e_num);

    $pos = 0;

    if ($posicion_busqueda == 0) {
      $pos = 3;
    }else {
      $pos = ($posicion_busqueda * 2) + 3;
    }

    return $pos;

  }

  public function view(): View
  {
    $nomina = [];
    $suma = [];
    $arreglo = [];
    $valores= explode('&',$this->id);
    $fecha_inicial = $valores[0];
    $fecha_final = $valores[1];
    $tipo = $valores[2];

    $fechaInicio=strtotime($fecha_inicial);
    $fechaFin=strtotime($fecha_final);

    for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
      $arreglo_fechas_buscar [] = date('Y-m-d',$i);
      $arreglo_nombre_dia [] = $this->get_nombre_dia(date('Y-m-d',$i));
    }

    $empleados = \App\RegChecador::
    join('empleados AS e','e.id','reg_checador.empleado_id')
    ->select('reg_checador.empleado_id')
    ->where('e.id_checador',$tipo)
    ->groupBy('reg_checador.empleado_id')
    ->get();


    foreach ($empleados as $key => $value) {

      $emp = DB::table('empleados AS e')
      ->join('puestos AS p','p.id','e.puesto_id')
      ->select(DB::raw("CONCAT(e.nombre,' ',ap_paterno,' ',ap_materno) AS empleado"),'p.nombre AS puesto')
      ->where('e.id',$value->empleado_id)->first();

      $asistencias_arreglo = [];

      foreach ($arreglo_fechas_buscar as $k => $v) {

        // $asistencias = Asistencia::join('registros AS r','r.id','asistencias.registro_id')
        // ->leftJoin('estado_asistencia_registros AS ear','ear.id','r.estado_asistencia_id')
        // ->select('r.*','ear.nombre AS nom')
        // ->where('asistencias.fecha',$v)
        // ->where('asistencias.empleado_id',$value->empleado_id)
        // ->orderBy('asistencias.fecha')
        // ->first();

        $asistencias = \App\RegChecador::
          where('empleado_id',$value->empleado_id)
        ->where('fecha',$v)
        ->orderBy('fecha')
        ->get();


        // if (isset($asistencias) == true) {
          $asistencias_arreglo []= $asistencias;
        // }else {
          // $asistencias_arreglo [] = '-';
        // }

      }
      if (count($asistencias_arreglo) > 0) {
        // code...
      $arreglo [] = ['empleado' => $emp,
                      'registros' => $asistencias_arreglo];
                    }
    }
    return view('excel.rhreporte', compact('arreglo_fechas_buscar','arreglo_nombre_dia','arreglo'));
  }

  public function get_nombre_dia($fecha){
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

  public function unique_multidim_array($array, $key) {
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

}
