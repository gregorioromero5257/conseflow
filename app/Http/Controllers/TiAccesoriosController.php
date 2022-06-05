<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Utilidades;
use App\TiAccesorio;
use Exception;
use Illuminate\Http\Request;

class TiAccesoriosController extends Controller
{

    /**
     * Obtiene todos los accesorios registrados
     */
    public function Obtener()
    {
        try
        {
            $accesorios = TiAccesorio::where('eliminado','1')->get();
            return response()->json(["status" => true, "accesorios" => $accesorios]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return response()->json(["status" => false]);
        }
    }

    /**
     * Obtiene todos los accesorios registrados
     */
    public function ObtenerActivos()
    {
        try
        {
            $accesorios = TiAccesorio::
            where('cantidad','>','0')
            ->where('eliminado','1')
            ->get();

            return response()->json(["status" => true, "accesorios" => $accesorios]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return response()->json(["status" => false]);
        }
    }

    /**
     * Registra un nuevo equipo de cómputo
     */
    public function Guardar(Request $request)
    {
        try
        {
            $existe = TiAccesorio::find($request->id);
            if ($existe == null)
            {
                // nueva
                $nuevo = new TiAccesorio($request->all());
                Utilidades::auditar($nuevo, 1);
                $nuevo->save();
            }
            else
            {
                $existe->fill($request->all());
                Utilidades::auditar($existe, $existe->id);
                $existe->update();
            }
            return response()->json(["status" => true]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return response()->json(["status" => false,"mensaje"=> "Error al guardar el accesorio"]);
        }
    }

    /**
     * Registra un nuevo equipo de cómputo
     */
    public function Actualizar(Request $request)
    {
        try
        {
            $equipo = TiAccesorio::find($request->id);
            $equipo->fill($request->all());
            $equipo->update();
            return response()->json(["status" => true]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return response()->json(["status" => false]);
        }
    }

    public function Activar(Request $request)
    {
        try
        {
            $equipo = TiAccesorio::find($request->id);
            $equipo->condicion = $request->condicion;
            $equipo->update();
            return response()->json(["status" => true]);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false]);
            Utilidades::errors($e);
        }
    }

    public function Eliminar($id)
   {
     try {
       $computo = TiAccesorio::where('id',$id)->first();
       $computo->eliminado = 0;
       Utilidades::auditar($computo, $computo->id);
       $computo->save();

       return response()->json(['status' => true]);
     } catch (\Throwable $e) {
       Utilidades::errors($e);
     }
   }
}
