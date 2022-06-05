<?php

namespace App\Http\Controllers\Calidad;

use App\CalidadModels\Brida;
use App\CalidadModels\PartidaTorque;
use App\CalidadModels\Torque;
use App\CalidadModels\TorqueImagen;
use App\EquiposCatalogo;
use App\Http\Controllers\Status;
use App\Http\Helpers\Utilidades;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;

class TorqueController extends Controller
{

    private $PATH_ROOT = "Archivos/Torque/";
    /**
     * Obtiene los reportes de torque del proyecto ingresado
     */
    public function Obtener($proyecto_id)
    {
        try
        {
            $torque = $this->ObtenerTorqueAux(1, $proyecto_id);

            return Status::Success("torque", $torque);
        }
        catch (Exception $e)
        {
            Status::Error($e, "obtener los reportes");
        }
    }

    /**
     * Obtiene los detalles de la inspeccion ingresada
     * @param int $tipo 1: Por proyecto. 2: Por Reporte
     */
    private function ObtenerTorqueAux($tipo, $id)
    {
        $query = DB::table("calidad_torque as ct")
            ->join("calidad_sistemas as cs", "cs.id", "ct.sistema_id")
            ->join("calidad_proyectos as cp", "cp.id", "ct.proyecto_id")
            ->join("proyectos as p", "p.id", "cp.proyecto_id")
            ->join("clientes as c", "c.id", "p.cliente_id")
            ->join("equipos_catalogo as ec1", "ec1.id", "ct.equipo1_id")
            ->join("equipos_catalogo as ec2", "ec2.id", "ct.equipo2_id")
            ->join("empleados as e", "e.id", "ct.inspecciona1_id")
            ->select(
                "cp.nombre as nombre_proyecto",
                "ct.*",
                "c.nombre as cliente",
                "cs.nombre as sistema_nombre",
                "cs.tag",
                "ec1.id as ec1_id",
                "ec1.descripcion as ec1_desc",
                "ec1.modelo as ec1_modelo",
                "ec1.numero_serie as ec1_numero_serie",
                "ec2.id as ec2_id",
                "ec2.descripcion as ec2_desc",
                "ec2.modelo as ec2_modelo",
                "ec2.numero_serie as ec2_numero_serie",
                "e.id as emp_inspecciona_id",
                DB::raw("CONCAT_WS(' ',e.nombre,e.ap_paterno,e.ap_materno) as emp_inspecciona_nom")

            );

        if ($tipo == 1)
        {
            // Proyecto. Obtener todos los reportes del proyecto ingresado
            $torque = $query->where("cs.proyecto_id", $id)->get();
        }
        else
        {
            // Inspección. Obtiene solo el reporte ingresado
            $torque = $query->where("ct.id", $id)->first();
        }
        return $torque;
    }

    /**
     * Obtener sistemas del proyecto seleccinado
     */
    public function ObtenerSistemas($p_id)
    {
        try
        {
            $sistemas = DB::table("calidad_proyectos as cp")
                ->join("calidad_sistemas as cs", "cs.proyecto_id", "cp.id")
                ->join("servicios_sistema as ss", "ss.sistema_id", "cs.id")
                ->where("cp.id", $p_id)
                ->select(
                    "ss.id",
                    "cs.tag",
                    "cs.nombre"
                )->get();
            return Status::Success("sistemas", $sistemas);
        }
        catch (Exception $e)
        {
            return Status::Error($e, "obtener los sistemas");
        }
    }

    /**
     * Obtiene todos los equipos de calibración para torque
     */
    public function ObtenerEquiposCalib()
    {
        try
        {
            $torquimetros = $this->ObtenerEquiposAux("torquimetro");
            $herramientas = $this->ObtenerEquiposAux("herramienta");
            return Status::Success("equipos", ["torquimetros" => $torquimetros, "herramientas" => $herramientas]);
        }
        catch (Exception $e)
        {
            return Status::Error($e, "obtener los equipos de calibración");
        }
    }

    /**
     * Obtiene los equipos que conincidan con el tipo ingresado
     */
    private function ObtenerEquiposAux($tipo)
    {
        $equipos = EquiposCatalogo::where("descripcion", "like", "%" . $tipo . "%")
            ->select("id", "descripcion", "marca", "modelo", "numero_serie")
            ->orderBy("descripcion")->get();
        return $equipos;
    }

    /**
     * Guarda el reporte de torque con los datos ingresdos
     */
    public function Guardar(Request $request)
    {
        try
        {
            // return Status::Success();
            if ($request->id == null)
            {
                $torque = new Torque($request->all());
                Utilidades::auditar($torque, $torque->id);
                $torque->save();
            }
            else
            {
                $torque = Torque::find($request->id);
                $torque->fill($request->all());
                Utilidades::auditar($torque, $torque->id);
                $torque->update();
            }
            return Status::Success();
        }
        catch (Exception $e)
        {
            return Status::Error($e, "guardar el reporte");
        }
    }

    /**
     * Obtiene las partidas del reporte ingresado
     * @param int $reporte_id Id del reporte de torque
     */
    public function ObtenerPartidas($reporte_id)
    {
        try
        {
            // Obtener todas las partidas del reporte
            $partidas = DB::table("calidad_torque_partida as ctp")
                ->join("calidad_bridas as cb", "cb.id", "brida_id")
                ->where("torque_id", $reporte_id)
                ->orderBy("item")
                ->select(
                    "ctp.*",
                    "cb.id as cb_id",
                    "cb.diametro_brida",
                    "cb.longitud_esparrago",
                    "cb.diametro_tornillo",
                    "cb.medida_tuerca",
                    DB::raw("CONCAT('Clase: ',clase,' - Diámetro ',diametro_brida,'Ø') as nombre_brida")
                )
                ->get();
            return Status::Success("partidas", $partidas);
        }
        catch (Exception $e)
        {
            dd($e);
            return Status::Error($e, "obtener las partidas");
        }
    }

    /**
     * Genera el reporte de la prueba de torque ingresada
     */
    public function GenerarReporte($reporte_id)
    {
        try
        {
            $torque = $this->ObtenerTorqueAux(2, $reporte_id); // Datos del reporte
            //Obtener partidas
            $partidas = DB::table("calidad_torque_partida as ctp")
                ->join("calidad_bridas as cb", "cb.id", "ctp.brida_id")
                ->where("ctp.torque_id", $reporte_id)
                ->select(
                    "ctp.*",
                    "cb.diametro_brida",
                    "cb.diametro_tornillo",
                    "cb.longitud_esparrago",
                    "cb.medida_tuerca",
                )->get();
            // dd($torque);
            $pdf = PDF::loadView('pdf.pruebatorque', compact("torque", "partidas"));

            $pdf->setPaper('letter', 'portrait');
            error_reporting(E_ALL ^ E_DEPRECATED);
            $pdf->getDomPDF()->set_option("enable_php", true);
            return $pdf->stream($torque->folio);
        }
        catch (Exception $e)
        {
            dd($e);
            return view("errors.500");
        }
    }

    /**
     * Registra una nueva partida del reporte de torque
     */
    public function GuardarPartida(Request $request)
    {
        try
        {
            if ($request->id == null)
            {
                $partida = new PartidaTorque($request->all());
                Utilidades::auditar($partida, 0);
                $partida->save();
            }
            else
            {
                $partida = PartidaTorque::find($request->id);
                $partida->fill($request->all());
                Utilidades::auditar($partida, $partida->id);
                $partida->update();
            }
            return Status::Success();
        }
        catch (Exception $e)
        {
            return Status::Error($e, "guardar la partida");
        }
    }

    /**
     * Obtiene las imagenes del reporte ingresado
     */
    public function ObtenerImagenes($torque_id)
    {
        try
        {
            $imagenes = DB::Table("calidad_torque_imagenes")->where("torque_id", $torque_id)->get();
            if (count($imagenes) == 0) // Sin imagenes
                return Status::Success("imagenes", ["valido" => false]);

            // mover imagenes al temporal
            $imagenes_temp = [];
            $path = "Archivos" . DIRECTORY_SEPARATOR . "Torque" . DIRECTORY_SEPARATOR . $torque_id . DIRECTORY_SEPARATOR;

            foreach ($imagenes as $imagen)
            {
                $nombre = $imagen->imagen;
                $archivo = $this->ftpSolucionT($path . $nombre);
                // Se coloca el archivo en una ruta local
                Storage::disk('temporal')->put($imagen->imagen, $archivo);
                $aux_imagen = (array)$imagen;
                $aux_imagen["ruta"] = "temp/" . $nombre; // Guardar la ubicación temporal en model
                $aux_imagen = (object)$aux_imagen;
                array_push($imagenes_temp, $aux_imagen);
            }
            return Status::Success("imagenes", ["imagenes" => $imagenes_temp, "valido" => true]);
        }
        catch (Exception $e)
        {
            return Status::Error($e, "obtener las imágenes");
        }
    }

    /** 
     * Carga una imagen el el directorio temporal
     */
    private function ftpSolucionT($path)
    {
        //Se busca en disk o carpeta
        if (Storage::exists($path))
        {
            // Se coloca el archivo en una ruta local
            $archivo = Storage::disk('local')->get($path);
        }
        else
        {
            $archivo = Storage::disk('local')->get($path);
        }
        return $archivo;
    }

    /**
     * Guardar las imagen seleccionada del reporte ingresada
     */
    public function GuardarImagenes(Request $request)
    {
        try
        {
            // Guardar docuemento
            if (!$request->hasFile('path_imagen')) return
                response()->json(["status" => false, "mensaje" => "Error al cargar la imágen"]);

            // Obtener imagen
            $img = $request->file("path_imagen");
            $nombre = $img->getClientOriginalName();
            $aux_nombre = $request->torque_id . DIRECTORY_SEPARATOR . $nombre;
            $ruta_documento = "Archivos/Torque/" . $aux_nombre; // Path de la imagen
            Storage::disk('local')->put($ruta_documento, fopen($img, 'r+'));

            // Guardar imagen
            if ($request->id == null)
            {
                $imagen = new TorqueImagen($request->all());
                $imagen->imagen = $nombre;
                $imagen->save();
            }
            else
            {
                $imagen = TorqueImagen::find($request->id);
                $imagen->fill($request->all());
                $imagen->imagen = $nombre;
                $imagen->update();
            }
            return Status::Success();
        }
        catch (Exception  $e)
        {
            Status::Error($e, "guardar las imágenes");
        }
    }

    /**
     * Obtiene todas las bridas para seleccion en partida-torque
     */
    public function ObtenerBridas()
    {
        try
        {
            $bridas = DB::table("calidad_bridas as cb")
                ->select(
                    "cb.*",
                    DB::raw("CONCAT('Clase: ',clase,' - Diámetro ',diametro_brida,'Ø') as nombre")
                )
                ->orderBy("clase")
                ->get();
            return Status::Success("bridas", $bridas);
        }
        catch (Exception $e)
        {
            return Status::Error($e);
        }
    }
}
