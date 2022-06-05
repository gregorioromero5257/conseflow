<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use Barryvdh\DomPDF\Facade as PDF;

class ResiduosUrbanosController extends Controller
{
    //
    public function get()
    {
     $data = DB::table('residuos_urbanos AS ru')
     ->join('empleados AS e','e.id','ru.empleado_entrega_id')
     ->select('ru.*',DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS entrega"))
     ->orderBy('ru.fecha_salida','DESC')
     ->get();

     return response()->json($data);
    }

    public function guardar(Request $request)
    {
      try {
        $fechas = '';
        foreach ($request->fechas as $key => $value) {
          $fechas .= $value.',';
        }

        $data = new \App\ResiduosUrbanos();
        $data->residuo = $request->residuo;
        $data->cantidad = $request->cantidad;
        $data->unidad = $request->unidad;
        $data->fecha_salida = $request->fecha_salida;
        $data->empleado_entrega_id = $request->empleado_entrega;
        $data->proveedor = $request->proveedor;
        $data->fechas = $fechas;
        $data->folio = $request->folio;
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
         $fechas = '';
         foreach ($request->fechas as $key => $value) {
           $fechas .= $value.',';
         }

         $data = \App\ResiduosUrbanos::where('id',$request->id)->first();
         $data->residuo = $request->residuo;
         $data->cantidad = $request->cantidad;
         $data->unidad = $request->unidad;
         $data->fecha_salida = $request->fecha_salida;
         $data->empleado_entrega_id = $request->empleado_entrega;
         $data->proveedor = $request->proveedor;
         $data->fechas = $fechas;
         $data->folio = $request->folio;
         Utilidades::auditar($data, $data->id);
         $data->save();

         return response()->json(['status' => true]);
       } catch (\Throwable $e) {
         Utilidades::errors($e);
       }
     }

     public function descargar()
     {
       // $arreglo = '';
       $data = DB::table('residuos_urbanos AS ru')
       ->join('empleados AS e','e.id','ru.empleado_entrega_id')
       ->select('ru.*',DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS entrega"))
       ->orderBy('ru.fecha_salida','ASC')
       ->get();
       // dd($arreglo);
       $pdf = PDF::loadView('pdf.bitacoraurbanos', compact('data'));
       //  $pdf->setPaper('A4', 'landscape');
         $pdf->setOption('isPhpEnabled', true);
       // $pdf->setPaper("A4", "portrait");
       $pdf->setPaper('letter', 'portrait');
       // return $pdf->download('cv-interno.pdf');
       return $pdf->stream();
       // code...
     }

}
