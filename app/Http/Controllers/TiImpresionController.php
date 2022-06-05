<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Utilidades;
use App\TiImpresion;
use Exception;
use Hamcrest\Util;
use Illuminate\Http\Request;

class TiImpresionController  extends Controller
{

    /**
     * Obtiene todas las impresoras registradas
     */
    public function Obtener()
    {
        $imporesoras = TiImpresion::where('eliminado','1')->get();
        return response()->json(["status" => true, "impresoras" => $imporesoras]);
    }

    /**
     * Actualiza o registra una impresora
     */
    public function Guardar(Request $request)
    {
        try
        {
            $existe = TiImpresion::find($request->id);
            if ($existe == null)
            {
                // nueva
                $nuevo = new TiImpresion($request->all());
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
            return response()->json(["status" => false, "mensaje" => "Error al guardar la impresora"]);
        }
    }

    /**
     * Cambia la condición de la impresora ingresada ?? Cambiar por Guardar y mandar estado
     */
    public function Activar(Request $request)
    {
        try
        {
            $imp = TiImpresion::find($request->id);
            $imp->condicion = $request->condicion;
            Utilidades::auditar($imp, $imp->id);
            $imp->update();
            return response()->json(["status" => true]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return response()->json(["status" => false, "mensaje" => "Error al actualizar la impresora"]);
        }
    }

    public function Eliminar($id)
    {
      try {
        $computo = TiImpresion::where('id',$id)->first();
        $computo->eliminado = 0;
        Utilidades::auditar($computo, $computo->id);
        $computo->save();

        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }
}
