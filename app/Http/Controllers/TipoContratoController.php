<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoContrato;
use Validator;
use Illuminate\Validation\Rule;

class TipoContratoController extends Controller
{
    protected $rules = array(
        'nombre' => 'required|max:45',
    );

    public function index(Request $request)
    {
        $tiposContrato = TipoContrato::orderBy('id', 'desc')->get()->toArray();
        return response()->json($tiposContrato);
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

        $tipoContrato = new TipoContrato();
        $tipoContrato->nombre = $request->nombre;
        $tipoContrato->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $this->rules['nombre'] = 'required|unique:tipo_contratos,nombre,'.$request->id.',id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $tipoContrato = TipoContrato::findOrFail($request->id);
        $tipoContrato->nombre = $request->nombre;
        $tipoContrato->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function getList(Request $request)
    {
        $tiposContrato = TipoContrato::select('id', 'nombre')->orderBy('id', 'desc')->get()->toArray();
        return response()->json($tiposContrato);
    }

}
