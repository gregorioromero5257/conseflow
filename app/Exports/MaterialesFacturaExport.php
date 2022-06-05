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

class MaterialesFacturaExport implements FromView,WithTitle,WithEvents
{

  public function __construct(int $id)
  {
    $this->id = $id;
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

        // $event->sheet->getDelegate()->getRowDimension(6)->setRowHeight(30);
        //
        $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(50);
        $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(10);
        $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(15);

        $event->sheet->getStyle('A1:K1')->applyFromArray($styleArray[5]); // Border
        // $event->sheet->getStyle('A6:H6')->applyFromArray($styleArray[5]); // Color
        // $event->sheet->getStyle('A6:H6')->applyFromArray($styleArray[1]);//se lllama el array de la posicion 0
        // $event->sheet->getStyle('A1')->applyFromArray($styleArray[0]);//se lllama el array de la posicion 0
        // $event->sheet->getStyle('A1')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        // $event->sheet->getStyle('B2')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        // $event->sheet->getStyle('G2')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        // $event->sheet->getStyle('H2')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        // $event->sheet->getStyle('G3')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        // $event->sheet->getStyle('H3')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
      },
    ];
  }

  public function view(): View
  {
    $partidas = DB::table('partidas AS pa')
    ->join('salidas AS s','s.id','pa.salida_id')
    ->join('lote_almacen AS la','pa.lote_id','la.id')
    ->join('lotes AS l','l.id','la.lote_id')
    ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
    ->join('articulos AS a','a.id','la.articulo_id')
    ->where('pa.tiposalida_id','1')
    ->where('s.proyecto_id',$this->id)
    ->select('la.id','la.stocke_id',
    DB::raw("SUM(pa.cantidad) AS cantidad_salida"),
    'a.descripcion','a.unidad','l.id AS l_id','pe.id AS pe_di','pe.req_com_id'
    )
    ->groupBy('la.id')
    ->get()->toArray();
    // return response()->json($partidas);

    $partidas_r = DB::table('partidas AS pa')
    ->join('salidassitio AS s','s.id','pa.salida_id')
    ->join('lote_almacen AS la','pa.lote_id','la.id')
    ->join('lotes AS l','l.id','la.lote_id')
    ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
    ->join('articulos AS a','a.id','la.articulo_id')
    ->where('pa.tiposalida_id','1')
    ->where('s.proyecto_id',$this->id)
    ->select('la.id','la.stocke_id',
    DB::raw("SUM(pa.cantidad) AS cantidad_salida"),
    'a.descripcion','a.unidad','l.id AS l_id','pe.id AS pe_di','pe.req_com_id'
    )
    ->groupBy('la.id')
    ->get()->toArray();

    $par_uno = array_merge($partidas, $partidas_r);

    $partidas_s = DB::table('partidas AS pa')
    ->join('salidasresguardo AS s','s.id','pa.salida_id')
    ->join('lote_almacen AS la','pa.lote_id','la.id')
    ->join('lotes AS l','l.id','la.lote_id')
    ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
    ->join('articulos AS a','a.id','la.articulo_id')
    ->where('pa.tiposalida_id','1')
    ->where('s.proyecto_id',$this->id)
    ->select('la.id','la.stocke_id',
    DB::raw("SUM(pa.cantidad) AS cantidad_salida"),
    'a.descripcion','a.unidad','l.id AS l_id','pe.id AS pe_di','pe.req_com_id'
    )
    ->groupBy('la.id')
    ->get()->toArray();

    $par = array_merge($par_uno, $partidas_s);

    $arreglo = [];

    foreach ($par as $key => $value) {
      $impuestos = [];
      $folio = '';
      $oc_num = '';
      $comentario = '';

        $partidas_facturas = DB::table('conceptos_oc_xml AS cox')
        ->join('gastos_xml_oc AS gxo','gxo.id','cox.gastos_xml_oc_id')
        ->join('ordenes_compras AS oc','oc.id','gxo.ordenes_compras_gastos_id')
        ->join('proveedores AS p','p.id','oc.proveedore_csct_id')
        ->select('cox.*','gxo.moneda','oc.folio','oc.proyecto_id','p.razon_social')
        ->where('cox.partida_rhc_id',$value->req_com_id)->first();

        $proyecto_origen = DB::table('stocks AS s')
        ->join('proyectos AS p','p.id','s.proyecto_id')
        ->select('p.nombre_corto')
        ->where('s.id',$value->stocke_id)
        ->first();

        $ordencompra = DB::table('requisicion_has_ordencompras AS rhoc')
        ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
        ->where('rhoc.id',$value->req_com_id)
        ->select('oc.folio','rhoc.comentario')
        ->first();
        if (isset($ordencompra) == true) {
          $folio = explode('-',$ordencompra->folio);
          $oc_num = $folio[count($folio) - 1];
          $comentario = $ordencompra->comentario;
        }

        if (isset($partidas_facturas) == true) {
          $impuestos_oc = DB::table('impuestos_oc_xml AS iox')
          ->where('iox.base',$partidas_facturas->importe)
          ->where('iox.base','!=',null)
          ->get();

          $impuestos = $impuestos_oc;
        }

        $arreglo [] = [
          'proyecto_origen' => $proyecto_origen->nombre_corto,
          'orden_compra' => $oc_num,
          'comentario' => $comentario ,
          'salida' => $value,
          'factura' => $partidas_facturas,
          'impuestos' => $impuestos,
        ];

    }
    return view('excel.materialesfactura', compact('arreglo'));
  }
}
