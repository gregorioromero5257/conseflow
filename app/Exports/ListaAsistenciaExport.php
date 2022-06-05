<?php

namespace App\Exports;

use App\Existencia;
use App\Proyecto;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ListaAsistenciaExport implements FromView, WithEvents
{
  protected $id;


  public function __construct(string $id)
  {
    $this->id = $id;
  }

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
        // $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        // $drawing->setName('Logo');
        // $drawing->setDescription('Logo');
        // $drawing->setPath(public_path('img/conserflow.png'));
        // $drawing->setCoordinates('A2');
        // $drawing->setHeight(60);
        // $drawing->setWorksheet($event->sheet->getDelegate());


        $event->sheet->getDelegate()->getRowDimension(4)->setRowHeight(30);

        $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(35);
        $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);

        //$event->sheet->getStyle('A7:N7')->applyFromArray($styleArray[1]);//se lllama el array de la posicion 0
        $event->sheet->getStyle('A1')->applyFromArray($styleArray[0]);//se lllama el array de la posicion 0

        // $event->sheet->setBorder('A1:F10', 'thin');

      },
    ];
  }


  public function view(): View
  {

    $valores = explode('&',$this->id);
    $fecha_reporte = $valores[0];

    $asistencias = DB::table('asistencias AS a')
    ->join('registros as r','r.id','a.registro_id')
    ->join('empleados AS e','e.id','a.empleado_id')
    ->join('puestos AS p','p.id','e.puesto_id')
    ->select(DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado"), 'p.nombre', 'r.hora_entrada')
    ->where('a.fecha','=',$valores[0])
    ->where('e.id_checador',$valores[1])
    ->get();

    return view('excel.asistenciasseguridad', compact('asistencias','fecha_reporte'));
  }

  public function mes($value)
  {
    $mes = '';
    switch ($value) {
      case '01': $mes = 'Enero';break;
      case '02': $mes = 'Febrero';break;
      case '03': $mes = 'Marzo';break;
      case '04': $mes = 'Abril';break;
      case '05': $mes = 'Mayo';break;
      case '06': $mes = 'Junio';break;
      case '07': $mes = 'Julio';break;
      case '08': $mes = 'Agosto';break;
      case '09': $mes = 'Septiembre';break;
      case '10': $mes = 'Octubre';break;
      case '11': $mes = 'Noviembre';break;
      case '12': $mes = 'Diciembre';break;

    }
    return $mes;
  }
}
