<?php

namespace App\Imports;

use App\EdenRedUser;
use App\EdenRedMovimiento;
use App\EdenRedEncabezadoMov;
use App\Empleado;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');


// class EdenRedUserImport implements ToCollection
class EdenRedEncabezadoMovImport implements ToCollection, WithHeadingRow
{
  /**
  * @param array $row
  *
  * @return \Illuminate\Database\Eloquent\Model|null
  */
  public function collection(Collection $rows)
  {
    // return var_dump($rows);

      $encabezado = EdenRedEncabezadoMov::
      where('dato_uno','=',$rows[0]['Administrador:'])
      ->where('dato_dos','=',$rows[0]['RODOLFO RODRIGUEZ .'])
      ->first();

      if (isset($encabezado) == false) {

        $encabezado_mov = new EdenRedEncabezadoMov();
        $encabezado_mov->dato_uno = $rows[0]['Administrador:'];
        $encabezado_mov->dato_dos = $rows[0]['RODOLFO RODRIGUEZ .'];
        $encabezado_mov->save();

      $movimientos = EdenRedMovimiento::where('estado','0')->get();
      foreach ($movimientos as $key => $val) {
        $mov = EdenRedMovimiento::where('id',$val->id)->first();
        $mov->estado = 1;
        $mov->encabezado_movimiento_id = $encabezado_mov->id;
        $mov->save();
      }
      }


  }

  public function headingRow(): int
  {
    return 4;
  }
}
