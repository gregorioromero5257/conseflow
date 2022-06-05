<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Descuento;
use App\Empleado;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class DescuentoController extends Controller
{
  /**
   * [protected Reglas para el guardado y actualizacion de registros]
   * @var [type]
   */
  protected $rules = array(
    'unidad' => 'required|max:4',
  );

  /**
   * [index Consulta en la BD de la tabla descuentos y sus respectivos pagos]
   * @param  Request $request [description]
   * @param  Int  $id      [description]
   * @return Response           [description]
   */
  public function index(Request $request, $id)
  {
    $descuento = DB::table('descuentos')
    ->join('empleados AS Empleado', 'Empleado.id', '=', 'descuentos.empleado_id')
    ->join('tipo_descuentos AS TipoDescuento','TipoDescuento.id', '=', 'descuentos.tipo_descuento_id')
    ->select('descuentos.*',
    DB::raw("CONCAT(Empleado.nombre,' ',Empleado.ap_paterno,' ',Empleado.ap_materno) AS empleado"),
    DB::raw("CONCAT(TipoDescuento.nombre) AS descuento"))
    ->where('descuentos.empleado_id', '=', $id)
    ->get();
    foreach ($descuento as $key => $value) {
      $descuentoid = $value->id;
      $pagos_descuento = DB::table('pagos_descuentos')
      ->select('pagos_descuentos.*')
      ->where('pagos_descuentos.descuento_id', '=', $descuentoid)
      ->get()->last();
      $pdnumeropago = '';
      if (!is_null($pagos_descuento)) {$pdnumeropago = $pagos_descuento->numero_pago;   }else { $pdnumeropago = '0';  }
      $pagosc = $pdnumeropago.' de '.$value->numero_pago;
      $arreglo [] =[
        'descuento' => $value,
        'pago' => $pagos_descuento,
        'c' => $pagosc,
      ];

    }

    return response()->json(
      $arreglo
    );


  }

  /**
   * [store Insercion de un registro en la BD]
   * @param  Request $request [description]
   * @return Response           [description]
   */
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
    $descuento = new Descuento();
    $descuento->fill($request->all());
    Utilidades::auditar($descuento, $descuento->id);
    $descuento->save();
    return response()->json(array(
      'status' => true
    ));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  /**
   * [update actualizacion de un registro en la BD]
   * @param  Request $request [description]
   * @param  Int  $id      [description]
   * @return Response           [description]
   */
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

    $descuento = Descuento::findOrFail($request->id);
    $descuento->fill($request->all());
    Utilidades::auditar($descuento, $descuento->id);
    $descuento->save();

    return response()->json(array(
      'status' => true
    ));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  /**
   * [edit description]
   * @param  [type] $id [description]
   * @return [type]     [description]
   */
  public function edit($id)
  {

  }

  /**
   * [show Consulta en la BD de la tabla descuentos]
   * @param  Request $request [description]
   * @return Response           [description]
   */
  public function show(Request $request)
  {
    $descuento = DB::table('descuentos')
    ->join('empleados AS Empleado', 'Empleado.id', '=', 'descuentos.empleado_id')
    ->join('tipo_descuentos AS TipoDescuento','TipoDescuento.id', '=', 'descuentos.tipo_descuento_id')
    ->select('descuentos.*',
    DB::raw("CONCAT(Empleado.nombre,' ',Empleado.ap_paterno,' ',Empleado.ap_materno) AS empleado"),
    DB::raw("CONCAT(TipoDescuento.nombre) AS descuento"))
    ->get();

    return response()->json(
      $descuento->toArray()
    );

  }



  public function activar(Request $request)
  {

  }


}
