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


class EstatusMaterialExport implements FromView, WithEvents
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
        $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(50);
        for ($i=7; $i < 5000; $i++) {
          $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(50);
        }
        $event->sheet->getDelegate()->getRowDimension(6)->setRowHeight(50);

        $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(10);
        $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(12);
        $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(10);
        $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(10);
        $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(50);
        $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(10);
        $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(20);
        // $event->sheet->getStyle('A5:S5')->applyFromArray($styleArray[1]);//se lllama el array de la posicion 0
        $event->sheet->getStyle('A6:S6')->applyFromArray($styleArray[1]);//se lllama el array de la posicion 0
        // $event->sheet->getActiveSheet(0)->freezePaneByColumnAndRow(0,6); // donde 0 es la columna desde donde se congela y 4 es la fila hasta donde se congela.

      },
    ];
  }

  public function tiempoentrega($data)
  {

  }

  public function view(): View
  {


        $pr = DB::table('partidas_requisiciones AS pr')
        ->join('requisiciones AS r','r.id','pr.requisicione_id')
        ->join('empleados AS e','e.id','r.solicita_empleado_id')
        ->select('r.folio','r.descripcion_uso','r.fecha_solicitud','r.estado_id',
        DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_solicita"),
        'pr.requisicione_id','pr.articulo_id','pr.servicio_id','pr.cantidad','pr.cantidad_almacen','pr.comentario')
        ->where('r.proyecto_id',$this->id)
        ->get();
        $arreglo = [];
        foreach ($pr as $key => $value) {
          $a = null;$s=null;$tipo_material="";$desc = '';
          if ($value->articulo_id != null) {
            $a = DB::table('articulos AS a')->select('a.descripcion','a.unidad','a.grupo_id')->where('id',$value->articulo_id)->first();
            $gpo = $a->grupo_id == '' ? 0 : $a->grupo_id;
            $desc = $a->descripcion;
            $tm = DB::table('grupos AS g')->join('categorias AS c','c.id','g.categoria_id')
            ->where('g.id',$gpo)
            ->select('g.nombre AS nombre_g','c.nombre AS nombre_c')->first();
            $tipo_material = $a->grupo_id == '' ? '-' : ($tm->nombre_g);
          }
          if ($value->servicio_id != null) {
            $s = DB::table('servicios AS s')->select('s.nombre_servicio','s.unidad_medida')->where('id',$value->servicio_id )->first();
            $tipo_material = 'SERVICIO';
            $desc = $s->nombre_servicio;
          }
          $oc = DB::table('requisicion_has_ordencompras AS rhoc')
          ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
          ->join('proveedores AS p','p.id','oc.proveedore_id')
          ->select('rhoc.id','oc.folio','rhoc.articulo_id','rhoc.cantidad','oc.periodo_entrega','oc.moneda',
            'rhoc.precio_unitario','rhoc.comentario','p.razon_social')
          ->where('rhoc.requisicione_id',$value->requisicione_id)
          ->where('rhoc.articulo_id',$value->articulo_id)
          ->where('rhoc.servicio_id',$value->servicio_id)
          ->first();

          // $tiempo_estimado_entraga = $this->tiempoentrega($oc->periodo_entrega);

          $pe = [];
          if (isset($oc) == true) {
          $pe = DB::table('partidas_entradas AS pe')
          ->join('entradas AS e','e.id','pe.entrada_id')
          ->where('req_com_id',$oc->id)
          ->select('pe.cantidad','e.fecha')
          ->get();
          }

          $arreglo [] = [
            'tipo_material' => $tipo_material,
            'requis' => $value,
            'oc' => $oc,
            's' => $s,
            'a' => $a,
            'descripcion' => $desc,
            'e' => $pe,
          ];
        }



    return view('excel.requisitadocompradouno', compact('arreglo'));
  }
}
