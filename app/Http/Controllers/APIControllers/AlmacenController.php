<?php

namespace App\Http\Controllers\APIControllers;

use App\Almacene;
use App\APIModels\Response;
use App\Articulo;
use App\Entrada;
use App\Existencia;
use Auth;
use App\Http\Controllers\Controller;
use App\Lote;
use App\LoteAlmacen;
use App\Movimiento;
use App\PartidaEntrada;
use App\Proyecto;
use App\requisicionhasordencompras;
use App\Stand;
use App\Stock;
use App\StockArticulo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class AlmacenController extends Controller
{
    public function Ingresar(Request $request)
    {
        try
        {
            $usuario = $request->usuario;
            $pass = $request->pass;
            $user = \App\User::orderBy('id', 'ASC')
                ->select('users.*')
                ->where('users.name_user', '=', $usuario)
                ->first();
            if ($user)
            {
                if (Auth::attempt(
                    [
                        'name_user' => $usuario,
                        'password' => $pass
                    ]
                ))
                {
                    //obtenemos el Id del usuario logueado
                    $iduser = auth()->id();
                    $usuario = \App\User::findOrFail($iduser);

                    $datos = array(
                        "Id"     => $usuario->id,
                        "User"   => $usuario->name_user,
                        "Nombre" => $usuario->name
                    );
                    return response()->json(new Response(200, "Ok", $datos, true));
                }
                else
                {
                    return response()->json(new Response(404, "Contraseña Incorrecta", null, false));
                }
            }
            else
            {
                return response()->json(new Response(404, "El usuario no existe", null, true));
            }
        }
        catch (\Exception $e)
        {
            return response()->json(new Response(500, $e->getMessage(), null, false));
        }
    }

    // Comprueba que se tenga una conexión con la DB
    public function Test()
    {
        try
        {
            Articulo::first();
            return response()->json(new Response(200, "Ok", "OK", true));
        }
        catch (Exception $e)
        {
            return response()->json(new Response(500, $e->getMessage(), null, false));
        }
    }

    // Obtiene todos los proyectos activos de la DB
    public function ObtenerProyectos()
    {
        try
        {
            $proyectos = Proyecto::where("condicion", "=", 1)
                ->select("id", "nombre_corto")
                ->orderBy("nombre_corto")->get();
            return response()->json(new Response(200, "OK", $proyectos, true));
        }
        catch (Exception $e)
        {
            return response()->json(new Response(500, $e->getMessage(), null, false));
        }
    }

    // Obtiene todas las OC's del proyecto ingresado
    public function ObtenerOCS($proyecto_id)
    {
        try
        {
            $ocs = DB::table("ordenes_compras")
                ->where("proyecto_id", "=", $proyecto_id)
                ->where("condicion","=",'2')
                ->orderBy("folio")
                ->select("id", "folio")
                ->get();
            return response()->json(new Response(200, "OK", $ocs, true));
        }
        catch (Exception $e)
        {
            return response()->json(new Response(500, $e->getMessage(), null, false));
        }
    }

    // Obtiene las partidas de la OC ingresada
    public function ObtenerPartidasOC($id_oc)
    {
        try
        {
            $partidas = DB::table("requisicion_has_ordencompras as rho")
                ->join("articulos as a", "a.id", "rho.articulo_id")
                ->where("rho.orden_compra_id", "=", $id_oc)
                ->where("rho.orden_compra_id", "!=", "''")
                ->select(
                    "a.id as articulo_id",
                    "a.descripcion",
                    "rho.id as oc_id",
                    "rho.condicion",
                    "a.marca"
                )
                ->get();
            return response()->json(new Response(200, "OK", $partidas, true));
        }
        catch (Exception $e)
        {
            return response()->json(new Response(500, $e->getMessage(), null, false));
        }
    }

    // Obtiene los detalles del articulo, mediante la RequisicionHasOrdenCompra Id
    public function ObtenerArticulo($id)
    {
        try
        {
            $art = DB::table("lote_almacen as la")
                ->join("lotes as l", "l.id", "la.lote_id")
                ->join("partidas_entradas as pe", "pe.id", "l.entrada_id")
                ->join("requisicion_has_ordencompras as rho", "rho.id", "pe.req_com_id")
                ->join("articulos as a", "a.id", "rho.articulo_id")
                ->where("rho.id", "=", $id)
                ->select(
                    "la.id as la_id",
                    "la.codigo_barras as code",
                    "l.id as lote_id",
                    "rho.id as rho_id",
                    "rho.condicion",
                    "a.id as articulo_id",
                    "a.descripcion as descripcion",
                    "a.marca as a_marca",
                    "a.unidad as a_um",
                    "la.cantidad as a_cant"
                )
                ->first();

            if ($art == null)
            {
                return response()->json(new Response(404, "OK", null, true));
            }
            else
            {
                return response()->json(new Response(200, "OK", $art, true));
            }
        }
        catch (Exception $e)
        {
            return response()->json(new Response(500, $e->getMessage(), null, false));
        }
    }

    // Obtiene todos los almacenes de la DB
    public function ObtenerAlmacenes()
    {
        try
        {
            $almacenes = Almacene::select("id", "nombre")
                ->where("condicion", "=", 1)
                ->orderBy("nombre")
                ->get();
            return response()->json(new Response(200, "OK", $almacenes, true));
        }
        catch (Exception $e)
        {
            return response()->json(new Response(500, $e->getMessage(), null, false));
        }
    }

    // Obtiene los stands del almacen seleccionado
    public function ObtenerStand($alamcen_id)
    {
        try
        {
            $stand = Stand::select("id", "nombre")
                ->where("condicion", "=", 1)
                ->where("almacene_id", "=", $alamcen_id)
                ->get();
            if (count($stand) > 0)
                return response()->json(new Response(200, "OK", $stand, true));
            else
                return response()->json(new Response(404, "Sin stands", null, true));
        }
        catch (Exception $e)
        {
            return response()->json(new Response(500, $e->getMessage(), null, false));
        }
    }

    // Obtiene los niveles del stand
    public function ObtenerNivel($stand_id)
    {
        try
        {
            $nivel = DB::table("niveles")
                ->select("id", "nombre")
                ->where("condicion", "=", 1)
                ->where("stande_id", "=", $stand_id)
                ->get();

            if (count($nivel) > 0)
                return response()->json(new Response(200, "OK", $nivel, true));
            else
                return response()->json(new Response(404, "Si niveles", null, true));
        }
        catch (Exception $e)
        {
            return response()->json(new Response(500, $e->getMessage(), null, false));
        }
    }

    public function GuardarCodigo(Request $request)
    {
        try
        {
            if ($request->la_id == null)
                return response()->json(new Response(404, "No encontrado", null, false));
            LoteAlmacen::where("id", $request->la_id)
                ->update(
                    [
                        "codigo_barras" => $request->codigo,
                    ]
                );
            return response()->json(new Response(200, "Ok", $request->la_id, true));
        }
        catch (Exception $e)
        {
            return response()->json(new Response(500, $e->getMessage(), null, false));
        }
    }

    // Registra una nueva entrada al almacén
    public function RegistrarEntrada(Request $request)
    {
        try
        {
            DB::beginTransaction();

            /** OK */
            // Obtener id_empleado de usuario
            $id_empleado = DB::table("users as u")
                ->leftJoin("empleados as e", "e.id", "u.empleado_id")
                ->where("u.id", "=", $request->usuaro_id)
                ->select("u.name_user", "e.nombre", "e.id")->first()->id;


            /**  OK */
            // Detalles de artículo
            $art = Articulo::where("id", "=", $request->articulo_id)->first();
            $art->marca = $request->marca;
            $art->unidad = $request->um;
            $art->save();

            /**  OK */
            // Obtener entrada de OC
            $entrada = Entrada::where("orden_compra_id", "=", $request->oc_id)->first();



            if ($entrada == null)
            {
                // No tiene entrada, registrar nueva
                $nueva_entrada = new Entrada();
                $nueva_entrada->fecha = date("y-m-d");
                $nueva_entrada->comentarios = "Nueva entrada por orden de compra";
                $nueva_entrada->empleado_usuario_id = $id_empleado;
                $nueva_entrada->empleado_almacen_id = $id_empleado;
                $nueva_entrada->orden_compra_id = $request->oc_id;
                $nueva_entrada->save();
                $entrada = $nueva_entrada;
            }

            /**  OK */
            // Registrar entrada de partida
            $partida_entrada = new PartidaEntrada();
            $partida_entrada->entrada_id = $entrada->id;
            $partida_entrada->req_com_id = $request->rho_id;
            $partida_entrada->articulo_id = $request->articulo_id;
            $partida_entrada->validacion_calidad = 0;
            $partida_entrada->cantidad = $request->cantidad;
            $partida_entrada->almacene_id = $request->almacen_id;
            $partida_entrada->stand_id = $request->stand_id;
            $partida_entrada->nivel_id = $request->nivel_id;
            $partida_entrada->save();


            /**  OK */
            // Obtener total de las entradas
            // Guardar cantidad
            $rho_edit = Requisicionhasordencompras::where('id', '=', $request->rho_id)->first();
            $rho_edit->cantidad_entrada = $rho_edit->cantidad_entrada - $request->cantidad; // REstar entrada a pendiente
            $rho_edit->save();

            if ($rho_edit->cantidad_entrada <= 0)
            {
                $rho_edit->condicion = 0; // Todos entrados
                $rho_edit->save();
            }

            /**  OK */
            // Obtener stock
            $stock_id = Stock::where("proyecto_id", "=", $request->proyecto_id)->first()->id;

            // Lotes
            /**  OK */
            $lote = new Lote();
            $lote->nombre = "-";
            $lote->entrada_id = $partida_entrada->id;
            $lote->articulo_id = $request->articulo_id;
            $lote->cantidad = $request->cantidad;
            $lote->save();
            $lote->nombre = ("lote " . $partida_entrada->id . "-" . $request->articulo_id . "-" . $lote->id);
            $lote->save();

            /**  OK */
            // Registrar en lote almacen
            $lote_almacen = new LoteAlmacen();
            $lote_almacen->lote_id = $lote->id;
            $lote_almacen->almacene_id = $request->almacen_id;
            $lote_almacen->nivel_id = $request->nivel_id;
            $lote_almacen->stand_id = $request->stand_id;
            $lote_almacen->cantidad += $request->cantidad;
            $lote_almacen->stocke_id = $stock_id;
            $lote_almacen->articulo_id = $request->articulo_id;
            $lote_almacen->codigo_barras = $request->codigo;
            $lote_almacen->save();

            /**  OK */
            $existencia = new Existencia();
            $existencia->id_lote = $lote->id;
            $existencia->articulo_id = $request->articulo_id;
            $existencia->almacene_id = $request->almacen_id;
            $existencia->stand_id = $request->stand_id;
            $existencia->nivel_id = $request->nivel_id;
            $existencia->cantidad = $request->cantidad;
            $existencia->save();

            /**  OK */
            // Registrar movimiento
            $movimiento = new Movimiento();
            $movimiento->cantidad = $request->cantidad;
            $movimiento->fecha = date("y-m-d");
            $movimiento->hora = date("H:i:s");
            $movimiento->tipo_movimiento = "Entrada";
            $movimiento->folio = "Entrada-" . $request->proyecto_id . $stock_id;
            $movimiento->proyecto_id = $request->proyecto_id;
            $movimiento->lote_id = $lote_almacen->id;
            $movimiento->stocke_id = $stock_id;
            $movimiento->almacene_id = $request->almacen_id;
            $movimiento->stand_id = $request->stand_id;
            $movimiento->nivel_id = $request->nivel_id;
            $movimiento->articulo_id = $request->articulo_id;
            $movimiento->save();

            /**  OK */
            // StockArticulos
            $stock_articulo = StockArticulo::where("articulo_id", "=", $request->articulo_id)
                ->where("stocke_id", "=", $stock_id)->first();
            if ($stock_articulo == null)
            {
                // Registrar nuevo
                $nuevo_stock = new StockArticulo();
                $nuevo_stock->cantidad = $request->cantidad;
                $nuevo_stock->articulo_id = $request->articulo_id;
                $nuevo_stock->stocke_id = $stock_id;
                $nuevo_stock->save();
            }
            else
            {
                // Sumar cantidad
                $n = $stock_articulo->cantidad + $request->cantidad;
                $stk = StockArticulo::where("articulo_id", "=", $request->articulo_id)
                    ->where("stocke_id", "=", $stock_id)->first();
                $stk->cantidad = $n;
                $stk->save();
            }

            /**  OK */
            // Actualizar cantidad de existencia
            $actual = $existencia->cantidad + $request->cantidad;

            $existencia = Existencia::where("articulo_id", "=", $request->articulo_id)
                ->where("almacene_id", "=", $request->almacen_id)
                ->first();
            $existencia->cantidad = $actual;
            $existencia->save();

            DB::commit();
            return response()->json(new Response(200, "Ok", null, true));
        }
        catch (Exception $e)
        {
            DB::rollBack();
            return response()->json(new Response(500, $e->getMessage(), null, false));
        }
    }

    // Comprueba si existe una nueva version de la aplciación
    public function BuscarActualizacion($version)
    {
        try
        {
            $current = DB::table("app_almacen")->first();

            if ($current->cur_ver > $version)
            {
                // Buscar actu
                return response()->json(new Response(200, "Actualización pendiente", $current->url, true));
            }
            else
            {
                // Actualizado
                return response()->json(new Response(404, "Ok", "OK", true));
            }
        }
        catch (Exception $e)
        {
            return response()->json(new Response(500, $e->getMessage(), null, false));
        }
    }
}
