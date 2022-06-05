<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CajaChicaSolicitud;
use App\CajaChicaSolicitudDBP;
use App\Empleado;
use App\EmpleadoDBP;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;
use Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CajaChicaComprobacionExport;


class CajaChicaController extends Controller
{
  //
  public function getCFW()
  {
    $user = Auth::user();

    $solicitud = CajaChicaSolicitud::
    join('empleados AS er','er.id','solicitud_caja_chica.empleado_revisa_id')
    ->join('empleados AS ea','ea.id','solicitud_caja_chica.empleado_autoriza_id')
    ->join('empleados AS eb','eb.id','solicitud_caja_chica.beneficiario_id')
    ->leftJoin('datos_bancarios_empleados AS dbe','dbe.id','solicitud_caja_chica.datos_bancos_beneficiario')
    ->leftJoin('catalogo_bancos AS b','b.id','dbe.banco_id')
    ->select('solicitud_caja_chica.*',
    DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_revisa"),
    DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_autoriza"),
    DB::raw("CONCAT(eb.nombre,' ',eb.ap_paterno,' ',eb.ap_materno) AS beneficiario"),
    'b.nombre AS nombre_banco'
    )
    ->where('empleado_user_id',$user->empleado_id)
    ->where('empresa','1')
    ->orderBy('solicitud_caja_chica.id','DESC')
    ->get();
    // code...
    return response()->json($solicitud);
  }

  //
  public function getControlCFW()
  {
    // $user = Auth::user();

    $solicitud = CajaChicaSolicitud::
    join('empleados AS er','er.id','solicitud_caja_chica.empleado_revisa_id')
    ->join('empleados AS ea','ea.id','solicitud_caja_chica.empleado_autoriza_id')
    ->join('empleados AS eb','eb.id','solicitud_caja_chica.beneficiario_id')
    ->leftJoin('datos_bancarios_empleados AS dbe','dbe.id','solicitud_caja_chica.datos_bancos_beneficiario')
    ->leftJoin('catalogo_bancos AS b','b.id','dbe.banco_id')
    ->select('solicitud_caja_chica.*',
    DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_revisa"),
    DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_autoriza"),
    DB::raw("CONCAT(eb.nombre,' ',eb.ap_paterno,' ',eb.ap_materno) AS beneficiario"),
    'b.nombre AS nombre_banco'
    )
    ->where('estado','9')
    ->where('empresa','1')
    ->orderBy('solicitud_caja_chica.id','DESC')
    ->get();
    // code...
    return response()->json($solicitud);
  }

  //
  public function getCFWC()
  {

    $solicitud = CajaChicaSolicitud::
    join('empleados AS er','er.id','solicitud_caja_chica.empleado_revisa_id')
    ->join('empleados AS ea','ea.id','solicitud_caja_chica.empleado_autoriza_id')
    ->join('empleados AS eb','eb.id','solicitud_caja_chica.beneficiario_id')
    ->leftJoin('datos_bancarios_empleados AS dbe','dbe.id','solicitud_caja_chica.datos_bancos_beneficiario')
    ->join('catalogo_bancos AS b','b.id','dbe.banco_id')
    ->select('solicitud_caja_chica.*',
    DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_revisa"),
    DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_autoriza"),
    DB::raw("CONCAT(eb.nombre,' ',eb.ap_paterno,' ',eb.ap_materno) AS beneficiario"),
    'b.nombre AS nombre_banco'
    )
    ->where('estado','3')
    ->where('empresa','1')
    ->orderBy('solicitud_caja_chica.id','DESC')
    ->get();
    // code...
    return response()->json($solicitud);
  }

  ///
  public function getCSCT()
  {
    $user = Auth::user();
    $user_empleado = $this->empleadoCSCT($user->empleado_id);

    // return response()->json($user_empleado);
    $solicitud = CajaChicaSolicitudDBP::
    leftJoin('empleados AS er','er.id','solicitud_caja_chica.empleado_revisa_id')
    ->leftJoin('empleados AS ea','ea.id','solicitud_caja_chica.empleado_autoriza_id')
    ->leftJoin('empleados AS eb','eb.id','solicitud_caja_chica.beneficiario_id')
    ->leftJoin('datos_bancarios_empleados AS dbe','dbe.id','solicitud_caja_chica.datos_bancos_beneficiario')
    ->leftJoin('catalogo_bancos AS b','b.id','dbe.banco_id')
    ->select('solicitud_caja_chica.*',
    DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_revisa"),
    DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_autoriza"),
    DB::raw("CONCAT(eb.nombre,' ',eb.ap_paterno,' ',eb.ap_materno) AS beneficiario"),
    'b.nombre AS nombre_banco'
    )
    ->where('empleado_user_id',$user_empleado)
    ->where('empresa','2')
    ->orderBy('solicitud_caja_chica.id','DESC')
    ->get();
    // code...
    return response()->json($solicitud);
  }

  ///
  public function getControlCSCT()
  {
    // $user = Auth::user();
    // $user_empleado = $this->empleadoCSCT($user->empleado_id);

    // return response()->json($user_empleado);
    $solicitud = CajaChicaSolicitudDBP::
    leftJoin('empleados AS er','er.id','solicitud_caja_chica.empleado_revisa_id')
    ->leftJoin('empleados AS ea','ea.id','solicitud_caja_chica.empleado_autoriza_id')
    ->leftJoin('empleados AS eb','eb.id','solicitud_caja_chica.beneficiario_id')
    ->leftJoin('datos_bancarios_empleados AS dbe','dbe.id','solicitud_caja_chica.datos_bancos_beneficiario')
    ->leftJoin('catalogo_bancos AS b','b.id','dbe.banco_id')
    ->select('solicitud_caja_chica.*',
    DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_revisa"),
    DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_autoriza"),
    DB::raw("CONCAT(eb.nombre,' ',eb.ap_paterno,' ',eb.ap_materno) AS beneficiario"),
    'b.nombre AS nombre_banco'
    )
    ->where('estado','9')
    ->where('empresa','2')
    ->orderBy('solicitud_caja_chica.id','DESC')
    ->get();
    // code...
    return response()->json($solicitud);
  }

  ///
  public function getCSCTC()
  {

    // return response()->json($user_empleado);
    $solicitud = CajaChicaSolicitudDBP::
    leftJoin('empleados AS er','er.id','solicitud_caja_chica.empleado_revisa_id')
    ->leftJoin('empleados AS ea','ea.id','solicitud_caja_chica.empleado_autoriza_id')
    ->leftJoin('empleados AS eb','eb.id','solicitud_caja_chica.beneficiario_id')
    ->leftJoin('datos_bancarios_empleados AS dbe','dbe.id','solicitud_caja_chica.datos_bancos_beneficiario')
    ->leftJoin('catalogo_bancos AS b','b.id','dbe.banco_id')
    ->select('solicitud_caja_chica.*',
    DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_revisa"),
    DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_autoriza"),
    DB::raw("CONCAT(eb.nombre,' ',eb.ap_paterno,' ',eb.ap_materno) AS beneficiario"),
    'b.nombre AS nombre_banco'
    )
    ->where('estado','3')
    ->where('empresa','2')
    ->orderBy('solicitud_caja_chica.id','DESC')
    ->get();
    // code...
    return response()->json($solicitud);
  }

  /**
  *
  **/
  public function post(Request $request)
  {
    try{
      $user_empleado = 0;
      $user = Auth::user();
      if($request->empresa == 1 ){
        $user_empleado = $user->empleado_id;
        $solicitud_ = CajaChicaSolicitud::where('empleado_user_id',$user_empleado)->get();
        $solicitud_contador = count($solicitud_);

        $solicitud = new CajaChicaSolicitud();
        $solicitud->folio = str_pad(($solicitud_contador + 1), 3, "0", STR_PAD_LEFT);
        $solicitud->fecha = $request->fecha;
        $solicitud->empleado_revisa_id = $request->empleado_revisa_id;
        $solicitud->empleado_autoriza_id = $request->empleado_autoriza_id;
        $solicitud->beneficiario_id = $request->beneficiario_id;
        $solicitud->datos_bancos_beneficiario = $request->datos_bancos_beneficiario;
        $solicitud->cuentauno = $request->cuentauno;
        $solicitud->claveuno = $request->claveuno;
        $solicitud->banco = $request->banco;
        $solicitud->clavecuentatarjetauno = $request->clavecuentatarjetauno;
        $solicitud->conceptos = $request->listado['conceptos'][0];
        $solicitud->efectivo = $request->listado['efectivo'][0];
        $solicitud->tranferencia = $request->listado['tranferencia'][0];
        $solicitud->empresa = $request->empresa;
        $solicitud->estado = 0;
        $solicitud->empleado_user_id = $user_empleado;
        Utilidades::auditar($solicitud,$solicitud->id);
        $solicitud->save();

      }elseif ($request->empresa == 2) {
        $user_empleado = $this->empleadoCSCT($user->empleado_id);
        $solicitud_ = CajaChicaSolicitudDBP::where('empleado_user_id',$user_empleado)->get();
        $solicitud_contador = count($solicitud_);

        $solicitud = new CajaChicaSolicitudDBP();
        $solicitud->folio = str_pad(($solicitud_contador + 1), 3, "0", STR_PAD_LEFT);
        $solicitud->fecha = $request->fecha;
        $solicitud->empleado_revisa_id = $request->empleado_revisa_id;
        $solicitud->empleado_autoriza_id = $request->empleado_autoriza_id;
        $solicitud->beneficiario_id = $request->beneficiario_id;
        $solicitud->datos_bancos_beneficiario = $request->datos_bancos_beneficiario;
        $solicitud->cuentauno = $request->cuentauno;
        $solicitud->claveuno = $request->claveuno;
        $solicitud->banco = $request->banco;
        $solicitud->clavecuentatarjetauno = $request->clavecuentatarjetauno;
        $solicitud->conceptos = $request->listado['conceptos'][0];
        $solicitud->efectivo = $request->listado['efectivo'][0];
        $solicitud->tranferencia = $request->listado['tranferencia'][0];
        $solicitud->empresa = $request->empresa;
        $solicitud->estado = 0;
        $solicitud->empleado_user_id = $user_empleado;
        Utilidades::auditar($solicitud,$solicitud->id);
        $solicitud->save();
      }

      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  /**
  *
  **/
  public function update(Request $request)
  {
    try{
      $user_empleado = 0;
      $user = Auth::user();
      if($request->empresa == 1 ){
        $user_empleado = $user->empleado_id;

        $solicitud = CajaChicaSolicitud::where('id',$request->id)->first();
        $solicitud->fecha = $request->fecha;
        $solicitud->empleado_revisa_id = $request->empleado_revisa_id;
        $solicitud->empleado_autoriza_id = $request->empleado_autoriza_id;
        $solicitud->beneficiario_id = $request->beneficiario_id;
        $solicitud->datos_bancos_beneficiario = $request->datos_bancos_beneficiario;
        $solicitud->cuentauno = $request->cuentauno;
        $solicitud->claveuno = $request->claveuno;
        $solicitud->banco = $request->banco;
        $solicitud->clavecuentatarjetauno = $request->clavecuentatarjetauno;
        $solicitud->conceptos = $request->listado['conceptos'][0];
        $solicitud->efectivo = $request->listado['efectivo'][0];
        $solicitud->tranferencia = $request->listado['tranferencia'][0];
        $solicitud->empresa = $request->empresa;
        $solicitud->estado = 0;
        $solicitud->empleado_user_id = $user_empleado;
        Utilidades::auditar($solicitud,$solicitud->id);
        $solicitud->update();

      }elseif ($request->empresa == 2) {
        $user_empleado = $this->empleadoCSCT($user->empleado_id);

        $solicitud = CajaChicaSolicitudDBP::where('id',$request->id)->first();
        $solicitud->fecha = $request->fecha;
        $solicitud->empleado_revisa_id = $request->empleado_revisa_id;
        $solicitud->empleado_autoriza_id = $request->empleado_autoriza_id;
        $solicitud->beneficiario_id = $request->beneficiario_id;
        $solicitud->datos_bancos_beneficiario = $request->datos_bancos_beneficiario;
        $solicitud->cuentauno = $request->cuentauno;
        $solicitud->claveuno = $request->claveuno;
        $solicitud->banco = $request->banco;
        $solicitud->clavecuentatarjetauno = $request->clavecuentatarjetauno;
        $solicitud->conceptos = $request->listado['conceptos'][0];
        $solicitud->efectivo = $request->listado['efectivo'][0];
        $solicitud->tranferencia = $request->listado['tranferencia'][0];
        $solicitud->empresa = $request->empresa;
        $solicitud->estado = 0;
        $solicitud->empleado_user_id = $user_empleado;
        Utilidades::auditar($solicitud,$solicitud->id);
        $solicitud->update();

      }



      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function empleadoCSCT($empleado_cfw)
  {
    $empleado = 0;
    $empleado_cfw = Empleado::where('id',$empleado_cfw)->select('nombre','ap_paterno','ap_materno')->first();
    $empleado_csct = EmpleadoDBP::where('nombre',$empleado_cfw->nombre)->where('ap_paterno',$empleado_cfw->ap_paterno)->where('ap_materno',$empleado_cfw->ap_materno)->first();
    $empleado = $empleado_csct->id;
    return $empleado;
  }

  /**
  **
  **/
  public function revision($id)
  {
    try {
      $valores = explode('&',$id);
      if ($valores[1] == 1) {

        $solicitud = CajaChicaSolicitud::where('id',$valores[0])->first();
        $solicitud->estado = 1;
        $solicitud->update();

        $solicitud_ = CajaChicaSolicitud::
        join('empleados AS er','er.id','solicitud_caja_chica.empleado_revisa_id')
        ->join('empleados AS ea','ea.id','solicitud_caja_chica.empleado_autoriza_id')
        ->join('empleados AS eb','eb.id','solicitud_caja_chica.beneficiario_id')
        ->leftJoin('datos_bancarios_empleados AS dbe','dbe.id','solicitud_caja_chica.datos_bancos_beneficiario')
        ->join('catalogo_bancos AS b','b.id','dbe.banco_id')
        ->select('solicitud_caja_chica.*',
        DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_revisa"),
        DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_autoriza"),
        DB::raw("CONCAT(eb.nombre,' ',eb.ap_paterno,' ',eb.ap_materno) AS beneficiario"),
        'b.nombre AS nombre_banco'
        )
        ->where('solicitud_caja_chica.id',$valores[0])
        ->first();
        $this->enviarCorreo($solicitud_,'Revisión Caja Chica',$solicitud_->empleado_revisa_id);
      }elseif ($valores[1] == 2) {

        $solicitud = CajaChicaSolicitudDBP::where('id',$valores[0])->first();
        $solicitud->estado = 1;
        $solicitud->update();

        $solicitud_ = CajaChicaSolicitudDBP::
        leftJoin('empleados AS er','er.id','solicitud_caja_chica.empleado_revisa_id')
        ->leftJoin('empleados AS ea','ea.id','solicitud_caja_chica.empleado_autoriza_id')
        ->leftJoin('empleados AS eb','eb.id','solicitud_caja_chica.beneficiario_id')
        ->leftJoin('datos_bancarios_empleados AS dbe','dbe.id','solicitud_caja_chica.datos_bancos_beneficiario')
        ->leftJoin('catalogo_bancos AS b','b.id','dbe.banco_id')
        ->select('solicitud_caja_chica.*',
        DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_revisa"),
        DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_autoriza"),
        DB::raw("CONCAT(eb.nombre,' ',eb.ap_paterno,' ',eb.ap_materno) AS beneficiario"),
        'b.nombre AS nombre_banco'
        )
        ->where('solicitud_caja_chica.id',$valores[0])
        ->first();

        $this->enviarCorreo($solicitud_,'Revisión Caja Chica',$solicitud_->empleado_revisa_id);
      }


      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function enviarCorreo($data_,$mensaje_,$emp,$ma = "")
  {
    $mensaje = $mensaje_;
    $correo_e = $this->obtenerCorreo($emp,$data_->empresa);
    $data = [
      'mensaje' =>$mensaje_,
      'mensaje_adicional' => $ma,
      'correo_electronico' => $correo_e,
      'solicitud' => $data_,
      'tt' => $data_->efectivo + $data_->tranferencia,
    ];
    Mail::send('emails.cajachica', $data, function($message) use ($data, $mensaje) {
      $core = $data['correo_electronico'];

      $pdf = PDF::loadView('pdf.forcc',$data);
      $message->to($core, $mensaje)->subject($mensaje);
      // $message->to('romerovelascogregorio@gmail.com', $mensaje)->subject($mensaje);
      $message->from('romerovelascogregorio@gmail.com','Conserflow');
      $message->attachData($pdf->output(), 'CajaChica.pdf');
      // $message->attach('public/img/1.png');
    });

  }

  public function obtenerCorreo($id, $emp)
  {
    try {

      $correo = '';
      if ($emp == 1) {
        $empleado_cfw = Empleado::
        leftJoin('users AS u','u.empleado_id','empleados.id')
        ->where('empleados.id',$id)
        ->select('u.email')
        ->first();
        $correo = $empleado_cfw->email;
      }elseif ($emp == 2) {
        $empleado_cfw = Empleado::
        where('empleados.id',$id)
        ->first();

        $empleado_csct = EmpleadoDBP::
        leftJoin('users AS u','u.empleado_id','empleados.id')
        ->where('nombre',$empleado_cfw->nombre)
        ->where('ap_paterno',$empleado_cfw->ap_paterno)
        ->where('ap_materno',$empleado_cfw->ap_materno)
        ->select('u.email')
        ->first();

        $correo = $empleado_csct->email;

      }
      return $correo;

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  /**
  **/
  public function descarga($id)
  {
    try {
      $valores = explode('&',$id);
      $tt = 0;
      if ($valores[1] == 1) {

        $solicitud = CajaChicaSolicitud::
        join('empleados AS er','er.id','solicitud_caja_chica.empleado_revisa_id')
        ->join('empleados AS ea','ea.id','solicitud_caja_chica.empleado_autoriza_id')
        ->join('empleados AS eb','eb.id','solicitud_caja_chica.beneficiario_id')
        ->leftJoin('datos_bancarios_empleados AS dbe','dbe.id','solicitud_caja_chica.datos_bancos_beneficiario')
        ->leftJoin('catalogo_bancos AS b','b.id','dbe.banco_id')
        ->select('solicitud_caja_chica.*',
        DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_revisa"),
        DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_autoriza"),
        DB::raw("CONCAT(eb.nombre,' ',eb.ap_paterno,' ',eb.ap_materno) AS beneficiario"),
        'b.nombre AS nombre_banco'
        )
        ->where('solicitud_caja_chica.id',(int)$valores[0])
        ->first();

        // return response()->json($valores[0]);
        $tt = $solicitud->efectivo + $solicitud->tranferencia;
      }elseif ($valores[1] == 2) {

        $solicitud = CajaChicaSolicitudDBP::
        leftJoin('empleados AS er','er.id','solicitud_caja_chica.empleado_revisa_id')
        ->leftJoin('empleados AS ea','ea.id','solicitud_caja_chica.empleado_autoriza_id')
        ->leftJoin('empleados AS eb','eb.id','solicitud_caja_chica.beneficiario_id')
        ->leftJoin('datos_bancarios_empleados AS dbe','dbe.id','solicitud_caja_chica.datos_bancos_beneficiario')
        ->leftJoin('catalogo_bancos AS b','b.id','dbe.banco_id')
        ->select('solicitud_caja_chica.*',
        DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_revisa"),
        DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_autoriza"),
        DB::raw("CONCAT(eb.nombre,' ',eb.ap_paterno,' ',eb.ap_materno) AS beneficiario"),
        'b.nombre AS nombre_banco'
        )
        ->where('solicitud_caja_chica.id',$valores[0])
        ->first();

        $tt = $solicitud->efectivo + $solicitud->tranferencia;

      }

      $pdf = PDF::loadView('pdf.forcc', compact('solicitud','tt'));
      //  $pdf->setPaper('A4', 'landscape');
      $pdf->setPaper("letter", "portrait");
      // return $pdf->download('cv-interno.pdf');
      return $pdf->stream();

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }


  }


  public function validar()
  {
    try {
      $user = Auth::user();
      $empleado_csct = $this->empleadoCSCT($user->empleado_id);

      $solicitud_cfw = CajaChicaSolicitud::
      join('empleados AS er','er.id','solicitud_caja_chica.empleado_revisa_id')
      ->join('empleados AS ea','ea.id','solicitud_caja_chica.empleado_autoriza_id')
      ->join('empleados AS eb','eb.id','solicitud_caja_chica.beneficiario_id')
      ->leftJoin('datos_bancarios_empleados AS dbe','dbe.id','solicitud_caja_chica.datos_bancos_beneficiario')
      ->join('catalogo_bancos AS b','b.id','dbe.banco_id')
      ->select('solicitud_caja_chica.*',
      DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_revisa"),
      DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_autoriza"),
      DB::raw("CONCAT(eb.nombre,' ',eb.ap_paterno,' ',eb.ap_materno) AS beneficiario"),
      'b.nombre AS nombre_banco'
      )
      ->where('solicitud_caja_chica.empleado_revisa_id',$user->empleado_id)
      ->where('solicitud_caja_chica.estado','1')
      ->get()->toArray();



      $solicitud_csct = CajaChicaSolicitudDBP::
      leftJoin('empleados AS er','er.id','solicitud_caja_chica.empleado_revisa_id')
      ->leftJoin('empleados AS ea','ea.id','solicitud_caja_chica.empleado_autoriza_id')
      ->leftJoin('empleados AS eb','eb.id','solicitud_caja_chica.beneficiario_id')
      ->leftJoin('datos_bancarios_empleados AS dbe','dbe.id','solicitud_caja_chica.datos_bancos_beneficiario')
      ->leftJoin('catalogo_bancos AS b','b.id','dbe.banco_id')
      ->select('solicitud_caja_chica.*',
      DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_revisa"),
      DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_autoriza"),
      DB::raw("CONCAT(eb.nombre,' ',eb.ap_paterno,' ',eb.ap_materno) AS beneficiario"),
      'b.nombre AS nombre_banco'
      )
      ->where('solicitud_caja_chica.empleado_revisa_id',$empleado_csct)
      ->where('solicitud_caja_chica.estado','1')
      ->get()->toArray();

      $solicitudes = array_merge($solicitud_cfw, $solicitud_csct);

      return response()->json($solicitudes);

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function validacion(Request $request)
  {
    try {

      if ($request->empresa == 1) {

        $solicitud_cfw = CajaChicaSolicitud::
        join('empleados AS er','er.id','solicitud_caja_chica.empleado_revisa_id')
        ->join('empleados AS ea','ea.id','solicitud_caja_chica.empleado_autoriza_id')
        ->join('empleados AS eb','eb.id','solicitud_caja_chica.beneficiario_id')
        ->leftJoin('datos_bancarios_empleados AS dbe','dbe.id','solicitud_caja_chica.datos_bancos_beneficiario')
        ->join('catalogo_bancos AS b','b.id','dbe.banco_id')
        ->select('solicitud_caja_chica.*',
        DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_revisa"),
        DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_autoriza"),
        DB::raw("CONCAT(eb.nombre,' ',eb.ap_paterno,' ',eb.ap_materno) AS beneficiario"),
        'b.nombre AS nombre_banco'
        )
        ->where('solicitud_caja_chica.id',$request->id)
        ->where('solicitud_caja_chica.empresa',$request->empresa)
        ->first();
        $solicitud_cfw_ = CajaChicaSolicitud::where('id',$request->id)
        ->where('empresa',$request->empresa)
        ->first();
        $solicitud_cfw_->estado = $request->estado;
        $solicitud_cfw_->save();

        if ($request->estado == 3) {
          // $this->enviarCorreo($solicitud_cfw,'Rechazado Caja Chica',$solicitud_cfw->empleado_user_id, $request->mensaje_adicional);

        }elseif ($request->estado == 2) {
          // $this->enviarCorreo($solicitud_cfw,'Autorización Caja Chica',$solicitud_cfw->empleado_autoriza_id);

        }



      }elseif ($request->empresa == 2) {

        $solicitud_csct = CajaChicaSolicitudDBP::
        leftJoin('empleados AS er','er.id','solicitud_caja_chica.empleado_revisa_id')
        ->leftJoin('empleados AS ea','ea.id','solicitud_caja_chica.empleado_autoriza_id')
        ->leftJoin('empleados AS eb','eb.id','solicitud_caja_chica.beneficiario_id')
        ->leftJoin('datos_bancarios_empleados AS dbe','dbe.id','solicitud_caja_chica.datos_bancos_beneficiario')
        ->leftJoin('catalogo_bancos AS b','b.id','dbe.banco_id')
        ->select('solicitud_caja_chica.*',
        DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_revisa"),
        DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_autoriza"),
        DB::raw("CONCAT(eb.nombre,' ',eb.ap_paterno,' ',eb.ap_materno) AS beneficiario"),
        'b.nombre AS nombre_banco'
        )
        ->where('solicitud_caja_chica.id',$request->id)
        ->where('solicitud_caja_chica.empresa',$request->empresa)
        ->first();

        $solicitud_csct_ = CajaChicaSolicitudDBP::where('id',$request->id)
        ->where('empresa',$request->empresa)
        ->first();
        $solicitud_csct_->estado = $request->estado;
        $solicitud_csct_->save();

        if ($request->estado == 3) {
          // $this->enviarCorreo($solicitud_csct,'Rechazado Caja Chica',$solicitud_csct->empleado_user_id, $request->mensaje_adicional);

        }elseif ($request->estado == 2) {
          // $this->enviarCorreo($solicitud_csct,'Autorización Caja Chica',$solicitud_csct->empleado_autoriza_id);

        }



      }

      return response()->json($request->estado);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function autoriza()
  {
    try {
      $user = Auth::user();
      $empleado_csct = $this->empleadoCSCT($user->empleado_id);

      $solicitud_cfw = CajaChicaSolicitud::
      join('empleados AS er','er.id','solicitud_caja_chica.empleado_revisa_id')
      ->join('empleados AS ea','ea.id','solicitud_caja_chica.empleado_autoriza_id')
      ->join('empleados AS eb','eb.id','solicitud_caja_chica.beneficiario_id')
      ->leftJoin('datos_bancarios_empleados AS dbe','dbe.id','solicitud_caja_chica.datos_bancos_beneficiario')
      ->join('catalogo_bancos AS b','b.id','dbe.banco_id')
      ->select('solicitud_caja_chica.*',
      DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_revisa"),
      DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_autoriza"),
      DB::raw("CONCAT(eb.nombre,' ',eb.ap_paterno,' ',eb.ap_materno) AS beneficiario"),
      'b.nombre AS nombre_banco'
      )
      ->where('solicitud_caja_chica.empleado_revisa_id',$user->empleado_id)
      ->where('solicitud_caja_chica.estado','2')
      ->get()->toArray();



      $solicitud_csct = CajaChicaSolicitudDBP::
      leftJoin('empleados AS er','er.id','solicitud_caja_chica.empleado_revisa_id')
      ->leftJoin('empleados AS ea','ea.id','solicitud_caja_chica.empleado_autoriza_id')
      ->leftJoin('empleados AS eb','eb.id','solicitud_caja_chica.beneficiario_id')
      ->leftJoin('datos_bancarios_empleados AS dbe','dbe.id','solicitud_caja_chica.datos_bancos_beneficiario')
      ->leftJoin('catalogo_bancos AS b','b.id','dbe.banco_id')
      ->select('solicitud_caja_chica.*',
      DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_revisa"),
      DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_autoriza"),
      DB::raw("CONCAT(eb.nombre,' ',eb.ap_paterno,' ',eb.ap_materno) AS beneficiario"),
      'b.nombre AS nombre_banco'
      )
      ->where('solicitud_caja_chica.empleado_revisa_id',$empleado_csct)
      ->where('solicitud_caja_chica.estado','2s')
      ->get()->toArray();

      $solicitudes = array_merge($solicitud_cfw, $solicitud_csct);

      return response()->json($solicitudes);

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function autorizacion(Request $request)
  {
    try {

      if ($request->empresa == 1) {

        $solicitud_cfw = CajaChicaSolicitud::
        join('empleados AS er','er.id','solicitud_caja_chica.empleado_revisa_id')
        ->join('empleados AS ea','ea.id','solicitud_caja_chica.empleado_autoriza_id')
        ->join('empleados AS eb','eb.id','solicitud_caja_chica.beneficiario_id')
        ->leftJoin('datos_bancarios_empleados AS dbe','dbe.id','solicitud_caja_chica.datos_bancos_beneficiario')
        ->join('catalogo_bancos AS b','b.id','dbe.banco_id')
        ->select('solicitud_caja_chica.*',
        DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_revisa"),
        DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_autoriza"),
        DB::raw("CONCAT(eb.nombre,' ',eb.ap_paterno,' ',eb.ap_materno) AS beneficiario"),
        'b.nombre AS nombre_banco'
        )
        ->where('solicitud_caja_chica.id',$request->id)
        ->where('solicitud_caja_chica.empresa',$request->empresa)
        ->first();
        $solicitud_cfw_ = CajaChicaSolicitud::where('id',$request->id)
        ->where('empresa',$request->empresa)
        ->first();
        $solicitud_cfw_->estado = $request->estado;
        $solicitud_cfw_->save();

        if ($request->estado == 3) {
          $this->enviarCorreo($solicitud_cfw,'Rechazado Caja Chica',$solicitud_cfw->empleado_user_id, $request->mensaje_adicional);

        }elseif ($request->estado == 4) {
          $this->enviarCorreo($solicitud_cfw,'Registrar pago a caja chica autorizada',$solicitud_cfw->empleado_autoriza_id);

        }



      }elseif ($request->empresa == 2) {

        $solicitud_csct = CajaChicaSolicitudDBP::
        leftJoin('empleados AS er','er.id','solicitud_caja_chica.empleado_revisa_id')
        ->leftJoin('empleados AS ea','ea.id','solicitud_caja_chica.empleado_autoriza_id')
        ->leftJoin('empleados AS eb','eb.id','solicitud_caja_chica.beneficiario_id')
        ->leftJoin('datos_bancarios_empleados AS dbe','dbe.id','solicitud_caja_chica.datos_bancos_beneficiario')
        ->leftJoin('catalogo_bancos AS b','b.id','dbe.banco_id')
        ->select('solicitud_caja_chica.*',
        DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_revisa"),
        DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_autoriza"),
        DB::raw("CONCAT(eb.nombre,' ',eb.ap_paterno,' ',eb.ap_materno) AS beneficiario"),
        'b.nombre AS nombre_banco'
        )
        ->where('solicitud_caja_chica.id',$request->id)
        ->where('solicitud_caja_chica.empresa',$request->empresa)
        ->first();

        $solicitud_csct_ = CajaChicaSolicitudDBP::where('id',$request->id)
        ->where('empresa',$request->empresa)
        ->first();
        $solicitud_csct_->estado = $request->estado;
        $solicitud_csct_->save();

        if ($request->estado == 3) {
          $this->enviarCorreo($solicitud_csct,'Rechazado Caja Chica',$solicitud_csct->empleado_user_id, $request->mensaje_adicional);

        }elseif ($request->estado == 4) {
          $this->enviarCorreo($solicitud_csct,'Registrar pago a caja chica autorizada',$solicitud_csct->empleado_autoriza_id);

        }

      }

      return response()->json($request->estado);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function comprobacion($id)
  {
    try {
      $valores = explode('&',$id);

      $comprobacion = \App\ComprobacionesCajaChica::
      join('gastos_xml_oc AS gx','gx.id','comprobaciones_caja_chica.factura_id')
      ->leftjoin('proyectos AS p','p.id','comprobaciones_caja_chica.centro_costos_id')
      ->select(
        'gx.*','p.nombre_corto',
        'comprobaciones_caja_chica.id AS ccc_id',
        'comprobaciones_caja_chica.empresa AS ccc_empresa',
        'comprobaciones_caja_chica.uuid AS ccc_uuid',
        'comprobaciones_caja_chica.comprobante',
        'comprobaciones_caja_chica.xml',
        'comprobaciones_caja_chica.estado AS ccc_estado'
        )
        ->where('cajachica_id',$valores[0])
        ->where('empresa',$valores[1])
        ->get();

        $arreglo = [];
        $arreglo_final = [];
        $comprobados = 0;
        foreach ($comprobacion as $key => $value) {
          if ($value->ccc_estado == 1) {
            $comprobados ++;
          }

          $impuesto = \App\ImpuestosOCXml::where('gastos_xml_oc_id',$value->id)
          ->where('base',NULL)
          ->where('tipo','t')
          ->first();

          $retencion = \App\ImpuestosOCXml::where('gastos_xml_oc_id',$value->id)
          ->where('base',NULL)
          ->where('tipo','r')
          ->first();

          $concepto = \App\ConceptosOCXml::where('gastos_xml_oc_id',$value->id)->first();

          $arreglo [] = [
            'comprobacion' =>  $value,
            'impuestos' => $impuesto,
            'retencion' => $retencion,
            'concepto' => $concepto,
          ];
        }

        $arreglo_final [] =
        [
          'data' => $arreglo,
          'contador' =>$comprobados
        ];

        return response()->json($arreglo_final);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function pagos($id)
    {
      try {
        $valores = explode('&',$id);

        $pagos = \App\PagoCajaChica::where('caja_chica_id',$valores[0])
        ->where('empresa',$valores[1])->get();

        return response()->json($pagos);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function store(Request $request)
    {
      try {
        $uso_cfdi_id = 0;
        $uuid = '';
        $total = 0;
        DB::beginTransaction();
        if ($request->hasFile('comprobante') && $request->hasFile('xml') ) {

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

          $factura->ordenes_compras_gastos_id = 0;
          $factura->tipo = 3;
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
            $impuesto = new \App\ImpuestosOCXml();
            $impuesto->base = $d['Base'];
            $impuesto->impuesto = $d['Impuesto'];
            $impuesto->tipofactor = $d['TipoFactor'];
            $impuesto->tasaocuota = $d['TasaOCuota'];
            $impuesto->importe = $d['Importe'];
            $impuesto->gastos_xml_oc_id = $factura->tid;
            $impuesto->tipo = 't';
            $impuesto->save();
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
          $PdfStore = 'CajaChica'.uniqid().'.'.$FacturaExt;
          //Subida del archivo al servidor ftp
          Storage::disk('local')->put('Archivos/'.$PdfStore, fopen($request->file('comprobante'), 'r+'));

          //obtiene el nombre del archivo y su extension
          $FacturaNEx = $request->file('xml')->getClientOriginalName();
          //Obtiene el nombre del archivo
          $FacturaNombrex = pathinfo($FacturaNEx, PATHINFO_FILENAME);
          //obtiene la extension
          $FacturaExtx = $request->file('xml')->getClientOriginalExtension();
          //nombre que se guarad en BD
          $XmlStore = 'CajaChica'.uniqid().'.'.$FacturaExtx;
          //Subida del archivo al servidor ftp
          Storage::disk('local')->put('Archivos/'.$XmlStore, fopen($request->file('xml'), 'r+'));


          $comprobacioncajachica = new \App\ComprobacionesCajaChica();
          $comprobacioncajachica->cajachica_id = $request->cajachica_id;
          $comprobacioncajachica->comprobante = $PdfStore;
          $comprobacioncajachica->xml = $XmlStore;
          $comprobacioncajachica->centro_costos_id = $request->centro_costos_id;
          $comprobacioncajachica->empresa = $request->empresa;
          $comprobacioncajachica->factura_id = $factura->id;
          $comprobacioncajachica->uuid = $uuid;
          $comprobacioncajachica->departamento = $request->departamento;
          $comprobacioncajachica->estado = 0;
          Utilidades::auditar($comprobacioncajachica, $comprobacioncajachica->id);
          $comprobacioncajachica->save();

          $this->enviarCorreoComprobacion($comprobacioncajachica);
        }
        DB::commit();
        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        DB::rollBack();
        Utilidades::errors($e);
      }
    }

    public function actualiza(Request $request)
    {
      // code...
    }

    public function buscar_id_ucdfi($data)
    {
      $uso = \App\SatCatUsoCfdi::where('c_uso',$data)->first();
      return $uso->id;
    }

    public function eliminacomprobacion($id)
    {
      try {
        DB::beginTransaction();
        $comprobacion = \App\ComprobacionesCajaChica::where('id',$id)->first();

        $gasto = \App\GastosXmlOC::where('id',$comprobacion->id)->first();

        $concepto = \App\ConceptosOCXml::where('gastos_xml_oc_id',$gasto->id)->delete();
        $impuesto = \App\ImpuestosOCXml::where('gastos_xml_oc_id',$gasto->id)->delete();
        $gasto_eliminar = \App\GastosXmlOC::where('id',$comprobacion->id)->delete();

        Utilidades::ftpSolucionEliminar($comprobacion->comprobante);
        Utilidades::ftpSolucionEliminar($comprobacion->xml);

        $comprobacion_eliminar = \App\ComprobacionesCajaChica::where('id',$id)->delete();
        DB::commit();

        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        DB::rollBack();
        Utilidades::errors($e);
      }
    }

    public function enviarCorreoComprobacion($data)
    {
      try {
        $correo = 'webmaster@conserflow.com';
        if ($data->departamento == 1) {
          // $correo = 'gregorio.romero@conserflow.com';
          $correo = 'jaime.martinez@conserflow.com';
        }elseif($data->departamento == 3){
          // $correo = 'gregorio.romero@conserflow.com';
          $correo = 'cesar.abad@conserflow.com';
        }elseif ($data->departamento == 2) {
          // $correo = 'gregorio.romero@conserflow.com';
          $correo = 'alejandro.machorro@conserflow.com';
        }
        $mensaje = 'Validación material caja chica';
        $data = [
          'mensaje' => $mensaje,
          'correo' => $correo,
        ];

        Mail::send('emails.cajachicac', $data, function($message) use ($data) {
          $core = $data['correo'];
          $mensaje = $data['mensaje'];
          // $folio = $data['solicitud_viaticos']['folio'];
          // $pdf = PDF::loadView('pdf.forvia',$data);
          $message->to($core, $mensaje)->subject($mensaje);
          // $message->to('romerovelascogregorio@gmail.com', $mensaje)->subject($mensaje);
          $message->from('webmaster@conserflow.com','Conserflow');
          // $message->attachData($pdf->output(), $folio.'.pdf');
          // $message->attach('public/img/1.png');
        });

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }

    }

    public function cerrarcomprobacion($id)
    {
      try {
        $valores = explode('&',$id);

        $comprobacion = \App\ComprobacionesCajaChica::
        where('cajachica_id',$valores[0])
        ->where('empresa',$valores[1])
        ->where('estado','!=','1')
        ->get();

        if (count($comprobacion) == 0) {
          $solicitud_caja_chica_up = \App\CajaChicaSolicitud::where('id',$valores[0])->first();
          $solicitud_caja_chica_up->estado = 9;
          $solicitud_caja_chica_up->save();

          $solicitud_caja_chica = \App\CajaChicaSolicitud::
          join('empleados AS e','e.id','solicitud_caja_chica.empleado_user_id')
          ->select('solicitud_caja_chica.folio',DB::raw("CONCAT(e.nombre,'-',e.ap_paterno) AS nombre_user"))
          ->where('solicitud_caja_chica.id',$valores[0])->first();


          $nombre_archivo = 'CajaChica-'.$solicitud_caja_chica->folio.'-'.$solicitud_caja_chica->nombre_user.'.xlsx';

          //SE DEBE CREAR EL EXCEL Y GUARDAR EN EL TEMPORAL
          Excel::store(new CajaChicaComprobacionExport($id), $nombre_archivo, 'temporal');

          //VIARIABLE DE NOMBRE DE CORRREO
          $correo = 'luisa.flores@conserflow.com';
          // $correo = 'jaime.martinez@conserflow.com';
          $mensaje = 'Revisión Comprobaciones Caja Chica '.$solicitud_caja_chica->folio.'-'.$solicitud_caja_chica->nombre_user;
          $data = [
            'mensaje' => $mensaje,
            'correo' => $correo,
            'nombre_archivo' => $nombre_archivo,
          ];
          //
          Mail::send('emails.cajachicac', $data, function($message) use ($data) {
            $core = $data['correo'];
            $mensaje = $data['mensaje'];
            // $folio = $data['solicitud_viaticos']['folio'];
            // $pdf = PDF::loadView('pdf.forvia',$data);
            $message->to($core, $mensaje)->subject($mensaje);
            $message->attach(public_path('temp/'.$data['nombre_archivo']));
            // $message->to('romerovelascogregorio@gmail.com', $mensaje)->subject($mensaje);
            $message->from('webmaster@conserflow.com','Conserflow');
            // $message->attachData($pdf->output(), $folio.'.pdf');
            // $message->attach('public/img/1.png');
          });

          //Eliminamos el teporal
          Storage::disk('temporal')->delete($nombre_archivo);
          return response()->json(['status' => true, 'estatus' => 1 , 'mensaje' => 'Cerrado correctamente']);
        //
        }else {
          return response()->json(['status' => true, 'estatus' => 2 ,'mensaje' => 'No se puede cerrar hasta finalizar las comprobaciones']);
        }

        // return response()->json($comprobacion);













        // return response()->json($edo_f);

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function validarcomprobacion()
    {
      try {
        $user = Auth::user();

        $comprobaciones = \App\ComprobacionesCajaChica::get();

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function pagosRegistrar(Request $request)
    {
      try {

        $PdfStore = '';

        if ($request->hasFile('comprobante') ) {

          //obtiene el nombre del archivo y su extension
          $FacturaNE = $request->file('comprobante')->getClientOriginalName();
          //Obtiene el nombre del archivo
          $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
          //obtiene la extension
          $FacturaExt = $request->file('comprobante')->getClientOriginalExtension();
          //nombre que se guarad en BD
          $PdfStore = 'PCajaChica'.uniqid().'.'.$FacturaExt;
          //Subida del archivo al servidor ftp
          Storage::disk('local')->put('Archivos/'.$PdfStore, fopen($request->file('comprobante'), 'r+'));


        }

        return response()->json(['status' => true]);


      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }

    }

    public function eliminarPago($id)
    {
      try {
        $pago = \App\PagoCajaChica::where('id',$id)->delete();
        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function revisionDep()
    {
      $user = Auth::user();
      $centro_costos = 0;

      /**
      ** CONDICONES
      ** 1 Almacen USUARIO_EMPLEADO => 2, 147, 3
      ** 2 Compras USUARIO_EMPLEADO => 6,7
      ** 3 TI USUARIO_EMPLEADO => 17
      **/
      $categoria = 0;
      if($user->empleado_id == 2 || $user->empleado_id == 147 || $user->empleado_id == 3 || $user->empleado_id == 119){
        $categoria = 1;
      }elseif ($user->empleado_id == 6 || $user->empleado_id == 7 || $user->empleado_id == 119) {
        $categoria = 2;
      }elseif ($user->empleado_id == 17 || $user->empleado_id == 119) {
        $categoria = 3;
      }

      // $comprobacioncajachica = \App\ComprobacionesCajaChica::
      // join('solicitud_caja_chica AS scc','scc.id','comprobaciones_caja_chica.cajachica_id')
      // ->select('comprobaciones_caja_chica.*','scc.id AS id_scc')
      // // ->where('comprobaciones_caja_chica.estado','1')
      // ->where('departamento',$categoria)
      // ->get();
      $comprobacion = \App\ComprobacionesCajaChica::
      join('gastos_xml_oc AS gx','gx.id','comprobaciones_caja_chica.factura_id')
      ->leftjoin('proyectos AS p','p.id','comprobaciones_caja_chica.centro_costos_id')
      ->select(
        'gx.*','p.nombre_corto',
        'comprobaciones_caja_chica.id AS ccc_id',
        'comprobaciones_caja_chica.empresa AS ccc_empresa',
        'comprobaciones_caja_chica.uuid AS ccc_uuid',
        'comprobaciones_caja_chica.comprobante',
        'comprobaciones_caja_chica.xml',
        'comprobaciones_caja_chica.estado AS ccc_estado'
        )
        ->where('departamento',$categoria)
        // ->where('cajachica_id',$valores[0])
        // ->where('empresa',$valores[1])
        ->orderBy('gx.id','DESC')
        ->get();

        $arreglo = [];
        $arreglo_final = [];
        $comprobados = 0;
        foreach ($comprobacion as $key => $value) {
          if ($value->ccc_estado == 1) {
            $comprobados ++;
          }

          $impuesto = \App\ImpuestosOCXml::where('gastos_xml_oc_id',$value->id)
          ->where('base',NULL)
          ->where('tipo','t')
          ->first();

          $retencion = \App\ImpuestosOCXml::where('gastos_xml_oc_id',$value->id)
          ->where('base',NULL)
          ->where('tipo','r')
          ->first();

          $concepto = \App\ConceptosOCXml::where('gastos_xml_oc_id',$value->id)->first();

          $arreglo [] = [
            'comprobacion' =>  $value,
            'impuestos' => $impuesto,
            'retencion' => $retencion,
            'concepto' => $concepto,
          ];
        }

        $arreglo_final [] =
        [
          'data' => $arreglo,
          'contador' =>$comprobados
        ];

        return response()->json($arreglo_final);
      // return response()->json($comprobacioncajachica);
    }

    public function validarMaterial($id)
    {
      try {
        $comprobacion = \App\ComprobacionesCajaChica::where('id',$id)->first();
        $comprobacion->estado = 1;
        $comprobacion->save();

        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }

    }

    public function rechazarMaterial(Request $request)
    {
      try {
        DB::beginTransaction();

        $comprobacion = \App\ComprobacionesCajaChica::where('id',$request->id)->first();
        $comprobacion->estado = 3;
        $comprobacion->save();

        /**
        *TIPO DE COMENTARIOS
        * 1- RECHAZO POR PARTE DE LOS DEPARTEMENTOS DIRIGIDOS
        * 2.- RECHAZO POR PARTE DEL AREA DE CONTABILIDAD LA MMONETO DE CERRAR LA CAJA Chica
        **/
        $comentarios_comprobacion_caja_chica = new \App\ComentariosCCC();
        $comentarios_comprobacion_caja_chica->comprobacion_id = $request->id;
        $comentarios_comprobacion_caja_chica->tipo = 1;
        $comentarios_comprobacion_caja_chica->comentario = $request->comentario;
        $comentarios_comprobacion_caja_chica->save();

        DB::commit();
        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        DB::rollBack();
        Utilidades::errors($e);
      }
    }

    public function getEmpleado()
    {
      try {
        $user = Auth::user();

        $empleado = DB::table('empleados')->where('id',$user->empleado_id)->first();

        $empleados = DB::table('empleados')->get();

        $arreglo = ['usuario' => $empleado, 'empleados' => $empleados];

        return response()->json($arreglo);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function getMotivoRechazo($id)
    {
      try {
        $comentarios_comprobacion_caja_chica = \App\ComentariosCCC::where('comprobacion_id',$id)
        ->orderBy('id','DESC')
        ->first();

        return response()->json($comentarios_comprobacion_caja_chica);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function guardarPago(Request $request)
    {
      try {
        $imageName = '';
        if ($request->comprobante != null) {
          $image_64 = $request->comprobante; // base64 encoded
          $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
          $replace = substr($image_64, 0, strpos($image_64, ',')+1);
          $image = str_replace($replace, '', $image_64);
          $image = str_replace(' ', '+', $image);
          $imageName = 'Pago'.uniqid().$extension;
          Storage::disk('local')->put('Archivos/'.$imageName, base64_decode($image));
        }

        $pago = new \App\PagoCajaChica();
        $pago->caja_chica_id = $request->solicitud_caja_chica_id;
        $pago->empresa = $request->empresa;
        $pago->fecha = $request->fecha;
        $pago->comprobante = $imageName;
        $pago->folio = $request->folio;
        $pago->cantidad = $request->cantidad;
        $pago->save();

        if ($request->empresa == 1) {
          $solicitud_cfw = CajaChicaSolicitud::
          join('empleados AS er','er.id','solicitud_caja_chica.empleado_revisa_id')
          ->join('empleados AS ea','ea.id','solicitud_caja_chica.empleado_autoriza_id')
          ->join('empleados AS eb','eb.id','solicitud_caja_chica.beneficiario_id')
          ->leftJoin('datos_bancarios_empleados AS dbe','dbe.id','solicitud_caja_chica.datos_bancos_beneficiario')
          ->join('catalogo_bancos AS b','b.id','dbe.banco_id')
          ->select('solicitud_caja_chica.*',
          DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_revisa"),
          DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_autoriza"),
          DB::raw("CONCAT(eb.nombre,' ',eb.ap_paterno,' ',eb.ap_materno) AS beneficiario"),
          'b.nombre AS nombre_banco'
          )
          ->where('solicitud_caja_chica.id',$request->solicitud_caja_chica_id)
          ->where('solicitud_caja_chica.empresa',$request->empresa)
          ->first();

          $this->enviarCorreo($solicitud_cfw,'Pago a Caja Chica',$solicitud_cfw->empleado_user_id);


        }elseif ($request->empresa == 2) {
          $solicitud_csct = CajaChicaSolicitudDBP::
          join('empleados AS er','er.id','solicitud_caja_chica.empleado_revisa_id')
          ->join('empleados AS ea','ea.id','solicitud_caja_chica.empleado_autoriza_id')
          ->join('empleados AS eb','eb.id','solicitud_caja_chica.beneficiario_id')
          ->leftJoin('datos_bancarios_empleados AS dbe','dbe.id','solicitud_caja_chica.datos_bancos_beneficiario')
          ->join('catalogo_bancos AS b','b.id','dbe.banco_id')
          ->select('solicitud_caja_chica.*',
          DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_revisa"),
          DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_autoriza"),
          DB::raw("CONCAT(eb.nombre,' ',eb.ap_paterno,' ',eb.ap_materno) AS beneficiario"),
          'b.nombre AS nombre_banco'
          )
          ->where('solicitud_caja_chica.id',$request->solicitud_caja_chica_id)
          ->where('solicitud_caja_chica.empresa',$request->empresa)
          ->first();

          $this->enviarCorreo($solicitud_csct,'Pago a Caja Chica',$solicitud_csct->empleado_user_id);

        }

        return response()->json($request);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }

    }



  }
