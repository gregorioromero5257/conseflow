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

class InventarioExport implements FromView, WithEvents
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
                  $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(60);
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

      $valores = explode('&',$this->id);
      $fecha_uno = $valores[0];
      $fecha_dos = $valores[1];
      $arreglo = [];
      $grupos = Grupo::get();
      foreach ($grupos as $key => $value) {

        $lote_almacen = LoteAlmacen::
        join('movimientos AS m','m.lote_id','=','lote_almacen.id')
        ->join('lotes AS l','l.id','=','lote_almacen.lote_id')
        ->join('partidas_entradas AS pe','pe.id','=','l.entrada_id')
        ->join('articulos AS a','a.id','=','lote_almacen.articulo_id')
        ->join('stocks AS s','s.id','=','lote_almacen.stocke_id')
        ->join('proyectos AS p','p.id','=','s.proyecto_id')
        ->select('lote_almacen.id AS lt_id','l.id AS l_id','lote_almacen.almacene_id','lote_almacen.nivel_id','lote_almacen.stand_id',
        'pe.cantidad AS cantidad_ingresada','pe.precio_unitario','lote_almacen.cantidad AS existencia',
        DB::raw('(pe.cantidad * pe.precio_unitario) AS total'),'p.nombre_corto','m.fecha',
        'a.nombre','a.descripcion','a.id AS a_id')
        ->where('a.grupo_id',$value->id)
        ->where('m.tipo_movimiento','LIKE','Entrada')
        ->whereBetween('m.fecha',[$fecha_uno,$fecha_dos])
        ->get();

        $suma = LoteAlmacen::
        join('movimientos AS m','m.lote_id','=','lote_almacen.id')
        ->join('lotes AS l','l.id','=','lote_almacen.lote_id')
        ->join('partidas_entradas AS pe','pe.id','=','l.entrada_id')
        ->join('articulos AS a','a.id','=','lote_almacen.articulo_id')
        ->select(DB::raw('SUM(pe.cantidad * pe.precio_unitario) AS total_gpo'))
        ->where('a.grupo_id',$value->id)
        ->where('m.tipo_movimiento','LIKE','Entrada')
        ->whereBetween('m.fecha',[$fecha_uno,$fecha_dos])
        ->first();

              $a = [];
              foreach ($lote_almacen as $key => $v) {
              $mov_e = Movimiento::select(DB::raw('SUM(cantidad) AS total_entrada'))->where('lote_id',$v->lt_id)->where('tipo_movimiento','Entrada')->first();
              $mov_s = Movimiento::select(DB::raw('SUM(cantidad) AS total_salida'))->where('lote_id',$v->lt_id)->where('tipo_movimiento','Salida')->first();
                  $a [] = [
                    'uno' => $v,
                    'en' => $mov_e,
                    'sa' => $mov_s,
                  ];
              }
          $arreglo [] = [
            'grupos' => $value,
            'articulos' => $a,
            'suma' => $suma,
          ];



      }

        return view('excel.inventarios', compact('arreglo'));
    }
}
