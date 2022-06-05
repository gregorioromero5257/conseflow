<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Escolaridade;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class EscolaridadesController extends Controller
{
    protected $rules = array(
        'titulo' => 'required|max:45',
        'cedula_prof'=> 'required|max:30',
    );

    public function index(Request $request, $id)
    {
        $escolaridades = Escolaridade::orderBy('id', 'desc')
        ->join('grados', 'grados.id', '=', 'escolaridades.grado_id')
        ->select('escolaridades.*', 'grados.nombre AS grado' )
        ->where('escolaridades.empleado_id', '=', $id)
        ->get()->toArray();
        return response()->json($escolaridades);
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

        $escolaridad = new Escolaridade();
        $escolaridad->titulo = $request->titulo;
        $escolaridad->cedula_prof = $request->cedula_prof;
        $escolaridad->fecha_inicio = $request->fecha_inicio;
        $escolaridad->fecha_termino = $request->fecha_termino;
        $escolaridad->grado_id = $request->grado_id;
        $escolaridad->empleado_id = $request->empleado_id;
        Utilidades::auditar($escolaridad, $escolaridad->id);
        $escolaridad->save();

        return response()->json(array(
            'status' => true
        ));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function edit($id)
    {
        $escolaridad = Escolaridade::findOrFail($id);
        if ($escolaridad->condicion==0) {
            $escolaridad->condicion = 1;
        }else{
            $escolaridad->condicion = 0;
        }
        Utilidades::auditar($escolaridad, $escolaridad->empleado_id.','.$escolaridad->id);
        $escolaridad->update();
        return $escolaridad;
    }

    public function show($id)
    {
        $escolaridad = Escolaridade::find($id);
        return response()->json($escolaridad);
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

        $escolaridad = Escolaridade::findOrFail($request->id);
        $escolaridad->titulo = $request->titulo;
        $escolaridad->cedula_prof = $request->cedula_prof;
        $escolaridad->fecha_inicio = $request->fecha_inicio;
        $escolaridad->fecha_termino = $request->fecha_termino;
        $escolaridad->grado_id = $request->grado_id;
        $escolaridad->empleado_id = $request->empleado_id;
        Utilidades::auditar($escolaridad, $escolaridad->empleado_id.','.$escolaridad->id);
        $escolaridad->save();

        return response()->json(array(
            'status' => true
        ));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function getList(Request $request)
    {
        $tiposUbicacion = TipoUbicacion::select('id', 'nombre')->orderBy('id', 'desc')->get()->toArray();
        return response()->json($tiposUbicacion);
    }
}
