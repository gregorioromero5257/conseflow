<?php

namespace App\Http\Controllers;

use App\TiMaterialResguardo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class RetornoTIController extends Controller
{
    /**
     * Obtiene todos los resguardos de TI
     */
    public function ObtenerResguardosEmp()
    {
        try
        {
            // Obtener los resguardos
            $resguardos = DB::table("ti_material_resguardo as tmr")
                ->join("empleados as e1", "e1.id", "tmr.empleado_entrega")
                ->join("empleados as e2", "e2.id", "tmr.empleado_recibe")
                ->where("estado",1)
                ->select(
                    "tmr.*",
                    DB::raw("CONCAT_WS(' ',e1.nombre,e1.ap_paterno,e1.ap_materno) as entrega"),
                    DB::raw("CONCAT_WS(' ',e2.nombre,e2.ap_paterno,e2.ap_materno) as recibe")
                )
                ->get();
            $array_resguardos = [];
            foreach ($resguardos as $resg)
            {
                $tablas = ["ti_computo", "ti_accesorios", "ti_impresoras", "ti_video", "ti_red"];
                $tbl = $tablas[$resg->tipo - 1];

                if ($resg->tipo == 1) // Computo
                    $campos = "CONCAT_WS(' ',no_serie,marca_modelo,cpu,ram,almacenamiento,tarjeta_video,tarjeta_red,observaciones,mac) as descripcion";
                else if ($resg->tipo == 5) // Red
                    $campos = "CONCAT_WS(' ',descripcion,marca,no_serie,mac) as descripcion";
                else // Otros
                    $campos = "CONCAT_WS(' ',descripcion,modelo,marca,no_serie) as descripcion";

                $query = "SELECT " . $campos . " FROM " . $tbl . " as tbl WHERE id = " . $resg->caiv;
                $equipos = DB::select($query);
                $aux = (array)$resg;
                $aux["equipo"] = $equipos[0]->descripcion;
                $array_resguardos[] = $aux;
            }
            return Status::Success("equipos", $array_resguardos);
        }
        catch (Exception $e)
        {
            dd($e);
            return Status::Error($e, "obtener los resguardos");
        }
    }

    /**
     * Registra el retorno de un resguardo de TI
     */
    public function Regresar(Request $request)
    {
        try
        {
            DB::beginTransaction();
            // Liberar resguardo
            $resguardo = TiMaterialResguardo::find($request->resguardo_id);
            $resguardo->estado = 2; // Liberado
            $resguardo->update();
            // Actualizar inventario
            $tablas = ["ti_computo", "ti_accesorios", "ti_impresoras", "ti_video", "ti_red"];
            $tbl = $tablas[$resguardo->tipo - 1];
            // Buscar equipo en inventario
            $n = DB::table($tbl)->where("id", $resguardo->caiv)->first()->cantidad;

            $equipo = DB::table($tbl)->where("id", $resguardo->caiv)
                ->update([
                    "cantidad" => ($n + $resguardo->cantidad),
                    "condicion" => 1
                ]);
            DB::commit();
            return Status::Success();
        }
        catch (Exception $e)
        {
            DB::rollBack();
            dd($e);
            return Status::Error($e, "registrar retorno del equipo");
        }
    }
}
