<?php

namespace App\Exports;

use App\Existencia;
use App\Proyecto;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class ExistenciaSExport implements FromView, ShouldAutoSize,WithTitle,WithEvents
{
  protected $almacen_id;
  public $nombre_almacen;

  public function __construct(string $id)
  {
    $this->almacen_id = $id;
  }

  public function title():string
  {
    return "Reporte de materiales";
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
              'color' => ['argb' => '08298a'],
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
      [
          'alignment' =>
          [
              'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          ],
      ]
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

        $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(4);
        $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(30);
        $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(80);
        $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(25);
        $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(35);
        $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(12);
        $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(12);
        $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(12);

        $event->sheet->getStyle('A1:H4')->applyFromArray($styleArray[5]); // Border
        $event->sheet->getStyle('A6:H6')->applyFromArray($styleArray[5]); // Color
        $event->sheet->getStyle('A6:H6')->applyFromArray($styleArray[1]);//se lllama el array de la posicion 0
        $event->sheet->getStyle('A1')->applyFromArray($styleArray[0]);//se lllama el array de la posicion 0
        $event->sheet->getStyle('A1')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        $event->sheet->getStyle('B2')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        $event->sheet->getStyle('G2')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        $event->sheet->getStyle('H2')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        $event->sheet->getStyle('G3')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        $event->sheet->getStyle('H3')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
      },
    ];
  }

  public function view(): View
  {
    $dts=explode("&",$this->almacen_id);

    if ($dts[0] == 0)
    {
      // sin almacen
      $comparer_alm = "!=";
      $query_alm = -1;
      $comparer_cat = "=";
      $query_cat = $dts[1];
    }
    else
    {
      // sin categoria
      $comparer_alm = "=";
      $query_alm = $dts[0];
      $comparer_cat = "!=";
      $query_cat = -1;
    }

    $articulos = DB::table("lote_almacen as la")
      ->join("almacenes as al", "al.id", "la.almacene_id")
      ->join("stocks as s", "s.id", "la.stocke_id")
      ->join("proyectos as p", "p.id", "s.proyecto_id")
      ->join("articulos as ar", "la.articulo_id", "ar.id")
      ->join("grupos as g", "g.id", "ar.grupo_id")
      ->join("categorias as c", "c.id", "g.categoria_id")
      ->where("al.id", $comparer_alm, $query_alm)
      ->where("c.id", $comparer_cat, $query_cat)
      ->select(
        "p.nombre_corto as p_nombre",
        "ar.id as a_id",
        "ar.descripcion as a_desc",
        'ar.unidad',
        "al.nombre as al_nombre",
        "al.id as al_id",
        "g.nombre as grupo",
        "c.nombre as categoria",
        "la.cantidad as existencia"
      )
      ->orderBy("p_nombre")
      ->orderBy("al_nombre")
      ->orderBy("a_desc")
      ->get();

    $this->nombre_almacen ="addada";
    // $this->nombre_almacen = (count($articulos) > 0) ? $articulos[0]->al->nombre : "Sin datos";
    foreach ($articulos as $articulo)
    {
      $existencia = Existencia::where("articulo_id", $articulo->a_id)
        ->where("almacene_id", $articulo->al_id)
        ->sum("cantidad");
      $articulo->existencia = $existencia;
    }

    return view('excel.existencias', compact('articulos'));
  }
}
