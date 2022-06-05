<?php

namespace App\Exports;

use App\Existencia;
use App\Proyecto;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class CajaChicaComprobacionExport implements FromView, WithEvents
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
          'color' => ['argb' => 'FFFFFF'],
        ],
      ],
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
      [
        'alignment' =>
        [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
        ],
      ],
      [
          'borders' => [
              'allBorders' => [
                  'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                  'color' => ['argb' => '000000'],
              ],
          ],
      ],
      [
          'alignment' =>
          [
              'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          ],
      ],

      /****fin la posicion 1****/
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
        // $drawing->setPath(public_path('img/conserflow.png'));
        // $drawing->setCoordinates('A2');
        // $drawing->setHeight(60);
        // $drawing->setWorksheet($event->sheet->getDelegate());


        // $event->sheet->getDelegate()->getRowDimension(6)->setRowHeight(30);

        $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(25);
        $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(35);
        $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
        $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(8);
        $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(8);
        $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(6);
        $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(6);
        $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(6);
        $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(6);
        $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(6);
        $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(6);
        // $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(60);
        // $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
        // $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
        // $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(20);
        // $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
        // $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
        // $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);

        //$event->sheet->getStyle('A7:N7')->applyFromArray($styleArray[1]);//se lllama el array de la posicion 0
        $event->sheet->getStyle('A4:O4')->applyFromArray($styleArray[1]);//se lllama el array de la posicion 0
        //$event->sheet->getStyle('A1')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        //$event->sheet->getStyle('B2')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        //$event->sheet->getStyle('G2')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        //$event->sheet->getStyle('H2')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        //$event->sheet->getStyle('G3')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        //$event->sheet->getStyle('H3')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        //$event->sheet->getStyle('H4')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6
        //$event->sheet->getStyle('G4')->applyFromArray($styleArray[6]);//se lllama el array de la posicion 6

        // $event->sheet->setBorder('A1:F10', 'thin');

      },
    ];
  }


  public function view(): View
  {

    $valores =explode("&",$this->id);

    $solicitud_caja_chica = \App\CajaChicaSolicitud::
    join('empleados AS e','e.id','solicitud_caja_chica.empleado_user_id')
    ->select('solicitud_caja_chica.folio',DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS nombre_user"))
    ->where('solicitud_caja_chica.id',$valores[0])->first();

    $comprobacion = \App\ComprobacionesCajaChica::
    join('proyectos AS p','p.id','comprobaciones_caja_chica.centro_costos_id')
    ->join('gastos_xml_oc AS gxoc','gxoc.id','comprobaciones_caja_chica.factura_id')
    ->select('comprobaciones_caja_chica.*','gxoc.fecha','gxoc.nombre_e','gxoc.rfc_e','gxoc.id AS gx_id',
    'p.nombre_corto','gxoc.total','gxoc.subtotal')
    ->where('cajachica_id',$valores[0])
    ->where('empresa',$valores[1])
    // ->where('estado','!=','1')
    ->get();

    $arreglo = [];

    foreach ($comprobacion as $key => $value) {


      $impuesto = \App\ImpuestosOCXml::where('gastos_xml_oc_id',$value->gx_id)
      ->where('base',NULL)
      ->where('tipo','t')
      ->first();

      $retencion = \App\ImpuestosOCXml::where('gastos_xml_oc_id',$value->gx_id)
      ->where('base',NULL)
      ->where('tipo','r')
      ->first();

      $concepto = \App\ConceptosOCXml::where('gastos_xml_oc_id',$value->gx_id)->first();

      $arreglo [] = [
        'comprobacion' =>  $value,
        'impuestos' => $impuesto,
        'retencion' => $retencion,
        'concepto' => $concepto,
      ];
    }


    // dd($arreglo);

    $b = 'hola';

    return view('excel.comprobacion', compact('solicitud_caja_chica','arreglo'));
  }

  public function mes($value)
  {
    $mes = '';
    switch ($value) {
      case '01': $mes = 'Enero';break;
      case '02': $mes = 'Febrero';break;
      case '03': $mes = 'Marzo';break;
      case '04': $mes = 'Abril';break;
      case '05': $mes = 'Mayo';break;
      case '06': $mes = 'Junio';break;
      case '07': $mes = 'Julio';break;
      case '08': $mes = 'Agosto';break;
      case '09': $mes = 'Septiembre';break;
      case '10': $mes = 'Octubre';break;
      case '11': $mes = 'Noviembre';break;
      case '12': $mes = 'Diciembre';break;

    }
    return $mes;
  }
}
