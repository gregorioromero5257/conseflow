<?php

namespace App\Imports;

use App\SueldosSemanal;
use App\GastosESemanaEmpleado;
use App\GastosESemanaEmpleadoRegistros;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use \App\Http\Helpers\Utilidades;
use DateTime;

// class EdenRedUserImport implements ToCollection

HeadingRowFormatter::default('none');

class GastosESemanalImport implements ToCollection, WithHeadingRow
{
  protected $id;
  protected $empresa;

  public function __construct(String $id, String $empresa)
  {
    $this->id = $id;
    $this->empresa = $empresa;
  }
  /**
  * @param array $row
  *
  * @return \Illuminate\Database\Eloquent\Model|null
  */
  public function collection(Collection $rows)
  {

    try {
      $valores = explode(' a ',$this->id);
      // $arreglo = [];
      foreach ($rows as $key => $value) {
        // $arreglo [] = $value;
        $empleado_id = 0;
        if ($this->empresa === 'CONSERFLOW') {
          $empleado = \App\Empleado::
          where(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre)"),'LIKE','%'.$value['NOMBRE'].'%')
          ->first();
          if (isset($empleado) == true) {
            $empleado_id = $empleado->id;
          }
          else{
            $empleado_dos = \App\Empleado::
            where(DB::raw("CONCAT(nombre,' ',ap_paterno,' ',ap_materno)"),'LIKE','%'.$value['NOMBRE'].'%')->first();
            if (isset($empleado_dos) == true) {
              $empleado_id = $empleado_dos->id;
            }
            else {
              $empleado_id = 0;
            }
          }
        }elseif ($this->empresa === 'CSCT') {
          $empleado = \App\EmpleadoDBP::
          where(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre)"),'LIKE','%'.$value['NOMBRE'].'%')
          ->first();
          if (isset($empleado) == true) {
            $empleado_id = $empleado->id;
          }
          else{
            $empleado_dos = \App\EmpleadoDBP::
            where(DB::raw("CONCAT(nombre,' ',ap_paterno,' ',ap_materno)"),'LIKE','%'.$value['NOMBRE'].'%')->first();
            if (isset($empleado_dos) == true) {
              $empleado_id = $empleado_dos->id;
            }
            else {
              $empleado_id = 0;
            }
          }
        }

        if ($value['NOMBRE'] != 'NULL' && $value['NOMBRE'] != null  && $value['NOMBRE'] != '') {

        $SueldoSemanaEmpleado = GastosESemanaEmpleado::where('empresa',$this->empresa)->where('nombre',$value['NOMBRE'])->first();
        if (isset($SueldoSemanaEmpleado) == false) {
          $SueldoSemanaEmpleado = new GastosESemanaEmpleado();
          $SueldoSemanaEmpleado->empresa = $this->empresa;
          $SueldoSemanaEmpleado->empleado_id = $empleado_id;
          $SueldoSemanaEmpleado->nombre = $value['NOMBRE'];
          $SueldoSemanaEmpleado->save();
        }
        $year = date('Y');

        if (count($valores) > 1) {
          for ($i=$valores[0]; $i < $valores[1]; $i++) {
              if ($value[$i] != 'NULL' && $value[$i] != null  && $value[$i] != '') {
                $a = $this->getInicioFinSemana($i,$year);

                $arreglo_fechas_buscar = [];
                $fechaInicio=strtotime($a['inicio']);
                $fechaFin=strtotime($a['fin']);

                for($k=$fechaInicio; $k<=$fechaFin; $k+=86400){
                  $arreglo_fechas_buscar [] = date('Y-m-d',$k);
                }

                for ($j=0; $j <count($arreglo_fechas_buscar) ; $j++) {

                    $SueldoSemanaEmpleadoRegistros = new GastosESemanaEmpleadoRegistros();
                    $SueldoSemanaEmpleadoRegistros->gastos_e_semana_empleado_id = $SueldoSemanaEmpleado->id;
                    $SueldoSemanaEmpleadoRegistros->semana = $i;
                    $SueldoSemanaEmpleadoRegistros->fecha_i= $arreglo_fechas_buscar[$j];
                    $SueldoSemanaEmpleadoRegistros->total = $value[$i]/7;
                    $SueldoSemanaEmpleadoRegistros->save();
                }
              }
          }
        }else {
          if ($value[$valores[0]] != 'NULL' && $value[$valores[0]] != null  && $value[$valores[0]] != '') {
            $a = $this->getInicioFinSemana($valores[0],$year);

            $arreglo_fechas_buscar = [];
            $fechaInicio=strtotime($a['inicio']);
            $fechaFin=strtotime($a['fin']);

            for($z=$fechaInicio; $z<=$fechaFin; $z+=86400){
              $arreglo_fechas_buscar [] = date('Y-m-d',$z);
            }

            for ($y=0; $y <count($arreglo_fechas_buscar) ; $y++) {

                $SueldoSemanaEmpleadoRegistros = new GastosESemanaEmpleadoRegistros();
                $SueldoSemanaEmpleadoRegistros->gastos_e_semana_empleado_id = $SueldoSemanaEmpleado->id;
                $SueldoSemanaEmpleadoRegistros->semana = $valores[0];
                $SueldoSemanaEmpleadoRegistros->fecha_i= $arreglo_fechas_buscar[$y];
                $SueldoSemanaEmpleadoRegistros->total = $value[$valores[0]]/7;
                $SueldoSemanaEmpleadoRegistros->save();
            }
          }
        }

      }


    }
    // echo "".var_dump($arreglo);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function headingRow(): int
  {
    return 2;
  }

  public function getInicioSemana($week, $year)
  {
      $dto = new DateTime();
      $dto->setISODate($year, $week);
      $ret = $dto->format('Y-m-d');
      return $ret;
  }
  public function getFinSemana($week, $year)
  {
      $dto = new DateTime();
      $dto->setISODate($year, $week);
      $dto->modify('+6 days');
      $ret= $dto->format('Y-m-d');
      return $ret;
  }

  public function getInicioFinSemana($week, $year)
  {
      $dto = new DateTime();
      $dto->setISODate($year, $week);
      $ret['inicio'] = $dto->format('Y-m-d');
      $dto->modify('+6 days');
      $ret['fin'] = $dto->format('Y-m-d');
      return $ret;
  }




}
