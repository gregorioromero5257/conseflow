<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use App\Articulo;
use App\Requisicionhasordencompras;
use Illuminate\Validation\Rule;
use Carbon\Carbon;



class ProductosSatController extends Controller
{
    public function descripcion( Request $request)
    {
        $desc=$request['desc'];
        $tipos=DB::table('tipos_sat')->get();

        return response()->json(array(
            'buscar' => "$desc",
            "tipos_sat" =>  $tipos,
        ));
    }

    public function tipos(Request $req)
    {
        $tipos=DB::table('tipos_sat')->get();
        return response()->json(array
        (
            "tipos_sat" =>  $tipos,
        ));
    }

    public function divisiones($tipo)
    {
        $divisiones=DB::table('divisiones_sat')
        ->where('tipos_sat_id','=',$tipo)
        ->get();

        return response()->json(array
        (
            'divisiones' => $divisiones,
        ));
    }
    
    public function grupos($division)
    {
        $grupos=DB::table('grupos_sat')
        ->where('divisiones_sat_id','=',$division)
        ->get();

        return response()->json(array
        (
            'grupos' => $grupos,
        ));
    }

    public function clases($grupo)
    {
        $clases=DB::table('clases_sat')
        -> where('grupos_sat_id','=',$grupo)
        ->get();
        return response()->json(array
        (
            'clases' => $clases,
        ));
    }

    public function codigos($clase)
    {
        $productos=DB::table('productos_sat')
        -> where('clase_id','=',$clase)
        ->get();
        $aux_productos=array();
        foreach ($productos as $producto )
        {
            $producto_aux= array
            (
                "descripcion" => $producto->codigo . " - ". $producto->nombre,
                "id"          => $producto->id,
            );
            array_push($aux_productos,$producto_aux);
        }
        return response()->json(array
        (
            'productos' => $aux_productos,
        ));
    }

    public function producto($id)
    {
        $producto=DB::table('productos_sat')
        ->where('id','=',$id)
        ->first();
        return response()->json(array
        (
            'producto' =>$producto,
        ));
    }

    public function getPtoductos()
    {
        
        $articulos=DB::table("articulos as a")
        ->leftJoin("catalogo_centro_costos as ccc","a.centro_costo_id","ccc.id")
        ->leftJoin("grupos as g", "a.grupo_id","g.id")
        ->leftJoin("categorias as c", "g.categoria_id","g.id")
        ->leftJoin("productos_sat as ps","a.codigo_producto_sat","ps.id")
        ->select("a.id","a.nombre as nombre" , "c.nombre as categoria",
                 "ccc.nombre as costo","ccc.id as costo_id",
                DB::raw("CONCAT(ps.codigo ,'-',ps.nombre) as codigo_sat"))
        ->orderBy("nombre")
        ->get();
        return response()->json($articulos);
    }


    public function centroCostos()
    {
        $catalogo = \App\CatalogoCentroCostos::leftJoin
        (
            'catalogo_centro_costos AS CCC','CCC.id','=','catalogo_centro_costos.catalogo_centro_costos_id'
        )
        ->select('catalogo_centro_costos.*','CCC.nombre AS nombre_sub')
        ->where('catalogo_centro_costos.sub','!=','3')
        ->orderBy("nombre_sub")
        ->orderBy("nombre")
        ->get();
        return response()->json($catalogo);
    }

    public function actualizarCentroCosto(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $id_articulo=explode("&",$request->ids)[0];
            $idproducto_sat=explode("&",$request->ids)[1];
            $articulo=\App\Articulo::find($id_articulo);
            $articulo->codigo_producto_sat=$idproducto_sat;
            $articulo->save();
            DB::commit();
            return response()->json([ "status" => true]);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json([ "status" => true]);
        }        
    }

    public function ActualizarCentro(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $id_articulo=explode("&",$request->ids)[0];
            $idcentro_costos=explode("&",$request->ids)[1];
            $articulo=\App\Articulo::find($id_articulo);
            $articulo->centro_costo_id=$idcentro_costos;
            $articulo->save();
             DB::commit();
            return response()->json([ "status" => true]);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json([ "status" => true]);
        }
    }
}
