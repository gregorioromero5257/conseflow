<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\Storage;
use \App\Http\Helpers\Utilidades;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class ReporteGastosViaticosController extends Controller
{

  /**
  * [protected Reglas para el guardado y actualizacion en la BD]
  * @var [type]
  */
  protected $rules = array(
    // 'folio' => 'required|max:30',
    'fecha' => 'required',
    'proveedor_acreedor' => 'required|max:100',
    'concepto' => 'required|max:200',
    'catalogo_conceptos_viaticos_id' => 'required',

    // 'referencia' => 'required|max:30',
  );

  /**
  * [show Consulta en el modelo ReporteGastosViaticos todos los registros en el]
  * @param  Int $id [description]
  * @return Response           [description]
  */
  public function show($id)
  {
    $valores = explode('&',$id);
    if ($valores[1] == 1) {
      $reporte = \App\ReporteGastosViaticos::
      leftjoin("empleados AS EB","EB.id","=","reporte_gastos_viaticos.beneficiario_id")
      ->join('solicitud_viaticos AS sv','sv.id','reporte_gastos_viaticos.solicitud_viaticos_id')
      ->join('beneficiario_viatico AS bv','bv.solicitud_viaticos_id','sv.id')
      ->select('reporte_gastos_viaticos.*',DB::raw("CONCAT(EB.nombre,' ',EB.ap_paterno,' ',EB.ap_materno) AS beneficiario"),'bv.beneficiario_externo')
      ->where('reporte_gastos_viaticos.solicitud_viaticos_id','=',$valores[0])
      ->get();
    }elseif ($valores[1] == 2) {
      $reporte = \App\ReporteGastosViaticosDBP::
      leftjoin("empleados AS EB","EB.id","=","reporte_gastos_viaticos.beneficiario_id")
      ->join('solicitud_viaticos AS sv','sv.id','reporte_gastos_viaticos.solicitud_viaticos_id')
      ->join('beneficiario_viatico AS bv','bv.solicitud_viaticos_id','sv.id')
      ->select('reporte_gastos_viaticos.*',DB::raw("CONCAT(EB.nombre,' ',EB.ap_paterno,' ',EB.ap_materno) AS beneficiario"),'bv.beneficiario_externo')
      ->where('reporte_gastos_viaticos.solicitud_viaticos_id','=',$valores[0])
      ->get();
    }

    // dd($valores);
    return response()->json($reporte);
  }

  public function reportepagosenviados($id)
  {
    $valores = explode('&',$id);
    /////CONSERFLOW
    if ($valores [1] == 1) {
      // code...
      $reporte = \App\ReporteGastosViaticos::where('reporte_gastos_viaticos.solicitud_viaticos_id','=',$valores[0])
      ->leftJoin('catalogo_conceptos_viaticos AS CCV','CCV.id','=','reporte_gastos_viaticos.catalogo_conceptos_viaticos_id')
      ->join("empleados AS EB","EB.id","=","reporte_gastos_viaticos.beneficiario_id")
      ->leftJoin('viaticos AS V', function($join)
      {
        $join->on('reporte_gastos_viaticos.solicitud_viaticos_id', '=', 'V.solicitud_viaticos_id');
        $join->on('reporte_gastos_viaticos.pda', '=', 'V.pda');


      })
      ->select('reporte_gastos_viaticos.*','CCV.nombre AS ccv_nombre','V.gastos_comprobados_deducibles AS gcd','V.gastos_comprobados_no_deducibles AS gcnd',DB::raw("CONCAT(EB.nombre,' ',EB.ap_paterno,' ',EB.ap_materno) AS beneficiario"))
      ->where('reporte_gastos_viaticos.condicion','>','0')->get();
      return response()->json($reporte);
    }
    ///CSCT
    elseif ($valores [1] == 2) {
      // code...
      $reporte = \App\ReporteGastosViaticosDBP::where('reporte_gastos_viaticos.solicitud_viaticos_id','=',$valores[0])
      ->leftJoin('catalogo_conceptos_viaticos AS CCV','CCV.id','=','reporte_gastos_viaticos.catalogo_conceptos_viaticos_id')
      ->join("empleados AS EB","EB.id","=","reporte_gastos_viaticos.beneficiario_id")
      ->leftJoin('viaticos AS V', function($join)
      {
        $join->on('reporte_gastos_viaticos.solicitud_viaticos_id', '=', 'V.solicitud_viaticos_id');
        $join->on('reporte_gastos_viaticos.pda', '=', 'V.pda');


      })
      ->select('reporte_gastos_viaticos.*','CCV.nombre AS ccv_nombre','V.gastos_comprobados_deducibles AS gcd','V.gastos_comprobados_no_deducibles AS gcnd',DB::raw("CONCAT(EB.nombre,' ',EB.ap_paterno,' ',EB.ap_materno) AS beneficiario"))
      ->where('reporte_gastos_viaticos.condicion','>','0')->get();
      return response()->json($reporte);
    }

  }

  /**
  * [store registra en la base de datos los datos contenidos en el Request]
  * @param  Request $request [description]
  * @return Response           [description]
  */
  public function store(Request $request)
  {
    try {

      $user = Auth::user();

      if ($request->empresa == 1) {////CONSERFLOW
        //CONDICIONES
        // 1.- SE DEBE DETERMINAR SI TIENE XML DE NO SER ASI ES UNA COMPRACION DE GASTO QUE REQUIERE LOS CAMPOS COMPLETOS
        // 2.- AL SUBIR UN XML SE DEBE BUSCAR SI EXISTE UN UUID CON EL QUE SE INTENTA SUBIR DE SER ASI MANDAR UN ESTATUS TRUE, CON ESTADO 0
        // 3.- SI ES UN UUID NUEVO SE REGISTRA Y SE ENVIA UN ESTADO 1 CON ESTATUS TRUE

        // DB::beginTransaction();

        //TIENE XML
        // return response()->json($request->check);
        if ($request->check === 'true') {
          libxml_use_internal_errors(true);
          $xml = simplexml_load_file($request->file('xml'));
          $ns = $xml->getNamespaces(true);
          $xml->registerXPathNamespace('c', $ns['cfdi']);
          $xml->registerXPathNamespace('t', $ns['tfd']);

          foreach ($xml->xpath('//cfdi:Comprobante') as $comprobante) {
            $subtotal = (array) $comprobante['SubTotal'];
            $total = (array) $comprobante['Total'];
          }

          foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
            $uuid = $tfd['UUID'];
          }

          $importecero = 0;
          $importediecisies = 0;
          $iva = 0;
          $otros_impuestos = 0;


          // return response()->json($uuid);
          $buscar_uuid = \App\ReporteGastosViaticos::where('foliosat',$uuid)->first();
          if (isset($buscar_uuid) == true) {
            return response()->json(['status' => true, 'estado' => '0']);
          }

          $traslados  = $xml->xpath('//cfdi:Comprobante//cfdi:Impuestos');
          // return  response()->json($traslados);
          $traslado = end($traslados);
          if ($traslado) {
            $otros_impuestos = $traslado['Importe'];
          }

          $retenciones  = $xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Retenciones//cfdi:Retencion');
          $retencion = end($retenciones);
          //DETERMINAR SI EL IMPUESTO ES BASE CERO
          if ($retencion) {
            if ((float)$retencion['Importe'] == 0) {
              $importecero = $subtotal[0];
            }else {
              $importediecisies = $subtotal[0];
              $iva = (float)$retencion['Importe'];
            }
          }

          foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $emisor) {
            $nombre_e = $emisor['Nombre'];
          }


          $catalogo_concepto_viatico = DB::table('catalogo_conceptos_viaticos')->where('id',$request->catalogo_conceptos_viaticos_id)->first();
          $beneficiario = DB::table('beneficiario_viatico')->where('solicitud_viaticos_id',$request->solicitud_viaticos_id)->first();

          /***************************************************************************/
          if ($request->metodo == 'Nuevo') {
            $reporte = new \App\ReporteGastosViaticos();
            $reporte->solicitud_viaticos_id = $request->solicitud_viaticos_id;
            $reporte->fecha = $request->fecha;
            $reporte->foliosat = $uuid[0];
            // $reporte->facturainterna = $request->facturainterna;
            $reporte->proveedor_acreedor = $nombre_e;
            $reporte->concepto =  $catalogo_concepto_viatico->nombre;
            $reporte->importediecisies = $importediecisies;
            $reporte->importecero = $importecero;
            $reporte->iva = $iva ;
            // $reporte->derechos = $request->derechos;
            $reporte->otros_impuestos = $otros_impuestos;
            $reporte->no_deducible = $request->no_deducible;
            $reporte->total = $total[0];
            $reporte->catalogo_conceptos_viaticos_id = $catalogo_concepto_viatico->id;
            $reporte->beneficiario_id = $beneficiario->empleado_beneficiario_id;
            // $reporte->folio_peaje = $request->folio_peaje;
            if ($request->hasFile('adjunto')) {
              $nombre_archivo = $this->obtenerNombre($request);
              $reporte->adjunto = $nombre_archivo;
              $this->subirAdjunto($request, $nombre_archivo);
            }
            if ($request->hasFile('xml')) {
              $nombre_archivo_xml = $this->obtenerNombreX($request);
              $reporte->xml = $nombre_archivo_xml;
              $this->subirAdjunto($request, $nombre_archivo_xml);
            }
            $reporte->pda = $this->pdaReporteViaticos($request->solicitud_viaticos_id,$request->empresa);
            $reporte->empleado_user_id = $user->empleado_id;
            $reporte->hora_dia_subida = date("Y-m-d H:i:s");
            $reporte->save();

            if ($request->hasFile('adjunto')) {
              $facturas = new \App\FacturasViaticos();
              $facturas->solicitud_viaticos_id = $request->solicitud_viaticos_id;
              $facturas->pda = $reporte->pda;
              $facturas->adjunto = $reporte->adjunto;
              $facturas->save();
            }


          }
          if ($request->metodo == 'Actualizar') {
            $nombre_archivo_actualizar = '';
            return response()->json($request);
            if ($request->hasFile('adjunto')) {


              $nombre_archivo_actualizar = $this->obtenerNombre($request);
              // Storage::disk('local')->delete($nombre_archivo_actualizar);
              $this->subirAdjunto($request, $nombre_archivo_actualizar);

              $reporte = DB::table('facturas_viaticos')->select('facturas_viaticos.*')
              ->where('solicitud_viaticos_id','=',$request->solicitud_viaticos_id)
              ->where('pda','=',$request->pda)->delete();

              $facturas = new \App\FacturasViaticos();
              $facturas->solicitud_viaticos_id = $request->solicitud_viaticos_id;
              $facturas->pda = $request->pda;
              $facturas->adjunto = $nombre_archivo_actualizar;
              $facturas->save();

              $reporte = \App\ReporteGastosViaticos::select('reporte_gastos_viaticos.*')
              ->where('solicitud_viaticos_id','=',$request->solicitud_viaticos_id)
              ->where('pda','=',$request->pda)->update([
                'fecha' => $request->fecha,
                'foliosat' => $request->foliosat,
                'facturainterna' => $request->facturainterna,
                'proveedor_acreedor' => $request->proveedor_acreedor,
                'concepto' => $request->concepto,
                'importediecisies' => $request->importediecisies,
                'importecero' => $request->importecero,
                'iva' => $request->iva,
                'derechos' => $request->derechos,
                'otros_impuestos' => $request->otros_impuestos,
                'no_deducible' => $request->no_deducible,
                'total' => $request->total,
                'catalogo_conceptos_viaticos_id' => $request->catalogo_conceptos_viaticos_id,
                'beneficiario_id' => $request->beneficiario_id,
                'folio_peaje' => $request->folio_peaje,
                'adjunto' => $nombre_archivo_actualizar,
                'empleado_user_id' => $user->empleado_id,
                'hora_dia_subida' => date("Y-m-d H:i:s"),
              ]);
            }
            // $reporte = new \App\ReporteGastosViaticos();
            $reporte = \App\ReporteGastosViaticos::select('reporte_gastos_viaticos.*')
            ->where('solicitud_viaticos_id','=',$request->solicitud_viaticos_id)
            ->where('pda','=',$request->pda)->update([
              'fecha' => $request->fecha,
              'foliosat' => $request->foliosat,
              'facturainterna' => $request->facturainterna,
              'proveedor_acreedor' => $request->proveedor_acreedor,
              'concepto' => $request->concepto,
              'importediecisies' => $request->importediecisies,
              'importecero' => $request->importecero,
              'iva' => $request->iva,
              'derechos' => $request->derechos,
              'otros_impuestos' => $request->otros_impuestos,
              'no_deducible' => $request->no_deducible,
              'total' => $request->total,
              'catalogo_conceptos_viaticos_id' => $request->catalogo_conceptos_viaticos_id,
              'beneficiario_id' => $request->beneficiario_id,
              'folio_peaje' => $request->folio_peaje,
              'empleado_user_id' => $user->empleado_id,
              'hora_dia_subida' => date("Y-m-d H:i:s"),
            ]);


            return response()->json(array('status' => true));

          }





        }elseif ($request->check === 'false') {//NO TIENE XML

          if ($request->metodo == 'Nuevo') {
          $beneficiario = DB::table('beneficiario_viatico')->where('solicitud_viaticos_id',$request->solicitud_viaticos_id)->first();

          $reporte = new \App\ReporteGastosViaticos();
          $reporte->solicitud_viaticos_id = $request->solicitud_viaticos_id;
          $reporte->fecha = $request->fecha;
          $reporte->foliosat = $request->foliosat;
          $reporte->facturainterna = $request->facturainterna;
          $reporte->proveedor_acreedor = $request->proveedor_acreedor;
          $reporte->concepto = $request->concepto;
          // $reporte->importediecisies = $request->importediecisies;
          // $reporte->importecero = $  request->importecero;
          $reporte->iva = $request->iva;
          // $reporte->derechos = $request->derechos;
          // $reporte->otros_impuestos = $request->otros_impuestos;
          $reporte->no_deducible = $request->no_deducible;
          $reporte->total = $request->total;
          $reporte->catalogo_conceptos_viaticos_id = $request->catalogo_conceptos_viaticos_id;
          $reporte->beneficiario_id = $beneficiario->empleado_beneficiario_id;
          $reporte->folio_peaje = $request->folio_peaje;
          if ($request->hasFile('adjunto')) {
            $nombre_archivo = $this->obtenerNombre($request);
            $reporte->adjunto = $nombre_archivo;
            $this->subirAdjunto($request, $nombre_archivo);
          }
          $reporte->pda = $this->pdaReporteViaticos($request->solicitud_viaticos_id,$request->empresa);
          $reporte->empleado_user_id = $user->empleado_id;
          $reporte->hora_dia_subida = date("Y-m-d H:i:s");
          $reporte->save();

          if ($request->hasFile('adjunto')) {
            $facturas = new \App\FacturasViaticos();
            $facturas->solicitud_viaticos_id = $request->solicitud_viaticos_id;
            $facturas->pda = $reporte->pda;
            $facturas->adjunto = $reporte->adjunto;
            $facturas->save();
          }
        }
        }

        DB::commit();
        return response()->json(['status' => true, 'estado' => '1']);
      }elseif ($request->empresa == 2) {//////CSCT
        //CONDICIONES
        // 1.- SE DEBE DETERMINAR SI TIENE XML DE NO SER ASI ES UNA COMPRACION DE GASTO QUE REQUIERE LOS CAMPOS COMPLETOS
        // 2.- AL SUBIR UN XML SE DEBE BUSCAR SI EXISTE UN UUID CON EL QUE SE INTENTA SUBIR DE SER ASI MANDAR UN ESTATUS TRUE, CON ESTADO 0
        // 3.- SI ES UN UUID NUEVO SE REGISTRA Y SE ENVIA UN ESTADO 1 CON ESTATUS TRUE

        // DB::beginTransaction();

        //TIENE XML
        // return response()->json($request->check);
        if ($request->check === 'true') {
          libxml_use_internal_errors(true);
          $xml = simplexml_load_file($request->file('xml'));
          $ns = $xml->getNamespaces(true);
          $xml->registerXPathNamespace('c', $ns['cfdi']);
          $xml->registerXPathNamespace('t', $ns['tfd']);

          foreach ($xml->xpath('//cfdi:Comprobante') as $comprobante) {
            $subtotal = (array) $comprobante['SubTotal'];
            $total = (array) $comprobante['Total'];
          }

          foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
            $uuid = $tfd['UUID'];
          }

          $importecero = 0;
          $importediecisies = 0;
          $iva = 0;
          $otros_impuestos = 0;


          // return response()->json($uuid);
          $buscar_uuid = \App\ReporteGastosViaticosDBP::where('foliosat',$uuid)->first();
          if (isset($buscar_uuid) == true) {
            return response()->json(['status' => true, 'estado' => '0']);
          }

          $traslados  = $xml->xpath('//cfdi:Comprobante//cfdi:Impuestos');
          // return  response()->json($traslados);
          $traslado = end($traslados);
          if ($traslado) {
            $otros_impuestos = $traslado['Importe'];
          }

          $retenciones  = $xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Retenciones//cfdi:Retencion');
          $retencion = end($retenciones);
          //DETERMINAR SI EL IMPUESTO ES BASE CERO
          if ($retencion) {
            if ((float)$retencion['Importe'] == 0) {
              $importecero = $subtotal[0];
            }else {
              $importediecisies = $subtotal[0];
              $iva = (float)$retencion['Importe'];
            }
          }

          foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $emisor) {
            $nombre_e = $emisor['Nombre'];
          }


          $catalogo_concepto_viatico = \App\CatalogoConceptosViaticosDBP::where('id',$request->catalogo_conceptos_viaticos_id)->first();

          $beneficiario = \App\BeneficiarioViaticoDBP::where('solicitud_viaticos_id',$request->solicitud_viaticos_id)->first();

          /***************************************************************************/
          if ($request->metodo == 'Nuevo') {
            $reporte = new \App\ReporteGastosViaticosDBP();
            $reporte->solicitud_viaticos_id = $request->solicitud_viaticos_id;
            $reporte->fecha = $request->fecha;
            $reporte->foliosat = $uuid[0];
            // $reporte->facturainterna = $request->facturainterna;
            $reporte->proveedor_acreedor = $nombre_e;
            $reporte->concepto =  $catalogo_concepto_viatico->nombre;
            $reporte->importediecisies = $importediecisies;
            $reporte->importecero = $importecero;
            $reporte->iva = $iva ;
            // $reporte->derechos = $request->derechos;
            $reporte->otros_impuestos = $otros_impuestos;
            $reporte->no_deducible = $request->no_deducible;
            $reporte->total = $total[0];
            $reporte->catalogo_conceptos_viaticos_id = $catalogo_concepto_viatico->id;
            $reporte->beneficiario_id = $beneficiario->empleado_beneficiario_id;
            // $reporte->folio_peaje = $request->folio_peaje;
            if ($request->hasFile('adjunto')) {
              $nombre_archivo = $this->obtenerNombre($request);
              $reporte->adjunto = $nombre_archivo;
              $this->subirAdjunto($request, $nombre_archivo);
            }
            if ($request->hasFile('xml')) {
              $nombre_archivo_xml = $this->obtenerNombreX($request);
              $reporte->xml = $nombre_archivo_xml;
              $this->subirAdjunto($request, $nombre_archivo_xml);
            }
            $reporte->pda = $this->pdaReporteViaticos($request->solicitud_viaticos_id,$request->empresa);
            $reporte->empleado_user_id = $user->empleado_id;
            $reporte->hora_dia_subida = date("Y-m-d H:i:s");
            $reporte->save();

            if ($request->hasFile('adjunto')) {
              $facturas = new \App\FacturasViaticosDBP();
              $facturas->solicitud_viaticos_id = $request->solicitud_viaticos_id;
              $facturas->pda = $reporte->pda;
              $facturas->adjunto = $reporte->adjunto;
              $facturas->save();
            }


          }
          if ($request->metodo == 'Actualizar') {
            $nombre_archivo_actualizar = '';
            return response()->json($request);
            if ($request->hasFile('adjunto')) {


              $nombre_archivo_actualizar = $this->obtenerNombre($request);
              // Storage::disk('local')->delete($nombre_archivo_actualizar);
              $this->subirAdjunto($request, $nombre_archivo_actualizar);

              $reporte = \App\FacturasViaticosDBP::select('facturas_viaticos.*')
              ->where('solicitud_viaticos_id','=',$request->solicitud_viaticos_id)
              ->where('pda','=',$request->pda)->delete();

              $facturas = new \App\FacturasViaticosDBP();
              $facturas->solicitud_viaticos_id = $request->solicitud_viaticos_id;
              $facturas->pda = $request->pda;
              $facturas->adjunto = $nombre_archivo_actualizar;
              $facturas->save();

              $reporte = \App\ReporteGastosViaticosDBP::select('reporte_gastos_viaticos.*')
              ->where('solicitud_viaticos_id','=',$request->solicitud_viaticos_id)
              ->where('pda','=',$request->pda)->update([
                'fecha' => $request->fecha,
                'foliosat' => $request->foliosat,
                'facturainterna' => $request->facturainterna,
                'proveedor_acreedor' => $request->proveedor_acreedor,
                'concepto' => $request->concepto,
                'importediecisies' => $request->importediecisies,
                'importecero' => $request->importecero,
                'iva' => $request->iva,
                'derechos' => $request->derechos,
                'otros_impuestos' => $request->otros_impuestos,
                'no_deducible' => $request->no_deducible,
                'total' => $request->total,
                'catalogo_conceptos_viaticos_id' => $request->catalogo_conceptos_viaticos_id,
                'beneficiario_id' => $request->beneficiario_id,
                'folio_peaje' => $request->folio_peaje,
                'adjunto' => $nombre_archivo_actualizar,
                'empleado_user_id' => $user->empleado_id,
                'hora_dia_subida' => date("Y-m-d H:i:s"),
              ]);
            }
            // $reporte = new \App\ReporteGastosViaticos();
            $reporte = \App\ReporteGastosViaticosDBP::select('reporte_gastos_viaticos.*')
            ->where('solicitud_viaticos_id','=',$request->solicitud_viaticos_id)
            ->where('pda','=',$request->pda)->update([
              'fecha' => $request->fecha,
              'foliosat' => $request->foliosat,
              'facturainterna' => $request->facturainterna,
              'proveedor_acreedor' => $request->proveedor_acreedor,
              'concepto' => $request->concepto,
              'importediecisies' => $request->importediecisies,
              'importecero' => $request->importecero,
              'iva' => $request->iva,
              'derechos' => $request->derechos,
              'otros_impuestos' => $request->otros_impuestos,
              'no_deducible' => $request->no_deducible,
              'total' => $request->total,
              'catalogo_conceptos_viaticos_id' => $request->catalogo_conceptos_viaticos_id,
              'beneficiario_id' => $request->beneficiario_id,
              'folio_peaje' => $request->folio_peaje,
              'empleado_user_id' => $user->empleado_id,
              'hora_dia_subida' => date("Y-m-d H:i:s"),
            ]);

            return response()->json(array('status' => true));

          }





        }elseif ($request->check === 'false') {//NO TIENE XML

          if ($request->metodo == 'Nuevo') {
          $beneficiario = \App\BeneficiarioViaticoDBP::where('solicitud_viaticos_id',$request->solicitud_viaticos_id)->first();

          $reporte = new \App\ReporteGastosViaticosDBP();
          $reporte->solicitud_viaticos_id = $request->solicitud_viaticos_id;
          $reporte->fecha = $request->fecha;
          $reporte->foliosat = $request->foliosat;
          $reporte->facturainterna = $request->facturainterna;
          $reporte->proveedor_acreedor = $request->proveedor_acreedor;
          $reporte->concepto = $request->concepto;
          // $reporte->importediecisies = $request->importediecisies;
          // $reporte->importecero = $  request->importecero;
          $reporte->iva = $request->iva;
          // $reporte->derechos = $request->derechos;
          // $reporte->otros_impuestos = $request->otros_impuestos;
          $reporte->no_deducible = $request->no_deducible;
          $reporte->total = $request->total;
          $reporte->catalogo_conceptos_viaticos_id = $request->catalogo_conceptos_viaticos_id;
          $reporte->beneficiario_id = $beneficiario->empleado_beneficiario_id;
          $reporte->folio_peaje = $request->folio_peaje;
          if ($request->hasFile('adjunto')) {
            $nombre_archivo = $this->obtenerNombre($request);
            $reporte->adjunto = $nombre_archivo;
            $this->subirAdjunto($request, $nombre_archivo);
          }
          $reporte->pda = $this->pdaReporteViaticos($request->solicitud_viaticos_id,$request->empresa);
          $reporte->empleado_user_id = $user->empleado_id;
          $reporte->hora_dia_subida = date("Y-m-d H:i:s");
          $reporte->save();

          if ($request->hasFile('adjunto')) {
            $facturas = new \App\FacturasViaticosDBP();
            $facturas->solicitud_viaticos_id = $request->solicitud_viaticos_id;
            $facturas->pda = $reporte->pda;
            $facturas->adjunto = $reporte->adjunto;
            $facturas->save();
          }
        }
        }

        DB::commit();
        return response()->json(['status' => true, 'estado' => '1']);
      }


    } catch (\Throwable $e) {
      DB::rollBack();
      Utilidades::errors($e);
    }


  }

  public function obtenerNombre($request)
  {
    //obtiene el nombre del archivo y su extension
    $FacturaNE = $request->file('adjunto')->getClientOriginalName();
    //Obtiene el nombre del archivo
    $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
    //obtiene la extension
    $FacturaExt = $request->file('adjunto')->getClientOriginalExtension();
    //nombre que se guarad en BD
    $FacturaStore = 'ReporteGasto_'.uniqid().'.'.$FacturaExt;

    return $FacturaStore;
  }

  public function obtenerNombreX($request)
  {
    //obtiene el nombre del archivo y su extension
    $FacturaNE = $request->file('xml')->getClientOriginalName();
    //Obtiene el nombre del archivo
    $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
    //obtiene la extension
    $FacturaExt = $request->file('xml')->getClientOriginalExtension();
    //nombre que se guarad en BD
    $FacturaStore = 'ReporteGastoXml_'.uniqid().'.'.$FacturaExt;

    return $FacturaStore;
  }

  public function pdaReporteViaticos($id,$empresa)
  {
    $pda = 0;
    if ($empresa == 1) {
      $reporte = \App\ReporteGastosViaticos::select(DB::raw("COUNT(reporte_gastos_viaticos.solicitud_viaticos_id) AS tamanio"))
      ->where('solicitud_viaticos_id','=',$id)->first();
      $pda = $reporte == null ? 1 : ($reporte->tamanio + 1);
      return $pda;
    }elseif ($empresa == 2) {
      $reporte = \App\ReporteGastosViaticosDBP::select(DB::raw("COUNT(reporte_gastos_viaticos.solicitud_viaticos_id) AS tamanio"))
      ->where('solicitud_viaticos_id','=',$id)->first();
      $pda = $reporte == null ? 1 : ($reporte->tamanio + 1);
      return $pda;
    }
  }

  public function subirAdjunto($request, $nombre_archivo)
  {
    Storage::disk('local')->put('Archivos/'.$nombre_archivo, fopen($request->file('adjunto'), 'r+'));
    return true;
  }

  public function descargarfactura($id)
  {
    // Se obtiene el archivo del FTP serve
    $archivo = Utilidades::ftpSolucion($id);
    // $archivo = Storage::disk('local')->get('ViaticosReporteGastos/'.$id);
    // Se coloca el archivo en una ruta local
    Storage::disk('descarga')->put($id, $archivo);
    //--Devuelve la respuesta y descarga el archivo--//
    return response()->download(storage_path().'/app/descargas/'.$id);
  }

  public function eliminararch($id)
  {
    Storage::disk('descarga')->delete($id);
    Storage::disk('local')->delete($id);

  }

  public function eliminar($id)
  {
    $valores = explode('&',$id);
    if ($valores[2] == 1) {
      $reporte = \App\ReporteGastosViaticos::
      where('solicitud_viaticos_id','=',$valores[0])
      ->where('pda','=',$valores[1])->delete();
    }elseif ($valores[2] == 2) {
      $reporte = \App\ReporteGastosViaticosDBP::
      where('solicitud_viaticos_id','=',$valores[0])
      ->where('pda','=',$valores[1])->delete();
    }
    return response()->json(array('status' => true));
  }

  public function enviarReporteParcial($id)
  {
    $valores = explode('&',$id);
    /////conserflow
    if ($valores[1] == 1) {
      // code...
      $numero = 0;
      $reporte_num_reporte = \App\ReporteGastosViaticos::select('reporte_gastos_viaticos.num_reporte')
      ->where('solicitud_viaticos_id','=',$id)->where('condicion','>','0')->distinct()->get();
      if(count($reporte_num_reporte) == 0){
        $numero = 1;
        $reporte = \App\ReporteGastosViaticos::select('reporte_gastos_viaticos.*')->where('solicitud_viaticos_id','=',$id)->where('condicion','=','0')->get();
        for ($i=0; $i <count($reporte) ; $i++) {
          $reporte_parcial = \App\ReporteGastosViaticos::select('reporte_gastos_viaticos.*')
          ->where('solicitud_viaticos_id','=',$reporte[$i]->solicitud_viaticos_id)
          ->where('pda','=',$reporte[$i]->pda)->update([
            'condicion' => 1,
            'num_reporte' => $numero,
          ]);
        }
      }
      else {
        $numero_n = end($reporte_num_reporte);
        $numero = $numero_n[0]->num_reporte + 1;

        $reporte = \App\ReporteGastosViaticos::select('reporte_gastos_viaticos.*')->where('solicitud_viaticos_id','=',$id)->where('condicion','=','0')->get();
        for ($i=0; $i <count($reporte) ; $i++) {
          $reporte_parcial = \App\ReporteGastosViaticos::select('reporte_gastos_viaticos.*')
          ->where('solicitud_viaticos_id','=',$reporte[$i]->solicitud_viaticos_id)
          ->where('pda','=',$reporte[$i]->pda)->update([
            'condicion' => 1,
            'num_reporte' => $numero,
          ]);
        }
      }



      return response()->json(array('status' => true));
    }
    ///CSCT
    elseif ($valores[1] == 2) {
      // code...
      $numero = 0;
      $reporte_num_reporte = \App\ReporteGastosViaticosDBP::select('reporte_gastos_viaticos.num_reporte')
      ->where('solicitud_viaticos_id','=',$id)->where('condicion','>','0')->distinct()->get();
      if(count($reporte_num_reporte) == 0){
        $numero = 1;
        $reporte = \App\ReporteGastosViaticosDBP::select('reporte_gastos_viaticos.*')->where('solicitud_viaticos_id','=',$id)->where('condicion','=','0')->get();
        for ($i=0; $i <count($reporte) ; $i++) {
          $reporte_parcial = \App\ReporteGastosViaticosDBP::select('reporte_gastos_viaticos.*')
          ->where('solicitud_viaticos_id','=',$reporte[$i]->solicitud_viaticos_id)
          ->where('pda','=',$reporte[$i]->pda)->update([
            'condicion' => 1,
            'num_reporte' => $numero,
          ]);
        }
      }
      else {
        $numero_n = end($reporte_num_reporte);
        $numero = $numero_n[0]->num_reporte + 1;

        $reporte = \App\ReporteGastosViaticosDBP::select('reporte_gastos_viaticos.*')->where('solicitud_viaticos_id','=',$id)->where('condicion','=','0')->get();
        for ($i=0; $i <count($reporte) ; $i++) {
          $reporte_parcial = \App\ReporteGastosViaticosDBP::select('reporte_gastos_viaticos.*')
          ->where('solicitud_viaticos_id','=',$reporte[$i]->solicitud_viaticos_id)
          ->where('pda','=',$reporte[$i]->pda)->update([
            'condicion' => 1,
            'num_reporte' => $numero,
          ]);
        }
      }



      return response()->json(array('status' => true));
    }

  }

  public function viaticoProyecto($id)
  {
    $total = 0;
    $proyectos = DB::table('proyectos')->where('id',$id)->first();
    $proyecto_db = \App\ProyectoDBP::where('nombre_corto','LIKE','%'.$proyectos->nombre_corto.'%')->first();

    if (isset($proyecto_db) == true) {
      $total_csct = \App\DetalleViaticoCSCT::
      join('solicitud_viaticos AS sv','sv.id','detalle_viaticos.solicitud_viaticos_id')
      ->select(DB::raw("SUM(total) AS total"))
      ->where('sv.proyecto_id',$proyecto_db->id)
      ->first();
    }

    $total_conserflow = \App\DetalleViatico::
    join('solicitud_viaticos AS sv','sv.id','detalle_viaticos.solicitud_viaticos_id')
    ->select(DB::raw("SUM(total) AS total"))
    ->where('sv.proyecto_id',$id)
    ->first();

    $total = (isset($total_csct) == true ? $total_csct->total : 0) + (isset($total_conserflow) == true ? $total_conserflow->total : 0);

    return response()->json($total);
  }


}
