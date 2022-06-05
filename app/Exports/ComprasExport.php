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


class ComprasExport implements FromView, ShouldAutoSize, WithEvents
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
        $event->sheet->getStyle('A1:M1')->applyFromArray($styleArray[1]);//se lllama el array de la posicion 0
      },
    ];
  }

  public function view(): View
  {
    $valores = explode('&',$this->id);

    $arreglo = [];
    $ordenes_compras = Compras::Join('proveedores AS p','p.id','=','ordenes_compras.proveedore_id')
    // ->leftJoin('')
    ->select('p.razon_social','total','moneda','folio','ordenes_compras.id AS id_c','conrequisicion','fecha_orden','proyecto_id')
    ->where('proyecto_id',$valores[0])
    ->whereBetween('fecha_orden',[$valores[1], $valores[2]])
    ->get();

    foreach ($ordenes_compras as $key => $value) {
      $partidas_compras = requisicionhasordencompras::leftJoin('requisiciones AS r','r.id','=','requisicion_has_ordencompras.requisicione_id')
      ->select('r.folio','r.descripcion_uso')
      ->where('orden_compra_id',$value->id_c)->where('r.folio','NOT LIKE','%SR')->distinct('requisicione_id')->get();
      $arreglo [] = [
        'compras' => $value,
        'requi' => $partidas_compras
      ];
    }

    return view('excel.compras', compact('arreglo'));
  }
}
