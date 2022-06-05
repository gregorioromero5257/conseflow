<?php

namespace App\Imports;

use App\Asistencia;
use App\Registro;
use App\prueba;
use App\Empleado;
use App\Contrato;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use \App\Http\Helpers\Utilidades;
use App\CatConceptosA;

// class EdenRedUserImport implements ToCollection

HeadingRowFormatter::default('none');

class NominaImport implements ToCollection
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
      // echo "".var_dump($rows);

      $valores = explode('&',$this->id);
      $arreglo = [];
      $arreglo_fechas_mostrar = [];
      $arreglo_fechas_buscar = [];
      $fechaInicio=strtotime($valores[0]);
      $fechaFin=strtotime($valores[1]);

      for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
        $arreglo_fechas_buscar [] = date('Y-m-d',$i);
      }

      foreach ($rows as $key => $value_) {
        $nodo = [];
        $total = 3 + count($arreglo_fechas_buscar);
        for ($i=0; $i <$total ; $i++) {
          $nodo [] = $value_[$i];
        }
        $arreglo [] =$nodo;
        $empleado = Empleado::
        select('id',DB::raw("CONCAT(nombre,' ',ap_paterno,' ',ap_materno) AS nombre_empleado"))->get();

        ////
        foreach ($arreglo as $key => $value) {
          $empleado_id = 0;
          $contrato_id = 0;
          foreach ($empleado as $key => $e) {
                if($e->nombre_empleado === $value[0]){
                $empleado_id = $e->id;
              }
          }
          $contrato = Contrato::where('empleado_id',$empleado_id)->where('condicion','1')->first();
          if (isset($contrato) == true) {
            $contrato_id = $contrato->id;
          }
          for ($j=0; $j < count($arreglo_fechas_buscar); $j++) {
            $asistencias = Asistencia::where('fecha',$arreglo_fechas_buscar[$j])->where('nombre',$value['0'])->first();
            if (isset($asistencias) == false) {

            $hora_uno = '00:00:00';
            $hora_dos = '00:00:00';
            $hora_tres = '00:00:00';
            $hora_cuatro = '00:00:00';
            $estado = 0;
            $pendiente = 0;

            $valores_= explode(':',$value[$j+2]);

            if (count($valores_) > 1) {
            $cadena_hora = preg_replace('([^A-Za-z0-9 ])','',$value[$j+2]);
            if (strlen($cadena_hora) == 4) {
              $hora_uno = substr($cadena_hora,0,2).':'.substr($cadena_hora,2,2).':00';
              $estado = 4;
              $pendiente =  1;
            }
            elseif (strlen($cadena_hora) == 8) {
              $hora_uno = substr($cadena_hora,0,2).':'.substr($cadena_hora,2,2).':00';
              $hora_cuatro = substr($cadena_hora,4,2).':'.substr($cadena_hora,6,2).':00';
              $estado = 0;
              $pendiente = 0;
            }
            elseif (strlen($cadena_hora) == 12) {
              $hora_uno = substr($cadena_hora,0,2).':'.substr($cadena_hora,2,2).':00';
              $hora_dos = substr($cadena_hora,4,2).':'.substr($cadena_hora,6,2).':00';
              $hora_cuatro = substr($cadena_hora,8,2).':'.substr($cadena_hora,10,2).':00';
              $estado = 0;
              $pendiente = 0;
            }
            elseif (strlen($cadena_hora) == 16) {
              $hora_uno = substr($cadena_hora,0,2).':'.substr($cadena_hora,2,2).':00';
              $hora_dos = substr($cadena_hora,4,2).':'.substr($cadena_hora,6,2).':00';
              $hora_tres = substr($cadena_hora,8,2).':'.substr($cadena_hora,10,2).':00';
              $hora_cuatro = substr($cadena_hora,12,2).':'.substr($cadena_hora,14,2).':00';
              $estado = 0;
              $pendiente = 0;
            }
          }else {
            $estado = 5;
            $pendiente = 1;
            $catagolo_de_conceptos = CatConceptosA::where('nombre',$value[$j+2])->first();
            if (isset($catagolo_de_conceptos) == false) {
              $catagolo_de_conceptos_new = new CatConceptosA();
              $catagolo_de_conceptos_new->nombre = $value[$j+2];
              $catagolo_de_conceptos_new->save();
            }
          }


            $registro = new Registro();
            $registro->hora_entrada =  $hora_uno;
            $registro->hora_salida_comida =  $hora_dos;
            $registro->hora_entrada_comida =  $hora_tres;
            $registro->hora_salida =  $hora_cuatro;
            $registro->estado = $estado;
            $registro->pendiente = $pendiente;
            $registro->observaciones = $value[$j+2];
            $registro->save();



            $asistencia = new Asistencia();
            $asistencia->fecha = $arreglo_fechas_buscar[$j];
            $asistencia->nombre = $value[0];
            $asistencia->registro_id = $registro->id;
            $asistencia->empleado_id = $empleado_id;
            $asistencia->contrato_id = $contrato_id;
            // $asistencia->dia_id = $this->saber_dia($arreglo_fechas_buscar[$j]);
            $asistencia->save();
          }
          }

      }

        ///
      }
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    // echo "".var_dump($rows);


        // $prueba = new prueba();
        // $prueba->nombre =  $value['Nombre'];
        // $prueba->fecha =  $value['Fecha'];
        // $prueba->entrada_uno =  $value['Primer Horario'] == 'Entrada' ? NULL : $value['Primer Horario'];
        // $prueba->salida_uno =  $value[0] == 'Salida' ? NULL : $value[0];
        // $prueba->entrada_dos =  $value['Segundo Horario']== 'Entrada' ? NULL : $value['Segundo Horario'];
        // $prueba->salida_dos =  $value[1]== 'Salida' ? NULL : $value[1];
        // $prueba->save();




    // $prueba = prueba::where('nombre','=',null)->delete();
  }

  public function saber_dia($nombredia) {
  $dias = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
  $fecha = $dias[date('N', strtotime($nombredia))];
  return $fecha;
  }

  // public function headingRow(): int
  // {
  //   return 3;
  // }



}
