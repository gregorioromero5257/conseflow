<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use App\Registro;
use App\ValidacionHorario;
use App\Empleado;
use App\Asistencia;
use Illuminate\Validation\Rule;


class RegistroController extends Controller
{

  /**
  * Index the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function index(Request $request,$id)
  {
    $registro = DB::table('asistencias')
    ->leftJoin('dias', 'dias.id' ,'=', 'asistencias.dia_id')
    ->leftJoin('registros', 'registros.id', '=', 'asistencias.registro_id')
    ->leftJoin('empleados', 'empleados.id', '=', 'asistencias.empleado_id')
    ->select('asistencias.*','dias.nombre','dias.id as did','registros.id as rid','registros.hora_entrada','registros.hora_salida_comida','registros.hora_entrada_comida',
    'registros.hora_salida', DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno)AS empleado"))
    ->where('asistencias.empleado_id','=',$id)->get();
    return response()->json(
      $registro->toArray()
    );
  }

  /**
  * Store the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    /*
    $valor = '';
    $retardo_hora_entrada = '';
    $horas_trabajadas = '';
    $retardo_hora_comida = '';
    $valor2 = '';
    $tiempo_retardo_comida = '';
    $id_asistencia = 0;
    $contratos = \App\Contrato::where('empleado_id','=',$request->empleado_id)->where('condicion','=','1')->get();
    for ($i=0; $i < count($contratos) ; $i++)
    {
      $horario = \App\Horario::where('id','=',$contratos[$i]->horario_id)->first();
      if($horario->hora_entrada != null ){
        $entrada = $horario->hora_entrada;
        $salida = $horario->hora_salida;
        $hora_entrada = explode(':',$entrada);
        $hora_salida = explode(':',$salida);
        if ($request->dia_id >= 1 && $request->dia_id <= 5) {
          // NOTE: En esta condicion se tomo en cuenta si el dia que se envia esta entre lunes a viernes
          if ($request->hora_entrada > $hora_entrada[0].':10:00') {
            $horario_definido_retardo = $hora_entrada[0].':10:00';
            $retardo_hora_entrada = $this->calcular_tiempo_trasnc($request->hora_entrada,$horario_definido_retardo).':00';
            $horas_trabajadas = $this->calcular_horas_trabajadas($request->hora_entrada,$request->hora_salida_comida,$request->hora_entrada_comida,$request->hora_salida).':00';
            $id_asistencia = $this->llenarAsistencias($request,$horas_trabajadas,$contratos[$i]->id);
            $this->llenarRetardos($id_asistencia,$retardo_hora_entrada,$tiempo_retardo_comida);
          }
          else {
            $valor = 'Fine';
            $horas_trabajadas = $this->calcular_horas_trabajadas($request->hora_entrada,$request->hora_salida_comida,$request->hora_entrada_comida,$request->hora_salida).':00';
            $id_asistencia = $this->llenarAsistencias($request,$horas_trabajadas,$contratos[$i]->id);
          }
          $retardo_hora_comida = $this->calcular_tiempo_trasnc($request->hora_entrada_comida,$request->hora_salida_comida).':00';
          if ($retardo_hora_comida > '01:00:00') {
            $tiempo_comida = '01:00:00';
            $tiempo_retardo_comida = $this->calcular_tiempo_trasnc($retardo_hora_comida,$tiempo_comida).':00';
            $this->llenarRetardos($id_asistencia,$retardo_hora_entrada,$tiempo_retardo_comida);
          }
          else {
            $valor2 = 'Fine';
          }
          $tip_contrato = \App\TipoContrato::where('id','=',$contratos[$i]->tipo_contrato_id)->first();
          $nombre_tip_con = strtoupper($tip_contrato->nombre);
          if ($nombre_tip_con == 'OBRA DETERMINADA') {
            $horario_horas_extra = $hora_salida[0].':30:00';
            if ($request->hora_salida > $hora_salida[0].':30:00') {
              $horas_extra_calculadas =$this->calculo_horas_extra($request->hora_salida,$horario_horas_extra);
              $guarda_horas_extra = new \App\HorasExtra();
              $guarda_horas_extra->total_horas_extras = $horas_extra_calculadas;
              $guarda_horas_extra->asistencia_id = $id_asistencia;
              $guarda_horas_extra->save();
            }
          }
        }
        elseif ($request->dia_id >= 6 && $request->dia_id <= 7) {
          // NOTE: Esta condicion se toma en cuenta si el dia es sábado o domingo
          if ($request->hora_entrada > $hora_entrada[0].':10:00') {
            $valor = 'retardo';
            $horario_definido_retardo = $hora_entrada[0].':10:00';
            $retardo_hora_entrada = $this->calcular_tiempo_trasnc($request->hora_entrada,$horario_definido_retardo).':00';
            $horas_trabajadas = $this->calcular_tiempo_trasnc($request->hora_salida,$request->hora_entrada).':00';
            $id_asistencia = $this->llenarAsistencias($request,$horas_trabajadas,$contratos[$i]->id);
            $this->llenarRetardos($id_asistencia,$retardo_hora_entrada,$tiempo_retardo_comida);
          }
          else {
            $valor = 'Fine';
            $horas_trabajadas = $this->calcular_tiempo_trasnc($request->hora_salida,$request->hora_entrada).':00';
            $id_asistencia = $this->llenarAsistencias($request,$horas_trabajadas,$contratos[$i]->id);
          }
          $tip_contrato = \App\TipoContrato::where('id','=',$contratos[$i]->tipo_contrato_id)->first();
          $nombre_tip_con = strtoupper($tip_contrato->nombre);
          if ($nombre_tip_con == 'OBRA DETERMINADA') {
            $horario_horas_extra = $hora_salida[0].':30:00';
            if ($request->hora_salida > $hora_salida[0].':30:00') {
              $horas_extra_calculadas =$this->calculo_horas_extra($request->hora_salida,$horario_horas_extra);
              $guarda_horas_extra = new \App\HorasExtra();
              $guarda_horas_extra->total_horas_extras = $horas_extra_calculadas;
              $guarda_horas_extra->asistencia_id = $id_asistencia;
              $guarda_horas_extra->save();
            }
          }
        }
      }
    }
    return response()->json(array('status' => true));*/
  }

  /**
  * llenarAsistencias the specified resource in storage.
  *
  * @param  $request
  * @param  date  $horas_trabajadas
  * @param  int $contrato_id
  * @return int $id
  */
  public function llenarAsistencias(/*$json)*/$request,$horas_trabajadas,$contrato_id,$registroid)
  {
    /*$request = json_decode($json);
    $registro = new Registro();
    $registro->hora_entrada = $request->hora_entrada;
    $registro->hora_salida_comida = $request->hora_salida_comida;
    $registro->hora_entrada_comida = $request->hora_entrada_comida;
    $registro->hora_salida = $request->hora_salida;
    $registro->save();*/

    $asistencia = new Asistencia();
    $asistencia->fecha = $request->fecha;
    $asistencia->horas_trabajadas = $horas_trabajadas;
    $asistencia->dia_id = $request->dia_id;
    $asistencia->registro_id = $registroid; //$registro->id;
    $asistencia->empleado_id = $request->empleado_id;
    $asistencia->contrato_id = $contrato_id;
    $asistencia->save();

    return $asistencia->id;
    /*return response()->json(array('status' => true));*/
  }

  /**
  * llenarRetardos the specified resource in storage.
  *
  * @param  int $id
  * @param  date  $retardo_hora_entrada
  * @param  date  $retardo_hora_comida
  * @return \Illuminate\Http\Response
  */
  public function llenarRetardos($id,$retardo_hora_entrada,$retardo_hora_comida)
  {

    $retardo = \App\Retardo::where('asistencia_id','=',$id)->first();
    if ($retardo != null) {
      $retardo->asistencia_id = $id;
      $retardo->tiempo_retardo_entrada = $retardo_hora_entrada;
      $retardo->tiempo_retardo_comida = $retardo_hora_comida;
      $retardo->save();
    }
    else {
      $retardos = new \App\Retardo();
      $retardos->asistencia_id = $id;
      $retardos->tiempo_retardo_entrada = $retardo_hora_entrada;
      $retardos->tiempo_retardo_comida = $retardo_hora_comida;
      $retardos->save();
    }

    return response()->json(array('status' => true));
  }

  /**
  * calcular_tiempo_trasnc the specified resource in storage.
  *
  * @param  date  $hora1
  * @param  date  $hora2
  * @return string $horas
  */
  function calcular_tiempo_trasnc($hora1,$hora2){
    $separar[1]=explode(':',$hora1);
    $separar[2]=explode(':',$hora2);

    $total_minutos_trasncurridos[1] = ($separar[1][0]*60)+$separar[1][1];
    $total_minutos_trasncurridos[2] = ($separar[2][0]*60)+$separar[2][1];
    $total_minutos_trasncurridos = $total_minutos_trasncurridos[1]-$total_minutos_trasncurridos[2];
    // return $total_minutos_trasncurridos;
    if($total_minutos_trasncurridos<=59){
      if($total_minutos_trasncurridos<=9) $total_minutos_trasncurridos='0'.$total_minutos_trasncurridos;
      return('00:'.$total_minutos_trasncurridos);
    }
    elseif($total_minutos_trasncurridos>59){
      $hora_transcurrida = intval($total_minutos_trasncurridos/60);
      if($hora_transcurrida<=9) $hora_transcurrida='0'.$hora_transcurrida;
      $minuitos_transcurridos = $total_minutos_trasncurridos%60;
      if($minuitos_transcurridos<=9) $minuitos_transcurridos='0'.$minuitos_transcurridos;
      return (($hora_transcurrida).':'.$minuitos_transcurridos);

    }
  }

  /**
  * calcular_horas_trabajadas the specified resource in storage.
  *
  * @param  date  $horauno
  * @param  date  $horados
  * @param  date  $horatres
  * @param  date  $horacuatro
  * @return string $horas
  */
  public function calcular_horas_trabajadas($horauno,$horados,$horatres,$horacuatro)
  {
    $separar[1]=explode(':',$horados);
    $separar[2]=explode(':',$horauno);
    $separar[3]=explode(':',$horacuatro);
    $separar[4]=explode(':',$horatres);

    $total_minutos_trasncurridos[1] = ($separar[1][0]*60)+$separar[1][1];
    $total_minutos_trasncurridos[2] = ($separar[2][0]*60)+$separar[2][1];
    $total_minutos_trasncurridos[3] = ($separar[3][0]*60)+$separar[3][1];
    $total_minutos_trasncurridos[4] = ($separar[4][0]*60)+$separar[4][1];
    $total_minutos_trasncurridos = ($total_minutos_trasncurridos[1]-$total_minutos_trasncurridos[2]) + ($total_minutos_trasncurridos[3]-$total_minutos_trasncurridos[4]);

    if($total_minutos_trasncurridos<=59){
      if($total_minutos_trasncurridos<=9) $total_minutos_trasncurridos='0'.$total_minutos_trasncurridos;
      return($total_minutos_trasncurridos);
    }
    elseif($total_minutos_trasncurridos>59){
      $hora_transcurrida = intval($total_minutos_trasncurridos/60);
      if($hora_transcurrida<=9) $hora_transcurrida='0'.$hora_transcurrida;
      $minuitos_transcurridos = $total_minutos_trasncurridos%60;
      if($minuitos_transcurridos<=9) $minuitos_transcurridos='0'.$minuitos_transcurridos;
      return (($hora_transcurrida).':'.$minuitos_transcurridos);

    }
  }

  /**
  * calculo_horas_extra the specified resource in storage.
  *
  * @param  date  $hora1
  * @param  date  $hora2
  * @return int $horas
  */
  public function calculo_horas_extra($hora1,$hora2)
  {
    $separar[1]=explode(':',$hora1);
    $separar[2]=explode(':',$hora2);
    $total_horas =0;
    $total_minutos_trasncurridos[1] = ($separar[1][0]*60)+$separar[1][1];
    $total_minutos_trasncurridos[2] = ($separar[2][0]*60)+$separar[2][1];
    $total_minutos_trasncurridos = ($total_minutos_trasncurridos[1]-$total_minutos_trasncurridos[2]);
    if($total_minutos_trasncurridos<=60){
      $total_horas = 1;
      return $total_horas;
    }
    elseif($total_minutos_trasncurridos>60){
      $total_horas = intval($total_minutos_trasncurridos/60);
      return ($total_horas + 1);
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    $registro = Registro::findOrFail($request->id);
    $registro->fill($request->all());
    $registro->save();
    return response()->json(array(
      'status' => true
    ));
  }

  /**
   * Display the specified resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function show()
  {
    $registro = DB::table('registros')
    ->select('registros.id')
    ->orderBy('registros.id', 'desc')->first();
    $registro_id = $registro->id;
    return response()->json($registro_id);
  }



  /*Método para obtener los registros de horarios de empleados*/
  public function clasificarHorarios()
  {
    /*Declaracion de variables generales*/
    $ingresoTurno = NULL;
    $salidaComida = NULL;
    $entradaComida = NULL;
    $salidaTurno = NULL;
    $he = 1;
    $hsc= 1;
    $hec = 1;
    $hs = 1;
    $validador = 0;
    $fechaComoEntero = NULL;
    $time1 = NULL;
    $time2 = NULL;
    $time3 = NULL;
    $time4 = NULL;
    $time5 = NULL;
    $time6 = NULL;
    $time7 = NULL;
    $time8 = NULL;
    /***********************************/

    /*Obtencion de registros del reloj checador*/
    $resgistrosGenerales = DB::table('registro_ES')
      ->select('registro_ES.empleado_id', 'registro_ES.fecha')
      ->orderBy('registro_ES.empleado_id', 'ASC')
      ->distinct()
      ->get();
    /******************************************/

    /*Recorrido de registros para comparar si existen en la tabla asistencias*/
    foreach ($resgistrosGenerales as $key => $value) {
      $asistencias = DB::table('asistencias')
        ->select('asistencias.fecha', 'asistencias.empleado_id')
        ->where('asistencias.fecha', '=', $value->fecha)
        ->where('asistencias.empleado_id', '=', $value->empleado_id)
        ->get();

      /*Si no existe algun registro, se registran los faltantes*/
      if (count($asistencias) == 0) {

        /*Se convierte a entero la fecha para obtener el numero del día de la semana*/
        $fechaComoEntero = strtotime($value->fecha);
        /*se obtiene el dia de la semana en número*/
        $dia_de_semana = date("w", $fechaComoEntero);

        $resgistrosGenerales2 = DB::table('registro_ES')
          ->select('registro_ES.empleado_id', 'registro_ES.fecha', 'registro_ES.hora')
          ->orderBy('registro_ES.empleado_id', 'ASC')
          ->where('registro_ES.empleado_id', '=', $value->empleado_id)
          ->where('registro_ES.fecha', '=', $value->fecha)
          ->get();

        /*Consulta de contrato del empleado para obtener el tipo de horario laboral*/
        $contrato = DB::table('contratos')
          ->select('contratos.horario_id')
          ->where('contratos.empleado_id', '=', $value->empleado_id)
          ->where('contratos.condicion', '=', 1)
          ->orderBy('contratos.id', 'asc')
          ->first();

        /*Consulta del horario asignado por día al empleado*/
        $cat_horarios = DB::table('horarios')
          ->select('horarios.*')
          ->where('horarios.tipo_horario_id', '=', $contrato->horario_id)
          ->where('horarios.dia_id', '=', $dia_de_semana)
          ->get();

        if (count($cat_horarios) != 0) {
          //restar 3 horas a la hora de entrada definida en BD(08:00:00)
          $time1 = date('H:i:s', strtotime($cat_horarios[0]->hora_entrada.' - 3 hours'));
          //agregar 6 horas y media a la hora de entrada definida en BD(08:00:00)
          $time2 = date('H:i:s', strtotime($cat_horarios[0]->hora_entrada.' + 6 hours + 30 minutes'));

          //restar 3 horas a la hora de salida a comida definida en BD(14:30:00)
          $time3 = date('H:i:s', strtotime($cat_horarios[0]->hora_salida_comida.' - 3 hours + 00 minutes'));
          //agregar 3 horas a la hora de salida a comida definida en BD(14:30:00)
          $time4 = date('H:i:s', strtotime($cat_horarios[0]->hora_salida_comida.' + 3 hours'));

          //restar 3 horas a la hora de entrada de comida definida en BD(15:30:00)
          $time5 = date('H:i:s', strtotime($cat_horarios[0]->hora_entrada_comida.' - 3 hours'));
          //agregar 3 horas a la hora de entrada de comida definida en BD(15:30:00)
          $time6 = date('H:i:s', strtotime($cat_horarios[0]->hora_entrada_comida.' + 2 hours'));

          //restar 3 horas a la hora de salida definida en BD(18:00:00)
          $time7 = date('H:i:s', strtotime($cat_horarios[0]->hora_salida.' - 3 hours'));
          //agregar 6 horas a la hora de salida definida en BD(18:00:00)
          $time8 = date('H:i:s', strtotime($cat_horarios[0]->hora_salida.' + 6 hours'));

          $inicio = false;
          $comidaS = false;
          $comidaE = false;
          $fin = false;
          $ingresoTurno = $cat_horarios[0]->hora_entrada;
          $salidaComida = $cat_horarios[0]->hora_salida_comida;
          $entradaComida = $cat_horarios[0]->hora_entrada_comida;
          $salidaTurno = $cat_horarios[0]->hora_salida;
          $ultimo_registro = count($resgistrosGenerales2) -1 ;
          $validador = count($resgistrosGenerales2);

          /*bucle para asignar los horarios solo para los dias sábados*/
          if ($dia_de_semana == 6) {

            if (count($resgistrosGenerales2) <= 2) {
              for ($i=0; $i < count($resgistrosGenerales2); $i++) {
                $hora_checador = $resgistrosGenerales2[$i]->hora;
                if ($hora_checador >= $time1 && $hora_checador <= $time2 && $inicio == false){
                  $ingresoTurno = $hora_checador;
                  $inicio = true;
                  $he = 0;
                  continue;
                }
                if ($hora_checador >= $time7 && $fin == false){
                  $salidaTurno = $hora_checador;
                  $fin = true;
                  $hs = 0;
                  continue;
                }
              }
            }

            if (count($resgistrosGenerales2) > 2) {
              for ($i=0; $i < count($resgistrosGenerales2); $i++) {
                $hora_checador = $resgistrosGenerales2[$i]->hora;
                if ($hora_checador >= $time1 && $hora_checador <= $time2 && $inicio == false){
                  $ingresoTurno = $hora_checador;
                  $inicio = true;
                  $he = 0;
                  continue;
                }
                if ($hora_checador >= $time3 && $hora_checador <= $time4 && $comidaS == false){
                  $salidaComida = $hora_checador;
                  $comidaS = true;
                  $hsc = 0;
                  continue;
                }
                if ($hora_checador >= $time5 && $hora_checador <= $time6 && $comidaE == false){
                  $entradaComida = $hora_checador;
                  $comidaE = true;
                  $hec = 0;
                  continue;
                }
                if ($hora_checador >= $time7 && $fin == false && $ultimo_registro == $i){
                  $salidaTurno = $hora_checador;
                  $fin = true;
                  $hs = 0;
                  continue;
                }
              }
            }

          }
          /************************************************************/

          /*bucle para asignar los horarios para el resto de los días*/
          if ($dia_de_semana != 6) {
            /*bucle for para iterar los registros de acuerdo al contador y clasificarlos*/
            for ($i=0; $i < count($resgistrosGenerales2); $i++) {
              $hora_checador = $resgistrosGenerales2[$i]->hora;

                if ($hora_checador >= $time1 && $hora_checador <= $time2 && $inicio == false){
                  $ingresoTurno = $hora_checador;
                  $inicio = true;
                  $he = 0;
                  continue;
                }
                if ($hora_checador >= $time3 && $hora_checador <= $time4 && $comidaS == false){
                  $salidaComida = $hora_checador;
                  $comidaS = true;
                  $hsc = 0;
                  continue;
                }
                if ($hora_checador >= $time5 && $hora_checador <= $time6 && $comidaE == false){
                  $entradaComida = $hora_checador;
                  $comidaE = true;
                  $hec = 0;
                  continue;
                }
                if ($hora_checador >= $time7 && $fin == false && $ultimo_registro == $i){
                  $salidaTurno = $hora_checador;
                  $fin = true;
                  $hs = 0;
                  continue;
                }
            }
          }
          /********************************************************/
        }
      //}

      /*Insercion de registro de horarios*/
      $registro = new Registro();
      $registro->hora_entrada = $ingresoTurno;
      $registro->hora_salida_comida = $salidaComida;
      $registro->hora_entrada_comida = $entradaComida;
      $registro->hora_salida = $salidaTurno;
      $registro->save();
      $x = $registro->id;
      /***********************************/
      /*Verificacion de horarios faltantes*/
      if ($validador < 4 && $dia_de_semana != 6) {
        $validacionHorario = new ValidacionHorario();
        $validacionHorario->h_entrada = $he;
        $validacionHorario->h_scomida = $hsc;
        $validacionHorario->h_ecomida = $hec;
        $validacionHorario->h_salida = $hs;
        $validacionHorario->registro_id = $registro->id;
        $validacionHorario->save();

        $registro = Registro::findOrFail($registro->id);
        $registro->validacion = 1;
        $registro->save();
      }
      /************************************/
      /*Asignación de los horarios dentro de la variable json*/
      $json =
        [
          'id' => 0,
          'hora_entrada' => $ingresoTurno,
          'hora_salida_comida' => $salidaComida,
          'hora_entrada_comida' => $entradaComida,
          'hora_salida' => $salidaTurno,
          'fecha' => $value->fecha,
          'dia_id' => $dia_de_semana,
          'empleado_id' => $value->empleado_id,
          'id_registro' => $registro->id,
          'contador' => $validador,
        ];

        /*Codificación del json*/
        $array = json_encode($json);
        /*Envio de array procesado al método llenarasistencias*/
        //11111$this->llenarAsistencias($array);
        $this->generarHorarios($array);
        /*Reseteo de variables*/
        $ingresoTurno = NULL;
        $salidaComida = NULL;
        $entradaComida = NULL;
        $salidaTurno = NULL;
        $he = 1;
        $hsc= 1;
        $hec = 1;
        $hs = 1;
        $fechaComoEntero = NULL;
        /**********************/
      }
    }

    return response()->json(array('status' => true));
  }

  public function obtenerHorarios()
  {
    //
  }
  public function generarHorarios($json)
  {
    $request = json_decode($json);
    $valor = '';
    $retardo_hora_entrada = '';
    $horas_trabajadas = '';
    $retardo_hora_comida = '';
    $valor2 = '';
    $tiempo_retardo_comida = '';
    $id_asistencia = 0;
    $contratos = \App\Contrato::where('empleado_id','=',$request->empleado_id)->where('condicion','=','1')->get();
    for ($i=0; $i < count($contratos) ; $i++)
    {
      $horario = \App\Horario::where('tipo_horario_id','=',$contratos[$i]->horario_id)->where('dia_id', '=', $request->dia_id)->first();
      if($horario->hora_entrada != null ){
        $entrada = $horario->hora_entrada;
        $salida = $horario->hora_salida;
        $hora_entrada = explode(':',$entrada);
        $hora_salida = explode(':',$salida);
        if ($request->dia_id >= 1 && $request->dia_id <= 5) {
          // NOTE: En esta condicion se tomo en cuenta si el dia que se envia esta entre lunes a viernes
          if ($request->hora_entrada > $hora_entrada[0].':10:00') {
            $horario_definido_retardo = $hora_entrada[0].':10:00';
            $retardo_hora_entrada = $this->calcular_tiempo_trasnc($request->hora_entrada,$horario_definido_retardo).':00';
            $horas_trabajadas = $this->calcular_horas_trabajadas($request->hora_entrada,$request->hora_salida_comida,$request->hora_entrada_comida,$request->hora_salida).':00';
            $id_asistencia = $this->llenarAsistencias($request,$horas_trabajadas,$contratos[$i]->id,$request->id_registro);
            $this->llenarRetardos($id_asistencia,$retardo_hora_entrada,$tiempo_retardo_comida);
          }
          else {
            $valor = 'Fine';
            $horas_trabajadas = $this->calcular_horas_trabajadas($request->hora_entrada,$request->hora_salida_comida,$request->hora_entrada_comida,$request->hora_salida).':00';
            $id_asistencia = $this->llenarAsistencias($request,$horas_trabajadas,$contratos[$i]->id,$request->id_registro);
          }
          $retardo_hora_comida = $this->calcular_tiempo_trasnc($request->hora_entrada_comida,$request->hora_salida_comida).':00';
          if ($retardo_hora_comida > '01:00:00') {
            $tiempo_comida = '01:00:00';
            $tiempo_retardo_comida = $this->calcular_tiempo_trasnc($retardo_hora_comida,$tiempo_comida).':00';
            $this->llenarRetardos($id_asistencia,$retardo_hora_entrada,$tiempo_retardo_comida);
          }
          else {
            $valor2 = 'Fine';
          }
          $tip_contrato = \App\TipoContrato::where('id','=',$contratos[$i]->tipo_contrato_id)->first();
          $nombre_tip_con = strtoupper($tip_contrato->nombre);
          if ($nombre_tip_con == 'OBRA DETERMINADA') {
            $horario_horas_extra = $hora_salida[0].':30:00';
            if ($request->hora_salida > $hora_salida[0].':30:00') {
              $horas_extra_calculadas =$this->calculo_horas_extra($request->hora_salida,$horario_horas_extra);
              $guarda_horas_extra = new \App\HorasExtra();
              $guarda_horas_extra->total_horas_extras = $horas_extra_calculadas;
              $guarda_horas_extra->asistencia_id = $id_asistencia;
              $guarda_horas_extra->save();
            }
          }
        }
        elseif ($request->dia_id >= 6 && $request->dia_id <= 7) {
          // NOTE: Esta condicion se toma en cuenta si el dia es sábado o domingo
          if ($request->hora_entrada > $hora_entrada[0].':10:00') {
            $valor = 'retardo';
            $horario_definido_retardo = $hora_entrada[0].':10:00';
            $retardo_hora_entrada = $this->calcular_tiempo_trasnc($request->hora_entrada,$horario_definido_retardo).':00';
            $horas_trabajadas = $this->calcular_tiempo_trasnc($request->hora_salida,$request->hora_entrada).':00';
            $id_asistencia = $this->llenarAsistencias($request,$horas_trabajadas,$contratos[$i]->id,$request->id_registro);
            $this->llenarRetardos($id_asistencia,$retardo_hora_entrada,$tiempo_retardo_comida);
          }
          else {
            $valor = 'Fine';
            $horas_trabajadas = $this->calcular_tiempo_trasnc($request->hora_salida,$request->hora_entrada).':00';
            $id_asistencia = $this->llenarAsistencias($request,$horas_trabajadas,$contratos[$i]->id,$request->id_registro);
          }
          $tip_contrato = \App\TipoContrato::where('id','=',$contratos[$i]->tipo_contrato_id)->first();
          $nombre_tip_con = strtoupper($tip_contrato->nombre);
          if ($nombre_tip_con == 'OBRA DETERMINADA') {
            $horario_horas_extra = $hora_salida[0].':30:00';
            if ($request->hora_salida > $hora_salida[0].':30:00') {
              $horas_extra_calculadas =$this->calculo_horas_extra($request->hora_salida,$horario_horas_extra);
              $guarda_horas_extra = new \App\HorasExtra();
              $guarda_horas_extra->total_horas_extras = $horas_extra_calculadas;
              $guarda_horas_extra->asistencia_id = $id_asistencia;
              $guarda_horas_extra->save();
            }
          }
        }
      }
    }
    return response()->json(array('status' => true));
  }
  /*************************************************************/
  public function post(Request $request)
  {
    $registro = Registro::where('id',$request->id)->first();
    $registro->estado = $request->estado;
    $registro->observaciones = $request->observaciones;
    $registro->save();
    return response()->json(array('status' => true));

  }


}
