<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade as PDF;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RHCredencialesController extends Controller
{

    /**
     * Guada las imagenes en el temp para mostrar en en blade
     */
    public function GuardarImagenes(Request $request)
    {
        try
        {
            $data = substr($request->ids, 0, -1); //Eliminar ultimo '|'
            $ids = explode("|", $data);
            // Pasar imagen al temp
            for ($i = 0; $i < count($ids); $i++)
            {
                $img = $request->file("foto_" . $ids[$i]); // Obtener imagen
                $nombre = $img->getClientOriginalName(); // Nombre de imagen
                $aux_nombre = "credenciales/foto_" . $ids[$i] . ".jpg"; // Guardar
                Storage::disk('temporal')->put($aux_nombre, fopen($img, 'r+'));
            }
            //$this->Generar($ids);
            return Status::Success();
        }
        catch (Exception $e)
        {
            return Status::Error($e, "guardar las imágenes");
        }
    }

    public function ImgTemp($nombre, $request)
    {
        try
        {
            $img = $request->file($nombre);
            $nombre = $img->getClientOriginalName();
            Storage::disk('temporal')->put($nombre, $img);
        }
        catch (Exception $e)
        {
            dd($e);
        }
    }
    /**
     * Genera el documento de las credenciales de los empleados
     */
    public function Generar($data)
    {
        try
        {
            $data = substr($data, 0, -1); //Eliminar ultimo '|'
            $ids = explode("|", $data);
            // if (count($ids) < 4)
            //     return response()->json(["status" => false, "mensaje" => "Seleccione todos los empleados"]);

            $ids_aux = "e.id= " . $ids[0];
            $fotos = [];
            $fotos[] = "temp/credenciales/foto_" . $ids[0] . ".jpg";
            array_shift($ids);
            foreach ($ids as $id)
            {
                $ids_aux .= " or e.id= " . $id;
                $fotos[] = "temp/credenciales/foto_" . $id . ".jpg";
            }
            

            // Crear query
            $query = "SELECT e.id,
                CONCAT_WS(' ',e.ap_paterno,e.ap_materno,e.nombre) as nombre,
                e.nss_imss, p.nombre as puesto
                from empleados e
                join puestos p on p.id=e.puesto_id
                WHERE " . $ids_aux;
            $empleados = DB::Select($query);
            // Generar PDF
            //dd($fotos);
            $pdf = PDF::loadView('pdf.rhcredenciales', compact("empleados", "fotos"));
            $pdf->setPaper('letter', 'landscape');
            error_reporting(E_ALL ^ E_DEPRECATED);
            return $pdf->stream("Credenciales.pdf");
        }
        catch (Exception $e)
        {
            dd($e);
            Status::Error($e);
            return View("errors.500");
        }
    }
}
