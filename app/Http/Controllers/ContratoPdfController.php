<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Contrato;
use App\ContratoDBP;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade as PDF;
use \App\Http\Helpers\Utilidades;
use DateTime;

class ContratoPdfController extends Controller
{
    //

    public function pdf($id)
    {
        $contratopdf = Contrato::where('contratos.id','=',$id)
        ->leftJoin('empleados AS EE', 'EE.id', '=', 'contratos.empleado_id')
        ->leftJoin('puestos AS puesto', 'puesto.id', '=', 'EE.puesto_id')
        ->leftJoin('proyectos', 'proyectos.id','=','contratos.proyecto_id')
       ->leftJoin('estados_civiles AS edo_civil', 'edo_civil.id', '=', 'EE.edo_civil_id')
       ->leftJoin('sueldos AS S','S.contrato_id','=','contratos.id')
       ->leftJoin('direcciones_empleados AS DR','DR.empleado_id','=','EE.id')
       ->leftJoin('empresas','empresas.id','=','contratos.empresa_id')
       ->join('tipo_ubicacion AS tu','tu.id','contratos.tipo_ubicacion_id')
       ->leftJoin('tipo_contratos AS tc','tc.id','=','contratos.tipo_contrato_id')
       ->select('contratos.*','tu.nombre AS ubicacion_contrato','contratos.id as conid','EE.fech_nac as ED','EE.rfc as erfc','EE.curp as ecur','puesto.nombre as areadmin','proyectos.nombre_corto as proyn',
       'edo_civil.nombre as civil','S.sueldo_diario_integral','DR.calle','DR.codigo_postal','DR.colonia','DR.codigo_postal','DR.localidad','DR.numero_exterior',
       'DR.entidad_federativa','empresas.nombre as emp','empresas.rfc as rfcem','empresas.direccion as empnomb','empresas.representante',
       'tc.nombre as tipoconnombre',
        DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS entrega"))
         ->where('contratos.id','=',$id)
        ->first();
        $numero = $contratopdf->sueldo_diario_integral;
        // $cambio = Utilidades::valorEnLetras($contratopdf->sueldo_diario_integral);
        $folempresa = $contratopdf->emp;
        $folioempresa = substr($folempresa,0,4);
        $idtipocontrato = $contratopdf->conid;
        $foltipocontrato = $contratopdf->tipoconnombre;
        $folioobradet = substr($foltipocontrato,0,1);
        $folioobradetx = substr($foltipocontrato,5,1);
        $foliotipocontrato = $folioobradet.$folioobradetx;
        $foltipocontratoa = $contratopdf->tipoconnombre;
        $foliotiempoin = substr($foltipocontratoa,0,1);
        $foliotiempoinx = substr($foltipocontratoa,7,1);
        $foliotipocontratox = $foliotiempoin.$foliotiempoinx;
        $foltipocontratob = $contratopdf->tipoconnombre;
        $foliotiempdet= substr($foltipocontratob,0,1);
        $foliotiempodetx = substr($foltipocontratob,7,1);
        $foliotipocontratoy =  $foliotiempdet.$foliotiempodetx;
        $fecha_empleado_nacimiento=$contratopdf->ED;
        $anio=date("Y");
        $anio_nacimiento=substr($fecha_empleado_nacimiento,0,4);

        $fecha_nacimiento = new DateTime($contratopdf->ED);
        $hoy = new DateTime();
        $edad = $hoy->diff($fecha_nacimiento)->y;

        $fecha_ingreso=$contratopdf->fecha_ingreso;
        $fecha_fin=$contratopdf->fecha_fin;
        $today = date("j, m");
        $t_y=date("Y");
        $fecha_final_ingreso='';
        $dia_hoy = substr($today,0,2);
        $mes_hoy = substr($today,4,2);
        $año_hoy = substr($t_y,0,4);
        $dia_ingreso = substr($fecha_ingreso,8,2);
        $dia_fin = substr($fecha_fin,8,2);
        $mes_ingreso=substr($fecha_ingreso,5,2);
        $mes_fin=substr($fecha_fin,5,2);
        $anio_ingreso=substr($fecha_ingreso,0,4);
        $anio_fin=substr($fecha_fin,0,4);
        $mes_inicio_letra = $this->meses($mes_ingreso);
        $mes_fin_letra = $this->meses($mes_fin);
        $fecha_final_ingreso= $this->dias($dia_ingreso).' días del mes de '.$this->meses($mes_ingreso).' del '.substr($fecha_ingreso,0,4);
        $fecha_final_temino= substr($fecha_fin,8,2).' días de '.$this->meses($mes_fin).' del '.\substr($fecha_fin,0,4);
        $fecha_hoy=substr($dia_hoy,0,2) .' de '.$this->meses($mes_hoy).' del '.substr($año_hoy,0,4);
        $sueldo_ = DB::table('sueldos')->latest('fecha_act')->where('contrato_id',$id)->first();

        $sueldo_actual = $sueldo_->sueldo_diario_integral;
       $cambio = Utilidades::valorEnLetras($sueldo_actual);

       // Obtener testigos
       $testigos = DB::table("testigos_contratos as tc")
        ->join("empleados as e", "e.id", "tc.empleado_id")
        ->where("tc.contrato_id", $id)
        ->select(
          "tc.*",
          DB::raw("concat_ws(' ',e.nombre,e.ap_paterno,e.ap_materno) as nombre_testigo")
        )->get()->toArray();
        $n_testigos=count($testigos);
        // ->where('contrato_id',$id)->latest('fecha_act')->first();


        $pdf = PDF::loadView('pdf.contrato', compact('sueldo_actual','contratopdf','edad','fecha_final_ingreso','fecha_final_temino','fecha_hoy','numero','dia_ingreso','dia_fin','mes_inicio_letra','mes_fin_letra','anio_ingreso','anio_fin',
         'cambio','folioempresa','foliotipocontrato','foliotipocontratox','foliotipocontratoy',"testigos","n_testigos"));
        return $pdf->stream("CONTRATO CONSERFLOW - ".$contratopdf->entrega.'.pdf');
    }

    public function pdfCSCT($id)
  {
      $contratopdf = ContratoDBP::where('contratos.id', '=', $id)
          ->leftJoin('empleados AS EE', 'EE.id', '=', 'contratos.empleado_id')
          ->leftJoin('puestos AS puesto', 'puesto.id', '=', 'EE.puesto_id')
          ->leftJoin('proyectos', 'proyectos.id', '=', 'contratos.proyecto_id')
          ->leftJoin('estados_civiles AS edo_civil', 'edo_civil.id', '=', 'EE.edo_civil_id')
          ->leftJoin('sueldos AS S', 'S.contrato_id', '=', 'contratos.id')
          ->leftJoin('direcciones_empleados AS DR', 'DR.empleado_id', '=', 'EE.id')
          ->leftJoin('empresas', 'empresas.id', '=', 'contratos.empresa_id')
          ->leftJoin('tipo_contratos AS tc', 'tc.id', '=', 'contratos.tipo_contrato_id')
          ->select(
              'contratos.*',
              'contratos.id as conid',
              'EE.fech_nac as ED',
              'EE.rfc as erfc',
              'EE.curp as ecur',
              'puesto.nombre as areadmin',
              'proyectos.nombre_corto as proyn',
              'edo_civil.nombre as civil',
              'S.sueldo_diario_integral',
              'DR.calle',
              'DR.codigo_postal',
              'DR.colonia',
              'DR.codigo_postal',
              'DR.localidad',
              'DR.numero_exterior',
              'DR.entidad_federativa',
              'empresas.nombre as emp',
              'empresas.rfc as rfcem',
              'empresas.direccion as empnomb',
              'empresas.representante',
              'tc.nombre as tipoconnombre',
              DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS entrega")
          )
          ->where('contratos.id', '=', $id)
          ->first();

      $numero = $contratopdf->sueldo_diario_integral;
      // $cambio = Utilidades::valorEnLetras($contratopdf->sueldo_diario_integral);
      $folempresa = $contratopdf->emp;
      $folioempresa = substr($folempresa, 0, 4);
      $idtipocontrato = $contratopdf->conid;
      $foltipocontrato = $contratopdf->tipoconnombre;
      $folioobradet = substr($foltipocontrato, 0, 1);
      $folioobradetx = substr($foltipocontrato, 5, 1);
      $foliotipocontrato = $folioobradet . $folioobradetx;
      $foltipocontratoa = $contratopdf->tipoconnombre;
      $foliotiempoin = substr($foltipocontratoa, 0, 1);
      $foliotiempoinx = substr($foltipocontratoa, 7, 1);
      $foliotipocontratox = $foliotiempoin . $foliotiempoinx;
      $foltipocontratob = $contratopdf->tipoconnombre;
      $foliotiempdet = substr($foltipocontratob, 0, 1);
      $foliotiempodetx = substr($foltipocontratob, 7, 1);
      $foliotipocontratoy =  $foliotiempdet . $foliotiempodetx;
      $fecha_empleado_nacimiento = $contratopdf->ED;
      $anio = date("Y");
      $anio_nacimiento = substr($fecha_empleado_nacimiento, 0, 4);

      $fecha_nacimiento = new DateTime($contratopdf->ED);
      $hoy = new DateTime();
      $edad = $hoy->diff($fecha_nacimiento)->y;

      // $edad = $anio - $anio_nacimiento;
      $fecha_ingreso = $contratopdf->fecha_ingreso;
      $fecha_fin = $contratopdf->fecha_fin;
      $today = date("j, m");
      $t_y = date("Y");
      $fecha_final_ingreso = '';
      $dia_hoy = substr($today, 0, 2);
      $mes_hoy = substr($today, 4, 2);
      $año_hoy = substr($t_y, 0, 4);
      $dia_ingreso = substr($fecha_ingreso, 8, 2);
      $dia_fin = substr($fecha_fin, 8, 2);
      $mes_ingreso = substr($fecha_ingreso, 5, 2);
      $mes_fin = substr($fecha_fin, 5, 2);
      $anio_ingreso = substr($fecha_ingreso, 0, 4);
      $anio_fin = substr($fecha_fin, 0, 4);
      $mes_inicio_letra = $this->meses($mes_ingreso);
      $mes_fin_letra = $this->meses($mes_fin);
      $fecha_final_ingreso = $this->dias($dia_ingreso) . ' días del mes de ' . $this->meses($mes_ingreso) . ' del ' . substr($fecha_ingreso, 0, 4);
      $fecha_final_temino = substr($fecha_fin, 8, 2) . ' días de ' . $this->meses($mes_fin) . ' del ' . \substr($fecha_fin, 0, 4);
      $fecha_hoy = substr($dia_hoy, 0, 2) . ' de ' . $this->meses($mes_hoy) . ' del ' . substr($año_hoy, 0, 4);
      $sueldo_ = \App\SueldoDBP::latest('fecha_act')->where('contrato_id', $id)->first();

      $sueldo_actual = $sueldo_->sueldo_diario_integral;
      $cambio = Utilidades::valorEnLetras($sueldo_actual);

      // Obtener testigos
      $testigos = \App\TestigoContratoDBP::
          join("empleados as e", "e.id", "testigos_contratos.empleado_id")
          ->where("testigos_contratos.contrato_id", $id)
          ->select(
              "testigos_contratos.*",
              DB::raw("concat_ws(' ',e.nombre,e.ap_paterno,e.ap_materno) as nombre_testigo")
          )->get()->toArray();
      $n_testigos = count($testigos);

      $pdf = PDF::loadView('pdf.contratocsct', compact(
          'contratopdf',
          'edad',
          'sueldo_actual',
          'fecha_final_ingreso',
          'fecha_final_temino',
          'fecha_hoy',
          'numero',
          'dia_ingreso',
          'dia_fin',
          'mes_inicio_letra',
          'mes_fin_letra',
          'anio_ingreso',
          'anio_fin',
          'cambio',
          'folioempresa',
          'foliotipocontrato',
          'foliotipocontratox',
          'foliotipocontratoy',
          "testigos",
          "n_testigos"
      ));
      return $pdf->stream("CONTRATO CSCT -" . $contratopdf->entrega);
  }

    public function meses($mes){
$resultado='';
switch ($mes) {
    case '01': $resultado='Enero'; break;
    case '02': $resultado='Febrero'; break;
    case '03': $resultado='Marzo'; break;
    case '04': $resultado='Abril'; break;
    case '05': $resultado='Mayo'; break;
    case '06': $resultado='Junio'; break;
    case '07': $resultado='Julio'; break;
    case '08': $resultado='Agosto'; break;
    case '09': $resultado='Septiembre'; break;
    case '10': $resultado='Octubre'; break;
    case '11': $resultado='Noviembre'; break;
    case '12': $resultado='Diciembre'; break;

}
        return $resultado;
    }

    public function dias($dias){
        $rd='';
        switch($dias){
            case '01': $rd='primer'; break;
            case '02': $rd='dos'; break;
            case '03': $rd='tres'; break;
            case '04': $rd='cuatro'; break;
            case '05': $rd='cinco'; break;
            case '06': $rd='seis'; break;
            case '07': $rd='siete'; break;
            case '08': $rd='ocho'; break;
            case '09': $rd='nueve'; break;
            case '10': $rd='diez'; break;
            case '11': $rd='once'; break;
            case '12': $rd='doce'; break;
            case '13': $rd='trece'; break;
            case '14': $rd='catorce'; break;
            case '15': $rd='quince'; break;
            case '16': $rd='dieciséis'; break;
            case '17': $rd='diecisiete'; break;
            case '18': $rd='dieciocho'; break;
            case '19': $rd='diecinueve'; break;
            case '20': $rd='veinte'; break;
            case '21': $rd='veintiun'; break;
            case '22': $rd='veintidos'; break;
            case '23': $rd='veintitrés'; break;
            case '24': $rd='veinticuatro'; break;
            case '25': $rd='veinticinco'; break;
            case '26': $rd='veintiséis'; break;
            case '27': $rd='veintisiete'; break;
            case '28': $rd='veintiocho'; break;
            case '29': $rd='veintinueve'; break;
            case '30': $rd='treinta'; break;
            case '31': $rd='treinta y un'; break;
        }
        return $rd;
    }


}
