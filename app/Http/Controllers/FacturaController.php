<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Helpers\Utilidades;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class FacturaController extends Controller
{

  /**
  * [Definición de las reglas para ser utilizadas en la actualizacion y la insercion]
  */
  protected $rules = array(
    //  'folio' => 'required|max:30',
    'tipo_factura_id' => 'required',
    'cliente_id' => 'required',
    'codigo_postal' => 'required',
    'moneda_id' => 'required',
    'formapago' => 'required',
    'metodopago' => 'required',
    'tipo_cambio' => 'required',
    'uso_factura' => 'required',

  );


  /**
  * [Consulta en BD los registro de la tabla factura con sus respectivas tablas recacionadas]
  * @return Response [Array de tipo JSON]
  */
  public function index()
  {
    $facturas = \App\Factura::
    join('datosgenerales AS dg','dg.id','=','factura.emisor_id')
    ->join('sat_cat_tipofactura AS sctf','sctf.id','=','factura.tipo_factura_id')
    ->join('clientes AS c','c.id','=','factura.cliente_id')
    ->join('sat_cat_usocfdi AS scuc','scuc.id','=','factura.uso_factura')
    ->join('sat_cat_monedas AS scm','scm.id','=','factura.moneda_id')
    ->join('sat_cat_formapago AS scfp','scfp.id','=','factura.formapago')
    ->join('sat_cat_metodopago AS scmp','scmp.id','=','factura.metodopago')
    ->leftJoin('sat_cat_tiporelacion AS sctr','sctr.id','=','factura.tipo_relacion')
    ->leftJoin('factura AS f','f.id','=','factura.factura_id')
    ->select('factura.*',
    'dg.rfc AS rfc_emisor','dg.razon_social AS razons_emisor','dg.nombre AS nombre_emisor','dg.regimenfiscal AS regimenfiscal_emisor',
    'sctf.c_tipofactura','sctf.descripcion AS descripcion_tipofactura',
    'c.nombre AS nombre_razons_receptor','c.rfc AS rfc_receptor','c.contacto AS correo_receptor',
    'scuc.c_uso','scuc.descripcion AS usocfdi_descripcion',
    'scm.c_moneda','scm.descripcion AS monedas_descripcion',
    'scfp.clave AS clave_formapago','scfp.descripcion AS descripcion_formapago',
    'scmp.c_metodopago','scmp.descripcion AS descripcion_metodopago','f.serie AS serie_relacionado','f.folio AS folio_relacionado',
    'f.uuid AS uuid_relacionado_f')
    ->get();
    return response()->json($facturas);
  }

  /**
  * [Consulta en BD de la tabla factura donde id = $id proporcionado]
  * @param  Int      $id [id del GET]
  * @return Response     [Array en formato JSON]
  */
  public function show($id)
  {
    $factura = \App\Factura::
    join('datosgenerales AS dg','dg.id','=','factura.emisor_id')
    ->join('sat_cat_tipofactura AS sctf','sctf.id','=','factura.tipo_factura_id')
    ->join('clientes AS c','c.id','=','factura.cliente_id')
    ->join('sat_cat_usocfdi AS scuc','scuc.id','=','factura.uso_factura')
    ->join('sat_cat_monedas AS scm','scm.id','=','factura.moneda_id')
    ->join('sat_cat_formapago AS scfp','scfp.id','=','factura.formapago')
    ->join('sat_cat_metodopago AS scmp','scmp.id','=','factura.metodopago')
    ->leftJoin('sat_cat_tiporelacion AS sctr','sctr.id','=','factura.tipo_relacion')
    ->leftJoin('factura AS f','f.id','=','factura.factura_id')
    ->select('factura.*',
    'dg.rfc AS rfc_emisor','dg.razon_social AS razons_emisor','dg.nombre AS nombre_emisor','dg.regimenfiscal AS regimenfiscal_emisor',
    'sctf.c_tipofactura','sctf.descripcion AS descripcion_tipofactura',
    'c.nombre AS nombre_razons_receptor','c.rfc AS rfc_receptor','c.contacto AS correo_receptor',
    'scuc.c_uso','scuc.descripcion AS usocfdi_descripcion',
    'scm.c_moneda','scm.descripcion AS monedas_descripcion',
    'scfp.clave AS clave_formapago','scfp.descripcion AS descripcion_formapago',
    'scmp.c_metodopago','scmp.descripcion AS descripcion_metodopago','f.serie AS serie_relacionado','f.folio AS folio_relacionado','f.uuid AS uuid_relacionado_f')
    ->where('factura.id',$id)->first();
    return response()->json($factura);
  }

  /**
  * [Guarda en BD los encabezados en la tabla factura respetando las condiciones y reglas
  *  establecidas, ademas del tipo de factura]
  * @param Request   $request [Objeto de datos del POST]
  * @return Response          [Array con estatus true y emisor_id de tipo entero]
  */
  public function store(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), $this->rules);

      if ($validator->fails()) {
        return response()->json(array(
          'status' => false,
          'errors' => $validator->errors()->all()
        ));
      }

      $factura = new \App\Factura();
      $folio = Utilidades::getFolioFactura($request->emisor_id,$request->tipo_factura_id);
      $request->merge(['serie' => $folio['serie'], 'folio' => $folio['folio']]);
      $factura->fill($request->all());
      Utilidades::auditar($factura, $factura->id);
      $factura->save();

      if ($request->relacionados != []) {
        $eliminar_relacionados = \App\Relacionados::where('factura_id',$factura->id)->delete();
        foreach ($request->relacionados as $key => $value) {
          $a = explode(' ',$value['name']);
          $relacionados = new \App\Relacionados();
          $relacionados->factura_id = $factura->id;
          $relacionados->uuid = count($a) == 1 ? $value['name'] : $a[2];
          // $relacionados->uuid = $a[2];
          $relacionados->save();
        }
      }

      if($request->tipo_factura_id == 4){
        $cable_cat_proser = \DB::table('sat_cat_prodser')->where('clave','=','84111506')->first();
        $cable_cat_unidad = \DB::table('sat_cat_unidades')->where('c_unidad','=','ACT')->first();

        $partidas_fac = new \App\PartidasFactura();
        $partidas_fac->productos_servicios_id = $cable_cat_proser->id;
        $partidas_fac->unidad_id = $cable_cat_unidad->id;
        $partidas_fac->cantidad = 1.00;
        $partidas_fac->unidad = '-';
        $partidas_fac->numero_identificacion = '-';
        $partidas_fac->descripcion = 'Pago';
        $partidas_fac->valor_unitario = 0.00;
        $partidas_fac->importe = 0.00;
        $partidas_fac->impuesto_iva = 0.00;
        $partidas_fac->factura_id = $factura->id;
        Utilidades::auditar($partidas_fac, $partidas_fac->id);
        $partidas_fac->save();
      }
      return response()->json(array('status' => true,'emisor_id' => $request->emisor_id));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return response()->json(array('status' => 'error'));
    }


  }
  //
  // public function crearEgresoAnticipo($id){
  //
  // }

  /**
  * [Actualiza en BD los registros de la tabla factura apartir de un id proporcionado respetando
  * las reglas definidas]
  * @param  Request  $request [Objeto de datos del PUT]
  * @param  Int      $id      [id del PUT]
  * @return Response          [Array con estatus true y emisor_id de tipo entero]
  */
  public function update(Request $request,$id)
  {
    try {

      $validator = Validator::make($request->all(), $this->rules);

      if ($validator->fails()) {
        return response()->json(array(
          'status' => false,
          'errors' => $validator->errors()->all()
        ));
      }
      $factura = \App\Factura::where('id',$id)->first();
      $factura->fill($request->all());
      Utilidades::auditar($factura, $factura->id);
      $factura->save();

      if ($request->relacionados != []) {
        $eliminar_relacionados = \App\Relacionados::where('factura_id',$factura->id)->delete();
        foreach ($request->relacionados as $key => $value) {
          $a = explode(' ',$value['name']);

          $relacionados = new \App\Relacionados();
          $relacionados->factura_id = $factura->id;
          $relacionados->uuid = (count($a) == 1) ? $value['name'] : $a[2];
          $relacionados->save();
        }
      }

      return response()->json(array('status' => true,'emisor_id' => $request->emisor_id));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return response()->json(array('status' => 'error'));
    }

  }

  /**
  * [Extrae en BD los datos de la tabla clientes para una factura al extranjero]
  * @return Response [Array en formato JSON]
  */
  public function clientextranjero()
  {
    $clientex = \App\Clientes::where('rfc','XEXX010101000')->first();
    return response()->json($clientex);
  }

  /**
  * [Cuenta los timbres restantes para cada emisor existente en la BD apartir de las facturas timbradas]
  * @return Response [Array con las cantidad de emisores y sus timbres restantes]
  */
  public function timbresrestantes()
  {
    $cantidad = \DB::select("SELECT COUNT(emisor_id) as contador FROM factura where timbrado = 1 OR timbrado = 2 group by emisor_id");
    $c = 0;
    $d = 0;
    if (count($cantidad) != 0) {
      $c = 500 - $cantidad[0]->contador;
    }

    return [
      'c' => $c,
      'd' => $d,
    ];
    // return response()->json();
  }

  /**
  * [Consulta en BD los registros de la tabla factura en relacion al id proporcionado]
  * @param  Int      $id [id del GET]
  * @return Response     [Array en formato JSON]
  */
  public function verfacturauno($id){
    $facturas = \App\Factura::
    join('datosgenerales AS dg','dg.id','=','factura.emisor_id')
    ->join('sat_cat_tipofactura AS sctf','sctf.id','=','factura.tipo_factura_id')
    ->join('clientes AS c','c.id','=','factura.cliente_id')
    ->join('sat_cat_usocfdi AS scuc','scuc.id','=','factura.uso_factura')
    ->join('sat_cat_monedas AS scm','scm.id','=','factura.moneda_id')
    ->join('sat_cat_formapago AS scfp','scfp.id','=','factura.formapago')
    ->join('sat_cat_metodopago AS scmp','scmp.id','=','factura.metodopago')
    ->leftJoin('sat_cat_tiporelacion AS sctr','sctr.id','=','factura.tipo_relacion')
    ->leftJoin('factura AS f','f.id','=','factura.factura_id')
    ->select('factura.*',
    'dg.rfc AS rfc_emisor','dg.razon_social AS razons_emisor','dg.nombre AS nombre_emisor','dg.regimenfiscal AS regimenfiscal_emisor',
    'sctf.c_tipofactura','sctf.descripcion AS descripcion_tipofactura',
    'c.nombre AS nombre_razons_receptor','c.rfc AS rfc_receptor','c.contacto AS correo_receptor',
    'scuc.c_uso','scuc.descripcion AS usocfdi_descripcion',
    'scm.c_moneda','scm.descripcion AS monedas_descripcion',
    'scfp.clave AS clave_formapago','scfp.descripcion AS descripcion_formapago',
    'scmp.c_metodopago','scmp.descripcion AS descripcion_metodopago','f.serie AS serie_relacionado',
    'f.folio AS folio_relacionado','f.uuid AS uuid_relacionado_f')
    ->where('factura.emisor_id',$id)
    ->orderBy('factura.id', 'desc');
    return $this->busqueda($facturas);
  }


  public function busqueda($dato)
  {
    extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));

    $data = $dato;

    if (isset($query) && $query) {
      $data = $byColumn == 1 ?
      Utilidades::busqueda_filterByColumn($data, $query) :
      Utilidades::busqueda_filter($data, $query, $fields);
    }

    $count = $data->count();

    $data->limit($limit)
    ->skip($limit * ($page - 1));

    if (isset($orderBy)) {
      $direction = $ascending == 1 ? 'ASC' : 'DESC';
      $data->orderBy($orderBy, $direction);
    }

    $results = $data->get();

    return [
      'data' => $results,
      'count' => $count,
    ];
  }


  /**
  * [Descarga del archivo XML generado y almacenado en el servidor configurado]
  * @param  String $id [Nombre del archivo]
  * @return         [objeto de tipo descarga con la url del archivo]
  */
  public function descargarfacturaxml($id)
  {
    // Se obtiene el archivo del FTP serve
    $archivo = Storage::disk('local')->get('Facturas/'.$id);
    // Se coloca el archivo en una ruta local
    Storage::disk('descarga')->put($id, $archivo);
    //--Devuelve la respuesta y descarga el archivo--//
    return response()->download(storage_path().'/app/descargas/'.$id);
  }

  /**
  * [Elimina de la ruta local el archivo descargado]
  * @param String $id [Nombre del archivo]
  */
  public function destroyxml($id)
  {
    Storage::disk('descarga')->delete($id);
  }

  public function agregar()
  {

    $factura = DB::table('factura')->where('tipo_relacion','!=','""')->get();

    foreach ($factura as $key => $value) {

      if ($value->uuid != '') {
        $relacionados = new \App\Relacionados();
        $relacionados->factura_id = $value->id;
        $relacionados->uuid = $value->uuid;
        $relacionados->save();
      }

      if ($value->factura_id != '') {
        $factura_id = DB::table('factura')->where('id',$value->factura_id)->first();

        $relacionados = new \App\Relacionados();
        $relacionados->factura_id = $value->id;
        $relacionados->uuid = $factura_id->uuid;
        $relacionados->save();

      }

    }
    return response()->json(['status' => true]);

  }

  public function eliminarAsignacion($id)
  {
    $a = \App\Relacionados::where('id',$id)->delete();
    return response()->json(['status' => true]);
  }

  public function getAsignacion($id)
  {
    $a = \App\Relacionados::where('factura_id',$id)->get();
    return response()->json($a);
  }

}
