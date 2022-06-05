<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Unidades;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Support\Facades\Storage;
use \App\Http\Helpers\Utilidades;
use \App;
use \App\PartidasRetorno;
use \App\Partidas;
use \App\LoteAlmacen;
use \App\Existencia;
use \App\Movimiento;
use \App\StockArticulo;

class EntradaSalidaController extends Controller
{
    protected $rules = array(
        'modelo' => 'required|max:45',
    );

    public function getProyectos()
    {
        $proyectos = \App\Proyecto::get();
        return response()->json(["proyectos"=>$proyectos]);
    }

    public function Guardar(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');

        if($request->accion==1) //Registrar
        {
            $salida= new App\SalidaRetorno();
        }
        else //Actualizar
        {
            $salida=\App\SalidaRetorno::findOrFail($request->id);
        }

        $salida->fecha=$request->fecha;
        $salida->comentarios=$request->comentarios;
        $salida->tipo_retorno=$request->tipoRetornoId;
        $salida->estado=1;  //Activo
        $salida->proyectoId=$request->proyectoId;
        Utilidades::auditar($salida, $salida->id);
        $salida->save();

        return response()->json(['salida'=>$salida]);
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function GetSalidas($proyectoId)
    {
        if($proyectoId==0)
        {
            $salidas=DB::table('salidas_retorno as s')
            ->leftjoin('proyectos as p','p.id','=','s.proyectoId')
            ->select('s.*','p.nombre_corto')
            ->orderBy('p.nombre_corto', 'ASC')
            ->get();
        }
        else
        {
            $salidas=DB::table('salidas_retorno as s')
            ->leftjoin('proyectos as p','p.id','=','s.proyectoId')
            ->where('p.id','=',$proyectoId)
            ->select('s.*','p.nombre_corto')->get();
        }

        return response()->json($salidas);
    }

    /**
   * Obtiene todas las partidas que se han retornado, del tipo de salida y proyecto ingresado
   * @param String $request Datos del proyecto y tipo de salida
   * @return Response [Json con las partidas retornadas]
   */
    public function GetPartidasRetorno($request)
    {
        $proyectoId=explode('&',$request)[0];
        $tipoSalidaId=explode('&',$request)[1];
        $tablaSalidas="";

        // $tablaSalidas=($tipoSalidaId==1)?"salidas":($tipoSalidaId==2)?"salidassitio":"salidasresguardo";
        if($tipoSalidaId==1)
            $tablaSalidas="salidas";
        else if($tipoSalidaId==2)
            $tablaSalidas="salidassitio";
        else
            $tablaSalidas="salidasresguardo";

        // $salidas=DB::select("
        // select p.salida_id, p.lote_id,p.cantidad_retorno, (p.cantidad-p.cantidad_retorno) as pendiente,
	      //   s.fecha, a.nombre as articulo, alm.nombre as almacen
        // from partidas as p
        // join $tablaSalidas as s
	      //   on p.salida_id =s.id
        // join lote_almacen as la
	      //   on p.lote_id = la.id
        // left join lotes as lt
	      //   on p.lote_id = lt.id
        // join articulos as a
	      //   on a.id=la.articulo_id
        // join proyectos  as pr
	      //   on pr.id = s.proyecto_id
        // join almacenes alm
	      //   on alm.id =la.almacene_id
        // where p.cantidad_retorno >0 and p.tiposalida_id=$tipoSalidaId and pr.id=$proyectoId
        // ");

        $salidas = DB::table('partidas_retorno AS pr')
        ->join('salidas_retorno AS sr','sr.id','pr.salida_retorno_id')
        ->join('partidas AS p','p.id','pr.partida_id')
        ->join('lote_almacen AS la','la.id','p.lote_id')
        // ->join('lotes AS l','l.id','la.lote_id')
        ->join('articulos AS a','a.id','pr.articulo_id')
        ->join('almacenes AS alm','alm.id','la.almacene_id')
        ->select(
          'p.salida_id',
          'sr.fecha',
          'pr.cantidad_entrada as cantidad_retorno',
          DB::raw("(p.cantidad-p.cantidad_retorno) as pendiente"),
	       'a.descripcion as articulo',
          'alm.nombre as almacen'
         )
        ->where('salida_retorno_id',$proyectoId)->get();

        return response()->json(["salidas" => $salidas]);
    }

  /**
   * Obtiene todas las partidas que han salido del tipo de salida y proyecto ingresado
   * @param String $request Datos del proyecto y tipo de salida
   * @return Response [Json con las partidas de salida]
   */
    public function GetPartidas($request)
    {
        $proyectoId=explode('&',$request)[0];
        $tipoSalidaId=explode('&',$request)[1];
        // $retornado=explode('&',$request)[2];
        $tablaSalidas="";


        if($tipoSalidaId==1)
            $tablaSalidas="salidas";
        else if($tipoSalidaId==2)
            $tablaSalidas="salidassitio";
        else
            $tablaSalidas="salidasresguardo";
        // $tablaSalidas=($tipoSalidaId==1)?"salidas":($tipoSalidaId==2)?"salidassitio":"salidasresguardo";
        // echo "$tablaSalidas";
        // echo $tipoSalidaId;
        /*
        $salidas=DB::table('partidas as p')
        ->join("$tablaSalidas as s",'p.salida_id','=','s.id')
        ->join('lote_almacen as la','p.lote_id','=','la.id')
        ->leftJoin('lotes as lt','p.lote_id','=','lt.id')
        ->join('articulos as a','a.id','=','la.articulo_id')
        ->join('proyectos as pr','pr.id','=','s.proyecto_id')
        ->select('p.id as partidaId','p.salida_id', 'p.lote_id','lt.nombre as lote', 'p.cantidad',
         'p.cantidad_retorno','s.fecha','pr.nombre_corto','a.id as articuloId', 'a.nombre as articulo',
         'p.tiposalida_id as tipoSalida','s.folio as salida_folio')
         ->where('p.cantidad_retorno','=','p.cantidad') // ← ¿¿POR QUÉ???
         ->where('p.tiposalida_id','=',$tipoSalidaId)
         ->where('pr.id','=',$proyectoId);
         */

        $salidas = DB::select("
            select p.id as partidaId,p.salida_id, p.lote_id,lt.nombre as lote, (p.cantidad-p.cantidad_retorno) as cantidad,p.cantidad_retorno,
            s.folio, s.fecha,pr.nombre_corto,a.id as articuloId, a.descripcion as articulo,p.tiposalida_id as tipoSalida
            from partidas as p
            join $tablaSalidas as s on p.salida_id =s.id
            join lote_almacen as la on p.lote_id = la.id
            left join lotes as lt on p.lote_id = lt.id
            join articulos as a on a.id=la.articulo_id
            join proyectos  as pr on pr.id = s.proyecto_id
            where  p.tiposalida_id='$tipoSalidaId' and pr.id='$proyectoId'and p.cantidad_retorno < p.cantidad "
        );

        return response()->json(["salidas"=>$salidas]);
    }

    /**
   * Registra un nuevo retorno de salida y actualiza las existencias correspondientessalidanew
   * @param Request $request Datos del retorno de salida
   * @return Response [Json con status y mensaje]
   */
    public function GuardarRetorno(Request $request)
    {
        DB::beginTransaction();
        try
        {
            // echo $request->articulo;
            $validos=$request->cEntrada-$request->defectuoso;
            $aux_articulo=$request->articuloId;
            //Guardar partida
            $partidaRetorno=new PartidasRetorno();
            $partidaRetorno->articulo_id=$request->articuloId;
            $partidaRetorno->cantidad_entrada=$request->cEntrada;
            $partidaRetorno->cantidad_defectuoso=$request->defectuoso;
            $partidaRetorno->proyecto_id=$request->proyectoId;
            $partidaRetorno->salida_id=$request->salidaId;
            $partidaRetorno->tipo_salida=$request->tipoSalida;
            $partidaRetorno->salida_retorno_id = $request->SalidaRetornoId;
            $partidaRetorno->partida_id = $request->partidaId;
            $partidaRetorno->save();
            // Aumentar en almacen
            // Actualizar cantidad en partida
            $partidaActualizar=Partidas::findOrFail($request->partidaId);
            $partidaActualizar->cantidad_retorno+=$request->cEntrada;
            Utilidades::auditar($partidaActualizar, $partidaActualizar->id);
            $partidaActualizar->save();

            // Verificar cantidad entrada con salida

            if($partidaActualizar->cantidad_retorno>$partidaActualizar->cantidad)
            {
                DB::rollback();
                return response()->json(["status"=>false,"error"=>"Cantidad de retorno excedida"]);
                // throw new Exception("Cantidad de retorono excedida");
            }

            // Actualizar lote_almacen
            $loteAlmacen=LoteAlmacen::findOrFail($request->loteId);
            $loteAlmacen->cantidad+=$validos;
            Utilidades::auditar($loteAlmacen, $loteAlmacen->id);
            $loteAlmacen->save();

            // existencias
            $existencias=Existencia::findOrFail($request->loteId);
            $existencias->cantidad+=$request->cEntrada;
            Utilidades::auditar($existencias, $existencias->id);
            $existencias->save();

            // Movimientos

            $movimiento=new Movimiento();
            $movimiento->cantidad=$request->cEntrada;
            $movimiento->fecha=date("Y-m-d");
            $movimiento->hora=date("h:i:s");
            $movimiento->tipo_movimiento= "Retorno";
            $movimiento->folio="RT-0".$request->partidaId."-".$partidaRetorno->articulo_id;
            $movimiento->proyecto_id= $request->proyectoId;
            $movimiento->lote_id=$request->loteId;
            $movimiento->stocke_id;
            $movimiento->almacene_id=$loteAlmacen->almacene_id;
            $movimiento->stand_id=$loteAlmacen->stand_id;
            $movimiento->nivel_id=$loteAlmacen->nivel_id;
            $movimiento->articulo_id=$partidaRetorno->articulo_id;
            Utilidades::auditar($movimiento, $movimiento->id);
            $movimiento->save();

            //Stock articulos
            $stock=StockArticulo::
            where("stocke_id","=",$loteAlmacen->stocke_id)
            ->where("articulo_id","=",$request->articuloId)
            ->firstOrFail();

            $stock->cantidad+=$validos;
            Utilidades::auditar($stock, $stock->id);
            $stock->save();
            DB::commit();

            //Actualizar cantidad en almacen
            return response()->json
            ([
                // "Id"         => $aux_articulo,
                "status"      => true,
                // "retorno"     => $partidaRetorno,
                // "partida"     => $partidaActualizar,
                // "loteAlmacen" => $loteAlmacen,
                // "existencia"  => $existencias,
                // "movimiento"  => $movimiento,
                // "stock"       => $stock,

            ]);
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return response()->json(["status"=>false,"error"=>$e->getMessage()]);
        }
    }
}
