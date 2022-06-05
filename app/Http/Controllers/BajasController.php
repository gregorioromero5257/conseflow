<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empleado;
use App\Contrato;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use File;
use Illuminate\Support\Facades\Storage;

class BajasController extends Controller
{
   protected $rules = array(
        'formato_finiquito' => 'required|max:45',
    );

    public function index(Request $request, $id)
    {
        /*Obtiene los detalles de contrato de acuerdo al ID del empleado*/
        $tiposContrato = Contrato::orderBy('contratos.id', 'ASC')//DB::table('contratos')
            ->leftJoin('tipo_contratos', 'tipo_contratos.id', '=', 'contratos.tipo_contrato_id')
            ->leftJoin('tipo_ubicacion', 'tipo_ubicacion.id', '=', 'contratos.tipo_ubicacion_id')
            ->leftJoin('tipo_nomina', 'tipo_nomina.id', '=', 'contratos.tipo_nomina_id')
            ->leftJoin('empleados', 'empleados.id', '=', 'contratos.empleado_id')
            ->leftJoin('horarios', 'horarios.id', '=', 'contratos.horario_id')
            ->leftJoin('tipo_horario', 'tipo_horario.id', '=', 'horarios.tipo_horario_id')
            ->leftJoin('proyectos', 'proyectos.id', '=', 'contratos.proyecto_id')
            ->leftJoin('tipo_fin_contrato', 'tipo_fin_contrato.id', '=', 'contratos.motivo_fecha_fin')
            ->leftJoin('bajas', 'bajas.contrato_id', '=', 'contratos.id')
            ->leftJoin('renuncias', 'renuncias.bajas_id', '=', 'bajas.id')
            ->leftJoin('sueldos', 'sueldos.contrato_id', '=', 'contratos.id')
            ->select('contratos.*', 'sueldos.id AS sueldoid', 'sueldos.sueldo_diario_integral AS SDI', 'sueldos.sueldo_mensual AS SM', 'sueldos.infonavit', 'sueldos.viaticos_mensuales AS VM', 'sueldos.sueldo_diario_neto AS SDN', 'sueldos.contrato_id AS sueldos_contrato_id','bajas.id AS bajaid', 'bajas.fecha_baja', 'bajas.observaciones', 'bajas.contrato_id AS bajas_contrato_id', 'renuncias.id AS renunciaid', 'renuncias.formato_renuncia', 'renuncias.bajas_id AS renuncias_bajas_id', 'tipo_fin_contrato.id AS motivofinid', 'tipo_fin_contrato.nombre AS motivo', 'tipo_contratos.nombre AS tc_nombre', 'tipo_nomina.nombre AS tn_nombre', 'empleados.nombre AS e_nombre', 'tipo_horario.nombre AS th_nombre', DB::raw('IFNULL(horarios.hora_entrada, "No Establecida") as h_he'), DB::raw('IFNULL(horarios.hora_salida, "No Establecida") as h_hs'), DB::raw('IFNULL(tipo_ubicacion.nombre, "Sin Definir") as tu_nombre'), DB::raw('IFNULL(proyectos.nombre, "No Asignado") p_nombre'))
           ->where('contratos.empleado_id', '=', $id)
            ->where('contratos.condicion', '=', '0')
            ->get();

        return response()->json($tiposContrato);
        /**********************************************************************************/
    }

    public function create()
    {
        $puestos = 0;
        $departamentos = 0;
        $estados = 0;
        $arreglo = [];
        /*Obtiene los registros de empleados con contratos dados de baja*/
        $empleados = DB::table('contratos')
            ->leftJoin('empleados', 'empleados.id', '=', 'contratos.empleado_id')
            ->select('empleados.id', 'empleados.nombre','empleados.ap_paterno','empleados.ap_materno','empleados.condicion','empleados.puesto_id', 'empleados.edo_civil_id')
            ->where('contratos.condicion', '=', 0)
            ->orderBy('empleados.id', 'asc')
            ->distinct()
            ->get();
        foreach ($empleados as $key => $empleado) {
            $puestoid = $empleado->puesto_id;
            $estadosid = $empleado->edo_civil_id;

            if (!is_null($puestoid)) {
                $puestos = DB::table('puestos')
                    ->select('puestos.id', 'puestos.nombre','puestos.area','puestos.departamento_id')
                    ->where('puestos.id', '=', $puestoid)
                    ->get();
                $departamentoid = $puestos[0]->departamento_id;
                if (!is_null($departamentoid)) {
                    $departamentos = DB::table('departamentos')
                        ->select('departamentos.id','departamentos.nombre','departamentos.direccion_administrativa_id')
                        ->where('departamentos.id', '=', $departamentoid)
                        ->get();
                }
            }
            if (!is_null($estadosid)) {
                 $estados = DB::table('estados_civiles')
                    ->select('estados_civiles.*')
                    ->where('estados_civiles.id', '=', $estadosid)
                    ->get();
            }

            $arreglo[] = [
                'empleado' => $empleado,
                'puesto' => $puestos[0],
                'departamento' => $departamentos[0],
                'estados' =>$estados[0],
            ];
        }
        return response()->json($arreglo);
        /*********************************************************************************/
    }

    public function store(Request $request)
    {
        //
    }

    public function edit($id)
    {
        // Se obtiene el archivo del FTP serve
      //  $archivo = Storage::disk('ftp')->get($id);
      $archivo = Utilidades::ftpSolucion($id);
        // Se coloca el archivo en una ruta local
        Storage::disk('descarga')->put($id, $archivo);
        //--Devuelve la respuesta y descarga el archivo--//
        return response()->download(storage_path().'/app/descargas/'.$id);
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request)
    {
        //
    }

    public function destroy($id)
    {
      //elimina de la ruta local el archivo descargado
        Storage::disk('descarga')->delete($id);
    }
}
