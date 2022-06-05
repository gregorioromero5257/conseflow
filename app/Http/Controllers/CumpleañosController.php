<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use App\Empleado;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade as PDF;
use \App\Http\Helpers\Utilidades;

class CumpleañosController extends Controller
{

    public function index()
    {
        $mescumpleaños = DB::table('empleados')
        ->whereraw('month(fech_nac)=month(NOW())')
        ->where('condicion', '=', '1')
        ->select(DB::raw('empleados.*, DAY(fech_nac) AS Edad'))
        ->orderBy('Edad')
        ->get();

        return response()->json($mescumpleaños->toArray());
    }

    public function pdf()
    {

      $fecha_nacimiento = DB::table('empleados')
      ->select(DB::raw('empleados.*, DAY(fech_nac) AS Edad'),'empleados.fech_nac',
      DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) AS completo"))
      ->whereraw('month(fech_nac)=month(NOW())')
      ->orderBy('Edad')
      ->where('condicion', '=', '1')->get();

        $pdf = PDF::loadView('pdf.cumpleaños', compact('fecha_nacimiento'));
        // $pdf->setPaper('A4', 'landscape');
        // return $pdf->download('cv-interno.pdf');
        return $pdf->stream();
    }
}
