<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Utilidades;
use App\TiComputo;
use Exception;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;


class TiComputoController extends Controller
{

    /**
     * Obtiene todos los equipos de computo registrados
     */
    public function Obtener($id)
    {
        try
        {
            $equipos = TiComputo::where('empresa',$id)->where('eliminado','1')->get();
            return response()->json(["status" => true, "equipos" => $equipos]);
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
    public function Registrar(Request $request)
    {
        try
        {
            $equipo = new TiComputo();
            $equipo->fill($request->all());
            $equipo->save();
            return response()->json(["status" => true]);
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
    public function Actualizar(Request $request)
    {
        try
        {
            $equipo = TiComputo::find($request->id);
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
        // try
        // {
            $equipo = TiComputo::where('id',$request->id)->first();
            $equipo->condicion = $equipo->condicion == 0 ? 1 : 0;
            $equipo->update();
            return response()->json(["status" => true]);
        // }
        // catch (Exception $e)
        // {
            // return response()->json(["status" => false]);
            // Utilidades::errors($e);
        // }
    }

    public function Descargar($id)
    {
      try {

        $arreglo_v = \App\TiComputo::
        where('empresa',$id)
        // ->where('condicion','1')
        ->get();
	//dd($arreglo_v);
        $arreglo = [];

        foreach ($arreglo_v as $key => $value) {

          $material = DB::table('ti_material_resguardo')
          ->leftJoin('empleados AS e','e.id','ti_material_resguardo.empleado_recibe')
          ->select('ti_material_resguardo.*',DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS nom_usr"))
          ->where('tipo','1')
          ->where('caiv',$value->id)
          ->first();
          $estado = '';
          if ($value->condicion == 0) {
            $estado = 'Inactivo';
          }elseif ($value->condicion == 1) {
            $estado = 'Activo';
          }elseif ($value->condicion == 2) {
            $estado = 'Resguardo';
          }elseif ($value->condicion == 3) {
            $estado = 'Sitio';
          }



          //if (isset($material) == true) {
            $arreglo [] = [
              'computo' => $value,
              'resguardo' => $material,
              'estado' => $estado,
            ];
          //}

        }
	//return response()->json($arreglo);
        if ($id == 1) {
          $pdf = PDF::loadView('pdf.invcompcsct', compact('arreglo'));
        }elseif ($id == 2) {
          $pdf = PDF::loadView('pdf.invcompcsct', compact('arreglo'));
        }

        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A2', 'portrait');
        return $pdf->stream();

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }

    }

    public function Eliminar($id)
    {
      try {
        $computo = TiComputo::where('id',$id)->first();
        $computo->eliminado = 0;
        Utilidades::auditar($computo, $computo->id);
        $computo->save();

        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }
}
