<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers\Utilidades;
use Barryvdh\DomPDF\Facade as PDF;

class ProgramaTIController extends Controller
{
    //
    public function getInicial($id)
    {
      $data = \App\ProgramaTIPreventivo::where('empresa',$id)
      ->select('anio')
      ->groupBy('anio')
      ->get();

      return response()->json($data);
    }

    public function getDetalle($id)
    {
      $valores = explode('&',$id);
      $data = \App\ProgramaTIPreventivo::where('anio',$valores[0])
      ->where('empresa',$valores[1])
      ->get();
      $arreglo = [];
      foreach ($data as $key => $value) {
        $des = '';

        if ($value->tipo == 1) {
          $cdata = \App\TiComputo::
          where('id',$value->caiv)
          ->select(DB::raw("CONCAT(no_serie,' ',marca_modelo,' ',cpu,' ',ram,' ',almacenamiento,' ',tarjeta_video,' ',tarjeta_red,' ',observaciones,' ',mac) AS descripcion"))
          ->first();
          $des = $cdata->descripcion;

        }
        if ($value->tipo == 3) {
          $cdata = \App\TiImpresion::
          select(DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS descripcion"))
          ->where('id',$value->caiv)
          ->first();
          $des = $cdata->descripcion;
        }

        $arreglo [] = [
          'data' => $value,
          'tipo' => $des,
        ];
      }

      return response()->json($arreglo);
    }

    public function guardar(Request $request)
    {
      try {

        $data_new = new \App\ProgramaTIPreventivo();
        $data_new->tipo = $request->tipo;
        $data_new->caiv = $request->caiv;
        $data_new->marca = $request->marca;
        $data_new->modelo = $request->modelo;
        $data_new->num_serie = $request->num_serie;
        $data_new->mes = $request->mes;
        $data_new->anio = $request->anio;
        $data_new->empresa = $request->empresa;
        $data_new->save();

        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function actualizar(Request $request)
    {
      try {

        $data_new = \App\ProgramaTIPreventivo::where('id',$request->id)->first();
        $data_new->tipo = $request->tipo;
        $data_new->caiv = $request->caiv;
        $data_new->marca = $request->marca;
        $data_new->modelo = $request->modelo;
        $data_new->num_serie = $request->num_serie;
        $data_new->mes = $request->mes;
        $data_new->anio = $request->anio;
        $data_new->empresa = $request->empresa;
        $data_new->save();

        return response()->json(['status' => true]);

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function descargar($id)
    {
      try {
        $valores = explode('&',$id);

        $arreglo = \App\ProgramaTIPreventivo::where('anio',$valores[0])
        ->where('empresa',$valores[1])
        ->get();
        // $arreglo = [];
        // foreach ($data as $key => $value) {
        //   $des = '';
        //
        //   if ($value->tipo == 1) {
        //     $cdata = \App\TiComputo::
        //     where('id',$value->caiv)
        //     ->select(DB::raw("CONCAT(no_serie,' ',marca_modelo,' ',cpu,' ',ram,' ',almacenamiento,' ',tarjeta_video,' ',tarjeta_red,' ',observaciones,' ',mac) AS descripcion"))
        //     ->first();
        //     $des = $cdata->descripcion;
        //
        //   }
        //   if ($value->tipo == 3) {
        //     $cdata = \App\TiImpresion::
        //     select(DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS descripcion"))
        //     ->where('id',$value->caiv)
        //     ->first();
        //     $des = $cdata->descripcion;
        //   }
        //
        //   $arreglo [] = [
        //     'data' => $value,
        //     'tipo' => $des,
        //   ];
        // }

        if ($valores[1] == 1) {
          $pdf = PDF::loadView('pdf.programapticfw', compact('arreglo'));
        }elseif ($valores[1] == 2) {
          $pdf = PDF::loadView('pdf.programapticsct', compact('arreglo'));
        }
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A2', 'portrait');
        return $pdf->stream();

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function delete($id)
    {
      try {
        $data_new = \App\ProgramaTIPreventivo::where('id',$id)->delete();
        return response()->json(['status' => true]);
      } catch (\Exception $e) {
        Utilidades::errors($e);
      }
    }
}
