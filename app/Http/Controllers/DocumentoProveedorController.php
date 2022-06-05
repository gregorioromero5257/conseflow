<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lote;
use Carbon\Carbon;
use Validator;
use \App\Http\Helpers\Utilidades;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use File;
use Illuminate\Support\Facades\Storage;


class DocumentoProveedorController extends Controller
{
  public function index(){
//    if (!$request->ajax()) return redirect('/');
        $documentos =DB::table('documentos_proveedores')->select('documentos_proveedores.*')->get();
    return response()->json($documentos);
  }
  public function show($id){
    $valores = explode('&',$id);
    $documentos_partida = DB::table('partidarequisicion_documentos')
    ->leftJoin('documentos_proveedores','documentos_proveedores.id','=','partidarequisicion_documentos.documento_id')
    ->leftJoin('partidas_requisiciones AS PR', function($join)
    {
      $join->on('PR.requisicione_id', '=', 'partidarequisicion_documentos.partidarequisicione_req')
      ->on('PR.articulo_id', '=', 'partidarequisicion_documentos.partidarequisicione_art');
    })
    ->leftJoin('requisicion_has_ordencompras AS RHC', function($join){
      $join->on('RHC.articulo_id','=','PR.articulo_id')
      ->on('RHC.requisicione_id','=','PR.requisicione_id');
    })
    ->leftJoin('partidas_entradas AS PE', function($join){
      $join->on('PE.articulo_id','=','RHC.articulo_id')
      ->on('PE.req_com_id','=','RHC.id');
    })
    ->select('partidarequisicion_documentos.*','documentos_proveedores.nombre')
    ->where('PE.req_com_id','=',$valores[0])
    ->where('PE.articulo_id','=',$valores[1])
    ->where('partidarequisicion_documentos.condicion','=','0')
    ->get();
    return response()->json($documentos_partida);

  }
  public function load(Request $request)
  {
    //obtiene el nombre del archivo y su extension
    $AdjuntoNE = $request->file('adjunto')->getClientOriginalName();
    //Obtiene el nombre del archivo
    $AdjuntoNombre = pathinfo($AdjuntoNE, PATHINFO_FILENAME);
    //obtiene la extension
    $AdjuntoExt = $request->file('adjunto')->getClientOriginalExtension();
    //nombre que se guarad en BD
    $AdjuntoStore = $AdjuntoNombre.'_'.uniqid().'.'.$AdjuntoExt;
    //Subida del archivo al servidor ftp
    Storage::disk('local')->put('Archivos/'.$AdjuntoStore, fopen($request->file('adjunto'), 'r+'));

    $qualitycert = new \App\QualityCert();
      $qualitycert->adjunto = $AdjuntoStore;
      $qualitycert->partidaentrada_id = $request->partidaentrada_id;
      $qualitycert->documento_id = $request->documento_id;
      $qualitycert->estado_documento = $request->estado_documento;
      $qualitycert->empleado_responsable_id = $request->empleado_responsable_id;
      $qualitycert->save();

    $orden_compra_id = $request->orden_compra_id;
    $articulo_id = $request->articulo_id;
    $documento_id = $request->documento_id;
    $documentos_partida = DB::table('partidarequisicion_documentos')
    ->leftJoin('documentos_proveedores','documentos_proveedores.id','=','partidarequisicion_documentos.documento_id')
    ->leftJoin('partidas_requisiciones AS PR', function($join)
    {
      $join->on('PR.requisicione_id', '=', 'partidarequisicion_documentos.partidarequisicione_req')
      ->on('PR.articulo_id', '=', 'partidarequisicion_documentos.partidarequisicione_art');
    })
    ->leftJoin('requisicion_has_ordencompras AS RHC', function($join){
      $join->on('RHC.articulo_id','=','PR.articulo_id')
      ->on('RHC.requisicione_id','=','PR.requisicione_id');
    })
    ->leftJoin('partidas_entradas AS PE', function($join){
      $join->on('PE.articulo_id','=','RHC.articulo_id')
      ->on('PE.req_com_id','=','RHC.id');
    })
    ->select('partidarequisicion_documentos.*')
    ->where('PE.req_com_id','=',$orden_compra_id)
    ->where('PE.articulo_id','=',$articulo_id)
    ->where('partidarequisicion_documentos.documento_id','=',$documento_id)
    ->where('partidarequisicion_documentos.condicion','=','0')
    ->update(['partidarequisicion_documentos.condicion' => 1]);


    return response()->json(array('status' => true));
    //return response()->json($AdjuntoStore);

  }

  public function requisicionesdocumentospendientes()
  {
    $requisiciones = DB::table('partidarequisicion_documentos')
    ->leftJoin('documentos_proveedores AS DC','DC.id','=','partidarequisicion_documentos.documento_id')
    ->leftJoin('requisiciones AS R','R.id','=','partidarequisicion_documentos.partidarequisicione_req')
    ->leftJoin('proyectos AS P','P.id','=','R.proyecto_id')
    ->select('R.*','P.nombre AS nombre_proyecto')
    ->where('partidarequisicion_documentos.condicion','=','0')
    ->where('R.estado_id','=','5')
    ->distinct()->get();

    return response()->json($requisiciones);
  }

  public function documentospendientes($id)
  {
    $documentos_partidas = DB::table('partidarequisicion_documentos')
    ->leftJoin('documentos_proveedores AS DC','DC.id','=','partidarequisicion_documentos.documento_id')
    ->leftJoin('requisiciones AS R','R.id','=','partidarequisicion_documentos.partidarequisicione_req')
    ->leftJoin('proyectos AS P','P.id','=','R.proyecto_id')
    ->select('R.*','P.nombre AS nombre_proyecto','DC.id  AS id_doc')
    ->where('partidarequisicion_documentos.condicion','=','0')
    ->where('R.estado_id','=','5')
    ->where('R.id','=',$id)
    ->get();
    return response()->json($documentos_partidas);
  }

  public function descargardocumentos($id)
  {

     // Se obtiene el archivo del FTP serve

     $archivo = Utilidades::ftpSolucion($id);
     // Se coloca el archivo en una ruta local
     Storage::disk('descarga')->put($id, $archivo);
     //--Devuelve la respuesta y descarga el archivo--//
     return response()->download(storage_path().'/app/descargas/'.$id);
  }


}
