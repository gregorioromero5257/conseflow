<?php

namespace App\Exports;

use App\Existencia;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
// use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
// use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
// use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class ReporteListasExport implements FromView, ShouldAutoSize, WithEvents
// WithStrictNullComparison
{
  protected $id;

  public function __construct($id)
  {
    $this->id = $id;
  }

  public function registerEvents(): array
  {

    $styleArray = [
      /****Inicia la posicion 0****/
      [
        'font' => [
          'bold' => true,
          'color' => ['argb' => '0489B1'],

        ],
      ],
      /****fin la posicion 0****/
      /****Inicia la posicion 1****/
      [
        'font' => [
          'bold' => true,
          'color' => ['argb' => 'ffffff   '],

        ],
      ], /****fin la posicion 1****/
      [
        'font' => [
          'bold' => true,
          'color' => ['argb' => 'ffffff'],

        ],
      ],
      /*
      [   ]
      puedes crear mas posiciones aqui en el array y llamarlos en el AfterSheet
      */
    ];
    return [
      //todos los estilos los cargas aqui
      AfterSheet::class => function (AfterSheet $event) use ($styleArray,$tam) {

        // $event->sheet->mergeCells("a".(1).":B".(1));
        // $this->parseWidth(0, 0,'A', 100);
        // $event->sheet->getStyle('A4:EE100')->applyFromArray($styleArray[1]);
        // $event->sheet->getStyle('A3:EE3')->applyFromArray($styleArray[2]);
        // set dropdown column
              $drop_column = 'A';

              // set dropdown options
              $options = [
                  'option 1',
                  'option 2',
                  'option 3',
              ];

              // set dropdown list for first data row
              // $validation = $event->sheet->getCell("{$drop_column}7")->getDataValidation();
              // $validation->setType(DataValidation::TYPE_LIST );
              // $validation->setErrorStyle(DataValidation::STYLE_INFORMATION );
              // $validation->setAllowBlank(false);
              // $validation->setShowInputMessage(true);
              // $validation->setShowErrorMessage(true);
              // $validation->setShowDropDown(true);
              // $validation->setErrorTitle('Input error');
              // $validation->setError('Value is not in list.');
              // $validation->setPromptTitle('Pick from list');
              // $validation->setPrompt('Please pick a value from the drop-down list.');
              // $validation->setFormula1(sprintf('"%s"',implode(',',$options)));

        // $event->sheet->getStyle('A1:B1')->applyFromArray($styleArray[2]);
        // $event->sheet->getStyle('')->getFill()->setFillType('solid')->getStartColor()->setARGB('000000');

        $event->sheet->getStyle('A2:H2')->applyFromArray($styleArray[1]);//se llama el array de la posicion 0
        $event->sheet->getStyle('A3:H3')->applyFromArray($styleArray[1]);//se llama el array de la posicion 0
        $event->sheet->getStyle('A4:I4')->applyFromArray($styleArray[1]);//se llama el array de la posicion 0
        $event->sheet->setCellValue('D6','=SUM(B6:C6)');
        // $event->sheet->getStyle('A4:A'.$tam)->applyFromArray($styleArray[1]);//se llama el array de la posicion 0
        // $event->sheet->getStyle('A3:BB3')->getFill()->setFillType('solid')->getStartColor()->setARGB('e68a00');

        /*getFill()->setFillType('solid')->getStartColor()->setARGB('5882FA');*/
      },
    ];
  }

  public function getInicioFinSemana($week, $year)
  {
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $ret['inicio'] = $dto->format('Y-m-d');
    $dto->modify('+6 days');
    $ret['fin'] = $dto->format('Y-m-d');
    return $ret;
  }

  public function view(): View
  {
    $valores = explode('&',$this->id);
    $supervisores = DB::table('control_tiempo AS ct')->select('ct.*')
    ->whereBetween('fecha',[$valores[0],$valores[1]])
    ->get();

    return view('excel.controltiempo', compact());
  }

}
