<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Viaticos;
use Mail;
use \App\Http\Helpers\Utilidades;

class PartidasViaticosPagosController extends Controller
{

  protected $viatico;

  public function __construct(Viaticos $viatico)
  {
    $this->viaticos = $viatico;
  }
  protected $rules = [
    'fecha_pago' => 'required'
  ];

  public function show($id)
  {
    $valores = explode('&',$id);

    if ($valores[1] == 1) {

      $partidas_viaticos = \App\PartidasViaticosPagos::select('partidas_viaticos_pagos.*',
      DB::raw("CONCAT(EB.nombre,' ',EB.ap_paterno,' ',EB.ap_materno) AS beneficiario"))
      ->join("empleados AS EB","EB.id","=","partidas_viaticos_pagos.beneficiario_id")
      ->where('solicitud_viaticos_id','=',$valores[0])->get();

      return response()->json($partidas_viaticos);

    }elseif ($valores[1] == 2) {

      $partidas_viaticos = \App\PartidasViaticosPagosDBP::select('partidas_viaticos_pagos.*',
      DB::raw("CONCAT(EB.nombre,' ',EB.ap_paterno,' ',EB.ap_materno) AS beneficiario"))
      ->join("empleados AS EB","EB.id","=","partidas_viaticos_pagos.beneficiario_id")
      ->where('solicitud_viaticos_id','=',$valores[0])->get();

      return response()->json($partidas_viaticos);

    }
  }

  public function store(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $validator = Validator::make($request->all(), $this->rules);

    if ($validator->fails()) {
      return response()->json(array(
        'status' => false,
        'errors' => $validator->errors()->all()
      ));
    }
    ///////////CONSERFLOW
    /////////////////////
    /////////////////////
    if ($request->empresa == 1) {
      if ($request->nuevo === '1' || $request->nuevo == 1) {


        $numero = $this->pda($request->solicitud_viaticos_id);
        $partidas_viaticos = new \App\PartidasViaticosPagos();
        $partidas_viaticos->solicitud_viaticos_id = $request->solicitud_viaticos_id;
        $partidas_viaticos->fecha_pago = $request->fecha_pago;
        $partidas_viaticos->importe_te = $request->importe_te;
        $partidas_viaticos->importe_efectivo = $request->importe_efectivo;
        $partidas_viaticos->pda = $numero;
        $partidas_viaticos->banco = $request->banco;
        $partidas_viaticos->beneficiario_id = $request->beneficiario_id;
        if ($request->hasFile('adjunto')) {
          $nombre_archivo = $this->obtenerNombre($request);
          $partidas_viaticos->adjunto = $nombre_archivo;
          $this->subirAdjunto($request, $nombre_archivo);
        }else {
          $nombre_archivo = $this->gua($request->adjunto);
          $partidas_viaticos->adjunto = $nombre_archivo;
        }
        $partidas_viaticos->save();

        $solicitud = \App\SolicitudViaticos::where('id',$request->solicitud_viaticos_id)->first();
        $solicitud->estado = 5;
        $solicitud->save();

        if ($request->hasFile('adjunto')) {
          $facturas = new \App\FacturasViaticosPagos();
          $facturas->solicitud_viaticos_id = $request->solicitud_viaticos_id;
          $facturas->pda = $partidas_viaticos->pda;
          $facturas->adjunto = $nombre_archivo;
          $facturas->save();
        }

        $viaticos = $this->respuesta($request->solicitud_viaticos_id);
        return response()->json(array('status' => true,'viaticos' => $viaticos,'nombre_archivo' => $nombre_archivo,
        'transferencia' => $request->importe_te, 'efectivo' => $request->importe_efectivo));
      }
      elseif ($request->nuevo == 0 || $request->nuevo === '0') {



        if ($request->hasFile('adjunto')) {
          $nombre_archivo = $this->obtenerNombre($request);
          $partidas_via = \App\PartidasViaticosPagos::where('pda','=',$request->pda)
          ->where('solicitud_viaticos_id','=',$request->solicitud_viaticos_id)->first();
          $this->subirAdjunto($request, $nombre_archivo);
          if($partidas_via->adjunto != NULL){
            Utilidades::ftpSolucionEliminar($partidas_via->adjunto);
          }
        }else {
          $nombre_archivo =$this->gua($request->adjunto);
          $partidas_via = \App\PartidasViaticosPagos::where('pda','=',$request->pda)
          ->where('solicitud_viaticos_id','=',$request->solicitud_viaticos_id)->first();
          if($partidas_via->adjunto != NULL){
            Utilidades::ftpSolucionEliminar($partidas_via->adjunto);
          }
        }


        $partidas_viaticos = \App\PartidasViaticosPagos::
        where('solicitud_viaticos_id','=',$request->solicitud_viaticos_id)->where('pda','=',$request->pda)
        ->update(
          [
            'fecha_pago' => $request->fecha_pago,
            'importe_te' => $request->importe_te,
            'importe_efectivo' => $request->importe_efectivo,
            'adjunto' => $nombre_archivo,
          ]
        );
        $viaticos = $this->respuesta($request->solicitud_viaticos_id);
        return response()->json(array('status' => true,'viaticos' => $viaticos));
      }
    }
    ///////////////
    //Constructora
    /////
    elseif ($request->empresa == 2) {
      // code
      if ($request->nuevo === '1' || $request->nuevo == 1) {

        $numero = $this->pdacsct($request->solicitud_viaticos_id);
        $nombre_archivo = $this->gua($request->adjunto);

        $partidas_viaticos = new \App\PartidasViaticosPagosDBP();
        $partidas_viaticos->solicitud_viaticos_id = $request->solicitud_viaticos_id;
        $partidas_viaticos->fecha_pago = $request->fecha_pago;
        $partidas_viaticos->importe_te = $request->importe_te;
        $partidas_viaticos->importe_efectivo = $request->importe_efectivo;
        $partidas_viaticos->pda = $numero;
        $partidas_viaticos->banco = $request->banco;
        $partidas_viaticos->beneficiario_id = $request->beneficiario_id;
        $partidas_viaticos->adjunto = $nombre_archivo;
        $partidas_viaticos->save();

        $solicitud = \App\SolicitudViaticosDBP::where('id',$request->solicitud_viaticos_id)->first();
        $solicitud->estado = 5;
        $solicitud->save();

        if ($request->hasFile('adjunto')) {
          $facturas = new \App\FacturasViaticosPagosDBP();
          $facturas->solicitud_viaticos_id = $request->solicitud_viaticos_id;
          $facturas->pda = $partidas_viaticos->pda;
          $facturas->adjunto = $partidas_viaticos->adjunto;
          $facturas->save();
        }

        $viaticos = $this->respuestacsct($request->solicitud_viaticos_id);
        return response()->json(array('status' => true,'viaticos' => $viaticos,'nombre_archivo' => $nombre_archivo,
        'transferencia' => $request->importe_te, 'efectivo' => $request->importe_efectivo));
      }

      elseif ($request->nuevo == 0 || $request->nuevo === '0') {
        $nombre_archivo = '';

        $partidas_via = \App\PartidasViaticosPagosDBP::where('pda','=',$request->pda)
        ->where('solicitud_viaticos_id','=',$request->solicitud_viaticos_id)->first();
        $nombre_archivo = $this->gua($request->adjunto);

        if($partidas_via->adjunto != NULL){
          Utilidades::ftpSolucionEliminar($partidas_via->adjunto);
        }
        $partidas_viaticos = \App\PartidasViaticosPagosDBP::
        where('solicitud_viaticos_id','=',$request->solicitud_viaticos_id)->where('pda','=',$request->pda)
        ->update(
          [
            'fecha_pago' => $request->fecha_pago,
            'importe_te' => $request->importe_te,
            'importe_efectivo' => $request->importe_efectivo,
            'adjunto' => $nombre_archivo,
          ]
        );
        $viaticos = $this->respuestacsct($request->solicitud_viaticos_id);
        return response()->json(array('status' => true,'viaticos' => $viaticos));
      }
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
    //Aqui de debe cambiar el nombre del archivo para poder tener archivos validos
    $FacturaStore = 'PagoViatico_'.uniqid().'.'.$FacturaExt;

    return $FacturaStore;
  }

  public function subirAdjunto($request, $nombre_archivo)
  {

    Storage::disk('local')->put('Archivos/'.$nombre_archivo, fopen($request->file('adjunto'), 'r+'));
    // $this->enviarCorreosPago($request->solicitud_viaticos_id, $nombre_archivo);
    return true;
  }

  public function descargarfactura($id)
  {
    // Se obtiene el archivo del FTP serve
    $archivo = Utilidades::ftpSolucion($id);
    //--Devuelve la respuesta y descarga el archivo--//
    Storage::disk('descarga')->put($id, $archivo);

    return response()->download(storage_path().'/app/descargas/'.$id);
  }

  public function elimina($id)
  {
    //elimina de la ruta local el archivo descargado
    Storage::disk('descarga')->delete($id);
    Storage::disk('local')->delete($id);
  }

  public function respuesta($id)
  {
    $arreglo = [];

    $viaticos = DB::table('solicitud_viaticos')
    ->select('solicitud_viaticos.*')
    ->where('solicitud_viaticos.id','=',$id)
    ->get();
    foreach ($viaticos as $key => $viatico) {
      $beneficiarios = $this->viaticos->BeneficiarioViatico($viatico->id);
      $detalles_viaticos = $this->viaticos->DetalleViatico($viatico->id);
      $arreglo [] = [
        'solicitud' => $viatico,
        'beneficiario' => $beneficiarios,
        'detalle' => $detalles_viaticos,
      ];
    }
    return $arreglo;
  }

  public function respuestacsct($id)
  {
    $arreglo = [];

    $viaticos = \App\SolicitudViaticosDBP::
    select('solicitud_viaticos.*')
    ->where('solicitud_viaticos.id','=',$id)
    ->get();
    foreach ($viaticos as $key => $viatico) {
      $beneficiarios = $this->viaticos->BeneficiarioViaticoCSCT($viatico->id);
      $detalles_viaticos = $this->viaticos->DetalleViaticoCSCT($viatico->id);
      $arreglo [] = [
        'solicitud' => $viatico,
        'beneficiario' => $beneficiarios,
        'detalle' => $detalles_viaticos,
      ];
    }
    return $arreglo;
  }


  public function pda($id)
  {
    $numero = 0;
    $partidas = \App\PartidasViaticosPagos::where('solicitud_viaticos_id','=',$id)->
    select(DB::raw("COUNT(partidas_viaticos_pagos.solicitud_viaticos_id) AS tamanio"))->first();
    $numero = $partidas == null ? 1 : ($partidas->tamanio + 1);
    return $numero;
  }

  public function pdacsct($id)
  {
    $numero = 0;
    $partidas = \App\PartidasViaticosPagosDBP::where('solicitud_viaticos_id','=',$id)->
    select(DB::raw("COUNT(partidas_viaticos_pagos.solicitud_viaticos_id) AS tamanio"))->first();
    $numero = $partidas == null ? 1 : ($partidas->tamanio + 1);
    return $numero;
  }

  public function enviarCorreosPago($ids)
  {
    $valores = explode('&',$ids);
    $id = $valores[0];
    $efectivo = strval($valores[1]);
    $transferencia = strval($valores[2]);

    $solicitud_viaticos = $this->viaticos->solicitud_consulta()
    ->where('solicitud_viaticos.id',$id)
    ->first();

    $empleado = \App\Empleado::join('correos_corporativos AS CC', 'empleados.id', '=', 'CC.empleado_id')
    ->where('empleados.id',$solicitud_viaticos->empleado_elabora_id)->select('empleados.*','CC.nombre as correo_electronico')->first();

    $hoy = date("Y-m-d");
    $msn = "Aviso de pago de viaticos";
    $ruta = 'ViaticosPagos/';
    $data = [
      'fecha' => $hoy,
      'nombre' => $empleado->nombre.' '.$empleado->ap_paterno.' '.$empleado->ap_materno,
      'correo_electronico' => $empleado->correo_electronico,
      'solicitud_viaticos' => $solicitud_viaticos,
      'mensaje' => $msn,
      'efectivo' => $efectivo, 'transferencia' => $transferencia,
    ];
    Mail::send('emails.viaticopago', $data, function($message) use ($data, $empleado, $msn, $ruta) {
      $core= $empleado->correo_electronico;
      $message->to($core, $msn)->subject($msn);
      $message->from('cesar.abad@conservct.com','Conserflow');

    });
  }

  public function revisionpagos($id)
  {
    $reporte = \App\ReporteGastosViaticos::where('solicitud_viaticos_id',$id)->get();
    return response()->json($reporte);
  }

  public function gua($img)
  {
    try {
      // return response()->json($request);

      $image_64 = $img; //your base64 encoded data

      $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf

      $replace = substr($image_64, 0, strpos($image_64, ',')+1);

      // find substring fro replace here eg: data:image/png;base64,

      $image = str_replace($replace, '', $image_64);

      $image = str_replace(' ', '+', $image);

      $imageName = uniqid().'ee.'.$extension;


      Storage::disk('local')->put('Archivos/'.$imageName, base64_decode($image));

      return $imageName;

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }



}
