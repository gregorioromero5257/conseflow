<?php

namespace App\Imports;

use App\CoutaImssEMA;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use \App\Http\Helpers\Utilidades;

// class EdenRedUserImport implements ToCollection

HeadingRowFormatter::default('none');

class CoutaImssEMAImport implements ToCollection, WithHeadingRow
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
      foreach ($rows as $key => $value) {
      $cuotaImssEMA = new CoutaImssEMA();
      $cuotaImssEMA->nombre = $value['Nombre'];
      $cuotaImssEMA->dias = $value['Días'];
      $cuotaImssEMA->subtotal = $value['SubTotal'];
      $cuotaImssEMA->mes = $this->id;
      $cuotaImssEMA->anio = date('Y');
      $cuotaImssEMA->save();
    }
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }

  public function headingRow(): int
  {
    return 11;
  }



}
