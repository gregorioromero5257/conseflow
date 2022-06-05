<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;
use \App\Http\Helpers\Utilidades;



class FacturaSellarTimbrarController extends Controller
{

  /**
  * [Cambiar el estatus del timbrado en la BD en la tabla factura, generar el archivo .ini,
  * timbrar el archivo generado y guardar los archivos respuesta]
  * @param  Int      $id [id del GET]
  * @return Response     [Array con estatus true,respuesta del archivo timbrado y emisor_id]
  */
  public function sellartimbrar($id)
  {

    $uuid = '';
    $factura = \App\Factura::where('factura.id',$id)
    ->join('clientes AS c','c.id','=','factura.cliente_id')
    ->join('datosgenerales AS dg','dg.id','=','factura.emisor_id')
    ->join('sat_cat_formapago AS scfp','scfp.id','=','factura.formapago')
    ->join('sat_cat_metodopago AS scmp','scmp.id','=','factura.metodopago')
    ->join('sat_cat_monedas AS scm','scm.id','=','factura.moneda_id')
    ->join('sat_cat_tipofactura AS sctf','sctf.id','=','factura.tipo_factura_id')
    ->join('sat_cat_usocfdi AS scuc','scuc.id','=','factura.uso_factura')
    ->leftJoin('sat_cat_tiporelacion AS sctr','sctr.id','=','factura.tipo_relacion')
    ->leftJoin('factura AS f','f.id','=','factura.factura_id')
    ->select('factura.*','c.nombre AS c_nombre','c.rfc AS c_rfc','dg.rfc AS dg_rfc','dg.razon_social AS dg_razon_social',
    'dg.regimenfiscal AS dg_regimenfiscal','scfp.clave AS scfp_clave','scmp.c_metodopago AS scmp_c_metodopago',
    'scm.c_moneda AS scm_c_moneda','sctf.c_tipofactura AS sctf_c_tipofactura','scuc.c_uso AS scuc_c_uso',
    'sctr.c_tiporelacion AS tiporelacion_cod','f.uuid AS uuid_relacionado_f')
    ->first();

    $partidas = \App\PartidasFactura::where('factura_id',$id)
    ->join('sat_cat_prodser AS scps','scps.id','=','partidas_factura.productos_servicios_id')
    ->join('sat_cat_unidades AS scu','scu.id','=','partidas_factura.unidad_id')
    ->select('partidas_factura.*','scps.clave AS scps_clave','scu.c_unidad AS scu_c_unidad')
    ->get();
    $partidas_pagos = \App\PartidasFacturasPagos::where('partidas_facturas_id','=',$partidas[0]->id)
    ->select('partidas_facturas_pagos.*')->get();

    $date = date("m.d.y");

    // cambiar el disk por ftp cuando no sea local
    Storage::disk('local')->put('Facturas/'.$id.'-'.$date.".ini", $this->Principal($factura, $partidas, $partidas_pagos));
    if ($factura->emisor_id == '1') {
      // $output = shell_exec("cd /home/libfaclinflow/;./sellartimbrar.sh $id-$date 2> ehoyc.txt;cat ehoyc.txt;");
      $output = shell_exec("cd /home/libfaclin/;./sellartimbrar.sh $id-$date 2> ehoyc.txt;cat ehoyc.txt;");
    }

    // var_dump($output);
    $suma_subtotal = 0;
    $suma_descuento = 0;
    $suma_impuesto = 0;
    foreach ($partidas as $key => $value) {
      $suma_subtotal += $value->importe;
      $suma_descuento += $value->descuento;
      $suma_impuesto += ($value->importe * $value->impuesto_iva);
    }
    $total_pagos_f = 0;
    foreach ($partidas_pagos as $key => $value) {
      $total_pagos_f += $value->importe_pagado;
    }
    if ($output == null) {

      $facturas = \App\Factura::where('id',$id)->first();
      $facturas->timbrado = '1';
      $facturas->archivo = $id.'-'.$date;
      $facturas->total = $factura->tipo_factura_id == 4 ?  $total_pagos_f :  (($suma_subtotal - $suma_descuento) + $suma_impuesto);
      $facturas->save();

      //buscar el uuid y se agreaga en la base de datos
      $xml_string = Storage::disk('local')->get('Facturas/'.$facturas->archivo.'tim.xml');
      $xml = simplexml_load_string($xml_string);
      $ns = $xml->getNamespaces(true);
      $xml->registerXPathNamespace('c', $ns['cfdi']);
      $xml->registerXPathNamespace('t', $ns['tfd']);

      foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
        $uuid = $tfd['UUID'];
      }
      //$factura_actualizar = \App\Factura::where('id',$valores[0])->first();
      $facturas->uuid = $uuid;
      $facturas->save();
      //

      return response()->json(array(
        'status' => true,
        'respuesta' => $output,
        'emisor_id' => $factura->emisor_id,

      ));
    }
    else {
      return response()->json(array(
        'status' => false,
        'respuesta' => $output,
      ));
    }

  }

  /**
  * [Cambiar el estatus del timbrado en la BD en la tabla factura, generar el archivo .ini,
  * timbrar el archivo generado y guardar los archivos respuesta]
  * @param  Int      $id [id del GET]
  * @return Response     [Array con estatus true,respuesta del archivo timbrado y emisor_id]
  */
  public function sellartimbrarprueba($id)
  {

    $uuid = '';
    $factura = \App\Factura::where('factura.id',$id)
    ->join('clientes AS c','c.id','=','factura.cliente_id')
    ->join('datosgenerales AS dg','dg.id','=','factura.emisor_id')
    ->join('sat_cat_formapago AS scfp','scfp.id','=','factura.formapago')
    ->join('sat_cat_metodopago AS scmp','scmp.id','=','factura.metodopago')
    ->join('sat_cat_monedas AS scm','scm.id','=','factura.moneda_id')
    ->join('sat_cat_tipofactura AS sctf','sctf.id','=','factura.tipo_factura_id')
    ->join('sat_cat_usocfdi AS scuc','scuc.id','=','factura.uso_factura')
    ->leftJoin('sat_cat_tiporelacion AS sctr','sctr.id','=','factura.tipo_relacion')
    ->leftJoin('factura AS f','f.id','=','factura.factura_id')
    ->select('factura.*','c.nombre AS c_nombre','c.rfc AS c_rfc','dg.rfc AS dg_rfc','dg.razon_social AS dg_razon_social',
    'dg.regimenfiscal AS dg_regimenfiscal','scfp.clave AS scfp_clave','scmp.c_metodopago AS scmp_c_metodopago',
    'scm.c_moneda AS scm_c_moneda','sctf.c_tipofactura AS sctf_c_tipofactura','scuc.c_uso AS scuc_c_uso',
    'sctr.c_tiporelacion AS tiporelacion_cod','f.uuid AS uuid_relacionado_f')
    ->first();

    $partidas = \App\PartidasFactura::where('factura_id',$id)
    ->join('sat_cat_prodser AS scps','scps.id','=','partidas_factura.productos_servicios_id')
    ->join('sat_cat_unidades AS scu','scu.id','=','partidas_factura.unidad_id')
    ->select('partidas_factura.*','scps.clave AS scps_clave','scu.c_unidad AS scu_c_unidad')
    ->get();
    $partidas_pagos = \App\PartidasFacturasPagos::where('partidas_facturas_id','=',$partidas[0]->id)
    ->select('partidas_facturas_pagos.*')->get();

    $date = date("m.d.y");

    // cambiar el disk por ftp cuando no sea local
    Storage::disk('local')->put('Facturas/'.$id.'-'.$date."p.ini", $this->Principal($factura, $partidas, $partidas_pagos));
    if ($factura->emisor_id == '1') {
      $output = shell_exec("cd /home/libfaclin/;./sellartimbrar.sh $id-$date 2> ehoyc.txt;cat ehoyc.txt;");
      // $output = shell_exec("cd /home/libfaclin/;./sellartimbrar.sh $id-$date 2> ehoyc.txt;cat ehoyc.txt;");
    }

    // var_dump($output);
    $suma_subtotal = 0;
    $suma_descuento = 0;
    $suma_impuesto = 0;
    foreach ($partidas as $key => $value) {
      $suma_subtotal += $value->importe;
      $suma_descuento += $value->descuento;
      $suma_impuesto += ($value->importe * $value->impuesto_iva);
    }
    $total_pagos_f = 0;
    foreach ($partidas_pagos as $key => $value) {
      $total_pagos_f += $value->importe_pagado;
    }
    if ($output == null) {

      $facturas = \App\Factura::where('id',$id)->first();
      $facturas->timbrado = '9';
      $facturas->archivo = $id.'-'.$date;
      $facturas->total = $factura->tipo_factura_id == 4 ?  $total_pagos_f :  (($suma_subtotal - $suma_descuento) + $suma_impuesto);
      $facturas->save();

      //buscar el uuid y se agreaga en la base de datos
      $xml_string = Storage::disk('local')->get('Facturas/'.$facturas->archivo.'timp.xml');
      $xml = simplexml_load_string($xml_string);
      $ns = $xml->getNamespaces(true);
      $xml->registerXPathNamespace('c', $ns['cfdi']);
      $xml->registerXPathNamespace('t', $ns['tfd']);

      foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
        $uuid = $tfd['UUID'];
      }
      //$factura_actualizar = \App\Factura::where('id',$valores[0])->first();
      $facturas->uuid = $uuid;
      $facturas->save();
      //

      return response()->json(array(
        'status' => true,
        'respuesta' => $output,
        'emisor_id' => $factura->emisor_id,

      ));
    }
    else {
      return response()->json(array(
        'status' => false,
        'respuesta' => $output,
      ));
    }

  }

  /**
  * [Construcccion del archivo .ini, contiene todas las funciones relacionadas]
  * @param  Array  $factura        [Array recibido en la función]
  * @param  Array  $partida        [Array recibido en la función]
  * @param  Array  $partidas_pagos [Array recibido en la función]
  * @return String $variable       [Cadena concatenada]
  */
  public function Principal($factura, $partida, $partidas_pagos)
  {
    //se separan algunos de los nodos en funciones para poder manipular los datos y crear n cantidad de subnodos
    $variable  =';ini'.PHP_EOL.';encoding=utf8'.PHP_EOL.PHP_EOL.
    '[cfdi:Comprobante]'.PHP_EOL.$this->Comprobante($factura, $partida).PHP_EOL.
    // ($factura->sctf_c_tipofactura == 'E' ? $this->Relacionados($factura) : $factura->factura_id != NULL ? $this->Relacionados($factura) : $factura->uuid_relacionado != NULL ? $this->Relacionados($factura) : '').PHP_EOL.PHP_EOL.
    ($factura->sctf_c_tipofactura == 'E' ? $this->Relacionados($factura) : $factura->tipo_relacion != '' ? $this->Relacionados($factura) : $factura->tipo_relacion != '' ? $this->Relacionados($factura) : '').PHP_EOL.PHP_EOL.
    '[cfdi:Comprobante/cfdi:Emisor]'.PHP_EOL.$this->CEmisor($factura->emisor_id).PHP_EOL.PHP_EOL.
    '[cfdi:Comprobante/cfdi:Receptor]'.PHP_EOL.$this->CReceptor($factura->cliente_id,$factura->scuc_c_uso).PHP_EOL.PHP_EOL.
    '[cfdi:Comprobante/cfdi:Conceptos]'.PHP_EOL.PHP_EOL.$this->Conceptos($factura,$partida).PHP_EOL.PHP_EOL.

    ($factura->sctf_c_tipofactura == 'P' ? '[cfdi:Comprobante/cfdi:Complemento]'.PHP_EOL.PHP_EOL.$this->Complemento($factura,$partida,$partidas_pagos) :'[cfdi:Comprobante/cfdi:Impuestos]'.PHP_EOL.PHP_EOL.$this->totalImpuestos($partida)).PHP_EOL.PHP_EOL.
    ($factura->adenda == NULL ? '': $this->Adenda($factura,$partida))
    ;

    return $variable;
  }

  /**
  * [Construcción del nodo cfdi:Comprobante del archivo .ini ]
  * @param  Array  $factura     [Array recibido en la función]
  * @param  Array  $partidas    [Array recibido en la función]
  * @return String $comprobante [Cadena concatenada]
  */
  public function Comprobante($factura, $partidas)
  {
    $suma_subtotal = 0;
    $suma_descuento = 0;
    $suma_impuesto = 0;
    $suma_retencion = 0;
    $to = $factura->tipo_cambio == 1.00 ? '1' : $factura->tipo_cambio;
    foreach ($partidas as $key => $value) {
      $suma_subtotal += $value->importe;
      $suma_descuento += $value->descuento;
      $suma_impuesto += ($value->importe * $value->impuesto_iva);
      if($value->retencion != ''){
        $suma_retencion += ($value->importe * $value->retencion);
      }
    }
    $fecha_e = substr($factura->fecha_hora, 0,4).'-'.substr($factura->fecha_hora, 5,2).'-'.substr($factura->fecha_hora, 8,2).'T'.substr($factura->fecha_hora, 11, 8);
    $comprobante = '';
    $comprobante = 'xmlns:cfdi = http://www.sat.gob.mx/cfd/3'.PHP_EOL.
    'xmlns:xsi = http://www.w3.org/2001/XMLSchema-instance'.PHP_EOL.
    'xmlns:pago10=http://www.sat.gob.mx/Pagos'.PHP_EOL.
    'xsi:schemaLocation = http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd http://www.sat.gob.mx/Pagos http://www.sat.gob.mx/sitio_internet/cfd/Pagos/Pagos10.xsd'.PHP_EOL.
    'Version = 3.3'.PHP_EOL.
    'Serie = '.$factura->serie.PHP_EOL.
    'Folio = '.$factura->folio.PHP_EOL.
    'Fecha = '.$fecha_e.PHP_EOL.//
    'Sello = Zg=='.PHP_EOL.//pendiente por definir por parte de la empresa
    ($factura->sctf_c_tipofactura == 'P' ? '' : 'FormaPago = '.$factura->scfp_clave).PHP_EOL.
    'NoCertificado = 01234567890123456789'.PHP_EOL.//pendiente lo debe proporcionar la empresa
    'Certificado = zg=='.PHP_EOL.//pendiente lo debe proporcionar la empresa
    'CondicionesDePago = '.$factura->condicion_pago.PHP_EOL.//
    'SubTotal = '.$suma_subtotal.PHP_EOL.
    ($factura->sctf_c_tipofactura == 'P' ? '' : 'Descuento = '.$suma_descuento).PHP_EOL.
    ($factura->sctf_c_tipofactura == 'P' ? 'Moneda = XXX' :'Moneda = '.$factura->scm_c_moneda).PHP_EOL.
    ($factura->sctf_c_tipofactura == 'P' ? '' : 'TipoCambio = '.$to).PHP_EOL.
    'Total = '.((($suma_subtotal - $suma_descuento) + round($suma_impuesto,2))- round($suma_retencion,2)).PHP_EOL.//correcta forma totalTOtsl
    'TipoDeComprobante = '.$factura->sctf_c_tipofactura.PHP_EOL.
    ($factura->sctf_c_tipofactura == 'P' ? '' : 'MetodoPago = '.$factura->scmp_c_metodopago).PHP_EOL.
    'LugarExpedicion = '.$factura->codigo_postal.PHP_EOL;
    // 'Confirmacion = DATO1'.PHP_EOL;//pediente por asignar y si es asignado ??

    return $comprobante;
  }

  /**
  * [Construcción del nodo cfdi:CfidRelacionados del archivo .ini]
  * @param  Array  $factura      [Array recibido en la funcion]
  * @return String $relacionados [Cadena concatenada]
  */
  public function Relacionados($factura)
  {
    $relacionados_f = DB::table('facturas_relacionadas')->where('factura_id',$factura->id)->get();

    $relacionados = '';
    $relacionados .= '[cfdi:Comprobante/cfdi:CfdiRelacionados]'.PHP_EOL.
    'TipoRelacion ='.$factura->tiporelacion_cod.PHP_EOL;
    foreach ($relacionados_f as $key => $value) {
      $relacionados .=  '[cfdi:Comprobante/cfdi:CfdiRelacionados/cfdi:CfdiRelacionado]'.PHP_EOL.
        'UUID ='.$value->uuid.PHP_EOL;
    }

    return $relacionados;
  }

  /**
  * [Construcción del nodo cfdi:Emisor del archivo .ini]
  * @param  Int    $id       [id de tipo  entero recibido en la funcion]
  * @return String $emisor_s [Cadena concatenada]
  */
  public function CEmisor($id)
  {
    $emisor = \App\DatosGenerales::where('id',$id)->first();
    $regimen = explode(' ',$emisor->regimenfiscal);
    $emisor_s = '';
    $emisor_s = 'Rfc = '.$emisor->rfc.PHP_EOL.'Nombre = '.$emisor->razon_social.PHP_EOL.'RegimenFiscal = '.$regimen[0];//pendiente por asignar por parte de la empresa

    return $emisor_s;
  }

  /**
  * [Construcción del nodo cfdi:Receptor del archivo .ini]
  * @param  Int    $id       [id de tipo  entero recibido en la funcion]
  * @param  Int    $uso_cfdi [id de tipo  entero recibido en la funcion]
  * @return String $receptor [Cadena concatenada]
  */
  public function CReceptor($id,$uso_cfdi)
  {

    $receptor_ = \App\Clientes::where('id',$id)->first();

    $receptor = '';
    $receptor = 'Rfc = '.$receptor_->rfc.PHP_EOL.//pendiente hasta tener los rfc correctos
    'Nombre = '.$receptor_->nombre.PHP_EOL.
    'UsoCFDI = '.$uso_cfdi;//pendiente por asignar
    return $receptor;
  }

  /**
  * [Construcción del nodo cfdi:Conceptos del archivo .ini]
  * @param  Array  $factura   [Array recibido en la función]
  * @param  Array  $partidas  [Array recibido en la función]
  * @return String $conceptos [Cadena concatenada]
  */
  public function Conceptos($factura, $partidas)
  {
    $conceptos = '';
    foreach ($partidas as $key => $value) {
      // code...
      $conceptos .= '[cfdi:Comprobante/cfdi:Conceptos/cfdi:Concepto]'.PHP_EOL.
      'ClaveProdServ = '.$value->scps_clave.PHP_EOL.
      ($factura->sctf_c_tipofactura == 'P' ? '' : 'NoIdentificacion = Dato').PHP_EOL.//pendiente ver si se expide
      'Cantidad = '.($factura->sctf_c_tipofactura == 'P' ? '1' : $value->cantidad).PHP_EOL.
      'ClaveUnidad = '.$value->scu_c_unidad.PHP_EOL.
      ($factura->sctf_c_tipofactura == 'P' ? '' : 'Unidad = '.$value->unidad).PHP_EOL.
      'Descripcion = '.($factura->sctf_c_tipofactura == 'P' ? 'Pago' : str_replace('\n',' ',$value->descripcion)).PHP_EOL.
      // 'Comentario = '.($factura->sctf_c_tipofactura == 'P' ? '' : $value->comentario).PHP_EOL.
      'ValorUnitario = '.$value->valor_unitario.PHP_EOL.
      'Importe = '.$value->importe.PHP_EOL.
      ($factura->sctf_c_tipofactura == 'P' ? '' : 'Descuento = '.$value->descuento.PHP_EOL.PHP_EOL.
      $this->impuestos($value->impuesto_iva,$value->importe,$value->retencion));
      ;

    }


    return $conceptos;
  }

  /**
  * [Construcción del nodo cfdi:Impuestos del archivo .ini]
  * @param  Float  $iva       [iva de tipo float recibido en la función]
  * @param  Float  $importe   [imorte de tipo float recibido en la función]
  * @return String $impuestos [Cadena concatenada]
  */
  public function impuestos($iva,$importe,$retencion)
  {
    // code...
    $impuestos = '';
    $impuestos = '[cfdi:Comprobante/cfdi:Conceptos/cfdi:Concepto/cfdi:Impuestos]'.PHP_EOL.PHP_EOL.
    '[cfdi:Comprobante/cfdi:Conceptos/cfdi:Concepto/cfdi:Impuestos/cfdi:Traslados]'.PHP_EOL.PHP_EOL.
    '[cfdi:Comprobante/cfdi:Conceptos/cfdi:Concepto/cfdi:Impuestos/cfdi:Traslados/cfdi:Traslado]'.PHP_EOL.
    'Base = '.$importe.PHP_EOL.
    'Impuesto = 002'.PHP_EOL.
    'TipoFactor = Tasa'.PHP_EOL.
    'TasaOCuota = '.$iva.PHP_EOL.
    'Importe = '.(($importe * $iva)).PHP_EOL.
    ($retencion == '' ? '' :  PHP_EOL.
    '[cfdi:Comprobante/cfdi:Conceptos/cfdi:Concepto/cfdi:Impuestos/cfdi:Retenciones]'.PHP_EOL.PHP_EOL.
    '[cfdi:Comprobante/cfdi:Conceptos/cfdi:Concepto/cfdi:Impuestos/cfdi:Retenciones/cfdi:Retencion]'.PHP_EOL.
    'Base = '.$importe.PHP_EOL.
    'Impuesto = 002'.PHP_EOL.
    'TipoFactor = Tasa'.PHP_EOL.
    'TasaOCuota = '.$retencion.PHP_EOL.
    'Importe = '.(($importe * $retencion)).PHP_EOL);
    return $impuestos;
  }

  /**
  * [Construcción del nodo TotalImpuestosTraslados del archivo .ini]
  * @param  Arrray $partidas       [Array recibido en la función]
  * @return String $totalimpuestos [Cadena concatenada]
  */
  public function totalImpuestos($partidas)
  {
    $suma_impuesto = 0;
    $suma_retencion = 0;
    foreach ($partidas as $key => $value) {
      $suma_impuesto += ($value->importe * $value->impuesto_iva);
      if($value->retencion != ''){
        $suma_retencion += ($value->importe * $value->retencion);
      }
    }
    $totalimpuestos = '';
    $totalimpuestos = ($partidas[0]->retencion == '' ? '' :'TotalImpuestosRetenidos = '.round($suma_retencion,2)).PHP_EOL.'TotalImpuestosTrasladados = '.round($suma_impuesto,2).PHP_EOL.PHP_EOL.
    ($partidas[0]->retencion == '' ? '' :
    '[cfdi:Comprobante/cfdi:Impuestos/cfdi:Retenciones]'.PHP_EOL.PHP_EOL.
    '[cfdi:Comprobante/cfdi:Impuestos/cfdi:Retenciones/cfdi:Retencion]'.PHP_EOL.
    'Impuesto = 002'.PHP_EOL.
    'Importe = '.round($suma_retencion,2).PHP_EOL)
    .'[cfdi:Comprobante/cfdi:Impuestos/cfdi:Traslados]'.PHP_EOL.PHP_EOL.
    '[cfdi:Comprobante/cfdi:Impuestos/cfdi:Traslados/cfdi:Traslado]'.PHP_EOL.
    'Impuesto = 002'.PHP_EOL.
    'TipoFactor = Tasa'.PHP_EOL.
    'TasaOCuota = '.$partidas[0]->impuesto_iva.PHP_EOL.
    'Importe = '.round($suma_impuesto,2).PHP_EOL
    ;

    return $totalimpuestos;
  }

  public function trucarDecimal( $value, $precision )
   {
       //Casts provided value
       $value = ( string )$value;

       //Gets pattern matches
       preg_match( "/(-+)?\d+(\.\d{1,".$precision."})?/" , $value, $matches );

       //Returns the full pattern match
       return $matches[0];
   }

  /**
  * [Devuelve la fecha y hora]
  * @return String $hoy [Cadena concatenada de la fecha y hora actual]
  */
  public function hoy()
  {
    $hoy = '';
    $hoy = date("Y-m-d").'T'.date("H:i:s");

    return $hoy;
  }

  /**
  * [Construccion del archivo PDF de la factura timbrada, creacion del codigo QR con la validacion del SAT]
  * @param  String  $id [Nombre del archivo]
  * @return Stream      [Preview del pdf a descargar]
  */
  public function descargarfactura($id)
  {
    try {
    //se aplica un explode del id recibido que es un string del nombre del archivo
    $valores = explode('-',$id);
    $uuid = '';$rest = '';$total = '';$cadenaoriginal = '';$rfc = '';$url = '';$numcersat = '';$sellocfd = '';
    $sellosat = '';$fecha_emision = ''; $tasa = '';$importe = ''; $impuesto = '';

    $factura = \App\Factura::where('factura.id',$valores[0])
    ->join('clientes AS c','c.id','=','factura.cliente_id')
    ->join('datosgenerales AS dg','dg.id','=','factura.emisor_id')
    ->join('sat_cat_usocfdi AS scuc','scuc.id','=','factura.uso_factura')
    ->join('sat_cat_formapago AS scfp','scfp.id','=','factura.formapago')
    ->join('sat_cat_metodopago AS scmp','scmp.id','=','factura.metodopago')
    ->join('sat_cat_monedas AS scm','scm.id','=','factura.moneda_id')
    ->join('sat_cat_tipofactura AS sctf','sctf.id','=','factura.tipo_factura_id')
    ->leftJoin('sat_cat_tiporelacion AS sctr','sctr.id','=','factura.tipo_relacion')
    ->leftJoin('factura AS f','f.id','=','factura.factura_id')
    ->select('factura.*','sctf.descripcion AS tipo_factura_d','c.rfc AS rfc_c','dg.rfc AS rfc_e',DB::raw("CONCAT(scuc.c_uso,' ',scuc.descripcion) AS uso_cfdi"),DB::raw("CONCAT(scfp.clave,' ',scfp.descripcion) AS forma_pago"),DB::raw("CONCAT(scmp.c_metodopago,' ',scmp.descripcion) AS metodo_pago"),'scm.c_moneda AS scm_c_moneda',
    'sctr.c_tiporelacion AS tiporel_cod','sctr.descripcion AS tiporel_desc','f.serie AS fr_serie','f.folio AS fr_folio','f.uuid AS fr_uuid',
    'f.total AS fr_total')->first();
    $partidas_factura = \App\PartidasFactura::where('factura_id',$id)
    // ->join('sat_cat_prodser as scp','scp.id','=','partidas_factura.productos_servicios_id')
    ->join('sat_cat_unidades AS scu','scu.id','=','partidas_factura.unidad_id')
    ->select('partidas_factura.*','scu.c_unidad as cla')->get();

    $arreglo_pf = [];
    foreach ($partidas_factura as $key_pf => $value_pf) {
      $ps = DB::table('sat_cat_prodser')->where('id',$value_pf->productos_servicios_id)->first();
      if (isset($ps) == false) {
        $ps = DB::table('sat_cat_prodser_big')->where('id',$value_pf->productos_servicios_id)->first();
      }
      $arreglo_pf [] = [
        'partidas' => $value_pf,
        'sat_ps' => $ps->clave,
      ];
    }
    $a = \App\Relacionados::where('factura_id',$id)->get();


    $partidas_pagos = \App\PartidasFacturasPagos::where('partidas_facturas_id','=',$partidas_factura[0]->id)
    ->select('partidas_facturas_pagos.*')->get();


    $emisor = \App\DatosGenerales::where('id',$factura->emisor_id)->first();
    $receptor = \App\Clientes::where('id',$factura->cliente_id)->first();

    $xml_string = Storage::disk('local')->get('Facturas/'.$id.'tim.xml');
    $xml = simplexml_load_string($xml_string);
    $ns = $xml->getNamespaces(true);
    $xml->registerXPathNamespace('c', $ns['cfdi']);
    $xml->registerXPathNamespace('t', $ns['tfd']);

    foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
      $uuid = $tfd['UUID'];
      $sellosat = $tfd['SelloSAT'];
      $sellocfd = $tfd['SelloCFD'];
      $fecha_cer = $tfd['FechaTimbrado'];
      $rfc = $tfd['RfcProvCertif'];
      $numcersat = $tfd['NoCertificadoSAT'];
      $rest = substr($tfd['SelloCFD'], -8);
    }
    foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
      $total = $cfdiComprobante['Total'];
      $certificado_emisor = $cfdiComprobante['NoCertificado'];
    }
    $factura_actualizar = \App\Factura::where('id',$valores[0])->first();
    $factura_actualizar->uuid = $uuid;
    $factura_actualizar->save();

    $cadenaoriginal = '||1.1|'.$uuid.'|'.$fecha_cer.'|'.$rfc.'|'.$sellocfd.'|'.$numcersat.'||';
    $suma_subtotal = 0;
    $suma_descuento = 0;
    $suma_impuesto = 0;
    $suma_retencion = 0;
    $total = 0;
    $suma_partidas_pagos = 0;
    foreach ($partidas_factura as $key => $value) {
      $suma_subtotal += $value->importe;
      $suma_descuento += $value->descuento;
      $suma_impuesto += ($value->importe * $value->impuesto_iva);
      if($value->retencion != ''){
        $suma_retencion += ($value->importe * $value->retencion);
      }
    }
    foreach ($partidas_pagos as $key => $value) {
      $suma_partidas_pagos += $value->importe_pagado;
    }


    $total = ((($suma_subtotal - $suma_descuento) + $suma_impuesto)- $suma_retencion);
    $cambio = Utilidades::valorEnLetrasFactura(round($total,2) , $factura->moneda_id);
    $v_l_total_pagos = Utilidades::valorEnLetrasFactura(round($suma_partidas_pagos,2), $factura->moneda_id);

    $url = 'https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?id='.$uuid.'&re='.$factura->rfc_e.'&rr='.$factura->rfc_c.'&tt='.round($total,2).'&fe='.$rest;
    $qr = \QrCode::size(300)
    ->format('png')
    // ->errorCorrection('Q')
    ->generate($url, public_path('img/'.$id.'.png'));

    $mes = array('Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sep.', 'Oct.', 'Nov.', 'Dic.');
    $num_mes = intval(substr($factura->fecha_hora,5,2));
    $num_mes_cer = intval(substr($fecha_cer,5,2));

    $fecha_emision =substr($factura->fecha_hora,8,2).' '.$mes[$num_mes - 1].' '.substr($factura->fecha_hora,0,4).' - '.substr($factura->fecha_hora,11);
    $fecha_certificacion =substr($fecha_cer,8,2).' '.$mes[$num_mes_cer - 1].' '.substr($fecha_cer,0,4).' - '.substr($fecha_cer,11);
    $pdf = PDF::loadView('pdf.factura', compact('factura','arreglo_pf','partidas_factura','uuid','sellosat','rest','total','certificado_emisor','id','fecha_emision',
    'emisor','receptor','suma_subtotal','suma_descuento','suma_impuesto','suma_retencion','cambio','numcersat','sellocfd','a',
    'cadenaoriginal','fecha_certificacion','partidas_pagos','v_l_total_pagos','suma_partidas_pagos'));
    $pdf->setPaper("A4", "portrait");
    return $pdf->stream();
  } catch (\Throwable $e) {
    Utilidades::errors($e);
    return view('errors.204');
  }

  }


  /**
  * [Construccion del archivo PDF de la factura timbrada, creacion del codigo QR con la validacion del SAT]
  * @param  String  $id [Nombre del archivo]
  * @return Stream      [Preview del pdf a descargar]
  */
  public function descargarfacturaprueba($id)
  {
    try {
    //se aplica un explode del id recibido que es un string del nombre del archivo
    $valores = explode('-',$id);
    $uuid = '';$rest = '';$total = '';$cadenaoriginal = '';$rfc = '';$url = '';$numcersat = '';$sellocfd = '';
    $sellosat = '';$fecha_emision = ''; $tasa = '';$importe = ''; $impuesto = '';

    $factura = \App\Factura::where('factura.id',$valores[0])
    ->join('clientes AS c','c.id','=','factura.cliente_id')
    ->join('datosgenerales AS dg','dg.id','=','factura.emisor_id')
    ->join('sat_cat_usocfdi AS scuc','scuc.id','=','factura.uso_factura')
    ->join('sat_cat_formapago AS scfp','scfp.id','=','factura.formapago')
    ->join('sat_cat_metodopago AS scmp','scmp.id','=','factura.metodopago')
    ->join('sat_cat_monedas AS scm','scm.id','=','factura.moneda_id')
    ->join('sat_cat_tipofactura AS sctf','sctf.id','=','factura.tipo_factura_id')
    ->leftJoin('sat_cat_tiporelacion AS sctr','sctr.id','=','factura.tipo_relacion')
    ->leftJoin('factura AS f','f.id','=','factura.factura_id')
    ->select('factura.*','sctf.descripcion AS tipo_factura_d','c.rfc AS rfc_c','dg.rfc AS rfc_e',DB::raw("CONCAT(scuc.c_uso,' ',scuc.descripcion) AS uso_cfdi"),DB::raw("CONCAT(scfp.clave,' ',scfp.descripcion) AS forma_pago"),DB::raw("CONCAT(scmp.c_metodopago,' ',scmp.descripcion) AS metodo_pago"),'scm.c_moneda AS scm_c_moneda',
    'sctr.c_tiporelacion AS tiporel_cod','sctr.descripcion AS tiporel_desc','f.serie AS fr_serie','f.folio AS fr_folio','f.uuid AS fr_uuid',
    'f.total AS fr_total')->first();
    $partidas_factura = \App\PartidasFactura::where('factura_id',$id)
    // ->join('sat_cat_prodser as scp','scp.id','=','partidas_factura.productos_servicios_id')
    ->join('sat_cat_unidades AS scu','scu.id','=','partidas_factura.unidad_id')
    ->select('partidas_factura.*','scu.c_unidad as cla')->get();

    $arreglo_pf = [];
    foreach ($partidas_factura as $key_pf => $value_pf) {
      $ps = DB::table('sat_cat_prodser')->where('id',$value_pf->productos_servicios_id)->first();
      if (isset($ps) == false) {
        $ps = DB::table('sat_cat_prodser_big')->where('id',$value_pf->productos_servicios_id)->first();
      }
      $arreglo_pf [] = [
        'partidas' => $value_pf,
        'sat_ps' => $ps->clave,
      ];
    }
    $a = \App\Relacionados::where('factura_id',$id)->get();


    $partidas_pagos = \App\PartidasFacturasPagos::where('partidas_facturas_id','=',$partidas_factura[0]->id)
    ->select('partidas_facturas_pagos.*')->get();


    $emisor = \App\DatosGenerales::where('id',$factura->emisor_id)->first();
    $receptor = \App\Clientes::where('id',$factura->cliente_id)->first();

    $xml_string = Storage::disk('local')->get('Facturas/'.$id.'timp.xml');
    $xml = simplexml_load_string($xml_string);
    $ns = $xml->getNamespaces(true);
    $xml->registerXPathNamespace('c', $ns['cfdi']);
    $xml->registerXPathNamespace('t', $ns['tfd']);

    foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
      $uuid = $tfd['UUID'];
      $sellosat = $tfd['SelloSAT'];
      $sellocfd = $tfd['SelloCFD'];
      $fecha_cer = $tfd['FechaTimbrado'];
      $rfc = $tfd['RfcProvCertif'];
      $numcersat = $tfd['NoCertificadoSAT'];
      $rest = substr($tfd['SelloCFD'], -8);
    }
    foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
      $total = $cfdiComprobante['Total'];
      $certificado_emisor = $cfdiComprobante['NoCertificado'];
    }
    $factura_actualizar = \App\Factura::where('id',$valores[0])->first();
    $factura_actualizar->uuid = $uuid;
    $factura_actualizar->save();

    $cadenaoriginal = '||1.1|'.$uuid.'|'.$fecha_cer.'|'.$rfc.'|'.$sellocfd.'|'.$numcersat.'||';
    $suma_subtotal = 0;
    $suma_descuento = 0;
    $suma_impuesto = 0;
    $suma_retencion = 0;
    $total = 0;
    $suma_partidas_pagos = 0;
    foreach ($partidas_factura as $key => $value) {
      $suma_subtotal += $value->importe;
      $suma_descuento += $value->descuento;
      $suma_impuesto += ($value->importe * $value->impuesto_iva);
      if($value->retencion != ''){
        $suma_retencion += ($value->importe * $value->retencion);
      }
    }
    foreach ($partidas_pagos as $key => $value) {
      $suma_partidas_pagos += $value->importe_pagado;
    }


    $total = ((($suma_subtotal - $suma_descuento) + $suma_impuesto)- $suma_retencion);
    $cambio = Utilidades::valorEnLetrasFactura(round($total,2) , $factura->moneda_id);
    $v_l_total_pagos = Utilidades::valorEnLetrasFactura(round($suma_partidas_pagos,2), $factura->moneda_id);

    $url = 'https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?id='.$uuid.'&re='.$factura->rfc_e.'&rr='.$factura->rfc_c.'&tt='.round($total,2).'&fe='.$rest;
    $qr = \QrCode::size(300)
    ->format('png')
    // ->errorCorrection('Q')
    ->generate($url, public_path('img/'.$id.'.png'));

    $mes = array('Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sep.', 'Oct.', 'Nov.', 'Dic.');
    $num_mes = intval(substr($factura->fecha_hora,5,2));
    $num_mes_cer = intval(substr($fecha_cer,5,2));

    $fecha_emision =substr($factura->fecha_hora,8,2).' '.$mes[$num_mes - 1].' '.substr($factura->fecha_hora,0,4).' - '.substr($factura->fecha_hora,11);
    $fecha_certificacion =substr($fecha_cer,8,2).' '.$mes[$num_mes_cer - 1].' '.substr($fecha_cer,0,4).' - '.substr($fecha_cer,11);
    $pdf = PDF::loadView('pdf.factura', compact('factura','arreglo_pf','partidas_factura','uuid','sellosat','rest','total','certificado_emisor','id','fecha_emision',
    'emisor','receptor','suma_subtotal','suma_descuento','suma_impuesto','suma_retencion','cambio','numcersat','sellocfd','a',
    'cadenaoriginal','fecha_certificacion','partidas_pagos','v_l_total_pagos','suma_partidas_pagos'));
    $pdf->setPaper("A4", "portrait");
    return $pdf->stream();
  } catch (\Throwable $e) {
    Utilidades::errors($e);
    return view('errors.204');
  }

  }

  /**
  * [Construccion del archivo PDF de la prefactura antes de ser timbrada]
  * @param  String  $id [Nombre del archivo]
  * @return Stream      [Preview del pdf a descargar]
  */
  public function descargarprefactura($id)
  {
    try {
      $factura = \App\Factura::where('factura.id',$id)
      ->join('clientes AS c','c.id','=','factura.cliente_id')
      ->join('datosgenerales AS dg','dg.id','=','factura.emisor_id')
      ->join('sat_cat_usocfdi AS scuc','scuc.id','=','factura.uso_factura')
      ->join('sat_cat_formapago AS scfp','scfp.id','=','factura.formapago')
      ->join('sat_cat_metodopago AS scmp','scmp.id','=','factura.metodopago')
      ->join('sat_cat_monedas AS scm','scm.id','=','factura.moneda_id')
      ->join('sat_cat_tipofactura AS sctf','sctf.id','=','factura.tipo_factura_id')
      ->leftJoin('sat_cat_tiporelacion AS sctr','sctr.id','=','factura.tipo_relacion')
      ->leftJoin('factura AS f','f.id','=','factura.factura_id')
      ->select('factura.*','sctf.descripcion AS tipo_factura_d','c.rfc AS rfc_c','dg.rfc AS rfc_e',DB::raw("CONCAT(scuc.c_uso,' ',scuc.descripcion) AS uso_cfdi"),DB::raw("CONCAT(scfp.clave,' ',scfp.descripcion) AS forma_pago"),DB::raw("CONCAT(scmp.c_metodopago,' ',scmp.descripcion) AS metodo_pago"),'scm.c_moneda AS scm_c_moneda',
      'sctr.c_tiporelacion AS tiporel_cod','sctr.descripcion AS tiporel_desc','f.serie AS fr_serie','f.folio AS fr_folio','f.uuid AS fr_uuid',
      'f.total AS fr_total')->first();

      $partidas_factura = \App\PartidasFactura::where('factura_id',$id)
      // ->join('sat_cat_prodser as scp','scp.id','=','partidas_factura.productos_servicios_id')
      ->join('sat_cat_unidades AS scu','scu.id','=','partidas_factura.unidad_id')
      ->select('partidas_factura.*','scu.c_unidad as cla')->get();

      $arreglo_pf = [];
      foreach ($partidas_factura as $key_pf => $value_pf) {
        $ps = DB::table('sat_cat_prodser')->where('id',$value_pf->productos_servicios_id)->first();
        if (isset($ps) == false) {
          $ps = DB::table('sat_cat_prodser_big')->where('id',$value_pf->productos_servicios_id)->first();
        }
        $arreglo_pf [] = [
          'partidas' => $value_pf,
          'sat_ps' => $ps->clave,
        ];
      }
      $a = \App\Relacionados::where('factura_id',$id)->get();

      // $a = \App\Relacionados::where('factura_id',$id)->get();

      $partidas_pagos = \App\PartidasFacturasPagos::where('partidas_facturas_id','=',$partidas_factura[0]->id)
      ->select('partidas_facturas_pagos.*')->get();

      $emisor = \App\DatosGenerales::where('id',$factura->emisor_id)->first();
      $receptor = \App\Clientes::where('id',$factura->cliente_id)->first();
      $suma_subtotal = 0;
      $suma_descuento = 0;
      $suma_impuesto = 0;
      $suma_retencion = 0;
      $total = 0;
      $suma_partidas_pagos = 0;
      foreach ($partidas_factura as $key => $value) {
        $suma_subtotal += $value->importe;
        $suma_descuento += $value->descuento;
        $suma_impuesto += ($value->importe * $value->impuesto_iva);
        if($value->retencion != ''){
          $suma_retencion += ($value->importe * $value->retencion);
        }
      }
      foreach ($partidas_pagos as $key => $value) {
        $suma_partidas_pagos += $value->importe_pagado;
      }

      $total = ((($suma_subtotal - $suma_descuento) + $suma_impuesto)- $suma_retencion);
      $cambio = Utilidades::valorEnLetrasFactura(round($total,2), $factura->moneda_id);
      $v_l_total_pagos = Utilidades::valorEnLetrasFactura(round($suma_partidas_pagos,2), $factura->moneda_id);

      $mes = array('Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sep.', 'Oct.', 'Nov.', 'Dic.');
      $num_mes = intval(substr($factura->fecha_hora,5,2));
      $fecha_emision =substr($factura->fecha_hora,8,2).' '.$mes[$num_mes - 1].' '.substr($factura->fecha_hora,0,4).' - '.substr($factura->fecha_hora,11);
      $pdf = PDF::loadView('pdf.prefactura', compact('factura','arreglo_pf','partidas_factura','total','fecha_emision',
      'a','emisor','receptor','suma_subtotal','suma_descuento','suma_impuesto','suma_retencion','cambio','partidas_pagos',
      'v_l_total_pagos','suma_partidas_pagos'));
      $pdf->setPaper("A4", "portrait");
      return $pdf->stream();
    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return view('errors.204');
    }

  }

  /**
  * [Creación del nodo Complemento/pago10:Pagos del archivo .ini]
  * @param  Array  $factura        [Array recibido en la función]
  * @param  Array  $partida        [Array recibido en la función]
  * @param  Array  $partidas_pagos [Array recibido en la función]
  * @return String $complemento    [cadena concatenada]
  */
  public function Complemento($factura,$partida,$partidas_pagos)
  {
    // code...
    $receptor_ = \App\Clientes::where('id',$factura->cliente_id)->first();
    $fecha_p = substr($factura->fecha_pago, 0,4).'-'.substr($factura->fecha_pago, 5,2).'-'.substr($factura->fecha_pago, 8,2).'T'.substr($factura->fecha_pago, 11, 8);

    $complemento = '';
    $complemento .= '[cfdi:Comprobante/cfdi:Complemento/pago10:Pagos]'.PHP_EOL.
    'Version = 1.0'.PHP_EOL;
    foreach ($partidas_pagos as $key => $value) {
      $complemento.='[cfdi:Comprobante/cfdi:Complemento/pago10:Pagos/pago10:Pago]'.PHP_EOL.
      'FechaPago ='.$fecha_p.PHP_EOL.
      'FormaDePagoP ='.$factura->scfp_clave.PHP_EOL.
      'MonedaP ='.$factura->scm_c_moneda.PHP_EOL.
      ($factura->scm_c_moneda == 'MXN' ? '' : 'TipoCambioP ='.$factura->tipo_cambio).PHP_EOL.
      'Monto ='.$value->importe_pagado.PHP_EOL.
      'NumOperacion = 0'.PHP_EOL.
      'RfcEmisorCtaBen ='.(/*$factura->sctf_c_tipofactura == 'P' ? 'AAA010101AAA' :*/$factura->rfc_cuenta_beneficiario).PHP_EOL.
      'RfcEmisorCtaOrd ='.$factura->rfc_cuenta_ordenante.PHP_EOL.
      'NomBancoOrdExt ='.$factura->ban_ordenante.PHP_EOL.
        'CtaBeneficiario ='.$factura->cuenta_ordenante.PHP_EOL.PHP_EOL.
        '[cfdi:Comprobante/cfdi:Complemento/pago10:Pagos/pago10:Pago/pago10:DoctoRelacionado]'.PHP_EOL.
        'IdDocumento ='.$value->uuid.PHP_EOL.
        'Serie ='.$value->serie.PHP_EOL.
        'Folio ='.$value->folio.PHP_EOL.
        'MonedaDR ='.$factura->scm_c_moneda.PHP_EOL.
        'MetodoDePagoDR ='.$factura->scmp_c_metodopago.PHP_EOL.
        'NumParcialidad = 1'.PHP_EOL.
        'ImpSaldoAnt = '.$value->saldo_anterior.PHP_EOL.
        'ImpPagado ='.$value->importe_pagado.PHP_EOL.
        'ImpSaldoInsoluto ='.$value->saldo_insoluto.PHP_EOL.PHP_EOL;

      }
      return $complemento;
    }

    /**
    * [Creación del nodo cfdi:Addenda del archivo .ini]
    * @param Array  $factura [Array recibido en la función]
    * @param String $adenda  [Cadena concatenada]
    */
    public function Adenda($factura,$partida)
    {
      $adenda = '';
      $to = $factura->tipo_cambio == 1.00 ? '1' : $factura->tipo_cambio;
      $adenda .= '[cfdi:Comprobante/cfdi:Addenda]'.PHP_EOL.
      '[cfdi:Comprobante/cfdi:Addenda/cfdi:'.$factura->adenda.']'.PHP_EOL.
      '[cfdi:Comprobante/cfdi:Addenda/cfdi:'.$factura->adenda.'/cfdi:Factura]'.PHP_EOL.
      'Moneda ='.$factura->scm_c_moneda.PHP_EOL.
      'TipoCambio = '.$to.PHP_EOL.PHP_EOL.
      '[cfdi:Comprobante/cfdi:Addenda/cfdi:'.$factura->adenda.'/cfdi:Informacion]'.PHP_EOL.
      'Proveedor ='.$factura->proveeade.PHP_EOL.
      'NoRecepcion ='.$factura->clave_ob.PHP_EOL.
      'OrdenCompra ='.$factura->orden_ob.PHP_EOL;
      foreach ($partida as $key => $value) {
        if ($value->comentario != '') {
          $adenda .=  'DescripcionCompleta'.$value->scps_clave.' = '.str_replace('\n',' ',$value->descripcion).' '.str_replace('\n',' ',$value->comentario).PHP_EOL;
        }
      }

      return $adenda;
    }

    /**
    * [Cancelación de una factura ya timbrada utilizando la DLL y cambio del campo timbrado = 2 'Cancelado' en la tabla factura]
    * @param Int    $id [id de tipo entero recibido en la función]
    * @return Array     [Array con estatus true, respuesta y emisor_id]
    */
    public function cancelarfactura($id)
    {
      $factura = \App\Factura::where('factura.id',$id)->join('datosgenerales AS DG','DG.id','=','factura.emisor_id')
      ->select('factura.*','DG.rfc AS rfc_emisor')->first();
      $uuid = strtoupper($factura->uuid);
      $rfc = $factura->rfc_emisor;

      // $output = shell_exec("cd /home/libfaclinconser/;./cancelar.sh $rfc $uuid 2> ehoyc.txt;cat ehoyc.txt;");
      if ($factura->emisor_id == '1') {
        $output = shell_exec("cd /home/libfaclinflow/;./cancelar.sh $rfc $uuid 2> $uuid.xml;");
      }

      $facturas = \App\Factura::where('id',$id)->first();
      $facturas->timbrado = '2';
      $facturas->save();

      return response()->json(array(
        'status' => true,
        'respuesta' => $output,
        'emisor_id' => $factura->emisor_id,

      ));


    }

    public function cancelarPrueba($id)
    {
      try {
        $facturas = \App\Factura::where('id',$id)->first();
        $facturas->timbrado = '0';
        $facturas->save();

        return response()->json(array(
          'status' => true,
          // 'respuesta' => $output,
          'emisor_id' => $facturas->emisor_id,

        ));
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }

      // code...
    }
  }
