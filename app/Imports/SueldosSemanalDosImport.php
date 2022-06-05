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

class SueldosSemanalDosImport implements ToCollection, WithHeadingRow
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
      // if (count($valores) > 1) {
      //   $eliminarSueldoSemanaRegitro =  SueldoSemanaEmpleadoRegistros::
      //   join('sueldo_semana_empleado AS sse','sse.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
      //   ->whereIn('semana',$valores)
      //   ->where('sse.empresa',$this->empresa)
      //   ->where('sueldo_semana_empleado_registros.anio',$this->anio)
      //   ->delete();
      // }else {
      //   $eliminarSueldoSemanaRegitro =  SueldoSemanaEmpleadoRegistros::
      //   join('sueldo_semana_empleado AS sse','sse.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
      //   ->where('semana',$valores[0])
      //   ->where('sse.empresa',$this->empresa)
      //   ->where('sueldo_semana_empleado_registros.anio',$this->anio)
      //   ->delete();
      // }

      $periodo = 'SEMANA '.$valores[0].' del '.$this->anio;
      $empre = '';
      if ($this->empresa === 'CSCT') {
        $empre = 3;
      }elseif ( $this->empresa === 'CONSERFLOW') {
        $empre = 1;
      }
      $nomina_id = 0;
      DB::beginTransaction();

      $nomina_buscar = \App\Nomina::where('periodo',$periodo)
      ->where('empresa',$empre)
      ->first();
      $b = $this->getInicioFinSemana($valores[0],$this->anio);

      //4 csct
      //2 conserflow
      if (isset($nomina_buscar) == false) {
        $nomina = new \App\Nomina();
        $nomina->fecha_inicial = $b['inicio'];
        $nomina->fecha_final = $b['fin'];
        $nomina->periodo = $periodo;
        $nomina->tipo_nomina = 2;
        $nomina->empresa = $empre;
        $nomina->save();

        $nomina_id = $nomina->id;
      }else {
        $nomina_id = $nomina_buscar->id;
      }


      foreach ($rows as $key => $value) {
        // $arreglo [] = $value;
        $empleado_id = 0;

        if ($value['NOMBRE'] != 'NULL' && $value['NOMBRE'] != null  && $value['NOMBRE'] != '') {

        $SueldoSemanaEmpleado = SueldoSemanaEmpleado::where('empresa',$this->empresa)->where('empleado_id',$value['NO.'])->first();
        if (isset($SueldoSemanaEmpleado) == false) {
          $SueldoSemanaEmpleado = new SueldoSemanaEmpleado();
          $SueldoSemanaEmpleado->empresa = $this->empresa;
          $SueldoSemanaEmpleado->empleado_id = $value['NO.'];
          $SueldoSemanaEmpleado->nombre = $value['NOMBRE'];
          // $SueldoSemanaEmpleado->aplica = $value['aplica'];
          $SueldoSemanaEmpleado->save();
        }
        // $year = '2021';

            $a = $this->getInicioFinSemana($valores[0],$this->anio);
            // $eliminarSueldoSemanaRegitro =  SueldoSemanaEmpleadoRegistros::where('semana',$valores[0])->delete();
            // $total = $this->guardarNomina($a, $value, $valores[0], $this->anio, $this->empresa, $nomina_id);
            //////

            $nomina_movimientos = new \App\MovimientosNomina();
            $nomina_movimientos->dias_trabajados = $value['DIAS LABORADOS'];
            $nomina_movimientos->transferencias = 0;
            $nomina_movimientos->efectivos = 0;
            $nomina_movimientos->otros = 0;
            $nomina_movimientos->descuento = $value['DESCTOS.'];
            $nomina_movimientos->infonavit = $value['INFONAVIT'];
            $nomina_movimientos->observaciones = $value['OBSERVACIONES'];
            $nomina_movimientos->banco = $value['BANCO'];
            if (array_key_exists('VIATICOS ALIMENTOS', $value)) {
              $nomina_movimientos->datos_banco = $value['DATOSB'];
            }
            $nomina_movimientos->puesto = $value['PUESTO'];
            $nomina_movimientos->sueldo_diario = (float)$value['SD'];
            if (array_key_exists('VIATICOS ALIMENTOS', $value)) {
              $nomina_movimientos->viaticos_alimentos = $value['VIATICOS ALIMENTOS'];
              $nomina_movimientos->totales = (( (float)$value['SD'] * (float)$value['DIAS LABORADOS']) - ( (float)$value['DESCTOS.'] + (float)$value['INFONAVIT']) ) + (float)$value['VIATICOS ALIMENTOS'];
            }else {
              $nomina_movimientos->totales = (( (float)$value['SD'] * (float)$value['DIAS LABORADOS']) - ( (float)$value['DESCTOS.'] + (float)$value['INFONAVIT']) );
            }
            $nomina_movimientos->empleado_id = $value['NO.'];
            $nomina_movimientos->contrato_id = 0;
            $nomina_movimientos->nomina_id = $nomina_id;
            $nomina_movimientos->save();

            $total = 0;

            if (array_key_exists('VIATICOS ALIMENTOS',$value)) {
              $total = (float)$value['SALARIO SEMANAL'] + (float)$value['VIATICOS ALIMENTOS'];

            }else {
              $total = (float)$value['SALARIO SEMANAL'];

            }

            ///////

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
                $SueldoSemanaEmpleadoRegistros->total = $total/6;
                $SueldoSemanaEmpleadoRegistros->anio = $this->anio;
                $SueldoSemanaEmpleadoRegistros->save();
              }
            }



      }


    }
    DB::commit();

    } catch (\Throwable $e) {
      DB::rollBack();
      Utilidades::errors($e);
    }
  }

  public function headingRow(): int
  {
    return 9;
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

  public function guardarNomina($a, $value, $quincena, $anio, $empresa, $nomina_id)
  {


    $nomina_movimientos = new \App\MovimientosNomina();
    $nomina_movimientos->dias_trabajados = $value['DIAS LABORADOS'];
    $nomina_movimientos->transferencias = 0;
    $nomina_movimientos->efectivos = 0;
    $nomina_movimientos->otros = 0;
    $nomina_movimientos->descuento = $value['DESCTOS.'];
    $nomina_movimientos->infonavit = $value['INFONAVIT'];
    $nomina_movimientos->observaciones = $value['OBSERVACIONES'];
    $nomina_movimientos->banco = $value['BANCO'];
    if (array_key_exists('VIATICOS ALIMENTOS', $value)) {
      $nomina_movimientos->datos_banco = $value['DATOSB'];
    }
    $nomina_movimientos->puesto = $value['PUESTO'];
    $nomina_movimientos->sueldo_diario = (float)$value['SD'];
    if (array_key_exists('VIATICOS ALIMENTOS', $value)) {
      $nomina_movimientos->viaticos_alimentos = $value['VIATICOS ALIMENTOS'];
      $nomina_movimientos->totales = (( (float)$value['SD'] * (float)$value['DIAS LABORADOS']) - ( (float)$value['DESCTOS.'] + (float)$value['INFONAVIT']) ) + (float)$value['VIATICOS ALIMENTOS'];
    }else {
      $nomina_movimientos->totales = (( (float)$value['SD'] * (float)$value['DIAS LABORADOS']) - ( (float)$value['DESCTOS.'] + (float)$value['INFONAVIT']) );
    }
    $nomina_movimientos->empleado_id = $value['NO.'];
    $nomina_movimientos->contrato_id = 0;
    $nomina_movimientos->nomina_id = $nomina_id;
    $nomina_movimientos->save();

    $total = 0;

    if (array_key_exists('VIATICOS ALIMENTOS',$value)) {
      $total = (float)$value['SALARIO SEMANAL'] + (float)$value['VIATICOS ALIMENTOS'];

    }else {
      $total = (float)$value['SALARIO SEMANAL'];

    }

    return $total;
  }





}
