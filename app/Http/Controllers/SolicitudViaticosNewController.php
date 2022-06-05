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

class SolicitudViaticosNewController extends Controller
{
    // NOTE: SEGUNDA VERSION DEL PROCESO DE REQUISICION

    protected $viatico;

    // LLAMADO A REPOSITORIO QUE CONTIENE CONSULTAS FUNCIONALES PARA EL PROCESO DE VIATICOS
    public function __construct(Viaticos $viatico)
    {
        $this->viaticos = $viatico;
    }

    public function getSolicitudes($id)
    {
      try {

        if ($id == 1) {//CONSERFLOW
          $viaticos = $this->viaticos->solicitud_consulta_server();
        }elseif ($id == 2) {//CSCT
          $viaticos = $this->viaticos->solicitud_consulta_csct_server();
        }

        return $this->busqueda($viaticos);

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function busqueda($dato)
    {
      extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));

      $data = $dato;

      if (isset($query) && $query) {
        $data = $byColumn == 1 ?
        $this->busqueda_filterByColumn($data, $query) :
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

    public static function busqueda_filterByColumn($data, $queries)
    {
      $queries = json_decode($queries, true);
      // dd($queries);
      return $data->where(function ($q) use ($queries)
      {
        foreach ($queries as $field => $query)
        {
          $_field = $field;

          if (is_string($query))
          {
            if ($_field === 'EE.nombre' || $_field === 'EE.ap_paterno' || $_field === 'EE.ap_materno') {
              $q->orWhere($_field, 'LIKE', "%{$query}%");
            }
            elseif ($_field === 'EB.nombre' || $_field === 'EB.ap_paterno' || $_field === 'EB.ap_materno') {
              $q->orWhere($_field, 'LIKE', "%{$query}%");
            }
            else {
              $q->where($_field, 'LIKE', "%{$query}%");
            }
          }
          else
          {
            $start = Carbon::createFromFormat('Y-m-d', substr($query['start'], 0, 10))->startOfDay();
            $end = Carbon::createFromFormat('Y-m-d', substr($query['end'], 0, 10))->endOfDay();

            $q->whereBetween($_field, [$start, $end]);
          }
        }
      });
    }

    public function createSolicitudes(Request $request)
    {
      try {
        DB::beginTransaction();
        $total_personas = 0;
        $transferencia = 0;
        $efectivo = 0;
        $user = Auth::user();

        $solicitud_viaticos = new \App\SolicitudViaticos();
        $folio  = $this->crearFolioViaticos($request->solicitud['proyecto_id']['id'],$request->empresa);
        $solicitud_viaticos->fecha_solicitud = $request->solicitud['fecha'];
        $solicitud_viaticos->fecha_pago = $request->solicitud['fecha_pago'];
        $solicitud_viaticos->folio = $folio;
        $solicitud_viaticos->proyecto_id = $request->solicitud['proyecto_id']['id'];
        $solicitud_viaticos->origen = $request->solicitud['origen'];
        $solicitud_viaticos->fecha_salida = $request->solicitud['fecha_salida'];
        $solicitud_viaticos->fecha_operacion = $request->solicitud['fecha_operacion'];
        $solicitud_viaticos->fecha_retorno = $request->solicitud['fecha_retorno'];
        $solicitud_viaticos->empleado_elabora_id = $request->solicitud['empleado_elabora_id']['id'];
        $solicitud_viaticos->empleado_revisa_id = $request->solicitud['empleado_revisa_id']['id'];
        $solicitud_viaticos->empleado_autoriza_id = $request->solicitud['empleado_autoriza_id']['id'];

        if (  isset($request->solicitud['empleado_supervisor_id']['id'])  == true) {
          $solicitud_viaticos->empleado_supervisor_id = $request->solicitud['empleado_supervisor_id']['id'];
          $total_personas += 1;
        }
        if ($request->solicitud['personal_servicio_viaticos_id'] != '') {
          if (count($request->solicitud['personal_servicio_viaticos_id']) > 0) {
            $total_personas += count($request->solicitud['personal_servicio_viaticos_id']);
            $solicitud_viaticos->personal_servicio = json_encode($request->solicitud['personal_servicio_viaticos_id']);
          }
        }
        $solicitud_viaticos->total_personas = $total_personas;
        $solicitud_viaticos->empleado_user_id = $user->empleado_id == null ? $user->id : $user->empleado_id;
        $solicitud_viaticos->tipo = $request->tipo;
        $solicitud_viaticos->destino = $request->solicitud['destino'];
        Utilidades::auditar($solicitud_viaticos, $solicitud_viaticos->id);
        if (count($request->vehiculos) > 0) {
          $solicitud_viaticos->vehiculos = json_encode($request->vehiculos);
        }
        $solicitud_viaticos->save();

        $beneficiario = new \App\BeneficiarioViatico();
        $beneficiario->solicitud_viaticos_id = $solicitud_viaticos->id;
        if(gettype($request->solicitud['beneficiario'][0]['id']) === 'array' || gettype($request->solicitud['beneficiario'][0]['id']) === 'object'){
          // interno
          $beneficiario->empleado_beneficiario_id = $request->solicitud['beneficiario'][0]['id']['id'];
          $beneficiario->datos_bancarios_empleado_id = $request->solicitud['beneficiario'][0]['dbemp_id'];
          $beneficiario->banco_nombre = $request->solicitud['beneficiario'][0]['banco'];
          $beneficiario->tarjeta = $request->solicitud['beneficiario'][0]['tarjeta'];
          $beneficiario->clabe = $request->solicitud['beneficiario'][0]['clave'];
          $beneficiario->cuenta = $request->solicitud['beneficiario'][0]['cuenta'];
        }else {
          // externo
          $beneficiario->empleado_beneficiario_id = 0;
          $beneficiario->beneficiario_externo = $request->solicitud['beneficiario'][0]['id'];
          $beneficiario->datos_bancarios_empleado_id = 0;
          $beneficiario->banco_nombre = $request->solicitud['beneficiario'][0]['dbemp_id'];
          $beneficiario->tarjeta = $request->solicitud['beneficiario'][0]['tarjeta'];
          $beneficiario->clabe = $request->solicitud['beneficiario'][0]['clave'];
          $beneficiario->cuenta = $request->solicitud['beneficiario'][0]['cuenta'];
        }
        Utilidades::auditar($beneficiario, $beneficiario->id);
        $beneficiario->save();

        foreach ($request->solicitud['conceptos'] as $key => $value) {
          $detalle = new \App\SolicitudViaticosDetalle();
          $detalle->solicitud_viaticos_id = $solicitud_viaticos->id;
          $detalle->catalogo_conceptos_id = $value['id'];
          $detalle->transferencia_electronica = $value['transferencia'];
          $detalle->efectivo = $value['efectivo'];
          $detalle->total  = (float)$value['transferencia'] + (float)$value['efectivo'];
          $detalle->catalogo_conceptos_viaticos = $value['concepto'];
          Utilidades::auditar($detalle, $detalle->id);
          $detalle->save();
          $efectivo += (float)$value['efectivo'];
          $transferencia +=  (float)$value['transferencia'];
        }

        $totales_viaticos = new \App\TotalSolicitudViaticos();
        $totales_viaticos->solicitud_viatico_id = $solicitud_viaticos->id;
        $totales_viaticos->efectivo = $efectivo;
        $totales_viaticos->transferencia = $transferencia;
        $totales_viaticos->total = $efectivo + $transferencia;
        Utilidades::auditar($totales_viaticos, $totales_viaticos->id);
        $totales_viaticos->save();


        DB::commit();
        return response()->json(['status' => true]);

      } catch (\Throwable $e) {
        DB::rollBack();
        Utilidades::errors($e);
        return response()->json(['status' => false]);
      }
    }

    public function updateSolicitudes(Request $request)
    {
      try {
        DB::beginTransaction();
        $total_personas = 0;
        $transferencia = 0;
        $efectivo = 0;
        $user = Auth::user();

        $solicitud_viaticos = \App\SolicitudViaticos::where('id',$request->solicitud['id'])->first();
        $solicitud_viaticos->fecha_solicitud = $request->solicitud['fecha'];
        $solicitud_viaticos->fecha_pago = $request->solicitud['fecha_pago'];
        $solicitud_viaticos->proyecto_id = $request->solicitud['proyecto_id']['id'];
        $solicitud_viaticos->origen = $request->solicitud['origen'];
        $solicitud_viaticos->fecha_salida = $request->solicitud['fecha_salida'];
        $solicitud_viaticos->fecha_operacion = $request->solicitud['fecha_operacion'];
        $solicitud_viaticos->fecha_retorno = $request->solicitud['fecha_retorno'];
        $solicitud_viaticos->empleado_elabora_id = $request->solicitud['empleado_elabora_id']['id'];
        $solicitud_viaticos->empleado_revisa_id = $request->solicitud['empleado_revisa_id']['id'];
        $solicitud_viaticos->empleado_autoriza_id = $request->solicitud['empleado_autoriza_id']['id'];

        if (  isset($request->solicitud['empleado_supervisor_id']['id'])  == true) {
          // if ($request->solicitud['empleado_supervisor_id']['id'] != null) {
            $solicitud_viaticos->empleado_supervisor_id = $request->solicitud['empleado_supervisor_id']['id'];
            $total_personas += 1;
          // }
        }
        if ($request->solicitud['personal_servicio_viaticos_id'] != '') {
          if (count($request->solicitud['personal_servicio_viaticos_id']) > 0) {
            $total_personas += count($request->solicitud['personal_servicio_viaticos_id']);
            $solicitud_viaticos->personal_servicio = json_encode($request->solicitud['personal_servicio_viaticos_id']);
          }
        }
        $solicitud_viaticos->total_personas = $total_personas;
        $solicitud_viaticos->empleado_user_id = $user->empleado_id == null ? $user->id : $user->empleado_id;
        $solicitud_viaticos->tipo = $request->tipo;
        $solicitud_viaticos->destino = $request->solicitud['destino'];
        Utilidades::auditar($solicitud_viaticos, $solicitud_viaticos->id);
        if (count($request->vehiculos) > 0) {
          $solicitud_viaticos->vehiculos = json_encode($request->vehiculos);
        }
        $solicitud_viaticos->save();

        $beneficiario = \App\BeneficiarioViatico::where('solicitud_viaticos_id',$request->solicitud['id'])->first();
        $beneficiario->solicitud_viaticos_id = $solicitud_viaticos->id;
        if(gettype($request->solicitud['beneficiario'][0]['id']) === 'array' || gettype($request->solicitud['beneficiario'][0]['id']) === 'object'){
          // interno
          $beneficiario->empleado_beneficiario_id = $request->solicitud['beneficiario'][0]['id']['id'];
          $beneficiario->datos_bancarios_empleado_id = $request->solicitud['beneficiario'][0]['dbemp_id'];
          $beneficiario->banco_nombre = $request->solicitud['beneficiario'][0]['banco'];
          $beneficiario->tarjeta = $request->solicitud['beneficiario'][0]['tarjeta'];
          $beneficiario->clabe = $request->solicitud['beneficiario'][0]['clave'];
          $beneficiario->cuenta = $request->solicitud['beneficiario'][0]['cuenta'];
        }else {
          // externo
          $beneficiario->empleado_beneficiario_id = 0;
          $beneficiario->beneficiario_externo = $request->solicitud['beneficiario'][0]['id'];
          $beneficiario->datos_bancarios_empleado_id = 0;
          $beneficiario->banco_nombre = $request->solicitud['beneficiario'][0]['dbemp_id'];
          $beneficiario->tarjeta = $request->solicitud['beneficiario'][0]['tarjeta'];
          $beneficiario->clabe = $request->solicitud['beneficiario'][0]['clave'];
          $beneficiario->cuenta = $request->solicitud['beneficiario'][0]['cuenta'];
        }
        Utilidades::auditar($beneficiario, $beneficiario->id);
        $beneficiario->save();

        foreach ($request->solicitud['conceptos'] as $key => $value) {
          if ($value['id_registro'] == 0) {
            $detalle = new \App\SolicitudViaticosDetalle();
            $detalle->solicitud_viaticos_id = $solicitud_viaticos->id;
            $detalle->catalogo_conceptos_id = $value['id'];
            $detalle->transferencia_electronica = $value['transferencia'];
            $detalle->efectivo = $value['efectivo'];
            $detalle->total  = (float)$value['transferencia'] + (float)$value['efectivo'];
            $detalle->catalogo_conceptos_viaticos = $value['concepto'];
            Utilidades::auditar($detalle, $detalle->id);
            $detalle->save();
            $efectivo += (float)$value['efectivo'];
            $transferencia +=  (float)$value['transferencia'];
          }
        }

        $totales_viaticos = \App\TotalSolicitudViaticos::where('solicitud_viatico_id',$request->solicitud['id'])->first();
        $totales_viaticos->efectivo += $efectivo;
        $totales_viaticos->transferencia += $transferencia;
        $totales_viaticos->total += $efectivo + $transferencia;
        Utilidades::auditar($totales_viaticos, $totales_viaticos->id);
        $totales_viaticos->save();


        DB::commit();
        return response()->json(['status' => true]);

      } catch (\Throwable $e) {
        DB::rollBack();
        Utilidades::errors($e);
        return response()->json(['status' => false]);
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
            $folio = strtoupper($proyecto->nombre_corto).'-'.str_pad($numero, 3, "0", STR_PAD_LEFT);
            return $folio;
        } catch (\Throwable $e) {
            Utilidades::errors($e);
        }
    }

    public function getBeneficiario($id)
    {
        $beneficiario = \App\BeneficiarioViatico::
        leftjoin('empleados AS e','e.id','solicitud_viaticos_beneficiarios.empleado_beneficiario_id')
        ->select('solicitud_viaticos_beneficiarios.*',DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS nombre_beneficiario"))
        ->where('solicitud_viaticos_beneficiarios.solicitud_viaticos_id',$id)->first();

        return response()->json($beneficiario);
    }

    public function getDetalles($data)
    {
      $valores = explode('&',$data);
      $id = $valores[0];
      $empresa = $valores[1];

      $detalle = \App\SolicitudViaticosDetalle::where('solicitud_viaticos_id',$id)->get();

      return response()->json($detalle);
    }



}
