<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EstadoRequisicion;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class EstadoRequisicionController extends Controller
{
    protected $rules = array(
        'nombre' => 'required|max:45',
    );

    public function index(Request $request)
    {
        $edorequi = EstadoRequisicion::orderBy('id', 'desc')->get()->toArray();
        return response()->json($edorequi);
    }

    public function store(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');

        $this->rules['nombre'] = 'required|unique:estado_requisiciones,nombre,0,id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $edorequi = new EstadoRequisicion();
        $edorequi->nombre = $request->nombre;
        Utilidades::auditar($edorequi, $edorequi->id);
        $edorequi->save();

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

        $edorequi = EstadoRequisicion::findOrFail($request->id);
        $edorequi->nombre = $request->nombre;
        Utilidades::auditar($edorequi, $edorequi->id);
        $edorequi->save();

        return response()->json(array(
            'status' => true
        ));
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

    }

    public function getList(Request $request)
    {
        $edorequi = EstadoRequisicion::select('id', 'nombre')->orderBy('id', 'desc')->get()->toArray();
        return response()->json($edorequi);
    }

}
