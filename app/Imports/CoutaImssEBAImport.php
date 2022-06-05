<?php

namespace App\Imports;

use App\CoutaImssEBA;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use \App\Http\Helpers\Utilidades;
// class EdenRedUserImport implements ToCollection

HeadingRowFormatter::default('none');

class CoutaImssEBAImport implements ToCollection, WithHeadingRow
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
    // echo "".var_dump($rows);

    try {
      foreach ($rows as $key => $value) {
      $cuotaImssEBA = new CoutaImssEBA();
      $cuotaImssEBA->nombre = $value['Nombre'];
      $cuotaImssEBA->dias = $value['Días'];
      $cuotaImssEBA->subtotal = $value['Suma'];
      $cuotaImssEBA->meses = $this->id;
      $cuotaImssEBA->anio = date('Y');
      $cuotaImssEBA->save();
    }
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function headingRow(): int
  {
    return 12;
  }



}
