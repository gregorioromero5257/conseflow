<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Validator;

class RetardoController extends Controller
{

  /**
  * retardosbuscar a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function retardosbuscar(Request $request)
  {
    $arreglo = [];
    $puestos = 0;
    $departamentos = 0;
    $estados = 0;
    $ubicaciones = 0;
    $empleados = DB::table('retardos')
    ->leftJoin('asistencias AS A','A.id','=','retardos.asistencia_id')
    ->leftJoin('contratos AS C','C.id','=','A.contrato_id')
    ->leftJoin('empleados AS E','E.id','=','A.empleado_id')
    ->select('E.*')
    ->whereBetween('A.fecha',[$request->fecha_inicial, $request->fecha_final])
    ->where([
      ['C.proyecto_id', $request->proyecto_id == 0 ? '>' : '=', $request->proyecto_id],
      ['C.empresa_id', $request->empresa_id == 0 ? '>' : '=', $request->empresa_id],
    ])
    ->orderBy('id', 'asc')->distinct()->get();
    foreach ($empleados as $key => $empleado) {
      $puestoid = $empleado->puesto_id;
      $estadosid = $empleado->edo_civil_id;

      if (!is_null($puestoid)) {
        //$puestos = Puesto::find($puestoid)->get();
        $puestos = DB::table('puestos')
        ->select('puestos.*')
        ->where('puestos.id', '=', $puestoid)
        ->get();
        $departamentoid = $puestos[0]->departamento_id;
        if (!is_null($departamentoid)) {
          //$departamentos = Departamento::find($departamentoid)->get();
          $departamentos = DB::table('departamentos')
          ->select('departamentos.*')
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


  }

  /**
  * retardosBuscarEmpleado a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function retardosBuscarEmpleado(Request $request)
  {
    $retardo = DB::table('retardos')
    ->leftJoin('asistencias AS A','A.id','=','retardos.asistencia_id')
    ->leftJoin('contratos AS C','C.id','=','A.contrato_id')
    ->leftJoin('empleados AS E','E.id','=','A.empleado_id')
    ->leftJoin('dias AS D','D.id','=','A.dia_id')
    ->leftJoin('registros AS R','R.id','=','A.registro_id')
    ->select('retardos.*','A.fecha','A.horas_trabajadas','D.nombre AS dia','R.hora_entrada','R.hora_salida')
    ->whereBetween('A.fecha',[$request->fecha_inicial, $request->fecha_final])
    ->where('A.empleado_id','=',$request->empleado_id)
    ->where([
      ['C.proyecto_id', $request->proyecto_id == 0 ? '>' : '=', $request->proyecto_id],
      ['C.empresa_id', $request->empresa_id == 0 ? '>' : '=', $request->empresa_id],
    ])
    ->orderBy('id', 'asc')->get();
    return response()->json($retardo);
  }


  /**
   * [descargaRetardo description]
   * @param  [string] $id [description]
   * @return [array]  $arreglo   []
   */
  public function descargaRetardo($id)
  {
    $arreglo = [];
    $valores = explode('&',$id);
    $asistencias = DB::table('asistencias')
    ->leftJoin('contratos AS C','C.id','=','asistencias.contrato_id')
    ->leftJoin('proyectos AS P','P.id','=','C.proyecto_id')
    ->select('P.*')
    ->whereBetween('asistencias.fecha',[$valores[0], $valores[1]])
    ->where([
      ['C.proyecto_id', $valores[2] == 0 ? '>' : '=', $valores[2]],
      ['C.empresa_id', $valores[3] == 0 ? '>' : '=', $valores[3]],
    ])
    ->distinct()->get();

    foreach ($asistencias as $key => $value) {
      $retardo = DB::table('retardos')
      ->leftJoin('asistencias AS A','A.id','=','retardos.asistencia_id')
      ->leftJoin('empleados AS E','E.id','=','A.empleado_id')
      ->leftJoin('contratos AS C','C.id','=','A.contrato_id')
      ->leftJoin('proyectos AS P','P.id','=','C.proyecto_id')
      ->leftJoin('empresas AS EM','EM.id','=','C.proyecto_id')
      ->leftJoin('dias AS D','D.id','=','A.dia_id')
      ->leftJoin('registros AS R','R.id','=','A.registro_id')
      ->select('retardos.*', DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno ) AS empleado"),'A.horas_trabajadas','A.fecha','D.nombre','EM.nombre AS nombre_empresa')
      ->whereBetween('A.fecha',[$valores[0], $valores[1]])
      ->where([
        ['C.proyecto_id', $valores[2] == 0 ? '>' : '=', $valores[2]],
        ['C.empresa_id', $valores[3] == 0 ? '>' : '=', $valores[3]],
      ])
      ->where('P.id','=',$value->id)
      ->get();
      $arreglo [] = [
        'asistencias' => $value,
        'retardos' => $retardo,
      ];
    }

    $pdfdbem = PDF::loadView('pdf.retardos', compact('arreglo'));
    $pdfdbem->setPaper("letter","landscape");

    return $pdfdbem->stream();
  }

}
