<?php

namespace App\Imports;

use App\SueldosSemanal;
use App\GastosEQuincenalEmpleado;
use App\GastosEQuincenalEmpleadoRegistros;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use \App\Http\Helpers\Utilidades;
use DateTime;

// class EdenRedUserImport implements ToCollection

HeadingRowFormatter::default('none');

class GastosEQuincenalImport implements ToCollection, WithHeadingRow
{
  protected $id;
  protected $empresa;

  public function __construct(String $id,$empresa)
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
      $arreglo = [];
      foreach ($rows as $key => $value) {
        $empleado_id = 0;

        if ($this->empresa === 'conserflow') {
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
        }elseif ($this->empresa === 'csct') {
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



        // $arreglo [] = $empleado_id;
        if ($value['NOMBRE'] != 'NULL' && $value['NOMBRE'] != null  && $value['NOMBRE'] != '') {

        $SueldoSemanaEmpleado = GastosEQuincenalEmpleado::where('empresa',$this->empresa)->where('nombre',$value['NOMBRE'])->first();
        if (isset($SueldoSemanaEmpleado) == false) {
          $SueldoSemanaEmpleado = new GastosEQuincenalEmpleado();
          $SueldoSemanaEmpleado->empresa = $this->empresa;
          $SueldoSemanaEmpleado->empleado_id = $empleado_id;
          $SueldoSemanaEmpleado->nombre = $value['NOMBRE'];
          $SueldoSemanaEmpleado->save();
        }
        $year = date('Y');

        if (count($valores) > 1) {
          $valores_busqueda = $this->rango($valores[0],$valores[1]);
          for ($i=0; $i < count($valores_busqueda); $i++) {

                $a = $this->getInicioFinSemana($valores_busqueda[$i]);

                $arreglo_fechas_buscar = [];
                $fechaInicio=strtotime($a['inicio']);
                $fechaFin=strtotime($a['fin']);

                for($k=$fechaInicio; $k<=$fechaFin; $k+=86400){
                  $arreglo_fechas_buscar [] = date('Y-m-d',$k);
                }

                for ($j=0; $j <count($arreglo_fechas_buscar) ; $j++) {

                    $SueldoSemanaEmpleadoRegistros = new GastosEQuincenalEmpleadoRegistros();
                    $SueldoSemanaEmpleadoRegistros->gastos_e_quincenal_empleado_id = $SueldoSemanaEmpleado->id;
                    $SueldoSemanaEmpleadoRegistros->semana = $valores_busqueda[$i];
                    $SueldoSemanaEmpleadoRegistros->fecha_i= $arreglo_fechas_buscar[$j];
                    $SueldoSemanaEmpleadoRegistros->total = $value[$valores_busqueda[$i]]/15;
                    $SueldoSemanaEmpleadoRegistros->save();
                }
          }
        }else {
            $a = $this->getInicioFinSemana($valores[0]);

            $arreglo_fechas_buscar = [];
            $fechaInicio=strtotime($a['inicio']);
            $fechaFin=strtotime($a['fin']);

            for($z=$fechaInicio; $z<=$fechaFin; $z+=86400){
              $arreglo_fechas_buscar [] = date('Y-m-d',$z);
            }

            for ($y=0; $y <count($arreglo_fechas_buscar) ; $y++) {

                $SueldoSemanaEmpleadoRegistros = new GastosEQuincenalEmpleadoRegistros();
                $SueldoSemanaEmpleadoRegistros->gastos_e_quincenal_empleado_id = $SueldoSemanaEmpleado->id;
                $SueldoSemanaEmpleadoRegistros->semana = $valores[0];
                $SueldoSemanaEmpleadoRegistros->fecha_i= $arreglo_fechas_buscar[$y];
                $SueldoSemanaEmpleadoRegistros->total = $value[$valores[0]]/15;
                $SueldoSemanaEmpleadoRegistros->save();
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

  public function getInicioFinSemana($data)
  {
    $meses = [' Q ENERO',' Q FEBRERO',' Q MARZO',' Q ABRIL',' Q MAYO',
    ' Q JUNIO',' Q JULIO',' Q AGOSTO',' Q SEP.',' Q OCT.',' Q NOV.',' Q DIC.'];
    $numero = ['1 Q ','2 Q '];
    $meses_arreglo = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEP.','OCT.','NOV.','DIC.'];
    $meses_arreglo_numero = ['01','02','03','04','05','06','07','08','09','10','11','12'];

    $numero_uno = str_replace($meses,"",$data);
    $mes_uno = str_replace($numero,"",$data);

    $meses_busqueda = array_search($mes_uno,$meses_arreglo);

    if($mes_uno === 'ENERO' && $numero_uno == 1){
      $ret ['inicio'] = (date('Y') - 1).'-12-26';
    }
    if($mes_uno != 'ENERO' && $numero_uno == 1) {
      $ret['inicio'] = date('Y').'-'.$meses_arreglo_numero[$meses_busqueda - 1].'-26';
    }
    if ($numero_uno == 2) {
      $ret['inicio'] = date('Y').'-'.$meses_arreglo_numero[$meses_busqueda].'-11';
    }

    if ($numero_uno == 1) {
      $ret['fin'] = date('Y').'-'.$meses_arreglo_numero[$meses_busqueda].'-10';
    }
    if ($numero_uno == 2) {
      $ret['fin'] = date('Y').'-'.$meses_arreglo_numero[$meses_busqueda].'-25';
    }
    return $ret;
  }

  public function rango($uno, $dos)
  {
    $meses = [' Q ENERO',' Q FEBRERO',' Q MARZO',' Q ABRIL',' Q MAYO',
    ' Q JUNIO',' Q JULIO',' Q AGOSTO',' Q SEP.',' Q OCT.',' Q NOV.',' Q DIC.'];
    $numero = ['1 Q ','2 Q '];
    $meses_arreglo = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEP.','OCT.','NOV.','DIC.'];

    $numero_uno = str_replace($meses,"",$uno);
    $numero_dos = str_replace($meses,"",$dos);
    $mes_uno = str_replace($numero,"",$uno);
    $mes_dos = str_replace($numero,"",$dos);

    $meses_busqueda = array_search($mes_dos,$meses_arreglo);
    $meses_busqueda_inicio = array_search($mes_uno,$meses_arreglo);

    $arreglo_rango = [];
    if ($numero_uno == 1) {
      $arreglo_rango [] = '1 Q '.$meses_arreglo[$meses_busqueda_inicio];
      $arreglo_rango [] = '2 Q '.$meses_arreglo[$meses_busqueda_inicio];
    }elseif ($numero_uno == 2) {
      $arreglo_rango [] = '2 Q '.$meses_arreglo[$meses_busqueda_inicio];
    }
    for ($i=$meses_busqueda_inicio + 1; $i < $meses_busqueda; $i++) {
      $arreglo_rango [] = '1 Q '.$meses_arreglo[$i];
      $arreglo_rango [] = '2 Q '.$meses_arreglo[$i];
    }
    if ($numero_dos == 1) {
      $arreglo_rango [] = '1 Q '.$meses_arreglo[$meses_busqueda];
    }elseif ($numero_dos == 2) {
      $arreglo_rango [] = '1 Q '.$meses_arreglo[$meses_busqueda];
      $arreglo_rango [] = '2 Q '.$meses_arreglo[$meses_busqueda];
    }

    return $arreglo_rango;
  }




}
