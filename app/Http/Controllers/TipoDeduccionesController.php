<?php

namespace App\Http\Controllers;

use App\TipoDeducciones;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;

class TipoDeduccionesController extends Controller
{
   protected $rules = array(
        'nombre' => 'required|max:45',
    );

    public function index(Request $request)
    {
        $tiposDeduccion = TipoDeducciones::orderBy('id', 'ASC')->get()->toArray();
        return response()->json($tiposDeduccion);
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $this->rules['nombre'] = 'required|unique:tipo_deducciones,nombre,0,id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $tipoDeduccion = new TipoDeducciones();
        $tipoDeduccion->nombre = $request->nombre;
        $tipoDeduccion->valor = $request->valor;
        $tipoDeduccion->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $this->rules['nombre'] = 'required|unique:tipo_deducciones,nombre,'.$request->id.',id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $tipoDeduccion = TipoDeducciones::findOrFail($request->id);
        $tipoDeduccion->nombre = $request->nombre;
        $tipoDeduccion->valor = $request->valor;
        $tipoDeduccion->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function getList(Request $request)
    {
        $tiposDeduccion = TipoDeducciones::select('id', 'nombre')->orderBy('id', 'desc')->get()->toArray();
        return response()->json($tiposDeduccion);
    }
}
