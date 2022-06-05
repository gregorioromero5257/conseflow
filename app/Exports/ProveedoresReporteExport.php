<?php

namespace App\Exports;

use App\Proveedor;
use Exception;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use \App\Http\Helpers\Utilidades;


class ProveedoresReporteExport implements FromView, WithEvents
{
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
                    'color' => ['argb' => '000000'],
                ],
            ],
            [
                'font' => [
                    'bold'       =>  true,
                    'color' => ['argb' => '08298a'],
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
            [
                'alignment' =>
                [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]
        ];

        return [
            //todos los estilos los cargas aqui
            AfterSheet::class => function (AfterSheet $event) use ($styleArray)
            {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                $drawing->setPath(public_path('img/conserflow.png'));
                $drawing->setCoordinates('A2');
                $drawing->setHeight(60);
                $drawing->setWorksheet($event->sheet->getDelegate());

                $event->sheet->getStyle('B1')->applyFromArray($styleArray[0]);
                $event->sheet->getStyle('A1')->applyFromArray($styleArray[0]);
                $event->sheet->getStyle('A1')->applyFromArray($styleArray[6]);
                $event->sheet->getStyle('A6:M6')->applyFromArray($styleArray[1]);
                $event->sheet->getStyle('A6:M6')->applyFromArray($styleArray[5]);
                $event->sheet->getStyle('A2:M4')->applyFromArray($styleArray[5]);//s
                $event->sheet->getStyle('B2')->applyFromArray($styleArray[6]);
                $event->sheet->getStyle('B2')->applyFromArray($styleArray[7]);
                $event->sheet->getStyle('A2:M4')->applyFromArray($styleArray[6]);
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(35);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(30);
                $event->sheet->getRowDimension(6)->setRowHeight(30);

                // $$event->sheet->setBorder('A5', 'solid');
            },
        ];
    }

    public function view(): View
    {
        try
        {
            // obtener datos
            $oc = DB::table('ordenes_compras')->select('proveedore_id')->groupBy('proveedore_id')->get()->toArray();
            $pro = [];
            foreach ($oc as $koc => $voc)
            {
                $pro[] = $voc->proveedore_id;
            }

            $proveedor1 = Proveedor::leftJoin('evaluacion_provee AS ep', 'ep.proveedor_id', 'proveedores.id')
                ->select(
                    'proveedores.*',
                    DB::raw("(ep.uno+ep.dos+ep.tres+ep.cuatro+ep.cinco+ep.seis+ep.siete+ep.ocho+ep.nueve+ep.diez+ep.once+ep.doce+ep.trece+ep.catorce+ep.quince+ep.diesiseis+ep.diesisiete+ep.diesiocho) AS total_eval")
                )
                ->whereIn('proveedores.id', $pro)
                ->orderBy('id', 'desc')->get()->toArray();
            $aux = [];
            $c1 = DB::table('evaluacion_provee')->get();
            foreach ($proveedor1 as $p)
            {
                $c = DB::table('evaluacion_provee')->where('proveedor_id', $p["id"])->first();
                $total = 0;
                if (isset($c) == true)
                {
                    $total = $c->uno + $c->dos + $c->tres + $c->cuatro + $c->cinco + $c->seis + $c->siete + $c->ocho + $c->nueve + $c->diez + $c->once + $c->doce + $c->trece
                        + $c->catorce + $c->quince + $c->diesiseis + $c->diesisiete + $c->diesiocho;
                }
                array_push($aux, $p);
            }

            return view('excel.provReporte', compact('aux'));
        }
        catch (Exception $e)
        {
          Utilidades::errors($e);
          return view('errors.204');
        }
    }
}
