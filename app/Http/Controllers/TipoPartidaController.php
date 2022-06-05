<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoPartida;
use Validator;
use Illuminate\Validation\Rule;

class TipoPartidaController extends Controller
{
    protected $rules = array(
        'nombre' => 'required|max:45',
    );

    public function index(Request $request)
    {
        $tipoPartida = TipoPartida::orderBy('id', 'desc')->get()->toArray();
        return response()->json($tipoPartida);
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $tipoPartida = new TipoPartida();
        $tipoPartida->nombre = $request->nombre;
        $tipoPartida->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $tipoPartida = TipoPartida::findOrFail($request->id);
        $tipoPartida->nombre = $request->nombre;
        $tipoPartida->save();

        return response()->json(array(
            'status' => true
        ));
    }

}
