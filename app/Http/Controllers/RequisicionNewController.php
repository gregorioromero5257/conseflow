<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\DB;

class RequisicionNewController extends Controller
{
  //

  public function buscarArticulo(Request $request)
  {
    try {
      $valores = explode(' ',$request->nombre);

      $articulos = DB::table('articulos AS a')->where(function ($query) use ($valores) {
        foreach ($valores as $key => $value) {
          $prepociones = $this->prepociones($value);
          if ($prepociones == false) {
            if ($key == 0) {
              $query->where('descripcion','LIKE','%'.$value.'%');
            }else {
              $query->orWhere('descripcion','LIKE','%'.$value.'%');
              $query->orWhere('nombre','LIKE','%'.$value.'%');
            }
          }
        }
      })
      ->get();

      return response()->json($articulos);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return response()->json(['status' => false]);
    }
  }

  public function prepociones($data)
  {

    $palabras = ['DE', 'CON', 'PARA', 'A' ,'POR','EN','en', 'de', 'con', 'para', 'a','por'];

    $busqueda = array_search($data, $palabras);
    $resultado = is_numeric($busqueda) == true ? true : $busqueda;

    return $resultado;
  }
}
