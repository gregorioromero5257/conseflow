<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Status;
use App\Http\Helpers\Utilidades;
use App\RHControlTiempoEmp;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical;
use PhpParser\Node\Stmt\Static_;

class RHControlTiempoEmpController extends Controller
{

    /**
     * Obtener los registros
     */
    public function Obtener()
    {
        try
        {
            $controles = DB::table("rhcontroltiempo_emp as rt")
                ->join("empleados as e", "e.id", "rt.empleado_id")
                ->join("proyectos as p", "p.id", "rt.proyecto_id")
                ->where("rt.condicion", 1) //activos
                ->select(
                    "rt.*",
                    DB::raw("CONCAT_WS(' ',e.nombre,e.ap_paterno,e.ap_materno) as empleado"),
                    "p.nombre_corto as proyecto"
                )->get();
            return Status::Success("controles", $controles);
        }
        catch (Exception $e)
        {
            dd($e);
            return Status::Error($e, "obtener los registros");
        }
    }

    /**
     * ELiminar
     */
    public function Eliminar(Request $request)
    {
        try
        {
            $asd = RHControlTiempoEmp::find($request->id);
            $asd->condicion = 0;
            $asd->update();
            return Status::Success();
        }
        catch (Exception $e)
        {
            return Status::Error($e, "eliminar");
        }
    }
    /**
     * Registrar nuevo control
     */
    public function Guardar(Request $request)
    {
        $asd = [];
        try
        {
            if ($request->id == null)
            {
                $dts1 = explode("|", $request->datos); // Filas
                foreach ($dts1 as $registro)
                {
                    if ($registro == "") continue;
                    $asd = $registro;
                    // Datos
                    $dts = explode("&", $registro);
                    $control = new RHControlTiempoEmp();
                    $control->empleado_id = $dts[0];
                    $control->proyecto_id = $dts[1];
                    $control->fecha = $dts[2];
                    $control->horas = $dts[3];
                    Utilidades::auditar($control, 0);
                    $control->save();
                }
            }
            else
            {
                return "asd";
                $control = RHControlTiempoEmp::find($request->id);
                Utilidades::auditar($control, $control->id);
                $control->update();
            }
            return Status::Success();
        }
        catch (Exception $e)
        {
            // print_r($asd);
            dd($asd);
            return Status::Error($e, "guardar el control");
        }
    }
}
