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




class QuincenaExportCon implements FromView, ShouldAutoSize, WithEvents
{
    protected $id;

    public function __construct(int $id)
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

          $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                $drawing->setPath(public_path('img/c.png'));
                $drawing->setCoordinates('A1');
                $drawing->setWorksheet($event->sheet->getDelegate());

          $event->sheet->getStyle('b6')->applyFromArray($styleArray[1]);
          $event->sheet->getStyle('A8:C8')->applyFromArray($styleArray[2]);
          $event->sheet->getStyle('A10:I10')->applyFromArray($styleArray[0]);//se llama el array de la posicion 0
        for ($i=11; $i < 100; $i++) {
          // code...
            $event->sheet->getStyle('A'.$i.':I'.$i)->applyFromArray($styleArray[1]);//se llama el array de la posicion 0
        }
        },
      ];
    }


    public function view(): View
    {

      $nomina = DB::table('nomina')
      ->select('nomina.*')
      ->where('nomina.id','=',$this->id)
      ->first();

      // $contratosp = DB::table('contratos')
      // ->leftJoin('empleados AS E','E.id','=','contratos.empleado_id')
      // ->leftJoin('proyectos AS P','P.id','=','contratos.proyecto_id')
      // ->select('P.*')
      // ->where('contratos.tipo_nomina_id','=','2')->distinct()->get();

      $contratosp = DB::table('nominas_movimientos')
      ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
      ->leftJoin('proyectos AS P','P.id','=','C.proyecto_id')
      ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
      ->leftJoin('datos_bancarios_empleados AS DBE','DBE.id','=','nominas_movimientos.banco_id')
      ->leftJoin('catalogo_bancos AS CB','CB.id','=','DBE.banco_id')
      ->select('nominas_movimientos.*','DBE.banco_id','CB.nombre AS banco','DBE.numero_cuenta',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre"))
      ->where('C.tipo_nomina_id','=','2')->where('nominas_movimientos.nomina_id','=',$this->id)->get();

      $contrat_to = DB::table('contratos')
      ->leftJoin('empleados AS E','E.id','=','contratos.empleado_id')
      ->leftJoin('proyectos AS P','P.id','=','contratos.proyecto_id')
      ->leftJoin('nominas_movimientos AS MN','MN.contrato_id','=','contratos.id')
      ->select(DB::raw('SUM(MN.transferencias) AS totaltr_to'))
      // ->where('P.id','=',$value->id)
      ->where('MN.nomina_id','=',$this->id)
      ->where('contratos.tipo_nomina_id','=','2')
      ->first();

      // $empleados = \App\


        return view('excel.quincenaldos', compact('contratosp','contrat_to','nomina'));
    }
}
