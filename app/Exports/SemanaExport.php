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



class SemanaExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $id;
    private $arreglo1;

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
          'background-color' => '#fff00f'
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
          'font' => [
            'bold'       =>  true,
            'color' => ['argb' => 'FFFFFF'],
          ],
        ],
        [
          'background' => [
            'color'=> '#08298A'
          ]
        ],
        // 5
        [
          'font' =>
                [
                  'name' => 'Arial',
                  'size' => 11,
                  'bold' => true,
                  'background' => '#ffffff',
                ]
        ],
        //6
        [
          'font' =>
                [
                  'name' => 'Arial',
                  'size' => 11,
                  'bold' => true,
                  'background' => '#ffffff',
                ]
        ],
        [
          'font' =>
                [
                  'name' => 'Calibri',
                  'size' => 14,
                  'bold' => true,
                  'background' => '#ff0000'
                ]
        ],
        //8
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

          // $event->sheet->getStyle('A1:AC300')->applyFromArray($styleArray[9]);
          $event->sheet->getStyle('C6')->applyFromArray($styleArray[1]);
          $event->sheet->getStyle('B7')->applyFromArray($styleArray[3]);

          $event->sheet->getStyle('A10')->applyFromArray($styleArray[3]);
          $event->sheet->getStyle('B10')->applyFromArray($styleArray[3]);
          $event->sheet->getStyle('C10')->applyFromArray($styleArray[3]);
          $event->sheet->getStyle('D10')->applyFromArray($styleArray[3]);
          $event->sheet->getStyle('E10')->applyFromArray($styleArray[3]);
          $event->sheet->getStyle('F10')->applyFromArray($styleArray[3]);
          $event->sheet->getStyle('G10')->applyFromArray($styleArray[3]);
          $event->sheet->getStyle('H10')->applyFromArray($styleArray[3]);
          $event->sheet->getStyle('A9:H9')->applyFromArray($styleArray[5]);
          $event->sheet->getStyle('L9:M9')->applyFromArray($styleArray[5]);
          $event->sheet->getStyle('I9:K9')->applyFromArray($styleArray[6]);
          $event->sheet->getStyle('A9:H9')->applyFromArray($styleArray[6]);

          $event->sheet->getStyle('A7:D7')->applyFromArray($styleArray[7]);
          //Alto Fila 9
          $event->sheet->getRowDimension(9)->setRowHeight(50);
          $event->sheet->getStyle('A9:P9')->applyFromArray($styleArray[8]);
        },
      ];
    }


    public function view(): View
    {
      $arreglo=[];

      $nomina = DB::table('nomina')->where('id',$this->id)->first();

      $nomina_movimientos = DB::table('nomina_movimientos AS nm','nm.nomina_id',$nomina->id)->get();
      $nomina_movimientos_proyecto = DB::table('nomina_movimientos AS nm')
      ->select('nm.proyecto_id')->groupBy('nm.proyecto_id')
      ->get();

      foreach ($nomina_movimientos as $key => $value) {
        

      }

      return view('excel.semanal',compact('contratosp','arreglo','contrat_to','contra_to','contr_to','nomina'));
    }
}
