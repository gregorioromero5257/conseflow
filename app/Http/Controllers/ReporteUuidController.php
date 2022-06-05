<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\FacturasEntradas;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\Storage;


class ReporteUuidController extends Controller
{
    //
    public function reporte(Request $request)
    {
      $reporte = DB::table ('facturas_entradas')
      ->leftJoin('entradas','entradas.id','=','facturas_entradas.entrada_id')
      ->leftJoin('ordenes_compras','ordenes_compras.id','entradas.orden_compra_id')
      ->select('facturas_entradas.*','ordenes_compras.folio as folio','entradas.fecha as fecha_entrada')
      ->get();

      return response()->json($reporte);

    }
}
