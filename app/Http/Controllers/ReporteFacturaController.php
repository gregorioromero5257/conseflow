<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\Storage;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\DB;


class ReporteFacturaController extends Controller
{
    //
    public function reporteFacturas(Request $request)
    {
      $reporte = DB::table ('facturas_entradas')
      ->leftJoin('entradas','entradas.id','=','facturas_entradas.entrada_id')
      ->leftJoin('ordenes_compras','ordenes_compras.id','=','entradas.orden_compra_id')
      ->select('facturas_entradas.*','entradas.fecha as entradaFecha','ordenes_compras.folio as ordenCompra')
      ->get();

      return response()->json($reporte);
    }
}
