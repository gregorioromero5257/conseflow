<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoCompra;
use Validator;
use Illuminate\Validation\Rule;

class TipoCompraController extends Controller
{
    protected $rules = array(
        'nombre' => 'required|max:45',
    );

    public function index(Request $request)
    {
        $tipocompra = TipoCompra::orderBy('id', 'desc')->get()->toArray();
        return response()->json($tipocompra);
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $this->rules['nombre'] = 'required|unique:tipo_contratos,nombre,0,id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $tipoCompra = new TipoCompra();
        $tipoCompra->nombre = $request->nombre;
        $tipoCompra->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $tipoCompra = TipoCompra::findOrFail($request->id);
        $tipoCompra->nombre = $request->nombre;
        $tipoCompra->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function getList(Request $request)
    {
        $tipocompra = TipoCompra::select('id', 'nombre')->orderBy('id', 'desc')->get()->toArray();
        return response()->json($tipocompra);
    }

}
