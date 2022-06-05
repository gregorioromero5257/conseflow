<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\CoutaImssEBAImport;
use App\Imports\CoutaImssEMAImport;
use App\Imports\SueldosSemanalImport;
use App\Imports\SueldosSemanalDosImport;
use App\Imports\SueldosQuincenaImport;
use App\Imports\SueldosQuincenaDosImport;
use App\Imports\GastosEmpresasImport;
use App\Imports\GastosESemanalImport;
use App\Imports\GastosEQuincenalImport;
use App\Exports\ReporteGastosExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RHExport;
use App\CoutaImssEBA;
use App\CoutaImssEMA;
use \App\Http\Helpers\Utilidades;




class SueldosImssController extends Controller
{
    public function upload(Request $request)
    {
      try {
        if (!$request->ajax()) return redirect('/');

        if($request->hasFile('import_file')){
          ini_set('max_execution_time', 300);

          if ($request->tipo === 'EBA') {
            $registros_ = CoutaImssEBA::where('meses',$request->periodo)->get();
            if (count($registros_) > 0 ) {
              return response()->json(array('status' => false));
            }else {
              $collection = Excel::import(new CoutaImssEBAImport($request->periodo), request()->file('import_file'));
              return response()->json($collection);
            }
          }
          if ($request->tipo === 'EMA') {
            $registros = CoutaImssEMA::where('mes',$request->periodo)->get();
            if (count($registros) > 0 ) {
              return response()->json(array('status' => false));
            }else {
              $collection = Excel::import(new CoutaImssEMAImport($request->periodo), request()->file('import_file'));
              return response()->json($collection);
            }
          }
        }
      } catch (\Exception $e) {
        Utilidades::errors($e);
        return response()->json($e);
      }
    }

    public function uploadsemana(Request $request)
    {
      try {
        if (!$request->ajax()) return redirect('/');

        if($request->hasFile('import_file')){
          ini_set('max_execution_time', 300);
          if ($request->numero == 1) {
            
            $collection = Excel::import(new SueldosSemanalDosImport($request->semanas,$request->empresa,$request->anio), request()->file('import_file'));
            return response()->json($collection);
          }elseif ($request->numero == 2) {
            // code...
            $collection = Excel::import(new SueldosSemanalImport($request->semanas,$request->empresa,$request->anio), request()->file('import_file'));
            return response()->json($collection);
          }
        }
      } catch (\Exception $e) {
        Utilidades::errors($e);
        return response()->json($e);
      }
    }

    public function uploadquincena(Request $request)
    {
      try {
        if (!$request->ajax()) return redirect('/');

        if($request->hasFile('import_file')){
          ini_set('max_execution_time', 300);
          if ($request->numero == 1) {
            // code...
            $collection = Excel::import(new SueldosQuincenaDosImport($request->semanas,$request->empresa,$request->anio), request()->file('import_file'));
            return response()->json($collection);
          }elseif ($request->numero == 2) {
            // code...
            $collection = Excel::import(new SueldosQuincenaImport($request->semanas,$request->empresa,$request->anio), request()->file('import_file'));
            return response()->json($collection);
          }
        }
      } catch (\Exception $e) {
        Utilidades::errors($e);
        return response()->json($e);
      }
    }

    public function uploadsemanagastose(Request $request)
    {
      try {
        if (!$request->ajax()) return redirect('/');

        if($request->hasFile('import_file')){
          ini_set('max_execution_time', 300);
              $collection = Excel::import(new GastosESemanalImport($request->semanas,$request->empresa), request()->file('import_file'));
              return response()->json($collection);
        }
      } catch (\Exception $e) {
        Utilidades::errors($e);
        return response()->json($e);
      }
    }

    public function uploadquincenagastose(Request $request)
    {
      try {
        if (!$request->ajax()) return redirect('/');

        if($request->hasFile('import_file')){
          ini_set('max_execution_time', 300);
              $collection = Excel::import(new GastosEQuincenalImport($request->semanas,$request->empresa), request()->file('import_file'));
              return response()->json($collection);
        }
      } catch (\Exception $e) {
        Utilidades::errors($e);
        return response()->json($e);
      }
    }


    public function uploadgastos(Request $request)
    {
      try {
        if (!$request->ajax()) return redirect('/');

        if($request->hasFile('import_file')){
          ini_set('max_execution_time', 300);
              $collection = Excel::import(new GastosEmpresasImport($request->empresa), request()->file('import_file'));
              return response()->json($collection);
        }
      } catch (\Exception $e) {
        Utilidades::errors($e);
        return response()->json($e);
      }
    }

    public function descargarReporteGastos($id)
    {
    ini_set('max_execution_time', 3000);
    return Excel::download(new ReporteGastosExport($id), 'ReporteGastosAdmin.xlsx');
    }
}
