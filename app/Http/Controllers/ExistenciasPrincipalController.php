<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use \App\Http\Helpers\Utilidades;
use Illuminate\Validation\Rule;


class ExistenciasPrincipalController extends Controller
{
  public function index(Request $request)
  {
    $articulo = DB::table('articulos')
      ->select(
        'articulos.*',
        'p.precio_unitario as precios',
        'p.precio_unitario as punit',
        DB::raw('ROUND(existencias.cantidad,0) as cantidad'),
        DB::raw('ROUND(existencias.cantidad * p.precio_unitario,2) AS Precio')
      )
      ->leftJoin('existencias', 'existencias.articulo_id', '=', 'articulos.id')
      ->leftJoin('lote_almacen as la', 'la.lote_id', '=', 'existencias.id_lote')
      ->leftJoin('precios AS p', 'p.lote_id', '=', 'la.id')
      ->where('existencias.articulo_id', '>', 0)
      ->distinct()->get();
    return response()->json($articulo);
  }

  public function Existencia($id)
  {
    $dts = explode("&", $id);
    if (count($dts) != 2)
      return response()->json(["status" => false, "mensaje" => "Datos incompletos"]);
    $query_alm = "";
    $comparer_alm = "";
    $query_cat = "";
    $comparer_cat = "";
    if ($dts[0] == 0)
    {
      // sin almacen
      $comparer_alm = "!=";
      $query_alm = -1;
      $comparer_cat = "=";
      $query_cat = $dts[1];
    }
    else
    {
      // sin categoria
      $comparer_alm = "=";
      $query_alm = $dts[0];
      $comparer_cat = "!=";
      $query_cat = -1;
    }

    $articulo = DB::table('articulos')
      ->select(
        'articulos.*',
        'p.precio_unitario as precios',
        'p.precio_unitario as punit',
        "a.nombre as alm_nombre",
        "c.id as c_id",
        "c.nombre as c_nombre",
        DB::raw('ROUND(existencias.cantidad,0) as cantidad'),
        DB::raw('ROUND(existencias.cantidad * p.precio_unitario,2) AS Precio')
      )
      ->leftJoin('existencias', 'existencias.articulo_id', '=', 'articulos.id')
      ->leftJoin('lote_almacen as la', 'la.lote_id', '=', 'existencias.id_lote')
      ->join("almacenes as a", "a.id", "la.almacene_id")
      ->leftJoin('precios AS p', 'p.lote_id', '=', 'la.id')
      ->join("grupos as g", "g.id", "articulos.grupo_id")
      ->join("categorias as c", "c.id", "g.categoria_id")
      ->where("la.almacene_id", $comparer_alm, $query_alm)
      ->where("c.id", $comparer_cat, $query_cat)
      ->distinct()->get();

    return response()->json(["status" => true, "articulos" => $articulo]);
  }

  public function buscarporcentrocostos($id)
  {
    $articulo = DB::table('articulos')
      ->select(
        'articulos.*',
        'p.precio_unitario as precios',
        'p.precio_unitario as punit',
        "a.nombre",
        DB::raw('ROUND(existencias.cantidad,0) as cantidad'),
        DB::raw('ROUND(existencias.cantidad * p.precio_unitario,2) AS Precio')
      )
      ->leftJoin('existencias', 'existencias.articulo_id', '=', 'articulos.id')
      ->leftJoin('lote_almacen as la', 'la.lote_id', '=', 'existencias.id_lote')
      ->join("almacenes as a", "a.id", "la.almacene_id")
      ->leftJoin('precios AS p', 'p.lote_id', '=', 'la.id')
      ->join("stocks as s", "s.id", "stocke_id")
      ->join("proyectos as pr", "pr.id", "s.proyecto_id")
      ->join("centro_costos as cc", "cc.proyecto_id", "pr.id")
      ->join("catalogo_centro_costos as ccc", "ccc.id", "cc.catalogo_centro_costos_id")
      ->where("ccc.id", $id)
      ->distinct()->get();
    return response()->json(["status" => true, "articulos" => $articulo]);
  }
}
