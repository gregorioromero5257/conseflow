<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conocido;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class ConocidoController extends Controller
{
    protected $rules = array(
        'nombre' => 'required|max:45',
        'puesto' => 'required|max:45',
        'tiempo_conocer' => 'required|max:20',
        'motivo' => 'required|max:8',
    );

    public function index(Request $request, $id)
    {
        $conocidos = Conocido::orderBy('id', 'desc')
            ->where('empleado_id', '=', $id)
            ->where('estado', 1)
            ->get()->toArray();
        return response()->json($conocidos);
    }

    public function store(Request $request)
    {
        try
        {
            if (!$request->ajax()) return redirect('/');

            $validator = Validator::make($request->all(), $this->rules);

            if ($validator->fails())
            {
                return response()->json(array(
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ));
            }

            $conocido = new Conocido();
            $conocido->nombre = $request->nombre;
            $conocido->puesto = $request->puesto;
            $conocido->tiempo_conocer = $request->tiempo_conocer;
            $conocido->motivo = $request->motivo;
            $conocido->empleado_id = $request->empleado_id;
            Utilidades::auditar($conocido, $conocido->id);
            $conocido->save();

            return response()->json(array(
                'status' => true
            ));
        }
        catch (\Throwable $e)
        {
            Utilidades::errors($e);
            return response()->json(["status"=>false]);
        }
    }

    public function update(Request $request)
    {
        try
        {
            if (!$request->ajax()) return redirect('/');

            $validator = Validator::make($request->all(), $this->rules);

            if ($validator->fails())
            {
                return response()->json(array(
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ));
            }

            $conocido = Conocido::findOrFail($request->id);
            $conocido->nombre = $request->nombre;
            $conocido->puesto = $request->puesto;
            $conocido->tiempo_conocer = $request->tiempo_conocer;
            $conocido->motivo = $request->motivo;
            $conocido->empleado_id = $request->empleado_id;
            Utilidades::auditar($conocido, $conocido->empleado_id . ',' . $conocido->id);
            $conocido->save();

            return response()->json(array(
                'status' => true
            ));
        }
        catch (\Throwable $e)
        {
            Utilidades::errors($e);
        }
    }

    public function Eliminar(Request $request)
    {
        try
        {
            $conocido=Conocido::findOrFail($request->id);
            $conocido->estado=0;
            $conocido->save();
            return response()->json(["status"=>true]);
        }catch (\Throwable $e)
        {
            Utilidades::errors($e);
            return response()->json(["status"=>false]);
        }
    }
}
