<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoUbicacion;
use Validator;
use Illuminate\Validation\Rule;

class TipoUbicacionController extends Controller
{
    protected $rules = array(
        'nombre' => 'required|max:45',
    );

    public function index(Request $request)
    {
        $tiposUbicacion = TipoUbicacion::orderBy('id', 'desc')->get()->toArray();
        return response()->json($tiposUbicacion);
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $this->rules['nombre'] = 'required|unique:tipo_ubicacion,nombre,0,id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $tipoUbicacion = new TipoUbicacion();
        $tipoUbicacion->nombre = $request->nombre;
        $tipoUbicacion->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $this->rules['nombre'] = 'required|unique:tipo_ubicacion,nombre,'.$request->id.',id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $tipoUbicacion = TipoUbicacion::findOrFail($request->id);
        $tipoUbicacion->nombre = $request->nombre;
        $tipoUbicacion->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function getList(Request $request)
    {
        $tiposUbicacion = TipoUbicacion::select('id', 'nombre')->orderBy('id', 'desc')->get()->toArray();
        return response()->json($tiposUbicacion);
    }

}
