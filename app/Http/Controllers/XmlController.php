<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Helpers\Utilidades;
use App\Imports\XmlImport;
use Maatwebsite\Excel\Facades\Excel;
use App\FacturaNomina;
use App\Receptor;
use App\NominaC;
use App\Percepciones;
use App\Concepto;
use App\Deducciones;
use App\OtroPago;
use App\MovimientosN;
use App\SemanaN;
use DateTime;
use Illuminate\Support\Facades\DB;
use App\Exports\ReporteSemanaExport;
use App\Exports\ReporteQuincenalExport;



class XmlController extends Controller
{
  public function __construct()
  {
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



    $this->uma = 86.98;
    $this->factor = 1.0452;
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
   $this->tablaMensual = [0.01, 578.53, 4910.29, 8629.21, 10031.08, 12009.95, 24222.32, 38177.70, 72887.51, 97183.34, 291550.01 ];
   $this->cuotaMensual = [0.00, 11.11, 288.33, 692.96, 917.26, 1271.87, 3880.44, 7162.74, 17575.68, 25350.35, 91435.02 ];
   $this->porcMensual = [1.92, 6.40, 10.88, 16.00, 17.92, 21.36, 23.52, 30.00, 32.00, 34.00, 35.00 ];
   $this->ingSubMensual = [1768.97, 2653.39, 3472.85, 3537.88, 4446.16, 4717.19, 5335.43, 6224.68, 7113.91, 7382.34 ];
   $this->canSubMensual = [407.02, 406.83, 406.62, 392.77, 382.46, 354.23, 324.87, 294.63, 253.54, 217.61, 0.00 ];
  $this->diasVac = [6,6,8,10,12,14,16,18,20,22,24];
  $this->aniosLab = [0.00,1.00,2.00,3.00,4.00,5.00,10.00,15.00,20.00,25.00,30.00];
  }

  public function calcular($dl,$p,$i)
  {
    // $this->inicial = $dl;
    $this->tipo ="Neto";
    // $this->periodo = $p;
    // return [$dl,$p,$i];
     // $this->updateSalary($p,(float)$i,(float)$dl);

    // $arreglo [] =
    return $this->updateSalary($p,(float)$i,(float)$dl);

    // code...
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

  public function calcSBC($value,$dl,$in) {
   $sbc;$sdi = 0;
   // return $in/$dl;
    if ($value === "Semanal") {
      // $this->dias = $dl;
      // return $dl;
      $sdi = ($in/$dl);
      $sbc = $sdi * $this->factor;
    }
    else if ($value === "Quincenal") {
      // $this->dias = $dl;
      $sdi = (float)$in/(float)$dl;
      $sbc = $sdi * $this->factor;
    }
    else if ($value === "Mensual") {
      // $this->dias = $dl;
      $sdi = (float)$in/(float)$dl;
      $sbc = $sdi * $this->factor;
    }
    if ($sbc > 2112.25) {
      $sbc = 2112.25;
    }
    return ($sbc);
  }

  public function calcIMSS($value,$dl){
    $prestaciones;$pensionados;$invalidez;$cesantia;$imss;$excedente;

    if ($value > (3 * $this->uma)) {
    $excedente = ($value - (3 * $this->uma)) * .004 * $dl;
    }
    else {
      $excedente = 0;
    }
    // especie
    $prestaciones = $value * .0025 * $dl;
    $pensionados = $value * .00375 * $dl;
    $invalidez = $value * .00625 * $dl;
    $cesantia = $value * 0.01125 * $dl;
    $imss = (float)$excedente + (float)$prestaciones + (float)$pensionados + (float)$invalidez + (float)$cesantia;


    return  ($imss);
  }

  public function calcCuotaP($value){
    $riesgo_trabajo_patron;$especie_patron;$excendete_patron;$pensionados_patron;$prestaciones_patron;$invalidez_patron;$retiro_patron;
    $cesantia_patron;$guarderia_patron;$infonavit_patron;$couta_patronal;

    $excendete_patron = ($value - (3 * $this->uma)) * .01100 * $this->dias;

    $riesgo_trabajo_patron = ($value * $this->dias) * 0.035888;
    $especie_patron = $this->dias * $this->uma * 0.20400;
    $pensionados_patron = $value * $this->dias * 0.01050;
    $prestaciones_patron = $value * $this->dias * 0.00700;
    $invalidez_patron = $value * $this->dias * 0.01750;
    $retiro_patron =  $value * $this->dias * 0.0200;
    $cesantia_patron = $value * $this->dias * 0.03150;
    $guarderia_patron = $value * $this->dias * 0.01000;
    $infonavit_patron = $value * $this->dias * 0.05000;

    $couta_patronal = $riesgo_trabajo_patron + $especie_patron + $excendete_patron + $pensionados_patron + $prestaciones_patron + $invalidez_patron
    + $retiro_patron + $cesantia_patron + $guarderia_patron + $infonavit_patron;

    return ($couta_patronal);
  }

  public function finiquito(){
    $dias = $this->calculo_Dias();
    $vac = $this->DiasVac($dias);

    $finiquito;
    $factor_dia_trabajado = 15 / $dias;
    $uno;
    $uno = $factor_dia_trabajado * (float)($this->dias_t);
    $this->aguinaldo = $uno * $this->sdi;

    $factor_dia_trabajado_vac = $vac / 365;
    $dos;
    $dos = $factor_dia_trabajado_vac * (float)($this->dias_t);
    $this->vacaciones = $dos * $this->sdi;

    $this->prima_vacacional = $this->vacaciones * 0.25;
    $finiquito = $this->aguinaldo  + $this->vacaciones + $this->prima_vacacional;
    return ($finiquito);
  }

    public function calculo_Dias(){
    $dia;
    if($this->rango === 'dias'){
      $dia = $this->dias_t;
    }else if ($this->rango === 'meses') {
       $dia_meses = floor($this->dias_t * 30.4);
      $dia = $dia_meses;
    }else if ($this->rango === 'anios') {
      $dia_anios = floor($this->dias_t * 365);
      $dia = $dia_anios;
    }
    // console.log(dia,'idid');
    return $dia;
  }

  public function DiasVac($d){
     $tabla_uno; $tabla_dos;$position;$i;
     $this->aniosLab;
    for ($i=0; $i < count($this->aniosLab); $i++) {
      if (((float)($d)/365) >= $this->aniosLab[$i] && (($this)($d)/365) < $this->aniosLab[$i+1]) {
        $position = $i;
      }
      else if (((float)($d)/365) >= $this->aniosLab[count($this->aniosLab) - 1]) {
        $position = count($this->aniosLab) - 1;
      }
    }
    $tabla_dos = $this->diasVac[$position];
    // console.log(position,'pos');
    // console.log(tabla_dos);
    return $tabla_dos;
  }

  public function calculateSalary($p,$in,$dl){
    // return $p;
    $tabla;$cuota;$porcentaje;$position;$subsidioIng;$subsidioCan;$i;
    // this.factorintegracion();
    $tabla = $this->getTable($p);
    $cuota = $this->getCuota($p);
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
    $this->limite = $tabla[$position];
    $this->excedente = (float)($in) - $this->limite;
    $this->porcentaje = $porcentaje[$position];
    $this->cuota = $cuota[$position];
    $this->marginal = $this->excedente * ($this->porcentaje/100);
    $isr = $this->cuota + $this->marginal;
    $sbc = $this->calcSBC($p,$dl,$in);
    $imss = $this->calcIMSS($sbc,$dl);
    // $imss = 0;

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
      $this->final = (((float)($in) - $imss - $isr + $this->subsidio));
      return
      [
        'neto' => $this->final,
        'isr' => $isr,
        'imss' => $imss,
        'sbc' => $sbc,
      ];

    // this.data.finiquito = this.finiquito();
    // this.data.couta_patronal = this.calcCuotaP(this.data.sbc);
    // this.data.costo_total_trabajador = parseFloat(this.data.final) + parseFloat(this.data.couta_patronal) + parseFloat(this.data.finiquito);
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
              $i = (float($i) - .01);
            }
            $this->final = (float)($i);
            $i = $this->temporal;

        }
        else {
          $result = $this->calculateSalary($p,$i,$dl);
          // $this->couta_patronal = this.calcCuotaP($this->sbc);

        }
        $this->couta_patronal = $this->calcCuotaP($this->sbc);
        $this->costo_total_trabajador = (float)($this->final) + (float)($this->couta_patronal);
        // this.data.finiquito = this.finiquito();
        return $result;
  }


  // public function calcSBC($value) {
  //  $sbc;
  //   if ($value === "Semanal") {
  //     this.data.dias = 7,
  //     this.data.sdi = parseFloat(this.data.inicial)/this.data.dias;
  //     sbc = this.data.sdi * this.data.factor;
  //   }
  //   else if ($value === "Quincenal") {
  //     this.data.dias = 15,
  //     this.data.sdi = parseFloat(this.data.inicial)/this.data.dias;
  //     sbc = this.data.sdi * this.data.factor;
  //   }
  //   else if ($value === "Mensual") {
  //     this.data.dias = 30,
  //     this.data.sdi = parseFloat(this.data.inicial)/this.data.dias;
  //     sbc = this.data.sdi * this.data.factor;
  //   }
  //   if (sbc > 2112.25) {
  //     sbc = 2112.25;
  //   }
  //   return sbc.toFixed(2);
  // },
  //
  public function cargar(Request $request)
  {
    try {
      // return var_dump($request->import_file);
      if (!$request->ajax()) return redirect('/');
      // foreach ($request->file('files') as $kes => $vf) {
      for ($i=0; $i < $request->tamanio ; $i++) {
        // code...
      // if($request->hasFile('import_file')){
        // ini_set('max_execution_time', 300);
        // $collection = Excel::import(new XmlImport(), request()->file('import_file'));

        $xml = simplexml_load_file($request->file('files'.$i));
        $ns = $xml->getNamespaces(true);
        $xml->registerXPathNamespace('c', $ns['cfdi']);
        $xml->registerXPathNamespace('t', $ns['tfd']);
        // $ns = $xml->getNamespaces(true);
        // $xml->registerXPathNamespace('c', $ns['cfdi']);
        // if($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//nomina12:Percepciones//nomina12:Percepcion')){
        // foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//nomina12:Percepciones//nomina12:Percepcion') as $cfdiComprobante){
        //  $arreglo [] = $cfdiComprobante;
        // }
        //
        // }
        $tc_ = null;

        $factura_nomina = new FacturaNomina();

        foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
          $factura_nomina->UUID = $tfd['UUID'];
          $factura_nomina->FechaTimbrado = $tfd['FechaTimbrado'];
        }
        foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
          $factura_nomina->Moneda = $cfdiComprobante['Moneda'];
          $factura_nomina->TipoDeComprobante = $cfdiComprobante['TipoDeComprobante'];
          $factura_nomina->MetodoPago = $cfdiComprobante['MetodoPago'];
          $factura_nomina->Serie = $cfdiComprobante['Serie'];
          $factura_nomina->Folio = $cfdiComprobante['Folio'];
          $factura_nomina->LugarExpedicion = $cfdiComprobante['LugarExpedicion'];
          $factura_nomina->SubTotal = $cfdiComprobante['SubTotal'];
          $factura_nomina->Descuento = $cfdiComprobante['Descuento'];
          $factura_nomina->Total = $cfdiComprobante['Total'];
          $tc_ =$cfdiComprobante['TipoDeComprobante'];
        }
        $factura_nomina->save();
        $valores = $tc_[0];
        $v = explode(':',$valores);
        // $a = ($v === ['N']);

        if ($v === ['N']) {
        $nomina_c = new NominaC();
        foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//nomina12:Nomina') as $nc){
          $nomina_c->factura_nomina_id = $factura_nomina->id;
          $nomina_c->Version = $nc['Version'];
          $nomina_c->TipoNomina = $nc['TipoNomina'];
          $nomina_c->FechaPago = $nc['FechaPago'];
          $nomina_c->FechaInicialPago = $nc['FechaInicialPago'];
          $nomina_c->FechaFinalPago = $nc['FechaFinalPago'];
          $nomina_c->NumDiasPagados = $nc['NumDiasPagados'];
          $nomina_c->TotalPercepciones = $nc['TotalPercepciones'];
          $nomina_c->TotalDeducciones = $nc['TotalDeducciones'];
          $nomina_c->TotalOtrosPagos = $nc['TotalOtrosPagos'];
        }
        $nomina_c->save();
      }


        $receptor = new Receptor();
        if ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor')) {
          // code...
        foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $rep){
          $receptor->factura_nomina_id = $factura_nomina->id;
          $receptor->Rfc = $rep['Rfc'];
          $receptor->Nombre = $rep['Nombre'];
          $receptor->UsoCFDI = $rep['UsoCFDI'];
        }
      }

      if ($v === ['N']) {
        foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//nomina12:Nomina//nomina12:Receptor') as $repc){
          $receptor->Curp = $repc['Curp'];
          $receptor->NumSeguridadSocial = $repc['NumSeguridadSocial'];
          $receptor->FechaInicioRelLaboral = $repc['FechaInicioRelLaboral'];
          $receptor->Antiguedad = $repc['Antigüedad'];
          $receptor->TipoContrato = $repc['TipoContrato'];
          $receptor->Sindicalizado = $repc['Sindicalizado'];
          $receptor->TipoJornada = $repc['TipoJornada'];
          $receptor->TipoRegimen = $repc['TipoRegimen'];
          $receptor->NumEmpleado = $repc['NumEmpleado'];
          $receptor->Departamento = $repc['Departamento'];
          $receptor->Puesto = $repc['Puesto'];
          $receptor->PeriodicidadPago = $repc['PeriodicidadPago'];
          $receptor->SalarioBaseCotApor = $repc['SalarioBaseCotApor'];
          $receptor->SalarioDiarioIntegrado = $repc['SalarioDiarioIntegrado'];
          $receptor->ClaveEntFed = $repc['ClaveEntFed'];
        }
      }
        $receptor->save();

        $concepto = new Concepto();
        foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $c){
          $concepto->factura_nomina_id = $factura_nomina->id;
          $concepto->ClaveProdServ = $c['ClaveProdServ'];
          $concepto->Cantidad = $c['Cantidad'];
          $concepto->ClaveUnidad = $c['ClaveUnidad'];
          $concepto->Descripcion = $c['Descripcion'];
          $concepto->ValorUnitario = $c['ValorUnitario'];
          $concepto->Importe = $c['Importe'];
          $concepto->Descuento = $c['Descuento'];
        }
        $concepto->save();

        if ($v === ['N']) {
        foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//nomina12:Percepciones//nomina12:Percepcion') as $p){
          $percepciones = new Percepciones();
          $percepciones->factura_nomina_id = $factura_nomina->id;
          $percepciones->TipoPercepcion = $p['TipoPercepcion'];
          $percepciones->Clave = $p['Clave'];
          $percepciones->Concepto = $p['Concepto'];
          $percepciones->ImporteGravado = $p['ImporteGravado'];
          $percepciones->ImporteExento = $p['ImporteExento'];
          $percepciones->save();
        }
      }

      if ($v === ['N']) {
        foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//nomina12:Deducciones//nomina12:Deduccion') as $d){
          $deduccion = new Deducciones();
          $deduccion->factura_nomina_id = $factura_nomina->id;
          $deduccion->TipoDeduccion = $d['TipoDeduccion'];
          $deduccion->Clave = $d['Clave'];
          $deduccion->Concepto = $d['Concepto'];
          $deduccion->Importe = $d['Importe'];
          $deduccion->save();
        }
      }

      if ($v === ['N']) {
        foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//nomina12:OtrosPagos//nomina12:OtroPago') as $op){
          $otrop = new OtroPago();
          $otrop->factura_nomina_id = $factura_nomina->id;
          $otrop->TipoOtroPago = $op['TipoOtroPago'];
          $otrop->Clave = $op['Clave'];
          $otrop->Concepto = $op['Concepto'];
          $otrop->Importe = $op['Importe'];
          $otrop->save();
        }
      }




        // }


      }
        return response()->json($request);

    } catch (\Exception $e) {
      Utilidades::errors($e);
      return response()->json($e);
    }
  }


    public function calcular_periodo($value)
    {
      $inicio = $value['inicio'];
      $fin = $value['fin'];

      $fechaInicio=strtotime($inicio);
      $fechaFin=strtotime($fin);
      // for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
      //  $fechas [] = date("d-m-Y", $i);
      // }
      for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
        $fechas_siete [] = date("m", $i);

      }

      $c = array_count_values($fechas_siete);
      $b = max($c);
      return array_search($b,$c);

    }

    public function getInicioFinSemanaQ($data)
    {
      $meses = [' Q E',' Q F',' Q MZ',' Q AB',' Q MY',
      ' Q JN',' Q JL',' Q AG',' Q SP',' Q OC',' Q NV',' Q DC'];
      $numero = ['1 Q ','2 Q '];
      $meses_arreglo = ['E','F','MZ','AB','MY','JN','JL','AG','SP','OC','NV.','DC'];
      $meses_arreglo_numero = ['01','02','03','04','05','06','07','08','09','10','11','12'];

      $numero_uno = str_replace($meses,"",$data);
      $mes_uno = str_replace($numero,"",$data);

      $meses_busqueda = array_search($mes_uno,$meses_arreglo);
      $ret = null;

      if($mes_uno === 'E' && $numero_uno == 1){
        $ret ['inicio'] = (date('Y') - 1).'-12-26';
      }
      if($mes_uno != 'E' && $numero_uno == 1) {
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

  public function getReporte($da)
  {
    $id =$da;
    $year = date('Y');

    $a = $this->getInicioFinSemana(trim($id),$year);


    $fecha_inicio_semana = '2020-01-11';
    $movimiento = MovimientosN::whereBetween('fecha',[$fecha_inicio_semana,$a['fin']])
    ->select('nombre')
    ->groupBy('nombre')->get();
    $arreglo = [];

    $periodo = $this->calcular_periodo($a);


    foreach ($movimiento as $key => $value) {

      $m = MovimientosN::whereBetween('fecha',[$fecha_inicio_semana,$a['fin']])
      ->where('nombre','LIKE','%'.$value->nombre.'%')
      // ->where('nombre',$value->nombre)
      ->orderBy('fecha','DESC')
      ->first();
      $arreglo [] = $m;
    }

    $arreglo_nom = [];
    foreach ($arreglo as $ka => $v) {
      // if ($v->movimiento === 'Baja' && $v->fecha > $a['inicio']) {
      //   $arreglo_nom [] = $v;
      // }
      // if ($v->movimiento === 'Reingreso' && $v->fecha > $a['inicio']) {
      //   $arreglo_nom [] = $v;
      // }
      if ($v->movimiento === 'Alta' || $v->movimiento === 'M/S' || $v->movimiento === 'Reingreso') {
        $arreglo_nom [] = $v;
      }

    }

    return response()->json($arreglo_nom);


    $arreglo_f = [];
    foreach ($arreglo_nom as $k_m => $mov) {
      //lo que registra RH
      $semana_n = \App\SemanaN::where('semana',$id)
      ->orWhere('semana',$da)
      ->where('nss',$mov->nss)->first();

      $infonavi_u = \App\Infonavit::where('nss','LIKE','%'.$mov->nss.'%')
      ->whereMonth('fecha_f',$periodo)
      ->first();
      $infonavi_d = \App\Infonavit::where('nss','LIKE','%'.$mov->nss.'%')
        ->whereMonth('fecha_i',$periodo)
        ->first();

      $i_final = $infonavi_u == null ? $infonavi_d : $infonavi_u;


      $nomina_c = NominaC::
      join('factura_nomina AS fn','fn.id','nomina_c.factura_nomina_id')
      ->join('receptor AS r','r.factura_nomina_id','fn.id')
      ->select('nomina_c.*','r.nombre','fn.Total AS to','fn.Descuento','r.SalarioBaseCotApor','r.SalarioDiarioIntegrado',
      'fn.SubTotal AS st','fn.Descuento AS des','fn.Total AS tt')
      ->where('NumSeguridadSocial',$mov->nss)
      ->where('r.TipoContrato','01')
      ->where('FechaInicialPago','>=',$a['inicio'])
      ->where('FechaFinalPago','<=',$a['fin'])->first();



      $d = '';
      $p = '';
      $suma_d = 0;
      $suma_p = 0;
      if (isset($nomina_c) == true) {
      $d = Deducciones::where('factura_nomina_id',$nomina_c->factura_nomina_id)->get();
      $sumad = Deducciones::select(DB::raw("SUM(importe) AS sd"))->where('factura_nomina_id',$nomina_c->factura_nomina_id)->first();
      $p = Percepciones::where('factura_nomina_id',$nomina_c->factura_nomina_id)->get();

      $suma_d = $sumad->sd;
    }
      $salario = $mov->salario / 1.0452;
      $pago_ordinario = $salario * (float)$semana_n['dl'];

      if (isset($semana_n) == true || isset($nomina_c) == true ) {
        if (isset($semana_n) == true && (float)$semana_n->dl != 0) {
        $arreglo_f [] = [
          'movimiento' => $mov,
          'semana' => $semana_n,
          'estado' => isset($nomina_c) == true ? 1 : 0,
          'factura' => isset($nomina_c) == true ? $nomina_c : $this->calcular((float)$semana_n->dl,"Quincenal",$pago_ordinario),
          'de' => $d,
          'pe' => $p,
          'salario' => $salario,
          'po' =>$pago_ordinario,
          's' => $mov->salario,
          'c' =>  $this->calcular((float)$semana_n->dl,"Quincenal",$pago_ordinario),
          'infonavit' => $i_final,
          'p' => $periodo,
          // 'tt' => $total_percepciones - $total_deducciones,
        ];
      }
    }
    }


    // $nomina_c = NominaC::
    // join('factura_nomina AS fn','fn.id','nomina_c.factura_nomina_id')
    // ->join('receptor AS r','r.factura_nomina_id','fn.id')
    // ->select('nomina_c.*','r.nombre')
    // ->where('FechaInicialPago','>=',$a['inicio'])
    // ->where('FechaFinalPago','<=',$a['fin'])->get();

    return response()->json($arreglo_f);

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

  public function generar($id)
  {
    ini_set('max_execution_time', 3000);
    return Excel::download(new ReporteSemanaExport($id), 'ReporteSemanal'.$id.'.xlsx');
  }

  public function generarq($id)
  {
    // $valores = explode('&',$id);
    ini_set('max_execution_time', 3000);
    return Excel::download(new ReporteQuincenalExport($id), 'ReporteQuicena'.$id.'.xlsx');
  }




}
