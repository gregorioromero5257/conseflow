<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Grados;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class GradoController extends Controller
{
   protected $rules = array(
        'nombre' => 'required|max:45',
    );

    public function index(Request $request)
    {
        $tiposGrado = Grados::orderBy('id', 'ASC')->get()->toArray();
        return response()->json($tiposGrado);
    }

    public function store(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');

        $this->rules['nombre'] = 'required|unique:grados,nombre,0,id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $tipoGrado = new Grados();
        $tipoGrado->nombre = $request->nombre;
        Utilidades::auditar($tipoGrado, $tipoGrado->id);
        $tipoGrado->save();

        return response()->json(array(
            'status' => true
        ));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function update(Request $request)
    {   
        try{
        if (!$request->ajax()) return redirect('/');

        $this->rules['nombre'] = 'required|unique:grados,nombre,'.$request->id.',id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $tipoGrado = Grados::findOrFail($request->id);
        $tipoGrado->nombre = $request->nombre;
        Utilidades::auditar($tipoGrado, $tipoGrado->id);
        $tipoGrado->save();

        return response()->json(array(
            'status' => true
        ));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function getList(Request $request)
    {
        $tiposGrado = Grados::select('id', 'nombre')->orderBy('id', 'desc')->get()->toArray();
        return response()->json($tiposGrado);
    }
}