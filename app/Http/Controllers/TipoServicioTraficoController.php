<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoServicioTrafico;
use Validator;
use Illuminate\Validation\Rule;

class TipoServicioTraficoController extends Controller
{
    protected $rules = array(
        'nombre' => 'required|max:50',
    );

    public function index(Request $request)
    {
        $tipoServicio = TipoServicioTrafico::orderBy('id', 'desc')->get()->toArray();
        return response()->json($tipoServicio);
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

        $tipoServicio = new TipoServicioTrafico();
        $tipoServicio->nombre = $request->nombre;
        $tipoServicio->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $tipoServicio = TipoServicioTrafico::findOrFail($request->id);
        $tipoServicio->nombre = $request->nombre;
        $tipoServicio->save();

        return response()->json(array(
            'status' => true
        ));
    }

}
