<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class Viaticos
{

  public function solicitud_consulta()
  {
    return  \App\SolicitudViaticos::leftJoin("empleados AS EA","EA.id","=","solicitud_viaticos.empleado_autoriza_id")
    ->join("empleados AS EE","EE.id","=","solicitud_viaticos.empleado_elabora_id")
    ->leftJoin("empleados AS ER","ER.id","=","solicitud_viaticos.empleado_revisa_id")
    ->leftJoin("empleados AS ES","ES.id","=","solicitud_viaticos.empleado_supervisor_id")
    ->join("proyectos AS P","P.id","=","solicitud_viaticos.proyecto_id")
    ->select("solicitud_viaticos.*","P.nombre_corto AS nombre_proyecto",
    DB::raw("CONCAT(EA.nombre,' ',EA.ap_paterno,' ',EA.ap_materno) AS nombre_autoriza"),
    DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS nombre_elabora"),
    DB::raw("CONCAT(ER.nombre,' ',ER.ap_paterno,' ',ER.ap_materno) AS nombre_revisa"),
    DB::raw("CONCAT(ES.nombre,' ',ES.ap_paterno,' ',ES.ap_materno) AS nombre_supervisor"));
  }

  public function solicitud_consulta_csct()
  {
    return  \App\SolicitudViaticosDBP::leftJoin("empleados AS EA","EA.id","=","solicitud_viaticos.empleado_autoriza_id")
    ->join("empleados AS EE","EE.id","=","solicitud_viaticos.empleado_elabora_id")
    ->leftJoin("empleados AS ER","ER.id","=","solicitud_viaticos.empleado_revisa_id")
    ->leftJoin("empleados AS ES","ES.id","=","solicitud_viaticos.empleado_supervisor_id")
    ->join("proyectos AS P","P.id","=","solicitud_viaticos.proyecto_id")
    ->select("solicitud_viaticos.*","P.nombre_corto AS nombre_proyecto",
    DB::raw("CONCAT(EA.nombre,' ',EA.ap_paterno,' ',EA.ap_materno) AS nombre_autoriza"),
    DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS nombre_elabora"),
    DB::raw("CONCAT(ER.nombre,' ',ER.ap_paterno,' ',ER.ap_materno) AS nombre_revisa"),
    DB::raw("CONCAT(ES.nombre,' ',ES.ap_paterno,' ',ES.ap_materno) AS nombre_supervisor"));
  }

  /**
  ** Utilizados en los vue table server
  **/
  public function solicitud_consulta_server()
  {
    return  \App\SolicitudViaticos::leftJoin("empleados AS EA","EA.id","=","solicitud_viaticos.empleado_autoriza_id")
    ->leftJoin("empleados AS EE","EE.id","=","solicitud_viaticos.empleado_elabora_id")
    ->leftJoin("empleados AS ER","ER.id","=","solicitud_viaticos.empleado_revisa_id")
    ->leftJoin("empleados AS ES","ES.id","=","solicitud_viaticos.empleado_supervisor_id")
    ->leftjoin("proyectos AS P","P.id","=","solicitud_viaticos.proyecto_id")
    ->leftJoin('totales_solicitud_viaticos AS tsv','tsv.solicitud_viatico_id','solicitud_viaticos.id')
    ->join('solicitud_viaticos_beneficiarios AS svb','svb.solicitud_viaticos_id','solicitud_viaticos.id')
    ->leftJoin("empleados AS EB","EB.id","svb.empleado_beneficiario_id")
    ->select("solicitud_viaticos.*","P.nombre_corto AS nombre_proyecto",'tsv.total','tsv.efectivo','tsv.transferencia',
    'svb.empleado_beneficiario_id',
    DB::raw("(CASE
                  WHEN svb.empleado_beneficiario_id = 0 THEN svb.beneficiario_externo
                  WHEN svb.empleado_beneficiario_id != 0 THEN CONCAT(EB.nombre,' ',EB.ap_paterno,' ',EB.ap_materno)
                  ELSE 'SuperAdmin'
                  END) AS beneficiario_nombre"),
    DB::raw("CONCAT(EA.nombre,' ',EA.ap_paterno,' ',EA.ap_materno) AS nombre_autoriza"),
    DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS nombre_elabora"),
    DB::raw("CONCAT(ER.nombre,' ',ER.ap_paterno,' ',ER.ap_materno) AS nombre_revisa"),
    DB::raw("CONCAT(ES.nombre,' ',ES.ap_paterno,' ',ES.ap_materno) AS nombre_supervisor"))
    ->orderBy('solicitud_viaticos.fecha_solicitud','DESC');
  }

  /**
  ** Utilizados en los vue table server
  **/
  public function solicitud_consulta_csct_server()
  {
    return  \App\SolicitudViaticosDBP::leftJoin("empleados AS EA","EA.id","=","solicitud_viaticos.empleado_autoriza_id")
    ->leftJoin("empleados AS EE","EE.id","=","solicitud_viaticos.empleado_elabora_id")
    ->leftJoin("empleados AS ER","ER.id","=","solicitud_viaticos.empleado_revisa_id")
    ->leftJoin("empleados AS ES","ES.id","=","solicitud_viaticos.empleado_supervisor_id")
    ->leftjoin("proyectos AS P","P.id","=","solicitud_viaticos.proyecto_id")
    ->leftJoin('totales_solicitud_viaticos AS tsv','tsv.solicitud_viatico_id','solicitud_viaticos.id')
    ->join('solicitud_viaticos_beneficiarios AS bv','bv.solicitud_viaticos_id','solicitud_viaticos.id')
    // ->leftJoin("empleados AS EB","EB.id","=","bv.empleado_beneficiario_id")
    ->select("solicitud_viaticos.*","P.nombre_corto AS nombre_proyecto",'tsv.total','tsv.efectivo','tsv.transferencia',
    'bv.empleado_beneficiario_id',
    // DB::raw("(CASE
    //               WHEN bv.empleado_beneficiario_id = 0 THEN bv.beneficiario_externo
    //               WHEN bv.empleado_beneficiario_id != 0 THEN CONCAT(EB.nombre,' ',EB.ap_paterno,' ',EB.ap_materno)
    //               ELSE 'SuperAdmin'
    //               END) AS beneficiario_nombre"),
    DB::raw("CONCAT(EA.nombre,' ',EA.ap_paterno,' ',EA.ap_materno) AS nombre_autoriza"),
    DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS nombre_elabora"),
    DB::raw("CONCAT(ER.nombre,' ',ER.ap_paterno,' ',ER.ap_materno) AS nombre_revisa"),
    DB::raw("CONCAT(ES.nombre,' ',ES.ap_paterno,' ',ES.ap_materno) AS nombre_supervisor"))
    ->orderBy('solicitud_viaticos.fecha_solicitud','DESC');
  }


  public function solicitud_ver($id,$user)
  {
    $solicitud_viaticos_respuesta = [];

    $solicitudes = $this->solicitud_empleado($id, $user);

    foreach ($solicitudes as $key => $solicitud) {
      $detalles_viaticos = \App\DetalleViatico::where('solicitud_viaticos_id','=',$solicitud->id)
      ->select(DB::raw("SUM(transferencia_electronica) AS transferencia"),
      DB::raw("SUM(efectivo) AS efectivo"),
      DB::raw("SUM(total) AS total"))->first();

      $solicitud_viaticos_respuesta [] = [
        'solicitud' => $solicitud,
        'detalles' => $detalles_viaticos,
      ];
    }

    return $solicitud_viaticos_respuesta;
  }

  public function solicitud_ver_csct($id,$user)
  {
    $solicitud_viaticos_respuesta = [];

    $solicitudes = $this->solicitud_empleado_csct($id, $user);

    foreach ($solicitudes as $key => $solicitud) {
      $detalles_viaticos = \App\DetalleViaticoCSCT::where('solicitud_viaticos_id','=',$solicitud->id)
      ->select(DB::raw("SUM(transferencia_electronica) AS transferencia"),
      DB::raw("SUM(efectivo) AS efectivo"),
      DB::raw("SUM(total) AS total"))->first();

      $solicitud_viaticos_respuesta [] = [
        'solicitud' => $solicitud,
        'detalles' => $detalles_viaticos,
      ];
    }

    return $solicitud_viaticos_respuesta;
  }

  public function solicitud_empleado($id, $user)
  {
    switch ($id) {
      case '2':
    $solicitud =  $this->solicitud_consulta()
      ->where('solicitud_viaticos.estado','=',$id)
      ->where('empleado_revisa_id','=',$user == null ? '0' : $user->empleado_id)
      ->get();
        break;

        case '3':
    $solicitud =  $this->solicitud_consulta()
        ->where('solicitud_viaticos.estado','=',$id)
        ->where('empleado_autoriza_id','=',$user == null ? '0' : $user->empleado_id)
        ->get();
          break;

    }
    return $solicitud;
  }

  public function solicitud_empleado_csct($id, $user)
  {
    switch ($id) {
      case '2':
    $solicitud =  $this->solicitud_consulta_csct()
      ->where('solicitud_viaticos.estado','=',$id)
      ->where('empleado_revisa_id','=',$user == null ? '0' : $user->empleado_id)
      ->get();
        break;

        case '3':
    $solicitud =  $this->solicitud_consulta_csct()
        ->where('solicitud_viaticos.estado','=',$id)
        ->where('empleado_autoriza_id','=',$user == null ? '0' : $user->empleado_id)
        ->get();
          break;

    }
    return $solicitud;
  }

  public function PersonalDetalles($id)
  {
    $personal_servicio_viaticos = [];
    $personal_servicio_viaticos = \App\PersonalDetalles::
    join('empleados AS E','E.id','=','personal_detalles.empleado_id')
    ->select('personal_detalles.*', DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS nombre_empleado"))
    ->where('personal_detalles.solicitud_viaticos_id','=',$id)
    ->get();
    return $personal_servicio_viaticos;
  }

  public function PersonalDetallesCSCT($id)
  {
    $personal_servicio_viaticos = [];
    $personal_servicio_viaticos = \App\PersonalDetallesDBP::
    join('empleados AS E','E.id','=','personal_detalles.empleado_id')
    ->select('personal_detalles.*', DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS nombre_empleado"))
    ->where('personal_detalles.solicitud_viaticos_id','=',$id)
    ->get();
    return $personal_servicio_viaticos;
  }

  public function VehiculosItinerarioViaticos($id)
  {
    $vehiculos_viaticos = [];
    $vehiculos_viaticos = \App\VehiculosItinerarioViaticos::
    join('empleados AS EO','EO.id','=','vehiculos_itinerario_viaticos.empleado_operador_id')
    ->where('vehiculos_itinerario_viaticos.solicitud_viaticos_id','=',$id)
    ->select('vehiculos_itinerario_viaticos.*',
    DB::raw("CONCAT(EO.nombre,' ',EO.ap_paterno,' ',EO.ap_materno) AS nombre_operador"))->get();

    return $vehiculos_viaticos;
  }

  public function VehiculosItinerarioViaticosCSCT($id)
  {
    $vehiculos_viaticos = [];
    $vehiculos_viaticos = \App\VehiculosItinerarioViaticosDBP::
    join('empleados AS EO','EO.id','=','vehiculos_itinerario_viaticos.empleado_operador_id')
    ->where('vehiculos_itinerario_viaticos.solicitud_viaticos_id','=',$id)
    ->select('vehiculos_itinerario_viaticos.*',
    DB::raw("CONCAT(EO.nombre,' ',EO.ap_paterno,' ',EO.ap_materno) AS nombre_operador"))->get();

    return $vehiculos_viaticos;
  }


  public function BeneficiarioViatico($id)
  {
    $beneficiario_viaticos  = [];
    $beneficiario_viaticos = \App\BeneficiarioViatico::
    leftJoin('empleados AS EB','EB.id','=','beneficiario_viatico.empleado_beneficiario_id')
    ->leftJoin('datos_bancarios_empleados AS DBE','DBE.id','=','beneficiario_viatico.datos_bancarios_empleado_id')
    ->leftJoin('catalogo_bancos AS CB','CB.id','=','DBE.banco_id')
    ->select('beneficiario_viatico.*',  DB::raw("CONCAT(EB.nombre,' ',EB.ap_paterno,' ',EB.ap_materno) AS nombre_beneficiario"),
    'DBE.numero_cuenta','DBE.numero_tarjeta','CB.nombre AS nombre_banco')
    ->where('beneficiario_viatico.solicitud_viaticos_id','=',$id)->get();

    return $beneficiario_viaticos;
  }

  public function BeneficiarioViaticoCSCT($id)
  {
    $beneficiario_viaticos  = [];
    $beneficiario_viaticos = \App\BeneficiarioViaticoDBP::
    leftJoin('empleados AS EB','EB.id','=','beneficiario_viatico.empleado_beneficiario_id')
    ->leftJoin('datos_bancarios_empleados AS DBE','DBE.id','=','beneficiario_viatico.datos_bancarios_empleado_id')
    ->leftJoin('catalogo_bancos AS CB','CB.id','=','DBE.banco_id')
    ->select('beneficiario_viatico.*',  DB::raw("CONCAT(EB.nombre,' ',EB.ap_paterno,' ',EB.ap_materno) AS nombre_beneficiario"),
    'DBE.numero_cuenta','DBE.numero_tarjeta','CB.nombre AS nombre_banco')
    ->where('beneficiario_viatico.solicitud_viaticos_id','=',$id)->get();

    return $beneficiario_viaticos;
  }

  public function DetalleViatico($id)
  {
    $detalles_viaticos = [];

    $detalles_viaticos = \App\DetalleViatico::where('solicitud_viaticos_id','=',$id)
    ->select(DB::raw("SUM(transferencia_electronica) AS transferencia"),
    DB::raw("SUM(efectivo) AS efectivo"),
    DB::raw("SUM(total) AS total"))->first();

    return $detalles_viaticos;

  }

  public function DetalleViaticoCSCT($id)
  {
    $detalles_viaticos = [];

    $detalles_viaticos = \App\DetalleViaticoCSCT::where('solicitud_viaticos_id','=',$id)
    ->select(DB::raw("SUM(transferencia_electronica) AS transferencia"),
    DB::raw("SUM(efectivo) AS efectivo"),
    DB::raw("SUM(total) AS total"))->first();

    return $detalles_viaticos;

  }

  public function partidasPagos($id)
  {
    $pagos = [];
   $pagos =  \App\PartidasViaticosPagos::select(DB::raw("SUM(importe_efectivo) AS total_i_efectivo"),DB::raw("SUM(importe_te) AS total_i_te"),
    DB::raw("SUM(importe_efectivo + importe_te) AS total"))
    ->where('solicitud_viaticos_id','=',$id)->first();
    return $pagos;
  }

  public function partidasPagosCSCT($id)
  {

   $pagos = [];
   $pagos =  \App\PartidasViaticosPagosDBP::select(DB::raw("SUM(importe_efectivo) AS total_i_efectivo"),DB::raw("SUM(importe_te) AS total_i_te"),
    DB::raw("SUM(importe_efectivo + importe_te) AS total"))
    ->where('solicitud_viaticos_id','=',$id)->first();
    return $pagos;

  }

  public function DatosCorreos($id, $edo)
  {
    $beneficiario_viatico = [];
    $solicitud_viaticos = $this->solicitud_consulta()
    ->where('solicitud_viaticos.id',$id)
    ->first();

    $empleados = \App\Empleado::leftJoin('users AS CC', 'empleados.id', '=', 'CC.empleado_id')
    ->where('empleados.id',$solicitud_viaticos->empleado_elabora_id)->select('empleados.*','CC.email as correo_electronico')->first();
    $empleado = $this->empleado($id,$edo);

    $detalle_viatico = \App\DetalleViatico::where('solicitud_viaticos_id','=',$id)->get();

    $beneficiario = $this->BeneficiarioViatico($id);
    if (count($beneficiario) != 0 ) {
      $beneficiario_viatico = $beneficiario;
    }
    $tet = 0;$et = 0 ; $tt = 0;
    foreach ($detalle_viatico as $key => $value) {
      $tet += $value->transferencia_electronica;
      $et += $value->efectivo;
      $tt += $value->total;
    }
    $vehiculoiv = $this->VehiculosItinerarioViaticos($id);
    $personalsv = $this->PersonalDetalles($id);

    $data =[
      'nombre' => $empleados->nombre.' '.$empleados->ap_paterno.' '.$empleados->ap_materno,
      'correo_electronico' => $empleado->correo_electronico,
      'solicitud_viaticos' => $solicitud_viaticos,
      'beneficiario_viatico' => $beneficiario_viatico,
      'detalle_viatico' => $detalle_viatico,
      'tet' => $tet,
      'et' => $et,
      'tt' => $tt,
      'vehiculoiv' => $vehiculoiv,
      'personalsv' => $personalsv,
    ];
    return $data ;

  }

  public function DatosCorreosCSCT($id, $edo)
  {
    $beneficiario_viatico = [];
    $solicitud_viaticos = $this->solicitud_consulta_csct()
    ->where('solicitud_viaticos.id',$id)
    ->first();

    $empleados = \App\EmpleadoDBP::leftJoin('users AS CC', 'empleados.id', '=', 'CC.empleado_id')
    ->where('empleados.id',$solicitud_viaticos->empleado_elabora_id)->select('empleados.*','CC.email as correo_electronico')->first();

    $empleado = $this->empleadoCSCT($id,$edo);

    $detalle_viatico = \App\DetalleViaticoCSCT::where('solicitud_viaticos_id','=',$id)->get();

    $beneficiario = $this->BeneficiarioViaticoCSCT($id);
    if (count($beneficiario) != 0 ) {
      $beneficiario_viatico = $beneficiario;
    }
    $tet = 0;$et = 0 ; $tt = 0;
    foreach ($detalle_viatico as $key => $value) {
      $tet += $value->transferencia_electronica;
      $et += $value->efectivo;
      $tt += $value->total;
    }
    $vehiculoiv = $this->VehiculosItinerarioViaticosCSCT($id);
    $personalsv = $this->PersonalDetallesCSCT($id);

    $data =[
      'nombre' => $empleados->nombre.' '.$empleados->ap_paterno.' '.$empleados->ap_materno,
      'correo_electronico' => $empleado->correo_electronico,
      'solicitud_viaticos' => $solicitud_viaticos,
      'beneficiario_viatico' => $beneficiario_viatico,
      'detalle_viatico' => $detalle_viatico,
      'tet' => $tet,
      'et' => $et,
      'tt' => $tt,
      'vehiculoiv' => $vehiculoiv,
      'personalsv' => $personalsv,
    ];
    return $data ;

  }

  public function empleado($id, $edo)
  {
    $solicitud_viaticos = $this->solicitud_consulta()
    ->where('solicitud_viaticos.id',$id)
    ->first();
    switch ($edo) {
      case '2':
      $empleado = \App\Empleado::leftJoin('users AS CC', 'empleados.id', '=', 'CC.empleado_id')
      ->where('empleados.id',$solicitud_viaticos->empleado_revisa_id)->select('empleados.*','CC.email as correo_electronico')->first();
        break;


      case '3':
      $empleado = \App\Empleado::leftJoin('users AS CC', 'empleados.id', '=', 'CC.empleado_id')
      ->where('empleados.id',$solicitud_viaticos->empleado_autoriza_id)->select('empleados.*','CC.email as correo_electronico')->first();
        break;

      case '0':
      $empleado = \App\Empleado::leftJoin('users AS CC', 'empleados.id', '=', 'CC.empleado_id')
      ->where('empleados.id',$solicitud_viaticos->empleado_elabora_id)->select('empleados.*','CC.email as correo_electronico')->first();
        break;

        case '4':
        $empleado = \App\Empleado::leftJoin('users AS CC', 'empleados.id', '=', 'CC.empleado_id')
        ->where('empleados.id',$solicitud_viaticos->empleado_elabora_id)->select('empleados.*','CC.email as correo_electronico')->first();
          break;
    }
    return $empleado;
  }

  public function empleadoCSCT($id, $edo)
  {
    $solicitud_viaticos = $this->solicitud_consulta()
    ->where('solicitud_viaticos.id',$id)
    ->first();
    switch ($edo) {
      case '2':
      $empleado = \App\Empleado::leftJoin('users AS CC', 'empleados.id', '=', 'CC.empleado_id')
      ->where('empleados.id',$solicitud_viaticos->empleado_revisa_id)->select('empleados.*','CC.email as correo_electronico')->first();
        break;


      case '3':
      $empleado = \App\Empleado::leftJoin('users AS CC', 'empleados.id', '=', 'CC.empleado_id')
      ->where('empleados.id',$solicitud_viaticos->empleado_autoriza_id)->select('empleados.*','CC.email as correo_electronico')->first();
        break;

      case '0':
      $empleado = \App\Empleado::leftJoin('users AS CC', 'empleados.id', '=', 'CC.empleado_id')
      ->where('empleados.id',$solicitud_viaticos->empleado_elabora_id)->select('empleados.*','CC.email as correo_electronico')->first();
        break;

        case '4':
        $empleado = \App\Empleado::leftJoin('users AS CC', 'empleados.id', '=', 'CC.empleado_id')
        ->where('empleados.id',$solicitud_viaticos->empleado_elabora_id)->select('empleados.*','CC.email as correo_electronico')->first();
          break;
    }
    return $empleado;
  }
}
