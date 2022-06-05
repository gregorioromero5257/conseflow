<?php
namespace App\Exports;

use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use \Maatwebsite\Excel\Sheet;
use App\Compras;
use App\requisicionhasordencompras;
use App\Partidas;
use App\FacturasEntradas;
use App\PagoCompra;


class EstatusMaterialExport implements FromView
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
    //
    // return [
    //   //todos los estilos los cargas aqui
    //   AfterSheet::class => function (AfterSheet $event) use ($styleArray) {
    //     $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    //     $drawing->setName('Logo');
    //     $drawing->setDescription('Logo');
    //     $drawing->setPath(public_path('img/conserflow.png'));
    //     $drawing->setCoordinates('B2');
    //     $drawing->setHeight(60);
    //     $drawing->setWorksheet($event->sheet->getDelegate());
    //
    //     // $event->sheet->getStyle('C7:C'.$total)->applyFromArray($styleArray[3]);
    //     // $event->sheet->getStyle('J7:J'.$total)->applyFromArray($styleArray[3]);
    //     // $event->sheet->getStyle('K7:K'.$total)->applyFromArray($styleArray[3]);
    //     $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(20);
    //     $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(50);
    //     for ($i=7; $i < 5000; $i++) {
    //       $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(50);
    //     }
    //     $event->sheet->getDelegate()->getRowDimension(6)->setRowHeight(50);
    //
    //     $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(20);
    //     $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
    //     $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(10);
    //     $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(12);
    //     $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(10);
    //     $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(10);
    //     $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(20);
    //     $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(50);
    //     $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(20);
    //     $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(20);
    //     $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(15);
    //     $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(10);
    //     $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(20);
    //     $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(20);
    //     $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(20);
    //     $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(20);
    //     $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(20);
    //     $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(20);
    //     // $event->sheet->getStyle('A5:S5')->applyFromArray($styleArray[1]);//se lllama el array de la posicion 0
    //     $event->sheet->getStyle('A6:S6')->applyFromArray($styleArray[1]);//se lllama el array de la posicion 0
    //     // $event->sheet->getActiveSheet(0)->freezePaneByColumnAndRow(0,6); // donde 0 es la columna desde donde se congela y 4 es la fila hasta donde se congela.
    //
    //   },
    // ];
  }

  public function tiempoentrega($data)
  {

  }

  public function view(): View
  {

    $valores= explode('&',$this->id);

    $fechauno = $valores[0];
    $fechados = $valores[1];

    $fechaInicio=strtotime($fechauno);
    $fechaFin=strtotime($fechados);
    $a = [];
    for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
      $a [] = date("Y-m-d", $i);
    }



    $empleados = DB::table('sueldo_semana_empleado AS sse')
    ->join('sueldo_semana_empleado_registros AS ssr','ssr.sueldo_semana_empleado_id','sse.id')
    ->whereBetween('ssr.fecha_i',[$fechauno,$fechados])
    ->select('nombre','id')
    ->groupBy('nombre','id')
    ->get();

    $arreglo = [];
    foreach ($empleados as $key_e => $value_e) {
      $reg = [];
      foreach ($a as $k => $v) {
        $r = DB::table('sueldo_semana_empleado_registros')
        ->where('sueldo_semana_empleado_id',$value_e->id)
        ->where('fecha_i',$v)
        ->where('marca','0')
        ->first();


        if (isset($r) == true) {
          $c= $r->total;
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


    return view('excel.reporte', compact('arreglo','a'));
  }
}
