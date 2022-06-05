<?php
namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\SetWidth;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use  Maatwebsite\Excel\Sheet;

class ReporteAlmacenExport implements FromView, ShouldAutoSize, WithEvents,WithTitle
{
    public function view(): View
    {
      // Consulta
      $articulos=DB::table("lote_almacen as la")
      ->Join("articulos as a","la.articulo_id","a.id")
      ->Join("lotes as l","la.lote_id","l.id")
      ->leftJoin("niveles as n","la.nivel_id","n.id")
      ->leftJoin("stands as s","la.stand_id","s.id")
      ->leftJoin("almacenes as  alm","la.almacene_id","alm.id")
      ->Join("partidas_entradas as  pe","l.entrada_id","pe.id")
      ->Join("requisicion_has_ordencompras as  rho","pe.req_com_id","rho.id")
      ->Join("ordenes_compras as oc","rho.orden_compra_id","oc.id")
      ->Join("proveedores as p","oc.proveedore_id","p.id")
      ->select("a.id","a.nombre as concepto","l.nombre as lote",
               "s.nombre as stand","n.nombre as nivel","oc.folio as folio_compra",
               "alm.nombre as alm","p.nombre as proveedor",
               "la.cantidad as existencia_sistema")
      ->orderBy("concepto")
      ->get();

      return view("excel.reportealmacen",compact("articulos"));
    }

    public function registerEvents(): array
    {
      $styleArray =
      [
        "boldAzulClaro"  => [ 'font'  => [ 'bold' => true,  'color' => ['argb' => '0489B1']]], // 0 bold azul claro
        "boldNegro"      => [ 'font'  => [ 'bold' =>  true, 'color' => ['argb' => '000000']]], // 1 bold negro
        "boldBlanco"     => [ 'font'  => [ 'bold' =>  true, 'color' => ['argb' => 'ffffff']]], // 1 bold blanco
        "boldAzulFuerte" => [ 'font'  => [ 'bold' =>  true, 'color' => ['argb' => '08298A']]],  // 2 bold azul fuerte

      ];
      return [
        AfterSheet::class => function (AfterSheet $event) use ($styleArray)
        {
          $logo_conser = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
          $logo_conser->setName('Logo');
          $logo_conser->setDescription('Logo');
          $logo_conser->setPath(public_path('img/c.png'));
          $logo_conser->setCoordinates('A1');
          $logo_conser->setWorksheet($event->sheet->getDelegate());

          $event->sheet->getStyle("A8:L8")->applyFromArray($styleArray["boldBlanco"]); // Encabezado

          // Ajuste de Texto
          $event->sheet->getStyle('B1:B'. $event->sheet->getHighestRow())->getAlignment()->setWrapText(true);
        }
      ];
    }

    public function title():string
    {
      return "Almacén - " . date("d-m-Y");
    }
}
