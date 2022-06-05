<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use App\ViaticosGastosMenores;
use \App\Http\Helpers\Utilidades;
use App\Proyecto;
use App\Empleado;
use App\TiposComprobacion;

// class EdenRedUserImport implements ToCollection

HeadingRowFormatter::default('none');

class ViaticosGastosImport implements ToCollection, WithHeadingRow
{
  /**
  * @param array $row
  *
  * @return \Illuminate\Database\Eloquent\Model|null
  */
  public function collection(Collection $rows)
  {

    try {
      foreach ($rows as $key => $value) {
        $date = date_create('30-12-1899');
        date_add($date, date_interval_create_from_date_string($value['Fecha'].' days'));
        $fecha = date_format($date, 'Y-m-d');

        $proyecto_id = Proyecto::where('nombre_corto','like','%'.$value['Proyecto'].'%')->first();

        $catalogo_tipos = TiposComprobacion::where('nombre','like','%'.$value['TIPO'].'%')->first();

        $empleado_id = Empleado::where(DB::raw("CONCAT(nombre,' ',ap_paterno,' ',ap_materno)"),'LIKE',$value['Proveedor  o acreedor'])->first();
        $proyecto = $value['Proyecto'];
        $centro_costos = $this->Casos($value['TIPO'],$proyecto);

        $c = new ViaticosGastosMenores();
        $c->fecha = $fecha;
        $c->descripcion = $value['Descripción'];
        $c->cargo = $value['Cargo'];
        $c->proveedor_acredor = $value['Proveedor  o acreedor'];
        $c->empleado_id = (isset($empleado_id) == true) ? $empleado_id->id : null;
        $c->proyecto = $value['Proyecto'];
        $c->proyecto_id = (isset($proyecto_id) == true) ? $proyecto_id->id : null;
        $c->tipo = (isset($catalogo_tipos) == true)  ? $catalogo_tipos->id : null;
        $c->moneda = $value['Moneda'];
        $c->banco = $value['Banco'];
        $c->mes = $value['Mes'];
        $c->centrocostos_id = $centro_costos;
        $c->save();

      }
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }



  }


  public function headingRow(): int
  {
    return 1;
  }

  public function Casos($valor,$proyecto)
  {
    $resultado = 0;
    if ($proyecto != 'ADMON') {
      switch ($valor) {
        case 'GTOS MENORES':
        $resultado = 31;
        break;
        case 'GASTOS MENORES':
        $resultado = 31;
        break;
        case 'REEMB GASTOS MENORES':
        $resultado = 31;
        break;
        case 'GTOS EQ TRANSPORTE':
        $resultado = 31;
        break;
        case 'GASTOS POR COMPROBAR':
        $resultado = 31;
        break;
        case 'GASTOS COMPROBAR':
        $resultado = 31;
        break;
        case 'viaticos':
        $resultado = 24;
        break;
        case 'VIATICOS':
        $resultado = 24;
        break;
        case 'ALIMENTOS':
        $resultado = 24;
        break;
      }
    }else {
      switch ($valor) {
        case 'VIATICOS':
        $resultado = 34;
        break;
        case 'viaticos':
        $resultado = 34;
        break;
        case 'GASTOS DE VIAJE':
        $resultado = 34;
        break;
        case 'GASTOS VIAJE':
        $resultado = 34;
        break;
        case 'GASTOS POR COMPROBAR':
        $resultado = 41;
        break;
        case 'GASTOS COMPROBAR':
        $resultado = 41;
        break;
        case 'GTOS MENORES':
        $resultado = 41;
        break;
        case 'GASTOS MENORES':
        $resultado = 41;
        break;
      }
    }

    return $resultado;
  }



}
