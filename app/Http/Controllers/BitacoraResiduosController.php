<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;


class BitacoraResiduosController extends Controller
{
    //

    public function store(Request $request)
    {
      try {

        $bre = new \App\BitacoraResiduosEntrada();
        $bre->bitacora_residuos_entrada_general_id = $request->bitacora_residuos_entrada_general_id;
        $bre->fecha = $request->fecha;
        $bre->nombre = $request->nombre;
        $bre->tipo = $request->tipo;
        $bre->cantidad = $request->cantidad;
        $bre->unidad = $request->unidad;
        $bre->area_proceso = $request->area_proceso;
        $bre->fecha_salida_rme = $request->fecha_salida_rme;
        $bre->crebit = $request->crebit;
        $bre->fecha_dos = $request->fecha_dos;
        $bre->clave = $request->clave;
        $bre->no_autorizacion = $request->no_autorizacion;
        $bre->proveedor = $request->proveedor;
        $bre->num_manifiesto = $request->num_manifiesto;
        // $bre->localidad = $request->localidad;
        // $bre->responsable = $request->responsable;
        Utilidades::auditar($bre, $bre->id);
        $bre->save();

        return response()->json(['status' => true]);

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function update(Request $request)
    {
      try {
        $bre = \App\BitacoraResiduosEntrada::where('id',$request->id)->first();
        $bre->bitacora_residuos_entrada_general_id = $request->bitacora_residuos_entrada_general_id;
        $bre->fecha = $request->fecha;
        $bre->nombre = $request->nombre;
        $bre->tipo = $request->tipo;
        $bre->cantidad = $request->cantidad;
        $bre->unidad = $request->unidad;
        $bre->area_proceso = $request->area_proceso;
        $bre->fecha_salida_rme = $request->fecha_salida_rme;
        $bre->crebit = $request->crebit;
        $bre->fecha_dos = $request->fecha_dos;
        $bre->clave = $request->clave;
        $bre->no_autorizacion = $request->no_autorizacion;
        $bre->proveedor = $request->proveedor;
        $bre->num_manifiesto = $request->num_manifiesto;
        // $bre->localidad = $request->localidad;
        // $bre->responsable = $request->responsable;
        Utilidades::auditar($bre, $bre->id);
        $bre->save();

        return response()->json(['status' => true]);

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function get($id)
    {
      try {

        $db = DB::table('bitacora_residuos_entrada AS bre')
        ->select('bre.*','cbr.id AS cbr_id','cbr.residuo','cbr.tipo AS tipo_cbr')
        ->join('catalogo_bitacora_residuos AS cbr','cbr.id','bre.nombre')
        ->where('bre.bitacora_residuos_entrada_general_id',$id)
        ->get();

        foreach ($db as $key => $value) {
          $bre = DB::table('catalogo_bitacora_residuos AS cbr')->first();
        }

        return response()->json($db);

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function getGral()
    {
      try {

        $db = DB::table('bitacora_residuos_entrada_general AS bre')
        ->leftjoin('empleados AS e','e.id','bre.responsable')
        ->select('bre.*',
        DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS responsable_nombre"))
        ->where('bre.condicion','1')
        ->get();
        return response()->json($db);

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function descargar($id)
    {
      try {
        $gral = DB::table('bitacora_residuos_entrada_general AS bre')
        ->leftjoin('empleados AS e','e.id','bre.responsable')
        ->select('bre.*',
        DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS responsable_nombre"))
        ->where('bre.id',$id)->first();

        $bre = DB::table('bitacora_residuos_entrada')
        ->join('catalogo_bitacora_residuos AS cbr','cbr.id','bitacora_residuos_entrada.nombre')
        ->where('bitacora_residuos_entrada_general_id',$id)
        ->select('bitacora_residuos_entrada.*','cbr.residuo AS nr')->get();

        $arreglo = '';
        $pdf = PDF::loadView('pdf.bitacora', compact('gral','bre'));
        //  $pdf->setPaper('A4', 'landscape');
        // $pdf->setPaper("A4", "portrait");
        // $pdf->setPaper('letter', 'portrait');
        // $pdf->setOption('isPhpEnabled', true);
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('ledger', 'portrait');
        // return $pdf->download('cv-interno.pdf');
        return $pdf->stream();
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function storeGral(Request $request)
    {
      try {
        $breg = new \App\BitacoraResiduosEntradaGral();
        $breg->folio = $request->folio;
        $breg->localidad = $request->localidad;
        $breg->responsable = $request->responsable;
        Utilidades::auditar($breg, $breg->id);
        $breg->save();

        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function updateGral(Request $request)
    {
      try {
        $breg =  \App\BitacoraResiduosEntradaGral::where('id',$request->id)->first();
        $breg->folio = $request->folio;
        $breg->localidad = $request->localidad;
        $breg->responsable = $request->responsable;
        Utilidades::auditar($breg, $breg->id);
        $breg->save();

        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function eliminar($id)
    {
      $data = \App\BitacoraResiduosEntradaGral::where('id',$id)->first();
      $data->condicion = 0;
      Utilidades::auditar($data, $data->id);
      $data->save();

      return response()->json(['status' => true]);
    }
}
