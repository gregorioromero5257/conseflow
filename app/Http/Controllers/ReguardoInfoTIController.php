<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers\Utilidades;
use Barryvdh\DomPDF\Facade as PDF;


class ReguardoInfoTIController extends Controller
{
    //
    public function get()
    {
      $data = \App\ReguardoInfoTI::join('empleados AS e','e.id','ti_resguardo_informacion.responsable_i')
      ->join('empleados AS ee','ee.id','ti_resguardo_informacion.responsable_r')
      ->select('ti_resguardo_informacion.*',
      DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_ii"),
      DB::raw("CONCAT(ee.nombre,' ',ee.ap_paterno,' ',ee.ap_materno) AS empleado_ri"))
      ->get();

      return response()->json($data);
    }

    public function guardar(Request $request)
    {
      try {
        $data = new \App\ReguardoInfoTI();
        $data->responsable_i = $request->responsable_i;
        $data->ruta = $request->ruta;
        $data->tamanio = $request->tamanio;
        $data->fecha = $request->fecha;
        $data->ubicacion = $request->ubicacion;
        $data->responsable_r = $request->responsable_r;
        $data->observaciones = $request->observaciones;
        Utilidades::auditar($data, $data->id);
        $data->save();

        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function actualizar(Request $request)
    {
      try {
        $data = \App\ReguardoInfoTI::where('id',$request->id)->first();
        $data->responsable_i = $request->responsable_i;
        $data->ruta = $request->ruta;
        $data->tamanio = $request->tamanio;
        $data->fecha = $request->fecha;
        $data->ubicacion = $request->ubicacion;
        $data->responsable_r = $request->responsable_r;
        $data->observaciones = $request->observaciones;
        Utilidades::auditar($data, $data->id);
        $data->save();

        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function descargar()
    {
      try {

        $data = \App\ReguardoInfoTI::join('empleados AS e','e.id','ti_resguardo_informacion.responsable_i')
        ->join('empleados AS ee','ee.id','ti_resguardo_informacion.responsable_r')
        ->select('ti_resguardo_informacion.*',
        DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_ii"),
        DB::raw("CONCAT(ee.nombre,' ',ee.ap_paterno,' ',ee.ap_materno) AS empleado_ri"))
        ->get();


        $pdf = PDF::loadView('pdf.bitacorati', compact('data'));
        //  $pdf->setPaper('A4', 'landscape');
        // $pdf->setPaper("A4", "portrait");
        $pdf->setPaper('letter', 'portrait');
        // return $pdf->download('cv-interno.pdf');
        return $pdf->stream();

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

}
