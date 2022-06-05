<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\SolicitudVacacional;
use App\Empleado;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade as PDF;

class VacacionesPdfController extends Controller
{
    public function pdf($id)
  {
    $vacaciones = SolicitudVacacional::where('solicitudes_vacacionales.id','=',$id)
    ->leftJoin('empleados AS area', 'area.id', '=', 'solicitudes_vacacionales.solicita_empleado_id')
    ->leftJoin('puestos AS puesto', 'puesto.id', '=', 'area.puesto_id')
    ->leftJoin('empleados AS autoriza', 'autoriza.id', '=', 'solicitudes_vacacionales.autoriza_empleado_id')
    ->leftJoin('empleados AS solicita', 'solicita.id', '=', 'solicitudes_vacacionales.solicita_empleado_id')
    ->select("solicitudes_vacacionales.*",
    'puesto.area as areadmin',
    'fecha_solicitud',
    'fecha_inicio',
    'fecha_fin',
    DB::raw("CONCAT(autoriza.nombre ,' ',autoriza.ap_paterno,' ',autoriza.ap_materno) AS e_autoriza"),
    DB::raw("CONCAT(solicita.nombre ,' ',solicita.ap_paterno,' ',solicita.ap_materno) AS e_solicita"))
    ->get()->first();

    $fecha=$vacaciones->fecha_solicitud;
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

    $fechaini=$vacaciones->fecha_inicio;
    $array_fechaini = explode('-',$fechaini);

    $anio1 =$array_fechaini[0];
    $mes1 = $array_fechaini[1];
    $dia1 = $array_fechaini[2];

    $fechafi=$vacaciones->fecha_fin;
    $array_fechafi = explode('-',$fechafi);

    $anio2 =$array_fechafi[0];
    $mes2 = $array_fechafi[1];
    $dia2 = $array_fechafi[2];
    $pdf = PDF::loadView('pdf.vacaciones', compact('vacaciones','anio','dia','mesnombre','anio1','mes1','dia1','anio2','mes2','dia2'));
    $pdf->setPaper('letter', 'portrait');
    return $pdf->stream();
  }
}
