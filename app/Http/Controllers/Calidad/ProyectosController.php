<?php

namespace App\Http\Controllers\Calidad;

use App\CalidadModels\Proyecto;
use App\CalidadModels\Proyectos;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Status;
use App\Http\Helpers\Utilidades;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProyectosController extends Controller
{

    /**
     * Obtiene todos los proyectos de calidad
     */
    public function ObtenerProyectos()
    {
        try
        {
            $proyectos = $this->AuxObtenerProyectos();
            return response()->json(["status" => true, "proyectos" => $proyectos]);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }

    /**
     * Obtiene los proyectos y carga los logos del cliente
     */
    public function ObtenerProyectosImg()
    {
        try
        {
            // Obtener proyectos
            $proyectos = $this->AuxObtenerProyectos();
            // Cargar imagenes
            foreach ($proyectos as $proyecto)
            {
                $path = "Archivos/Clientes/" . $proyecto->logo_cliente; // ruta de la imagen
                if (Storage::exists($path))
                {
                    $imagen = Storage::disk('local')->get($path);
                    Storage::disk('temporal')->put($proyecto->logo_cliente, $imagen);
                }
            }
            return response()->json(["status" => true, "proyectos" => $proyectos]);
        }
        catch (Exception $e)
        {
            dd($e);
            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }

    /**
     * Obtiene proyectos
     */
    private function AuxObtenerProyectos()
    {
        try
        {
            $proyectos = DB::Table("calidad_proyectos")
                ->join("proyectos as p", "p.id", "calidad_proyectos.proyecto_id")
                ->select("calidad_proyectos.*", "p.nombre_corto as nombre_proyecto")
                ->get();
            return $proyectos;
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return false;
        }
    }

    /**
     * Obtiene los proyectos activos registrados en el sistema
     */
    public function ObtenerProySistema()
    {
        try
        {
            $proyectos = DB::table("proyectos as p")
                ->join("proyecto_subcategorias as ps", "ps.id", "p.proyecto_subcategorias_id")
                ->join("proyecto_categorias as pc", "pc.id", "ps.proyecto_categoria_id")
                ->where("p.condicion", 1)
                ->where("pc.id", 1)
                ->orderBy("p.nombre_corto")
                ->select("p.id", "p.nombre_corto as nombre")
                ->get();
            return response()->json(["status" => true, "proyectos" => $proyectos]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }

    /**
     * Crea o almacena un proyecto para calidad
     */
    public function GuardarProyecto(Request $request)
    {
        try
        {
            $nombre = "";
            // Si tiene imagen se guarda
            if ($request->hasFile('logo_cliente'))
            {
                $img = $request->file("logo_cliente");
                $nombre = $img->getClientOriginalName();
                $ruta_documento = "Archivos/Clientes/" . $nombre; // Path de la imagen
                Storage::disk('local')->put($ruta_documento, fopen($img, 'r+'));
            }

            if ($request->id == null || $request->id == "null")
            {
                $proyecto = new Proyecto();
                $proyecto->logo_cliente = $nombre;
                $proyecto->fill($request->all());
                $proyecto->save();
            }
            else
            {
                $proyecto = Proyecto::find($request->id);
                $proyecto->logo_cliente = $request->logo_cliente;
                $proyecto->nombre = $request->nombre;
                $proyecto->logo_cliente = $nombre;
                $proyecto->update();
            }
            return response()->json(["status" => true]);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mesaje" => $e->getMessage()]);
        }
    }
}
