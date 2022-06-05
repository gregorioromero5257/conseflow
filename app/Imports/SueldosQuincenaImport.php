<?php

namespace App\Imports;

use App\SueldosSemanal;
use App\SueldoQuincenaEmpleado;
use App\SueldoQuincenaEmpleadoRegistros;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use \App\Http\Helpers\Utilidades;
use DateTime;

// class EdenRedUserImport implements ToCollection

HeadingRowFormatter::default('none');

class SueldosQuincenaImport implements ToCollection, WithHeadingRow
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
    try {
      $valores = explode(' a ',$this->id);
      if (count($valores) > 1) {
        $eliminarSueldoSemanaRegitro =  SueldoQuincenaEmpleadoRegistros::
        join('sueldo_quincena_empleado AS sse','sse.id','=','sueldo_quincena_empleado_registros.sueldo_quincena_empleado_id')
        ->whereIn('semana',$valores)
        ->where('sse.empresa',$this->empresa)
        ->where('sueldo_quincena_empleado_registros.anio',$this->anio)
        ->delete();
      }else {
        $eliminarSueldoSemanaRegitro =  SueldoQuincenaEmpleadoRegistros::
        join('sueldo_quincena_empleado AS sse','sse.id','=','sueldo_quincena_empleado_registros.sueldo_quincena_empleado_id')
        ->where('semana',$valores[0])
        ->where('sueldo_quincena_empleado_registros.anio',$this->anio)
        ->delete();
      }
      $arreglo = [];
      foreach ($rows as $key => $value) {
        $empleado_id = 0;




        // $arreglo [] = $empleado_id;
        if ($value['NOMBRE'] != 'NULL' && $value['NOMBRE'] != null  && $value['NOMBRE'] != '') {

        $SueldoSemanaEmpleado = SueldoQuincenaEmpleado::where('empresa',$this->empresa)->where('empleado_id',$value['id'])->first();
        if (isset($SueldoSemanaEmpleado) == false) {
          $SueldoSemanaEmpleado = new SueldoQuincenaEmpleado();
          $SueldoSemanaEmpleado->empresa = $this->empresa;
          $SueldoSemanaEmpleado->empleado_id = $value['id'];
          $SueldoSemanaEmpleado->nombre = $value['NOMBRE'];
          $SueldoSemanaEmpleado->aplica = $value['aplica'];
          $SueldoSemanaEmpleado->save();
        }
        // $year = date('Y');

        if (count($valores) > 1) {
          $valores_busqueda = $this->rango($valores[0],$valores[1]);
          for ($i=0; $i < count($valores_busqueda); $i++) {

                $a = $this->getInicioFinSemana($valores_busqueda[$i],$this->anio);

                $arreglo_fechas_buscar = [];
                $fechaInicio=strtotime($a['inicio']);
                $fechaFin=strtotime($a['fin']);

                for($k=$fechaInicio; $k<=$fechaFin; $k+=86400){
                  $arreglo_fechas_buscar [] = date('Y-m-d',$k);
                }
                $contador= 0;
                for ($cj=0; $cj <count($arreglo_fechas_buscar) ; $cj++) {
                  $diac = $this->getDia($arreglo_fechas_buscar[$cj]);
                  if ($diac != 'Domingo') {
                    $contador ++;
                  }
                }

                for ($j=0; $j <count($arreglo_fechas_buscar) ; $j++) {
                  $dia = $this->getDia($arreglo_fechas_buscar[$j]);
                  if ($dia != 'Domingo') {
                    $SueldoSemanaEmpleadoRegistros = new SueldoQuincenaEmpleadoRegistros();
                    $SueldoSemanaEmpleadoRegistros->sueldo_quincena_empleado_id = $SueldoSemanaEmpleado->id;
                    $SueldoSemanaEmpleadoRegistros->semana = $valores_busqueda[$i];
                    $SueldoSemanaEmpleadoRegistros->fecha_i= $arreglo_fechas_buscar[$j];
                    $SueldoSemanaEmpleadoRegistros->total = $value[$valores_busqueda[$i]]/$contador;
                    $SueldoSemanaEmpleadoRegistros->anio = $this->anio;
                    $SueldoSemanaEmpleadoRegistros->save();
                  }
                }
          }
        }else {
            $a = $this->getInicioFinSemana($valores[0],$this->anio);

            $arreglo_fechas_buscar = [];
            $fechaInicio=strtotime($a['inicio']);
            $fechaFin=strtotime($a['fin']);

            for($z=$fechaInicio; $z<=$fechaFin; $z+=86400){
              $arreglo_fechas_buscar [] = date('Y-m-d',$z);
            }

            $contador= 0;
            for ($cj=0; $cj <count($arreglo_fechas_buscar) ; $cj++) {
              $diac = $this->getDia($arreglo_fechas_buscar[$cj]);
              if ($diac != 'Domingo') {
                $contador ++;
              }
            }


            for ($y=0; $y <count($arreglo_fechas_buscar) ; $y++) {
              $dia = $this->getDia($arreglo_fechas_buscar[$y]);
              if ($dia != 'Domingo') {
                $SueldoSemanaEmpleadoRegistros = new SueldoQuincenaEmpleadoRegistros();
                $SueldoSemanaEmpleadoRegistros->sueldo_quincena_empleado_id = $SueldoSemanaEmpleado->id;
                $SueldoSemanaEmpleadoRegistros->semana = $valores[0];
                $SueldoSemanaEmpleadoRegistros->fecha_i= $arreglo_fechas_buscar[$y];
                $SueldoSemanaEmpleadoRegistros->total = $value[$valores[0]]/$contador;
                $SueldoSemanaEmpleadoRegistros->anio = $this->anio;
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

  public function getInicioFinSemana($data, $anio)
  {
    $meses = [' Q ENERO',' Q FEBRERO',' Q MARZO',' Q ABRIL',' Q MAYO',
    ' Q JUNIO',' Q JULIO',' Q AGOSTO',' Q SEP.',' Q OCT.',' Q NOV.',' Q DIC.'];
    $numero = ['1 Q ','2 Q '];
    $meses_arreglo = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEP.','OCT.','NOV.','DIC.'];
    $meses_arreglo_numero = ['01','02','03','04','05','06','07','08','09','10','11','12'];

    $numero_uno = str_replace($meses,"",$data);
    $mes_uno = str_replace($numero,"",$data);

    $meses_busqueda = array_search($mes_uno,$meses_arreglo);
    $ret = null;

    if($mes_uno === 'ENERO' && $numero_uno == 1){
      $ret ['inicio'] = ((int) $anio - 1).'-12-26';
    }
    if($mes_uno != 'ENERO' && $numero_uno == 1) {
      $ret['inicio'] = ((int) $anio).'-'.$meses_arreglo_numero[$meses_busqueda - 1].'-26';
    }
    if ($numero_uno == 2) {
      $ret['inicio'] = ((int) $anio).'-'.$meses_arreglo_numero[$meses_busqueda].'-11';
    }

    if ($numero_uno == 1) {
      $ret['fin'] = ((int) $anio).'-'.$meses_arreglo_numero[$meses_busqueda].'-10';
    }
    if ($numero_uno == 2) {
      $ret['fin'] = ((int) $anio).'-'.$meses_arreglo_numero[$meses_busqueda].'-25';
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

  public function getDia($fecha)
  {
    $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');

    $dia = $dias[(date('N', strtotime($fecha))) - 1];

    return $dia;
  }




}
