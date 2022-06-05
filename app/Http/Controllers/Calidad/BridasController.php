<?php

namespace App\Http\Controllers\Calidad;

use App\CalidadModels\Brida;
use App\Http\Controllers\Status;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class BridasController extends Controller
{
    /**
     * Obtiene todas las bridas registradas
     */
    public function Obtener()
    {
        try
        {
            $bridas = Brida::orderBy("clase")->get();
            return Status::Success("bridas", $bridas);
        }
        catch (Exception  $e)
        {
            dd($e);
            return Status::Error($e, "obtener las bridas");
        }
    }

    /**
     * Guarda una brida con los datos ingresados
     */
    public function Guardar(Request $request)
    {
        try
        {
            if (!$request->hasFile("img_brida"))
                return response()->json(["status" => false, "mensaje" => "Imágen no encontrada"]);

            // Registrar
            if ($request->id == null)
            {
                $brida = new Brida($request->all());
                $brida->imagen = "-";
                $brida->save();
            }
            else
            {
                $brida = Brida::find($request->id);
                $brida->fill($request->all());
                $brida->imagen = "-";
                $brida->update();
            }

            // Guardar imagen
            $img = $request->file("img_brida");
            $nombre = $img->getClientOriginalName();
            $aux_nombre = $brida->id . DIRECTORY_SEPARATOR . $nombre;
            $ruta_documento = "Archivos/Bridas/" . $aux_nombre; // Path de la imagen
            Storage::disk('local')->put($ruta_documento, fopen($img, 'r+'));
            $brida->imagen = $nombre;
            $brida->update();

            return Status::Success();
        }
        catch (Exception $e)
        {
            dd($e);
            return Status::Error($e, "guardar la brida");
        }
    }
}
