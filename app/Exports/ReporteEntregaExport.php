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



class ReporteEntregaExport implements FromView, ShouldAutoSize,WithEvents
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


    //obtener empleado con el tipo de reporte_estado

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
          'color' => ['argb' => 'FFFFFF'],
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
        // $event->sheet->getStyle('A1:AA2')->applyFromArray($styleArray[1]);//se lllama el array de la posicion 0
        // $event->sheet->getActiveSheet(0)->freezePaneByColumnAndRow(0,6); // donde 0 es la columna desde donde se congela y 4 es la fila hasta donde se congela.

      },
    ];
  }



  public function view(): View
  {
    $valores= explode('&',$this->id);
    $fecha_inicial = $valores[0];
    $fecha_final = $valores[1];

    $data = DB::table('control_cb')
    ->join('empleados AS e','e.id','control_cb.empleado_id')
    ->join('empleados AS ea','ea.id','control_cb.autoriza_id')
    ->select('control_cb.*',
    DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado"),
    DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS autoriza")
    )
    ->whereBetween('fecha',$valores)->get();

    return view('excel.reporteentrega', compact('data'));
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
