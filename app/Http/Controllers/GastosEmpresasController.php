<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\GastosEmpresasImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use App\FacturasGastosEmpresa;
use App\Compras;
use App\GastosEmpresas;
use App\Imports\XmlImport;
use Illuminate\Support\Facades\Storage;



class GastosEmpresasController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    //
  }

  /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function create()
  {
    //
    // $FacturaStore='';
    // $tipos = explode(',',$request->tipos);
    // $valor = explode(',',$request->valor);
    //
    // if ($request->hasFile('comprobante')) {
    //   //obtiene el nombre del archivo y su extension
    //   $FacturaNE = $request->file('comprobante')->getClientOriginalName();
    //   //Obtiene el nombre del archivo
    //   $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
    //   //obtiene la extension
    //   $FacturaExt = $request->file('comprobante')->getClientOriginalExtension();
    //   //nombre que se guarad en BD
    //   $FacturaStore = 'FacturaEntrada'.uniqid().'.'.$FacturaExt;
    //   //Subida del archivo al servidor ftp
    //   Storage::disk('local')->put('Archivos/'.$FacturaStore, fopen($request->file('comprobante'), 'r+'));
    //   $factura_entrada = new FacturasEntradas();
    //   $factura_entrada->entrada_id = $request->entrada;
    //   $factura_entrada->proveedore_id = $request->proveedore_id;
    //   $factura_entrada->uso_factura_id = $request->uso_factura;
    //   $factura_entrada->comprobante = $FacturaStore;
    //   $factura_entrada->uuid = $request->uuid;
    //   $factura_entrada->uuid_relacionado = $request->uuid_relacionado;
    //   $factura_entrada->descuento = $request->descuento;
    //   $factura_entrada->catalogo_centro_costos_id = $request->centro_costo;
    //   $factura_entrada->orden_compra_id = $request->orden_compra_id;
    //   $factura_entrada->total_factura = $request->total_factura;
    //   Utilidades::auditar($factura_entrada, $factura_entrada->id);
    //   $factura_entrada->save();
    //
    //   $this->impuesto_guardar($request->tamanio, $factura_entrada->id, $tipos, $valor);
    //
    // }
    // else {
    //   $factura_entrada = new FacturasEntradas();
    //   $factura_entrada->entrada_id = $request->entrada;
    //   $factura_entrada->proveedore_id = $request->proveedore_id;
    //   $factura_entrada->uso_factura_id = $request->uso_factura;
    //   $factura_entrada->comprobante = $FacturaStore;
    //   $factura_entrada->uuid = $request->uuid;
    //   $factura_entrada->uuid_relacionado = $request->uuid_relacionado;
    //   $factura_entrada->descuento = $request->descuento;
    //   $factura_entrada->catalogo_centro_costos_id = $request->centro_costo;
    //   $factura_entrada->orden_compra_id = $request->orden_compra_id;
    //   $factura_entrada->total_factura = $request->total_factura;
    //   Utilidades::auditar($factura_entrada, $factura_entrada->id);
    //   $factura_entrada->save();
    //   $this->impuesto_guardar($request->tamanio, $factura_entrada->id, $tipos, $valor);
    //
    // }
    // return response()->json(array('status' => true,'entrada_id' => $request->entrada,'orden_compra_id' => $request->orden_compra_id));

  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    //
  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show($id)
  {
    //
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function edit($id)
  {
    //
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, $id)
  {


  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    //
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function upload(Request $request)
  {
    try {
      if (!$request->ajax()) return redirect('/');

      if($request->hasFile('import_file')){
        ini_set('max_execution_time', 300);
        $collection = Excel::import(new GastosEmpresasImport($request->empresa), request()->file('import_file'));
        return response()->json($collection);
      }
    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return response()->json($e);
    }
  }

  /*
  *
  */
  public function getoc($id)
  {
    try {
      $oc = DB::table('ordenes_compras_gastos')
      ->select('orden_compra',DB::raw("SUM(cargo) AS total"))
      ->where('empresa',strtoupper($id))
      ->groupBy('ordenes_compras_gastos.orden_compra')
      ->get();
      return response()->json($oc);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  /*
  *
  */
  public function getocs($id)
  {
    try {
      $valores = explode('&',$id);
      $oc = DB::table('ordenes_compras_gastos')
      ->where('empresa',strtoupper($valores[0]))
      ->where('orden_compra',$valores[1])
      ->get();

      return response()->json($oc);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }

  public function getocGral($id)
  {
    try {
      $valores = explode('&',$id);
      $oc = DB::table('ordenes_compras_gastos')
      ->where('empresa',strtoupper($valores[0]))
      // ->where('orden_compra',$valores[1])
      ->get();

      return response()->json($oc);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }

  /*
  *
  */
  public function getocsfechas($id)
  {
    try {
      $valores = explode('&',$id);
      $fecha = $valores[2].'-'.$valores[1];
      $oc = DB::table('ordenes_compras_gastos')
      ->where('empresa',strtoupper($valores[0]))
      ->where('fecha','LIKE',$fecha.'%')
      ->get();

      return response()->json($oc);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }

  public function verocsproacre($id)
  {
    try {
      $valores = explode('&',$id);
      $oc = DB::table('ordenes_compras_gastos')
      ->where('empresa',strtoupper($valores[0]))
      ->where('proveedor_acreedor','LIKE','%'.$valores[1].'%')
      ->get();

      return response()->json($oc);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }

  public function verocstotal($id)
  {
    try {
      $valores = explode('&',$id);
      $oc = DB::table('ordenes_compras_gastos')
      ->where('empresa',strtoupper($valores[0]))
      ->where('cargo','LIKE','%'.$valores[1].'%')
      ->get();

      return response()->json($oc);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }

  /**
  *
  **/
  public function getfacturasoc($id)
  {
    try {
      $f = FacturasGastosEmpresa::where('orden_compra_gasto_id',$id)->get();
      return response()->json($f);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function guardarfaoc(Request $request)
  {
    try {

      $find_oc = Compras::where('folio','LIKE',$request->folio)->first();

      if (isset($find_oc) == true) {
        $g = GastosEmpresas::where('id',$request->oc_gasto_id)->first();
        $g->orden_compra_id = $find_oc->id;
        Utilidades::auditar($g,$g->id);
        $g->save();
      }

      return response()->json($find_oc);

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function guardar(Request $request)
  {
    try {
      $nombre_archivo_pdf = '';
      $nombre_archivo_xml = '';
      $nombre_archivo_poliza = '';
      $nombre_archivo_pago = '';
      $num_poliza = '';
      $uso_cfdi_id = 0;
      $uuid = '';
      $total = 0;


      // // dd($request);
      DB::beginTransaction();

      $fev = DB::table('facturas_entradas')->where('orden_compra_id',$request->id)
      ->where('tipo','2')->first();

      if (isset($fev) == false) {

        if($request->hasFile('xml')){
          libxml_use_internal_errors(true);
          $xml = simplexml_load_file($request->file('xml'));
          $ns = $xml->getNamespaces(true);
          $xml->registerXPathNamespace('c', $ns['cfdi']);
          $xml->registerXPathNamespace('t', $ns['tfd']);

          foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
            $uuid = $tfd['UUID'];
            // $factura_nomina->FechaTimbrado = $tfd['FechaTimbrado'];
          }

          $factura = new \App\GastosXmlOC();

          foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
            $factura->folio = $cfdiComprobante['Folio'];
            $factura->fecha = $cfdiComprobante['Fecha'];
            $factura->formapago = $cfdiComprobante['FormaPago'];
            $factura->subtotal = $cfdiComprobante['SubTotal'];
            $factura->descuento = $cfdiComprobante['Descuento'];
            $factura->moneda = $cfdiComprobante['Moneda'];
            $factura->tipo_cambio = $cfdiComprobante['TipoCambio'];
            $factura->total = $cfdiComprobante['Total'];
            $total = $cfdiComprobante['Total'];
            $factura->folio = $cfdiComprobante['Folio'];
            $factura->tipocomprobante = $cfdiComprobante['TipoDeComprobante'];
            $factura->metodopago = $cfdiComprobante['MetodoPago'];
            $factura->lugarexpedicion = $cfdiComprobante['LugarExpedicion'];
          }
          foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $rep){
            $factura->rfc_r = $rep['Rfc'];
            $factura->nombre_r = $rep['Nombre'];
            $factura->usocfdi = $rep['UsoCFDI'];
            $uso_cfdi_id = $this->buscar_id_ucdfi($rep['UsoCFDI']);
          }

          foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $key => $emi) {
            $factura->rfc_e = $emi['Rfc'];
            $factura->nombre_e = $emi['Nombre'];
            $factura->regimenfiscal = $emi['RegimenFiscal'];
          }

          $factura->ordenes_compras_gastos_id = $request->oc_id;
          $factura->save();

          foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $c){
            $concepto = new \App\ConceptosOCXml();
            $concepto->claveprodserv = $c['ClaveProdServ'];
            $concepto->noidentificacion = $c['NoIdentificacion'];
            $concepto->cantidad = $c['Cantidad'];
            $concepto->claveunidad = $c['ClaveUnidad'];
            $concepto->unidad = $c['Unidad'];
            $concepto->descripcion = $c['Descripcion'];
            $concepto->valorunitario = $c['ValorUnitario'];
            $concepto->importe = $c['Importe'];
            $concepto->descuento = $c['Descuento'];
            $concepto->gastos_xml_oc_id = $factura->id;
            $concepto->save();
          }

          foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $d){
            $concepto = new \App\ImpuestosOCXml();
            $concepto->base = $d['Base'];
            $concepto->impuesto = $d['Impuesto'];
            $concepto->tipofactor = $d['TipoFactor'];
            $concepto->tasaocuota = $d['TasaOCuota'];
            $concepto->importe = $d['Importe'];
            $concepto->gastos_xml_oc_id = $factura->id;
            $concepto->save();
          }
          $nombre_archivo_xml = $this->obtenerNombre($request,'xml');
          // $reporte->adjunto = $nombre_archivo;
          $this->subirAdjunto($request, 'xml',$nombre_archivo_xml);
        }
        if ($request->hasFile('pdf')) {
          $nombre_archivo_pdf = $this->obtenerNombre($request,'pdf');
          // $reporte->adjunto = $nombre_archivo;
          $this->subirAdjunto($request, 'pdf',$nombre_archivo_pdf);
        }

        if ($request->hasFile('poliza')) {
          $nombre_archivo_poliza = $this->obtenerNombre($request,'poliza');
          $num_poliza = $request->num_poliza;
          $this->subirAdjunto($request, 'poliza',$nombre_archivo_poliza);
        }

        if ($request->hasFile('pago')) {
          $nombre_archivo_pago = $this->obtenerNombre($request,'pago');
          // $reporte->adjunto = $nombre_archivo;
          $this->subirAdjunto($request, 'pago',$nombre_archivo_pago);
        }
        if (!$request->hasFile('pago') && $request->pago != '' && $this->is_base64_encoded($request->pago)) {
          $nombre_archivo_pago = $this->gua($request->pago);
        }


        $factura_entrada = new \App\FacturasEntradas();
        $factura_entrada->entrada_id = 0;
        $factura_entrada->proveedore_id = 0;
        $factura_entrada->uso_factura_id = $uso_cfdi_id;
        $factura_entrada->comprobante = $nombre_archivo_pdf;
        $factura_entrada->uuid = $request->uuid;
        $factura_entrada->uuid_xml = $uuid;
        $factura_entrada->catalogo_centro_costos_id = $request->centro_costo;
        $factura_entrada->orden_compra_id = $request->oc_id;
        $factura_entrada->total_factura = $total;
        $factura_entrada->tipo = 2;
        $factura_entrada->xml = $nombre_archivo_xml;
        $factura_entrada->poliza = $nombre_archivo_poliza;
        $factura_entrada->num_poliza = $request->num_poliza;
        $factura_entrada->pago_adj = $nombre_archivo_pago;
        Utilidades::auditar($factura_entrada, $factura_entrada->id);
        $factura_entrada->save();

      }elseif (isset($fev) == true) {

        $factura_entrada = \App\FacturasEntradas::where('orden_compra_id',$request->id)->where('tipo','2')->first();


        if($request->hasFile('xml')){
          libxml_use_internal_errors(true);
          $xml = simplexml_load_file($request->file('xml'));
          $ns = $xml->getNamespaces(true);
          $xml->registerXPathNamespace('c', $ns['cfdi']);
          $xml->registerXPathNamespace('t', $ns['tfd']);

          foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
            $uuid = $tfd['UUID'];
            // $factura_nomina->FechaTimbrado = $tfd['FechaTimbrado'];
          }

          $factura = new \App\GastosXmlOC();

          foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
            $factura->folio = $cfdiComprobante['Folio'];
            $factura->fecha = $cfdiComprobante['Fecha'];
            $factura->formapago = $cfdiComprobante['FormaPago'];
            $factura->subtotal = $cfdiComprobante['SubTotal'];
            $factura->descuento = $cfdiComprobante['Descuento'];
            $factura->moneda = $cfdiComprobante['Moneda'];
            $factura->tipo_cambio = $cfdiComprobante['TipoCambio'];
            $factura->total = $cfdiComprobante['Total'];
            $total = $cfdiComprobante['Total'];
            $factura->folio = $cfdiComprobante['Folio'];
            $factura->tipocomprobante = $cfdiComprobante['TipoDeComprobante'];
            $factura->metodopago = $cfdiComprobante['MetodoPago'];
            $factura->lugarexpedicion = $cfdiComprobante['LugarExpedicion'];
          }
          foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $rep){
            $factura->rfc_r = $rep['Rfc'];
            $factura->nombre_r = $rep['Nombre'];
            $factura->usocfdi = $rep['UsoCFDI'];
            $uso_cfdi_id = $this->buscar_id_ucdfi($rep['UsoCFDI']);
          }

          foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $key => $emi) {
            $factura->rfc_e = $emi['Rfc'];
            $factura->nombre_e = $emi['Nombre'];
            $factura->regimenfiscal = $emi['RegimenFiscal'];
          }

          $factura->ordenes_compras_gastos_id = $request->oc_id;
          $factura->save();

          foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $c){
            $concepto = new \App\ConceptosOCXml();
            $concepto->claveprodserv = $c['ClaveProdServ'];
            $concepto->noidentificacion = $c['NoIdentificacion'];
            $concepto->cantidad = $c['Cantidad'];
            $concepto->claveunidad = $c['ClaveUnidad'];
            $concepto->unidad = $c['Unidad'];
            $concepto->descripcion = $c['Descripcion'];
            $concepto->valorunitario = $c['ValorUnitario'];
            $concepto->importe = $c['Importe'];
            $concepto->descuento = $c['Descuento'];
            $concepto->gastos_xml_oc_id = $factura->id;
            $concepto->save();
          }

          foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $d){
            $concepto = new \App\ImpuestosOCXml();
            $concepto->base = $d['Base'];
            $concepto->impuesto = $d['Impuesto'];
            $concepto->tipofactor = $d['TipoFactor'];
            $concepto->tasaocuota = $d['TasaOCuota'];
            $concepto->importe = $d['Importe'];
            $concepto->gastos_xml_oc_id = $factura->id;
            $concepto->save();
          }
          $nombre_archivo_xml = $this->obtenerNombre($request,'xml');
          // $reporte->adjunto = $nombre_archivo;
          $this->subirAdjunto($request, 'xml',$nombre_archivo_xml);

          $factura_entrada->uso_factura_id = $uso_cfdi_id;
          $factura_entrada->comprobante = $nombre_archivo_pdf;
          $factura_entrada->uuid_xml = $uuid;
          $factura_entrada->total_factura = $total;
          $factura_entrada->xml = $nombre_archivo_xml;

        }
        if ($request->hasFile('pdf')) {
          $nombre_archivo_pdf = $this->obtenerNombre($request,'pdf');
          // $reporte->adjunto = $nombre_archivo;
          $this->subirAdjunto($request, 'pdf',$nombre_archivo_pdf);
        }

        if ($request->hasFile('poliza')) {
          $nombre_archivo_poliza = $this->obtenerNombre($request,'poliza');
          $$num_poliza = $request->num_poliza;
          $this->subirAdjunto($request, 'poliza',$nombre_archivo_poliza);
          $factura_entrada->poliza = $nombre_archivo_poliza;

        }

        if ($request->hasFile('pago')) {
          $nombre_archivo_pago = $this->obtenerNombre($request,'pago');
          // $reporte->adjunto = $nombre_archivo;
          $this->subirAdjunto($request, 'pago',$nombre_archivo_pago);
          $factura_entrada->pago_adj = $nombre_archivo_pago;
        }
        if (!$request->hasFile('pago') && $request->pago != '' && $this->is_base64_encoded($request->pago)) {
          $nombre_archivo_pago = $this->gua($request->pago);
          $factura_entrada->pago_adj = $nombre_archivo_pago;

        }


        $factura_entrada->entrada_id = 0;
        $factura_entrada->proveedore_id = 0;

        $factura_entrada->uuid = $request->uuid;

        $factura_entrada->catalogo_centro_costos_id = $request->centro_costo;
        $factura_entrada->orden_compra_id = $request->oc_id;
        $factura_entrada->num_poliza = $request->num_poliza;
        Utilidades::auditar($factura_entrada, $factura_entrada->id);
        $factura_entrada->save();

      }


      DB::commit();
      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      DB::rollBack();
      Utilidades::errors($e);
    }

  }

  public function obtenerNombre($request, $file)
  {
    //obtiene el nombre del archivo y su extension
    $FacturaNE = $request->file($file)->getClientOriginalName();
    //Obtiene el nombre del archivo
    $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
    //obtiene la extension
    $FacturaExt = $request->file($file)->getClientOriginalExtension();
    //nombre que se guarad en BD
    $FacturaStore = $file.'_'.uniqid().'.'.$FacturaExt;

    return $FacturaStore;
  }

  public function subirAdjunto($request, $n,$nombre_archivo)
  {
    Storage::disk('local')->put('Archivos/'.$nombre_archivo, fopen($request->file($n), 'r+'));
    return true;
  }

  public function buscar_id_ucdfi($data)
  {
    $uso = \App\SatCatUsoCfdi::where('c_uso',$data)->first();
    return $uso->id;
  }

  public function getData($id)
  {
    try {

      $factura_entrada = \App\FacturasEntradas::where('orden_compra_id',$id)
      ->where('tipo','2')
      ->first();

      return response()->json($factura_entrada);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function gua($image)
  {
    try {
      // return response()->json($request);

      $image_64 = $image; //your base64 encoded data

      $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf

      $replace = substr($image_64, 0, strpos($image_64, ',')+1);

      // find substring fro replace here eg: data:image/png;base64,

      $image = str_replace($replace, '', $image_64);

      $image = str_replace(' ', '+', $image);

      $imageName = uniqid().'.'.$extension;


      Storage::disk('local')->put('Archivos/'.$imageName, base64_decode($image));

      // return response()->json(['status' => true]);
      return $imageName;

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }

  public function is_base64_encoded($data)
  {
    $t = strlen($data);
    if ($t > 200) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

}
