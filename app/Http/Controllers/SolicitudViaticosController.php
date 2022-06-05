<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Viaticos;
use Mail;
use Barryvdh\DomPDF\Facade as PDF;
use App\Proyecto;
use App\ProyectoDBP;
use App\Empleado;
use App\EmpleadoDBP;
use Illuminate\Support\Facades\Config;

class SolicitudViaticosController extends Controller
{

    protected $viatico;

    public function __construct(Viaticos $viatico)
    {
        $this->viaticos = $viatico;
    }

    public function unique_multidim_array($array) {
        try {

            $temp_array = array();
            $i = 0;
            $key_array = array();
            foreach($array as $val) {
                if (!in_array($val->id, $key_array)) {
                    $key_array[$i] = $val->id;
                    $temp_array[$i] = $val;
                }
                $i++;
            }
            return $temp_array;
        } catch (\Throwable $e) {
            dd($e);
        }

    }

    public function ObtenerViaticosConser($id_emp)
    {
        $db=Config::get("database.connections.mysql.database");
        $tabla_viaticos=$db.".solicitud_viaticos sv ";
        $query="SELECT sv.*, P.nombre_corto as nombre_proyecto,
        CONCAT_WS(' ',EA.nombre,EA.ap_paterno,EA.ap_materno) AS nombre_autoriza,
        CONCAT_WS(' ',EE.nombre,EE.ap_paterno,EE.ap_materno) AS nombre_elabora,
        CONCAT_WS(' ',ER.nombre,ER.ap_paterno,ER.ap_materno) AS nombre_revisa,
        CONCAT_WS(' ',ES.nombre,ES.ap_paterno,ES.ap_materno) AS nombre_supervisor
        from ".$tabla_viaticos."
        left join permisos_viaticos pv on pv.empleado_permitido_id =".$id_emp."
        left join empleados as EE on EE.id = sv.empleado_elabora_id
        left join empleados as EA on EA.id = sv.empleado_autoriza_id
        left join empleados as ER on ER.id = sv.empleado_revisa_id
        left join empleados as ES on ES.id = sv.empleado_supervisor_id
        join proyectos as P on P.id = sv.proyecto_id
        where (sv.empleado_user_id =".$id_emp."
        or sv.empleado_user_id =pv.propietario_id) ORDER BY sv.fecha_solicitud DESC;";
        $solicitudes = DB::select($query);
        $d = $this->unique_multidim_array($solicitudes);
        return $d;
    }

    public function ObtenerViaticosCsct($id_emp)
    {
        $db=Config::get("database.connections.csct.database");
        $tabla_viaticos=$db.".solicitud_viaticos sv ";
        // dd($db);
        $query="SELECT sv.*, P.nombre_corto as nombre_proyecto,
        CONCAT_WS(' ',EA.nombre,EA.ap_paterno,EA.ap_materno) AS nombre_autoriza,
        CONCAT_WS(' ',EE.nombre,EE.ap_paterno,EE.ap_materno) AS nombre_elabora,
        CONCAT_WS(' ',ER.nombre,ER.ap_paterno,ER.ap_materno) AS nombre_revisa,
        CONCAT_WS(' ',ES.nombre,ES.ap_paterno,ES.ap_materno) AS nombre_supervisor
        from ".$tabla_viaticos."
        left join permisos_viaticos pv on pv.empleado_permitido_id =".$id_emp."
        left join empleados as EE on EE.id = sv.empleado_elabora_id
        left join empleados as EA on EA.id = sv.empleado_autoriza_id
        left join empleados as ER on ER.id = sv.empleado_revisa_id
        left join empleados as ES on ES.id = sv.empleado_supervisor_id
        join ".$db.".proyectos as P on P.id = sv.proyecto_id
        where (sv.empleado_user_id =".$id_emp."
        or sv.empleado_user_id =pv.propietario_id) ORDER BY sv.fecha_solicitud DESC;";
        $solicitudes=DB::select($query);
        $d = $this->unique_multidim_array($solicitudes);

        return $d;
    }
    /**
    * [index Busqueda especifica de las partidas correspondientes ]
    * @return Response           [description]
    */
    public function getConserflow()
    {
        try
        {
            $user = Auth::user();
            $solicitud_viaticos_respuesta = [];
            $beneficiario_viaticos = [];

            $solicitud_viaticos = $this->ObtenerViaticosConser($user->empleado_id);

            foreach ($solicitud_viaticos as $key => $solicitud) {

                $detalles_viaticos = $this->viaticos->DetalleViatico($solicitud->id);
                $detalles_listado = \App\DetalleViatico::where('solicitud_viaticos_id','=',$solicitud->id)
                ->select('detalle_viaticos.*')->get();

                $beneficiario_viaticos = $this->viaticos->BeneficiarioViatico($solicitud->id);
                $vehiculos_viaticos = $vehiculos_viaticos = $this->viaticos->VehiculosItinerarioViaticos($solicitud->id);
                $personal_servicio_viaticos = $this->viaticos->PersonalDetalles($solicitud->id);
                $comprobacion = DB::table('viaticos')->select(DB::raw("SUM(gastos_comprobados_deducibles) AS total"))
                ->where('solicitud_viaticos_id',$solicitud->id)->first();

                // code...
                $solicitud_viaticos_respuesta [] = [
                    'solicitud' => $solicitud,
                    'detalle' => $detalles_viaticos,
                    'detalles_listado' => $detalles_listado,
                    'beneficiarios' => $beneficiario_viaticos,
                    'vehiculo' => $vehiculos_viaticos,
                    'empleados' => $personal_servicio_viaticos,
                    'comprobacion' => $comprobacion,
                ];
            }
            return response()->json($solicitud_viaticos_respuesta);
        } catch (\Throwable $e) {
            Utilidades::errors($e);
        }

    }

    /**
    * [index Busqueda especifica de las partidas correspondientes ]
    * @return Response           [description]
    */
    public function getCSCT()
    {
        try {
            $user = Auth::user();
            $empleado_cfw = \App\Empleado::where('id',$user->empleado_id)->select('nombre','ap_paterno','ap_materno')->first();
            $empleado_csct = \App\EmpleadoDBP::where('nombre',$empleado_cfw->nombre)->where('ap_paterno',$empleado_cfw->ap_paterno)->where('ap_materno',$empleado_cfw->ap_materno)->first();
            $solicitud_viaticos_respuesta = [];
            $beneficiario_viaticos = [];
            $solicitud_viaticos=$this->ObtenerViaticosCsct($empleado_csct->id);

            foreach ($solicitud_viaticos as $key => $solicitud) {
                $detalles_viaticos = $this->viaticos->DetalleViaticoCSCT($solicitud->id);
                $detalles_listado = \App\DetalleViaticoCSCT::where('solicitud_viaticos_id','=',$solicitud->id)
                ->select('detalle_viaticos.*')->get();

                $beneficiario_viaticos = $this->viaticos->BeneficiarioViaticoCSCT($solicitud->id);
                $vehiculos_viaticos = $vehiculos_viaticos = $this->viaticos->VehiculosItinerarioViaticosCSCT($solicitud->id);
                $personal_servicio_viaticos = $this->viaticos->PersonalDetallesCSCT($solicitud->id);
                // code...
                $solicitud_viaticos_respuesta [] = [
                    'solicitud' => $solicitud,
                    'detalle' => $detalles_viaticos,
                    'detalles_listado' => $detalles_listado,
                    'beneficiarios' => $beneficiario_viaticos,
                    'vehiculo' => $vehiculos_viaticos,
                    'empleados' => $personal_servicio_viaticos,
                ];
            }
            return response()->json($solicitud_viaticos_respuesta);
        } catch (\Throwable $e) {
            Utilidades::errors($e);
        }
    }

    public static function crearFolioViaticos($proyecto_id,$empresa)
    {
        try {

            $folio = "";
            $numero = 0;
            $numero_c = 0;
            if ($empresa == 1) {
                $proyecto = \App\Proyecto::select('id','nombre_corto')->where('id', '=', $proyecto_id)->first();

                $numero_solicitudes = \App\SolicitudViaticos::select('folio')
                ->where('proyecto_id','=',$proyecto_id)
                ->where('eliminado','0')
                ->orderBy('folio','DESC')
                ->first();

            }elseif ($empresa == 2) {
                $proyecto = \App\ProyectoDBP::select('id','nombre_corto')->where('id', '=', $proyecto_id)->first();
                $numero_solicitudes = \App\SolicitudViaticosDBP::select('folio')
                ->where('proyecto_id','=',$proyecto_id)
                ->where('eliminado','0')
                ->orderBy('folio','DESC')
                ->first();

            }

            if (isset($numero_solicitudes) == false) {
                $numero_c = 1;
            }else {
                $valores = explode('-',$numero_solicitudes);
                $numero_c = (int) end($valores) + 1;
            }

            $numero = $numero_c == 1 ? 1 : ($numero_c);
            // dd($numero);
            $folio = strtoupper($proyecto->nombre_corto).'-'.str_pad($numero, 3, "0", STR_PAD_LEFT);

            return $folio;
        } catch (\Throwable $e) {
            Utilidades::errors($e);
        }
    }
    /**
    * [store Guarda un registro en el modelo SolicitudViaticos ]
    * @param Request
    * @return Response           [description]
    */
    public function store(Request $request)
    {
        try {
            if ($request->empresa == 1) {
                // code...
                $user = Auth::user();

                $solicitud_viaticos = new \App\SolicitudViaticos();
                $folio  = $this->crearFolioViaticos($request->proyecto_id,$request->empresa);
                $request->merge(['folio' => $folio,'empleado_user_id' => $user->empleado_id == null ? $user->id : $user->empleado_id]);
                $solicitud_viaticos->fill($request->all());
                $solicitud_viaticos->save();

                $this->beneficiarios_llenar($solicitud_viaticos, $request, $request->empresa);

                if($request->detalles_listado != null){
                    for ($i=0; $i < count($request->detalles_listado['efectivo']); $i++) {

                        $this->llenardetallesViaticos(
                            $solicitud_viaticos,
                            $request->detalles_listado['efectivo'][$i],
                            $request->detalles_listado['tranferencia'][$i],
                            $request->detalles_listado['conceptos'][$i],
                            $request->tipo);
                        }
                    }

                    if (count($request->vehiculos_itinerario_viaticos) != 0) {
                        for ($i=0; $i < count($request->vehiculos_itinerario_viaticos) ; $i++) {
                            $this->llenarVehiculosItinerarioViaticos($solicitud_viaticos->id,
                            $request->vehiculos_itinerario_viaticos[$i]['unidad'],
                            $request->vehiculos_itinerario_viaticos[$i]['km_inicial'],
                            $request->vehiculos_itinerario_viaticos[$i]['empleado_operador_id']);
                        }
                    }

                    if (count($request->personal_servicio_viaticos_id) != 0) {
                        for ($i=0; $i < count($request->personal_servicio_viaticos_id) ; $i++) {
                            $this->llenarpersonalServicioViaticos(
                                $solicitud_viaticos->id,
                                $request->personal_servicio_viaticos_id[$i]['id']
                            );
                        }
                    }

                    return response()->json(
                        array('status' => true)
                    );
                }elseif ($request->empresa == 2) {
                    $user = Auth::user();

                    $empleado_cfw = \App\Empleado::where('id',$user->empleado_id)->select('nombre','ap_paterno','ap_materno')->first();
                    $empleado_csct = \App\EmpleadoDBP::where('nombre',$empleado_cfw->nombre)->where('ap_paterno',$empleado_cfw->ap_paterno)->where('ap_materno',$empleado_cfw->ap_materno)->first();

                    $solicitud_viaticos = new \App\SolicitudViaticosDBP();
                    $folio  = $this->crearFolioViaticos($request->proyecto_id,$request->empresa);
                    $request->merge(['folio' => $folio,'empleado_user_id' => $empleado_csct->id == null ? $user->id : $empleado_csct->id]);
                    $solicitud_viaticos->fill($request->all());
                    $solicitud_viaticos->save();

                    $this->beneficiarios_llenar($solicitud_viaticos, $request, $request->empresa);

                    if($request->detalles_listado != null){
                        for ($i=0; $i < count($request->detalles_listado['efectivo']); $i++) {
                            $this->llenardetallesViaticosCSCT(
                                $solicitud_viaticos,
                                $request->detalles_listado['efectivo'][$i],
                                $request->detalles_listado['tranferencia'][$i],
                                $request->detalles_listado['conceptos'][$i],
                                $request->tipo);
                            }
                        }

                        if (count($request->vehiculos_itinerario_viaticos) != 0) {
                            for ($i=0; $i < count($request->vehiculos_itinerario_viaticos) ; $i++) {
                                $this->llenarVehiculosItinerarioViaticosCSCT($solicitud_viaticos->id,
                                $request->vehiculos_itinerario_viaticos[$i]['unidad'],
                                $request->vehiculos_itinerario_viaticos[$i]['km_inicial'],
                                $request->vehiculos_itinerario_viaticos[$i]['empleado_operador_id']);
                            }
                        }

                        if (count($request->personal_servicio_viaticos_id) != 0) {
                            for ($i=0; $i < count($request->personal_servicio_viaticos_id) ; $i++) {
                                $this->llenarpersonalServicioViaticosCSCT(
                                    $solicitud_viaticos->id,
                                    $request->personal_servicio_viaticos_id[$i]['id']
                                );
                            }
                        }

                        return response()->json(
                            array('status' => true)
                        );
                    }
                } catch (\Throwable $e) {
                    Utilidades::errors($e);
                }


            }

            /**
            * [update actualiza los registro del modelo SolicitudViaticos ]
            * @param Request
            * @param Int
            * @return Response           [description]
            */
            public function update(Request $request, $id)
            {



                if ($request->empresa == 1) {

                    $solicitud_viaticos = \App\SolicitudViaticos::findOrFail($request->id);
                    $solicitud_viaticos->fill($request->all());
                    $solicitud_viaticos->save();
                    //se vacian los registros existentes para poder agregar los nuevos no se busca ni actualiza por el tamanio de la consulta
                    \App\BeneficiarioViatico::where([['solicitud_viaticos_id', '=',  $solicitud_viaticos->id],])->delete();
                    \App\DetalleViatico::where([['solicitud_viaticos_id', '=',  $solicitud_viaticos->id],])->delete();
                    \App\VehiculosItinerarioViaticos::where([['solicitud_viaticos_id', '=',  $solicitud_viaticos->id],])->delete();
                    \App\PersonalDetalles::where([['solicitud_viaticos_id', '=',  $solicitud_viaticos->id],])->delete();

                    $this->beneficiarios_llenar($solicitud_viaticos, $request, $request->empresa);

                    if($request->detalles_listado != null){
                        for ($i=0; $i < count($request->detalles_listado['efectivo']); $i++) {

                            $this->llenardetallesViaticos(
                                $solicitud_viaticos,
                                $request->detalles_listado['efectivo'][$i],
                                $request->detalles_listado['tranferencia'][$i],
                                $request->detalles_listado['conceptos'][$i],
                                $request->tipo);
                            }
                        }

                        if (count($request->vehiculos_itinerario_viaticos) != 0) {
                            for ($i=0; $i < count($request->vehiculos_itinerario_viaticos) ; $i++) {
                                $this->llenarVehiculosItinerarioViaticos($solicitud_viaticos->id,
                                $request->vehiculos_itinerario_viaticos[$i]['unidad'],
                                $request->vehiculos_itinerario_viaticos[$i]['km_inicial'],
                                $request->vehiculos_itinerario_viaticos[$i]['empleado_operador_id']);
                            }
                        }

                        if (count($request->personal_servicio_viaticos_id) != 0) {
                            for ($i=0; $i < count($request->personal_servicio_viaticos_id) ; $i++) {
                                $this->llenarpersonalServicioViaticos(
                                    $solicitud_viaticos->id,
                                    $request->personal_servicio_viaticos_id[$i]['id']
                                );
                            }
                        }

                        return response()->json(
                            array('status' => true)
                        );
                    }elseif ($request->empresa == 2) {
                        $solicitud_viaticos = \App\SolicitudViaticosDBP::findOrFail($request->id);
                        $solicitud_viaticos->fill($request->all());
                        $solicitud_viaticos->save();

                        \App\BeneficiarioViaticoDBP::where([['solicitud_viaticos_id', '=',  $solicitud_viaticos->id],])->delete();
                        \App\DetalleViaticoCSCT::where([['solicitud_viaticos_id', '=',  $solicitud_viaticos->id],])->delete();
                        \App\VehiculosItinerarioViaticosDBP::where([['solicitud_viaticos_id', '=',  $solicitud_viaticos->id],])->delete();
                        \App\PersonalDetallesDBP::where([['solicitud_viaticos_id', '=',  $solicitud_viaticos->id],])->delete();

                        $this->beneficiarios_llenar($solicitud_viaticos, $request, $request->empresa);

                        if($request->detalles_listado != null){
                            for ($i=0; $i < count($request->detalles_listado['efectivo']); $i++) {
                                $this->llenardetallesViaticosCSCT(
                                    $solicitud_viaticos,
                                    $request->detalles_listado['efectivo'][$i],
                                    $request->detalles_listado['tranferencia'][$i],
                                    $request->detalles_listado['conceptos'][$i],
                                    $request->tipo);
                                }
                            }

                            if (count($request->vehiculos_itinerario_viaticos) != 0) {
                                for ($i=0; $i < count($request->vehiculos_itinerario_viaticos) ; $i++) {
                                    $this->llenarVehiculosItinerarioViaticosCSCT($solicitud_viaticos->id,
                                    $request->vehiculos_itinerario_viaticos[$i]['unidad'],
                                    $request->vehiculos_itinerario_viaticos[$i]['km_inicial'],
                                    $request->vehiculos_itinerario_viaticos[$i]['empleado_operador_id']);
                                }
                            }

                            if (count($request->personal_servicio_viaticos_id) != 0) {
                                for ($i=0; $i < count($request->personal_servicio_viaticos_id) ; $i++) {
                                    $this->llenarpersonalServicioViaticosCSCT(
                                        $solicitud_viaticos->id,
                                        $request->personal_servicio_viaticos_id[$i]['id']
                                    );
                                }
                            }

                            return response()->json(
                                array('status' => true)
                            );
                        }

                    }

                    /**
                    * [llenarBeneficiarios inserta un registro en el modelo BeneficiarioViatico ]
                    * @param Object
                    * @param Int
                    * @param Int
                    * @return Status           [description]
                    */
                    public function llenarBeneficiarios($solicitud_viaticos, $request, $empresa)
                    {
                        try {

                            if ($empresa == 1) {
                                $beneficiario_viaticos = new \App\BeneficiarioViatico();
                            }elseif ($empresa == 2) {
                                $beneficiario_viaticos = new \App\BeneficiarioViaticoDBP();
                            }
                            $beneficiario_viaticos->solicitud_viaticos_id = $solicitud_viaticos->id;//
                            if(gettype($request['id']) === 'array' || gettype($request['id']) === 'object'){
                                //interno
                                $beneficiario_viaticos->empleado_beneficiario_id = $request['id']['id'];//
                                $beneficiario_viaticos->datos_bancarios_empleado_id = $request['dbemp_id'];///pendiente
                                $beneficiario_viaticos->banco_nombre = $request['banco'];//n
                                $beneficiario_viaticos->tarjeta = $request['tarjeta'];//n
                                $beneficiario_viaticos->clabe = $request['clave'];//n
                                $beneficiario_viaticos->cuenta = $request['cuenta'];//n
                            }else {
                                //externo
                                $beneficiario_viaticos->empleado_beneficiario_id = 0;//
                                $beneficiario_viaticos->beneficiario_externo = $request['id'];//n
                                $beneficiario_viaticos->datos_bancarios_empleado_id = 0;
                                $beneficiario_viaticos->banco_nombre = $request['dbemp_id'];//n
                                $beneficiario_viaticos->tarjeta = $request['tarjeta'];//n
                                $beneficiario_viaticos->clabe = $request['clave'];//n
                                $beneficiario_viaticos->cuenta = $request['cuenta'];//n

                            }
                            $beneficiario_viaticos->save();
                            return true;
                        } catch (\Throwable $e) {
                            Utilidades::errors($e);
                        }
                    }

                    /**
                    * [llenarBeneficiarios inserta un registro en el modelo BeneficiarioViatico ]
                    * @param Object
                    * @param Int
                    * @param Int
                    * @param Int
                    * @return Status           [description]
                    */
                    public function llenardetallesViaticos($solicitud_viaticos, $efectivo, $transferencia, $i, $tipo)
                    {
                        try {
                            if ($tipo === '0' || $tipo == 0) {
                                $detalles_viaticos = new \App\DetalleViatico();
                                $detalles_viaticos->solicitud_viaticos_id = $solicitud_viaticos->id;
                                $detalles_viaticos->catalogo_conceptos_viaticos_id = 0;
                                $detalles_viaticos->transferencia_electronica = $transferencia;
                                $detalles_viaticos->efectivo = $efectivo;
                                $detalles_viaticos->total = ($efectivo + $transferencia);
                                $detalles_viaticos->catalogo_concepto_viaticos = $i;
                                $detalles_viaticos->save();
                            }else {
                                $detalles_viaticos = new \App\DetalleViatico();
                                $detalles_viaticos->solicitud_viaticos_id = $solicitud_viaticos->id;
                                $detalles_viaticos->catalogo_conceptos_viaticos_id = $i;
                                $detalles_viaticos->transferencia_electronica = $transferencia;
                                $detalles_viaticos->efectivo = $efectivo;
                                $detalles_viaticos->total = ($efectivo + $transferencia);
                                $detalles_viaticos->save();
                            }
                            return true;
                        } catch (\Exception $e) {
                            Utilidades::errors($e);
                        }
                    }

                    /**
                    * [llenarBeneficiarios inserta un registro en el modelo BeneficiarioViatico ]
                    * @param Object
                    * @param Int
                    * @param Int
                    * @param Int
                    * @return Status           [description]
                    */
                    public function llenardetallesViaticosCSCT($solicitud_viaticos, $efectivo, $transferencia, $i, $tipo)
                    {
                        try {
                            if ($tipo === '0' || $tipo == 0) {
                                $detalles_viaticos = new \App\DetalleViaticoCSCT();
                                $detalles_viaticos->solicitud_viaticos_id = $solicitud_viaticos->id;
                                $detalles_viaticos->catalogo_conceptos_viaticos_id = 0;
                                $detalles_viaticos->transferencia_electronica = $transferencia;
                                $detalles_viaticos->efectivo = $efectivo;
                                $detalles_viaticos->total = ($efectivo + $transferencia);
                                $detalles_viaticos->catalogo_concepto_viaticos = $i;
                                $detalles_viaticos->save();
                            }else {
                                $detalles_viaticos = new \App\DetalleViaticoCSCT();
                                $detalles_viaticos->solicitud_viaticos_id = $solicitud_viaticos->id;
                                $detalles_viaticos->catalogo_conceptos_viaticos_id = $i;
                                $detalles_viaticos->transferencia_electronica = $transferencia;
                                $detalles_viaticos->efectivo = $efectivo;
                                $detalles_viaticos->total = ($efectivo + $transferencia);
                                $detalles_viaticos->save();
                            }
                            return true;
                        } catch (\Throwable $e) {
                            Utilidades::errors($e);
                        }
                    }

                    /**
                    * [llenarBeneficiarios inserta un registro en el modelo BeneficiarioViatico ]
                    * @param Object
                    * @param Int
                    * @param Int
                    * @param Int
                    * @return Status           [description]
                    */
                    public function llenarVehiculosItinerarioViaticos($solicitud_viaticos_id,$unidad,$km_inicial,$empleado_operador_id)
                    {
                        try {
                            $vehiculos_itinerario_viaticos = new \App\VehiculosItinerarioViaticos();
                            $vehiculos_itinerario_viaticos->solicitud_viaticos_id  = $solicitud_viaticos_id;
                            $vehiculos_itinerario_viaticos->unidad = $unidad;
                            $vehiculos_itinerario_viaticos->km_inicial = $km_inicial;
                            $vehiculos_itinerario_viaticos->empleado_operador_id = $empleado_operador_id;
                            $vehiculos_itinerario_viaticos->save();
                            return true;
                        } catch (\Throwable $e) {
                            Utilidades::errors($e);
                        }

                    }

                    /**
                    * [llenarBeneficiarios inserta un registro en el modelo BeneficiarioViatico ]
                    * @param Object
                    * @param Int
                    * @param Int
                    * @param Int
                    * @return Status           [description]
                    */
                    public function llenarVehiculosItinerarioViaticosCSCT($solicitud_viaticos_id,$unidad,$km_inicial,$empleado_operador_id)
                    {

                        $vehiculos_itinerario_viaticos = new \App\VehiculosItinerarioViaticosDBP();
                        $vehiculos_itinerario_viaticos->solicitud_viaticos_id  = $solicitud_viaticos_id;
                        $vehiculos_itinerario_viaticos->unidad = $unidad;
                        $vehiculos_itinerario_viaticos->km_inicial = $km_inicial;
                        $vehiculos_itinerario_viaticos->empleado_operador_id = $this->empleadoCSCT($empleado_operador_id);
                        $vehiculos_itinerario_viaticos->save();
                        return true;
                    }

                    /**
                    * [llenarBeneficiarios inserta un registro en el modelo BeneficiarioViatico ]
                    * @param Object
                    * @param Int
                    * @return Status           [description]
                    */
                    public function llenarpersonalServicioViaticos($solicitud_viaticos_id, $empleado_id)
                    {
                        $personal_servicio_viaticos = new \App\PersonalDetalles();
                        $personal_servicio_viaticos->solicitud_viaticos_id = $solicitud_viaticos_id;
                        $personal_servicio_viaticos->empleado_id = $empleado_id;
                        $personal_servicio_viaticos->save();
                        return true;
                    }

                    /**
                    * [llenarBeneficiarios inserta un registro en el modelo BeneficiarioViatico ]
                    * @param Object
                    * @param Int
                    * @return Status           [description]
                    */
                    public function llenarpersonalServicioViaticosCSCT($solicitud_viaticos_id, $empleado_id)
                    {
                        $personal_servicio_viaticos = new \App\PersonalDetallesDBP();
                        $personal_servicio_viaticos->solicitud_viaticos_id = $solicitud_viaticos_id;
                        $personal_servicio_viaticos->empleado_id = $this->empleadoCSCT($empleado_id);
                        $personal_servicio_viaticos->save();
                        return true;
                    }

                    /**0.4242 = 25...
                    * [estados actuaciza el campo estado del modelo SolicitudViaticos dependiendo del estado ]
                    * @param Request
                    * @return Status           [description]
                    */
                    public function estados(Request $request)
                    {
                        try {
                            if ($request->empresa == 1) {
                                //enviar a revision la solicitud de viaticos
                                if ($request->edo == 10) {//2 DESABILITADO
                                    $mensaje = 'Aviso de revisión de viaticos';
                                    $mensaje_adicional = '';
                                    $this->estadoGuardar($request->id, $request->edo);
                                    $this->enviarCorreoVia($request->id,$mensaje,$mensaje_adicional, $request->edo,'');
                                }
                                if ($request->edo == 3) {// DESABILITADO
                                    $mensaje = 'Aviso de autorización de viaticos';
                                    $mensaje_adicional = '';
                                    $this->estadoGuardar($request->id, $request->edo);
                                    $this->enviarCorreoVia($request->id,$mensaje, $mensaje_adicional, $request->edo,'');
                                }
                                if ($request->edo == 2) {//4 NUEVO
                                    $mensaje = 'Viaticos en revisión';
                                    $mensaje_adicional = '';
                                    $this->estadoGuardar($request->id, $request->edo);
                                    $correos_d = ['viaticos@conserflow.com','luis.hernandez@conserflow.com'];
                                    for ($i=0; $i < count($correos_d); $i++) {
                                        $this->enviarCorreoVia($request->id,$mensaje, $mensaje_adicional, $request->edo,'control',$correos_d[$i]);
                                    }

                                }
                                if ($request->edo == 5) {
                                    $this->estadoGuardar($request->id, $request->edo);
                                }
                                if ($request->edo == 6) {
                                    $this->estadoGuardar($request->id, $request->edo);
                                }
                                //Solicitud rechazada
                                if($request->edo == 0){
                                    $this->estadoGuardar($request->id, $request->edo);
                                    $mensaje = 'Aviso de rechazado de viaticos ';
                                    $mensaje_adicional = $request->motivo;
                                    $this->enviarCorreoVia($request->id,$mensaje, $mensaje_adicional,$request->edo,'');
                                }
                                return response()->json(array('status' => true));
                            }
                            ////////////////CSCT
                            elseif ($request->empresa == 2) {

                                //enviar a revision la solicitud de viaticos
                                if ($request->edo == 10) {//2 DESABILITADO
                                    $mensaje = 'Aviso de revisión de viaticos';
                                    $mensaje_adicional = '';
                                    $this->estadoGuardarCSCT($request->id, $request->edo);
                                    $this->enviarCorreoViaCSCT($request->id,$mensaje,$mensaje_adicional, $request->edo,'');
                                }
                                if ($request->edo == 3) {//DESABILITADO
                                    $mensaje = 'Aviso de autorización de viaticos';
                                    $mensaje_adicional = '';
                                    $this->estadoGuardarCSCT($request->id, $request->edo);
                                    $this->enviarCorreoViaCSCT($request->id,$mensaje, $mensaje_adicional, $request->edo,'');
                                }
                                if ($request->edo == 2) {//4 NUEVO
                                    $mensaje = 'Viaticos en revisión';
                                    $mensaje_adicional = '';
                                    $this->estadoGuardarCSCT($request->id, $request->edo);
                                    $correos_d = ['viaticos@conserflow.com','luis.hernandez@conserflow.com'];
                                    for ($i=0; $i < count($correos_d); $i++) {
                                        $this->enviarCorreoViaCSCT($request->id,$mensaje, $mensaje_adicional, $request->edo,'control',$correos_d[$i]);
                                    }

                                }
                                if ($request->edo == 5) {
                                    $this->estadoGuardarCSCT($request->id, $request->edo);
                                }
                                if ($request->edo == 6) {
                                    $this->estadoGuardarCSCT($request->id, $request->edo);
                                }
                                //Solicitud rechazada
                                if($request->edo == 0){
                                    $this->estadoGuardarCSCT($request->id, $request->edo);
                                    $mensaje = 'Aviso de rechazado de viaticos ';
                                    $mensaje_adicional = $request->motivo;
                                    $this->enviarCorreoViaCSCT($request->id,$mensaje, $mensaje_adicional,$request->edo,'');
                                }
                                return response()->json(array('status' => true));
                            }


                        } catch (\Throwable $e) {
                            Utilidades::errors($e);
                        }
                    }

                    /**
                    * [viaticosenrevision Funcion ocupada en los dashboard, revisa las solicitudes con estado 2]7
                    * @return Response
                    */
                    public function viaticosenrevision()
                    {
                        $user = Auth::user();
                        $user_csct = $this->user_csct($user->empleado_id);
                        $solicitudes = [];

                        $solicitudes_cfw = $this->viaticos->solicitud_ver(2, $user);
                        $solicitudes_csct = $this->viaticos->solicitud_ver_csct(2, $user_csct);

                        $solicitudes = array_merge($solicitudes_cfw,$solicitudes_csct);

                        return response()->json($solicitudes);
                    }

                    /**
                    * [viaticosporautorizar Funcion que es llamada en el dashboard , revisa las solicitudes con estado 3]
                    * @return Response
                    */
                    public function viaticosporautorizar()
                    {
                        $user = Auth::user();
                        $solicitudes = $this->viaticos->solicitud_ver(3, $user);
                        return response()->json($solicitudes);
                    }

                    /**
                    * [show Funcion que selecciona una solicitud de viaticos por id solicitado]
                    * @param Int $id
                    * @return Response json
                    */
                    public function show($id)
                    {
                        $solicitud_viaticos_respuesta = [];
                        $solicitudes =  $this->viaticos->solicitud_consulta()
                        ->where('solicitud_viaticos.id','=',$id)->get();
                        foreach ($solicitudes as $key => $solicitud) {
                            $detalles_viaticos = $this->viaticos->DetalleViatico($solicitud->id);

                            $detalles_listado = \App\DetalleViatico::where('solicitud_viaticos_id','=',$solicitud->id)
                            ->join('catalogo_conceptos_viaticos AS CCV','CCV.id','=','detalle_viaticos.catalogo_conceptos_viaticos_id')
                            ->select('detalle_viaticos.*','CCV.nombre')->get();

                            $beneficiario_viaticos = $this->viaticos->BeneficiarioViatico($solicitud->id);
                            $vehiculos_viaticos = $this->viaticos->VehiculosItinerarioViaticos($solicitud->id);
                            $personal_servicio_viaticos = $this->viaticos->PersonalDetalles($solicitud->id);

                            $solicitud_viaticos_respuesta [] = [
                                'solicitud' => $solicitud,
                                'detalles' => $detalles_viaticos,
                                'detalles_listado' => $detalles_listado,
                                'beneficiarios' => $beneficiario_viaticos,
                                'vehiculo' => $vehiculos_viaticos,
                                'empleados' => $personal_servicio_viaticos,
                            ];
                        }
                        return response()->json($solicitud_viaticos_respuesta);
                    }

                    /**
                    * [revision Funcion que revisa si la solicitud de viaticos tenga beneficiarios agreagdos para poder enviarse a revision]
                    * @param Int $id
                    * @return Response json
                    */
                    public function revision($id)
                    {
                        $beneficiario_viaticos = \App\BeneficiarioViatico::where('solicitud_viaticos_id','=',$id)->get();
                        if (count($beneficiario_viaticos) > 0) {
                            return true;
                        }
                        else {
                            return false;
                        }
                    }

                    /**
                    * [beneficiarios_llenar Funcion que realiza la insercion de un registro en la tabla beneficiarios]
                    * @param Object $solicitud_viaticos
                    * @param Request $request
                    * @return Boolean true
                    */
                    public function beneficiarios_llenar($solicitud_viaticos, $request,$empresa)
                    {
                        if ($request->beneficiario_uno != null) {
                            $this->llenarBeneficiarios($solicitud_viaticos, $request->beneficiario_uno[0], $empresa);
                        }

                        return true;
                    }

                    /**
                    * [estadoGuardar Funcion que actualiza el estado de la solicitud de viaticos]
                    * @param Int $id
                    * @param Char $edo
                    * @return Boolean true
                    */
                    public function estadoGuardar($id, $edo)
                    {
                        $solicitud = \App\SolicitudViaticos::where('id',$id)->first();
                        $solicitud->estado = $edo;
                        $solicitud->save();
                        return true;
                    }

                    /**
                    * [estadoGuardar Funcion que actualiza el estado de la solicitud de viaticos]
                    * @param Int $id
                    * @param Char $edo
                    * @return Boolean true
                    */
                    public function estadoGuardarCSCT($id, $edo)
                    {
                        $solicitud = \App\SolicitudViaticosDBP::where('id',$id)->first();
                        $solicitud->estado = $edo;
                        $solicitud->save();
                        return true;
                    }

                    /**
                    * [enviarCorreoVia Funcion que envia segun la revision o autorizacion ]
                    */
                    public function enviarCorreoVia($id, $mensaje, $mensaje_adicional, $edo, $tipo, $correo)
                    {
                        try {

                            $respuesta = $this->viaticos->DatosCorreos($id,$edo);
                            // dd($respuesta);
                            // if($respuesta['correo_electronico'] != null){
                            $correo_e = '';
                            $mensaje_adicional_final = '';
                            if ($tipo === 'control') {
                                $correo_e = $correo;
                                $mensaje_adicional_final = $respuesta['solicitud_viaticos']['nombre_autoriza'];
                            }else {
                                $correo_e = $respuesta['correo_electronico'];
                                $mensaje_adicional_final = $mensaje_adicional;
                            }

                            $hoy = date("Y-m-d");
                            $data = [
                                'estado' => $edo,
                                'nombre' => $respuesta['nombre'],
                                'correo_electronico' => $correo_e,
                                'fecha' => $hoy,
                                'solicitud_viaticos' => $respuesta['solicitud_viaticos'],
                                'beneficiario_viatico' => $respuesta['beneficiario_viatico'][0],
                                'detalle_viatico' => $respuesta['detalle_viatico'],
                                'tet' => $respuesta['tet'],
                                'et' => $respuesta['et'],
                                'tt' => $respuesta['tt'],
                                'vehiculoiv' => $respuesta['vehiculoiv'],
                                'personalsv' => $respuesta['personalsv'],
                                'mensaje' => $mensaje,
                                'mensaje_adicional' => $mensaje_adicional_final,
                            ];
                            // dd($data);
                            Mail::send('emails.viatico', $data, function($message) use ($data, $mensaje) {
                                $core = $data['correo_electronico'];
                                $folio = $data['solicitud_viaticos']['folio'];
                                $pdf = PDF::loadView('pdf.forvia',$data);
                                $message->to($core, $mensaje)->subject($mensaje);
                                // $message->to('romerovelascogregorio@gmail.com', $mensaje)->subject($mensaje);
                                $message->from('webmaster@conserflow.com','Conserflow');
                                $message->attachData($pdf->output(), $folio.'.pdf');
                                // $message->attach('public/img/1.png');
                            });
                            return  true;
                            // }
                            // else {
                            //   return true;
                            // }
                        } catch (\Throwable $e) {
                            Utilidades::errors($e);
                        }
                    }

                    /**
                    * [enviarCorreoVia Funcion que envia segun la revision o autorizacion ]
                    */
                    public function enviarCorreoViaCSCT($id, $mensaje, $mensaje_adicional, $edo, $tipo, $correo)
                    {
                        try {

                            $respuesta = $this->viaticos->DatosCorreosCSCT($id,$edo);
                            // dd($respuesta);
                            // if($respuesta['correo_electronico'] != null){
                            $correo_e = '';
                            $mensaje_adicional_final = '';
                            if ($tipo === 'control') {
                                $correo_e = $correo;
                                $mensaje_adicional_final = $respuesta['solicitud_viaticos']['nombre_autoriza'];
                            }else {
                                $correo_e = $respuesta['correo_electronico'];
                                $mensaje_adicional_final = $mensaje_adicional;
                            }

                            $hoy = date("Y-m-d");
                            $data = [
                                'estado' => $edo,
                                'nombre' => $respuesta['nombre'],
                                'correo_electronico' => $correo_e,
                                'fecha' => $hoy,
                                'solicitud_viaticos' => $respuesta['solicitud_viaticos'],
                                'beneficiario_viatico' => $respuesta['beneficiario_viatico'][0],
                                'detalle_viatico' => $respuesta['detalle_viatico'],
                                'tet' => $respuesta['tet'],
                                'et' => $respuesta['et'],
                                'tt' => $respuesta['tt'],
                                'vehiculoiv' => $respuesta['vehiculoiv'],
                                'personalsv' => $respuesta['personalsv'],
                                'mensaje' => $mensaje,
                                'mensaje_adicional' => $mensaje_adicional_final,
                            ];
                            // dd($data);
                            Mail::send('emails.viatico', $data, function($message) use ($data, $mensaje) {
                                $core = $data['correo_electronico'];
                                $folio = $data['solicitud_viaticos']['folio'];
                                $pdf = PDF::loadView('pdf.forviacsct',$data);
                                $message->to($core, $mensaje)->subject($mensaje);
                                // $message->to('romerovelascogregorio@gmail.com', $mensaje)->subject($mensaje);
                                $message->from('webmaster@conserflow.com','Conserflow');
                                $message->attachData($pdf->output(), $folio.'.pdf');
                                // $message->attach('public/img/1.png');
                            });
                            return  true;
                            // }
                            // else {
                            //   return true;
                            // }
                        } catch (\Throwable $e) {
                            Utilidades::errors($e);
                        }
                    }

                    public function proyectos($value)
                    {

                        if ($value == 1) {
                            $proyectos = Proyecto::orderBy('id', 'asc')->get();
                        }elseif ($value == 2) {
                            $proyectos = ProyectoDBP::orderBy('id', 'asc')->get();
                        }
                        return response()->json($proyectos);
                    }

                    public function empleadoCSCT($empleado_cfw)
                    {
                        $empleado_cfw = Empleado::where('id',$empleado_cfw)->select('nombre','ap_paterno','ap_materno')->first();
                        $empleado_csct = EmpleadoDBP::where('nombre',$empleado_cfw->nombre)->where('ap_paterno',$empleado_cfw->ap_paterno)->where('ap_materno',$empleado_cfw->ap_materno)->first();
                        return $empleado_csct->id;
                    }

                    public function user_csct($empleado_id)
                    {
                        $empleado_csct = $this->empleadoCSCT($empleado_id);

                        $user_csct = EmpleadoDBP::leftjoin('users AS u','u.empleado_id','empleados.id')
                        ->where('empleados.id',$empleado_csct)
                        ->select('u.*')
                        ->first();

                        return isset($user_csct) == true ? $user_csct : 0;
                    }

                    public function empleados($value)
                    {
                        if ($value == 1) {
                            $empleados = Empleado::select('empleados.*','empleados.ubicacion_id', 'tipo_ubicacion.nombre AS ubicacion')
                            ->leftJoin('tipo_ubicacion','empleados.ubicacion_id', '=','tipo_ubicacion.id')
                            ->orderBy('id', 'asc')->get();
                        }elseif ($value == 2) {
                            $empleados = EmpleadoDBP::select('empleados.*','empleados.ubicacion_id', 'tipo_ubicacion.nombre AS ubicacion')
                            ->leftJoin('tipo_ubicacion','empleados.ubicacion_id', '=', 'tipo_ubicacion.id')
                            ->orderBy('id', 'asc')->get();
                        }

                        return response()->json($empleados);
                    }

                    public function updateEmp()
                    {

                        $svcfw = \App\SolicitudViaticos::get();
                        foreach ($svcfw as $key_c => $value_c) {
                            $solicitud = \App\SolicitudViaticos::where('id',$value_c->id)->first();
                            $solicitud->empleado_user_id = $solicitud->empleado_user_id.'|';
                            $solicitud->save();
                        }

                        $svdp = \App\SolicitudViaticosDBP::get();
                        foreach ($svdp as $key_csct => $value_csct) {
                            $solicitud = \App\SolicitudViaticosDBP::where('id',$value_csct->id)->first();
                            $solicitud->empleado_user_id = $solicitud->empleado_user_id.'|';
                            $solicitud->save();
                        }
                        return true;
                    }

                    public function eliminar($id)
                    {
                        $valores = explode('&',$id);

                        $solicitud_id = $valores[0];
                        $empresa = $valores[1];

                        if ($empresa == 1) {
                            $sol = \App\SolicitudViaticos::where('id',$id)->update(['eliminado' => 1]);
                        }elseif ($empresa == 2) {
                            $solicitud = \App\SolicitudViaticosDBP::where('id',$id)->update(['eliminado' => 1]);
                        }

                        return response()->json(['status' => true]);
                    }
                }
