<?php

namespace App\Imports;

use App\EdenRedUser;
use App\EdenRedMovimiento;
use App\Empleado;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Carbon\Carbon;

HeadingRowFormatter::default('none');

// class EdenRedUserImport implements ToCollection
class EdenRedMovimientosImport implements ToCollection, WithHeadingRow
{
  /**
  * @param array $row
  *
  * @return \Illuminate\Database\Eloquent\Model|null
  */
  public function collection(Collection $rows)
  {
    // return var_dump($rows);
    foreach ($rows as $key => $value) {
      $fecha_hora = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value['Fecha de Movimiento']));
      $movimientos = EdenRedMovimiento::
      where('fecha_movimiento','=',$fecha_hora)
      ->where('numero_nomina','=',$value['Número de Nomina'])
      ->where('concepto','=',$value['Concepto'])
      ->where('descripcion_comercio','=',$value['Descripción / Comercio'])
      ->where('tipo_comercio','=',$value['Tipo de Comercio'])
      ->where('rfc_comercio','=',$value['RFC de comercio'])
      ->where('id_movimiento','=',$value['Id Movimiento'])
      ->where('numero_autorizacion','=',$value['Número de Autorización'])
      ->where('numero_control_edo_cuenta','=',$value['No. Control Edo. de Cuenta'])
      ->where('abono','=',$value['Abono'])
      ->where('valor_pesos','=',$value['Valor (pesos)'])
      ->where('comision_iva','=',$value['Comisión + IVA'])
      ->where('valor_moneda_extranjera','=',$value['Valor (Moneda Extranjera)'])
      ->where('descripcion_moneda_extranjera','=',$value['Descripción (Moneda Extranjera)'])
      ->where('tipo_cambio_pesos','=',$value['Tipo de cambio (pesos por x)'])
      ->where('observaciones','=',$value['Observaciones'])
      ->first();

      if (isset($movimientos) == false) {
        EdenRedMovimiento::create([
          'fecha_movimiento' => $fecha_hora,
          'numero_nomina' => $value['Número de Nomina'],
          'concepto' => $value['Concepto'],
          'descripcion_comercio' => $value['Descripción / Comercio'],
          'tipo_comercio' => $value['Tipo de Comercio'],
          'rfc_comercio' => $value['RFC de comercio'],
          'id_movimiento' => $value['Id Movimiento'],
          'numero_autorizacion' => $value['Número de Autorización'],
          'numero_control_edo_cuenta' => $value['No. Control Edo. de Cuenta'],
          'abono' => $value['Abono'],
          'valor_pesos' => $value['Valor (pesos)'],
          'comision_iva' => $value['Comisión + IVA'],
          'valor_moneda_extranjera' => $value['Valor (Moneda Extranjera)'],
          'descripcion_moneda_extranjera' => $value['Descripción (Moneda Extranjera)'],
          'tipo_cambio_pesos' => $value['Tipo de cambio (pesos por x)'],
          'observaciones' => $value['Observaciones'],
        ]);
      }
    }
  }

  public function headingRow(): int
  {
    return 7;
  }
}
