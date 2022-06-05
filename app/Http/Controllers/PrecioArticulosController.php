<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Utilidades;
use App\Partidas;
use App\PrecioAlmacen;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrecioArticulosController extends Controller
{

    public function ObtenerArticulos()
    {
        try
        {
            $articulos = DB::table("partidas_requisiciones as pr")
                ->join("requisiciones as r", "r.id", "pr.requisicione_id")
                ->join("proyectos as p", "p.id", "r.proyecto_id")
                ->join("articulos as a", "a.id", "pr.articulo_id")
                ->join("grupos as g", "g.id", "a.grupo_id")
                ->where("pr.cantidad_almacen", ">", 0)
                ->where("pr.precio_compras", "=", 0)
                ->where("r.fecha_solicitud", ">", "2020-04-24")
                ->select(
                    "pr.requisicione_id as req_id",
                    "p.nombre_corto as proyecto",
                    "a.id as a_id",
                    "a.descripcion",
                    "a.marca",
                    "pr.cantidad_almacen as cantidad",
                    "g.nombre as grupo",
                    "r.fecha_solicitud as fecha"
                )
                ->orderBy("fecha", "DESC")
                ->orderBy("descripcion")
                ->distinct()
                ->get();
            return response()->json(["status" => true, "articulos" => $articulos]);
        }
        catch (Exception $e)
        {
            return response()->json(["mensaje" => $e->getMessage()]);
        }
    }

    public function Registrar(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $precio = new PrecioAlmacen();
            $precio->fill($request->all());
            $precio->save();

            $existe = DB::table("partidas_requisiciones")->where("requisicione_id", "=", $request->req_id)
                ->where("articulo_id", "=", $request->articulo_id)->first();

            if ($existe != null)
            {
                DB::table("partidas_requisiciones")->where("requisicione_id", "=", $request->req_id)
                    ->where("articulo_id", "=", $request->articulo_id)->update(
                        [
                            "precio_compras" => 1,
                        ]
                    );

                DB::commit();
                return response()->json(["status" => true]);
            }
            else
            {
                DB::rollBack();
                return response()->json(["status" => false, "mensaje" => "No encontrado"]);
            }
        }
        catch (Exception $e)
        {
            DB::rollBack();
            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }

    /**
     * Registra un precio de almacén
     */
    public function Registrar2(Request $request)
    {
        try
        {
            DB::beginTransaction();
            // Registrar precio
            $precio = new PrecioAlmacen();
            $precio->fill($request->all());
            $precio->save();
            // Actualizar partida
            $partida = Partidas::find($request->partida_id);
            $partida->precio_asignado = 1; // Ya se asignó el precio
            $partida->update();
            DB::commit();
            return Status::Success();
        }
        catch (Exception $e)
        {
            DB::rollBack();
            return Status::Error($e, "registrar el precio");
        }
    }

    public function ObtenerPrecios()
    {
        try
        {
            $articulos = DB::table("precios_almacen as pa")
                ->join("articulos as a", "a.id", "pa.articulo_id")
                ->join("requisicion_has_ordencompras as rho", "rho.id", "pa.req_id")
                ->join("ordenes_compras as oc", "oc.id", "rho.orden_compra_id")
                ->select(
                    "pa.id",
                    "oc.folio",
                    "a.descripcion",
                    "pa.precio",
                    "rho.id as rho_id"
                )->get();
            return response()->json(["status" => true, "articulos" => $articulos]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }

    /**
     * Obtiene las salidas de los artículos del proyecto seleccionado
     */
    public function ObtenerArticulosSalida($p_id)
    {
        try
        {
            // Salidas taller
            $st = Partidas::join('salidas AS s', 's.id', 'partidas.salida_id')
                ->join('empleados AS e', 'e.id', 's.empleado_id')
                ->join('lote_almacen AS la', 'la.id', 'partidas.lote_id')
                ->join('lotes AS l', 'l.id', 'la.lote_id')
                ->join('partidas_entradas AS pe', 'pe.id', 'l.entrada_id')
                ->join('requisicion_has_ordencompras AS rhoc', 'rhoc.id', 'pe.req_com_id')
                ->join('ordenes_compras AS oc', 'oc.id', 'rhoc.orden_compra_id')
                ->join('articulos AS a', 'a.id', 'la.articulo_id')
                ->join('stocks AS ss', 'ss.id', 'la.stocke_id')
                ->Join('proyectos AS p', 'p.id', 'ss.proyecto_id')
                ->Join('proyectos AS p2', 'p2.id', 's.proyecto_id')
                ->leftJoin('proyecto_subcategorias AS ps', 'ps.id',  'p.proyecto_subcategorias_id')
                ->leftJoin('proyecto_categorias AS pc', 'pc.id', 'ps.proyecto_categoria_id')
                ->where('s.proyecto_id', $p_id)
                ->where("oc.folio", "OC-CONSERFLOW-020-GENERAL-001")
                ->where('partidas.tiposalida_id', '1')
                ->where('partidas.precio_asignado', 0)
                ->select(
                    "a.id as a_id",
                    "partidas.id as partida_id",
                    'partidas.cantidad AS cantidad_salida',
                    's.id as ids',
                    DB::raw("('Taller') AS tipo"),
                    's.folio as folio',
                    'p.nombre_corto',
                    "p2.nombre_corto as p_salida",
                    'a.nombre AS nombre',
                    'a.descripcion AS desc',
                    'a.unidad as unidad',
                    's.empleado_id',
                    DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_solicita"),
                    'oc.folio AS oc_folio'
                )
                ->get()->toArray();

            // Salidas sitio
            $ss = Partidas::join('salidassitio AS s', 's.id', 'partidas.salida_id')
                ->join('empleados AS e', 'e.id', 's.empleado_solicita_id')
                ->join('lote_almacen AS la', 'la.id', 'partidas.lote_id')
                ->join('lotes AS l', 'l.id', 'la.lote_id')
                ->join('partidas_entradas AS pe', 'pe.id', 'l.entrada_id')
                ->join('requisicion_has_ordencompras AS rhoc', 'rhoc.id', 'pe.req_com_id')
                ->join('ordenes_compras AS oc', 'oc.id', 'rhoc.orden_compra_id')
                ->join('articulos AS a', 'a.id', 'la.articulo_id')
                ->join('stocks AS ss', 'ss.id', 'la.stocke_id')
                ->Join('proyectos AS p', 'p.id', 'ss.proyecto_id')
                ->join('proyectos AS p2', 'p2.id', 's.proyecto_id')
                ->leftJoin('proyecto_subcategorias AS ps', 'ps.id', 'p.proyecto_subcategorias_id')
                ->leftJoin('proyecto_categorias AS pc', 'pc.id', 'ps.proyecto_categoria_id')
                ->where('s.proyecto_id', $p_id)
                ->where('partidas.tiposalida_id', '2')
                ->where('partidas.precio_asignado', 0)
                ->where("oc.folio", "OC-CONSERFLOW-020-GENERAL-001")
                ->select(
                    "partidas.id as partida_id",
                    "a.id as a_id",
                    'partidas.cantidad AS cantidad_salida',
                    's.id as ids',
                    DB::raw("('Sitio') AS tipo"),
                    's.folio as folio',
                    'p.nombre_corto',
                    'p2.nombre_corto AS p_salida',
                    'a.nombre AS nombre',
                    'a.descripcion AS desc',
                    'a.unidad as unidad',
                    's.empleado_solicita_id AS empleado_id',
                    DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_solicita"),
                    'oc.folio AS oc_folio'
                )
                ->get()->toArray();

            $array_uno = array_merge($st, $ss);

            $sr = Partidas::join('salidasresguardo AS s', 's.id', 'partidas.salida_id')
                ->join('empleados AS e', 'e.id', 's.empleado_solicita_id')
                ->join('lote_almacen AS la', 'la.id', 'partidas.lote_id')
                ->join('lotes AS l', 'l.id', 'la.lote_id')
                ->join('partidas_entradas AS pe', 'pe.id', 'l.entrada_id')
                ->join('requisicion_has_ordencompras AS rhoc', 'rhoc.id', 'pe.req_com_id')
                ->join('ordenes_compras AS oc', 'oc.id', 'rhoc.orden_compra_id')
                ->join('articulos AS a', 'a.id', 'la.articulo_id')
                ->join('stocks AS ss', 'ss.id', 'la.stocke_id')
                ->Join('proyectos AS p', 'p.id', 'ss.proyecto_id')
                ->leftJoin('proyecto_subcategorias AS ps', 'ps.id', 'p.proyecto_subcategorias_id')
                ->leftJoin('proyecto_categorias AS pc', 'pc.id', 'ps.proyecto_categoria_id')
                ->where('s.proyecto_id', $p_id)
                ->where('partidas.tiposalida_id', '3')
                ->where('partidas.precio_asignado', 0)
                ->where("oc.folio", "OC-CONSERFLOW-020-GENERAL-001")
                ->select(
                    "partidas.id as partida_id",
                    "a.id as a_id",
                    's.id as ids',
                    DB::raw("('Resguardo') AS tipo"),
                    's.folio as folio',
                    'p.nombre_corto',
                    'a.nombre AS nombre',
                    "a.id as a_id",
                    'a.descripcion AS desc',
                    'a.unidad as unidad',
                    's.empleado_solicita_id AS empleado_id',
                    DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_solicita"),
                    'oc.folio AS oc_folio'
                )
                ->get()->toArray();

            $array = array_merge($array_uno, $sr);

            return Status::Success("articulos", $array);
        }
        catch (Exception $e)
        {
            dd($e);
            return Status::Error($e, "obtener los arítículos");
        }
    }
}
