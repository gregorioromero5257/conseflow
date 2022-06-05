<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Correo;
use App\Empleado;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class CorreosController extends Controller
{
    protected $rules = array(
        'nombre' => 'required|max:60',
        // 'edad' => 'required|max:3',

    );

    public function index(Request $request, $id)
    {
        $correos = Correo::orderBy('id', 'desc')
        ->where('empleado_id', '=', $id)
        ->get();
        return response()->json(
            $correos->toArray()
        );
    }

    public function store(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }
        $correo = new Correo();
        $correo->fill($request->all());
        Utilidades::auditar($correo, $correo->id);
        $correo->save();
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

        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $correo = Correo::findOrFail($request->id);
        $correo->fill($request->all());
        Utilidades::auditar($correo, $correo->id);
        $correo->save();

        return response()->json(array(
            'status' => true
        ));
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }



    public function edit($id)
    {
        $correo = Correo::findOrFail($id);
        if ($correo->condicion==0) {
            $correo->condicion = 1;
        }else{
            $correo->condicion = 0;
        }
        $correo->update();
        return $correo;
    }

    public function show($id)
    {
        $correos = Correo::orderBy('id', 'desc')

        ->get();
        return response()->json(
            $correos->toArray()
        );
    }
}
