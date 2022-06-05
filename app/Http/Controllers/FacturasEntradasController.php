<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\FacturasEntradas;
use App\PartidaEntrada;
use App\ImpuestosFacturasEntradas;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\Storage;



class FacturasEntradasController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    $facturas = FacturasEntradas::join('entradas AS E','E.id','=','facturas_entradas.entrada_id')
    ->leftJoin('ordenes_compras AS OC','OC.id','=','E.orden_compra_id')
    ->join('proveedores AS P','P.id','=','facturas_entradas.proveedore_id')
    ->join('sat_cat_usocfdi AS SCUF','SCUF.id','=','facturas_entradas.uso_factura_id')
    ->select('facturas_entradas.*',DB::raw("CONCAT(P.nombre,' ',P.razon_social) AS proveedor_name"),'SCUF.descripcion',
    'OC.folio AS foliocompra')
    ->get();

    return response()->json($facturas);
  }

  /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function create()
  {
    //
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    try{

      DB::beginTransaction();
      $PdfStore='';
      $XmlStore = '';
      $tipos = explode(',',$request->tipos);
      $valor = explode(',',$request->valor);
      $uso_cfdi_id = 0;
      $uuid = '';
      $total = 0;

      if ($request->hasFile('comprobante') && !$request->hasFile('xml')) {

        //obtiene el nombre del archivo y su extension
        $FacturaNE = $request->file('comprobante')->getClientOriginalName();
        //Obtiene el nombre del archivo
        $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
        //obtiene la extension
        $FacturaExt = $request->file('comprobante')->getClientOriginalExtension();
        //nombre que se guarad en BD
        $PdfStore = 'FacturaEntrada'.uniqid().'.'.$FacturaExt;
        //Subida del archivo al servidor ftp
        Storage::disk('local')->put('Archivos/'.$PdfStore, fopen($request->file('comprobante'), 'r+'));

        $factura_entrada = new FacturasEntradas();
        $factura_entrada->entrada_id = $request->entrada;
        $factura_entrada->proveedore_id = $request->proveedore_id;
        $factura_entrada->uso_factura_id = $request->uso_factura;
        $factura_entrada->comprobante = $PdfStore;
        $factura_entrada->uuid = $request->uuid;
        $factura_entrada->uuid_relacionado = $request->uuid_relacionado;
        $factura_entrada->descuento = $request->descuento;
        $factura_entrada->catalogo_centro_costos_id = $request->centro_costo;
        $factura_entrada->orden_compra_id = $request->orden_compra_id;
        $factura_entrada->total_factura = $request->total_factura;
        $factura_entrada->xml = $XmlStore;
        $factura_entrada->tipo = 1;
        $factura_entrada->uuid_xml = '';
        Utilidades::auditar($factura_entrada, $factura_entrada->id);
        $factura_entrada->save();

      }elseif ($request->hasFile('comprobante') && $request->hasFile('xml') ) {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_file($request->file('xml'));
        $ns = $xml->getNamespaces(true);
        $xml->registerXPathNamespace('c', $ns['cfdi']);
        $xml->registerXPathNamespace('t', $ns['tfd']);

        foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
          $uuid = $tfd['UUID'];
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

        $factura->ordenes_compras_gastos_id = $request->orden_compra_id;
        $factura->tipo = 2;
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
          $concepto->tipo = 't';
          $concepto->save();
        }


        foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Retenciones//cfdi:Retencion') as $d){
          $impuesto = new \App\ImpuestosOCXml();
          $impuesto->base = $d['Base'];
          $impuesto->impuesto = $d['Impuesto'];
          $impuesto->tipofactor = $d['TipoFactor'];
          $impuesto->tasaocuota = $d['TasaOCuota'];
          $impuesto->importe = $d['Importe'];
          $impuesto->gastos_xml_oc_id = $factura->id;
          $impuesto->tipo = 'r';
          $impuesto->save();
        }

        //obtiene el nombre del archivo y su extension
        $FacturaNE = $request->file('comprobante')->getClientOriginalName();
        //Obtiene el nombre del archivo
        $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
        //obtiene la extension
        $FacturaExt = $request->file('comprobante')->getClientOriginalExtension();
        //nombre que se guarad en BD
        $PdfStore = 'FacturaEntrada'.uniqid().'.'.$FacturaExt;
        //Subida del archivo al servidor ftp
        Storage::disk('local')->put('Archivos/'.$PdfStore, fopen($request->file('comprobante'), 'r+'));

        //obtiene el nombre del archivo y su extension
        $FacturaNEx = $request->file('xml')->getClientOriginalName();
        //Obtiene el nombre del archivo
        $FacturaNombrex = pathinfo($FacturaNEx, PATHINFO_FILENAME);
        //obtiene la extension
        $FacturaExtx = $request->file('xml')->getClientOriginalExtension();
        //nombre que se guarad en BD
        $XmlStore = 'FacturaEntrada'.uniqid().'.'.$FacturaExtx;
        //Subida del archivo al servidor ftp
        Storage::disk('local')->put('Archivos/'.$XmlStore, fopen($request->file('xml'), 'r+'));


        $factura_entrada = new FacturasEntradas();
        $factura_entrada->entrada_id = $request->entrada;
        $factura_entrada->proveedore_id = 0;
        $factura_entrada->uso_factura_id = $uso_cfdi_id;
        $factura_entrada->comprobante = $PdfStore;
        $factura_entrada->uuid = $request->uuid;
        $factura_entrada->uuid_relacionado = $request->uuid_relacionado;
        $factura_entrada->descuento = $request->descuento;
        $factura_entrada->catalogo_centro_costos_id = $request->centro_costo;
        $factura_entrada->orden_compra_id = $request->orden_compra_id;
        $factura_entrada->total_factura = $total;
        $factura_entrada->xml = $XmlStore;
        $factura_entrada->tipo = 1;
        $factura_entrada->uuid_xml = $uuid;
        Utilidades::auditar($factura_entrada, $factura_entrada->id);
        $factura_entrada->save();

      } else {

        $factura_entrada = new FacturasEntradas();
        $factura_entrada->entrada_id = $request->entrada;
        $factura_entrada->proveedore_id = 0;
        $factura_entrada->uso_factura_id = $request->uso_factura;
        $factura_entrada->comprobante = $PdfStore;
        $factura_entrada->uuid = $request->uuid;
        $factura_entrada->uuid_relacionado = $request->uuid_relacionado;
        $factura_entrada->catalogo_centro_costos_id = $request->centro_costo;
        $factura_entrada->orden_compra_id = $request->orden_compra_id;
        $factura_entrada->total_factura = $request->total_factura;
        $factura_entrada->xml = $XmlStore;
        $factura_entrada->tipo = 1;
        Utilidades::auditar($factura_entrada, $factura_entrada->id);
        $factura_entrada->save();

      }
      DB::commit();
      return response()->json(array('status' => true,'entrada_id' => $request->entrada,'orden_compra_id' => $request->orden_compra_id));
    } catch (\Throwable $e) {
      DB::rollBack();
      Utilidades::errors($e);
    }
  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show($data)
  {
    $valores = explode('&',$data);
    $id = $valores[0];
    $tipo = $valores[1];
    if(count($valores) > 2){
      $orden_compra = $valores[2];
    }
    if ($tipo === 'Entrada') {
      $facturas_rev = FacturasEntradas::where('entrada_id',$id)->orWhere('orden_compra_id',$orden_compra)->get();
      if (count($facturas_rev) > 0) {
        foreach ($facturas_rev as $key => $value) {
          $t =0;$r =0;$total = 0;$subtotal =0;
          $partidas = PartidaEntrada::leftJoin('facturas_entradas AS FE','FE.id','=','partidas_entradas.factura_id')
          ->where('factura_id',$value->id)->select(DB::raw("SUM((cantidad * precio_unitario)-((cantidad * precio_unitario)*(FE.descuento/100))) AS subtotal"))->first();
          $impuestos = ImpuestosFacturasEntradas::join('impuestos_facturas AS IFF','IFF.id','impuestos_facturas_entradas.impuesto_id')
          ->where('impuestos_facturas_entradas.factura_id',$value->id)->select('impuestos_facturas_entradas.*','IFF.tipo')->get();
          if (count($impuestos) > 0) {
            foreach ($impuestos as $key => $val_imp) {
              if ($val_imp->tipo === 'Trasladados') {
                $t += floatval($val_imp->monto);
              }elseif ($val_imp->tipo === 'Retenidos') {
                $r += floatval($val_imp->monto);
              }
            }
          }
          $subtotal = $partidas->subtotal;
          $total = (($subtotal + $t) - $r);
          if (isset($partidas) == true) {
            $facturas_editar = FacturasEntradas::where('id',$value->id)->first();
            $facturas_editar->total = $total;
            // $facturas_editar->sub
            $facturas_editar->save();
          }
        }
      }

      $facturas = FacturasEntradas::join('proveedores AS P','P.id','=','facturas_entradas.proveedore_id')
      ->leftJoin('sat_cat_usocfdi AS SCUF','SCUF.id','=','facturas_entradas.uso_factura_id')
      ->select('facturas_entradas.*',DB::raw("CONCAT(P.nombre,' ',P.razon_social) AS proveedor_name"),'SCUF.descripcion')
      ->where('facturas_entradas.entrada_id','=',$id)
      ->orWhere('orden_compra_id',$orden_compra)
      ->get();
      return response()->json($facturas);
    }
    if ($tipo === 'Compra') {
      
      $facturas = FacturasEntradas::
      leftJoin('proveedores AS P','P.id','=','facturas_entradas.proveedore_id')
      ->leftJoin('sat_cat_usocfdi AS SCUF','SCUF.id','=','facturas_entradas.uso_factura_id')
      ->leftJoin('gastos_xml_oc AS gxo','gxo.ordenes_compras_gastos_id','facturas_entradas.orden_compra_id')
      ->select('facturas_entradas.*',DB::raw("CONCAT(P.nombre,' ',P.razon_social) AS proveedor_name"),
      'SCUF.descripcion','gxo.nombre_e AS emisor')
      ->where('facturas_entradas.orden_compra_id','=',$id)
      ->where('facturas_entradas.tipo','1')
      ->get();

      return response()->json($facturas);

    }


  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function edit($id)
  {
    //elimina de la ruta local el archivo descargado
    Storage::disk('descarga')->delete($id);
    Storage::disk('local')->delete($id);
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function descargar($id)
  {
    $archivo = Utilidades::ftpSolucion($id);
    // Se coloca el archivo en una ruta local
    Storage::disk('descarga')->put($id, $archivo);
    //--Devuelve la respuesta y descarga el archivo--//
    return response()->download(storage_path().'/app/descargas/'.$id);
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function actualizarfactura(Request $request)
  {
    try{
      DB::beginTransaction();
      $PdfStore='';
      $PdfStorex='';
      $ValorFactura = '';
      $ValorXml = '';
      $tipos = explode(',',$request->tipos);
      $valor = explode(',',$request->valor);

      $factura = FacturasEntradas::where('id',$request->id)->get();

      if (count($factura) > 0) {
        foreach ($factura as $key => $value) {
          $ValorFactura = $value->comprobante;
          $ValorXml = $value->xml;
        }
      }





      if ($request->hasFile('comprobante') && !$request->hasFile('xml')) {
        //obtiene el nombre del archivo y su extension
        $FacturaNE = $request->file('comprobante')->getClientOriginalName();
        //Obtiene el nombre del archivo
        $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
        //obtiene la extension
        $FacturaExt = $request->file('comprobante')->getClientOriginalExtension();
        //nombre que se guarad en BD
        $PdfStore = 'FacturaEntrada'.uniqid().'.'.$FacturaExt;
        //Subida del archivo al servidor ftp
        Storage::disk('local')->put('Archivos/'.$PdfStore, fopen($request->file('comprobante'), 'r+'));
        if ($ValorFactura != '') {
          //Elimina el archivo en el servidor si requiere ser actualizado
          Utilidades::ftpSolucionEliminar($ValorFactura);
        }
        $factura_entrada = FacturasEntradas::where('id',$request->id)->first();
        $factura_entrada->entrada_id = $request->entrada;
        $factura_entrada->proveedore_id = $request->proveedore_id;
        $factura_entrada->uso_factura_id = $request->uso_factura;
        $factura_entrada->comprobante = $PdfStore;
        $factura_entrada->uuid = $request->uuid;
        $factura_entrada->uuid_relacionado = $request->uuid_relacionado;
        $factura_entrada->descuento = $request->descuento;
        $factura_entrada->catalogo_centro_costos_id = $request->centro_costo;
        $factura_entrada->orden_compra_id = $request->orden_compra_id;
        $factura_entrada->total_factura = $request->total_factura;
        Utilidades::auditar($factura_entrada, $factura_entrada->id);
        $factura_entrada->save();
        $this->impuesto_guardar($request->tamanio, $factura_entrada->id, $tipos, $valor);

      }
      elseif (!$request->hasFile('comprobante') && $request->hasFile('xml') ) {

        $gxoc_ = \App\GastosXmlOC::
        where('ordenes_compras_gastos_id',$request->orden_compra_id)
        ->where('tipo','2')
        ->first();

        $concepto = \App\ConceptosOCXml::where('gastos_xml_oc_id',$gxoc_->id)->delete();
        $impuesto = \App\ImpuestosOCXml::where('gastos_xml_oc_id',$gxoc_->id)->delete();

        $gx =  \App\GastosXmlOC::
        where('ordenes_compras_gastos_id',$request->orden_compra_id)
        ->where('tipo','2')
        ->delete();


        $xml = simplexml_load_file($request->file('xml'));
        $ns = $xml->getNamespaces(true);
        $xml->registerXPathNamespace('c', $ns['cfdi']);
        $xml->registerXPathNamespace('t', $ns['tfd']);

        foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
          $uuid = $tfd['UUID'];
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
        $factura->ordenes_compras_gastos_id = $request->orden_compra_id;
        $factura->tipo = 2;
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

        //obtiene el nombre del archivo y su extension
        $FacturaNEx = $request->file('xml')->getClientOriginalName();
        //Obtiene el nombre del archivo
        $FacturaNombrex = pathinfo($FacturaNEx, PATHINFO_FILENAME);
        //obtiene la extension
        $FacturaExtx = $request->file('xml')->getClientOriginalExtension();
        //nombre que se guarad en BD
        $PdfStorex = 'FacturaEntrada'.uniqid().'.'.$FacturaExtx;
        //Subida del archivo al servidor ftp
        Storage::disk('local')->put('Archivos/'.$PdfStorex, fopen($request->file('xml'), 'r+'));
        if ($ValorXml != '') {
          //Elimina el archivo en el servidor si requiere ser actualizado
          Utilidades::ftpSolucionEliminar($ValorXml);
        }

        $factura_entrada = FacturasEntradas::where('id',$request->id)->first();
        $factura_entrada->entrada_id = $request->entrada;
        $factura_entrada->proveedore_id = 0;
        $factura_entrada->uso_factura_id = $uso_cfdi_id;
        $factura_entrada->uuid = $request->uuid;
        $factura_entrada->uuid_relacionado = $request->uuid_relacionado;
        $factura_entrada->descuento = $request->descuento;
        $factura_entrada->catalogo_centro_costos_id = $request->centro_costo;
        $factura_entrada->orden_compra_id = $request->orden_compra_id;
        $factura_entrada->total_factura = $total;
        $factura_entrada->xml = $PdfStorex;
        $factura_entrada->tipo = 1;
        $factura_entrada->uuid_xml = $uuid;
        Utilidades::auditar($factura_entrada, $factura_entrada->id);
        $factura_entrada->save();

      }
      elseif ($request->hasFile('comprobante') && $request->hasFile('xml') ) {

        $gxoc_ = \App\GastosXmlOC::
        where('ordenes_compras_gastos_id',$request->orden_compra_id)
        ->where('tipo','2')
        ->first();

        if (isset($gxoc_) == true) {
          $concepto = \App\ConceptosOCXml::where('gastos_xml_oc_id',$gxoc_->id)->delete();
          $impuesto = \App\ImpuestosOCXml::where('gastos_xml_oc_id',$gxoc_->id)->delete();

          $gx =  \App\GastosXmlOC::
          where('ordenes_compras_gastos_id',$request->orden_compra_id)
          ->where('tipo','2')
          ->delete();
        }



        $xml = simplexml_load_file($request->file('xml'));
        $ns = $xml->getNamespaces(true);
        $xml->registerXPathNamespace('c', $ns['cfdi']);
        $xml->registerXPathNamespace('t', $ns['tfd']);

        foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
          $uuid = $tfd['UUID'];
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
        $factura->ordenes_compras_gastos_id = $request->orden_compra_id;
        $factura->tipo = 2;
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

        //obtiene el nombre del archivo y su extension
        $FacturaNE = $request->file('comprobante')->getClientOriginalName();
        //Obtiene el nombre del archivo
        $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
        //obtiene la extension
        $FacturaExt = $request->file('comprobante')->getClientOriginalExtension();
        //nombre que se guarad en BD
        $PdfStore = 'FacturaEntrada'.uniqid().'.'.$FacturaExt;
        //Subida del archivo al servidor ftp
        Storage::disk('local')->put('Archivos/'.$PdfStore, fopen($request->file('comprobante'), 'r+'));
        if ($ValorFactura != '') {
          //Elimina el archivo en el servidor si requiere ser actualizado
          Utilidades::ftpSolucionEliminar($ValorFactura);
        }

        //obtiene el nombre del archivo y su extension
        $FacturaNEx = $request->file('xml')->getClientOriginalName();
        //Obtiene el nombre del archivo
        $FacturaNombrex = pathinfo($FacturaNEx, PATHINFO_FILENAME);
        //obtiene la extension
        $FacturaExtx = $request->file('xml')->getClientOriginalExtension();
        //nombre que se guarad en BD
        $PdfStorex = 'FacturaEntrada'.uniqid().'.'.$FacturaExtx;
        //Subida del archivo al servidor ftp
        Storage::disk('local')->put('Archivos/'.$PdfStorex, fopen($request->file('xml'), 'r+'));
        if ($ValorXml != '') {
          //Elimina el archivo en el servidor si requiere ser actualizado
          Utilidades::ftpSolucionEliminar($ValorXml);
        }

        $factura_entrada = FacturasEntradas::where('id',$request->id)->first();
        $factura_entrada->entrada_id = $request->entrada;
        $factura_entrada->proveedore_id = 0;
        $factura_entrada->uso_factura_id = $uso_cfdi_id;
        $factura_entrada->comprobante = $PdfStore;
        $factura_entrada->uuid = $request->uuid;
        $factura_entrada->uuid_relacionado = $request->uuid_relacionado;
        $factura_entrada->descuento = $request->descuento;
        $factura_entrada->catalogo_centro_costos_id = $request->centro_costo;
        $factura_entrada->orden_compra_id = $request->orden_compra_id;
        $factura_entrada->total_factura = $total;
        $factura_entrada->xml = $PdfStorex;
        $factura_entrada->tipo = 1;
        $factura_entrada->uuid_xml = $uuid;
        Utilidades::auditar($factura_entrada, $factura_entrada->id);
        $factura_entrada->save();

      }
      else
      {
        $factura_entrada = FacturasEntradas::where('id',$request->id)->first();
        $factura_entrada->entrada_id = $request->entrada;
        $factura_entrada->proveedore_id = $request->proveedore_id;
        $factura_entrada->uso_factura_id = $request->uso_factura;
        // $factura_entrada->comprobante = $PdfStore;
        $factura_entrada->uuid = $request->uuid;
        $factura_entrada->uuid_relacionado = $request->uuid_relacionado;
        $factura_entrada->descuento = $request->descuento;
        $factura_entrada->catalogo_centro_costos_id = $request->centro_costo;
        $factura_entrada->orden_compra_id = $request->orden_compra_id;
        $factura_entrada->total_factura = $request->total_factura;
        Utilidades::auditar($factura_entrada, $factura_entrada->id);
        $factura_entrada->save();
        $this->impuesto_guardar($request->tamanio, $factura_entrada->id, $tipos, $valor);

      }
      DB::commit();
      return response()->json(array('status' => true,'entrada_id' => $request->entrada,'orden_compra_id' => $request->orden_compra_id));
    } catch (\Throwable $e) {
      DB::rollBack();
      Utilidades::errors($e);
    }

  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function delete($id)
  {
    $factura_entrada = FacturasEntradas::where('id',$id)->delete();
    return response()->json(array('status' => true));
  }

  public function impuesto_guardar($tamanio, $factura_entrada_id, $tipos, $valor)
  {
    for ($i=0; $i < $tamanio; $i++) {
      $impueto = new \App\ImpuestosFacturasEntradas();
      $impueto->factura_id = $factura_entrada_id;
      $impueto->impuesto_id = $tipos[$i];
      $impueto->monto = $valor[$i];
      Utilidades::auditar($impueto, $impueto->id);
      $impueto->save();
    }
  }

  public function eliminarimpuesto($id)
  {

    $impueto = ImpuestosFacturasEntradas::where('id',$id)->delete();
    return response()->json(array('status' => true));
  }

  public function getimpuestos($id)
  {
    $impuestos = ImpuestosFacturasEntradas::join('impuestos_facturas AS IFF','IFF.id','=','impuestos_facturas_entradas.impuesto_id')
    ->select('impuestos_facturas_entradas.*','IFF.id AS impuesto_id','IFF.tipo','IFF.nombre','IFF.porcentaje')
    ->where('impuestos_facturas_entradas.factura_id','=',$id)->get();
    return response()->json($impuestos);
  }

  public function buscar_id_ucdfi($data)
  {
    $uso = \App\SatCatUsoCfdi::where('c_uso',$data)->first();
    return $uso->id;
  }
}
