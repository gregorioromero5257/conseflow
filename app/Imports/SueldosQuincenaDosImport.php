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

class SueldosQuincenaDosImport implements ToCollection, WithHeadingRow
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
      // $arreglo = [];

      // foreach ($rows as $key => $value) {
      //
      //     $nb = new \App\ReRap();
      //     $nb->fecha = $value['FECHA'];
      //     $nb->empleado = $value['EMPLEADO'];
      //     $nb->id_emp = $value['ID'];
      //     $nb->horas = $value['HORA'];
      //     $nb->save();
      //
      //
      //
      //   // $arreglo [] = $value;
      // }
      // dd($arreglo);

      $valores = explode(' a ',$this->id);
      // if (count($valores) > 1) {
      //   $eliminarSueldoSemanaRegitro =  SueldoQuincenaEmpleadoRegistros::
      //   join('sueldo_quincena_empleado AS sse','sse.id','=','sueldo_quincena_empleado_registros.sueldo_quincena_empleado_id')
      //   ->whereIn('semana',$valores)
      //   ->where('sse.empresa',$this->empresa)
      //   ->where('sueldo_quincena_empleado_registros.anio',$this->anio)
      //   ->delete();
      // }else {
      //   $eliminarSueldoSemanaRegitro =  SueldoQuincenaEmpleadoRegistros::
      //   join('sueldo_quincena_empleado AS sse','sse.id','=','sueldo_quincena_empleado_registros.sueldo_quincena_empleado_id')
      //   ->where('semana',$valores[0])
      //   ->where('sueldo_quincena_empleado_registros.anio',$this->anio)
      //   ->delete();
      // }
      $arreglo = [];
      $periodo = $valores[0].' del '.$this->anio;
      $empre = 0;
      if ($this->empresa === 'csct') {
        $empre = 4;
      }elseif ( $this->empresa === 'conserflow') {
        $empre = 2;
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
        $nomina->tipo_nomina = 1;
        $nomina->empresa = $empre;
        $nomina->save();

        $nomina_id = $nomina->id;
      }else {
        $nomina_id = $nomina_buscar->id;
      }

      foreach ($rows as $key => $value) {
        $empleado_id = 0;




        // $arreglo [] = $empleado_id;
        if ($value['NOMBRE'] != 'NULL' && $value['NOMBRE'] != null  && $value['NOMBRE'] != '') {

        $SueldoSemanaEmpleado = SueldoQuincenaEmpleado::where('empresa',$this->empresa)->where('empleado_id',$value['NO.'])->first();
        if (isset($SueldoSemanaEmpleado) == false) {
          $SueldoSemanaEmpleado = new SueldoQuincenaEmpleado();
          $SueldoSemanaEmpleado->empresa = $this->empresa;
          $SueldoSemanaEmpleado->empleado_id = $value['NO.'];
          $SueldoSemanaEmpleado->nombre = $value['NOMBRE'];
          // $SueldoSemanaEmpleado->aplica = $value['aplica'];
          $SueldoSemanaEmpleado->save();
        }
        // $year = date('Y');


            $a = $this->getInicioFinSemana($valores[0],$this->anio);

            // $total = $this->guardarNomina($a, $value, $valores[0], $this->anio, $this->empresa, $nomina_id);

///////

    $nomina_movimientos = new \App\MovimientosNomina();
    $nomina_movimientos->dias_trabajados = $value['DIAS LABORADOS'];
    $nomina_movimientos->transferencias = 0;
    $nomina_movimientos->efectivos = 0;
    $nomina_movimientos->otros = 0;
    $nomina_movimientos->descuento = $value['DESCTOS.'];
    $nomina_movimientos->infonavit = $value['INFONAVIT'];
    $nomina_movimientos->observaciones = $value['OBSERVACIONES'];
    $nomina_movimientos->banco = $value['BANCO'];
    // $nomina_movimientos->datos_banco = $value['DATOSB'];
    $nomina_movimientos->puesto = $value['PUESTO'];
    $nomina_movimientos->sueldo_diario = (float)$value['SD'];
    if (array_key_exists('ALIMENTOS', $value)) {
      $nomina_movimientos->viaticos_alimentos = $value['ALIMENTOS'];
      $nomina_movimientos->totales = (( (float)$value['SD'] * (float)$value['DIAS LABORADOS']) - ( (float)$value['DESCTOS.'] + (float)$value['INFONAVIT']) ) + (float)$value['ALIMENTOS'];
    }else {
      $nomina_movimientos->totales = (( (float)$value['SD'] * (float)$value['DIAS LABORADOS']) - ( (float)$value['DESCTOS.'] + (float)$value['INFONAVIT']) );
    }
    $nomina_movimientos->empleado_id = $value['NO.'];
    $nomina_movimientos->contrato_id = 0;
    $nomina_movimientos->nomina_id = $nomina_id;
    $nomina_movimientos->save();

    $total = 0;

    if (array_key_exists('ALIMENTOS',$value)) {
      $total = (float)$value['SALARIO QUINCENAL'] + (float)$value['ALIMENTOS'];

    }else {
      $total = (float)$value['SALARIO QUINCENAL'];

    }

/////


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
                $SueldoSemanaEmpleadoRegistros->total = $total/$contador;
                $SueldoSemanaEmpleadoRegistros->anio = $this->anio;
                $SueldoSemanaEmpleadoRegistros->save();
              }
            }



      }


    }
    DB::commit();




    // echo "".var_dump($arreglo);
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
    $nomina_movimientos->datos_banco = $value['DATOSB'];
    $nomina_movimientos->puesto = $value['PUESTO'];
    $nomina_movimientos->sueldo_diario = (float)$value['SD'];
    if (array_key_exists('ALIMENTOS', $value)) {
      $nomina_movimientos->viaticos_alimentos = $value['ALIMENTOS'];
      $nomina_movimientos->totales = (( (float)$value['SD'] * (float)$value['DIAS LABORADOS']) - ( (float)$value['DESCTOS.'] + (float)$value['INFONAVIT']) ) + (float)$value['ALIMENTOS'];
    }else {
      $nomina_movimientos->totales = (( (float)$value['SD'] * (float)$value['DIAS LABORADOS']) - ( (float)$value['DESCTOS.'] + (float)$value['INFONAVIT']) );
    }
    $nomina_movimientos->empleado_id = $value['NO.'];
    $nomina_movimientos->contrato_id = 0;
    $nomina_movimientos->nomina_id = $nomina_id;
    $nomina_movimientos->save();

    $total = 0;

    if (array_key_exists('ALIMENTOS',$value)) {
      $total = (float)$value['SALARIO QUINCENAL'] + (float)$value['ALIMENTOS'];

    }else {
      $total = (float)$value['SALARIO QUINCENAL'];

    }

    return $total;
  }




}
