<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Helpers\Utilidades;
use App\Repositories\Viaticos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;

class ViaticoTimbradoController extends Controller
{
  protected $viatico;

  public function __construct(Viaticos $viatico)
  {
    $this->viaticos = $viatico;
  }

  public function getData($id)
  {
    try {
      $valores = explode('&',$id);
      $solicitud_id = $valores[0];
      $empresa = $valores[1];

      if ($empresa == 1) {
        $arreglo = [];

        $viaticos = DB::table('solicitud_viaticos')
        ->select('solicitud_viaticos.*')
        ->where('solicitud_viaticos.id','=',$solicitud_id)
        // ->where('solicitud_viaticos.estado','!=','1')
        ->first();

        // foreach ($viaticos as $key => $viatico) {

        $beneficiarios = $this->viaticos->BeneficiarioViatico($viaticos->id);
        $empleado = DB::table('empleados AS e')->where('e.id',$beneficiarios[0]->empleado_beneficiario_id)->first();
        $datos_empleado = DB::table('empleados_viaticos')->where('nss',$empleado->nss_imss)->first();
        $detalles_viaticos = $this->viaticos->DetalleViatico($viaticos->id);
        $solicitud_viaticos_timbre = \App\SolicitudViaticosTimbrado::join('sat_cat_prodser AS scp','scp.id','solicitud_viaticos_timbrado.clave_sat')
        ->select('solicitud_viaticos_timbrado.*',DB::raw("CONCAT(scp.clave,' ',scp.descripcion) AS descripcion_sat"))
        ->where('solicitud_viatico_id',$solicitud_id)
        ->where('empresa',$empresa)->first();

        $partidas = [];
        if (isset($solicitud_viaticos_timbre) == true) {
          $partidas = \App\OtrosPagosViaticosTimbre::
          join('sat_cat_tipo_otro_pago AS sctop','sctop.id','otros_pagos_viaticos_timbre.sat_cat_tipo_otro_pago_id')
          ->select('otros_pagos_viaticos_timbre.*',DB::raw("CONCAT(sctop.c_tipootropago,' ',sctop.descripcion) AS desct"))
          ->where('solicitud_viaticos_timbrado_id',$solicitud_viaticos_timbre->id)
          ->get();
        }

        $comprobacion = DB::table('viaticos')->select(DB::raw("SUM(gastos_comprobados_deducibles) AS total"))
        ->where('solicitud_viaticos_id',$solicitud_id)->first();


        $partidas_pagos = $this->viaticos->partidasPagos($viaticos->id);
        $arreglo [] = [
          'solicitud' => $viaticos,
          'beneficiario' => $beneficiarios,
          'detalle' => $detalles_viaticos,
          'pagos' => $partidas_pagos,
          'empleado' => $empleado,
          'datos_empleado' => $datos_empleado,
          'timbre' => $solicitud_viaticos_timbre,
          'partidas' => $partidas,
          'comprobacion' => $comprobacion,
        ];
        // }

        return response()->json($arreglo);

      }elseif ($empresa == 2) {
        $arreglo = [];

        $viaticos = \App\SolicitudViaticosDBP::
        select('solicitud_viaticos.*')
        ->where('solicitud_viaticos.id','=',$solicitud_id)
        ->first();

        // foreach ($viaticos as $key => $viatico) {

        $beneficiarios = $this->viaticos->BeneficiarioViaticoCSCT($viaticos->id);
        $empleado = \App\EmpleadoDBP::where('id',$beneficiarios[0]->empleado_beneficiario_id)->first();
        $detalles_viaticos = $this->viaticos->DetalleViaticoCSCT($viaticos->id);

        $solicitud_viaticos_timbre = \App\SolicitudViaticosTimbrado::join('sat_cat_prodser AS scp','scp.id','solicitud_viaticos_timbrado.clave_sat')
        ->select('solicitud_viaticos_timbrado.*',DB::raw("CONCAT(scp.clave,' ',scp.descripcion) AS descripcion_sat"))
        ->where('solicitud_viatico_id',$solicitud_id)
        ->where('empresa',$empresa)->first();
        $partidas = [];
        if (isset($solicitud_viaticos_timbre) == true) {
          $partidas = \App\OtrosPagosViaticosTimbre::
          join('sat_cat_tipo_otro_pago AS sctop','sctop.id','otros_pagos_viaticos_timbre.sat_cat_tipo_otro_pago_id')
          ->select('otros_pagos_viaticos_timbre.*',DB::raw("CONCAT(sctop.c_tipootropago,' ',sctop.descripcion) AS desct"))
          ->where('solicitud_viaticos_timbrado_id',$solicitud_viaticos_timbre->id)
          ->get();
        }


        $partidas_pagos = $this->viaticos->partidasPagosCSCT($viaticos->id);

        $arreglo [] = [
          'solicitud' => $viaticos,
          'beneficiario' => $beneficiarios,
          'detalle' => $detalles_viaticos,
          'pagos' => $partidas_pagos,
          'empleado' => $empleado,
          'timbre' => $solicitud_viaticos_timbre,
          'partidas' => $partidas,
        ];
        // }

        return response()->json($arreglo);

      }

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function getDataPartidas($id)
  {
    $valores = explode('&',$id);
    $solicitud_id = $valores[0];
    $empresa = $valores[1];

    $solicitud_viaticos_timbre = \App\SolicitudViaticosTimbrado::join('sat_cat_prodser AS scp','scp.id','solicitud_viaticos_timbrado.clave_sat')
    ->select('solicitud_viaticos_timbrado.*',DB::raw("CONCAT(scp.clave,' ',scp.descripcion) AS descripcion_sat"))
    ->where('solicitud_viatico_id',$solicitud_id)
    ->where('empresa',$empresa)->get();

    return response()->json($solicitud_viaticos_timbre);
  }

  public function claveSat(Request $request)
  {
    try {
      $data = DB::table('sat_cat_prodser')
      ->where('clave','LIKE','%'.$request->des.'%')
      ->orWhere('descripcion','LIKE','%'.$request->des.'%')
      ->select('id',DB::raw("CONCAT(clave,' ',descripcion) AS descripcion"))
      ->get();

      return response()->json($data);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function otroPagoCat()
  {
    $data = DB::table('sat_cat_tipo_otro_pago')->get();

    return response()->json($data);
  }

  public function guardarDatosTimbre(Request $request)
  {
    try {

      // return response()->json($request);
      DB::beginTransaction();
      $general_b = \App\SolicitudViaticosTimbrado::
      where('solicitud_viatico_id',$request->solicitud_id)
      ->where('empresa',$request->empresa)
      ->where('estado_vista',$request->estado_vista)
      ->first();

      if (isset($general_b) == true) {
        $general = \App\SolicitudViaticosTimbrado::
        where('solicitud_viatico_id',$request->solicitud_id)
        ->where('empresa',$request->empresa)
        ->where('estado_vista',$request->estado_vista)
        ->first();
      }else {
        $general = new \App\SolicitudViaticosTimbrado();
      }

      $general->lugar_expedicion = $request['comprobante']['lugar_expedicion'];
      $general->metodopago = $request['comprobante']['metodopago'];
      $general->total = $request['comprobante']['total'];
      $general->formapago = $request['comprobante']['formapago'];
      $general->subtotal = $request['comprobante']['subtotal'];
      $general->descuento = $request['comprobante']['descuento'];
      $general->fecha = $request['comprobante']['fecha'];
      $general->moneda = $request['comprobante']['moneda'];
      $general->tipo_cambio = $request['comprobante']['tipo_cambio'];
      $general->folio = $request['comprobante']['folio'];
      $general->serie = $request['comprobante']['serie'];
      $general->fecha_pago = $request['nomina']['fecha_pago'];
      $general->fecha_pago_i = $request['nomina']['fecha_pago_i'];
      $general->fecha_pago_f = $request['nomina']['fecha_pago_f'];
      $general->dias = $request['nomina']['dias'];
      $general->clave_sat = $request['concepto']['clave_sat']['id'];
      $general->cantidad = $request['concepto']['cantidad'];
      $general->clave_unidad = $request['concepto']['clave_unidad'];
      $general->descripcion = $request['concepto']['descripcion'];
      $general->valor_unitario = $request['concepto']['valor_unitario'];
      $general->importe = $request['concepto']['importe'];
      $general->rfc_r = $request['receptor']['rfc'];
      $general->usocfdi = $request['receptor']['usocfdi'];
      $general->sdi = $request['receptor']['sdi'];
      $general->sbc = $request['receptor']['sbc'];
      $general->periodicidadpago = $request['receptor']['periodicidadpago'];
      $general->riesgopuesto = $request['receptor']['riesgopuesto'];
      $general->puesto = $request['receptor']['puesto'];
      $general->cef = $request['receptor']['cef'];
      $general->departamento = $request['receptor']['departamento'];
      $general->tiporegimen = $request['receptor']['tiporegimen'];
      $general->tipojornada = $request['receptor']['tipojornada'];
      $general->sindicalizado = $request['receptor']['sindicalizado'];
      $general->tipocontrato = $request['receptor']['tipocontrato'];
      $general->antiguedad = $request['receptor']['antiguedad'];
      $general->fechainiciorellaboral = $request['receptor']['fechainiciorellaboral'];
      $general->nss = $request['receptor']['nss'];
      $general->numempleado = $request['receptor']['numempleado'];
      $general->curp = $request['receptor']['curp'];
      $general->solicitud_viatico_id = $request->solicitud_id;
      $general->tipo = $request->tipo;
      $general->empresa = $request->empresa;
      $general->estado_vista = $request->estado_vista;
      $general->emisor_rfc = $request['emisor']['rfc'];
      $general->save();

      //
      // $partida_percepciones= \App\PercepcionesViaticoTimbre::
      // where('solicitud_viaticos_timbrado_id',$general->id)
      // // ->where('sat_cat_tipo_otro_pago_id','!=',$arreglo_delete)
      // ->delete();
      if (count($request['percepcion']) != 0) {
      $arreglo_delete_p = [];
        // code...

      foreach ($request['percepcion'] as $key_p => $value_p) {
      $arreglo_delete_p [] = $value_p['id'];
      }

      $partida_d = \App\PercepcionesViaticoTimbre::
      where('solicitud_viaticos_timbrado_id',$general->id)
      ->whereNotIn('sat_cat_tipopercepcion_id','!=',$arreglo_delete_p)->delete();

      foreach ($request['percepcion'] as $key_p => $value_p) {
        $partida_percepciones_b = \App\PercepcionesViaticoTimbre::
          where('solicitud_viaticos_timbrado_id',$general->id)
        ->where('importeexento',$value_p['importeexento'])
        ->where('sat_cat_tipopercepcion_id',$value_p['id'])->first();

        if (isset($partida_percepciones_b) == false) {
          $partida_per = new \App\PercepcionesViaticoTimbre();
          $partida_per->sat_cat_tipopercepcion_id = $value_p['id'];
          $partida_per->clave = $value_p['clave'];
          $partida_per->concepto = $value_p['concepto'];
          $partida_per->importegravado = $value_p['importegravado'];
          $partida_per->importeexento = $value_p['importeexento'];
          $partida_per->solicitud_viaticos_timbrado_id = $general->id;
          Utilidades::auditar($partida_per, $partida_per->id);
          $partida_per->save();
        }
      }
    }

    if (count($request['percepcion']) != 0) {

      $arreglo_delete_d = [];

      foreach ($request['deduccion'] as $key_d => $value_d) {
      $arreglo_delete_d [] = $value_d['id'];
      }

      $partida_d = \App\DeduccionesViaticoTimbre::
      where('solicitud_viaticos_timbrado_id',$general->id)
      ->whereNotIn('sat_cat_tipodeduccion_id','!=',$arreglo_delete_d)->delete();

      foreach ($request['deduccion'] as $key_d => $value_d) {
        $partida_deducciones_b = \App\DeduccionesViaticoTimbre::
          where('solicitud_viaticos_timbrado_id',$general->id)
          ->where('importe',$value_d['importe'])
        ->where('sat_cat_tipodeduccion_id',$value_d['id'])->first();

        if (isset($partida_deducciones_b) == false) {
          $partida_ded = new \App\DeduccionesViaticoTimbre();
          $partida_ded->sat_cat_tipodeduccion_id = $value_d['id'];
          $partida_ded->clave = $value_d['clave'];
          $partida_ded->concepto = $value_d['concepto'];
          $partida_ded->importe = $value_d['importe'];
          $partida_ded->solicitud_viaticos_timbrado_id = $general->id;
          Utilidades::auditar($partida_ded, $partida_ded->id);
          $partida_ded->save();
        }
      }
    }

      $arreglo_delete = [];

      foreach ($request['otros'] as $key => $value) {
      $arreglo_delete [] = $value['id'];
      }

      $partida_d = \App\OtrosPagosViaticosTimbre::
      where('solicitud_viaticos_timbrado_id',$general->id)
      ->whereNotIn('sat_cat_tipo_otro_pago_id','!=',$arreglo_delete)->delete();

      foreach ($request['otros'] as $key_i => $value_i) {
        $partida_b = \App\OtrosPagosViaticosTimbre::
          where('solicitud_viaticos_timbrado_id',$general->id)
        ->where('sat_cat_tipo_otro_pago_id',$value_i['id'])->first();

        if (isset($partida_b) == false) {
          $partida = new \App\OtrosPagosViaticosTimbre();
          $partida->sat_cat_tipo_otro_pago_id = $value_i['id'];
          $partida->importe = $value_i['importe'];
          $partida->solicitud_viaticos_timbrado_id = $general->id;
          Utilidades::auditar($partida, $partida->id);
          $partida->save();
        }
      }
      DB::commit();

      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      DB::rollBack();
      Utilidades::errors($e);
    }
  }

  public function timbraPrueba($id)
  {
    try {
      $valores = explode('&',$id);
      $solicitud_id = $valores[0];
      $empresa = $valores[1];
      $tp = $valores[2];
      $uuid = '';


      $date = date("mdy");



      // $general_b = \App\SolicitudViaticosTimbrado::where('solicitud_viatico_id',$solicitud_id)
      // ->where('empresa',$empresa)->first();
      // // return response()->json($general_b);
      //
      // if (isset($general_b) == false) {
      //   return response()->json([
      //     'estado' => 1,
      //     'status' => true,
      //     'respuesta' => 'Guarde los datos antes de realizar el timbrado',
      //   ]);
      // }
      if ($empresa == 1) {
        $general_b = \App\SolicitudViaticosTimbrado::where('id',$tp)->first();
        $viaticos = DB::table('solicitud_viaticos')
        ->select('solicitud_viaticos.*')
        ->where('solicitud_viaticos.id','=',$solicitud_id)
        ->first();

        $nombre_arch = $general_b->id.$viaticos->id.$date;//cambiar



        Storage::disk('local')->put('Recibos/CFW/'.$nombre_arch.".ini", $this->Principal($general_b, $empresa));
        $output = shell_exec("cd /home/libfaclinflow/;./sellartimbrarnomina.sh $nombre_arch 2> ehoyc.txt;cat ehoyc.txt;");
        // $output = shell_exec("cd /home/libfaclin/;./sellartimbrarnominacfw.sh $nombre_arch 2> ehoyc.txt;cat ehoyc.txt;");
	       if($output != ''){
	          return response()->json([
        'estado' => 2,
        'status' => true,
        'respuesta' => $output,
        ]);

	       }
         $xml_string = Storage::disk('local')->get('Recibos/CFW/'.$nombre_arch.'tim.xml');

         $xml = simplexml_load_string($xml_string);
         $ns = $xml->getNamespaces(true);
         $xml->registerXPathNamespace('c', $ns['cfdi']);
         $xml->registerXPathNamespace('t', $ns['tfd']);

         foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
           $uuid = $tfd['UUID'];
         }
         $general_b->uuid = $uuid;
         $general_b->timbrado_pd = 1;
	       $general_b->nombre_archivo = $nombre_arch;
         $general_b->save();


      }elseif ($empresa == 2) {
        $general_b = \App\SolicitudViaticosTimbrado::where('id',$tp)->first();
        $viaticos = \App\SolicitudViaticosDBP::where('id',$solicitud_id)->first();
        $nombre_arch = $general_b->id.$viaticos->id.$date;

        Storage::disk('local')->put('Recibos/CSCT/'.$nombre_arch.".ini", $this->Principal($general_b, $empresa));
       $output = shell_exec("cd /home/libfaclinconser/;./sellartimbrarnomina.sh $nombre_arch 2> ehoyc.txt;cat ehoyc.txt;");
         // $output = shell_exec("cd /home/libfaclin/;./sellartimbrarnominacsct.sh $nombre_arch 2> ehoyc.txt;cat ehoyc.txt;");
            if($output != ''){
          return response()->json([
        'estado' => 2,
        'status' => true,
        'respuesta' => $output,
      ]);

        }

        $xml_string = Storage::disk('local')->get('Recibos/CSCT/'.$nombre_arch.'tim.xml');

        $xml = simplexml_load_string($xml_string);
        $ns = $xml->getNamespaces(true);
        $xml->registerXPathNamespace('c', $ns['cfdi']);
        $xml->registerXPathNamespace('t', $ns['tfd']);

        foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
          $uuid = $tfd['UUID'];
        }
        $general_b->uuid = $uuid;
        $general_b->timbrado_pd = 1;
        $general_b->nombre_archivo = $nombre_arch;
        $general_b->save();
      }

      return response()->json([
        'estado' => 2,
        'status' => true,
        'respuesta' => 'Correcto !!!',
      ]);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function Principal($data, $empresa)
  {
    try {
      $emisor = $this->GetEmisor($data->emisor_rfc);
      $variable  =';ini'.PHP_EOL.';encoding=utf8'.PHP_EOL.PHP_EOL.
      '[cfdi:Comprobante]'.PHP_EOL.$this->Comprobante($data).PHP_EOL.
      '[cfdi:Comprobante/cfdi:Emisor]'.PHP_EOL.$this->CEmisor($emisor).PHP_EOL.PHP_EOL.
      '[cfdi:Comprobante/cfdi:Receptor]'.PHP_EOL.$this->CReceptor($data->rfc_r, $data->usocfdi, $empresa).PHP_EOL.PHP_EOL.
      '[cfdi:Comprobante/cfdi:Conceptos]'.PHP_EOL.PHP_EOL.$this->Conceptos($data).PHP_EOL.PHP_EOL.
      '[cfdi:Comprobante/cfdi:Complemento]'.PHP_EOL.$this->Complemento($data, $emisor).PHP_EOL.PHP_EOL
      ;

      return $variable;
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function Comprobante($data)
  {
    try {
      $forma_pago_clave = DB::table('sat_cat_formapago')->where('id',$data->formapago)->first();
      $metodo_pago_clave = DB::table('sat_cat_metodopago')->where('id',$data->metodopago)->first();

      $comprobante = '';
      $comprobante .= 'xmlns:cfdi = http://www.sat.gob.mx/cfd/3'.PHP_EOL.
      'xmlns:xsi = http://www.w3.org/2001/XMLSchema-instance'.PHP_EOL.
      'xmlns:nomina12 = http://www.sat.gob.mx/nomina12'.PHP_EOL.
      'xsi:schemaLocation = http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd http://www.sat.gob.mx/nomina12 http://www.sat.gob.mx/sitio_internet/cfd/nomina/nomina12.xsd'.PHP_EOL.
      'Version = 3.3'.PHP_EOL.
      'Serie = '.$data->serie.PHP_EOL.
      'Folio = '.$data->folio.PHP_EOL.
      'Fecha = '.$data->fecha.':00'.PHP_EOL.//
      'Sello = Zg=='.PHP_EOL.//pendiente por definir por parte de la empresa
      'FormaPago = '.$forma_pago_clave->clave.PHP_EOL.
      'NoCertificado = 01234567890123456789'.PHP_EOL.//pendiente lo debe proporcionar la empresa
      'Certificado = zg=='.PHP_EOL.//pendiente lo debe proporcionar la empresa

      'SubTotal = '.$data->subtotal.PHP_EOL.

      ($data->estado_vista == 1 ? '' :
       $data->estado_vista == 2 ? 'Descuento = '.$data->descuento :
       $data->estado_vista == 3 ? 'Descuento = '.$data->descuento :
       ''
        ).PHP_EOL.
      // ($factura->sctf_c_tipofactura == 'P' ? '' : 'Descuento = '.$suma_descuento).PHP_EOL.
      'Moneda = '.$data->moneda.PHP_EOL.
      'TipoCambio = '.$data->tipo_cambio.PHP_EOL.

      'Total = '.$data->total.PHP_EOL.
      //correcta forma totalTOtsl
      'TipoDeComprobante = N'.PHP_EOL.
      'MetodoPago = '.$metodo_pago_clave->c_metodopago.PHP_EOL.
      'LugarExpedicion = '.$data->lugar_expedicion.PHP_EOL;

      return $comprobante;
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function calcularsubtotal($id, $data, $estado)
  {
    if ($estado == 2 || $estado == 3) {
      $percepciones_totales = DB::table('percepciones_viatico_timbre')
      ->select(DB::raw("SUM(importeexento) AS importeexento"),
      DB::raw("SUM(importegravado) AS importegravado"))
      ->where('solicitud_viaticos_timbrado_id',$id)->first();

      return $percepciones_totales->importeexento;
    }elseif ($estado == 1) {
      return $data;
    }

  }

  public function calculartotal($id, $data, $estado)
  {
    if ($estado == 2 || $estado == 3) {
    $percepciones_totales = DB::table('percepciones_viatico_timbre')
    ->select(DB::raw("SUM(importeexento) AS importeexento"),
    DB::raw("SUM(importegravado) AS importegravado"))
    ->where('solicitud_viaticos_timbrado_id',$id)->first();

    $deducciones_totales = DB::table('deducciones_viatico_timbre')
    ->select(DB::raw("SUM(importe) AS importe"))
    ->where('solicitud_viaticos_timbrado_id',$id)->first();

    $total = $percepciones_totales->importeexento - $deducciones_totales->importe;
    return number_format($total,2);
  }elseif ($estado == 1) {
    return $data;
  }
  }

  public function CEmisor($emisor)
  {
    $emisor_s = '';
    $emisor_s = 'Rfc = '.$emisor['rfc'].PHP_EOL.'Nombre = '.$emisor['nombre'].PHP_EOL.'RegimenFiscal = '.$emisor['regimenfiscal'];//pendiente por asignar por parte de la empresa

    return $emisor_s;
  }

  public function CReceptor($rfc, $uso, $empresa)
  {
    if ($empresa == 1) {
      $empleado = DB::table('empleados AS e')->where('e.rfc','LIKE','%'.$rfc.'%')
      ->select(DB::raw("CONCAT(e.ap_paterno,' ',e.ap_materno,' ',e.nombre) AS empleado"))->first();
    }elseif ($empresa == 2) {
      $empleado = \App\EmpleadoDBP::where('rfc','LIKE','%'.$rfc.'%')
      ->select(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre) AS empleado"))->first();
    }
    $emisor_s = '';
    $emisor_s = 'Rfc = '.$rfc.PHP_EOL.'Nombre = '.$empleado->empleado.PHP_EOL.'UsoCFDI = '.$uso;//pendiente por asignar por parte de la empresa

    return $emisor_s;
  }

  public function GetEmisor($rfc)
  {
    $emisor = [];
    if ($rfc === 'CON1912026U2') {
      $emisor = [
        'rfc' => 'CON1912026U2',
        'nombre' => 'CONSERFLOW SA DE CV',
        'regimenfiscal' => '601',
        'registropatronal' => 'W8910499107',
        'calle' => 'Calle Del Mezquite Lote 5 Mza. 3',
        'colonia' => 'Parque Industrial',
        'municipio' => 'Tehuacán-Miahuatlán',
        'entidad_federativa' => 'Puebla',
        'numero_exterior' => '5',
        'numero_interior' => '-',
        'codigo_postal' => '75820',
      ];
    }elseif ($rfc === 'CSC050609LF7') {
      $emisor = [
        'rfc' => 'CSC050609LF7',
        'nombre' => 'CONSTRUCTORA Y SERVICIOS CALDERON-TORRES SA DE CV',
        'regimenfiscal' => '601',
        'registropatronal' => 'F3437438101',
        'calle' => 'Av. Francisco I. Madero 1000',
        'colonia' => 'Ma. de la Piedad',
        'municipio' => 'Coatzacoalcos',
        'entidad_federativa' => 'Veracruz',
        'numero_exterior' => '1000',
        'numero_interior' => '-',
        'codigo_postal' => '96410',
      ];
    }
    return $emisor;
  }

  public function Conceptos($data)
  {
    try {
      $conceptos = '';

      $clave_sat = DB::table('sat_cat_prodser')->where('id',$data->clave_sat)->first();

      $conceptos .= '[cfdi:Comprobante/cfdi:Conceptos/cfdi:Concepto]'.PHP_EOL.
      'ClaveProdServ = '.$clave_sat->clave.PHP_EOL.
      'Cantidad = '.$data->cantidad.PHP_EOL.
      'ClaveUnidad = '.$data->clave_unidad.PHP_EOL.
      'Descripcion = '.$data->descripcion.PHP_EOL.

      'ValorUnitario = '.$data->valor_unitario.PHP_EOL.
      'Importe ='.$data->importe.PHP_EOL.

      ($data->estado_vista == 1 ? '' :
       $data->estado_vista == 2 ? 'Descuento = '.$data->descuento :
       $data->estado_vista == 3 ? 'Descuento = '.$data->descuento  :
       ''
        ).PHP_EOL;

      return $conceptos;
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function calcularimportes($id)
  {
    $percepciones_totales = DB::table('percepciones_viatico_timbre')
    ->select(DB::raw("SUM(importeexento) AS importeexento"),
    DB::raw("SUM(importegravado) AS importegravado"))
    ->where('solicitud_viaticos_timbrado_id',$id)->first();

    $otros_pagos_totales = DB::table('otros_pagos_viaticos_timbre')
    ->select(DB::raw("SUM(importe) AS importe"))
    ->where('solicitud_viaticos_timbrado_id',$id)->first();

    $total =  $percepciones_totales->importeexento + $otros_pagos_totales->importe;
    return $total;
  }

  public function calcularvu($id)
  {
    $percepciones_totales = DB::table('percepciones_viatico_timbre')
    ->select(DB::raw("SUM(importeexento) AS importeexento"),
    DB::raw("SUM(importegravado) AS importegravado"))
    ->where('solicitud_viaticos_timbrado_id',$id)->first();

    $otros_pagos_totales = DB::table('otros_pagos_viaticos_timbre')
    ->select(DB::raw("SUM(importe) AS importe"))
    ->where('solicitud_viaticos_timbrado_id',$id)->first();

    $total =  $percepciones_totales->importeexento + $otros_pagos_totales->importe;
    return $total;

  }

  public function calculardescuento($id)
  {
    $deducciones_totales = DB::table('deducciones_viatico_timbre')
    ->select(DB::raw("SUM(importe) AS importe"))
    ->where('solicitud_viaticos_timbrado_id',$id)->first();

    return $deducciones_totales->importe;
  }

  public function Complemento($data, $emisor)
  {
    try {
      $complemento = '';

      $complemento .= PHP_EOL.'[cfdi:Comprobante/cfdi:Complemento/nomina12:Nomina]'.PHP_EOL.
      //////
      'Version = 1.2'.PHP_EOL.
      'TipoNomina = E'.PHP_EOL.
      'FechaPago = '.$data->fecha_pago.PHP_EOL.
      'FechaInicialPago = '.$data->fecha_pago_i.PHP_EOL.
      'FechaFinalPago = '.$data->fecha_pago_f.PHP_EOL.
      'NumDiasPagados = '.$data->dias.PHP_EOL.
      'TotalOtrosPagos = '.$data->total.PHP_EOL.PHP_EOL.
      ////Emisor
      PHP_EOL.'[cfdi:Comprobante/cfdi:Complemento/nomina12:Nomina/nomina12:Emisor]'.PHP_EOL.
      'RegistroPatronal = '.$emisor['registropatronal'].PHP_EOL.
      'RfcPatronOrigen = '.$emisor['rfc'].PHP_EOL.
      ///Receptor
      PHP_EOL.'[cfdi:Comprobante/cfdi:Complemento/nomina12:Nomina/nomina12:Receptor]'.PHP_EOL.
      'SalarioDiarioIntegrado = '.$data->sdi.PHP_EOL.
      'SalarioBaseCotApor = '.$data->sbc.PHP_EOL.
      'PeriodicidadPago = '.$data->periodicidadpago.PHP_EOL.
      'RiesgoPuesto = '.$data->riesgopuesto.PHP_EOL.
      'Puesto = '.$data->puesto.PHP_EOL.
      'ClaveEntFed = '.$data->cef.PHP_EOL.
      'Departamento = '.$data->departamento.PHP_EOL.
      'TipoRegimen = '.$data->tiporegimen.PHP_EOL.
      'TipoJornada = '.$data->tipojornada.PHP_EOL.
      'Sindicalizado = '.$data->sindicalizado.PHP_EOL.
      'TipoContrato = '.$data->tipocontrato.PHP_EOL.
      'Antigüedad = '.$data->antiguedad.PHP_EOL.
      'FechaInicioRelLaboral = '.$data->fechainiciorellaboral.PHP_EOL.
      'NumSeguridadSocial = '.$data->nss.PHP_EOL.
      'NumEmpleado = '.$data->numempleado.PHP_EOL.
      'Curp = '.$data->curp.PHP_EOL.
      //PERCEPCIONES SI  APLIACA
      // $data->sat_clave_tipopercepcion == null ? '' : $this->Percepciones($data).PHP_EOL.
      //DEDUCIONES SI APLICA
      // $data->sat_clave_tipodeduccion == null ? '' : $this->Deduccion($data).PHP_EOL.
      //otrosPAgos
      ($data->estado_vista > 1 ? $this->DeduccionPercepcion($data->id) : '').PHP_EOL.
      PHP_EOL.'[cfdi:Comprobante/cfdi:Complemento/nomina12:Nomina/nomina12:OtrosPagos]'.PHP_EOL.PHP_EOL.
      $this->OtrosPagos($data->id).PHP_EOL;

      return $complemento;
      } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function DeduccionPercepcion($id)
  {
    try {
      $percepciones_totales = DB::table('percepciones_viatico_timbre')
      ->select(DB::raw("SUM(importeexento) AS importeexento"),
      DB::raw("SUM(importegravado) AS importegravado"))
      ->where('solicitud_viaticos_timbrado_id',$id)->first();

      $deducciones_totales = DB::table('deducciones_viatico_timbre')
      ->select(DB::raw("SUM(importe) AS importe"))
      ->where('solicitud_viaticos_timbrado_id',$id)->first();

      $percepciones = DB::table('percepciones_viatico_timbre')
      ->join('sat_cat_tipopercepcion AS sctp','sctp.id','percepciones_viatico_timbre.sat_cat_tipopercepcion_id')
      ->select('percepciones_viatico_timbre.*','sctp.c_tipopercepcion')
      ->where('solicitud_viaticos_timbrado_id',$id)->get();

      $deducciones = DB::table('deducciones_viatico_timbre')
      ->join('sat_cat_tipodeduccion AS sctp','sctp.id','deducciones_viatico_timbre.sat_cat_tipodeduccion_id')
      ->select('deducciones_viatico_timbre.*','sctp.c_tipodeduccion')
      ->where('solicitud_viaticos_timbrado_id',$id)->get();

      $TotalSueldos = $percepciones_totales->importeexento + $percepciones_totales->importegravado;
      $TotalOtrasDeducciones = $deducciones_totales->importe;

      $dp = '';
      $dp .= '[cfdi:Comprobante/cfdi:Complemento/nomina12:Nomina/nomina12:Percepciones]'.PHP_EOL.
      'TotalSueldos = '.$TotalSueldos.PHP_EOL.
      'TotalGravado = '.$percepciones_totales->importegravado.PHP_EOL.
      'TotalExento = '.$percepciones_totales->importeexento.PHP_EOL;

      foreach ($percepciones as $key_p => $value_p) {
        $dp .='[cfdi:Comprobante/cfdi:Complemento/nomina12:Nomina/nomina12:Percepciones/nomina12:Percepcion]'.PHP_EOL.
        'TipoPercepcion = '.$value_p->c_tipopercepcion.PHP_EOL.
        'Clave = '.$value_p->clave.PHP_EOL.
        'Concepto = '.$value_p->concepto.PHP_EOL.
        'ImporteGravado = '.$value_p->importegravado.PHP_EOL.
        'ImporteExento = '.$value_p->importeexento.PHP_EOL;
      }

      $dp .= '[cfdi:Comprobante/cfdi:Complemento/nomina12:Nomina/nomina12:Deducciones]'.PHP_EOL.
      'TotalImpuestosRetenidos = 0.00'.PHP_EOL.
      'TotalOtrasDeducciones = '.$TotalOtrasDeducciones.PHP_EOL;

      foreach ($deducciones as $key_d => $value_d) {
        $dp .='[cfdi:Comprobante/cfdi:Complemento/nomina12:Nomina/nomina12:Percepciones/nomina12:Deduccion]'.PHP_EOL.
        'TipoDeduccion = '.$value_d->c_tipodeduccion.PHP_EOL.
        'Clave = '.$value_d->clave.PHP_EOL.
        'Concepto = '.$value_d->concepto.PHP_EOL.
        'Importe = '.$value_d->importe.PHP_EOL;
      }


      return $dp;

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  // public function Percepciones($data)
  // {
  //   try {
  //   $percepciones = '';
  //   $percepciones .=
  //   return $percepciones;
  //   } catch (\Throwable $e) {
  //     Utilidades::errors($e);
  //   }
  // }

  public function Deduccion($data)
  {

  }

  public function OtrosPagos($id)
  {
    try {
      $partida_d = \App\OtrosPagosViaticosTimbre::where('solicitud_viaticos_timbrado_id',$id)->get();
      $otros_pagos = '';

      foreach ($partida_d as $key => $value) {
        $sat_cat_op = DB::table('sat_cat_tipo_otro_pago')->where('id',$value->sat_cat_tipo_otro_pago_id)->first();
        $otros_pagos .= '[cfdi:Comprobante/cfdi:Complemento/nomina12:Nomina/nomina12:OtrosPagos/nomina12:OtroPago]'.PHP_EOL.
        'TipoOtroPago = '.$sat_cat_op->c_tipootropago.PHP_EOL.
        'Clave = '.$sat_cat_op->c_tipootropago.PHP_EOL.
        'Concepto = '.$sat_cat_op->descripcion.PHP_EOL.
	      'Importe = '.$value->importe.PHP_EOL;

        if ($sat_cat_op->c_tipootropago === '002') {
          $otros_pagos .= '[cfdi:Comprobante/cfdi:Complemento/nomina12:Nomina/nomina12:OtrosPagos/nomina12:OtroPago/nomina12:SubsidioAlEmpleo]'.PHP_EOL.
          'SubsidioCausado = 0.00'.PHP_EOL;
        }

      }

      return $otros_pagos;
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function DescargarPre($id)
  {
    try {
      $valores = explode('&',$id);
      $solicitud_id = $valores[0];
      $empresa = $valores[1];
      $tipo = $valores[2];


      $general = \App\SolicitudViaticosTimbrado::
      join('sat_cat_prodser AS scps','scps.id','solicitud_viaticos_timbrado.clave_sat')
      ->select('solicitud_viaticos_timbrado.*','scps.clave','scps.descripcion')
      ->where('solicitud_viaticos_timbrado.id',$tipo)
      ->first();

      $emisor = $this->GetEmisor($general->emisor_rfc);
      $forma_pago_clave = DB::table('sat_cat_formapago')->where('id',$general->formapago)->first();
      $metodo_pago_clave = DB::table('sat_cat_metodopago')->where('id',$general->metodopago)->first();
      // return response()->json($general);
      if ($empresa == 1) {
        $empleado = DB::table('empleados AS e')->where('e.rfc','LIKE','%'.$general->rfc_r.'%')
        ->select(DB::raw("CONCAT(e.ap_paterno,' ',e.ap_materno,' ',e.nombre) AS empleado"))->first();
      }elseif ($empresa == 2) {
        $empleado = \App\EmpleadoDBP::where('rfc','LIKE','%'.$general->rfc_r.'%')
        ->select(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre) AS empleado"))->first();
      }

      $partida_d = \App\OtrosPagosViaticosTimbre::
      join('sat_cat_tipo_otro_pago AS sc','sc.id','otros_pagos_viaticos_timbre.sat_cat_tipo_otro_pago_id')
      ->where('solicitud_viaticos_timbrado_id',$general->id)
      ->select('otros_pagos_viaticos_timbre.*','sc.c_tipootropago','sc.descripcion')
      ->get();

      $total_o_p = DB::table('otros_pagos_viaticos_timbre')->where('solicitud_viaticos_timbrado_id',$general->id)
      ->select(DB::raw("SUM(importe) AS total"))->first();

      $cambio = Utilidades::valorEnLetrasFactura(round($general->total,2) , 1);

      $percepciones_totales = DB::table('percepciones_viatico_timbre')
      ->select(DB::raw("SUM(importeexento) AS importeexento"),
      DB::raw("SUM(importegravado) AS importegravado"))
      ->where('solicitud_viaticos_timbrado_id',$general->id)->first();

      $deducciones_totales = DB::table('deducciones_viatico_timbre')
      ->select(DB::raw("SUM(importe) AS importe"))
      ->where('solicitud_viaticos_timbrado_id',$general->id)->first();

      $percepciones = DB::table('percepciones_viatico_timbre')
      ->join('sat_cat_tipopercepcion AS sctp','sctp.id','percepciones_viatico_timbre.sat_cat_tipopercepcion_id')
      ->select('percepciones_viatico_timbre.*','sctp.c_tipopercepcion','sctp.descripcion')
      ->where('solicitud_viaticos_timbrado_id',$general->id)->get();

      $deducciones = DB::table('deducciones_viatico_timbre')
      ->join('sat_cat_tipodeduccion AS sctp','sctp.id','deducciones_viatico_timbre.sat_cat_tipodeduccion_id')
      ->select('deducciones_viatico_timbre.*','sctp.c_tipodeduccion','sctp.descripcion')
      ->where('solicitud_viaticos_timbrado_id',$general->id)->get();

      $TotalSueldos = $percepciones_totales->importeexento + $percepciones_totales->importegravado;
      $TotalOtrasDeducciones = $deducciones_totales->importe;


      $pdf = PDF::loadView('pdf.precomprobante', compact('general','emisor','empleado',
      'forma_pago_clave','metodo_pago_clave','partida_d','total_o_p','cambio','empresa',
      'percepciones_totales','deducciones_totales','percepciones','deducciones','TotalSueldos','TotalOtrasDeducciones'));
      $pdf->setPaper("letter", "portrait");
      // $pdf->getDomPDF()->set_option("enable_php", true);
      return $pdf->stream();

    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return view('errors.204');
    }
  }

  public function Descargar($id)
  {
    try {
      $valores = explode('&',$id);
      $solicitud_id = $valores[0];
      $empresa = $valores[1];
      $tipo = $valores[2];

      $general = \App\SolicitudViaticosTimbrado::
      join('sat_cat_prodser AS scps','scps.id','solicitud_viaticos_timbrado.clave_sat')
      ->select('solicitud_viaticos_timbrado.*','scps.clave','scps.descripcion')
      ->where('solicitud_viaticos_timbrado.id',$tipo)
      ->first();

      $emisor = $this->GetEmisor($general);
      $forma_pago_clave = DB::table('sat_cat_formapago')->where('id',$general->formapago)->first();
      $metodo_pago_clave = DB::table('sat_cat_metodopago')->where('id',$general->metodopago)->first();
      // return response()->json($general);
      if ($empresa == 1) {
        $empleado = DB::table('empleados AS e')->where('e.rfc','LIKE','%'.$general->rfc_r.'%')
        ->select(DB::raw("CONCAT(e.ap_paterno,' ',e.ap_materno,' ',e.nombre) AS empleado"))->first();
      }elseif ($empresa == 2) {
        $empleado = \App\EmpleadoDBP::where('rfc','LIKE','%'.$general->rfc_r.'%')
        ->select(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre) AS empleado"))->first();
      }

      $partida_d = \App\OtrosPagosViaticosTimbre::
      join('sat_cat_tipo_otro_pago AS sc','sc.id','otros_pagos_viaticos_timbre.sat_cat_tipo_otro_pago_id')
      ->where('solicitud_viaticos_timbrado_id',$general->id)
      ->select('otros_pagos_viaticos_timbre.*','sc.c_tipootropago','sc.descripcion')
      ->get();

      $total_o_p = DB::table('otros_pagos_viaticos_timbre')->where('solicitud_viaticos_timbrado_id',$general->id)
      ->select(DB::raw("SUM(importe) AS total"))->first();

      if ($empresa == 1) {
        $xml_string = Storage::disk('local')->get('Recibos/CFW/'.$general->nombre_archivo.'tim.xml');
      }elseif($empresa == 2){
        $xml_string = Storage::disk('local')->get('Recibos/CSCT/'.$general->nombre_archivo.'tim.xml');
      }
      $cambio = Utilidades::valorEnLetrasFactura(round($total_o_p->total,2) , 1);

      $percepciones_totales = DB::table('percepciones_viatico_timbre')
      ->select(DB::raw("SUM(importeexento) AS importeexento"),
      DB::raw("SUM(importegravado) AS importegravado"))
      ->where('solicitud_viaticos_timbrado_id',$general->id)->first();

      $deducciones_totales = DB::table('deducciones_viatico_timbre')
      ->select(DB::raw("SUM(importe) AS importe"))
      ->where('solicitud_viaticos_timbrado_id',$general->id)->first();

      $percepciones = DB::table('percepciones_viatico_timbre')
      ->join('sat_cat_tipopercepcion AS sctp','sctp.id','percepciones_viatico_timbre.sat_cat_tipopercepcion_id')
      ->select('percepciones_viatico_timbre.*','sctp.c_tipopercepcion','sctp.descripcion')
      ->where('solicitud_viaticos_timbrado_id',$general->id)->get();

      $deducciones = DB::table('deducciones_viatico_timbre')
      ->join('sat_cat_tipodeduccion AS sctp','sctp.id','deducciones_viatico_timbre.sat_cat_tipodeduccion_id')
      ->select('deducciones_viatico_timbre.*','sctp.c_tipodeduccion','sctp.descripcion')
      ->where('solicitud_viaticos_timbrado_id',$general->id)->get();

      $TotalSueldos = $percepciones_totales->importeexento + $percepciones_totales->importegravado;
      $TotalOtrasDeducciones = $deducciones_totales->importe;

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
      $cadenaoriginal = '||1.1|'.$uuid.'|'.$fecha_cer.'|'.$rfc.'|'.$sellocfd.'|'.$numcersat.'||';

      $url = 'https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?id='.$uuid.'&re='.$emisor['rfc'].'&rr='.$general->rfc_r.'&tt='.round($total,2).'&fe='.$rest;
      $qr = \QrCode::size(300)
      ->format('png')
      // ->errorCorrection('Q')
      ->generate($url, public_path('img/'.$general->nombre_archivo.'.png'));

      $mes = array('Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sep.', 'Oct.', 'Nov.', 'Dic.');
      $num_mes = intval(substr($general->fecha,5,2));
      $num_mes_cer = intval(substr($fecha_cer,5,2));
	$img_name = $general->nombre_archivo;
      $fecha_emision =substr($general->fecha,8,2).' '.$mes[$num_mes - 1].' '.substr($general->fecha,0,4).' - '.substr($general->fecha,11);
      $fecha_certificacion =substr($fecha_cer,8,2).' '.$mes[$num_mes_cer - 1].' '.substr($fecha_cer,0,4).' - '.substr($fecha_cer,11);

      $pdf = PDF::loadView('pdf.comprobante', compact('general','emisor','empleado','img_name',
      'forma_pago_clave','metodo_pago_clave','partida_d','total_o_p','cambio','empresa',
      'certificado_emisor','uuid','numcersat','cadenaoriginal','sellosat','sellocfd','fecha_cer','rfc',
      'percepciones_totales','deducciones_totales','percepciones','deducciones','TotalSueldos','TotalOtrasDeducciones',
    'fecha_emision','fecha_certificacion'));
      $pdf->setPaper("letter", "portrait");
      return $pdf->stream();

    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return view('errors.204');
    }
  }

  /**
  * [Descarga del archivo XML generado y almacenado en el servidor configurado]
  * @param  String $id [Nombre del archivo]
  * @return         [objeto de tipo descarga con la url del archivo]
  */
  public function descargarfacturaxml($id)
  {
    $valores = explode('&',$id);
    $nombre_archivo = $valores[0];
    $empresa = $valores[1];

    if ($empresa == 1) {
      // Se obtiene el archivo del FTP serve
      $archivo = Storage::disk('local')->get('Recibos/CFW/'.$nombre_archivo);
      // Se coloca el archivo en una ruta local
      Storage::disk('descarga')->put($nombre_archivo, $archivo);
      //--Devuelve la respuesta y descarga el archivo--//
      return response()->download(storage_path().'/app/descargas/'.$nombre_archivo);
    }elseif ($empresa == 2) {
      // Se obtiene el archivo del FTP serve
      $archivo = Storage::disk('local')->get('Recibos/CSCT/'.$nombre_archivo);
      // Se coloca el archivo en una ruta local
      Storage::disk('descarga')->put($nombre_archivo, $archivo);
      //--Devuelve la respuesta y descarga el archivo--//
      return response()->download(storage_path().'/app/descargas/'.$nombre_archivo);
    }
  }

  /**
  * [Elimina de la ruta local el archivo descargado]
  * @param String $id [Nombre del archivo]
  */
  public function destroyxml($id)
  {
    Storage::disk('descarga')->delete($id);
  }

  public function getOtroPagos($id)
  {
    try {

      $data = DB::table('otros_pagos_viaticos_timbre AS opvt')
      ->join('sat_cat_tipo_otro_pago AS sctop','sctop.id','opvt.sat_cat_tipo_otro_pago_id')
      ->select('opvt.*','sctop.descripcion AS desct')
      ->where('opvt.solicitud_viaticos_timbrado_id',$id)
      ->get();

      return response()->json($data);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function getDeducciones($id)
  {
    try {

      $data = DB::table('deducciones_viatico_timbre AS opvt')
      // ->join('sat_cat_tipodeduccion AS sctop','sctop.id','opvt.sat_cat_tipodeduccion_id')
      ->select('opvt.*')
      ->where('opvt.solicitud_viaticos_timbrado_id',$id)
      ->get();

      return response()->json($data);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function getPercepciones($id)
  {
    try {

      $data = DB::table('percepciones_viatico_timbre AS opvt')
      // ->join('sat_cat_tipopercepcion AS sctop','sctop.id','opvt.sat_cat_tipopercepcion_id')
      ->select('opvt.*')
      ->where('opvt.solicitud_viaticos_timbrado_id',$id)
      ->get();

      return response()->json($data);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }
}
