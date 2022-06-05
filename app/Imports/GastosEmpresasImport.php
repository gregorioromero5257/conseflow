<?php

namespace App\Imports;

use App\GastosEmpresas;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use \App\Http\Helpers\Utilidades;
use DateTime;
use Carbon\Carbon;


// class EdenRedUserImport implements ToCollection

HeadingRowFormatter::default('none');

class GastosEmpresasImport implements ToCollection, WithHeadingRow
{
  protected $id;
  public function __construct(String $id)
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
      $arreglo = [];
      DB::beginTransaction();
      foreach ($rows as $key => $value) {

          //
          $centro_costos_id = $this->obtenerCentroCostos($value['TIPO DE GASTO']);
          $fecha = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value['Fecha']));
          $folio = $this->obtenerFolio($value['ORDEN DE COMPRA'],$this->id,$fecha);

          //BUSCAMOS SI EXIXTE EL REGISTRO
          $GastosEmpresas_buscar = GastosEmpresas::
          where('fecha',$fecha)
          ->where('proveedor_acreedor',$value['Proveedor  o acreedor'])
          // ->where('proyecto',$value['Proyecto'])
          // ->where('tipo',$value['TIPO'])
          ->where('tipo_gasto',$value['TIPO DE GASTO'])
          ->where('orden_compra',$value['ORDEN DE COMPRA'])
          ->where('cargo',$value['Cargo'])
          ->where('moneda',$value['moneda'])
          ->where('empresa',$this->id)
          ->first();

          if (isset($GastosEmpresas_buscar) == false) {
            $GastosEmpresas = new GastosEmpresas();
            $GastosEmpresas->fecha = $fecha;
            $GastosEmpresas->proveedor_acreedor = $value['Proveedor  o acreedor'];
            $GastosEmpresas->proyecto = $value['Proyecto'];
            $GastosEmpresas->tipo = $value['TIPO'];
            $GastosEmpresas->tipo_gasto = $value['TIPO DE GASTO'];
            $GastosEmpresas->centro_costos_id = $centro_costos_id;
            $GastosEmpresas->orden_compra = $value['ORDEN DE COMPRA'];
            $GastosEmpresas->cargo = $value['Cargo'];
            $GastosEmpresas->moneda = $value['moneda'];
            $GastosEmpresas->mes = $value['mes'];
            $GastosEmpresas->empresa = $this->id;
            $GastosEmpresas->folio = $folio;
            $GastosEmpresas->save();
          }
          //   $GastosEmpresas->tipo = $value['TIPO'];
          //   $GastosEmpresas->centro_costos_id = $centro_costos_id;
          //   $GastosEmpresas-sema>cargo = $value['Cargo'];
          //   $GastosEmpresas->tipo_moneda = $value['moneda'];
          //   $GastosEmpresas->proveedor_acreedor = $value['Proveedor  o acreedor'];
          //   $GastosEmpresas->empresa = $this->id;
          //   $GastosEmpresas->save();
      }
      DB::commit();
    } catch (\Throwable $e) {
      DB::rollBack();
      Utilidades::errors($e);
    }
  }

  public function headingRow(): int
  {
    return 1;
  }

  public function obtenerCentroCostos($value)
  {
    $id = 0;
    switch ($value) {
      case 'ALMACÉN': $id = 49; break;
      case 'ADMINISTRACIÓN': $id = 50; break;
      case 'VEHICULOS': $id = 51; break;
      case 'SEGURIDAD': $id = 52; break;
      case 'FINANZAS': $id = 53; break;
      case 'FINANCIAMIENTO': $id = 53; break;
      case 'VENTAS': $id = 54; break;

    }
    return $id;
  }

  public function obtenerFolio($oc, $empresa,$fecha)
  {
    $gastos_e = GastosEmpresas::where('orden_compra',$oc)->where('empresa',$empresa)->get();
    $tamanio = count($gastos_e);

    $folio =  'OC-'.strtoupper($empresa).'-'.substr($fecha, 1,3).'-'.$oc.'-'.str_pad(($tamanio + 1),3,'0',STR_PAD_LEFT);
    return $folio;
  }



}
