<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Almacene;
use Illuminate\Support\Facades\DB;;
use App\Existencia;
use App\Nivele;
use App\Stand;
use App\TipoUbicacion;
use App\Exports\ReporteAlmacenExport;
use Maatwebsite\Excel\Facades\Excel;
use \App\Http\Helpers\Utilidades;

class ReporteAlmacenController extends Controller
{

  public function Descargar(Request $request)
  {
    
    return Excel::download(new ReporteAlmacenExport(), "ReporteAlmacen - ".  date("d-m-Y") . " .xlsx");
  }
}