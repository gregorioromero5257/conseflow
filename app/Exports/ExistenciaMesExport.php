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


class ExistenciaMesExport implements FromView, WithEvents
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

        $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(30);
        $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(60);
        $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);

        $event->sheet->getStyle('A1:I4')->applyFromArray($styleArray[5]); // Border
        $event->sheet->getStyle('E6:I6')->applyFromArray($styleArray[5]); // Color
        $event->sheet->getStyle('A7:H7')->applyFromArray($styleArray[5]); // Color
        $event->sheet->getStyle('A7:N7')->applyFromArray($styleArray[1]);//se lllama el array de la posicion 0
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

    $valores =explode("&",$this->id);

    $mes = $this->mes($valores[0]);
    $anio = $valores[1];

    $reporte = DB::table('partidas_entradas AS pe')
    ->join('requisicion_has_ordencompras AS rhoc','rhoc.id','pe.req_com_id')
    ->join('entradas AS e','e.id','pe.entrada_id')
    ->join('ordenes_compras AS oc','oc.id','e.orden_compra_id')
    ->join('proyectos AS p','p.id','oc.proyecto_id')
    ->join('articulos AS a','a.id','pe.articulo_id')
    ->leftjoin('grupos AS g','g.id','a.grupo_id')
    ->join('lotes AS l','l.entrada_id','pe.id')
    ->join('lote_almacen AS la','la.lote_id','l.id')
    ->select('g.nombre AS nombre_grupo', 'a.descripcion',
    'a.unidad',
    'rhoc.precio_unitario','e.fecha', 'p.nombre_corto'
    ,'pe.cantidad','e.orden_compra_id', 'oc.proyecto_id',
    'la.cantidad AS la_cantidad', 'l.cantidad AS l_cantidad',
    'la.id AS lote_almacen_id')
    ->where('e.fecha','LIKE',$valores[1].'-'.$valores[0].'%')
    ->get();

    $array = [];
    foreach ($reporte as $key => $value) {
      $salidas = DB::table('partidas AS p')
      ->where('p.lote_id',$value->lote_almacen_id)
      ->select(DB::raw("SUM(cantidad) AS cantidad"))->first();


      $array [] = [
        'a' => $value,
        'b' => $salidas->cantidad,
      ];
    }

    return view('excel.existenciasmes', compact('array','mes','anio'));
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
