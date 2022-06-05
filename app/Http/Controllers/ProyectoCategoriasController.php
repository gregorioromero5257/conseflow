<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProyectoCategoria;
use Validator;
use Illuminate\Validation\Rule;

class ProyectoCategoriasController extends Controller
{
    protected $rules = array(
        'nombre' => 'required|max:45',
    );

    public function index(Request $request)
    {
        $proyectoCategoria = ProyectoCategoria::orderBy('id', 'desc')->get()->toArray();
        return response()->json($proyectoCategoria);
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

        $proyectoCategoria = new ProyectoCategoria();
        $proyectoCategoria->nombre = $request->nombre;
        $proyectoCategoria->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $proyectoCategoria = ProyectoCategoria::findOrFail($request->id);
        $proyectoCategoria->nombre = $request->nombre;
        $proyectoCategoria->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function getList(Request $request)
    {
        $proyectoCategoria = ProyectoCategoria::select('id', 'nombre')->orderBy('id', 'desc')->get()->toArray();
        return response()->json($proyectoCategoria);
    }

}
