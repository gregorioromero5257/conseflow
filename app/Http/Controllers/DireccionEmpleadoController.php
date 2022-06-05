<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DireccionEmpleado;
use Validator;
use Illuminate\Validation\Rule;
use Rap2hpoutre\FastExcel\FastExcel;
use \App\Http\Helpers\Utilidades;


class DireccionEmpleadoController extends Controller
{
    protected $rules = array(
        'codigo_postal' => 'required|max:5',
        'numero_exterior' => 'required|max:5',
        'numero_interior' => 'max:10',
        'calle' => 'required|max:60',
        'localidad' => 'max:60',
        'entidad_federativa' => 'max:60',
        'entre_que_calles' => 'max:255',
        'tipo_avenidad' => 'max:60',
        'colonia' => 'max:60',
        'municipio' => 'max:60',
    );

    public function index(Request $request, $id)
    {
        $DirEmpleado = DireccionEmpleado::orderBy('id', 'desc')
        ->where('empleado_id', '=', $id)
        ->where('condicion', 1)
        ->get()->toArray();
        return response()->json($DirEmpleado);


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
        $DirEmpleado = new DireccionEmpleado();
        $DirEmpleado->fill($request->all());
        Utilidades::auditar($DirEmpleado, $DirEmpleado->id);
        $DirEmpleado->save();
        return response()->json(array(
            'status' => true
        ));
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function edit($id)
    {
        try{
        $DirEmpleado = DireccionEmpleado::findOrFail($id);
        if ($DirEmpleado->condicion==0) {
            $DirEmpleado->condicion = 1;
        }else{
            $DirEmpleado->condicion = 0;
        }
        Utilidades::auditar($DirEmpleado, $DirEmpleado->id);
        $DirEmpleado->update();
        return $DirEmpleado;
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
        $DirEmpleado = DireccionEmpleado::findOrFail($request->id);
        $DirEmpleado->fill($request->all());
        Utilidades::auditar($DirEmpleado, $DirEmpleado->id);
        $DirEmpleado->save();

        return response()->json(array(
            'status' => true,
        ));
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

    }

    public function get(Request $request, $id)
    {

    }

}
