<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Utilidades;
use App\UnidadMedida;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnidadesMedidaController extends Controller
{

    /**
     * Obtiene todas las unidades de medida existentes
     */
    public function Obtener()
    {
        $unidades = UnidadMedida::get();
        return response()->json(["status" => true, "unidades" => $unidades]);
    }
    
    /**
     * Obtiene todas las unidades de medida existentes
     */
    public function ObtenerDescripcion()
    {
        $unidades = UnidadMedida::select("id","clave",DB::raw("CONCAT(clave,' - ',nombre) as nombre"))->get();
        return response()->json(["status" => true, "unidades" => $unidades]);
    }

    /**
     * Crea una nueva unidad de medida
     */
    public function Guardar(Request $request)
    {
        try
        {
            $unidad = new UnidadMedida();
            $unidad->fill($request->all());
            $unidad->save();
            return response()->json(["status" => true]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return response()->json(["status" => false]);
        }
    }

    /**
     * Actualiza la unidad de medida ingresada
     */
    public function Actualizar(Request $request)
    {
        try
        {
            $unidad = UnidadMedida::find($request->id);
            $unidad->fill($request->all());
            $unidad->update();
            return response()->json(["status" => true]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return response()->json(["status" => false]);
        }
    }
}
