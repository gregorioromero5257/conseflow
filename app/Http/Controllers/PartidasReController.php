<?php

namespace App\Http\Controllers;

use App\PartidaRe;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use \App\Http\Helpers\Utilidades;
use App\PartidaEntrada;
use App\Requisicionhasordencompras;

class PartidasReController extends Controller
{

  /**
   * [index Obtiene todos los registros de la tabla partidas_requisiciones respetando los filtros establecidos]
   * @return Response [description]
   */
  public function index()
  {
    $data = DB::table('partidas_requisiciones')
    ->leftJoin('articulos AS a', 'a.id' ,'=', 'partidas_requisiciones.articulo_id')
    ->leftJoin('requisiciones AS r', 'r.id', '=', 'partidas_requisiciones.requisicione_id')
    ->leftJoin('proyectos AS p', 'p.id', '=' , 'r.proyecto_id')
    ->select('partidas_requisiciones.*','a.id AS ida','r.id AS rid','a.descripcion AS descripciona','p.nombre AS proyecton','p.id AS proyectoi','r.folio AS rf','r.fecha_solicitud AS rfs')
    ->where('partidas_requisiciones.condicion', '=', '1')
    ->where('r.estado_id','=','5')
    ->get();
    return response()->json($data);
  }

  /**
   * [show Funcion que se utiliza para ver las requisiciones autorizadas para ser agregadas en las partidas de compras
   * ]
   * @param  Int $id [description]
   * @return Response     [description]
   */
  public function show($id)
  {
    $data_art = DB::table('partidas_requisiciones')
    ->join('articulos AS a', 'a.id' ,'=', 'partidas_requisiciones.articulo_id')
    ->leftJoin('requisiciones AS r', 'r.id', '=', 'partidas_requisiciones.requisicione_id')
    ->leftJoin('proyectos AS p', 'p.id', '=' , 'r.proyecto_id')
    ->select('partidas_requisiciones.*','a.centro_costo_id AS centro_costo','a.unidad','a.id AS ida','r.id AS rid','a.descripcion AS descripciona'
    ,'p.nombre AS proyecton','p.id AS proyectoi','r.folio AS rf','r.fecha_solicitud AS rfs')
    ->where('p.id','=',$id)
    ->where('partidas_requisiciones.condicion', '=', '1')
    ->where('partidas_requisiciones.cantidad_compra','>','0')
    ->whereIn('r.estado_id',['5','9'])
    ->where('partidas_requisiciones.articulo_id','!=','null')
    ->get()->toArray();
    $data_serv = DB::table('partidas_requisiciones')
    ->join('servicios AS s', 's.id' ,'=', 'partidas_requisiciones.servicio_id')
    ->leftJoin('requisiciones AS r', 'r.id', '=', 'partidas_requisiciones.requisicione_id')
    ->leftJoin('proyectos AS p', 'p.id', '=' , 'r.proyecto_id')
    ->select('partidas_requisiciones.*','s.centro_costos_id AS centro_costo','s.id AS ida',
    'r.id AS rid',DB::raw("CONCAT(s.nombre_servicio,' ',s.proveedor_marca) AS descripciona"),'p.nombre AS proyecton',
    'p.id AS proyectoi','r.folio AS rf','r.fecha_solicitud AS rfs')
    ->where('p.id','=',$id)
    ->where('partidas_requisiciones.condicion', '=', '1')
    ->where('partidas_requisiciones.cantidad_compra','>','0')
    ->whereIn('r.estado_id',['5','9'])
    ->where('partidas_requisiciones.servicio_id','!=','null')
    ->get()->toArray();

    $data = array_merge($data_art, $data_serv);
    return response()->json($data);
  }

  public function getApartados($id)
  {
    $data_art = DB::table('partidas_requisiciones')
    ->join('articulos AS a', 'a.id' ,'=', 'partidas_requisiciones.articulo_id')
    ->leftJoin('requisiciones AS r', 'r.id', '=', 'partidas_requisiciones.requisicione_id')
    ->leftJoin('proyectos AS p', 'p.id', '=' , 'r.proyecto_id')
    ->select('partidas_requisiciones.cantidad_almacen as cantidad_compra','partidas_requisiciones.requisicione_id',
    'partidas_requisiciones.articulo_id','partidas_requisiciones.servicio_id','partidas_requisiciones.pda'
    ,'a.centro_costo_id AS centro_costo','a.unidad','a.id AS ida','r.id AS rid','a.descripcion AS descripciona'
     ,'p.nombre AS proyecton','p.id AS proyectoi','r.folio AS rf','r.fecha_solicitud AS rfs')
    ->where('p.id','=',$id)
    // ->where('partidas_requisiciones.condicion', '=', '1')
    ->whereIn('r.estado_id',['5','9'])
    ->where('r.pendiente','1')
    ->where('partidas_requisiciones.apartado_comprado','0')
    ->where('partidas_requisiciones.articulo_id','!=','null')
    ->where('partidas_requisiciones.cantidad_almacen','!=','0')
    ->get()->toArray();

    return response()->json($data_art);
  }

  public function requisicioncompserart($id)
  {

    $arreglo_art =[];
    $arreglo_serv =[];
    $data_art = DB::table('partidas_requisiciones')
    ->join('articulos AS a', 'a.id' ,'=', 'partidas_requisiciones.articulo_id')
    ->leftJoin('requisiciones AS r', 'r.id', '=', 'partidas_requisiciones.requisicione_id')
    ->leftJoin('proyectos AS p', 'p.id', '=' , 'r.proyecto_id')
    ->select('partidas_requisiciones.*','a.id AS ida','r.id AS rid','a.descripcion AS descripciona','p.nombre AS proyecton','p.id AS proyectoi',
    'r.folio AS rf','r.fecha_solicitud AS rfs','a.unidad AS um')
    ->where('partidas_requisiciones.requisicione_id','=',$id)
    ->where('partidas_requisiciones.articulo_id','!=','null')
    ->get();

    foreach ($data_art as $k_art => $art) {
      $comentarios = DB::table('incidencias_requisiciones')->where('requisicion_id',$art->requisicione_id)
      ->where('pda',$art->pda)->where('articulo_servicio','1')
      ->where('articulo_servicio_id',$art->articulo_id)
      ->where('activo','1')
      ->first();

      $arreglo_art [] = [
        'req' => $art,
        'correccion' => $comentarios,
      ];
    }

    $data_serv = DB::table('partidas_requisiciones')
    ->join('servicios AS s', 's.id' ,'=', 'partidas_requisiciones.servicio_id')
    ->leftJoin('requisiciones AS r', 'r.id', '=', 'partidas_requisiciones.requisicione_id')
    ->leftJoin('proyectos AS p', 'p.id', '=' , 'r.proyecto_id')
    ->select('partidas_requisiciones.*','s.id AS ida','r.id AS rid',DB::raw("CONCAT(s.nombre_servicio,' ',s.proveedor_marca) AS descripciona"),
    'p.nombre AS proyecton','p.id AS proyectoi','r.folio AS rf','r.fecha_solicitud AS rfs','s.unidad_medida AS um')
    ->where('partidas_requisiciones.requisicione_id','=',$id)
    ->where('partidas_requisiciones.servicio_id','!=','null')
    ->get()->toArray();

    foreach ($data_serv as $k_serv => $serv) {
      $comentarios = DB::table('incidencias_requisiciones')->where('requisicion_id',$serv->requisicione_id)
      ->where('pda',$serv->pda)->where('articulo_servicio','0')
      ->where('articulo_servicio_id',$serv->servicio_id)
      ->where('activo','1')
      ->first();

      $arreglo_serv [] = [
        'req' => $serv,
        'correccion' => $comentarios,
      ];
    }

    $data = array_merge($arreglo_art, $arreglo_serv);

    return response()->json($data);
  }

  /**
   * [store Guarda en el modelo PartidaRe]
   * @param  Request $request [description]
   * @return Response           [description]
   */
  public function store(Request $request)
  {
    try {


          if (!$request->ajax()) return redirect('/');

          if ($request->pendiente != 0) {
            $par_entrada = PartidaEntrada::find($request->part_entr);
            $par_entrada->pendiente = 2;
            $par_entrada->save();

            $reqhasordcomp = Requisicionhasordencompras::find($request->req_com_id);
            $reqhasordcomp->requisicione_id = $request->requisicione_id;
            $reqhasordcomp->antigua = $request->req_antigua;
            $reqhasordcomp->save();
          }
          // $pda_doc = Utilidades::pdaDoc($request->requisicione_id);
          //Validar Articulos
          if ($request->articulo_id != null) {
            $consulta = PartidaRe::select('partidas_requisiciones.*')
              ->where('requisicione_id','=',$request->requisicione_id)
              ->where('articulo_id','=',$request->articulo_id)
              ->count();



            if ($consulta>0) {
              return response()->json(array(
                'status' => false
              ));
            }
            else{
              $partidare = new PartidaRe();
              $pda = Utilidades::crearPda($request->requisicione_id);
              $request->merge(['pda' => $pda]);
              $partidare->fill($request->all());
              $partidare->save();

              if($request->documentacionreid != ''){
                for ($i=0; $i < count($request->documentacionreid) ; $i++) {
                    $documentos = new \App\PartidaRequisicionDoc();
                    $documentos->documento_id = $request->documentacionreid[$i];
                    $documentos->partidarequisicione_art = $request->articulo_id;
                    $documentos->partidarequisicione_req = $request->requisicione_id;
                    $documentos->partidarequisicione_serv = null;
                    // $documentos->pda =$pda_doc;
                    $documentos->save();
                }
              }

              return response()->json(array(
                'status' => true
              ));

            }

          }
          //Fin Validar Articulos

          //Validar Servicios
          if ($request->servicio_id != null) {
            // $consulta = PartidaRe::select('partidas_requisiciones.*')
            //   ->where('requisicione_id','=',$request->requisicione_id)
            //   ->where('servicio_id','=',$request->servicio_id)
            //   ->count();
            //
            // if ($consulta>0) {
            //   return response()->json(array(
            //     'status' => false
            //   ));
            // }

            // else{

              $partidare = new PartidaRe();
              $pda = Utilidades::crearPda($request->requisicione_id);
              $request->merge(['pda' => $pda]);
              $partidare->fill($request->all());
              $partidare->save();

              if($request->documentacionreid != ''){
                for ($i=0; $i < count($request->documentacionreid) ; $i++) {
                    $documentos = new \App\PartidaRequisicionDoc();
                    $documentos->documento_id = $request->documentacionreid[$i];
                    $documentos->partidarequisicione_art = null;
                    $documentos->partidarequisicione_req = $request->requisicione_id;
                    $documentos->partidarequisicione_serv = $request->servicio_id;
                    // $documentos->pda = $pda_doc;
                    $documentos->save();
                }
              }

              return response()->json(array(
                'status' => true
              ));

            // }
          }
          //Fin Validar Servicios

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }


  }

  /**
   * [edit Elimina un registro en el modelo Requisicionhasordencompras y en la tabla partidas_requisiciones]
   * @param  Int $id [description]
   * @return Response     [description]
   */
  public function edit($id){
    $valores = explode("&",$id);
    $servicio = $valores[2];
    $articulo = $valores[0];

    /*Verificar Servicio*/
    if ($servicio != "null") {

      DB::table('partidas_requisiciones')->where([['pda', '=',  $valores[2]],['requisicione_id', '=', $valores[1]],])->delete();
      DB::table('partidarequisicion_documentos')->where([['pda', '=',  $valores[2]],['partidarequisicione_req', '=', $valores[1]],])->delete();
      return response()->json(array(
        'status' => true,
        'servicio' =>$servicio,
        'articulo' =>$articulo
      ));
    }
    /**/

    /*Verificar Articulo*/
    if ($articulo != "null") {

      /********************************************************************/
      $datos = DB::table('partidas_requisiciones')
        ->select('partidas_requisiciones.*')
        ->where('pda', '=',  $valores[0])
        ->where('requisicione_id', '=', $valores[1])
        ->get();

      $reqhasoc = DB::table('requisicion_has_ordencompras')
        ->select('requisicion_has_ordencompras.*')
        ->where('requisicion_has_ordencompras.requisicione_id', '=', $valores[1])
        ->where('requisicion_has_ordencompras.articulo_id', '=', $datos[0]->articulo_id)
        ->get();

      if (count($reqhasoc) != 0) {
        $actualizar = Requisicionhasordencompras::findOrFail($reqhasoc[0]->id);
        $actualizar->requisicione_id = $reqhasoc[0]->antigua;
        $actualizar->antigua = 0;
        $actualizar->save();

        $part_entr = DB::table('partidas_entradas')
          ->select('partidas_entradas.*')
          ->where('partidas_entradas.req_com_id', '=', $reqhasoc[0]->id)
          ->where('partidas_entradas.articulo_id', '=', $reqhasoc[0]->articulo_id)
          ->get();
        //return response()->json($part_entr[0]);

        $actualizar2 = PartidaEntrada::findOrFail($part_entr[0]->id);
        $actualizar2->pendiente = 1;
        $actualizar2->save();
      }
      /************************************************************************/



      DB::table('partidas_requisiciones')->where([['pda', '=',  $valores[0]],['requisicione_id', '=', $valores[1]],])->delete();
      DB::table('partidarequisicion_documentos')->where([['pda', '=',  $valores[0]],['partidarequisicione_req', '=', $valores[1]],])->delete();

      return response()->json(array(
        'status' => true,
        'servicio' =>$servicio,
        'articulo' =>$articulo
      ));
    }
    /**/

  }

/**
 * [activ Actualiza el campo condicion de la tabla $partida_requisiciones]
 * @param  Int $id [description]
 * @return Response     [description]
 */
  public function activ($id)
  {
    $valores = explode("&",$id);
    if ($valores[1] != null) {
      $partidas = \App\PartidaRe::where('articulo_id','=',$valores[0])
      ->where('requisicione_id','=',$valores[1])
      ->update(['condicion' => 1]);
      // ->first();
    }
    if($valores[2] != null) {
      $partidas = \App\PartidaRe::where('servicio_id','=',$valores[2])
      ->where('requisicione_id','=',$valores[1])
      ->update(['condicion' => 1]);
      // ->first();
    }
    return response()->json(array('status' => true));


  }

/**
 * [update description]
 * @param  Request $request [description]
 * @return [type]           [description]
 */
  public function update(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $partidare = \App\PartidaRe::select('partidas_requisiciones.*')
    ->where('requisicione_id','=',$request->requisicione_id)
    ->where('pda','=',$request->pda)->update([
      'peso' => $request->peso,
      'cantidad' => $request->cantidad,
      'cantidad_compra' => $request->cantidad,
      'cantidad_almacen' => 0,
      'equivalente' => $request->equivalente,
      'fecha_requerido' => $request->fecha_requerido,
      'comentario' => $request->comentario,
      'produccion' => $request->produccion,
    ]);

    // $partidas = \App\PartidaRe::where('requisicione_id','=',$request->requisicione_id)
    // ->where('pda','=',$request->pda)
    // ->update(['condicion' => 1]);

    // $partidare->peso = $request->peso;
    // $partidare->cantidad = $request->cantidad;
    // $partidare->equivalente = $request->equivalente;
    // $partidare->fecha_requerido = $request->fecha_requerido;
    // $partidare->comentario = $request->comentario;
    // $partidare->update();
    return response()->json(array(
      'status' => true
    ));

    // return response()->json($partidare);
  }

  /**
   * [updatedoc Actualiza los documentos requeridos por cada partida de una requisicion]
   * @param  Request $request [description]
   * @return Response           [description]
   */
  public function updatedoc(Request $request)
  {
    $auth = Auth::id();
    $users = \App\User::where('id','=',$auth)->first();
    /********/
    if ($request->partidarequisicione_art != null) {
      DB::table('partidarequisicion_documentos')
      ->where([['partidarequisicione_art',  '=',  $request->partidarequisicione_art],['partidarequisicione_req', '=', $request->partidarequisicione_req]])->delete();

      if($request->documento_id != ''){
        for ($i=0; $i < count($request->documento_id) ; $i++) {
          $documentos = new \App\PartidaRequisicionDoc();
          $documentos->documento_id = $request->documento_id[$i];
          $documentos->partidarequisicione_art = $request->partidarequisicione_art;
          $documentos->partidarequisicione_req = $request->partidarequisicione_req;
          $documentos->empleado_valida = $users->empleado_id;
          $documentos->save();


            }
      }

      return response()->json(array(
        'status' => true
      ));
    }

    if ($request->partidarequisicione_serv!= null) {
      DB::table('partidarequisicion_documentos')
      ->where([['partidarequisicione_serv',  '=',  $request->partidarequisicione_serv],['partidarequisicione_req', '=', $request->partidarequisicione_req]])->delete();

      if($request->documento_id != ''){
        for ($i=0; $i < count($request->documento_id) ; $i++) {
          $documentos = new \App\PartidaRequisicionDoc();
          $documentos->documento_id = $request->documento_id[$i];
          $documentos->partidarequisicione_serv = $request->partidarequisicione_serv;
          $documentos->partidarequisicione_req = $request->partidarequisicione_req;
          $documentos->empleado_valida = $users->empleado_id;
          $documentos->save();
            }
      }

      return response()->json(array(
        'status' => true
      ));
    }
    /********/

  }

  /**
   * [activar description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function activar(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $requi = PartidaRe::findOrFail($request->articulo_id);
    $requi->condicion = '1';
    $requi->save();
  }
}
