<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use App\PartidasFactura;
use App\SatCatProdSer;
use App\SatCatUnidades;
use \App\Http\Helpers\Utilidades;

// class EdenRedUserImport implements ToCollection

HeadingRowFormatter::default('none');

class PartidasFacturasImport implements ToCollection, WithHeadingRow
{
  protected $id;

  public function __construct(int $id)
  {
      $this->id = $id;
  }
  /**
  * @param array $row
  *
  * @return \Illuminate\Database\Eloquent\Model|null
  */
  public function collection(Collection $rows)
  {
    try {

      $ps_id = 0;
      foreach ($rows as $key => $value) {
        $sat_catprodserv = SatCatProdSer::where('clave','=',$value['PRODUCTO Y SERVICIO'])->first();

      if (isset($sat_catprodserv) == false ) {
        $sat_catprodserv_big = DB::table('sat_cat_prodser_big')->where('clave','=',$value['PRODUCTO Y SERVICIO'])->first();
        $ps_id = $sat_catprodserv_big->id;
      }else {
        $ps_id = $sat_catprodserv->id;
      }

      $sat_cat_unidades_id = DB::table('sat_cat_unidades')->where('c_unidad',$value['CLAVE UNIDAD'])->first();

      $cantidad = $value['CANTIDAD'];
      $unidad = $value['UNIDAD'];
      $num_identificacion = $value['NUMERO DE IDENTIFICACIÓN'];
      $descripcion = str_replace('\n',' ',$value['DESCRIPCIÓN']);
      $valor_unitario = $value['VALOR UNITARIO'];
      $importe = (float)$cantidad * (float)$valor_unitario;
      $iva = $value['IVA'];
      $retencion = $value['RENTENCION'];

      $partidas_facturas = new PartidasFactura();
      $partidas_facturas->productos_servicios_id = $ps_id;
      $partidas_facturas->unidad_id = $sat_cat_unidades_id->id;
      $partidas_facturas->cantidad = (float)$cantidad;
      $partidas_facturas->unidad = (string)$unidad;
      $partidas_facturas->numero_identificacion = (string)$num_identificacion;
      $partidas_facturas->descripcion = (string)$descripcion;
      $partidas_facturas->valor_unitario = (float)$valor_unitario;
      $partidas_facturas->importe = $importe;
      $partidas_facturas->impuesto_iva = (float)$iva;
      $partidas_facturas->factura_id = $this->id;
      $partidas_facturas->retencion = (float)$retencion;
      $partidas_facturas->valor_impuesto = $importe * (float)$iva;
      $partidas_facturas->save();
      }

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }

  public function headingRow(): int
  {
    return 1;
  }



}
