<?php
namespace App\Exports;

use App\Existencia;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Grupo;
use App\LoteAlmacen;
use App\Movimiento;

class InventariosPExport implements FromView, WithEvents
{
    protected $id;

    // public function __construct(string $id)
    // {
    //     $this->id = $id;
    // }

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
                    'color' => ['argb' => '000000'],
                ],
            ], /****fin la posicion 1****/
            [
                'font' => [
                    'bold'       =>  true,
                    'color' => ['argb' => 'ffffff'],
                ],
                ]
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
                  // $drawing->setPath(public_path('img/Imagen3.png'));
                  // $drawing->setCoordinates('A1');
                  // $drawing->setWidth(1560);
                  // $drawing->setWorksheet($event->sheet->getDelegate());
                  // $drawing->setOffsetX(25);
                  // $drawing->setHeight(180);
                    // $event->sheet->setAutoSize(true);
                  // $event->sheet->getColumnDimension('A21')->setAutoSize(true) ;
                  // $event->sheet->setWidth('A', 5);

                  $event->sheet->getStyle('A1:A30000')->getAlignment()->setWrapText(true);
                  $event->sheet->getStyle('G1:G30000')->getAlignment()->setWrapText(true);
                  $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(20);
                  $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
                  $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);




                // $event->sheet->getStyle('C1')->applyFromArray($styleArray[1]);
                // $event->sheet->getStyle('A1:j1')->applyFromArray($styleArray[2]);
                // $event->sheet->getStyle('A1:j1')->applyFromArray($styleArray[2]);//se llama el array de la posicion 0
                // $event->sheet->getStyle('A1:j1')->getFill()->setFillType('solid')->getStartColor()->setARGB('5882FA');
/*                getFill()->setFillType('solid')->getStartColor()->setARGB('5882FA');*/
            },
        ];
    }

    public function view(): View
    {

      $arreglo = [];
      $valores = [79,88,90,91,96,98,105,108,109,110,111,112];
      $where = 'ordenes_compras.proyecto_id';

      $ordencompra = \App\Compras::
      join('proyectos AS p','p.id','=','ordenes_compras.proyecto_id')
      ->join('proveedores AS pr','pr.id','=','ordenes_compras.proveedore_id')
      ->select('ordenes_compras.id AS id','ordenes_compras.fecha_orden','ordenes_compras.folio','ordenes_compras.total',
      'ordenes_compras.moneda','ordenes_compras.proyecto_id','p.nombre_corto','pr.razon_social')
      ->whereIn($where,$valores)
      ->get();

      foreach ($ordencompra as $key => $value) {

        $partidas_rhoc_a = \App\requisicionhasordencompras::where('requisicion_has_ordencompras.orden_compra_id',$value->id)
        ->join('articulos AS a','a.id','=','requisicion_has_ordencompras.articulo_id')
        ->leftJoin('grupos AS g','g.id','a.grupo_id')
        ->select('requisicion_has_ordencompras.*','a.descripcion as description','g.nombre as nombre_grupo')
        ->where('requisicion_has_ordencompras.articulo_id','!=','NULL')
        ->get()->toArray();

        $partidas_rhoc_s = \App\requisicionhasordencompras::where('requisicion_has_ordencompras.orden_compra_id',$value->id)
        ->join('servicios AS s','s.id','=','requisicion_has_ordencompras.servicio_id')
        ->select('requisicion_has_ordencompras.*','s.nombre_servicio as description','s.unidad_medida AS nombre_grupo'
        )
        ->where('requisicion_has_ordencompras.servicio_id','!=','NULL')->get()->toArray();

        $partidas_rhoc = array_merge($partidas_rhoc_a,$partidas_rhoc_s);

        $arr = [];
        foreach ($partidas_rhoc as $key => $va) {
          if ($va['articulo_id'] != '') {
            $entrada = \App\PartidaEntrada::
            leftJoin('lotes AS l','l.entrada_id','=','partidas_entradas.id')
            ->leftJoin('lote_almacen AS la','la.lote_id','=','l.id')
            ->leftJoin('movimientos AS m','m.lote_id','=','la.id')
            ->where('partidas_entradas.req_com_id',$va['id'])
            ->where('m.tipo_movimiento','Entrada')
            ->select(DB::raw('SUM(m.cantidad) AS cantidad_entrada'))
            ->first();
          }
          if ($va['servicio_id'] != '') {
            $entrada = \App\ServicioCheck::join('requisicion_has_ordencompras AS rhoc','rhoc.id','=','servicio_check.rhoc_id')
            ->select(DB::raw('SUM(rhoc.cantidad) AS cantidad_entrada'))
            ->where('servicio_check.rhoc_id',$va['id'])
            ->first();
          }

          if ($va['articulo_id'] != '') {
            $salida = \App\PartidaEntrada::
            leftJoin('lotes AS l','l.entrada_id','=','partidas_entradas.id')
            ->leftJoin('lote_almacen AS la','la.lote_id','=','l.id')
            ->leftJoin('movimientos AS m','m.lote_id','=','la.id')
            ->where('partidas_entradas.req_com_id',$va['id'])
            ->where('m.tipo_movimiento','Salida')
            ->select(DB::raw('SUM(m.cantidad) AS cantidad_salida'))
            ->first();
          }
          if ($va['servicio_id'] != '') {
            $salida = \App\ServicioCheck::join('requisicion_has_ordencompras AS rhoc','rhoc.id','=','servicio_check.rhoc_id')
            ->select(DB::raw('SUM(rhoc.cantidad) AS cantidad_salida'))
            ->where('servicio_check.rhoc_id',$va['id'])
            ->first();
          }
          $arr [] = [
            'salida' => $salida->cantidad_salida,
            'entrada' => $entrada->cantidad_entrada,
            'descripcion' => str_replace(array('Ø'),'',$va['description']),
            'codificacion' =>  mb_detect_encoding($va['description']),
            'cantidad' => $va['cantidad'],
            'comentarios' => $va['comentario'],
            'grupo' => $va['nombre_grupo'],
          ];
        }




        $total_oc = \App\requisicionhasordencompras::select(DB::raw('SUM(cantidad) AS suma_total_oc'))
        ->where('orden_compra_id',$value->id)->first();

        $total_sin_en = \App\requisicionhasordencompras::select(DB::raw('SUM(cantidad) AS suma_total_sin_en'))
        ->where('orden_compra_id',$value->id)
        ->where('articulo_id','!=','NULL')
        ->where('condicion','1')->first();

        $total_con_en = \App\requisicionhasordencompras::
       select(DB::raw('(SUM(requisicion_has_ordencompras.cantidad)) AS suma_total_con_en'))
        ->where('requisicion_has_ordencompras.orden_compra_id',$value->id)
        ->where('requisicion_has_ordencompras.condicion','0')->first();
        $total_salidas = 0;

          $partidas = \App\Partidas::
          Join('lote_almacen AS la','la.id','=','partidas.lote_id')
          ->Join('lotes AS l','l.id','=','la.lote_id')
          ->Join('partidas_entradas AS pa','pa.id','=','l.entrada_id')
          ->Join('requisicion_has_ordencompras AS RHOC','RHOC.id','=','pa.req_com_id')
          ->where('RHOC.orden_compra_id',$value->id)
          ->select(DB::raw('SUM(partidas.cantidad) AS suma_total_partidas'))
          ->first();

          $total_con_en_ser = \App\requisicionhasordencompras::
         select(DB::raw('(SUM(requisicion_has_ordencompras.cantidad)) AS suma_total_con_en_ser'))
          ->where('requisicion_has_ordencompras.orden_compra_id',$value->id)
          ->where('requisicion_has_ordencompras.servicio_id','!=','NULL')
          ->where('requisicion_has_ordencompras.condicion','0')->first();

          $total_salidas = $partidas->suma_total_partidas == null ? 0 :$partidas->suma_total_partidas;
          if ($total_con_en_ser->suma_total_con_en_ser != null) {
            $total_salidas += (float)$total_con_en_ser->suma_total_con_en_ser;
          }
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

        $facturas_entradas = \App\FacturasEntradas::
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

        $pagos_compras = \App\PagoCompra::
        join('pagos_no_recurrentes AS pnr','pnr.id','=','pagos_compras.pagos_no_recurrentes_id')
        ->select(DB::raw('SUM(cargo) AS total_pagos'))
        ->where('pnr.ordenes_comp_id',$value->id)->first();

        $pagos_compras_pagos = \App\PagoCompra::
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
          }elseif(count($valores) == 6){
            $folio_oc = $valores[3].'-'.$valores[4].'-'.$valores[5];
          }
        }
        $moneda = '';
        if($value->moneda == 1){ $moneda = 'USD';}
        if($value->moneda == 2){ $moneda = 'MXN';}


        $arreglo [] = [
          'oc' => $value,
          'partidas' => $arr,
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


        return view('excel.inventarioproyecto', compact('arreglo'));
    }
}
