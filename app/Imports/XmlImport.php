<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use \App\Http\Helpers\Utilidades;

// class EdenRedUserImport implements ToCollection
HeadingRowFormatter::default('none');

class XmlImport implements ToCollection, WithHeadingRow
{


  /**
  * @param array $row
  *
  * @return \Illuminate\Database\Eloquent\Model|null
  */
  public function collection(Collection $rows)
  {

    try {
      dd($rows);

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }



  // public function headingRow(): int
  // {
  //   return 3;
  // }



}
