<?php

namespace App\Http\Controllers\APIControllers;

use App\APIModels\Response;
use App\FacturaProveedor;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Utilidades;
use App\OpinionSatProveedor;
use App\PartidaFacturaProveedor;
use App\Proveedor;
use App\RFCSatProveedor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PharIo\Manifest\Email;

class ProveedoresController extends Controller
{
    protected $SERVER_TOKEN = "*9V&pCMQ!t4J3Cr#ayQA!&ZxWWrU9othZUda7l^";

    protected $PATH_ROOT = "Archivos/FacturasProveedores/";

    // Linux
    protected $TMP_PATH = "/opt/docker/webConserflow/test/miweb/proveedores/data/tmp/";
    // Windows
    //protected $TMP_PATH = "C:\\wamp64\\tmp\\";

    public function Test()
    {
        //$files = scandir("/opt/docker/webConserflow/test/miweb/proveedores/data/tmp/");
        //print_r(count($files));
        return response()->json(new Response(200, "Ok", "-", true));
    }

    /**
     * Registra un nuevo proveedor
     */
    public function RegistrarProveedor(Request $request)
    {
        $rfc = $request->rfc;
        try
        {
            $token_client = $request->token;
            $contra = $request->contra;
            $email = $request->email;
            $nombre = $request->nombre;
            $token_client = $request->token;
            if (!($token_client === $this->SERVER_TOKEN))
            {
                return response()->json(new Response(505, "ERROR 403. Unauthorized Access", "", false));
            }
            // Validar que el proveedore exista en el sistema
            $proveedor = Proveedor::where("rfc", $rfc)->first();
            if ($proveedor == null)
            {
                if ($request->nuevo == "1") // Registrar nuevo
                {
                    if ($nombre == null)
                        return response()
                            ->json(new Response(202, "Ingrese la razón social.", null, true));
                    // No existe. Registrar nuevo proveedor
                    $nuevo = new Proveedor();
                    $nuevo->rfc = $rfc;
                    $nuevo->nombre=$nombre;
                    $nuevo->razon_social=$nombre;
                    $nuevo->correo = $email;
                    $nuevo->portal_existe = true;
                    $nuevo->portal_correo = $email;
                    $nuevo->passw = $contra;
                    $nuevo->condicion_aceptado = 0;
                    $nuevo->save();
                    return response()->json(new Response(200, "Proveedor registrado", "", true));
                }
                return response()
                    ->json(new Response(202, "Proveedor desconocido", null, true));
            }
            else
            {
                //
                if ($proveedor->portal_existe)
                {
                    return response()->json(new Response(203, "Proveedor ya registrado", null, true));
                }
                $proveedor->portal_existe = true;
                $proveedor->portal_correo = $email;
                $proveedor->passw = $contra;
                $proveedor->condicion_aceptado = 0;
                $proveedor->update();
            }

            return response()->json(new Response(200, "OK", null, true));
        }
        catch (Exception $e)
        {
            Utilidades::errors($e, 2, $rfc);
            return response()->json(new Response(500, "Error al registar proveedor", null, false));
        }
    }

    /**
     * Ingresar con RFC
     */
    public function Ingresar(Request $request)
    {
        try
        {
            if ($request->rfc == null || $request->contra == null)
                return response()->json(new Response(501, "Error", "Datos incompletos", false));
            $proveedor = DB::table("proveedores")->where("rfc", $request->rfc)
                ->where("portal_existe", 1) // Registrado
                ->where("passw", $request->contra)  //Contra
                ->select("id", "rfc as RFC", "nombre as Name", "condicion_aceptado as aceptado")
                ->first();
            if ($proveedor == null)
            {
                return response()->json(new Response(404, "RFC o contraseña incorrectos", null, true));
            }
            else
            {
                // Notificación de RFC & Opinión
                $mensaje_opinion = "";
                $opinion = DB::table("validacion_opinion_proveedores")
                    ->where("proveedor_id", $proveedor->id)
                    ->orderBy("id", "desc")->first();
                if ($opinion == null) // Sin registro de opinión
                {
                    $mensaje_opinion = "Sin Documento de Opinión del SAT";
                }
                else
                {
                    $fecha_valido = strtotime($opinion->fecha_carga . "+5 days"); // Rango 
                    $hoy = strtotime(date("d-m-Y"));
                    if ($fecha_valido >= $hoy)
                    {
                        // OK
                        $mensaje_opinion = "";
                    }
                    else
                        $mensaje_opinion = "Renovar documeto de opinión del SAT";
                }



                $mensaje_rfc = "";
                $rfc = DB::table("validacion_rfc_proveedores")
                    ->where("proveedor_id", $proveedor->id)
                    ->orderBy("id", "desc")->first();
                if ($opinion == null) // Sin registro de opinión
                {
                    $mensaje_rfc = "Sin Documento de Comprobación de RFC";
                }
                else
                {
                    $fecha_valido = strtotime($rfc->fecha_carga . "+5 days"); // Rango 
                    $hoy = strtotime(date("d-m-Y"));
                    if ($fecha_valido >= $hoy)
                    {
                        // OK
                        $mensaje_rfc = "";
                    }
                    else
                        $mensaje_rfc = "Renovar documeto de RFC";
                }
                $aux = (array)$proveedor;
                $aux["mensaje_opinion"] = $mensaje_opinion;
                $aux["mensaje_rfc"] = $mensaje_rfc;
                $proveedor = (object)$aux;
                return response()->json(new Response(200, "Ok", $proveedor, true));
            }
        }
        catch (Exception $e)
        {
            Utilidades::errors($e, 2, $request->rfc);
            return response()->json(new Response(500, "Error 2", "", false));
        }
    }

    /**
     * Registra las facturas de los proveedores con las partidas y cantidad ingresadas
     */
    public function SubirFactura(Request $request)
    {
        try
        {
            $token_client = $request->token;
            if (!($token_client === $this->SERVER_TOKEN))
            {
                return response()->json(new Response(505, "ERROR 403. Unauthorized Access", "", false));
            }
            // Obtener datos
            $file_almacen = json_decode($request->almacen, true);
            $file_calidad = json_decode($request->calidad, true);
            $file_fact_xml = json_decode($request->factura_xml, true);
            $file_fact_pdf = json_decode($request->factura_pdf, true);
            $rfc = $request->rfc;
            $files = [$file_almacen, $file_calidad, $file_fact_xml, $file_fact_pdf];
            foreach ($files as $f)
            {
                $res = $this->ComprobarArchivo($f);
                if (!$res)
                {
                    throw new Exception("Archivo no existe", 404);
                }
            }

            DB::beginTransaction();
            $proveedor = Proveedor::where("rfc", $rfc)->firstOrFail();

            //$name1=$this->PathTemp($file_almacen);
            //                return response()->json(new Response(505, $name1, "", false));

            $files = [$file_almacen, $file_calidad, $file_fact_xml, $file_fact_pdf];
            foreach ($files as $f)
            {
                $res = $this->ComprobarArchivo($f);
                if (!$res)
                {
                    throw new Exception("Archivo no existe", 404);
                }
            }
            // Comprobar no de facura
            $n = DB::table("facturas_proveedores as fp")
                ->where("fp.proveedor_id", $proveedor->id)
                ->where("fp.no_factura", $request->no_factura)
                ->count();

            if ($n >= 1)
            {
                return response()->json(new Response(202, "Factura ya registrada", "", true));
            }
            $oc_id = $request->oc_id;
            $aux_ids_partidas = $request->ids_partidas;
            $aux_cantidades = $request->cantidades;

            // Guardar archivos
            $hash_proveedor = $this->GetHashProveedor($proveedor->rfc);
            $hoy = date("y-m-d");
            $hoy_aux = date("y_m_d_h_i_s");
            $nombre_calidad = $hoy_aux . "_calidad_" . str_replace(" ", "_", $file_almacen["name"]);
            $nombre_almacen = $hoy_aux . "_almacen_" . str_replace(" ", "_", $file_calidad["name"]);
            $nombre_xml_factura = $hoy_aux . "_xml_fact_" . str_replace(" ", "_", $file_fact_xml["name"]);
            $nombre_pdf_factura = $hoy_aux . "_pdf_fact_" . str_replace(" ", "_", $file_fact_pdf["name"]);
            $ruta_almacen = $this->PATH_ROOT . $hash_proveedor . "/" . $nombre_calidad;
            $ruta_calidad = $this->PATH_ROOT . $hash_proveedor . "/" . $nombre_almacen;
            $ruta_xml_factura = $this->PATH_ROOT . $hash_proveedor . "/" . $nombre_xml_factura;
            $ruta_pdf_factura = $this->PATH_ROOT . $hash_proveedor . "/" . $nombre_pdf_factura;


            // Registrar archivos
            $factura = new FacturaProveedor();
            $factura->proveedor_id = $proveedor->id;
            $factura->fecha_carga = $hoy;
            $factura->doc_calidad = $nombre_calidad;
            $factura->doc_almacen = $nombre_almacen;
            $factura->oc_id = $oc_id;
            $factura->moneda = $request->moneda;
            $factura->total = $request->total;
            $factura->no_factura = $request->no_factura;
            $factura->pdf_factura = $nombre_pdf_factura;
            $factura->xml_factura = $nombre_xml_factura;
            $factura->save();

            // Registrar partidas
            $ids_partidas = explode(",", $aux_ids_partidas);
            $cantidades = explode(",", $aux_cantidades);
            for ($i = 0; $i < count($ids_partidas); $i++)
            {
                $partida = new PartidaFacturaProveedor();
                $partida->facturas_proveedor_id = $factura->id;
                $partida->articulo_id = explode("_", $ids_partidas[$i])[0];
                $partida->requ_id = explode("_", $ids_partidas[$i])[1];
                $partida->cantidad = $cantidades[$i];
                $partida->save();
            }


            //Guarda archivos
            Storage::disk('local')->put($ruta_almacen, fopen($this->PathTemp($file_almacen), 'r'));
            Storage::disk('local')->put($ruta_calidad, fopen($this->PathTemp($file_calidad), 'r'));
            Storage::disk('local')->put($ruta_xml_factura, fopen($this->PathTemp($file_fact_xml), 'r'));
            Storage::disk('local')->put($ruta_pdf_factura, fopen($this->PathTemp($file_fact_pdf), 'r'));
            DB::commit();


            return response()->json(new Response(200, "Factura registrada correctamente", "", true));
        }
        catch (Exception $e)
        {
            DB::rollBack();
            Utilidades::errors($e, 2, $request->rfc);
            return response()->json(new Response(500, "Error 504", $e->getMessage(), false));
        }
    }

    public function ComprobarArchivo1($file)
    {
        $nombre = $this->PathTemp($file);
        return file_exists($nombre);
    }

    public function PathTemp1($file)
    {
        $n_aux = $file["tmp_name"];
        $pts = explode(DIRECTORY_SEPARATOR, $n_aux);
        $n = $this->TMP_PATH . end($pts);
        return $n;
    }
    /**
     * Comprueba que el archivo exista en el directorio temporal
     */
    public function ComprobarArchivo($file)
    {
        $nombre = $this->PathTemp($file);
        return file_exists($nombre);
    }

    /**
     * Obtiene el la ruta temporal del archivo ingresado
     */
    public function PathTemp($file)
    {
        $n_aux = $file["tmp_name"];
        $pts = explode(DIRECTORY_SEPARATOR, $n_aux);
        $n = $this->TMP_PATH . end($pts);
        return $n;
    }
    /**
     * Obtiene todas las facturas subidas del proveedor ingresado
     */
    public function ObtenerFacturas(Request $request)
    {
        try
        {
            $rfc = $request->rfc;
            $token_client = $request->token;
            if (!($token_client === $this->SERVER_TOKEN))
            {
                return response()->json(new Response(505, "ERROR 403. Unauthorized Access", "", false));
            }
            $proveedor = Proveedor::where("rfc", "=", $rfc)
                ->select(
                    "id",
                    "nombre",
                    "rfc"
                )
                ->first();
            if ($proveedor == null)
                return response()->json(new Response(504, "Datos incompletos", "", false));

            // Obtener facturas
            $facturas = DB::table("facturas_proveedores as fp")
                ->where("fp.proveedor_id", $proveedor->id)
                ->join("ordenes_compras as oc", "oc.id", "fp.oc_id")
                ->select(
                    "fp.id as id",
                    "fp.fecha_carga as fecha",
                    "fp.doc_calidad as calidad",
                    "fp.doc_almacen as almacen",
                    "fp.estado",
                    "fp.mensaje",
                    "fp.total",
                    "fp.moneda",
                    "fp.no_factura",
                    "oc.folio as oc_folio"
                )
                ->get();
            $partidas_facturas = [];
            // Obtener partidas
            foreach ($facturas as $factura)
            {
                $partidas1 = DB::table("partidas_facturas_proveedores as pfp")
                    ->join("requisicion_has_ordencompras as rho", "rho.requisicione_id", "pfp.requ_id")
                    ->join("articulos as a", "a.id", "pfp.articulo_id")
                    ->where("pfp.facturas_proveedor_id", $factura->id)
                    ->where("pfp.articulo_id", "rho.articulo_id")
                    ->select(
                        "a.descripcion as articulo",
                        "pfp.cantidad as recibido",
                        "rho.cantidad as total"
                    )
                    ->get();

                $partidas = DB::select("
                    SELECT a.descripcion as articulo,
                    pfp.cantidad as recibido, rho.cantidad as total
                    from partidas_facturas_proveedores pfp
                    right join requisicion_has_ordencompras rho on rho.requisicione_id =pfp.requ_id
                    join articulos a on a.id=pfp.articulo_id 
                    where pfp.facturas_proveedor_id =" . $factura->id . "
                    and pfp.articulo_id =rho.articulo_id");
                $aux = ["factura" => $factura, "partidas" => $partidas];
                array_push($partidas_facturas, $aux);
            }

            // Obtener ubicacion de documentos
            $hash_proveedor = $this->GetHashProveedor($proveedor->rfc);
            $path_docs = $this->PATH_ROOT . $hash_proveedor . "/";
            $data = [
                "facturas" => $partidas_facturas,
            ];

            return response()->json(new Response(200, "OK", $data, true));
        }
        catch (Exception $e)
        {
            Utilidades::errors($e, 2, $request->rfc);
            return response()->json(new Response(500, "Error 2", "", false));
        }
    }

    /**
     * Genera la clave del proveedor para guardar los documentos
     */
    public function GetHashProveedor($rfc)
    {
        $hash_proveedor = md5($rfc);
        return $hash_proveedor;
    }

    /**
     * Obtiene las ordenes de compra del proveedores ingresado
     */
    public function ObterOCS($rfc)
    {
        try
        {
            $ocs = DB::table("ordenes_compras as oc")
                ->join("proveedores as p", "p.id", "oc.proveedore_id")
                ->join("estado_compras as ec", "ec.id", "oc.estado_id")
                ->where("p.rfc", $rfc)
                ->select("oc.id", "oc.folio", "ec.nombre as estado")
                ->orderBy("folio")
                ->get();
            return response()->json(new Response(200, "OK", $ocs, true));
        }
        catch (Exception $e)
        {
            Utilidades::errors($e, 2, $rfc);
            return response()->json(new Response(500, "Error 2", "", false));
        }
    }

    /**
     * Obtiene las partidas de la orden de compra ingresada
     */
    public function ObtenerPartidasOc($id)
    {
        try
        {
            $partidas = DB::table("requisicion_has_ordencompras as rho")
                ->join("articulos as a", "a.id", "rho.articulo_id")
                ->join("ordenes_compras as oc", "oc.id", "rho.orden_compra_id")
                ->join("sat_cat_monedas as scm", "scm.id", "oc.moneda")
                ->where("rho.orden_compra_id", $id)
                ->select(
                    "a.id as articulo_id",
                    "rho.requisicione_id as req_id",
                    "a.descripcion as desc",
                    "rho.cantidad as total",
                    "rho.cantidad_entrada as recibido",
                    "oc.moneda as moneda"
                )
                ->get();
            return response()->json(new Response(200, "OK", $partidas, true));
        }
        catch (Exception $e)
        {
            Utilidades::errors($e, 2);
            return response()->json(new Response(500, "Error 2", "", false));
        }
    }

    /**
     * Descarga el archivo ingresado
     */
    public function Descargar(Request $request)
    {
        $token_client = $request->token;
        if (!($token_client === $this->SERVER_TOKEN))
        {
            return response()->json(new Response(505, "ERROR 403. Unauthorized Access", "", false));
        }
        $file_name = $request->file_name;
        $rfc = $request->rfc;
        $header = 'application/pdf';
        $hash_proveedor = $this->GetHashProveedor($rfc);
        $path_file = "\\app\\Archivos\\FacturasProveedores\\" . $hash_proveedor . "/" . $file_name;

        // Obtener ubicacion de documentos
        // $dir = Storage::get($path_file);

        if (Storage::exists($path_file))
        {
            $val = true;
        }
        else
        {
            $avl = false;
        }
        $st = storage_path() . $path_file;

        $header = 'application/pdf';
        // $header = 'text/plain';
        $res = response()->download($st, "demo_dl.pdf", [
            'Content-Type' => $header,
            'Content-Disposition' => 'inline; filename="Demo1.pdf"'
        ]);
        return $res;
    }

    public function Yolo()
    {
        $hash_proveedor = "";
        $file = "";
        $path = "app/Archivos/FacturasProveedores/" . $hash_proveedor . "/" . $file;;
        $path = storage_path('app/' . "demo.txt");

        $header = 'application/pdf';
        // $header = 'text/plain';
        return response()->download($path, "demo_dl.pdf", [
            'Content-Type' => $header,
            'Content-Disposition' => 'inline; filename="Demo1.pdf"'
        ]);
    }

    /**
     * Muestra los datos del proveedor del rfc ingresado
     */
    public function ObtenerDatos($rfc)
    {
        try
        {
            // Datos generales del proveedor
            $proveedor = DB::table("proveedores")->where("rfc", $rfc)
                ->select(
                    "id",
                    "rfc",
                    "razon_social",
                    "contacto",
                    "correo",
                    "direccion",
                    "telefono"
                )
                ->first();
            if ($proveedor == null)
            {
                return response()->json(new Response(404, "No encontrado", "", false));
            }
            // Opinión del sat
            $opinion = OpinionSatProveedor::where("proveedor_id", $proveedor->id)
                ->orderBy("id", "desc")->first();

            // Sin registro de opinion
            if ($opinion == null)
            {
                $aux = (array)$proveedor;
                $aux["estado_opinion"] = "No disponible";
                $aux["fecha_opinion"] = "dd/mm/aaaa";
                $aux["mensaje_opinion"] = "Sin opinión";
                $proveedor = (object)$aux;
            }
            else
            {
                $aux = (array)$proveedor;
                $aux["estado_opinion"] = $this->GetEstadoProveedor($opinion->estado);
                $aux["mensaje_opinion"] = $opinion->aviso;
                $aux["fecha_opinion"] = $opinion->fecha_carga;
                $proveedor = (object)$aux;
            }

            $rfc_sat = RFCSatProveedor::where("proveedor_id", $proveedor->id)
                ->orderBy("id", "desc")->first();
            if ($rfc_sat == null)
            {
                $aux = (array)$proveedor;
                $aux["estado_rfc"] = "No disponible";
                $aux["fecha_rfc"] = "dd/mm/aaaa";
                $aux["mensaje_rfc"] = "Sin RFC comprobado";
                $proveedor = (object)$aux;
            }
            else
            {
                $aux = (array)$proveedor;
                $aux["estado_rfc"] = $this->GetEstadoProveedor($rfc_sat->estado);
                $aux["mensaje_rfc"] = $rfc_sat->aviso;
                $aux["fecha_rfc"] = $rfc_sat->fecha_carga;
                $proveedor = (object)$aux;
            }

            return response()->json(new Response(200, "OK", $proveedor, true));
        }
        catch (Exception $e)
        {
            Utilidades::errors($e, 2, $rfc);
            return response()->json(new Response(505, "ERROR 403. Unauthorized Access", "", false));
        }
    }

    public function GetEstadoProveedor($estado)
    {
        if ($estado == 0) return "En revisión";
        if ($estado == 1) return "Aceptado";
        if ($estado == 2) return "Rechazado";
    }

    /**
     * Actualiza los datos del proveedor. Registra opinión del sat y rfc
     */
    public function ActualizarDatos(Request $request)
    {
        try
        {
            $token_client = $request->token;
            if (!($token_client === $this->SERVER_TOKEN))
            {
                return response()->json(new Response(505, "ERROR 403. Unauthorized Access", "", false));
            }
            DB::beginTransaction();
            $rfc = $request->rfc;
            $proveedor = Proveedor::where("rfc", "=", $rfc)->first();
            if ($proveedor == null)
                return response()->json(new Response(504, "Datos incompletos", "", false));

            $proveedor->direccion = $this->QuitarAcentos($request->direccion);
            $proveedor->contacto = $this->QuitarAcentos($request->contacto);
            $proveedor->correo = $request->email;
            $proveedor->telefono = $request->telefono;
            // Datos para validación
            $aux_file_opinion = $request->file_Opinion;
            $aux_file_rfc = $request->file_RFC;
            // Si existe, guardamos
            $hoy = date("y-m-d");
            if ($aux_file_opinion != null)
            {
                $file_opinion = json_decode($aux_file_opinion, true);
                $res = $this->ComprobarArchivo($file_opinion, true);
                if (!$res)
                {
                    throw new Exception("Error al guardar documento de opinión");
                }
                // Guardar Documento
                $hoy_aux = date("y_m_d_h_i_s");
                $nombre_opinion = $hoy_aux . "_opinion_" . str_replace(" ", "_", $file_opinion["name"]);
                $hash_proveedor = $this->GetHashProveedor($proveedor->rfc);
                $ruta_opinion = $this->PATH_ROOT . $hash_proveedor . "/" . $nombre_opinion;
                $opinion = new OpinionSatProveedor();
                $opinion->aviso = "En revisión";
                $opinion->proveedor_id = $proveedor->id;
                $opinion->fecha_carga = $hoy;
                $opinion->documento = $nombre_opinion;
                Storage::disk('local')->put($ruta_opinion, fopen($this->PathTemp($file_opinion), 'r'));
                $opinion->save();
            }
            if ($aux_file_rfc != null) // x2
            {
                $file_rfc = json_decode($aux_file_rfc, true);
                $res = $this->ComprobarArchivo($file_rfc);
                if (!$res)
                {
                    throw new Exception("Error al guardar documento de RFC");
                }
                // Guardar Documento
                $hoy_aux = date("y_m_d_h_i_s");
                $nombre_sat = $hoy_aux . "_rfc_" . str_replace(" ", "_", $file_rfc["name"]);
                $hash_proveedor = $this->GetHashProveedor($proveedor->rfc);
                $ruta_rfc = $this->PATH_ROOT . $hash_proveedor . "/" . $nombre_sat;
                $rfc = new RFCSatProveedor();
                $rfc->proveedor_id = $proveedor->id;
                $rfc->fecha_carga = $hoy;
                $rfc->aviso = "En revisión";
                $rfc->documento = $nombre_sat;
                Storage::disk('local')->put($ruta_rfc, fopen($this->PathTemp($file_rfc), 'r'));
                $rfc->save();
            }
            $proveedor->save();
            DB::commit();
            return response()->json(new Response(200, "OK", "", true));
        }
        catch (Exception $e)
        {
            DB::rollBack();
            Utilidades::errors($e, 2, $request->rfc);
            return response()->json(new Response(505, $e->getMessage(), "", false));
        }
    }

    /**
     * Acepta el acuerdo del portal
     */
    public function AceptarAcuerdo(Request $request)
    {
        try
        {
            $proveedor = Proveedor::where("rfc", $request->rfc)
                ->select("rfc as RFC", "nombre as Name", "condicion_aceptado")
                ->first();
            if ($proveedor == null)
            {
                return response()->json(new Response(504, "Datos incompletos", "", false));
            }
            DB::table("proveedores")->where("rfc", $request->rfc)->update(
                ["condicion_aceptado" => 1]
            );
            $proveedor1 = Proveedor::where("rfc", $request->rfc)
                ->select("rfc as RFC", "nombre as Name", "condicion_aceptado as aceptado")
                ->first();
            return response()->json(new Response(200, "OK", $proveedor1, true));
        }
        catch (Exception $e)
        {
            Utilidades::errors($e, 2, $request->rfc);
            return response()->json(new Response(505, "Error en el servidor", "Error", false));
        }
    }

    public function QuitarAcentos($cadena)
    {
        $no_permitidas = [
            "á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì",
            "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®",
            "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«",
            "Ò", "Ã", "Ã„", "Ã‹"
        ];
        $permitidas = [
            "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N",
            "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i",
            "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E"
        ];
        $texto = strtoupper(str_replace($no_permitidas, $permitidas, $cadena));
        return $texto;
    }
}
