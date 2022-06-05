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




class QuincenaExport implements FromView, ShouldAutoSize, WithEvents
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
    ],
    // 3
    [
      'alignment' => 
      [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        'text-color' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE
      ],
      'font' => 
      [
        'bold'       =>  true,
        'color' => ['argb' => 'FFFFFF'],
      ],
    ],
    // 4
    [
      'alignment' => 
      [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        'text-color' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK
      ],
      'font' => 
      [
        'bold'       =>  true,
        'color' => ['argb' => '000000'],
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
          $drawing->setCoordinates('A2');
          $drawing->setHeight(70);
          $drawing->setWorksheet($event->sheet->getDelegate());

          $event->sheet->getStyle('C6')->applyFromArray($styleArray[1]);
          $event->sheet->getStyle('A8:C8')->applyFromArray($styleArray[2]);
          // $event->sheet->getStyle('A10:I10')->applyFromArray($styleArray[0]);//se llama el array de la posicion 0

          //fondo azul, letra blanca
          $event->sheet->getStyle('A3:I3')->applyFromArray($styleArray[3]);
          $event->sheet->getStyle('A10:I10')->applyFromArray($styleArray[3]);
          $event->sheet->getStyle('A8')->applyFromArray($styleArray[3]);
          $event->sheet->getStyle('B8:C8')->applyFromArray($styleArray[4]);
          // Alto 50
          $event->sheet->getRowDimension(8)->setRowHeight(25);
          $event->sheet->getRowDimension(10)->setRowHeight(50);

        //   $event->sheet->setSize(array(
        //     'A1' => array(
        //         'width'     => 215,
        //         'height'    => 25
        //     )
        // ));
        for ($i=11; $i < 100; $i++) {
          // code...
            $event->sheet->getStyle('A'.$i.':I'.$i)->applyFromArray($styleArray[1]);//se llama el array de la posicion 0
        }
        },
      ];
    }



    public function view(): View
    {
      $arreglo =[];
      // $contratosp = DB::table('contratos')
      // ->leftJoin('empleados AS E','E.id','=','contratos.empleado_id')
      // ->leftJoin('proyectos AS P','P.id','=','contratos.proyecto_id')
      // ->select('P.*')
      // ->where('contratos.tipo_nomina_id','=','2')->distinct()->get();


            $nomina = DB::table('nomina')
            ->select('nomina.*')
            ->where('nomina.id','=',$this->id)
            ->first();


      $contratosp = DB::table('nominas_movimientos')
      ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
      ->leftJoin('proyectos AS P','P.id','=','C.proyecto_id')
      ->select('P.*')
      ->where('C.tipo_nomina_id','=','2')->where('nominas_movimientos.nomina_id','=',$this->id)->distinct()->get();

      foreach ($contratosp as $key => $value) {
        $contrato = DB::table('contratos')
        ->leftJoin('empleados AS E','E.id','=','contratos.empleado_id')
        ->leftJoin('proyectos AS P','P.id','=','contratos.proyecto_id')
        ->leftJoin('nominas_movimientos AS MN','MN.contrato_id','=','contratos.id')
        ->leftJoin('datos_bancarios_empleados AS DBE','DBE.id','=','MN.banco_id')
        ->leftJoin('catalogo_bancos AS CB','CB.id','=','DBE.banco_id')
        ->select('contratos.*',  DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre"),'MN.dias_trabajados'
        ,'MN.transferencias','MN.efectivos','MN.totales','MN.otros','MN.descuento','CB.nombre AS banco','DBE.banco_id','DBE.numero_cuenta')
        ->where('P.id','=',$value->id)
        ->where('MN.nomina_id','=',$this->id)
        ->where('contratos.tipo_nomina_id','=','2')
        ->get();
        $contrat = DB::table('contratos')
        ->leftJoin('empleados AS E','E.id','=','contratos.empleado_id')
        ->leftJoin('proyectos AS P','P.id','=','contratos.proyecto_id')
        ->leftJoin('nominas_movimientos AS MN','MN.contrato_id','=','contratos.id')
        ->select(DB::raw('SUM(MN.transferencias) AS totaltr'))
        ->where('P.id','=',$value->id)
        ->where('MN.nomina_id','=',$this->id)
        ->where('contratos.tipo_nomina_id','=','2')
        ->get();
        $contra = DB::table('contratos')
        ->leftJoin('empleados AS E','E.id','=','contratos.empleado_id')
        ->leftJoin('proyectos AS P','P.id','=','contratos.proyecto_id')
        ->leftJoin('nominas_movimientos AS MN','MN.contrato_id','=','contratos.id')
        ->select(DB::raw('SUM(MN.efectivos) AS totalef'))
        ->where('P.id','=',$value->id)
        ->where('MN.nomina_id','=',$this->id)
        ->where('contratos.tipo_nomina_id','=','2')
        ->get();
        $contr = DB::table('contratos')
        ->leftJoin('empleados AS E','E.id','=','contratos.empleado_id')
        ->leftJoin('proyectos AS P','P.id','=','contratos.proyecto_id')
        ->leftJoin('nominas_movimientos AS MN','MN.contrato_id','=','contratos.id')
        ->select(DB::raw('SUM(MN.totales) AS totalto'))
        ->where('P.id','=',$value->id)
        ->where('MN.nomina_id','=',$this->id)
        ->where('contratos.tipo_nomina_id','=','2')
        ->get();

        // foreach ($contrato as $key => $contra) {
        //
        // }




        // $contrato = \App\Contrato::where('id','=',$value->)->get();

        $arreglo = [
          'Proyectos' => $value,
          'contrato' => $contrato,
          'contrat' => $contrat[0],
          'contra' => $contra[0],
          'contr' =>  $contr[0],


        ];
      }
      $contrat_to = DB::table('contratos')
      ->leftJoin('empleados AS E','E.id','=','contratos.empleado_id')
      ->leftJoin('proyectos AS P','P.id','=','contratos.proyecto_id')
      ->leftJoin('nominas_movimientos AS MN','MN.contrato_id','=','contratos.id')
      ->select(DB::raw('SUM(MN.transferencias) AS totaltr_to'))
      // ->where('P.id','=',$value->id)
      ->where('MN.nomina_id','=',$this->id)
      ->where('contratos.tipo_nomina_id','=','2')
      ->first();
      $contra_to = DB::table('contratos')
      ->leftJoin('empleados AS E','E.id','=','contratos.empleado_id')
      ->leftJoin('proyectos AS P','P.id','=','contratos.proyecto_id')
      ->leftJoin('nominas_movimientos AS MN','MN.contrato_id','=','contratos.id')
      ->select(DB::raw('SUM(MN.efectivos) AS totalef_to'))
      // ->where('P.id','=',$value->id)
      ->where('MN.nomina_id','=',$this->id)
      ->where('contratos.tipo_nomina_id','=','2')
      ->first();
      $contr_to = DB::table('contratos')
      ->leftJoin('empleados AS E','E.id','=','contratos.empleado_id')
      ->leftJoin('proyectos AS P','P.id','=','contratos.proyecto_id')
      ->leftJoin('nominas_movimientos AS MN','MN.contrato_id','=','contratos.id')
      ->select(DB::raw('SUM(MN.totales) AS totalto_to'))
      // ->where('P.id','=',$value->id)
      ->where('MN.nomina_id','=',$this->id)
      ->where('contratos.tipo_nomina_id','=','2')
      ->first();
      // $empleados = \App\


        return view('excel.quincenal', compact('contratosp','arreglo','contrat_to','contra_to','contr_to','nomina'));
    }
}
