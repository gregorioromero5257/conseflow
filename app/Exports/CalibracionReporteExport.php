<?php

namespace App\Exports;

use App\EquiposCatalogo;
use App\Http\Controllers\EquiposCalibracionController;
use Exception;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class CalibracionReporteExport  implements FromView, WithEvents
{
    /**
     * @return array
     */
    public function registerEvents(): array
    {

        $styleArray = [
            [
                'font' => [ //0
                    'bold'       =>  true,
                    'color' => ['argb' => '0489B1'],
                    'size'=> 12
                ],
            ],
            [
                'font' => [
                    'bold'       =>  true,
                    'color' => ['argb' => 'FFFFFF'],
                ],
            ],
            [
                'font' => [  // 2
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
                'alignment' =>   // 4
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
                'alignment' =>  // 6
                [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
            [
                'alignment' =>
                [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
            [
                'font' => [ // 8
                    'bold'       =>  true,
                    'color' => ['argb' => 'ffffff'],
                ],
            ],
            [
                'font' => [
                    'bold'       =>  true,
                    'color' => ['argb' => '000'],
                ],
            ],
            [
                'borders' => [  // 10
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        'color' => ['argb' => '000'],
                    ],
                ],
            ],
            [
                'font' => [
                    'bold'       =>  true,
                    'color' => ['argb' => '000'],
                    'size'=> 15
                ],
            ],
            [
                'font' => [
                    'bold'       =>  true,
                    'color' => ['argb' => '000'],
                    'size'=> 14
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

                $event->sheet->getStyle('A1')->applyFromArray($styleArray[0]);
                $event->sheet->getStyle('B1')->applyFromArray($styleArray[0]);
                $event->sheet->getStyle('A1')->applyFromArray($styleArray[6]);
                $event->sheet->getStyle('A6:J6')->applyFromArray($styleArray[1]);
                // $event->sheet->getStyle('A6:J6')->applyFromArray($styleArray[5]);
                // $event->sheet->getStyle('A2:J4')->applyFromArray($styleArray[5]); //s
                $event->sheet->getStyle('B2')->applyFromArray($styleArray[6]);
                $event->sheet->getStyle('B2')->applyFromArray($styleArray[7]);
                $event->sheet->getStyle('A2:H6')->applyFromArray($styleArray[6]);

                $event->sheet->getStyle('F6:H6')->applyFromArray($styleArray[8]);
                $event->sheet->getStyle('F6:H6')->applyFromArray($styleArray[9]);
                $event->sheet->getStyle('F7:H7')->applyFromArray($styleArray[8]);
                
                // Borde delgado
                $event->sheet->getStyle('I2:J4')->applyFromArray($styleArray[5]);
                // Borde grueso
                $event->sheet->getStyle('A1:J4')->applyFromArray($styleArray[10]);
                // Centrado
                $event->sheet->getStyle('A1:J1')->applyFromArray($styleArray[10]);
                $event->sheet->getStyle('A6:J7')->applyFromArray($styleArray[5]);
                $event->sheet->getStyle('A6:J7')->applyFromArray($styleArray[5]);
                $event->sheet->getStyle('A6:J7')->applyFromArray($styleArray[6]);
                $event->sheet->getStyle('A6:J7')->applyFromArray($styleArray[7]);
                
                // Titulo
                $event->sheet->getStyle('A2:H4')->applyFromArray($styleArray[11]);
                $event->sheet->getStyle('A2:H4')->applyFromArray($styleArray[12]);
                $event->sheet->getStyle('A2:H4')->applyFromArray($styleArray[6]);
                $event->sheet->getStyle('A2:H4')->applyFromArray($styleArray[7]);
                
                // Width
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(20);


                // $$event->sheet->setBorder('A5', 'solid');
            },
        ];
    }

    public function view(): View
    {
        // try
        // {
            $aux=[];
            $equipos = DB::table("equipos_catalogo")
            ->orderBy("descripcion")
            ->get();
            foreach ($equipos as $equipo)
            {
                $servicio = DB::table("servicios_equipos_calibracion as sec")
                    ->where("equipos_catalogo_id", $equipo->id)
                    ->orderBy("id", "desc")
                    ->first();
                $proxima_fecha = "N/D";
                $fecha_servicio = "N/D";
                $id = 0;
                $estado = $servicio != null ? $servicio->estado : 0;
                if ($servicio != null)
                {
                    $proxima_fecha = $servicio->proxima_fecha;
                    $fecha_servicio = $servicio->fecha_servicio;
                    $id = $servicio->id;
                }

                $equipo->proxima_fecha = $proxima_fecha;
                $equipo->fecha_servicio = $fecha_servicio;
                $equipo->estado_equipo = $estado;
                $equipo->servicio_id = $id;
                $aux[]=(array)$equipo;
            }   
            return view('excel.calibReporte', compact("aux"));
        // }
        // catch (Exception $e)
        // {
        //     Utilidades::errors($e);
        //     return view('errors.204');
        // }
    }
}
