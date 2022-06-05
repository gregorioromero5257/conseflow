<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ExperienciasLaborale;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class ExperienciasLaboralesController extends Controller
{
    protected $rules = array(
        'empresa' => 'required',
        'puesto' => 'required|max:30',
        'jefe_inmediato' => 'required|max:60',
        'referencia' => 'required|max:60',
        'tel_referencia' => 'required|max:60',
        'actividades' => 'required|max:150',
    );

    public function index(Request $request, $id)
    {
        $experiencias = ExperienciasLaborale::orderBy('id', 'desc')
        ->where('empleado_id', '=', $id)
        ->get()->toArray();
        return response()->json($experiencias);
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

        $explab = new ExperienciasLaborale();
        $explab->empresa = $request->empresa;
        $explab->fecha_inicio = $request->fecha_inicio;
        $explab->jefe_inmediato = $request->jefe_inmediato;
        $explab->fecha_termino = $request->fecha_termino;
        $explab->referencia = $request->referencia;
        $explab->tel_referencia = $request->tel_referencia;
        $explab->puesto = $request->puesto;
        $explab->actividades = $request->actividades;
        $explab->empleado_id = $request->empleado_id;
        $explab->condicion = '1';
        Utilidades::auditar($explab, $explab->id);
        $explab->save();

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

        $explab = ExperienciasLaborale::findOrFail($request->id);
        $explab->empresa = $request->empresa;
        $explab->fecha_inicio = $request->fecha_inicio;
        $explab->jefe_inmediato = $request->jefe_inmediato;
        $explab->fecha_termino = $request->fecha_termino;
        $explab->referencia = $request->referencia;
        $explab->tel_referencia = $request->tel_referencia;
        $explab->puesto = $request->puesto;
        $explab->actividades = $request->actividades;
        $explab->empleado_id = $request->empleado_id;
        $explab->condicion = '1';
        Utilidades::auditar($explab, $explab->empleado_id.','.$explab->id);
        $explab->save();

        return response()->json(array(
            'status' => true
        ));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $explab = ExperienciasLaborale::findOrFail($request->id);
        $explab->condicion = '0';
        Utilidades::auditar($explab, $explab->empleado_id.','.$explab->id);
        $explab->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $explab = ExperienciasLaborale::findOrFail($request->id);
        $explab->condicion = '1';
        Utilidades::auditar($explab, $explab->empleado_id.','.$explab->id);
        $explab->save();
    }

}
