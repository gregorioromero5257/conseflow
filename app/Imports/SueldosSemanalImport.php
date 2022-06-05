<?php

namespace App\Imports;

use App\SueldosSemanal;
use App\SueldoSemanaEmpleado;
use App\SueldoSemanaEmpleadoRegistros;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use \App\Http\Helpers\Utilidades;
use DateTime;

// class EdenRedUserImport implements ToCollection

HeadingRowFormatter::default('none');

class SueldosSemanalImport implements ToCollection, WithHeadingRow
{
  protected $id;
  protected $empresa;

  public function __construct(String $id,String $empresa,String $anio)
  {
    $this->id = $id;
    $this->empresa = $empresa;
    $this->anio = $anio;
  }
  /**
  * @param array $row
  *
  * @return \Illuminate\Database\Eloquent\Model|null
  */
  public function collection(Collection $rows)
  {
    // dd($rows);
    try {
      $valores = explode(' a ',$this->id);
      // return $valores;
      $arreglo = [];
      if (count($valores) > 1) {
        $eliminarSueldoSemanaRegitro =  SueldoSemanaEmpleadoRegistros::
        join('sueldo_semana_empleado AS sse','sse.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
        ->whereIn('semana',$valores)
        ->where('sse.empresa',$this->empresa)
        ->where('sueldo_semana_empleado_registros.anio',$this->anio)
        ->delete();
      }else {
        $eliminarSueldoSemanaRegitro =  SueldoSemanaEmpleadoRegistros::
        join('sueldo_semana_empleado AS sse','sse.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
        ->where('semana',$valores[0])
        ->where('sse.empresa',$this->empresa)
        ->where('sueldo_semana_empleado_registros.anio',$this->anio)
        ->delete();
      }

      foreach ($rows as $key => $value) {
        // $arreglo [] = $value;
        $empleado_id = 0;

        if ($value['NOMBRE'] != 'NULL' && $value['NOMBRE'] != null  && $value['NOMBRE'] != '') {

        $SueldoSemanaEmpleado = SueldoSemanaEmpleado::where('empresa',$this->empresa)->where('empleado_id',$value['id'])->first();
        if (isset($SueldoSemanaEmpleado) == false) {
          $SueldoSemanaEmpleado = new SueldoSemanaEmpleado();
          $SueldoSemanaEmpleado->empresa = $this->empresa;
          $SueldoSemanaEmpleado->empleado_id = $value['id'];
          $SueldoSemanaEmpleado->nombre = $value['NOMBRE'];
          $SueldoSemanaEmpleado->aplica = $value['aplica'];
          $SueldoSemanaEmpleado->save();
        }
        // $year = '2021';

        if (count($valores) > 1) {
          for ($i=$valores[0]; $i < $valores[1]; $i++) {

              // $eliminarSueldoSemanaRegitro = SueldoSemanaEmpleadoRegistros::where('semana',$i)->delete();

              if ($value[$i] != 'NULL' && $value[$i] != null  && $value[$i] != '') {
                $a = $this->getInicioFinSemana($i,$this->anio);

                $arreglo_fechas_buscar = [];
                $fechaInicio=strtotime($a['inicio']);
                $fechaFin=strtotime($a['fin']);

                for($k=$fechaInicio; $k<=$fechaFin; $k+=86400){
                  $arreglo_fechas_buscar [] = date('Y-m-d',$k);
                }

                for ($j=0; $j <count($arreglo_fechas_buscar) ; $j++) {
                  $dia = $this->getDia($arreglo_fechas_buscar[$j]);
                  if ($dia != 'Domingo' ) {
                    $SueldoSemanaEmpleadoRegistros = new SueldoSemanaEmpleadoRegistros();
                    $SueldoSemanaEmpleadoRegistros->sueldo_semana_empleado_id = $SueldoSemanaEmpleado->id;
                    $SueldoSemanaEmpleadoRegistros->semana = $i;
                    $SueldoSemanaEmpleadoRegistros->fecha_i= $arreglo_fechas_buscar[$j];
                    $SueldoSemanaEmpleadoRegistros->total = $value[$i]/6;
                    $SueldoSemanaEmpleadoRegistros->anio = $this->anio;
                    $SueldoSemanaEmpleadoRegistros->save();
                  }
                }
              }
          }
        }else {
          if ($value[$valores[0]] != 'NULL' && $value[$valores[0]] != null  && $value[$valores[0]] != '') {
            $a = $this->getInicioFinSemana($valores[0],$this->anio);
            // $eliminarSueldoSemanaRegitro =  SueldoSemanaEmpleadoRegistros::where('semana',$valores[0])->delete();

            $arreglo_fechas_buscar = [];
            $fechaInicio=strtotime($a['inicio']);
            $fechaFin=strtotime($a['fin']);

            for($z=$fechaInicio; $z<=$fechaFin; $z+=86400){
              $arreglo_fechas_buscar [] = date('Y-m-d',$z);
            }

            for ($y=0; $y <count($arreglo_fechas_buscar) ; $y++) {
              $dia = $this->getDia($arreglo_fechas_buscar[$y]);
              if ($dia != 'Domingo' ) {
                $SueldoSemanaEmpleadoRegistros = new SueldoSemanaEmpleadoRegistros();
                $SueldoSemanaEmpleadoRegistros->sueldo_semana_empleado_id = $SueldoSemanaEmpleado->id;
                $SueldoSemanaEmpleadoRegistros->semana = $valores[0];
                $SueldoSemanaEmpleadoRegistros->fecha_i= $arreglo_fechas_buscar[$y];
                $SueldoSemanaEmpleadoRegistros->total = $value[$valores[0]]/6;
                $SueldoSemanaEmpleadoRegistros->anio = $this->anio;
                $SueldoSemanaEmpleadoRegistros->save();
              }
            }
          }
        }

      }


    }

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

  public function getDia($fecha)
  {
    $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');

    $dia = $dias[(date('N', strtotime($fecha))) - 1];

    return $dia;
  }




}
