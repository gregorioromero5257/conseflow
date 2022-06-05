<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Validator;
use App\PermisosEmpleados;
use App\Empleado;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade as PDF;

class PermisoEmpleadoPDFController extends Controller
{
    
    public function pdf($id)
  {
    $permisos = PermisosEmpleados::where('solicitud_permisos.id','=',$id)


    ->leftJoin('empleados AS area', 'area.id', '=', 'solicitud_permisos.solicita_empleado_id')
    ->leftJoin('puestos AS puesto', 'puesto.id', '=', 'area.puesto_id')
    

    ->leftjoin('empleados AS SolicitaId', 'SolicitaId.id','=', 'solicitud_permisos.solicita_empleado_id')
    ->leftjoin('empleados AS AutorizaId', 'AutorizaId.id','=', 'solicitud_permisos.autoriza_empleado_id')


       ->select('solicitud_permisos.*',
       'puesto.area as areadmin',
       'puesto.nombre as nombreemp',
       
        'fecha_solicitud',

        'tipo_salida',

        'fech_temp',

        'hora_salida',

        'hora_regreso',

        'goce',

        'motivo',



       DB::raw("CONCAT(AutorizaId.nombre ,' ',AutorizaId.ap_paterno,' ',AutorizaId.ap_materno) AS NombreAutoriza"),
       DB::raw("CONCAT(SolicitaId.nombre ,' ',SolicitaId.ap_paterno,' ',SolicitaId.ap_materno) AS NombreSolicita"))

    ->get()->first();
   
       
$fecha=$permisos->fecha_solicitud;
$array_fecha = explode('-',$fecha);
$anio =$array_fecha[0];
$mes = $array_fecha[1];
$dia = $array_fecha[2];
 $mesnombre ='';

 switch ($mes) {
   case '01': $mesnombre='Enero'; break;
   case '02': $mesnombre='Febrero'; break;
   case '03': $mesnombre='Marzo'; break;
   case '04': $mesnombre='Abril'; break;
   case '05': $mesnombre='Mayo'; break;
   case '06': $mesnombre='Junio'; break;
   case '07': $mesnombre='Julio'; break;
   case '08': $mesnombre='Agosto'; break;
   case '09': $mesnombre='Septiembre'; break;
   case '10': $mesnombre='Octubre'; break;
   case '11': $mesnombre='Noviembre'; break;
   case '12': $mesnombre='Diciembre'; break;


    }


    $anio1 ='';
    $mes1 = '';
    $dia1 = '';
    $anio2 ='';
    $mes2 = '';
    $dia2 = '';
    $horaregresofinal = '';
    $horasalidafinal = '';
    $array_fechaini= [];
$fechaini=$permisos->fecha_inicio;
if (!is_null($fechaini)) {
  $array_fechaini = explode('-',$fechaini);
$anio1 =$array_fechaini[0];
$mes1 = $array_fechaini[1];
$dia1 = $array_fechaini[2];

}
$array_fechafi =[];
$fechafi=$permisos->fecha_fin;
if (!is_null($fechafi)) {
$array_fechafi = explode('-',$fechafi);
$anio2 =$array_fechafi[0];
$mes2 = $array_fechafi[1];
$dia2 = $array_fechafi[2];
}

$congoce=$permisos->goce;


$tiposalida=$permisos->tipo_salida;





$horasalida = explode(':',$permisos->hora_salida);
$horaregreso = explode(':',$permisos->hora_regreso);
$tiempos = '';
$tiempor = '';
if ($horasalida[0] > 12 ) {
  $tiempos = 'pm';
}else{ $tiempos = 'am';}
if ($horaregreso[0] > 12 ) {
  $tiempor = 'pm';
}else{ $tiempor = 'am';}

if (!is_null($permisos->hora_salida)) {
$horasalidafinal = $horasalida[0].':'.$horasalida[1].' '.$tiempos;

}
if (!is_null($permisos->hora_regreso)) {
$horaregresofinal = $horaregreso[0].':'.$horaregreso[1].' '.$tiempor;

}

     


  






$pdf = PDF::loadView('pdf.permisosemp', compact('permisos','anio','dia','mesnombre','anio1','dia1','mes1','anio2','dia2','mes2','congoce','horasalidafinal','horaregresofinal','tiposalida'));
      //$pdf->setPaper('A4', 'landscape');
      // return $pdf->download('cv-interno.pdf');
      return $pdf->stream();
  }
}
