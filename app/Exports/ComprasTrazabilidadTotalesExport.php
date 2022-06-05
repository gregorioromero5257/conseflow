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


class ComprasTrazabilidadTotalesExport implements FromView, WithEvents
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
    $valores = explode('&',$this->id);
    if ($valores[0] === 'Proyecto') {
      $where = 'oc.proyecto_id';
    }elseif ($valores[0] === 'Proveedor') {
      $where = 'oc.proveedore_id';
    }
    $total_columnas = requisicionhasordencompras::
    join('ordenes_compras AS oc','oc.id','=','requisicion_has_ordencompras.orden_compra_id')
    ->select('requisicion_has_ordencompras.id')
    ->whereIn($where,$valores)->get();

    $total = (count($total_columnas));

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
      AfterSheet::class => function (AfterSheet $event) use ($styleArray,$total) {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('img/conserflow.png'));
        $drawing->setCoordinates('B2');
        $drawing->setHeight(60);
        $drawing->setWorksheet($event->sheet->getDelegate());

        $event->sheet->getStyle('C7:C'.$total)->applyFromArray($styleArray[3]);
        $event->sheet->getStyle('J7:J'.$total)->applyFromArray($styleArray[3]);
        $event->sheet->getStyle('K7:K'.$total)->applyFromArray($styleArray[3]);
        $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(14);
        $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(10);
        $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(16);
        $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(35);
        $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(15);
        $event->sheet->getStyle('A5:N5')->applyFromArray($styleArray[1]);//se lllama el array de la posicion 0
        $event->sheet->getStyle('A6:N6')->applyFromArray($styleArray[1]);//se lllama el array de la posicion 0
        $event->sheet->setCellValue('C'.(($total+8)+1),'=(B'.($total+8).':B'.(($total+8)+1).')');

      },
    ];
  }

  public function view(): View
  {
    $arreglo = [];
    $valores = explode('&',$this->id);
    if ($valores[0] === 'Proyecto') {
      $where = 'ordenes_compras.proyecto_id';
    }elseif ($valores[0] === 'Proveedor') {
      $where = 'ordenes_compras.proveedore_id';
    }

    $ordencompra = Compras::
    join('proyectos AS p','p.id','=','ordenes_compras.proyecto_id')
    ->join('proveedores AS pr','pr.id','=','ordenes_compras.proveedore_id')
    ->select('ordenes_compras.id AS id','ordenes_compras.fecha_orden','ordenes_compras.folio','ordenes_compras.total',
    'ordenes_compras.moneda','ordenes_compras.proyecto_id','p.nombre_corto','pr.razon_social')
    ->whereIn($where,$valores)
    ->get();

    $total_columnas = requisicionhasordencompras::
    join('ordenes_compras','ordenes_compras.id','=','requisicion_has_ordencompras.orden_compra_id')
    ->select('requisicion_has_ordencompras.id')
    ->whereIn($where,$valores)->get();

    foreach ($ordencompra as $key => $value) {

      $total_oc = requisicionhasordencompras::select(DB::raw('SUM(cantidad) AS suma_total_oc'))
      ->where('orden_compra_id',$value->id)->first();

      $total_sin_en = requisicionhasordencompras::select(DB::raw('SUM(cantidad) AS suma_total_sin_en'))
      ->where('orden_compra_id',$value->id)
      ->where('articulo_id','!=','NULL')
      ->where('condicion','1')->first();

      $total_con_en = requisicionhasordencompras::
      // join('partidas_entradas AS pe','pe.req_com_id','=','requisicion_has_ordencompras.id')
      // ->join('lotes AS l','l.entrada_id','=','pe.id')
      // ->join('lote_almacen AS la','la.lote_id','=','l.id')
      // ->join('lote_temporal AS lt','lt.lote_almacen_id','=','la.id')
     select(DB::raw('(SUM(requisicion_has_ordencompras.cantidad)) AS suma_total_con_en'))
      // ->select(DB::raw('(SUM(requisicion_has_ordencompras.cantidad) + SUM(lt.cantidad)) AS suma_total_con_en'))
      ->where('requisicion_has_ordencompras.orden_compra_id',$value->id)
      // ->where('requisicion_has_ordencompras.articulo_id','!=','NULL')
      ->where('requisicion_has_ordencompras.condicion','0')->first();
      $total_salidas = 0;

      // $total_temp = LoteTemporal::
      // Join('lote_almacen AS la','la.id','=','lote_temporal.lote_almacen_id')
      // ->Join('lotes AS l','l.id','=','la.lote_id')
      // ->Join('partidas_entradas AS pa','pa.id','=','l.entrada_id')
      // ->Join('requisicion_has_ordencompras AS RHOC','RHOC.id','=','pa.req_com_id')
      // ->where('RHOC.orden_compra_id',$value->id)
      // ->select(DB::raw('SUM(lote_temporal.cantidad) AS suma_total_partidas_temp'))
      // ->first();


      // foreach ($total_oc as $key => $valor) {
        $partidas = Partidas::
        Join('lote_almacen AS la','la.id','=','partidas.lote_id')
        ->Join('lotes AS l','l.id','=','la.lote_id')
        ->Join('partidas_entradas AS pa','pa.id','=','l.entrada_id')
        ->Join('requisicion_has_ordencompras AS RHOC','RHOC.id','=','pa.req_com_id')
        ->where('RHOC.orden_compra_id',$value->id)
        ->select(DB::raw('SUM(partidas.cantidad) AS suma_total_partidas'))
        ->first();

        $total_con_en_ser = requisicionhasordencompras::
       select(DB::raw('(SUM(requisicion_has_ordencompras.cantidad)) AS suma_total_con_en_ser'))
        ->where('requisicion_has_ordencompras.orden_compra_id',$value->id)
        ->where('requisicion_has_ordencompras.servicio_id','!=','NULL')
        ->where('requisicion_has_ordencompras.condicion','0')->first();

        $total_salidas = $partidas->suma_total_partidas == null ? 0 :$partidas->suma_total_partidas;
        if ($total_con_en_ser->suma_total_con_en_ser != null) {
          $total_salidas += (float)$total_con_en_ser->suma_total_con_en_ser;
        }
        // $total_salidas = $partidas->suma_total_partidas == null ? 0 :$partidas->suma_total_partidas;
      // }
      $total = $total_oc->suma_total_oc == null ? 0 : $total_oc->suma_total_oc;
      $total_con_entradas = $total_con_en->suma_total_con_en == null ? 0 : $total_con_en->suma_total_con_en;
      $total_sin_entradas = $total_sin_en->suma_total_sin_en == null ? 0 : $total_sin_en->suma_total_sin_en;



      if (($total_con_entradas) == 0) {
      $porcentaje = 0;
      }else {
      $porcentaje = (($total_con_entradas) * 100) / ($total);
      }
      if (($total_salidas) == 0) {
      $porcentaje_salida = 0;
      }else {
      $porcentaje_salida = (($total_salidas) * 100) / ($total);
      }

      $facturas_entradas = FacturasEntradas::
      where('orden_compra_id',$value->id)
      ->where('entrada_id','0')
      ->where('entrada_id','')
      ->get();

      $total_factura = 0;
      $facturas_folios = '';
      if (count($facturas_entradas) > 0) {
        foreach ($facturas_entradas as $key => $vs) {
          $total_factura += $vs->total_factura;
          $facturas_folios .= $vs->uuid. ' ';
        }
      }

      $diferencia_costos = (floatval(str_replace(',','',$value->total))) - ($total_factura);

      $pagos_compras = PagoCompra::
      join('pagos_no_recurrentes AS pnr','pnr.id','=','pagos_compras.pagos_no_recurrentes_id')
      ->select(DB::raw('SUM(cargo) AS total_pagos'))
      ->where('pnr.ordenes_comp_id',$value->id)->first();

      $pagos_compras_pagos = PagoCompra::
      join('pagos_no_recurrentes AS pnr','pnr.id','=','pagos_compras.pagos_no_recurrentes_id')
      ->select('pagos_compras.cargo','pagos_compras.tipo_cambio',
      DB::raw('(pagos_compras.cargo * pagos_compras.tipo_cambio) AS total_pesos'))
      ->where('pnr.ordenes_comp_id',$value->id)->get();

      $tipo_change = '';
      $pagos_c = '';
      $total_en_mn = 0;
      foreach ($pagos_compras_pagos as $key => $vp) {
        $tipo_change.=round($vp->tipo_cambio,2).','.PHP_EOL;
        $pagos_c.=round($vp->cargo,2).','.PHP_EOL;
        $total_en_mn += $vp->total_pesos;
      }

      $total_pagos = $pagos_compras->total_pagos == null ? 0 :$pagos_compras->total_pagos ;

      $tipo_cambio = '';

      if (($total_pagos) == 0) {
      $porcentaje_pago = 0;
      }else {
      if ($value->moneda == 1) {
        $tipo_cambio = $total_pagos / (floatval(str_replace(',','',$value->total)));
        }
      }

      if (($total_pagos) == 0) {
      $porcentaje_pago = 0;
      }else {
      $porcentaje_pago = (($total_pagos) * 100) / ((floatval(str_replace(',','',$value->total))));
      }

      $folio_oc = '';
      if ($value->folio != '') {
        $valores = explode('-',$value->folio);
        if (count($valores) == 5) {
          $folio_oc = $valores[3].'-'.$valores[4];
        }
      }

      if($value->moneda == 1){ $moneda = 'USD';}
      if($value->moneda == 2){ $moneda = 'MXN';}


      $arreglo [] = [
        't' => count($total_columnas),
        'oc' => $value,
        'folio_oc' => $folio_oc,
        'total_par' => $total,
        'moneda' => $moneda,
        'total_sin_entrada' => $total_sin_entradas,
        'total_con_entrada' => $total_con_entradas,
        'procentaje_entrada' => $porcentaje,
        'total_salidas' => $total_salidas,
        'porcentaje_salidas' => $porcentaje_salida,
        'total_factura' => $total_factura,
        'factura' => $facturas_folios,
        'diferencia_costos' => $diferencia_costos,
        'totales' => $pagos_compras_pagos,
        'tipo_cambio' => $tipo_change,
        'pagos' => $pagos_c,
        'total_en_mn' => $total_en_mn,
        'porcentaje_pago' => $porcentaje_pago,
      ];
    }
    return view('excel.comprastotalestrazabilidad', compact('arreglo'));
  }
}
