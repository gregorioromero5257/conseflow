<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Prestamo;
use App\PagoPrestamo;
use App\Empleado;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class PagoPrestamoController extends Controller
{
  protected $rules = array(
    'cantidad_a_pagar' => 'required|max:8',
  );

  public function index()
  {
    $arreglo = [];
    $pagoprestamo = DB::table('prestamos')
    ->leftJoin('empleados', 'empleados.id', '=' , 'prestamos.empleado_id')
    ->select('empleados.*')
    ->distinct()->get();

    foreach ($pagoprestamo as $key => $empleado) {
      $puestoid = $empleado->puesto_id;
      $estadosid = $empleado->edo_civil_id;

      if (!is_null($puestoid)) {
        $puestos = DB::table('puestos')
        ->select('puestos.*')
        ->where('puestos.id', '=', $puestoid)
        ->get();
        $departamentoid = $puestos[0]->departamento_id;
        if (!is_null($departamentoid)) {
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

  public function store(Request $request)
  {
    try{
    if (!$request->ajax()) return redirect('/');
    $validator = Validator::make($request->all(), $this->rules);

    if ($validator->fails()) {
      return response()->json(array(
        'status' => false,
        'errors' => $validator->errors()->all()
      ));
    }
    $pagoprestamo = new PagoPrestamo();
    $pagoprestamo->fill($request->all());
     Utilidades::auditar($pagoprestamo, $pagoprestamo->id);
    $pagoprestamo->save();
    return response()->json(array(
      'status' => true
    ));
     } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }


  public function update(Request $request, $id)
  {
    try{
    if (!$request->ajax()) return redirect('/');

    $validator = Validator::make($request->all(), $this->rules);

    if ($validator->fails()) {
      return response()->json(array(
        'status' => false,
        'errors' => $validator->errors()->all()
      ));
    }

    $pagoprestamo = PagoPrestamo::findOrFail($request->id);
    $pagoprestamo->fill($request->all());
    Utilidades::auditar($pagoprestamo, $pagoprestamo->id);
    $pagoprestamo->save();

    return response()->json(array(
      'status' => true
    ));
     } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }

  public function show($id)
  {
    $pagoprestamo = DB::table('pagos_prestamos')
    ->join('prestamos', 'prestamos.id', '=', 'pagos_prestamos.prestamo_id')
    ->select('pagos_prestamos.*',
    'prestamos.id AS Pid',
    'prestamos.cantidad AS Pcantidad',
    'prestamos.fecha AS Pfecha',
    'prestamos.observaciones AS Pobservaciones',
    'prestamos.adjunto AS Padjunto',
    'prestamos.empleado_id AS Dempleadoid'
    )
    ->where('pagos_prestamos.prestamo_id', '=' ,$id)
    ->get();

    $total = DB::table('pagos_prestamos')
    ->join('prestamos', 'prestamos.id', '=', 'pagos_prestamos.prestamo_id')
    ->select(DB::raw('SUM(pagos_prestamos.cantidad_a_pagar) AS total')
    )->where('pagos_prestamos.prestamo_id','=',$id)->get();
    $dif = DB::table('prestamos')
    ->select(DB::raw('prestamos.cantidad AS dif')
    )->where('prestamos.id','=',$id)->get();
    $total_pagado = floatval($total[0]->total);
    $total_deuda = floatval($dif[0]->dif);
    $total_restante = $total_deuda - $total_pagado;
    if ($total_restante <=0) {
      $prestamos = \App\Prestamo::where('id','=',$id)->first();
      $prestamos->pagado = 1;
      $prestamos->save();
    }
    $pagos [] = [
      'totalp' => $total_pagado,
      'totald' => $total_deuda,
      'totalr' => $total_restante,
    ];
    $array = [
      'pago' => $pagoprestamo,
      'detalle'=> $pagos

    ];

    return response()->json($array);
  }



  public function activar(Request $request)
  {

  }



}
