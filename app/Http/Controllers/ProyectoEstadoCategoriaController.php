<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Utilidades;
use Exception;
use Illuminate\Support\Facades\DB;

class ProyectoEstadoCategoriaController extends Controller
{

    public function ObtenerProyectos()
    {
        try
        {
            $proyectos = DB::table("proyectos as p")
                ->leftJoin("proyecto_subcategorias as ps", "ps.id", "p.proyecto_subcategorias_id")
                ->leftJoin("proyecto_categorias as pc", "pc.id", "ps.proyecto_categoria_id")
                ->select(
                    "p.id",
                    "p.nombre_corto as proyecto",
                    "pc.nombre as categoria",
                    "ps.nombre as sub",
                    "p.condicion",
                    "p.fecha_inicio"
                )
                ->orderBy("proyecto")
                ->orderBy("categoria")
                ->orderBy("sub")
                ->get();
            return response()->json(["status" => true, "proyectos" => $proyectos]);
        }
        catch (Exception $e)
        {

            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }
}
