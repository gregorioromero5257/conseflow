<?php
namespace App\Http\Controllers;

use App\Inventario;
use App\Articulo;
use App\Entrada;
use App\Existencia;
use App\Http\Controllers\Controller;
use App\Lote;
use App\LoteAlmacen;
use App\Movimiento;
use App\PartidaEntrada;
use App\StockArticulo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class YoloController extends Controller
{
    public function Yolo()
    {
        $inventario = Inventario::get();
        foreach ($inventario as $inv)
        {
            $existe = null;
            $nombre = $inv["nombre"];
            $descripcion = $inv["descripcion"];
            if ($nombre == "" && $descripcion == "") //nom, desc =""->saltar
            {
                echo $inv["id"] . ": Vacio<br>";
                continue;
            }
            if ($descripcion == "") // buscar por nombre
            {
                $existe = Articulo::where("nombre", "like", $nombre)
                    ->orWhere("descripcion", "like", $nombre)
                    ->first();
            }
            else if ($nombre == "") // buscar por descripcion
            {
                $existe = Articulo::where("nombre", "like", $descripcion)
                    ->orWhere("descripcion", "like", $descripcion)
                    ->first();
            }

            if ($existe!=null)
            {
                echo $existe -> id . ": Existe<br>";
            }
        }
    }

    /**
     * 1. Cargar artículos de XLS
     */
    public function Yolo1(Request $request)
    {
        // return Inventario::count();
        $error = [];
        try
        {
            set_time_limit(500);
            $uno = (new FastExcel())->sheet(1)->import($request->file('inventario')->getRealPath());
            $dos = (new FastExcel())->sheet(2)->import($request->file('inventario')->getRealPath());
            $tres = (new FastExcel())->sheet(3)->import($request->file('inventario')->getRealPath());
            $cuatro = (new FastExcel())->sheet(4)->import($request->file('inventario')->getRealPath());
            $hojas = [$uno,$dos, $tres, $cuatro];
            foreach ($hojas as $hoja)
            {
                foreach ($hoja as $fila)
                {
                    $error = $fila;
                    $art_existe = null;
                    if ($fila["nombre"] != "")
                    {
                        // Tiene nombre
                        $art_existe = Articulo::where("nombre", "=", $fila["nombre"])->first();
                    }
                    else if ($fila["descripcion"] != "")
                    {
                        $art_existe = Articulo::where("descripcion", "=", $fila["descripcion"])->first();
                    }
                    else
                    {
                        // nada
                    }
                    $n = new Inventario($this->Datos($fila));
                    if ($art_existe != null)
                        $n->articulo_id = $art_existe->id;
                    else
                        $n->articulo_id = 0;
                    $n->save();
                }
            }
            //
            return "ok";
        }
        catch (Exception $e)
        {
            print_r($error);
            dd($e);
        }
    }

    public function Datos($fila)
    {
        $cantidad = $fila["cantidad"] == "" ? 0 : $fila["cantidad"];
        $fila["cantidad"] = $cantidad;
        return $fila;
    }

    /**
     * 2.
     */
    public function Entrada()
    {
        $start = microtime(true);

        // Crear articulos y dar entrada
        set_time_limit(500);
        DB::beginTransaction();
        $arti_nuevo=0;
        $entradas=0;
        $nope=0;
        try
        {

            // Crear entrada
            $entrada = new Entrada();
            $entrada->fecha = date('Y-m-d');
            $entrada->comentarios = "Entrada de inventario";
            $entrada->tipo_entrada_id = 1;
            $entrada->save();

            $articulos_invenrtario = Inventario::get();
            foreach ($articulos_invenrtario as $articulo_aux)
            {
                if ($articulo_aux->nombre = "" && $articulo_aux->descripcion = "")
                    {
                        $nope+=1;
                        continue; // No válido
                    }
                $grupo = DB::table("grupos as g")->where("nombre", "=", $articulo_aux->grupo)->first();
                $articulo=null;
                if($articulo_aux->articulo_id==0)
                {
                    // No existe el artículo. Crear
                    $articulo = new Articulo();
                    $articulo->nombre = $articulo_aux->nombre;
                    $articulo->codigo = $articulo_aux->codigo;
                    $articulo->descripcion = $articulo_aux->descripcion == "" ? $articulo_aux->nombre : $articulo_aux->descripcion;
                    $articulo->marca = $articulo_aux->marca;
                    $articulo->unidad = $articulo_aux->unidad = "" ? "N/D" : $articulo_aux->um;
                    $articulo->um_id = 0;
                    if ($grupo != null)
                        $articulo->grupo_id = $grupo->id;
                    $articulo->save();
                    $arti_nuevo+=1;
                }
                else
                {
                    // Obtener  artícculo
                    $articulo=Articulo::find($articulo_aux->articulo_id);
                }
                $articulo_id=$articulo->id;

                $almacen_id = 1; // GENERAL



                //Stock
                $stock_id = DB::table("stocks as s")
                    ->join("proyectos as p", "p.id", "s.proyecto_id")
                    ->where("p.nombre_corto", "=", $articulo_aux->proyecto)
                    ->select("s.id")->first();
                if ($stock_id == null)
                    $stock_id = 1;
                else
                    $stock_id = $stock_id->id;
                $proyecto_id = DB::table("proyectos as p")
                    ->where("p.nombre_corto", "=", $articulo_aux->proyecto)
                    ->select("p.id")->first();
                if ($proyecto_id == null)
                    $proyecto_id = 1;
                else
                    $proyecto_id = $proyecto_id->id;

                    $rhoc = new \App\requisicionhasordencompras();
                    $rhoc->requisicione_id = 1;
                    $rhoc->orden_compra_id = 1;
                    $rhoc->articulo_id = $articulo->id;
                    $rhoc->cantidad = 1;
                    $rhoc->precio_unitario = 0;
                    $rhoc->tipo_entrada = 'Interna';
                    $rhoc->condicion = 0;
                    $rhoc->antigua = 0;
                    $rhoc->cantidad_entrada = 0;
                    // Utilidades::auditar($rhoc,$rhoc->id);
                    $rhoc->save();

                // PArtidaEntrada
                $partidaEntrada = new PartidaEntrada();
                $partidaEntrada->entrada_id = 1;
                $partidaEntrada->req_com_id = $rhoc->id;
                $partidaEntrada->articulo_id = $articulo_id;
                $partidaEntrada->validacion_calidad = 0;
                $partidaEntrada->cantidad = $articulo_aux->cantidad;
                $partidaEntrada->almacene_id = $almacen_id;
                $partidaEntrada->pendiente = 0;
                $partidaEntrada->status = 0;
                $partidaEntrada->precio_unitario = 0;
                $partidaEntrada->nivel_id = doubleval($articulo_aux->nivel_id);
                $partidaEntrada->stand_id = doubleval($articulo_aux->stand_id);
                $partidaEntrada->stocke_id = $stock_id;
                $partidaEntrada->save();

                // Lote
                $lote = new Lote();
                $lote->nombre = "lote 0001-".$articulo->id;
                $lote->entrada_id = $partidaEntrada->id;
                $lote->articulo_id = $articulo_id;
                $lote->cantidad = $articulo_aux->cantidad;
                $lote->save();
                $lote->nombre = ("lote 0001-" . $articulo_id . "-" . $lote->id);
                $lote->save();

                // LoteAlmacen
                $lote_almacen = new LoteAlmacen();
                $lote_almacen->lote_id = $lote->id;
                $lote_almacen->almacene_id = $almacen_id;
                $lote_almacen->nivel_id = doubleval($articulo_aux->nivel_id);
                $lote_almacen->stand_id = doubleval($articulo_aux->stand_id);
                $lote_almacen->cantidad = $articulo_aux->cantidad;
                $lote_almacen->stocke_id = $stock_id;
                $lote_almacen->articulo_id = $articulo_id;
                $lote_almacen->condicion = 1;
                $lote_almacen->codigo_barras = 'MCF 0001 '.$articulo->id.' '.$lote->id;
                $lote_almacen->save();

                // Existencia ??? Crear nueva existencia para cada articulo? (Repetidos?)
                $existencia = new Existencia();
                $existencia->articulo_id = $articulo_id;
                $existencia->almacene_id = $almacen_id;
                $existencia->id_lote = $lote->id;
                $existencia->nivel_id = doubleval($articulo_aux->nivel_id);
                $existencia->stand_id = doubleval($articulo_aux->stand_id);
                $existencia->save();

                // Movimiento
                $movimiento = new Movimiento();
                $movimiento->cantidad = $articulo_aux->cantidad;
                $movimiento->fecha = date("y-m-d");
                $movimiento->hora = date("H:i:s");
                $movimiento->tipo_movimiento = "INV";
                $movimiento->folio = "Entrada-" . $proyecto_id . $stock_id;
                $movimiento->proyecto_id = $proyecto_id;
                $movimiento->lote_id = $lote_almacen->id;
                $movimiento->stocke_id = $stock_id;
                $movimiento->almacene_id = $almacen_id;
                $movimiento->stand_id = doubleval($articulo_aux->stand_id);
                $movimiento->nivel_id = doubleval($articulo_aux->nivel_id);
                $movimiento->articulo_id = $articulo_aux->id;
                $movimiento->save();

                // StockArticulos
                $stock_articulo = StockArticulo::where("articulo_id", "=", $articulo_id)
                    ->where("stocke_id", "=", $stock_id)->first();
                if ($stock_articulo == null)
                {
                    // Registrar nuevo
                    $nuevo_stock = new StockArticulo();
                    $nuevo_stock->cantidad = $articulo_aux->cantidad;
                    $nuevo_stock->articulo_id = $articulo_id;
                    $nuevo_stock->stocke_id = $stock_id;
                    $nuevo_stock->save();
                }
                else
                {
                    // Sumar cantidad
                    $n = $stock_articulo->cantidad + $articulo_aux->cantidad;
                    $stk = StockArticulo::where("articulo_id", "=", $articulo_id)
                        ->where("stocke_id", "=", $stock_id)->first();
                    $stk->cantidad = $n;
                    $stk->update();
                }
                $entradas+=1;
                // break;
            } //For
            DB::commit();
            $end = microtime(true);
            $time = $end-$start;
            echo "Ok<br>";
            echo "Tiempo {$time}<br>";
            echo "Artículos nuevos: ".$arti_nuevo."<br>";
            echo "Entradas : ".$entradas."<br>";
            echo "Omitidos : ".$nope."<br>";
        }
        catch (Exception $e)
        {
            DB::rollBack();
            dd($e);
        }
    }
}
