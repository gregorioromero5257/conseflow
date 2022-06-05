<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Horario;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;

class HorarioController extends Controller
{
    protected $rules = array(
        'hora_entrada' => 'required',
        'hora_salida' => 'required'
    );

    public function index(Request $request)
    {
        $horarios = Horario::orderBy('id', 'ASC')
        ->join('tipo_horario', 'horarios.tipo_horario_id', '=', 'tipo_horario.id')
        ->join('dias', 'horarios.dia_id', 'dias.id')
        ->select('horarios.*', 'tipo_horario.nombre AS tipo', 'dias.nombre AS dia')
        ->get()->toArray();
        return response()->json($horarios);
    }

    public function create()
    {
        $dias = DB::table('dias')
        ->select('dias.*')
        ->orderBy('dias.id', 'desc')
        ->get();

        return response()->json($dias);
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

        $horario = new Horario();
        $horario->hora_entrada = $request->hora_entrada;
        $horario->hora_salida_comida = $request->hora_salida_comida;
        $horario->hora_entrada_comida = $request->hora_entrada_comida;
        $horario->hora_salida = $request->hora_salida;
        $horario->tipo_horario_id = $request->tipo_horario_id;
        $horario->dia_id = $request->dia_id;
        Utilidades::auditar($horario, $horario->id);
        $horario->save();

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

        $horario = Horario::findOrFail($request->id);
        $horario->hora_entrada = $request->hora_entrada;
        $horario->hora_salida_comida = $request->hora_salida_comida;
        $horario->hora_entrada_comida = $request->hora_entrada_comida;
        $horario->hora_salida = $request->hora_salida;
        $horario->tipo_horario_id = $request->tipo_horario_id;
        $horario->dia_id = $request->dia_id;
        Utilidades::auditar($horario, $horario->id);
        $horario->save();

        return response()->json(array(
            'status' => true
        ));
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function getList(Request $request)
    {
        $horario = Horario::select('id', 'hora_entrada', 'hora_salida')->orderBy('id', 'desc')->get()->toArray();
        return response()->json($horario);
    }

}
