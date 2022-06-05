<?php

namespace App\Http\Controllers\Calidad;

use Illuminate\Http\Request;
use App\Accesorio;
use App\CalidadModels\InspeccionVisual;
use App\ColadaInspeccion;
use App\Http\Controllers\Controller;
use App\Inspeccion;
use App\InspeccionCalidad;
use App\Proyecto;
use App\RimeCalidad;
use Exception;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Http\Helpers\Utilidades;
use App\Proveedor;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;

class InspeccionesController extends Controller
{

    public function Reportes($rime_id)
    {
        try
        {
            $reportes = DB::table("inspecciones_calidad as i")
                ->join('rime_calidad AS rc', 'rc.id', 'i.rime_calidad_id')
                ->leftJoin('empleados AS er', 'er.id', 'rc.empleado_reviso')
                ->leftJoin('empleados AS ee', 'ee.id', 'rc.empleado_enterado')
                ->leftJoin('empleados AS ea', 'ea.id', 'rc.empleado_aprobo')
                ->join("articulos as a", "i.articulo_id", "a.id")
                ->select(
                    "i.*",
                    "a.descripcion as material",
                    'rc.fecha',
                    'rc.no_proyecto',
                    'rc.factura',
                    'rc.empleado_reviso AS emp_rev_id',
                    'rc.empleado_aprobo AS emp_apr_id',
                    'rc.empleado_enterado AS emp_ent_id',
                    DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS emp_rev_name"),
                    DB::raw("CONCAT(ee.nombre,' ',ee.ap_paterno,' ',ee.ap_materno) AS emp_ent_name"),
                    DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS emp_apr_name")
                )
                ->where("i.rime_calidad_id", $rime_id)->get();

            // Obtener las coladas de la inspección
            $inspecciones = [];
            foreach ($reportes as $inspeccion)
            {
                $coladas = DB::table("coladas_inspeccion")->where("inspeccion_id", $inspeccion->id)->get();
                $temp = (array)$inspeccion;
                $temp["coladas"] = $coladas;
                array_push($inspecciones, (object)$temp);
            }
            // $reportes=InspeccionCalidad::where("proyecto_id",$proyecto_id)->get();
            return response()->json(["status" => true, "reportes" => $inspecciones]);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }
    // Obtiene todos los proyectos activos
    public function Proyectos()
    {
        try
        {
            $proyectos = Proyecto::where("condicion", 1)
                ->orderBy("nombre_corto")
                ->select("id", "nombre_corto")->get();
            return response()->json(["status" => true, "proyectos" => $proyectos]);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }

    // Obtiene todas las OC's del proyecto ingresado
    public function ObtenerOCS($proyecto_id)
    {
        try
        {
            $ocs = DB::table("ordenes_compras")
                ->where("proyecto_id", "=", $proyecto_id)
                ->orderBy("folio")
                ->select("id", "folio")
                ->get();
            return response()->json(["status" => true, "oc" => $ocs]);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }

    // Obtiene las partidas (articulos) de la OC ingresada
    public function ObtenerPartidasOC($data)
    {
        try
        {
            $dts = explode("&", $data);
            if (count($dts) == 1)
            {
                // Obtener datos de la OC
                $partidas = DB::table("requisicion_has_ordencompras as rho")
                    ->join("articulos as a", "a.id", "rho.articulo_id")
                    ->where("rho.orden_compra_id", "=", $dts[0]) // OC id
                    ->where("rho.orden_compra_id", "!=", "''")
                    ->select(
                        "a.id as articulo_id",
                        "a.descripcion",
                        "rho.id as rho_id",
                        "a.marca",
                        "rho.cantidad",
                        "rho.orden_compra_id as oc_id"
                    )
                    ->get();
            }
            else
            {
                // Obter datos de OC temporal
                $partidas = DB::table("requisiciones as r")
                    ->join("partidas_requisiciones as pr", "pr.requisicione_id", "r.id")
                    ->join("articulos as a", "a.id", "pr.articulo_id")
                    ->where("r.proyecto_id", $dts[1]) // proyecto_id
                    ->where("pr.cantidad_almacen", ">", 0)
                    ->select(
                        "a.id as articulo_id",
                        "a.descripcion",
                        DB::raw("'-99' as rho_id"),
                        "a.marca",
                        "pr.cantidad_almacen as cantidad",
                        DB::raw("'-99' as oc_id")
                    )
                    ->orderBy("a.descripcion")->get();
            }
            return response()->json(["status" => true, "articulos" => $partidas]);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }

    // Obtiene las rimes de calidad del Proyecto ingresado
    public function ObtenerRimes($proyecto_id)
    {
        try
        {
            $ocs = DB::table("rime_calidad as r")
                ->leftJoin('empleados AS er', 'er.id', 'r.empleado_reviso')
                ->leftJoin('empleados AS ee', 'ee.id', 'r.empleado_enterado')
                ->leftJoin('empleados AS ea', 'ea.id', 'r.empleado_aprobo')
                ->leftJoin("ordenes_compras as oc", "oc.id", "r.oc_id")
                ->where("r.proyecto_id", "=", $proyecto_id)
                ->orderBy("folio_rime")
                ->select(
                    "r.id",
                    "r.folio as folio_rime",
                    "oc.folio as oc_folio",
                    "oc.id as oc_id",
                    DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_reviso_n"),
                    DB::raw("CONCAT(ee.nombre,' ',ee.ap_paterno,' ',ee.ap_materno) AS empleado_enterado_n"),
                    DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_aprobo_n"),
                    'r.empleado_reviso',
                    'r.empleado_enterado',
                    'r.empleado_aprobo',
                    'r.fecha',
                    'r.no_proyecto',
                    'r.factura'
                )
                ->get();
            return response()->json(["status" => true, "rimes" => $ocs]);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }

    /**
     * Guarda la rime con los datos ingresados
     */
    public function Guardar(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $data = $request->all();
            $inspeccion = null;
            // Registrar nuevo
            if ($request->rime_id == "null" || $request->rime_id == null)
            {
                // return response()->json(["status"=> false,"mensaje"=>"nuevo"]);
                // Crear nuevo Rime
                $rime = new RimeCalidad();
                $rime->proyecto_id = $request->proyecto_id;
                $rime->oc_id = $request->oc_id;
                $rime->folio = $request->folio;
                $rime->fecha = $request->fecha;
                $rime->no_proyecto = $request->no_proyecto;
                $rime->factura = $request->factura;
                $rime->empleado_aprobo = $request->aprobo;
                $rime->empleado_enterado = $request->enterado;
                $rime->empleado_reviso = $request->reviso;
                Utilidades::auditar($rime, $rime->id);
                $rime->save();

                // Guardar inspección
                $inspeccion = new InspeccionCalidad($data);
                $inspeccion->rime_calidad_id = $rime->id;
                Utilidades::auditar($inspeccion, $inspeccion->id);
                $inspeccion->save();
            }
            else
            {
                // return response()->json(["status"=> false,"mensaje"=>"actu"]);
                $rime = RimeCalidad::find($request->rime_id);
                $rime->proyecto_id = $request->proyecto_id;
                $rime->oc_id = $request->oc_id;
                $rime->fecha = $request->fecha;
                $rime->no_proyecto = $request->no_proyecto;
                $rime->factura = $request->factura;
                $rime->empleado_aprobo = $request->aprobo;
                $rime->empleado_enterado = $request->enterado;
                $rime->empleado_reviso = $request->reviso;
                Utilidades::auditar($rime, $rime->id);
                $rime->update();

                $inspeccion = InspeccionCalidad::find($request->id);
                if ($inspeccion == null)
                {
                    $inspeccion = new InspeccionCalidad($data);
                    Utilidades::auditar($inspeccion, 0);
                    $inspeccion->rime_calidad_id = $rime->id;
                    $inspeccion->save();
                }
                else
                {

                    $inspeccion->fill($data);
                    Utilidades::auditar($inspeccion, $inspeccion->id);
                    $inspeccion->update();
                }
            }

            // Guardar documento
            if ($request->hasFile('certificado'))
            {
                $doc = $request->file("certificado");
                $nombre = $doc->getClientOriginalName();
                $hoy_aux = date("y_m_d_h_i_s");
                $aux_nombre = $hoy_aux . "_" . $inspeccion->articulo_id . "_" . $nombre;
                $ruta_documento = "Archivos/Rime_Calidad/" . $rime->id . "/" . $aux_nombre;
                Storage::disk('local')->put($ruta_documento, fopen($doc, 'r+'));
                $inspeccion->certificado_pdf = $aux_nombre;
                $inspeccion->save();
            }
            // Coladas
            $coladas_aux = $request->coladas;
            $cantidades_aux = $request->cantidades;
            $ids_aux = $request->ids;

            $ids = array_filter(explode("|", $ids_aux));
            $cantidades = array_filter(explode("|", $cantidades_aux));
            $coladas = array_filter(explode("|", $coladas_aux));

            $n = count($ids);
            for ($i = 0; $i < count($ids); $i++)
            {
                if ($ids[$i] == "null") // Nueva
                {
                    $colada = new ColadaInspeccion();
                    $colada->inspeccion_id = $inspeccion->id;
                    $colada->cantidad = $cantidades[$i];
                    $colada->colada = $coladas[$i];
                    $colada->save();
                }
                else // Actu
                {
                }
            }
            DB::commit();
            // DB::rollBack();
            return response()->json(["status" => true, 'rime' => $rime, "n" => $n]);
        }
        catch (Exception $e)
        {
            DB::rollBack();
            //dd($e);
            Utilidades::errors($e);
            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }

    /**
     * Obtiene el siguiente folio
     */
    public function ObtenerFolio($p_id)
    {
        try
        {
            $folio = $this->Folio($p_id);
            return response()->json(["status" => true, "folio" => $folio]);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mensaje" => "Error al obtener el folio"]);
        }
    }

    // Obtiene el folio de la rime
    public function Folio($proyecto_id)
    {
        try
        {
            // total de rimes del proyecto +1
            $n = count(RimeCalidad::where("proyecto_id", $proyecto_id)->get());
            $nombre = DB::table("proyectos")->where("id", $proyecto_id)->first()->nombre_corto;
            $folio = "RIME-$nombre-" . str_pad(($n + 1), 4, "0", STR_PAD_LEFT);
            return $folio;
        }
        catch (Exception $e)
        {
            throw new Exception("ERROR FOLIO " . $e->getMessage());
        }
    }

    // Descargar Formato de Rime
    public function Descargar($id)
    {
        $rime = DB::table('rime_calidad AS rc')
            ->join('proyectos AS p', 'p.id', 'rc.proyecto_id')
            ->leftJoin('ordenes_compras AS oc', 'oc.id', 'rc.oc_id')
            ->leftJoin('proveedores AS pr', 'pr.id', 'oc.proveedore_id')
            ->join('empleados AS er', 'er.id', 'rc.empleado_reviso')
            ->join('empleados AS ee', 'ee.id', 'rc.empleado_enterado')
            ->join('empleados AS ea', 'ea.id', 'rc.empleado_aprobo')
            ->select(
                'rc.*',
                'oc.folio AS folio_oc',
                'pr.razon_social',
                'p.nombre_corto',
                DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS reviso"),
                DB::raw("CONCAT(ee.nombre,' ',ee.ap_paterno,' ',ee.ap_materno) AS enterado"),
                DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS aprobo")
            )
            ->where('rc.id', $id)
            ->first();
        // Comprobar datos de la OC que no sea EP
        if (str_contains($rime->folio_oc, "EP-OC"))
        {
            // OC no capturada
            // Buscar en otra tabla
            $ep = DB::table("entradas_pendientes as ep")
                ->join("requisicion_has_ordencompras as rho", "rho.id", "ep.rhoc_id")
                ->join("ordenes_compras AS oc", "oc.id", "rho.orden_compra_id")
                ->select("ep.*")
                ->where("oc.folio", $rime->folio_oc)->first();
            //  dd($ep);
            // Comprobar que ya esté la OC relacionada
            $proveedor = Proveedor::where("id", $ep->proveedor_id)->first()->razon_social;
            $rime->razon_social = $proveedor;
            if ($ep->folio != null)
            {
                // obtener folio y provedor real
                // dd($proveedor);
                $folio = $ep->folio;
                $rime->folio_oc = $folio;
            }
        }

        // 2020-01-01
        $dia = substr($rime->fecha, 8, 2);
        $mes = substr($rime->fecha, 5, 2);
        $anio = substr($rime->fecha, 0, 4);

        $fecha = $dia . '/' . $mes . '/' . $anio;

        $ic = DB::table('inspecciones_calidad AS ic')
            ->join('articulos AS a', 'a.id', 'ic.articulo_id')
            ->select('ic.*', 'a.descripcion')
            ->where('ic.rime_calidad_id', $id)->get();

        $pdf = PDF::loadView('pdf.rime', compact('rime', 'fecha', 'ic'));
        //  $pdf->setPaper('A4', 'landscape');
        $pdf->setPaper('ledger', 'portrait');
        // $pdf->setPaper("A4", "portrait");
        // return $pdf->download('cv-interno.pdf');
        error_reporting(E_ALL ^ E_DEPRECATED);
        return $pdf->stream($rime->folio);
    }

    /**
     * Descarga el certificado cargado anteriormente, en caso de que exista
     */
    public function DescargarCertificado($id)
    {
        // obtener rime
        $inspeccion = DB::table("rime_calidad as rc")
            ->join("inspecciones_calidad as ic", "rc.id", "ic.rime_calidad_id")
            ->select("ic.certificado_pdf", "rc.id as rc_id")
            ->where("ic.id", $id)
            ->first();

        //21_01_04_05_42_19_4180_document(22).pdf
        //21_01_04_05_44_54_4165_document(22).pdf
        if ($inspeccion != null)
        {
            $ruta_documento = "Archivos/Rime_Calidad/" . $inspeccion->rc_id . "/" . $inspeccion->certificado_pdf;

            $path = storage_path("app/" . $ruta_documento);
            if (file_exists($path) && $inspeccion->certificado_pdf != null)
            {
                $aux = explode("_", $inspeccion->certificado_pdf);
                $nombre = $aux[count($aux) - 1];

                return response()->download($path, $nombre, [
                    'Content-Type' => "application/pdf",
                    'Content-Disposition' => 'inline; filename="' . $nombre . '"'
                ]);
            }
        }
        return view('errors.404');
    }

    /**
     * Busca las rimes del proyecto ingresado de acuerto al material ingresado
     */
    public function Buscar($data)
    {
        try
        {
            $dts = explode("&", $data);
            if (count($dts) < 2)
                return response()->json(["status" => false, "mensaje" => "Datos incompletos"]);
            $proyecto_id = $dts[0];
            $busacar = $dts[1];

            $inspecciones = DB::table("rime_calidad as r")
                ->leftJoin("empleados as er", "er.id", "r.empleado_reviso")
                ->leftJoin("empleados as ee", "ee.id", "r.empleado_enterado")
                ->leftJoin("empleados as ea", "ea.id", "r.empleado_aprobo")
                ->leftJoin("ordenes_compras as oc", "oc.id", "r.oc_id")
                ->join("inspecciones_calidad as ic", "ic.rime_calidad_id", "r.id")
                ->join("articulos as a", "a.id", "ic.articulo_id")
                ->where("r.proyecto_id", $proyecto_id)
                ->where("a.descripcion", "like", "%" . $busacar . "%")
                ->select(
                    "r.id",
                    "r.folio as folio_rime",
                    "oc.folio as oc_folio",
                    "oc.id as oc_id",
                    DB::raw("CONCAT_WS(' ',er.nombre,er.ap_paterno, er.ap_materno) AS empleado_reviso_n"),
                    DB::raw("CONCAT_ws(' ',ee.nombre,ee.ap_paterno,ee.ap_materno) AS empleado_enterado_n"),
                    DB::raw("CONCAT_ws(' ',ea.nombre,ea.ap_paterno,ea.ap_materno) AS empleado_aprobo_n"),
                    "r.empleado_reviso",
                    "r.empleado_enterado",
                    "r.empleado_aprobo",
                    "r.fecha",
                    "r.no_proyecto",
                    "r.factura",
                    "a.descripcion"
                )->get()->toArray();

            return response()->json(["status" => true, "inspecciones" => $inspecciones]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return response()->json(["status" => false, "mensaje" => "Error al obtener las Rimes"]);
        }
    }
}
