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
use Maatwebsite\Excel\Events\AfterImport;

class SalidaExport implements FromView, WithEvents
{

  protected $id;

  public function __construct(int $id)
  {
    $this->id = $id;
  }

    public function registerEvents(): array
    {

        $styleArray = [
            [
                'font' => [
                    'bold'       =>  true, #0
                    'color' => ['argb' => '0489B1'],
                ],
            ],
            [
                'font' => [
                    'bold'       =>  true,
                    'color' => ['argb' => 'FFFFFF'],
                ],
            ],
            [
                'font' => [
                    'bold'       =>  true,  #2
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
                'alignment' =>  #4
                [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
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
                'alignment' =>  #4
                [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]

        ];

        return [
            //todos los estilos los cargas aqui
            AfterSheet::class => function (AfterSheet $event) use ($styleArray)
            {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                $drawing->setPath(public_path('img/conserflow.png'));
                $drawing->setCoordinates('A2');
                $drawing->setHeight(60);
                $drawing->setWorksheet($event->sheet->getDelegate());


                $event->sheet->getDelegate()->getRowDimension(4)->setRowHeight(30);

                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);

                $event->sheet->getStyle('A1:I1')->applyFromArray($styleArray[4]);
                $event->sheet->getStyle('A1:I4')->applyFromArray($styleArray[6]);
                $event->sheet->getStyle('A1:I4')->applyFromArray($styleArray[5]); //Border
                $event->sheet->getStyle('E6:I6')->applyFromArray($styleArray[5]); //Border
               // $event->sheet->getStyle('A7:I7')->applyFromArray($styleArray[5]); //Border
              //  $event->sheet->getStyle('A7:I7')->applyFromArray($styleArray[1]); //se lllama el array de la posicion 0
                // $event->sheet->getStyle('A7:H7')->applyFromArray($styleArray[4]);
                $event->sheet->getStyle('A1')->applyFromArray($styleArray[0]); //se lllama el array de la posicion 0

                // $event->sheet->setBorder('A1:F10', 'thin');

            },
        ];
    }


    public function view(): View
    {
      $array = [];
      // Salidas taller
      $st = \App\Partidas::
      join('salidas AS s','s.id','partidas.salida_id')
      ->join('lote_almacen AS la','la.id','partidas.lote_id')
      ->join('lotes AS l','l.id','la.lote_id')
      ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
      ->join('requisicion_has_ordencompras AS rhoc','rhoc.id','pe.req_com_id')
      ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
      ->join('articulos AS a','a.id','=','la.articulo_id')
      ->join('stocks AS ss','ss.id','la.stocke_id')
      ->Join('proyectos AS p','p.id','=','ss.proyecto_id')
      ->Join('proyectos AS p2','p2.id','=','s.proyecto_id')
      ->leftJoin('proyecto_subcategorias AS ps','ps.id','=','p.proyecto_subcategorias_id')
      ->leftJoin('proyecto_categorias AS pc','pc.id','=','ps.proyecto_categoria_id')
      ->where('s.proyecto_id',$this->id)
      // ->whereIn('p.nombre',['Proyectos','Servicios'])
      // ->where('a.nombre','LIKE','%'.$request->articulo.'%')
      // ->orWhere('a.descripcion','LIKE','%'.$request->articulo.'%')
      ->where('partidas.tiposalida_id','1')
      ->select(
      'partidas.cantidad AS cantidad_salida',
      's.id as ids',
      DB::raw("('Taller') AS tipo"),
      's.folio as folio',
      'p.nombre_corto',
      "p2.nombre_corto as p_salida",
      'a.nombre AS nombre',
      'a.descripcion AS desc',
      'a.unidad as unidad',
      'oc.folio AS oc_folio')
      ->get()->toArray();

      // Salidas sitio
      $ss = \App\Partidas::
      join('salidassitio AS s','s.id','partidas.salida_id')
      ->join('lote_almacen AS la','la.id','partidas.lote_id')
      ->join('lotes AS l','l.id','la.lote_id')
      ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
      ->join('requisicion_has_ordencompras AS rhoc','rhoc.id','pe.req_com_id')
      ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
      ->join('articulos AS a','a.id','=','la.articulo_id')
      ->join('stocks AS ss','ss.id','la.stocke_id')
      ->Join('proyectos AS p','p.id','=','ss.proyecto_id')
      ->join('proyectos AS p2','p2.id','s.proyecto_id')
      ->leftJoin('proyecto_subcategorias AS ps','ps.id','=','p.proyecto_subcategorias_id')
      ->leftJoin('proyecto_categorias AS pc','pc.id','=','ps.proyecto_categoria_id')
      ->where('s.proyecto_id',$this->id)
      // ->whereIn('p.nombre',['Proyectos','Servicios'])
      // ->where('a.nombre','LIKE','%'.$request->articulo.'%')
      // ->orWhere('a.descripcion','LIKE','%'.$request->articulo.'%')
      ->where('partidas.tiposalida_id','2')
      ->select(
      'partidas.cantidad AS cantidad_salida',
      's.id as ids',
      DB::raw("('Sitio') AS tipo"),
      's.folio as folio',
      'p.nombre_corto',
      'p2.nombre_corto AS p_salida',
      'a.nombre AS nombre',
      'a.descripcion AS desc',
      'a.unidad as unidad',
      'oc.folio AS oc_folio')
      ->get()->toArray();

      $array_uno = array_merge($st,$ss);

      $sr = \App\Partidas::
      join('salidasresguardo AS s','s.id','partidas.salida_id')
      ->join('lote_almacen AS la','la.id','partidas.lote_id')
      ->join('lotes AS l','l.id','la.lote_id')
      ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
      ->join('requisicion_has_ordencompras AS rhoc','rhoc.id','pe.req_com_id')
      ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
      ->join('articulos AS a','a.id','=','la.articulo_id')
      ->join('stocks AS ss','ss.id','la.stocke_id')
      ->Join('proyectos AS p','p.id','=','ss.proyecto_id')
      ->leftJoin('proyecto_subcategorias AS ps','ps.id','=','p.proyecto_subcategorias_id')
      ->leftJoin('proyecto_categorias AS pc','pc.id','=','ps.proyecto_categoria_id')
      ->where('s.proyecto_id',$this->id)
      // ->whereIn('p.nombre',['Proyectos','Servicios'])
      // ->where('a.nombre','LIKE','%'.$request->articulo.'%')
      // ->orWhere('a.descripcion','LIKE','%'.$request->articulo.'%')
      ->where('partidas.tiposalida_id','3')
      ->select(
        'partidas.cantidad AS cantidad_salida',
      's.id as ids',
      DB::raw("('Resguardo') AS tipo"),
      's.folio as folio',
      'p.nombre_corto',
      'a.nombre AS nombre',
      'a.descripcion AS desc',
      'a.unidad as unidad',
      'oc.folio AS oc_folio')
      ->get()->toArray();

      $array = array_merge($array_uno,$sr);


        return view('excel.salidaexport', compact('array'));
    }
}
