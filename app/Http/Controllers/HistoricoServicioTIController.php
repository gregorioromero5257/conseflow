<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use Barryvdh\DomPDF\Facade as PDF;


class HistoricoServicioTIController extends Controller
{
    //
    public function get()
    {
      $data = \App\HistoricoServicioTI::
      select('ti_historico_servicio.*')
      ->where('ti_historico_servicio.condicion','1')->get();

      return response()->json($data);
    }

    public function guardar(Request $request)
    {
      try {
        $data = new \App\HistoricoServicioTI();
        $data->tipo = $request->tipo;
        $data->usuario = $request->usuario;
        $data->nombre_usuario = $request->nombre_usuario;
        $data->problema_servicio = $request->problema_servicio;
        $data->fecha_reporte = $request->fecha_reporte;
        $data->solucion = $request->solucion;
        $data->fecha_solucion  = $request->fecha_solucion ;
        $data->reincidencia = $request->reincidencia;
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
        $data = \App\HistoricoServicioTI::where('id',$request->id)->first();
        $data->tipo = $request->tipo;
        $data->usuario = $request->usuario;
        $data->nombre_usuario = $request->nombre_usuario;
        $data->problema_servicio = $request->problema_servicio;
        $data->fecha_reporte = $request->fecha_reporte;
        $data->solucion = $request->solucion;
        $data->fecha_solucion  = $request->fecha_solucion ;
        $data->reincidencia = $request->reincidencia;
        Utilidades::auditar($data, $data->id);
        $data->save();

        return response()->json(['status' => true]);

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function delete($id)
    {
      $data = \App\HistoricoServicioTI::where('id',$id)->first();
      $data->condicion = 0;
      $data->save();

      return response()->json(['status' => true]);
    }

    public function descargar()
    {
     try {
	//dd('hh');
	set_time_limit(200);
	ini_set('memory_limit', '400M');
        $data = DB::table('ti_historico_servicio')
        ->where('ti_historico_servicio.condicion','1')
	//->limit('600')
	->get();

	//
        $pdf = PDF::loadView('pdf.historicoservicios', compact('data'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A2', 'portrait');
        return $pdf->stream();

      } catch (\Throwable $e) {
        Utilidades::errors($e);
	return view('errors.204');
      }
    }

}
