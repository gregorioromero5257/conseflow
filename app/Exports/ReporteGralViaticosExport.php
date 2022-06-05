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
use App\Controltiempo;
use App\Http\Controllers\SolicitudViaticosController;
use App\Repositories\Viaticos;
use Illuminate\Support\Facades\Auth;



class ReporteGralViaticosExport implements FromView, ShouldAutoSize,WithEvents
{

  protected $id;
  public function __construct(int $id)
  {
    $this->id = $id;
  }

  /**
  * @return array
  */
  public function registerEvents(): array
  {
    // $valores= explode('&',$this->id);
    // $fecha_inicial = $valores[0];
    // $fecha_final = $valores[1];
    // $tipo = $valores[2];
    //obtener empleado con el tipo de reporte_estado


    // $asistencias = Asistencia::join('registros AS r','r.id','asistencias.registro_id')
    // ->join('empleados AS e','e.id','asistencias.empleado_id')
    // ->select('r.*','asistencias.fecha','asistencias.empleado_id')
    // ->whereBetween('asistencias.fecha',[$fecha_inicial,$fecha_final])
    // ->where('r.observaciones','!=','null')
    // ->where('e.id_checador',$tipo)
    // ->get();

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
      AfterSheet::class => function (AfterSheet $event) use ($styleArray) {


        // $event->sheet->getStyle('C7:C'.$total)->applyFromArray($styleArray[3]);
        // $event->sheet->getStyle('J7:J'.$total)->applyFromArray($styleArray[3]);
        // $event->sheet->getStyle('K7:K'.$total)->applyFromArray($styleArray[3]);
        // $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(20);
        // $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(50);
        // for ($i=7; $i < 5000; $i++) {
        //   $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(50);
        // }
        // $event->sheet->getDelegate()->getRowDimension(6)->setRowHeight(50);
        // foreach ($asistencias as $key => $value) {
        //   $pos_horizontal = $this->horizontal($value);
        //   $pos_vertical = $this->vertical($value);
        //   $cordenada = $pos_horizontal.$pos_vertical;
        //
        //   $event->sheet->getDelegate()->getComment($cordenada)->getText()->createTextRun($value->observaciones);
        // }

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




  public function view(): View
  {

    $arreglo=[];
    $user = Auth::user();

    if ($this->id == 1) {////CONSERFLOW
      $controller = new SolicitudViaticosController(new Viaticos([]));
      $solicitudes=$controller->ObtenerViaticosConser($user->empleado_id);

      foreach ($solicitudes as $key => $value) {
        $beneficiario = \App\BeneficiarioViatico::
        join('empleados AS e','e.id','beneficiario_viatico.empleado_beneficiario_id')
        ->select('beneficiario_viatico.*',DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS beneficiario"))
        ->where('solicitud_viaticos_id',$value->id)->first();

        $pagos = \App\PartidasViaticosPagos::where('solicitud_viaticos_id',$value->id)->get();
        $pagos_t = \App\PartidasViaticosPagos::where('solicitud_viaticos_id',$value->id)
        ->select(DB::raw("SUM(importe_te) AS total_uno"),DB::raw("SUM(importe_efectivo) AS total_dos"))->first();
        $total_pagos = $pagos_t->total_uno + $pagos_t->total_dos;

        $comprobacion = \App\ReporteGastosViaticos::where('solicitud_viaticos_id',$value->id)->get();
        $comprobacion_t = \App\ReporteGastosViaticos::where('solicitud_viaticos_id',$value->id)
        ->select(DB::raw("SUM(total) AS total"))
        ->first();


        $detalles = \App\DetalleViatico::
        select(DB::raw("SUM(transferencia_electronica) AS transferencia"),
        DB::raw("SUM(efectivo) AS efectivo"),
        DB::raw("SUM(total) AS total"))
        ->where('solicitud_viaticos_id',$value->id)->first();

        $arreglo [] = [
          'solicitud' => $value,
          'detalle' => $detalles,
          'beneficiario' => $beneficiario,
          'pagos' => $pagos,
          'total_pago' => $total_pagos,
          'comprobacion' => $comprobacion,
          'total_comprobacion' => $comprobacion_t->total,
        ];
      }
    }elseif ($this->id == 2) {////CSCT

      $empleado_cfw = \App\Empleado::where('id',$user->empleado_id)->select('nombre','ap_paterno','ap_materno')->first();
      $empleado_csct = \App\EmpleadoDBP::where('nombre',$empleado_cfw->nombre)->where('ap_paterno',$empleado_cfw->ap_paterno)->where('ap_materno',$empleado_cfw->ap_materno)->first();

      $controller = new SolicitudViaticosController(new Viaticos([]));
      $solicitudes=$controller->ObtenerViaticosCsct($empleado_csct->id);

      foreach ($solicitudes as $key => $value) {
        $beneficiario = \App\BeneficiarioViaticoDBP::
        join('empleados AS e','e.id','beneficiario_viatico.empleado_beneficiario_id')
        ->select('beneficiario_viatico.*',DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS beneficiario"))
        ->where('solicitud_viaticos_id',$value->id)->first();

        $pagos = \App\PartidasViaticosPagosDBP::where('solicitud_viaticos_id',$value->id)->get();
        $pagos_t = \App\PartidasViaticosPagosDBP::where('solicitud_viaticos_id',$value->id)
        ->select(DB::raw("SUM(importe_te) AS total_uno"),DB::raw("SUM(importe_efectivo) AS total_dos"))->first();
        $total_pagos = $pagos_t->total_uno + $pagos_t->total_dos;

        $comprobacion = \App\ReporteGastosViaticosDBP::where('solicitud_viaticos_id',$value->id)->get();
        $comprobacion_t = \App\ReporteGastosViaticosDBP::where('solicitud_viaticos_id',$value->id)
        ->select(DB::raw("SUM(total) AS total"))
        ->first();
        $detalles = \App\DetalleViaticoCSCT::
        select(DB::raw("SUM(transferencia_electronica) AS transferencia"),
        DB::raw("SUM(efectivo) AS efectivo"),
        DB::raw("SUM(total) AS total"))
        ->where('solicitud_viaticos_id',$value->id)->first();

        $arreglo [] = [
          'solicitud' => $value,
          'detalle' => $detalles,
          'beneficiario' => $beneficiario,
          'pagos' => $pagos,
          'total_pago' => $total_pagos,
          'comprobacion' => $comprobacion,
          'total_comprobacion' => $comprobacion_t->total,
        ];
      }
    }


    $empresa = '';
    if ($this->id == 1) {
      $empresa = 'CONSERFLOW S.A. DE C.V.';
    }elseif ($this->id == 2) {
      $empresa = 'CSCT';
    }
    // $empresa = $this->id == 1 ? 'CONSERFLOW S.A. DE C.V.' : 'CSCT' ;


    return view('excel.viaticosgral', compact('arreglo','empresa'));
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

public function buscarSueldo($value)
{

  $dia = $this->getDia($value->fecha);
  $total_q = 0;
  $total_s = 0;

  if ($dia === 'Domingo') {
    $fecha = date("Y-m-d",strtotime($value->fecha."- 1 days"));

    $empleado_csct = DB::table('sueldo_quincena_empleado_registros AS sqer')
    ->join('sueldo_quincena_empleado AS sqe','sqe.id','sqer.sueldo_quincena_empleado_id')
    ->where('sqe.empleado_id',$value->empleado_asignado_id)
    ->where('sqer.fecha_i',$fecha)
    ->where('sqe.aplica','1')
    ->select('sqer.total')->first();

    $empleado_cfw = DB::table('sueldo_semana_empleado_registros AS sqer')
    ->join('sueldo_semana_empleado AS sqe','sqe.id','sqer.sueldo_semana_empleado_id')
    ->where('sqe.empleado_id',$value->empleado_asignado_id)
    ->where('sqer.fecha_i',$fecha)
    ->where('sqe.aplica','1')
    ->select('sqer.total')->first();


    if (isset($empleado_csct) == true) {
      $total_q = $empleado_csct->total;
    }
    if ($empleado_cfw) {
      $total_s = $empleado_cfw->total;
    }

    $total = $total_q + $total_s;

  }else {
    // code...
    $empleado_csct = DB::table('sueldo_quincena_empleado_registros AS sqer')
    ->join('sueldo_quincena_empleado AS sqe','sqe.id','sqer.sueldo_quincena_empleado_id')
    ->where('sqe.empleado_id',$value->empleado_asignado_id)
    ->where('sqer.fecha_i',$value->fecha)
    ->where('sqe.aplica','1')
    ->select('sqer.total')->first();

    $empleado_cfw = DB::table('sueldo_semana_empleado_registros AS sqer')
    ->join('sueldo_semana_empleado AS sqe','sqe.id','sqer.sueldo_semana_empleado_id')
    ->where('sqe.empleado_id',$value->empleado_asignado_id)
    ->where('sqer.fecha_i',$value->fecha)
    ->where('sqe.aplica','1')
    ->select('sqer.total')->first();


    if (isset($empleado_csct) == true) {
      $total_q = $empleado_csct->total;
    }
    if ($empleado_cfw) {
      $total_s = $empleado_cfw->total;
    }

    $total = $total_q + $total_s;
  }


   return $total;
}

public function getDia($fecha)
{
  $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');

  $dia = $dias[(date('N', strtotime($fecha))) - 1];

  return $dia;
}


}
