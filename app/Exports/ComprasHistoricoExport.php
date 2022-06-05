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
use \App\Http\Helpers\Utilidades;


class ComprasHistoricoExport implements FromView, WithEvents
{
  protected $id;

  public function __construct(int $id)
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

    return [
      //todos los estilos los cargas aqui
      AfterSheet::class => function (AfterSheet $event) use ($styleArray) {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('img/conserflow.png'));
        $drawing->setCoordinates('B2');
        $drawing->setHeight(60);
        $drawing->setWorksheet($event->sheet->getDelegate());

        // $event->sheet->getStyle('C7:C'.$total)->applyFromArray($styleArray[3]);
        // $event->sheet->getStyle('J7:J'.$total)->applyFromArray($styleArray[3]);
        // $event->sheet->getStyle('K7:K'.$total)->applyFromArray($styleArray[3]);
        $event->sheet->getDelegate()->getRowDimension(6)->setRowHeight(30);

        $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(25);
        $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(16);
        $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(35);
        $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(10);
        $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(10);
        $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(50);
        $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('U')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('V')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('W')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('X')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('Y')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('Z')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('AA')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('AB')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('AC')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('AD')->setWidth(20);
        $event->sheet->getStyle('A5:N5')->applyFromArray($styleArray[1]);//se lllama el array de la posicion 0
        // $event->sheet->getStyle('A6:N6')->applyFromArray($styleArray[1]);//se lllama el array de la posicion 0
      },
    ];
  }

  public function view(): View
  {
    try {

      if ($this->id == 0) {
        $partidas_oc_a = DB::table('requisicion_has_ordencompras AS rhoc')
        ->join('articulos AS a','a.id','rhoc.articulo_id')
        ->join('grupos AS g','g.id','a.grupo_id')
        ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
        ->join('proyectos AS pro','pro.id','oc.proyecto_id')
        ->join('proveedores AS p','p.id','oc.proveedore_id')
        ->select('oc.folio','oc.fecha_orden','p.razon_social','g.nombre','oc.condicion_pago_id',
        'oc.periodo_entrega','oc.comentario_condicion_pago','rhoc.cantidad','a.unidad','a.descripcion',
        'rhoc.precio_unitario','oc.moneda','rhoc.articulo_id','rhoc.servicio_id','rhoc.requisicione_id','oc.condicion',
        'rhoc.comentario','rhoc.id','pro.id AS pro_id','pro.nombre_corto')
        ->where('rhoc.articulo_id','!=',NULL)
        ->where('pro.id','!=','1')
        ->get()->toArray();


        $partidas_oc_s = DB::table('requisicion_has_ordencompras AS rhoc')
        ->join('servicios AS s','s.id','rhoc.servicio_id')
        // ->join('grupos AS g','g.id','a.grupo_id')
        ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
        ->join('proyectos AS pro','pro.id','oc.proyecto_id')
        ->join('proveedores AS p','p.id','oc.proveedore_id')
        ->select('oc.folio','oc.fecha_orden','p.razon_social','s.unidad_medida AS nombre','oc.condicion_pago_id',
        'oc.periodo_entrega','oc.comentario_condicion_pago','rhoc.cantidad','s.unidad_medida AS unidad',
        's.nombre_servicio AS descripcion',
        'rhoc.precio_unitario','oc.moneda','rhoc.articulo_id','rhoc.servicio_id','rhoc.requisicione_id','oc.condicion',
        'rhoc.comentario','rhoc.id','pro.id AS pro_id','pro.nombre_corto')
        ->where('rhoc.servicio_id','!=',NULL)
        ->where('pro.id','!=','1')
        ->get()->toArray();

        $partidas_oc = array_merge($partidas_oc_a,$partidas_oc_s);
      }else {
        $partidas_oc_a = DB::table('requisicion_has_ordencompras AS rhoc')
        ->join('articulos AS a','a.id','rhoc.articulo_id')
        ->join('grupos AS g','g.id','a.grupo_id')
        ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
        ->join('proyectos AS pro','pro.id','oc.proyecto_id')
        ->join('proveedores AS p','p.id','oc.proveedore_id')
        ->select('oc.folio','oc.fecha_orden','p.razon_social','g.nombre','oc.condicion_pago_id',
        'oc.periodo_entrega','oc.comentario_condicion_pago','rhoc.cantidad','a.unidad','a.descripcion',
        'rhoc.precio_unitario','oc.moneda','rhoc.articulo_id','rhoc.servicio_id','rhoc.requisicione_id','oc.condicion',
        'rhoc.comentario','rhoc.id','pro.id AS pro_id','pro.nombre_corto')
        ->where('rhoc.articulo_id','!=',NULL)
        ->where('pro.id','=',$this->id)
        ->get()->toArray();


        $partidas_oc_s = DB::table('requisicion_has_ordencompras AS rhoc')
        ->join('servicios AS s','s.id','rhoc.servicio_id')
        // ->join('grupos AS g','g.id','a.grupo_id')
        ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
        ->join('proyectos AS pro','pro.id','oc.proyecto_id')
        ->join('proveedores AS p','p.id','oc.proveedore_id')
        ->select('oc.folio','oc.fecha_orden','p.razon_social','s.unidad_medida AS nombre','oc.condicion_pago_id',
        'oc.periodo_entrega','oc.comentario_condicion_pago','rhoc.cantidad','s.unidad_medida AS unidad',
        's.nombre_servicio AS descripcion',
        'rhoc.precio_unitario','oc.moneda','rhoc.articulo_id','rhoc.servicio_id','rhoc.requisicione_id','oc.condicion',
        'rhoc.comentario','rhoc.id','pro.id AS pro_id','pro.nombre_corto')
        ->where('rhoc.servicio_id','!=',NULL)
        ->where('pro.id','=',$this->id)
        ->get()->toArray();

        $partidas_oc = array_merge($partidas_oc_a,$partidas_oc_s);
      }


      // return response()->json($partidas_oc);

      $arreglo = [];
      foreach ($partidas_oc as $key => $value) {
        if ($value->articulo_id != '') {

        $partidas_requisiciones = DB::table('partidas_requisiciones AS pr')
        ->join('requisiciones AS r','r.id','pr.requisicione_id')
        ->where('articulo_id',$value->articulo_id)
        ->where('requisicione_id',$value->requisicione_id)
        ->select('r.folio','r.fecha_solicitud','pr.produccion')
        ->first();

        $partidas_entradas_f = DB::table('partidas_entradas AS pe')
        ->join('entradas AS e','e.id','pe.entrada_id')
        // ->join('lotes AS l','l.entrada_id','pe.id')
        // ->join('lote_almacen AS la','la.lote_id','l.id')
        ->select('e.fecha')
        ->where('req_com_id',$value->id)
        ->get();

        $partidas_entradas_c = DB::table('partidas_entradas AS pe')
        ->join('entradas AS e','e.id','pe.entrada_id')
        ->select(DB::raw("SUM(pe.cantidad) AS cantidad"))
        ->where('req_com_id',$value->id)
        ->first();
      }
      if ($value->servicio_id != '') {
        $partidas_requisiciones = DB::table('partidas_requisiciones AS pr')
        ->join('requisiciones AS r','r.id','pr.requisicione_id')
        ->where('articulo_id',$value->servicio_id)
        ->where('requisicione_id',$value->requisicione_id)
        ->select('r.folio','r.fecha_solicitud','pr.produccion')
        ->first();

        $partidas_entradas_f = \App\ServicioCheck::join('requisicion_has_ordencompras AS rhoc','rhoc.id','=','servicio_check.rhoc_id')
        ->select('servicio_check.fecha')
        ->where('servicio_check.rhoc_id',$value->id)
        ->get();

        $partidas_entradas_c = \App\ServicioCheck::join('requisicion_has_ordencompras AS rhoc','rhoc.id','=','servicio_check.rhoc_id')
        ->select('rhoc.cantidad')
        ->where('servicio_check.rhoc_id',$value->id)
        ->first();
      }

        $arreglo [] = [
          'a' => $value,
          'b' => $partidas_requisiciones,
          'c' => $partidas_entradas_f,
          'd' => $partidas_entradas_c,
        ];
      }


      return view('excel.comprashistorico', compact('arreglo'));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

}
