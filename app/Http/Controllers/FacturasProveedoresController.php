<?php

namespace App\Http\Controllers;

use App\Compras;
use App\FacturaProveedor;
use App\Http\Helpers\Utilidades;
use App\PagosNoRecurrentes;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class FacturasProveedoresController extends Controller
{

    /*
    -- Estados de FacturaProveedor
    0. Rechazado
    1. Aprobación de Almacén
    2. Aprobación de Calidad
    3. Asignación de fecha de pago
    4. Autorización de pago
    5. Pendiente de pago
    6. Pagado
    */

    /**
     * Obtiene los pagos pendientes por revisar
     */
    public function ObtenerPagos()
    {
        try
        {
            $pagos = DB::table("facturas_proveedores as fp")
                ->join("proveedores as p", "p.id", "fp.proveedor_id")
                ->join("ordenes_compras as oc", "oc.id", "fp.oc_id")
                ->where("fp.estado", 0)
                ->select(
                    "fp.id as fp_id",
                    "p.nombre as proveedor",
                    "fp.fecha_carga",
                    "fp.estado",
                    "fp.fecha_programada",
                    "oc.folio"
                )
                ->orderBy("fecha_carga")
                ->orderBy("proveedor")
                ->get();

            return response()->json(["status" => true, "pagos" => $pagos]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return response()->json(["status" => false, "mensaje" => "Intente más tarde"]);
        }
    }

    /**
     * Asigna una fecha de pago a la factura del proveedor
     */
    public function AsignarFecha(Request $request)
    {
        try
        {
            DB::table("facturas_proveedores as fp")->where("id", $request->fp_id)
                ->update(
                    [
                        "fecha_programada" => $request->fecha,
                        "estado" => 4
                    ]
                );
            return response()->json(["status" => true]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return response()->json(["status" => false, "mensaje" => "Intente más tarde"]);
        }
    }

    /**
     * Autoriza el pago de la factura del proveedor (Dirección Financiera)
     */
    public function Autorizar(Request $request)
    {
        try
        {
            DB::beginTransaction();
            // Autorizar pago
            $factura = FacturaProveedor::find($request->id);
            $factura->estado = 5;
            $oc = Compras::find($factura->oc_id);

            // Pasar a pagos no recurretes
            $pago = new PagosNoRecurrentes();
            $pago->proveedor_id = $factura->proveedor_id;
            $pago->ordenes_comp_id = $oc->id;
            $pago->proyecto_id = $oc->proyecto_id;
            $pago->monto = str_replace(",", "", $oc->total);
            $pago->condicion = 4;
            $pago->rango_dias = -99;
            $pago->fecha_autorizado = $factura->fecha_programada;
            $pago->save();

            Utilidades::auditar($factura, $factura->id);
            $factura->save();
            DB::commit();
            return response()->json(["status" => true]);
        }
        catch (Exception $e)
        {
            DB::rollBack();
            Utilidades::errors($e);
            return response()->json(["status" => false, "mensaje" => "Intente más tarde"]);
        }
    }

    /**
     * Obtiene las patias_facturas pendientes por autorizar
     */
    public function ObtenerFacturasPorAutorizar()
    {
        return $this->AuxObtenerFacturasProgramadas("=", 1);
    }

    /**
     * Obtiene las patias_facturas pendientes por autorizar
     */
    public function ObtenerFacturasProgramadas()
    {
        return $this->AuxObtenerFacturasProgramadas("!=", 0);
    }

    /**
     * Obtiene las factudas con el estado inngresado
     */
    public function AuxObtenerFacturasProgramadas($val)
    {
        $dts = explode("&", $val);
        $comparador = $dts[0];
        $estado = $dts[1];
        try
        {
            $pagos = DB::table("facturas_proveedores as fp")
                ->join("proveedores as p", "p.id", "fp.proveedor_id")
                ->join("ordenes_compras as oc", "oc.id", "fp.oc_id")
                ->join("proyectos as pr", "pr.id", "oc.proyecto_id")
                ->join("empleados as e1", "e1.id", "oc.autoriza_empleado_id")
                ->join("empleados as e2", "e2.id", "oc.elabora_empleado_id")
                ->where("fp.estado", $comparador, $estado)
                ->select(
                    "fp.id as fp_id",
                    "p.nombre as proveedor",
                    "oc.folio",
                    "oc.prioridad",
                    "oc.condicion_pago_id",
                    "oc.moneda",
                    "oc.periodo_entrega",
                    "pr.nombre_corto",
                    "oc.total",
                    "fp.fecha_programada",
                    "fp.estado",
                    "fp.fecha_carga",
                    "oc.id as oc_id",
                    "fp.mensaje",
                    DB::raw("concat_ws(' ',e1.nombre,e1.ap_paterno,e1.ap_materno) as nombre_empleado_autoriza"),
                    DB::raw("concat_ws(' ',e2.nombre,e2.ap_paterno,e2.ap_materno) as nombre_empleado_elabora")
                )
                ->get();
            return response()->json(["status" => true, "pagos" => $pagos]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return response()->json(["status" => false, "mensaje" => "Error. Intente más tarde"]);
        }
    }

    /**
     * Cambia la factura al estado ingresado
     */
    public function CambiarEstado(Request $request)
    {
        try
        {
            DB::table("facturas_proveedores as fp")->where("id", $request->id)
                ->update(
                    [
                        "estado" => $request->estado,
                        "mensaje" => $request->mensaje,
                    ]
                );
            return response()->json(["status" => true]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return response()->json(["status" => false, "mensaje" => "Error. Intente más tarde"]);
        }
    }
}
