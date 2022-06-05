<?php

namespace App\Exports;

use App\Existencia;
use App\Proyecto;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class HistoricoValeEppExport implements FromView, WithEvents
{
  protected $id;


  public function __construct(int $id)
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
      ],
      [
          'borders' => [
              'allBorders' => [
                  'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                  'color' => ['argb' => '000000'],
              ],
          ],
      ],
      [
          'alignment' =>
          [
              'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          ],
      ],

      /****fin la posicion 1****/
      /*
      [   ]
      puedes crear mas posiciones aqui en el array y llamarlos en el AfterSheet
      */
    ];

    return [
      //todos los estilos los cargas aqui
      AfterSheet::class => function (AfterSheet $event) use ($styleArray) {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('img/conserflow.png'));
        $drawing->setCoordinates('A2');
        $drawing->setHeight(60);
        $drawing->setWorksheet($event->sheet->getDelegate());


        // $event->sheet->getDelegate()->getRowDimension(6)->setRowHeight(30);

        $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(5);
        $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(60);
        $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(30);
        $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(30);
        $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);

        $event->sheet->getStyle('A1:I4')->applyFromArray($styleArray[5]); // Border
        $event->sheet->getStyle('E6:I6')->applyFromArray($styleArray[5]); // Color
        // $event->sheet->getStyle('A7:H7')->applyFromArray($styleArray[5]); // Color
        // $event->sheet->getStyle('A7:N7')->applyFromArray($styleArray[1]);//se lllama el array de la posicion 0
        $event->sheet->getStyle('A1')->applyFromArray($styleArray[0]);//se lllama el array de la posicion 0
        $event->sheet->getStyle('A1')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        $event->sheet->getStyle('B2')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        $event->sheet->getStyle('G2')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        $event->sheet->getStyle('H2')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        $event->sheet->getStyle('G3')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        $event->sheet->getStyle('H3')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        $event->sheet->getStyle('H4')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        $event->sheet->getStyle('G4')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6

        // $event->sheet->setBorder('A1:F10', 'thin');

      },
    ];
  }


  public function view(): View
  {

      if ($this->id == 0) {
          $articulos_epp =  DB::table('partidas_vale_epp AS pve')
          ->join('empleados_vale_epp AS evp','evp.id','pve.empleado_vale_id')
          ->join('empleados AS e','e.id','evp.empleado_id')
          ->join('empleados AS ea','ea.id','pve.autoriza_id')
          ->join('articulos AS a','a.id','pve.articulo_id')
          ->select(
              'pve.*','a.descripcion',
              DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleador"),
              DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleadoa")
              )
          ->get();
      }else {
          $articulos_epp =  DB::table('partidas_vale_epp AS pve')
          ->join('empleados_vale_epp AS evp','evp.id','pve.empleado_vale_id')
          ->join('empleados AS e','e.id','evp.empleado_id')
          ->join('empleados AS ea','ea.id','pve.autoriza_id')
          ->join('articulos AS a','a.id','pve.articulo_id')
          ->select(
              'pve.*','a.descripcion',
              DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleador"),
              DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleadoa")
              )
              ->where('pve.articulo_id',$this->id)
              ->get();
      }

    return view('excel.historicoepp', compact('articulos_epp'));
  }

}
