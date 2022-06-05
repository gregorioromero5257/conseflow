<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Contrato;
use App\Empleado;
use App\Puesto;
use App\Departamento;
use App\EstadoCivil;
use App\DireccionEmpleado;
use App\Finiquito;
use App\Antiguedad;
use Barryvdh\DomPDF\Facade as PDF;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;
use File;
use Illuminate\Support\Facades\Storage;
use App\Exports\FiniquitosExport;
use Maatwebsite\Excel\Facades\Excel;

class FiniquitoController extends Controller
{

    public function index()
    {
        $finiquitos = DB::table('empleados')
        ->leftJoin('puestos', 'empleados.puesto_id', '=', 'puestos.id')
        ->leftJoin('departamentos', 'puestos.departamento_id', '=', 'departamentos.id')
        ->select('empleados.id', 'empleados.nombre', 'empleados.ap_paterno', 'empleados.ap_materno', 'puestos.nombre AS puesto', 'departamentos.nombre AS departamento')
        ->join('antiguedad', 'antiguedad.empleado_id', '=', 'empleados.id')
        // ->join('empresas', 'antiguedad.empresa_id', '=', 'empresas.id')
        // ->select('finiquito.id', 'finiquito.formato', 'antiguedad.fecha_inicial', 'antiguedad.fecha_final', 'empresas.nombre AS empresa')
        ->where([
            // 'antiguedad.empleado_id' => $id,
            'antiguedad.condicion' => 0
        ])
        ->distinct('empleados.id')
        ->get();

        return response()->json($finiquitos);
    }

    public function pdf($id)
    {
        $finiquito = Finiquito::where('finiquito.id', '=',$id)
        ->leftJoin('antiguedad AS ant', 'ant.id', '=', 'finiquito.antiguedad_id')
        ->leftJoin('empleados AS emp', 'emp.id', '=', 'ant.empleado_id')

        ->leftJoin('direcciones_empleados AS direccion', 'direccion.empleado_id', '=', 'emp.id')

        ->leftJoin('puestos AS puesto', 'puesto.id', '=', 'emp.puesto_id')
        ->leftJoin('estados_civiles AS edo_civil', 'edo_civil.id', '=', 'emp.edo_civil_id')
        ->select('finiquito.*', 'finiquito.total AS total', 'emp.lug_nac AS lug_nac', 'edo_civil.nombre as civil', 'direccion.calle AS calle', 'direccion.numero_exterior AS exterior', 'direccion.numero_interior AS interior',
        'direccion.colonia AS colonia', 'direccion.entidad_federativa AS estado',
        DB::raw("CONCAT(emp.nombre,' ',emp.ap_paterno,' ',emp.ap_materno) AS empleado"),DB::raw('emp.fech_nac, TIMESTAMPDIFF(YEAR,fech_nac,CURDATE()) AS edad'))
        ->get()->first();
            $count = 1;
            $numero = $finiquito->total;
            $cambio = Utilidades::valorEnLetras($numero);

            $dias = array("DOMINGO","LUNES","MARTES","MIERCOLES","JUEVES","VIERNES","SABADO");

            $hoy = $dias[date('w')]." ".date('d')." DE ".strtoupper(Utilidades::meses(date('n')) ). " DEL ".date('Y') ;

            $pdf = PDF::loadView('pdf.finiquitos', compact('numero','cambio','empleado','mescumpleaños','hoy','telefonos','count','domicilios','sueldos','finiquito'));
            $pdf->setPaper("letter","portrait");

            return $pdf->stream();

    }

    public function store(Request $request){
        if (!$request->ajax()) return redirect('/');

        //obtiene el nombre del archivo y su extension
        $FormatoNE = $request->file('adjunto')->getClientOriginalName();
        //Obtiene el nombre del archivo
        $FormatoNombre = pathinfo($FormatoNE, PATHINFO_FILENAME);
        //obtiene la extension
        $FormatoExt = $request->file('adjunto')->getClientOriginalExtension();
        //nombre que se guarad en BD
        $FormatoStore = $FormatoNombre.'_'.uniqid().'.'.$FormatoExt;
        //Subida del archivo al servidor ftp
        Storage::disk('local')->put('Archivos/'.$FormatoStore, fopen($request->file('adjunto'), 'r+'));

        $finiquito = Finiquito::findOrFail($request->id);
        $finiquito->formato = $FormatoStore;
        $finiquito->save();

        return response()->json(array(
            'status' => true,
        ));
    }

    public function show($id)
    {
        // Se obtiene el archivo del FTP serve
        $archivo = Utilidades::ftpSolucion($id);
        // Se coloca el archivo en una ruta local
        Storage::disk('descarga')->put($id, $archivo);
        //--Devuelve la respuesta y descarga el archivo--//
        return response()->download(storage_path().'/app/descargas/'.$id);
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

    public function finiquitosPorEmpleado(Request $request, $id)
    {
        $finiquitos = DB::table('antiguedad')
        ->join('finiquito', 'antiguedad.id', '=', 'finiquito.antiguedad_id')
        ->join('empresas', 'antiguedad.empresa_id', '=', 'empresas.id')
        ->select('finiquito.id', 'finiquito.formato', 'antiguedad.fecha_inicial', 'antiguedad.fecha_final', 'empresas.nombre AS empresa')
        ->where([
            'antiguedad.empleado_id' => $id,
            'antiguedad.condicion' => 0
        ])
        ->get();

        return response()->json($finiquitos->toArray());
    }

    public function reporteFiniquitosProyecto(Request $request)
    {
        $finiquitos = DB::table('finiquito')
        ->leftJoin('antiguedad', 'finiquito.antiguedad_id', '=', 'antiguedad.id')
        ->join('empresas', 'antiguedad.empresa_id', '=', 'empresas.id')
        ->join('empleados', 'antiguedad.empleado_id', '=', 'empleados.id')
        ->join('contratos', 'finiquito.contrato_id', '=', 'contratos.id')
        ->join('proyectos', 'contratos.proyecto_id', '=', 'proyectos.id')
        ->select('finiquito.id', 'finiquito.formato', 'antiguedad.fecha_inicial', 'antiguedad.fecha_final', 'empresas.nombre AS empresa', 'contratos.antiguedad_id', 'proyectos.nombre AS proyecto', DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) AS empleado"), 'finiquito.total', 'proyectos.nombre AS proyecto' )
        ->where([
            ['antiguedad.condicion',  '=',  0],
            ['antiguedad.fecha_final', '>=', $request->fecha_inicial],
            ['antiguedad.fecha_final', '<=', $request->fecha_final],
            ['contratos.proyecto_id', $request->proyecto_id == 0 ? '>' : '=', $request->proyecto_id]
        ])
        ->get();

        return response()->json($finiquitos->toArray());
    }

    public function excel(Request $request, $fecha_inicial, $fecha_final, $proyecto_id)
    {
        return Excel::download(new FiniquitosExport($fecha_inicial, $fecha_final, $proyecto_id), 'ReporteFiniquitos.xlsx' );
    }

    public function finiquito(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $contrato = DB::table('contratos')
        ->where([
            'id' => $request->contrato_id
        ])
        ->get()->first();

        $antiguedad = DB::table('antiguedad')
        ->where([
            'empleado_id' => $contrato->empleado_id,
            'empresa_id'  => $contrato->empresa_id
        ])
        ->get()->first();

        $sueldo = DB::table('sueldos')
        ->where([
            'contrato_id' => $contrato->id
        ])
        ->get()->first();

        $date1 = new \DateTime($antiguedad->fecha_inicial);
        $date2 = new \DateTime($antiguedad->fecha_final);
        $diff = $date1->diff($date2);

        $dias = $diff->days;

        // Aguinaldo proporcional (se obtiene de sueldos el sueldo diario integrado)
        $sdi = $sueldo->sueldo_diario_integral;

	    $dias_lab_quince = $dias * 15;

	    $dias_a_pagar_aguinaldo = $dias_lab_quince / 365;

        $aguinaldo_proporcional = $sdi * $dias_a_pagar_aguinaldo;


        // Aguinald proporcional - Factor
        $factor_aguinaldo = 0.04109;

        $factor_dias_lab = $factor_aguinaldo * $dias;

        $aguinaldo_proporcional_factor = $sdi * $factor_dias_lab;

        // Vacaciones proporcionales
        $factor_vacaciones = 0.0164383561643836;

        $dias_factor = $dias * $factor_vacaciones;

        $vacaciones_proporcionales = $sdi * $dias_factor;

        // Prima vacacional
        $prima_vacacional = $vacaciones_proporcionales * 0.25;

        // Finiquito
        $total = $aguinaldo_proporcional + $vacaciones_proporcionales + $prima_vacacional;

        $finiquito = new Finiquito();
        $finiquito->total = $total;
        $finiquito->aguinaldo_proporcional = $aguinaldo_proporcional;
        $finiquito->prima_vacacional = $vacaciones_proporcionales;
        $finiquito->vacaciones_proporcional = $prima_vacacional;
        $finiquito->antiguedad_id = $antiguedad->id;
        $finiquito->contrato_id = $request->contrato_id;
        $finiquito->save();

        return response()->json(array(
            'status' => true
        ));
    }

}
