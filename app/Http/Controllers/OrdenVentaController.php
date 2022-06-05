<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Validator;

class OrdenVentaController extends Controller
{

    protected $rules = array(
        'fecha' => 'required',
        'cliente_id' => 'required',
    );

    public function index()
    {
        $ventas = DB::table('ordenes_ventas AS v')
        ->join('clientes AS c','v.cliente_id','=','c.id')
        ->select('v.*', 'c.nombre AS razon_social', 'c.rfc AS rfc_receptor')
        ->get();
        return response()->json($ventas);
    }

    public function store(Request $request)
    {
        try{
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
        return response()->json(array(
            'status' => false,
            'errors' => $validator->errors()->all()
        ));
        }
        $venta = new \App\OrdenVenta();
        $folio = Utilidades::getFolioOrdenVenta();
        $venta->fecha = $request->fecha;
        $venta->folio = $folio['folio'];
        $venta->serie = $folio['serie'];
        $venta->cliente_id = $request->cliente_id;
        $venta->solicita_id = Auth::user()->empleado_id;
        Utilidades::auditar($venta, $venta->id);
        $venta->save();

        return response()->json(array('status' => true));
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

    }

    public function update(Request $request)
    {
        try{
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
        return response()->json(array(
            'status' => false,
            'errors' => $validator->errors()->all()
        ));
        }
        $venta = \App\OrdenVenta::where('id',$request->id)->first();
        $venta->fecha = $request->fecha;
        $venta->cliente_id = $request->cliente_id;
        Utilidades::auditar($venta, $venta->id);
        $venta->save();
        return response()->json(array('status' => true));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

}
