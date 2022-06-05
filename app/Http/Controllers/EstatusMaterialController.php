<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proyecto;
use App\PartidaRe;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class EstatusMaterialController extends Controller
{
  //
  public function estatus($id)
  {
    $proyectos = Proyecto::where('id',$id)->first();

    $partidas_requisiciones_ = PartidaRe::join('requisiciones AS R','R.id','=','partidas_requisiciones.requisicione_id')
    ->join('articulos AS A','A.id','=','partidas_requisiciones.articulo_id')
    ->leftJoin('grupos AS G','G.id','=','A.grupo_id')
    ->leftJoin('requisicion_has_ordencompras AS RHOC', function($query){
      $query->on('RHOC.requisicione_id', '=', 'partidas_requisiciones.requisicione_id');
      $query->on('RHOC.articulo_id', '=', 'partidas_requisiciones.articulo_id');
    })
    ->leftJoin('ordenes_compras AS OC','OC.id','=','RHOC.orden_compra_id')
    ->select('partidas_requisiciones.*','G.nombre','OC.folio AS folio_orden_compra','R.folio AS folio_requisicion',
    'A.descripcion','RHOC.id AS rhoc_id','RHOC.imagen AS image',
    'RHOC.cantidad AS partida_orden_compra_cantidad',
    'OC.fecha_orden','OC.periodo_entrega','RHOC.comentario AS poc_comentario','OC.id AS oc_id')
    ->where('R.proyecto_id',$id)->where('partidas_requisiciones.articulo_id','!=','')->get();

    $partidas_requisiciones =PartidaRe::join('requisiciones AS R','R.id','partidas_requisiciones.requisicione_id')
    ->join('articulos AS A','A.id','partidas_requisiciones.articulo_id')
    ->leftJoin('grupos AS G','G.id','A.grupo_id')
    ->select('partidas_requisiciones.*','G.nombre','R.folio AS folio_requisicion',
    'A.descripcion','R.id AS requisicion_id')
    ->where('R.proyecto_id',$id)
    ->where('partidas_requisiciones.articulo_id','!=','')
    ->get();

    $arreglo = [];
    foreach ($partidas_requisiciones as $key => $value) {

      $rhoc = DB::table('requisicion_has_ordencompras AS RHOC')
      ->join('ordenes_compras AS OC','OC.id','=','RHOC.orden_compra_id')
      ->select('RHOC.id AS rhoc_id','RHOC.imagen AS image',
      'RHOC.cantidad AS partida_orden_compra_cantidad','OC.folio AS folio_orden_compra',
      'OC.fecha_orden','OC.periodo_entrega','RHOC.comentario AS poc_comentario','OC.id AS oc_id')
      ->where('RHOC.requisicione_id',$value->requisicione_id)
      ->where('RHOC.articulo_id',$value->articulo_id)
      ->first();

      $arreglo [] = [
        'requisicion' => $value,
        'oc' => $rhoc,
      ];
    }

    return response()->json($arreglo);
  }

  public function generaReporte($id)
  {

  }

  public function subirfoto(Request $request)
  {
    try {
      //obtiene el nombre del archivo y su extension
      $FacturaNE = $request->file('imagen')->getClientOriginalName();
      //Obtiene el nombre del archivo
      $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
      //obtiene la extension
      $FacturaExt = $request->file('imagen')->getClientOriginalExtension();
      //nombre que se guarad en BD
      $PdfStore = 'Imgssrc'.uniqid().'.'.$FacturaExt;
      //Subida del archivo al servidor ftp

      Storage::disk('local')->put('Fotos/'.$PdfStore, fopen($request->file('imagen'), 'r+'));

      $rhoc = \App\requisicionhasordencompras::where('id',$request->req_com_id)->first();
      $rhoc->imagen = 1;
      $rhoc->save();

      $rhoci = new \App\ImagenRHOC();
      $rhoci->imagen = $PdfStore;
      $rhoci->req_com_id = $request->req_com_id;
      $rhoci->save();


      return response()->json(array(
        'status' => true
      ));

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function subirfotorequi(Request $request)
  {
    try {
      //obtiene el nombre del archivo y su extension
      $FacturaNE = $request->file('imagen')->getClientOriginalName();
      //Obtiene el nombre del archivo
      $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
      //obtiene la extension
      $FacturaExt = $request->file('imagen')->getClientOriginalExtension();
      //nombre que se guarad en BD
      $PdfStore = 'Imgssrc'.uniqid().'.'.$FacturaExt;
      //Subida del archivo al servidor ftp

      Storage::disk('local')->put('Fotos/'.$PdfStore, fopen($request->file('imagen'), 'r+'));

      $rhoc = \App\PartidaRe::where('requisicione_id',$request->requisicione_id)
      ->where('articulo_id',$request->articulo_id)
      ->update(['imagen' => 1]);
      // $rhoc->imagen = 1;
      // $rhoc->save();

      $rhoci = new \App\ImagenPR();
      $rhoci->imagen = $PdfStore;
      $rhoci->requisicione_id = $request->requisicione_id;
      $rhoci->articulo_id = $request->articulo_id;
      $rhoci->save();


      return response()->json(array(
        'status' => true
      ));

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function ftpSolucion($id)
  {
    return Storage::response("Fotos/$id");
    // Se obtiene el archivo del local serve
    //Se busca en disk o carpeta -----
    if (Storage::exists('Fotos/'.$id)){
      // Se coloca el archivo en una ruta local
      $archivo = Storage::disk('local')->get('Fotos/'.$id);
    }else {
      $output = shell_exec("mkdir /var/www/html/conserflow2020/conserflow-sistema2018/storage/app/Fotos/ 2> errormk.txt;cp /home/vsftpuser/ftp/Fotos/$id /var/www/html/conserflow2020/conserflow-sistema2018/storage/app/Fotos/ 2> error.txt;");
      $archivo = Storage::disk('local')->get('Fotos/'.$id);
    }
    shell_exec("chmod 777 /var/www/html/conserflow2020/public/FilesFTP/".$id);
    return $archivo;
  }

  public function verimagenes($id)
  {
    try {
      $rhoci = \App\ImagenRHOC::where('req_com_id',$id)->get();
      return response()->json($rhoci);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function verimagenesreq($id)
  {
    try {
      $valores = explode('&',$id);
      $rhoci = \App\ImagenPR::where('requisicione_id',$valores[0])
      ->where('articulo_id',$valores[1])
      ->get();

      return response()->json($rhoci);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }


  public function guardarTmp($id)
  {
    try {
      $nombre_archivo = $id;

      $archivo = $this->ftpSolucionT($nombre_archivo);
      // Se coloca el archivo en una ruta local
      Storage::disk('temporal')->put($nombre_archivo, $archivo);

      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }

  public function ftpSolucionT($id)
  {
    // return Storagse::response("Empleados/$id");
    // Se obtiene el archivo del local serve
    //Se busca en disk o carpeta -----
    if (Storage::exists('Fotos/'.$id)){
      // Se coloca el archivo en una ruta local
      $archivo = Storage::disk('local')->get('Fotos/'.$id);
    }else {
      $output = shell_exec("mkdir /var/www/html/conserflow2020/conserflow-sistema2018/storage/app/Fotos/ 2> errormk.txt;cp /home/vsftpuser/ftp/Fotos/$id /var/www/html/conserflow2020/conserflow-sistema2018/storage/app/Fotos/ 2> error.txt;");
      $archivo = Storage::disk('local')->get('Fotos/'.$id);
    }
    // shell_exec("chmod 777 /var/www/html/conserflow2020/public/FilesFTP/".$id);
    return $archivo;
  }

  public function eliminarTmp($id)
  {
    try {
      $nombre_archivo = $id;
      // $archivo = $this->ftpSolucionT($nombre_archivo);
      // Se coloca el archivo en una ruta local
      Storage::disk('temporal')->delete($nombre_archivo);

      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }

  public function eliminarImg($id)
  {
    try {
      $rhoci =  \App\ImagenRHOC::where('id',$id)->delete();
      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }


    public function eliminarImgReq($id)
    {
      try {
        $rhoci =  \App\ImagenPR::where('id',$id)->delete();
        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }

    }

  public function descargarfoto($id)
  {
    try {

      $nombre_archivo = $id;
      // Se obtiene el archivo del FTP serve
      $archivo = $this->ftpSolucionT($nombre_archivo);
      // Se coloca el archivo en una ruta local
      Storage::disk('cotizaciones')->put($nombre_archivo, $archivo);


          return response()->json(array(
            'status' => true
          ));

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function descargar($id)
  {
    $archivo = $this->ftpSolucionT($id);
    // Se coloca el archivo en una ruta local
    Storage::disk('descarga')->put($id, $archivo);
    //--Devuelve la respuesta y descarga el archivo--//
    return response()->download(storage_path().'/app/descargas/'.$id);
  }
}
