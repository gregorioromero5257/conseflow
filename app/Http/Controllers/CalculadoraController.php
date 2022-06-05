<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use \App\Http\Helpers\Utilidades;


class CalculadoraController extends Controller
{
  //

  public function __construct()
  {
    // $this->id = $id;

    $this->dias = 0;
    $this->inicial= 0;
    $this->limite= 0;
    $this->excedente= 0;
    $this->porcentaje= 0;
    $this->marginal= 0;
    $this->cuota= 0;
    $this->isr= 0;
    $this->imss= 0;
    $this->subsidio= 0;
    $this->final= 0;
    $this->temporal= 0;
    $this->diario= 0;
    $this->factor_calculos = 0;
    $this->couta_patronal = 0;
    $this->costo_total_trabajador = 0;
    $this->sbc= 0;
    $this->sdi= 0;
    $this->dias_t = '';
    $this->rango = '';
    $this->aguinaldo = 0;
    $this->vacaciones = 0;
    $this->prima_vacacional =0;
    $this->finiquito = 0;

    //UMA 2020
    $this->uma = 0;
    //UMA 2021
    // $this->uma_nuevo = 89.62;


    $this->factor = 0;
    $this->factor_finiquito = 0;
    $this->subsidio = true;
    $this->imss = true;
    $this->tipo = '';
    $this->periodo = '';
    $this->tablaSemanal =[0.01, 133.22, 1130.65, 1987.03, 2309.80, 2765.43, 5577.54, 8790.96, 16783.35, 22377.75, 67133.23];
    $this->cuotaSemanal = [0.00, 2.59, 66.36, 159.53, 211.19, 292.88, 893.55, 1649.34, 4047.05, 5837.23, 21054.11 ];
    $this->porcSemanal = [1.92, 6.40, 10.88, 16.00, 17.92, 21.36, 23.52, 30.00, 32.00, 34.00, 35.00 ];
    $this->ingSubSemanal = [407.34, 610.97, 799.69, 814.67, 1023.76, 1086.20, 1228.58, 1433.33, 1638.08, 1699.89 ];
    $this->canSubSemanal = [93.73, 93.66, 93.66, 90.44, 88.06, 81.55, 74.83, 67.83, 58.38, 50.12, 0.00 ];
    $this->tablaQuincenal = [0.01, 285.46, 2422.81, 4257.91, 4949.56, 5925.91, 11951.86, 18837.76, 35964.31, 47952.31, 143856.91 ];
    $this->cuotaQuincenal = [0.00, 5.55, 142.20, 341.85, 425.55, 627.60, 1914.75, 3534.30, 8672.25, 12508.35, 45115.95 ];
    $this->porcQuincenal = [1.92, 6.40, 10.88, 16.00, 17.92, 21.36, 23.52, 30.00, 32.00, 34.00, 35.00 ];
    $this->ingSubQuincenal = [872.86, 1309.21, 1713.61, 1745.71, 2193.76, 2327.56, 2632.66, 3071.41, 3510.16, 3642.61 ];
    $this->canSubQuincenal = [200.85, 200.70, 200.70, 193.80, 188.70, 174.75, 160.35, 145.35, 125.10, 107.40, 0.00 ];

    ///tablas mensuales  2019
    //--LIMITE INFERIOR
    $this->tablaMensual = [];
    //--COUTA FIJA
    $this->cuotaMensual = [];
    //-- %APLICABLE SOBRE LE EXECENTE DEL LIMUTE INFERIOR
    $this->porcMensual = [];
    //- SUBSIDIO MENSUAL HASTA INGRESOS DE
    $this->ingSubMensual = [];
    //CANTIDAD DE SUBSIDIO PARA EL EMPLEO DIARIO
    $this->canSubMensual = [];
    //fin de tablas mesuales 2019

    // ///tablas mensuales  2021
    // //--LIMITE INFERIOR
    // $this->tablaMensualNuevo = [0.01, 664.59, 5470.93, 9614.67, 11176.63, 13381.48, 26988.51, 42537.59, 81211.26, 108281.68, 324845.02 ];
    // //--COUTA FIJA
    // $this->cuotaMensualNuevo = [0.00, 12.38, 321.26, 772.10, 1022.01, 1417.12, 4323.58, 7980.73, 19582.83, 28245.36, 101876.90 ];
    // //-- %APLICABLE SOBRE LE EXECENTE DEL LIMUTE INFERIOR
    // $this->porcMensualNuevos = [1.92, 6.40, 10.88, 16.00, 17.92, 21.36, 23.52, 30.00, 32.00, 34.00, 35.00 ];
    // //- SUBSIDIO MENSUAL HASTA INGRESOS DE
    // $this->ingSubMensualNuevo = [1768.96, 2653.38, 3472.84, 3537.87, 4446.15, 4717.18, 5335.42, 6224.67, 7113.90, 7382.33 ];
    // //CANTIDAD DE SUBSIDIO PARA EL EMPLEO DIARIO
    // $this->canSubMensualNuevo = [407.02, 406.83, 406.62, 392.77, 382.46, 354.23, 324.87, 294.63, 253.54, 217.61, 0.00 ];
    // //fin de tablas mesuales 2021s

    $this->diasVac = [6,8,10,12,14,16,18,20,22,24];
    $this->aniosLab = [0.00,1.00,2.00,3.00,4.00,5.00,10.00,15.00,20.00,25.00,30.00];
  }

  public function calcularFactorFiniquito($valores)
  {
    $dias_anio = 365;
    $prima_vacacional = 1.5;

    $factor_dia_trabajado = 15 / 365;
    $uno = $factor_dia_trabajado * (float)$valores[0];
    $aguinaldo = $uno;

    $factor_en_dias = $dias_anio + $prima_vacacional + $aguinaldo;

    $factor_integrecion = $factor_en_dias / $dias_anio ;

    return $factor_integrecion;
  }

  public function calcularFactor($valores)
  {
    // code...
  }

  public function index($id)
  {
    $valores = explode('&',$id);
    $this->dias = $valores[0];
    $sueldo_s = $valores[1];
    $this->dias_t = $valores[0];

    if($valores[2] == 2020){
      $this->uma = 86.98;

      ///tablas mensuales  2019
      //--LIMITE INFERIOR
      $this->tablaMensual = [0.01, 578.53, 4910.29, 8629.21, 10031.08, 12009.95, 24222.32, 38177.70, 72887.51, 97183.34, 291550.01 ];
      //--COUTA FIJA
      $this->cuotaMensual = [0.00, 11.11, 288.33, 692.96, 917.26, 1271.87, 3880.44, 7162.74, 17575.68, 25350.35, 91435.02 ];
      //-- %APLICABLE SOBRE LE EXECENTE DEL LIMUTE INFERIOR
      $this->porcMensual = [1.92, 6.40, 10.88, 16.00, 17.92, 21.36, 23.52, 30.00, 32.00, 34.00, 35.00 ];
      //- SUBSIDIO MENSUAL HASTA INGRESOS DE
      $this->ingSubMensual = [1768.97, 2653.39, 3472.85, 3537.88, 4446.16, 4717.19, 5335.43, 6224.68, 7113.91, 7382.34 ];
      //CANTIDAD DE SUBSIDIO PARA EL EMPLEO DIARIO
      $this->canSubMensual = [407.02, 406.83, 406.62, 392.77, 382.46, 354.23, 324.87, 294.63, 253.54, 217.61, 0.00 ];
      //fin de tablas mesuales 2019

    }elseif ($valores[2] == 2021) {
      $this->uma = 89.62;

      ///tablas mensuales  2021
      //--LIMITE INFERIOR
      $this->tablaMensual = [0.01, 664.59, 5470.93, 9614.67, 11176.63, 13381.48, 26988.51, 42537.59, 81211.26, 108281.68, 324845.02 ];
      //--COUTA FIJA
      $this->cuotaMensual = [0.00, 12.38, 321.26, 772.10, 1022.01, 1417.12, 4323.58, 7980.73, 19582.83, 28245.36, 101876.90 ];
      //-- %APLICABLE SOBRE LE EXECENTE DEL LIMUTE INFERIOR
      $this->porcMensual = [1.92, 6.40, 10.88, 16.00, 17.92, 21.36, 23.52, 30.00, 32.00, 34.00, 35.00 ];
      //- SUBSIDIO MENSUAL HASTA INGRESOS DE
      $this->ingSubMensual = [1768.96, 2653.38, 3472.84, 3537.87, 4446.15, 4717.18, 5335.42, 6224.67, 7113.90, 7382.33 ];
      //CANTIDAD DE SUBSIDIO PARA EL EMPLEO DIARIO
      $this->canSubMensual = [407.02, 406.83, 406.62, 392.77, 382.46, 354.23, 324.87, 294.63, 253.54, 217.61, 0.00 ];
      //fin de tablas mesuales 2021s

    }


    $this->factor_finiquito = $this->calcularFactorFiniquito($valores);
    $this->factor = $this->calcularFactor($valores);

    $re = $this->calcular($this->dias,'Mensual',(30 * $sueldo_s), $sueldo_s);

    return response()->json($re);
  }

  public function calcular($dl, $p, $i, $sd)
  {
    $this->tipo ="Neto";

    $isr = $this->calcularISR($dl, $p, $i);

    $sbc = $this->calcSBC($p, $dl , $i, $sd);
    $imss = $this->calcIMSS($sbc[3]['sbc'], $dl);
    $patron = $this->calcCuotaP($sbc[3]['sbc'], $dl);
    $finiquito = $this->finiquito($sbc[3]['sbc'], $dl);

    return [
      'isr' => $isr,
      'sbc' => $sbc,
      'imss' => $imss,
      'patron' => $patron,
      'finiquito' => $finiquito,
      ];

    // return $this->updateSalary($p,(float)$i,(float)$dl);
  }

  public function calcularISR($dl, $periodo, $sueldo)
  {
    $base_gravable_salario = $sueldo;

    $tabla = $this->getTable($periodo);
    $porcentaje = $this->getPorcentaje($periodo);
    $cuota = $this->getCuota($periodo);
    $subsidioIng = $this->getIngSub($periodo);
    $subsidioCan = $this->getCanSub($periodo);

    for ($i=0; $i < count($tabla); $i++) {
      if ((float)($sueldo) >= $tabla[$i] && (float)($sueldo) < $tabla [$i+1]) {
        $position = $i;
      }
      else if ((float)($sueldo) >= $tabla[count($tabla) - 1]) {
        $position = count($tabla) - 1;
      }
    }

    $limite = $tabla[$position];
    $tasa_isr = $porcentaje[$position];
    $cuota_fija = $cuota[$position];


    return [
      'base_gravable_salario' => $base_gravable_salario,
      'limite_inferior' => $limite,
      'excedente_limite_inferior' => (float)$base_gravable_salario - (float)$limite,
      'tasa_isr' => $tasa_isr,
      'isr_excente_limite_inferior' => (((float)$base_gravable_salario - (float)$limite) * (float)$tasa_isr)/100,
      'cuota_fija' => $cuota_fija,
      'isr_cargo' => ((((float)$base_gravable_salario - (float)$limite) * (float)$tasa_isr)/100) + $cuota_fija,
    ];

  }

  public function updateSalary($p,$i,$dl) {

    // return $this->tipo;
    $result;
    if ($this->tipo === "Bruto") {
      $this->temporal= (float)($i);

      while ($this->final < $this->temporal) {
        $this->calculateSalary($p,$i,$dl);
        $i = ((float)($i) + .01);
      }
      while ($this->final > $this->temporal) {
        $this->calculateSalary($p,$i,$dl);
        $i = ((float)($i) - .01);
      }
      $this->final = (float)($i);
      $i = $this->temporal;

    }
    else {
      $result = $this->calculateSalary($p,$i,$dl);
    }
    // return $this->sbc.;
    $this->couta_patronal = $this->calcCuotaP($this->sbc[0]);
    $this->costo_total_trabajador = (float)($this->final) + (float)($this->couta_patronal);
    return [
      'uno' => $result,
      'dos' => $this->couta_patronal];
    }

    public function calculateSalary($p,$in,$dl){
      $tabla;$cuota;$porcentaje;$position;$subsidioIng;$subsidioCan;$i;
      $tabla = $this->getTable($p);

      //CALCULO ISR
      $cuota = $this->getCuota($p);
      //CALCULO ISR

      $porcentaje = $this->getPorcentaje($p);
      $subsidioIng = $this->getIngSub($p);
      $subsidioCan = $this->getCanSub($p);

      for ($i=0; $i < count($tabla); $i++) {
        if ((float)($in) >= $tabla[$i] && (float)($in) < $tabla [$i+1]) {
          $position = $i;
        }
        else if ((float)($in) >= $tabla[count($tabla) - 1]) {
          $position = count($tabla) - 1;
        }
      }
      //CALCULO ISR
      $this->limite = $tabla[$position];
      $this->excedente = (float)($in) - $this->limite;
      $this->porcentaje = $porcentaje[$position];
      $this->cuota = $cuota[$position];
      $this->marginal = $this->excedente * ($this->porcentaje/100);
      $isr = $this->cuota + $this->marginal;
      //CALCULO ISR

      // $sbc = $this->calcSBC($p,$dl,$in);
      $this->sbc = $sbc;
      $imss = $this->calcIMSS($sbc[0],$dl);

      for ($i=0; $i < count($subsidioIng); $i++) {
        if ((float)($in) < $subsidioIng[$i]) {
          $position = $i;
          break;
        }
        else if ((float)($in) >= $subsidioIng[count($subsidioIng) - 1]) {
          $position = count($subsidioIng);
        }
      }

      $this->subsidio = $subsidioCan[$position];
      $this->final = (((float)($in) - $imss['imss'] - $isr + $this->subsidio));
      return
      [
        'p'=>$p,
        'dl' => $dl,
        'in' => $in,
        'neto' => $this->final,
        'isr' => $isr,
        'imss' => $imss,
        'sbc' => $sbc,
        'finiquito' => $this->finiquito(),
        'excedente' => $this->excedente,
        'porcentaje' => $this->porcentaje,
        'couta' => $this->cuota,
        'marginal'  => $this->marginal,
        'limite' => $this->limite,
        // 'couta_p' => $cuota,porcentaje
      ];
    }

    public function calcCuotaP($value,$dl){
      $riesgo_trabajo_patron;$especie_patron;$excendete_patron;$pensionados_patron;$prestaciones_patron;$invalidez_patron;$retiro_patron;
      $cesantia_patron;$guarderia_patron;$infonavit_patron;$couta_patronal;

      $excendete_patron = ($value - (3 * $this->uma)) * .01100 * $dl;
      // return $excendete_patron;

      $riesgo_trabajo_patron = ($value * $dl) * 0.035888;
      $especie_patron = $dl * $this->uma * 0.20400;
      $pensionados_patron = $value * $dl * 0.01050;
      $prestaciones_patron = $value * $dl * 0.00700;
      $invalidez_patron = $value * $dl * 0.01750;
      $retiro_patron =  $value * $dl * 0.0200;
      $cesantia_patron = $value * $dl * 0.03150;
      $guarderia_patron = $value * $dl * 0.01000;
      $infonavit_patron = $value * $dl * 0.05000;

      $couta_patronal = $riesgo_trabajo_patron + $especie_patron + $excendete_patron + $pensionados_patron + $prestaciones_patron + $invalidez_patron
      + $retiro_patron + $cesantia_patron + $guarderia_patron + $infonavit_patron;

      return
      [
        'dias' =>  $dl,
        'excendete_patron' => $excendete_patron,
        'especie_patron' => $especie_patron,
        'pensionados_patron' => $pensionados_patron,
        'prestaciones_patron' => $prestaciones_patron,
        'invalidez_patron' => $invalidez_patron,
        'retiro_patron' => $retiro_patron,
        'cesantia_patron' => $cesantia_patron,
        'guarderia_patron' => $guarderia_patron,
        'infonavit_patron' => $infonavit_patron,
        'couta_patronal' => $couta_patronal,
      ]
      ;
    }

    public function getTable($value) {

      if ($value === "Semanal") {
        return $this->tablaSemanal;
      }
      else if ($value === "Quincenal") {
        return $this->tablaQuincenal;
      }
      else if ($value === "Mensual") {
        return $this->tablaMensual;
      }
    }

    public function getCuota($value) {
      if ($value === "Semanal") {
        return $this->cuotaSemanal;
      }
      else if ($value === "Quincenal") {
        return $this->cuotaQuincenal;
      }
      else if ($value === "Mensual") {
        return $this->cuotaMensual;
      }
    }

    public function getPorcentaje($value) {
      if ($value === "Semanal") {
        return $this->porcSemanal;
      }
      else if ($value === "Quincenal") {
        return $this->porcQuincenal;
      }
      else if ($value === "Mensual") {
        return $this->porcMensual;
      }
    }

    public function getIngSub($value) {

      if ($value === 'Semanal') {
        return $this->ingSubSemanal;
      }
      else if ($value === 'Quincenal') {
        return $this->ingSubQuincenal;
      }
      else if ($value === 'Mensual') {
        return $this->ingSubMensual;
      }
    }

    public function getCanSub($value) {

      if ($value === "Semanal") {
        return $this->canSubSemanal;
      }
      else if ($value === "Quincenal") {
        return $this->canSubQuincenal;
      }
      else if ($value === "Mensual") {
        return $this->canSubMensual;
      }
    }

    public function calcSBC($value, $dl, $in, $sd ) {
      $sbc;$sdi;

      $factor = $this->factor($value, $dl, $in, $sd);

      // if ($value === "Semanal") {
      //   $sdi = ($in/$dl);
      //   $sbc = $sdi * $factor;
      // }
      // else if ($value === "Quincenal") {
      //   $sdi = (float)$in/(float)$dl;
      //   $sbc = $sdi * $factor;
      // }
      // else if ($value === "Mensual") {
      //   $sdi = (float)$in/(float)$dl;
      //   $sbc = $sdi * $factor;
      // }
      // if ($sbc > 2112.25) {
      //   $sbc = 2112.25;
      // }
      // $this->sdi = $sbc;
      return ([$value, $dl, $in, $factor]);
    }

    public function factor($m, $dl, $in, $sd)
    {
      $salario_diario = $sd;
      $dias_anio = 365;
      $dias_laborados = $dl;
      $vacaciones = $this->vacaciones($dl);
      $prima_vacacional = 0.25 * $vacaciones;
      // $aguinaldo = $this->aguinaldo();
      $aguinaldo = 15;
      $factor_en_dias = $dias_anio + $prima_vacacional + $aguinaldo;
      $factor_integracion = $factor_en_dias / $dias_anio;
      $salario_b_c = $factor_integracion * $salario_diario;
      // return $salario_b_c;
      return [
        'salario_diario' => $salario_diario,
        'dia_anio' => $dias_anio,
        'dias_laborados' =>  $dias_laborados,
        'vacaciones' => $vacaciones,
        'prima_vacacional' => $prima_vacacional,
        'aguinaldo' => $aguinaldo,
        'factor_en_dias' => $factor_en_dias,
        'factor_integracion' => $factor_integracion,
        'sbc' => $salario_b_c,
      ];
    }

    public function vacaciones($dl)
    {
      $position;
      if ( ($dl / 365) <= 1) {
        $position = 0;
      }elseif (($dl / 365) > 1 && ($dl / 365) <= 2) {
        $position = 1;
      }elseif (($dl / 365) > 2 && ($dl / 365) <= 3) {
        $position = 2;
      }elseif (($dl / 365) > 3 && ($dl / 365) <= 4) {
        $position = 3;
      }elseif (($dl / 365) > 4 && ($dl / 365) <= 5) {
        $position = 4;
      }elseif (($dl / 365) > 5 && ($dl / 365) <= 10) {
        $position = 5;
      }elseif (($dl / 365) > 10 && ($dl / 365) <= 15) {
        $position = 6;
      }elseif (($dl / 365) > 15 && ($dl / 365) <= 20) {
        $position = 7;
      }

      $dias_vac = $this->diasVac[$position];
      $dias_anio = (ceil($dl/365)) * 365;

      $vacaciones =  round(($dias_vac/$dias_anio) * (float)$dl, 2);
      return $vacaciones;
      // return [$dias_vac, $dias_anio, $dl, $vacaciones];
    }

    // public function aguinaldo($dl)
    // {
    //   if () {
    //     // code...
    //   }
    // }

      public function calcIMSS($value,$dl){
      $prestaciones;$pensionados;$invalidez;$cesantia;$imss;$excedente;

      if ($value > (3 * $this->uma)) {
        $excedente = ($value - (3 * $this->uma)) * .004 * $dl;
      }
      else {
        $excedente = 0;
      }
      $prestaciones = $value * .0025 * $dl;
      $pensionados = $value * .00375 * $dl;
      $invalidez = $value * .00625 * $dl;
      $cesantia = $value * 0.01125 * $dl;
      $imss = (float)$excedente + (float)$prestaciones + (float)$pensionados + (float)$invalidez + (float)$cesantia;
      return  [
        'exedente' => $excedente,
        'prestaciones' => $prestaciones,
        'pensionados' => $pensionados,
        'invalidez' => $invalidez,
        'cesantia' => $cesantia,
        'imss' =>$imss,
        'dias' => $dl,
        'execentdde' => $value,
        'uma' => $this->uma,
      ];
      return  ($imss);
    }

    public function finiquito( $sdi, $dl){
      $rango_anio = [365,730,1095,1460,1825,2190,2555,2920,3285,3650,4015,4380,4745,5110];
      $anio = [6,8,10,12,14,14,14,14,16,16,16,16,16];
      $dias_vacaciones_calculado_uno = 0;
      $dias_vacaciones_calculado_dos = 0;
        // $anio_s = 0;
        for ($i=0; $i < count($rango_anio); $i++) {
          if ((float)($dl) < $rango_anio[$i]) {
            $position = $i;
            break;
          }
          else if ((float)($dl) >= $rango_anio[count($rango_anio) - 1]) {
            $position = count($rango_anio);
          }
        }
        $dias_tres = 0;
      if ($position == 0) {
        $rango_anio_position = $rango_anio[0];
        $anio_position = $anio[0];
        $dias_vacaciones_calculado_uno = $dl;
        $dias_vacaciones_calculado_dos = ($anio_position / 365) * $dl;
        $dias_tres = $dl;
      }elseif ($position > 0) {
        $rango_anio_position = $rango_anio[$position - 1];
        $anio_position = $anio[$position];
        $dias_vacaciones_calculado_uno = $dl - $rango_anio_position;
        $dias_vacaciones_calculado_dos = ($anio_position / 365) * $dias_vacaciones_calculado_uno;
        $dias_tres = $dl - $rango_anio_position;
      }


      $aguinaldo_anio_completo = $sdi;
      $dias_aguinaldo_pagar = (15/365) * $dias_tres;
      $aguinaldo =  $dias_aguinaldo_pagar * $sdi;///

      $dias_vacaciones = (15/365) * $dias_tres;
      $vacaciones_ = $dl * $dias_vacaciones;
      $vacaciones = $dias_vacaciones * $sdi;
      $prima_vacacional = $vacaciones * 0.25;


      $finiquito = $aguinaldo + $vacaciones + $prima_vacacional;


      return [
        'aguinaldo' => $aguinaldo,
        'finiquito' =>  $finiquito,
        'dias_aguinaldo_pagar' => $dias_aguinaldo_pagar,
        'sdi' => $sdi,
        'dias_vacaciones' => $dias_vacaciones,
        'vacaciones' => $vacaciones,
        'prima_vacacional' => $prima_vacacional,
        'position' =>$position,
        'dl' => (float)$dl,
        'dias_vacaciones_calculado_uno' => $dias_vacaciones_calculado_uno,
        'dias_vacaciones_calculado_dos' => $dias_vacaciones_calculado_dos,
        'rango_anio_position' => $rango_anio_position,
        'anio_position' => $anio_position,
        'dias_tres' => $dias_tres,
      ];
    }

    // public function calculo_Dias(){
    //   $dia = 30;
    //   if($this->rango === 'dias'){
    //     $dia = $this->dias_t;
    //   }else if ($this->rango === 'meses') {
    //     $dia_meses = floor($this->dias_t * 30);
    //     $dia = $dia_meses;
    //   }else if ($this->rango === 'anios') {
    //     $dia_anios = floor($this->dias_t * 365);
    //     $dia = $dia_anios;
    //   }
    //   return $dia;
    // }

    public function DiasVac($d){
      // return $d;
      $tabla_uno; $tabla_dos;$position;$i;
      $this->aniosLab;

      for ($i=0; $i < count($this->aniosLab); $i++) {
        if ( ( (float)($d)/365 )
        >= $this->aniosLab[$i]
        && ((float)($d)/365)
        < $this->aniosLab[$i+1]) {
          $position = $i;
        }
        else if (((float)($d)/365) >= $this->aniosLab[count($this->aniosLab) - 1]) {
          $position = count($this->aniosLab) - 1;
        }
      }
      $tabla_dos = $this->diasVac[$position];
      return $tabla_dos;
    }

    public function gua(Request $request)
    {
      try {
        // return response()->json($request);

        $image_64 = $request->image; //your base64 encoded data

        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf

        $replace = substr($image_64, 0, strpos($image_64, ',')+1);

        // find substring fro replace here eg: data:image/png;base64,

        $image = str_replace($replace, '', $image_64);

        $image = str_replace(' ', '+', $image);

        $imageName = uniqid().'ee.'.$extension;


        Storage::disk('local')->put('Archivos/'.$imageName, base64_decode($image));

        return response()->json(['status' => true]);

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }

    }

  }
