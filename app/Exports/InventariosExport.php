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
use App\requisicionhasordencompras;
use \App\Http\Helpers\Utilidades;

class InventariosExport implements FromView, WithEvents
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
      try {

      $arreglo = [];
      $proyectos = DB::table('ordenes_compras AS oc')
      ->join('proyectos AS p','p.id','oc.proyecto_id')
      ->leftJoin('proyecto_subcategorias AS ps','ps.id','p.proyecto_subcategorias_id')
      ->where('ps.proyecto_categoria_id','1')
      ->orWhere('ps.proyecto_categoria_id','2')
      ->select('oc.proyecto_id')
      ->groupBy('oc.proyecto_id')->get()->toArray();
      $a = [];
      foreach ($proyectos as $key => $value) {
        $a [] = $value->proyecto_id;
      }

      $partidas_rhoc_a = requisicionhasordencompras::
      join('articulos AS a','a.id','=','requisicion_has_ordencompras.articulo_id')
      ->join('ordenes_compras AS oc','oc.id','requisicion_has_ordencompras.orden_compra_id')
      ->leftJoin('grupos AS g','g.id','a.grupo_id')
      ->select('a.id','g.nombre','a.descripcion')
      ->whereIn('oc.proyecto_id',$a)
      ->where('requisicion_has_ordencompras.articulo_id','!=','NULL')
      ->whereNotIn('g.nombre',['TECNOLOGÍAS DE LA INFORMACIÓN','SOFTWARE','PAPELERIA','LIMPIEZA','OFICINA'])
      ->groupBy('a.id')
      ->orderBy('g.nombre')
      ->get();

      $arreglo = [];
      foreach ($partidas_rhoc_a as $key => $value) {
          $partidas_rhoc = requisicionhasordencompras::
          select(DB::raw("SUM(cantidad) AS cantidad_t"))
          ->where('articulo_id',$value->id)
          ->first();

        $partidas_entradas = DB::table('partidas_entradas')
        ->select(DB::raw("SUM(cantidad) AS cantidad_e"))
        ->where('articulo_id',$value->id)
        ->first();

        $partidas_salidas = DB::table('partidas AS p')
        ->join('lote_almacen AS la','la.id','p.lote_id')
        ->select(DB::raw("SUM(p.cantidad) AS cantidad_s"))
        ->where('la.articulo_id',$value->id)
        ->first();

        $arreglo [] =
        [
          'codificacion' =>  mb_detect_encoding($value->descripcion),
          'grupo' => $value->nombre,
          'descripcion' => $value->descripcion,
          'cantidad_inicial' => $partidas_rhoc->cantidad_t,
          'cantidad_entrada' => $partidas_entradas->cantidad_e,
          'cantidad_salida' => $partidas_salidas->cantidad_s,
        ];

      }

        return view('excel.inventariogeneral', compact('arreglo'));
      } catch (\Throwable $e) {
        Utilidades::errors($e);
        return view('errors.204');
      }
    }
}
