<?php

namespace App\Http\Controllers\APIControllers;

use App\APIModels\Response;
use App\Empleado;
use App\Existencia;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Utilidades;
use App\LoteAlmacen;
use App\Movimiento;
use App\Partidas;
use App\Proyecto;
use App\Salida;
use App\SalidasResguardo;
use App\Stock;
use App\StockArticulo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalidasController extends Controller
{
    // Obtiene todos los proyectos activos de la DB
    public function ObtenerProyectos()
    {
        try
        {
            $proyectos = Proyecto::where("condicion", "=", 1)
                ->select("id", "nombre_corto")
                ->orderBy("nombre_corto")->get();
            return response()->json(new Response(200, "OK", $proyectos, true));
        }
        catch (Exception $e)
        {
            return response()->json(new Response(500, $e->getMessage(), null, false));
        }
    }

    // Obtiene los empleados que son supersivores ?
    public function ObtenerEmpleados()
    {
        try
        {
            $empleados = Empleado::where("condicion", 1)
                ->select("id", DB::raw("concat_ws(' ',nombre,ap_paterno, ap_materno) as nombre"))
                ->orderBy("nombre")
                ->get();
            return response()->json(new Response(200, "OK", $empleados, true));
        }
        catch (Exception $e)
        {
            return response()->json(new Response(500, $e->getMessage(), null, false));
        }
    }

    // Obtiene todos los artículos con el código de barras ingresado
    public function ObtenerArticulos($id)
    {
        try
        {
            // $articulos_a = DB::table("lote_almacen as la")
            //     ->join("lotes as l", "l.id", "la.lote_id")
            //     ->join("articulos as ar", "ar.id", "la.articulo_id")
            //     ->join("almacenes as a", "a.id", "la.almacene_id")
            //     ->join("stocks as ss", "ss.id", "la.stocke_id")
            //     ->join("proyectos as pr", "pr.id", "ss.proyecto_id")
            //     ->leftJoin("stands as s", "s.id", "la.stand_id")
            //     ->leftJoin("niveles as n", "n.id", "la.nivel_id")
            //     ->where("codigo_barras", $codigo)
            //     ->orderBy("codigo_barras")
            //     ->select(
            //         "ar.descripcion as a_desc",
            //         "la.id as la_id",
            //         "la.cantidad as cantidad",
            //         "la.articulo_id as a_id",
            //         "la.codigo_barras as codigo",
            //         "l.nombre as lote_nombre",
            //         "a.nombre as almacen",
            //         "n.nombre as nivel",
            //         "s.nombre as stand",
            //         "pr.nombre_corto as nombre_proyecto"
            //     )
            //     ->get()->toArray();
            //
            // $solo_numeros = str_replace('MCF ', '', $codigo);
            // $valores = explode(' ', $solo_numeros);
            // if (count($valores) == 1) // Código de proveedor
            //   {  $texto_busqueda = $codigo;
            //     $not_like = ' ';
            //   }
            // else{ // Código interno
            //     $texto_busqueda = ' ' . $valores[1] . ' ';
            //     $not_like = 'MCF '.$valores[1];
            //   }
            // $articulos_b = DB::table("lote_almacen as la")
            //     ->join("lotes as l", "l.id", "la.lote_id")
            //     ->join("articulos as ar", "ar.id", "la.articulo_id")
            //     ->join("almacenes as a", "a.id", "la.almacene_id")
            //     ->join("stocks as ss", "ss.id", "la.stocke_id")
            //     ->join("proyectos as pr", "pr.id", "ss.proyecto_id")
            //     ->leftJoin("stands as s", "s.id", "la.stand_id")
            //     ->leftJoin("niveles as n", "n.id", "la.nivel_id")
            //     ->where("codigo_barras", 'LIKE', '%' . $texto_busqueda . '%')
            //     ->where('la.codigo_barras','NOT LIKE', $not_like.'%')
            //     ->where('codigo_barras', '!=', $codigo)
            //     ->orderBy("codigo_barras")
            //     ->select(
            //         "ar.descripcion as a_desc",
            //         "la.id as la_id",
            //         "la.cantidad as cantidad",
            //         "la.articulo_id as a_id",
            //         "la.codigo_barras as codigo",
            //         "l.nombre as lote_nombre",
            //         "a.nombre as almacen",
            //         "n.nombre as nivel",
            //         "s.nombre as stand",
            //         "pr.nombre_corto as nombre_proyecto"
            //     )
            //     ->get()->toArray();
            //
            // $articulos = array_merge($articulos_a, $articulos_b);

            $inicio = explode(' ', $id);

            if (count($inicio) == 1)
            {

                $aux = "MCF";

                $pos = substr($id, 0, 3);

                if ($pos === 'MCF')
                {
                    // Interno
                    $num = str_replace("MCF", "", $id);

                    // $articulos_a = DB::table("lote_almacen as la")
                    //    ->join("lotes as l", "l.id", "la.lote_id")
                    //    ->join("articulos as ar", "ar.id", "la.articulo_id")
                    //    ->join("almacenes as a", "a.id", "la.almacene_id")
                    //    ->join("stocks as ss", "ss.id", "la.stocke_id")
                    //    ->join("proyectos as pr", "pr.id", "ss.proyecto_id")
                    //    ->leftJoin("stands as s", "s.id", "la.stand_id")
                    //    ->leftJoin("niveles as n", "n.id", "la.nivel_id")
                    //    ->where("codigo_barras", $codigo)
                    //    ->orderBy("codigo_barras")
                    //    ->select(
                    //        "ar.descripcion as a_desc",
                    //        "la.id as la_id",
                    //        "la.cantidad as cantidad",
                    //        "la.articulo_id as a_id",
                    //        "la.codigo_barras as codigo",
                    //        "l.nombre as lote_nombre",
                    //        "a.nombre as almacen",
                    //        "n.nombre as nivel",
                    //        "s.nombre as stand",
                    //        "pr.nombre_corto as nombre_proyecto"
                    //    )
                    //    ->get()->toArray();

                    $l_uno = DB::table('lote_almacen AS la')
                        ->join('lotes as l', 'l.id', '=', 'la.lote_id')
                        ->join('articulos AS a', 'a.id', '=', 'la.articulo_id')
                        ->join('stocks AS s', 's.id', '=', 'la.stocke_id')
                        ->join('proyectos AS p', 'p.id', '=', 's.proyecto_id')
                        ->leftJoin('almacenes AS al', 'al.id', 'la.almacene_id')
                        ->leftJoin('niveles AS niv', 'niv.id', 'la.nivel_id')
                        ->leftJoin('stands AS st', 'st.id', 'la.stand_id')
                        ->select(
                            "a.descripcion as a_desc",
                            "la.id as la_id",
                            "la.cantidad as cantidad",
                            "la.articulo_id as a_id",
                            "la.codigo_barras as codigo",
                            "l.nombre as lote_nombre",
                            "al.nombre as almacen",
                            "niv.nombre as nivel",
                            "st.nombre as stand",
                            "p.nombre_corto as nombre_proyecto"
                        )
                        ->where('la.codigo_barras', 'LIKE', $aux . '%')
                        ->where('la.codigo_barras', 'LIKE', '%' . $num)
                        ->get()->toArray();

                    $l_dos = [];
                    if (count($l_uno) > 0)
                    {
                        $l_dos = DB::table('lote_almacen AS la')
                            ->join('lotes as l', 'l.id', '=', 'la.lote_id')
                            ->join('articulos AS a', 'a.id', '=', 'la.articulo_id')
                            ->join('stocks AS s', 's.id', '=', 'la.stocke_id')
                            ->join('proyectos AS p', 'p.id', '=', 's.proyecto_id')
                            ->leftJoin('almacenes AS al', 'al.id', 'la.almacene_id')
                            ->leftJoin('niveles AS niv', 'niv.id', 'la.nivel_id')
                            ->leftJoin('stands AS st', 'st.id', 'la.stand_id')
                            ->select(
                                "a.descripcion as a_desc",
                                "la.id as la_id",
                                "la.cantidad as cantidad",
                                "la.articulo_id as a_id",
                                "la.codigo_barras as codigo",
                                "l.nombre as lote_nombre",
                                "al.nombre as almacen",
                                "niv.nombre as nivel",
                                "st.nombre as stand",
                                "p.nombre_corto as nombre_proyecto"
                            )
                            ->where('la.articulo_id', $l_uno[0]->a_id)
                            ->where('la.id', '!=', $l_uno[0]->la_id)
                            ->get()->toArray();
                    }
                    $l = array_merge($l_uno, $l_dos);
                }
                else
                {
                    // Proveedor
                    $l = DB::table('lote_almacen AS la')
                        ->join('lotes as l', 'l.id', '=', 'la.lote_id')
                        ->join('articulos AS a', 'a.id', '=', 'la.articulo_id')
                        ->join('stocks AS s', 's.id', '=', 'la.stocke_id')
                        ->join('proyectos AS p', 'p.id', '=', 's.proyecto_id')
                        ->leftJoin('almacenes AS al', 'al.id', 'la.almacene_id')
                        ->leftJoin('niveles AS niv', 'niv.id', 'la.nivel_id')
                        ->leftJoin('stands AS st', 'st.id', 'la.stand_id')
                        ->select(
                            "a.descripcion as a_desc",
                            "la.id as la_id",
                            "la.cantidad as cantidad",
                            "la.articulo_id as a_id",
                            "la.codigo_barras as codigo",
                            "l.nombre as lote_nombre",
                            "al.nombre as almacen",
                            "niv.nombre as nivel",
                            "st.nombre as stand",
                            "p.nombre_corto as nombre_proyecto"
                        )
                        ->where('la.codigo_barras', $id)
                        ->get();
                }
            }
            else
            {
                $solo_numeros = str_replace('MCF ', '', $id);
                $valores = explode(' ', $solo_numeros);
                $texto_busqueda = ' ' . $valores[1] . ' ';
                // $nombre_l =  'lote '.(int)$valores[0].'-'.(int)$valores[1].'-'.(int)$valores[2];
                $l_uno = DB::table('lote_almacen AS la')
                    ->join('lotes as l', 'l.id', '=', 'la.lote_id')
                    ->join('articulos AS a', 'a.id', '=', 'la.articulo_id')
                    ->join('stocks AS s', 's.id', '=', 'la.stocke_id')
                    ->join('proyectos AS p', 'p.id', '=', 's.proyecto_id')
                    ->leftJoin('almacenes AS al', 'al.id', 'la.almacene_id')
                    ->leftJoin('niveles AS niv', 'niv.id', 'la.nivel_id')
                    ->leftJoin('stands AS st', 'st.id', 'la.stand_id')
                    ->select(
                        "a.descripcion as a_desc",
                        "la.id as la_id",
                        "la.cantidad as cantidad",
                        "la.articulo_id as a_id",
                        "la.codigo_barras as codigo",
                        "l.nombre as lote_nombre",
                        "al.nombre as almacen",
                        "niv.nombre as nivel",
                        "st.nombre as stand",
                        "p.nombre_corto as nombre_proyecto"
                    )
                    ->where('la.codigo_barras', $id)
                    ->get()->toArray();

                $l_dos = DB::table('lote_almacen AS la')
                    ->join('lotes as l', 'l.id', '=', 'la.lote_id')
                    ->join('articulos AS a', 'a.id', '=', 'la.articulo_id')
                    ->join('stocks AS s', 's.id', '=', 'la.stocke_id')
                    ->join('proyectos AS p', 'p.id', '=', 's.proyecto_id')
                    ->leftJoin('almacenes AS al', 'al.id', 'la.almacene_id')
                    ->leftJoin('niveles AS niv', 'niv.id', 'la.nivel_id')
                    ->leftJoin('stands AS st', 'st.id', 'la.stand_id')
                    ->select(
                        "a.descripcion as a_desc",
                        "la.id as la_id",
                        "la.cantidad as cantidad",
                        "la.articulo_id as a_id",
                        "la.codigo_barras as codigo",
                        "l.nombre as lote_nombre",
                        "al.nombre as almacen",
                        "niv.nombre as nivel",
                        "st.nombre as stand",
                        "p.nombre_corto as nombre_proyecto"
                    )
                    ->where('la.codigo_barras', 'LIKE', '%' . $texto_busqueda . '%')
                    ->where('la.codigo_barras', 'NOT LIKE', 'MCF ' . $valores[1] . '%')
                    ->where('la.codigo_barras', '!=', $id)
                    ->get()->toArray();

                $l = array_merge($l_uno, $l_dos);
            }

            if (count($l) == 0)
                return response()->json(new Response(404, "OK", $l, true));
            else
                return response()->json(new Response(200, "OK", $l, true));
        }
        catch (Exception $e)
        {
            Utilidades::errors($e, 2);
            return response()->json(new Response(500, $e->getMessage(), null, false));
        }
    }

    // Genera una nueva salida a taller
    public function RegistrarSalida(Request $request)
    {
        try
        {
            DB::beginTransaction();
            /*** Encabezado ***/
            // Folio
            $hoy = date("Y-m-d");
            // Obtener Salida
            $salida = DB::table('salidas')
                ->where('fecha', 'like', date('Y-m-d'))
                ->where('proyecto_id', $request->proyecto_id)
                ->where('empleado_id', $request->id_supervisor)
                ->first();

            if ($salida == null)
            {
                // Folio
                $folios = DB::table("salidas as s")
                    ->where("proyecto_id", $request->proyecto_id)
                    ->get();
                $n = count($folios);
                $folio = str_pad(($n + 1), 4, "0", STR_PAD_LEFT);

                // Empleado
                $empleado = DB::table("users as u")
                    ->leftJoin("empleados as e", "e.id", "u.empleado_id")
                    ->where("u.id", "=", $request->usuaro_id)
                    ->select("u.name_user", "e.nombre", "e.id")->first();
                if ($empleado->id == null)
                    $empleado->id = 9999;
                $id_empleado = $empleado->id;
                // Nueva salida
                $salida = new Salida();
                $salida->fecha = date('Y-m-d');
                $salida->folio = $folio;
                $salida->ubicacion = $request->ubicacion;
                $salida->proyecto_id = $request->proyecto_id;
                $salida->tiposalida_id = $request->salida_tipo;
                $salida->empleado_id = $request->id_supervisor;
                $salida->empleado_entrega_id = $id_empleado;
                $salida->supervisores_proyectos_id = $this->getSupervisor($request->proyecto_id);
                $salida->save();
            }

            /*** Movimientos ***/
            $partidas = new Partidas();
            $partidas->salida_id = $salida->id;
            $partidas->tiposalida_id = $request->salida_tipo;
            $partidas->lote_id = $request->lote_id;
            $partidas->cantidad = $request->cantidad;
            $partidas->save();

            $tipo_salida_nombre = "Taller";

            $lote_almacen = LoteAlmacen::where('id', '=', $partidas->lote_id)->first();
            $cantidadresta = $lote_almacen->cantidad - $request->cantidad;
            $lote_almacen->cantidad = $cantidadresta;
            Utilidades::auditar($lote_almacen, $lote_almacen->id, 2);
            $lote_almacen->update();

            $stokearticulo = StockArticulo::where('articulo_id', '=', $lote_almacen->articulo_id)
                ->where('stocke_id', '=', $lote_almacen->stocke_id)->first();
            $cantidadrestastoke = $stokearticulo->cantidad - $request->cantidad;
            $stokearticulo->cantidad = $cantidadrestastoke;
            Utilidades::auditar($stokearticulo, $stokearticulo->id, 2);
            $stokearticulo->update();

            $existencias = Existencia::where('id_lote', '=', $lote_almacen->lote_id)
                ->where('articulo_id', '=', $lote_almacen->articulo_id)->first();
            if ($existencias == null)
            {
                return response()->json(new Response(500, "Artículo sin existencias en el lote indicado", 0, false));
            }
            $cantidadrestaexistencia = $existencias->cantidad - $request->cantidad;
            $existencias->cantidad = $cantidadrestaexistencia;
            Utilidades::auditar($existencias, $existencias->id, 2);
            $existencias->update();

            $stok_request = DB::table('stocks')->where('proyecto_id', $request->proyecto_id)->first();
            $stocks = Stock::where('id', '=', $stokearticulo->stocke_id)->first();
            $stk_id = 0;
            if ($stok_request->id != $stocks->id)
            {
                $stk_id = $stok_request->id;
                $proyectos = Proyecto::where('id', '=', $stok_request->proyecto_id)->first();
            }
            else
            {
                $stk_id = $stocks->id;
                $proyectos = Proyecto::where('id', '=', $stocks->proyecto_id)->first();
            }

            // Movimiento
            $hoy = date("Y-m-d");
            $hora = date("H:i:s");
            $movimiento = new Movimiento();
            $movimiento->cantidad = $request->cantidad;
            $movimiento->fecha = $hoy;
            $movimiento->hora = $hora;
            $movimiento->tipo_movimiento = 'Salida ';
            $movimiento->folio = 'Salida-' . $lote_almacen->articulo_id . ' a ' . $tipo_salida_nombre;
            $movimiento->proyecto_id = $proyectos->id;
            $movimiento->lote_id =  $lote_almacen->id;
            $movimiento->stocke_id =  $stk_id;
            $movimiento->almacene_id = $lote_almacen->almacene_id;
            $movimiento->stand_id = ($lote_almacen->stand_id == 'null') ? null : $lote_almacen->stand_id;
            $movimiento->nivel_id = ($lote_almacen->nivel_id == 'null') ? null : $lote_almacen->nivel_id;
            $movimiento->articulo_id = $lote_almacen->articulo_id;
            Utilidades::auditar($movimiento, $movimiento->id, 2);
            $movimiento->save();
            DB::commit();
            return response()->json(new Response(200, "OK", $salida->id, true));
        }
        catch (Exception $e)
        {
            Utilidades::errors($e, 2);
            DB::rollBack();
            return response()->json(new Response(500, $e->getMessage(), null, false));
        }
    }

    /**
     * Registra una nueva salida a resguardo
     */
    public function SalidaResguardo(Request $request)
    {
        try
        {
            DB::beginTransaction();

            // Usuario que registra
            // Empleado
            $empleado = DB::table("users as u")
                ->leftJoin("empleados as e", "e.id", "u.empleado_id")
                ->where("u.id", "=", $request->usuaro_id)
                ->select("u.name_user", "e.nombre", "e.id")->first();
            if ($empleado->id == null)
                $empleado->id = 9999;
            $id_empleado = $empleado->id;

            $hoy = date("Y-m-d");
            $hora = date("H:i:s");

            $salida = SalidasResguardo::where('proyecto_id', $request->proyecto_id)
                ->where('fecha', $hoy)
                ->where('empleado_solicita_id', $request->id_supervisor)
                ->first();
            if ($salida == null) // Registrar nueva salida
            {
                // Generar folio
                $folios = DB::table("salidasresguardo as s")
                    ->where("proyecto_id", $request->proyecto_id)
                    ->get();
                $n = count($folios);
                $folio = str_pad(($n + 1), 4, "0", STR_PAD_LEFT);

                // Registrar salida
                $salida = new SalidasResguardo();
                $salida->fecha = $hoy;
                $salida->folio = $folio;
                $salida->proyecto_id = $request->proyecto_id;
                $salida->tiposalida_id = 3;
                $salida->empleado_entrega_id = $id_empleado;
                $salida->empleado_solicita_id = $request->id_supervisor;
                $salida->supervisores_proyectos_id = $this->getSupervisor($request->proyecto_id);
                $salida->save();
            }

            /*** Movimientos ***/
            $partidas = Partidas::where('lote_id', $request->lote_id)
                ->where('tiposalida_id', '3')
                ->where('salida_id', $salida->id)
                ->first();

            if ($partidas == null) // Crear nueva partida
            {
                $partidas = new Partidas();
                $partidas->salida_id = $salida->id;
                $partidas->tiposalida_id = 3; // Resguardo
                $partidas->lote_id = $request->lote_id;
                $partidas->cantidad = $request->cantidad;
                $partidas->save();
            }
            else
            {
                $partidas->cantidad = $partidas->cantidad + $request->cantidad;
                $partidas->update();
            }

            $lote_almacen = LoteAlmacen::where('id', '=', $partidas->lote_id)->first();
            $cantidadresta = $lote_almacen->cantidad - $request->cantidad;
            $lote_almacen->cantidad = $cantidadresta;
            Utilidades::auditar($lote_almacen, $lote_almacen->id, 2); // Usuario = Sistema
            $lote_almacen->update();

            $stokearticulo = StockArticulo::where('articulo_id', '=', $lote_almacen->articulo_id)
                ->where('stocke_id', $lote_almacen->stocke_id)
                ->first();
            $cantidadrestastoke = $stokearticulo->cantidad - $request->cantidad;
            $stokearticulo->cantidad = $cantidadrestastoke;
            Utilidades::auditar($stokearticulo, $stokearticulo->id, 2); // Usuario = Sistema
            $stokearticulo->update();

            $existencias = Existencia::where('id_lote', $lote_almacen->lote_id)
                ->where('articulo_id', $lote_almacen->articulo_id)
                ->first();

            if ($existencias == null)
            {
                //ERROR. No hay existencia del artículo
                return response()->json(new Response(500, "Artículo sin existencias en el lote indicado", 0, false));
            }

            $cantidadrestaexistencia = $existencias->cantidad - $request->cantidad;
            $existencias->cantidad = $cantidadrestaexistencia;
            Utilidades::auditar($existencias, $existencias->id, 2);
            $existencias->update();

            $stok_request = DB::table('stocks')->where('proyecto_id', $request->proyecto_id)
                ->first();
            $stocks = Stock::where('id', '=', $stokearticulo->stocke_id)->first();
            $stk_id = 0;
            if ($stok_request->id != $stocks->id)
            {
                $stk_id = $stok_request->id;
                $proyectos = Proyecto::where('id', $stok_request->proyecto_id)->first();
            }
            else
            {
                $stk_id = $stocks->id;
                $proyectos = Proyecto::where('id', $stocks->proyecto_id)->first();
            }

            // Movimiento
            $movimiento = new Movimiento();
            $movimiento->cantidad = $request->cantidad;
            $movimiento->fecha = $hoy;
            $movimiento->hora = $hora;
            $movimiento->tipo_movimiento = 'Salida ';
            $movimiento->folio = 'Salida-' . $lote_almacen->articulo_id . " a Resg";
            $movimiento->proyecto_id = $proyectos->id;
            $movimiento->lote_id =  $lote_almacen->id;
            $movimiento->stocke_id =  $stk_id;
            $movimiento->almacene_id = $lote_almacen->almacene_id;
            $movimiento->stand_id = ($lote_almacen->stand_id == 'null') ? null : $lote_almacen->stand_id;
            $movimiento->nivel_id = ($lote_almacen->nivel_id == 'null') ? null : $lote_almacen->nivel_id;
            $movimiento->articulo_id = $lote_almacen->articulo_id;
            Utilidades::auditar($movimiento, $movimiento->id, 2);
            $movimiento->save();

            DB::commit();
            return response()->json(new Response(200, "Ok", null, true));
        }
        catch (\Throwable $e)
        {
            DB::rollBack();
            Utilidades::errors($e);
        }
    }

    // Muestra las salidas a taller
    public function VerSalidas($salida_id)
    {
        try
        {
            $salida_id = $salida_id;
            $partidasalida = DB::table('partidas')
                ->leftJoin('salidas AS S', 'S.id', '=', 'partidas.salida_id')
                ->leftJoin('proyectos AS P', 'P.id', '=', 'S.proyecto_id')
                ->leftJoin('tipo_salidas AS TS', 'TS.id', '=', 'S.tiposalida_id')
                ->leftJoin('lote_almacen AS LA', 'LA.id', '=', 'partidas.lote_id')
                ->leftJoin('almacenes AS AL', 'AL.id', '=', 'LA.almacene_id')
                ->leftJoin('niveles AS N', 'N.id', '=', 'LA.nivel_id')
                ->leftJoin('stands AS ST', 'ST.id', '=', 'LA.stand_id')
                ->leftJoin('stocks AS SK', 'SK.id', '=', 'LA.stocke_id')
                ->leftJoin('articulos AS A', 'A.id', '=', 'LA.articulo_id')
                ->select(
                    'partidas.cantidad as cantidad',
                    'A.descripcion AS a_desc'
                )
                ->where('partidas.salida_id', '=', $salida_id)
                ->where('partidas.tiposalida_id', '=', 1) // 1. Taller
                ->get();
            return response()->json(new Response(200, "OK", $partidasalida, true));
        }
        catch (Exception $e)
        {
            Utilidades::errors($e, 2);
            DB::rollBack();
            return response()->json(new Response(500, $e->getMessage(), null, false));
        }
    }

    // Busca un artículo mediante su descripción
    public function BuscarArticulo($params)
    {
        try
        {
            $dts = explode("&", $params);
            if (count($dts) != 2)
                return response()->json(new Response(500, "Ingrese todos los datos", null, false));
            $texto_busqueda = "%" . $dts[0] . "%";
            $index = $dts[1];
            $articulos = DB::table("lote_almacen as la")
                ->join("articulos as a", "a.id", "la.articulo_id")
                ->join("lotes as l", "l.id", "la.lote_id")
                ->join("partidas_entradas as pe", "pe.id", "l.entrada_id")
                ->join("requisicion_has_ordencompras as rho", "rho.id", "pe.req_com_id")
                ->join("ordenes_compras as oc", "oc.id", "rho.orden_compra_id")
                ->where("a.descripcion", "like", $texto_busqueda)
                ->select(
                    "a.id as a_id",
                    "la.id as la_id",
                    "la.codigo_barras",
                    "a.descripcion",
                    "oc.folio as folio_oc"
                )
                ->offset($index)
                ->limit(100)
                ->orderBy("folio")
                ->orderBy("descripcion")
                ->get();
            return response()->json(new Response(200, "OK", $articulos, true));
        }
        catch (Exception $e)
        {
            Utilidades::errors($e, 2);
            DB::rollBack();
            return response()->json(new Response(500, $e->getMessage(), null, false));
        }
    }

    public function ActualizarCodigo(Request $request)
    {
        try
        {
            $la = LoteAlmacen::find($request->la_id);
            if ($la == null)
                return response()->json(new Response(500, "Artículo no encontrado", null, false));
            else
            {
                $la->codigo_barras = $request->codigo;
                $la->save();
                return response()->json(new Response(200, "OK", null, true));
            }
        }
        catch (Exception $e)
        {
            Utilidades::errors($e, 2);
            DB::rollBack();
            return response()->json(new Response(500, $e->getMessage(), null, false));
        }
    }

    public function getSupervisor($id)
    {
        $supervisor = DB::table('supervisores_proyectos')
            ->where('proyecto_id', $id)
            ->where('activo', '1')
            ->first();

        return isset($supervisor) == false ? 0 : $supervisor->id;
    }
}
