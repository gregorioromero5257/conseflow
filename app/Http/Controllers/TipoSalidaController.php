<?php

namespace App\Http\Controllers;

use App\TipoSalida;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;

class TipoSalidaController extends Controller
{
   protected $rules = array(
        'nombre' => 'required|max:45',
    );

    public function index(Request $request)
    {
        $tipoSalida = TipoSalida::orderBy('id', 'ASC')->get()->toArray();
        return response()->json($tipoSalida);
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $this->rules['nombre'] = 'required|unique:tipo_percepciones,nombre,0,id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $tipoSalida = new TipoSalida();
        $tipoSalida->nombre = $request->nombre;
        $tipoSalida->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $this->rules['nombre'] = 'required|unique:tipo_percepciones,nombre,'.$request->id.',id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $tipoSalida = TipoSalida::findOrFail($request->id);
        $tipoSalida->nombre = $request->nombre;
        $tipoSalida->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function getList(Request $request)
    {
        $tiposPercepcion = TipoSalida::select('id', 'nombre')->orderBy('id', 'desc')->get()->toArray();
        return response()->json($tiposPercepcion);
    }
}
