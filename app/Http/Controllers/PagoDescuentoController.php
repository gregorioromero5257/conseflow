<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Descuento;
use App\PagoDescuento;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class PagoDescuentoController extends Controller
{
    // protected $rules = array(
    //     'nombre' => 'required|max:50',
    //     'edad' => 'required|max:3',
    //
    // );

    public function index()
    {
      $arreglo = [];
        $pago_descuento = DB::table('descuentos')
        // ->join('pagos_descuentos', 'pagos_descuentos.descuento_id', '=', 'descuentos.id')
        ->join('empleados', 'empleados.id', '=' , 'descuentos.empleado_id')
        ->select('empleados.*')
        ->distinct()->get();

          foreach ($pago_descuento as $key => $empleado) {
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


            $arreglo[] = [
                'empleado' => $empleado,
                'puesto' => $puestos[0],
                'departamento' => $departamentos[0],

            ];

          }

      return response()->json($arreglo);



    }

    public function store(Request $request)
    {
      try{
        if (!$request->ajax()) return redirect('/');
        // $validator = Validator::make($request->all(), $this->rules);
        //
        // if ($validator->fails()) {
        //     return response()->json(array(
        //         'status' => false,
        //         'errors' => $validator->errors()->all()
        //     ));
        // }
        $pago_descuento = new PagoDescuento();
        $pago_descuento->fill($request->all());
        Utilidades::auditar($pago_descuento, $pago_descuento->id);
        $pago_descuento->save();
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

        // $validator = Validator::make($request->all(), $this->rules);
        //
        // if ($validator->fails()) {
        //     return response()->json(array(
        //         'status' => false,
        //         'errors' => $validator->errors()->all()
        //     ));
        // }

        $pago_descuento = PagoDescuento::findOrFail($request->id);
        $pago_descuento->fill($request->all());
        Utilidades::auditar($pago_descuento, $pago_descuento->id);
        $pago_descuento->save();

        return response()->json(array(
            'status' => true
        ));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }



    public function edit($id)
    {

    }

    public function show($id)
    {
      $pago_descuento = DB::table('pagos_descuentos')
      ->join('descuentos', 'descuentos.id', '=', 'pagos_descuentos.descuento_id')
      ->select('pagos_descuentos.*',
     'descuentos.id AS Did',
    'descuentos.fecha AS Dfecha',
    'descuentos.unidad AS Dunidad',
    'descuentos.total AS Dtotal',
    'descuentos.numero_pago AS Dnumero_pago',
      'descuentos.empleado_id AS Dempleadoid',
      'descuentos.numero_pago AS numeropagodescueto'
            )
            ->where('pagos_descuentos.descuento_id','=',$id)
      ->get();
      $total = DB::table('pagos_descuentos')
      ->join('descuentos', 'descuentos.id', '=', 'pagos_descuentos.descuento_id')
      ->select(DB::raw('SUM(pagos_descuentos.cantidad) AS total')
      )->where('pagos_descuentos.descuento_id','=',$id)->get();
      $dif = DB::table('descuentos')
      ->select(DB::raw('descuentos.total AS dif')
      )->where('descuentos.id','=',$id)->get();
      $total_pagado = floatval($total[0]->total);
      $total_deuda = floatval($dif[0]->dif);
      $total_restante = $total_deuda - $total_pagado;
      $pagos [] = [
        'totalp' => $total_pagado,
        'totald' => $total_deuda,
        'totalr' => $total_restante,
      ];


      $array = [
        'pago' => $pago_descuento,
        'detalle'=> $pagos

      ];
       return response()->json($array);
    }



    public function activar(Request $request)
    {

    }



}
