<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Puesto;
use Validator;
use \App\Http\Helpers\Utilidades;

class PuestoController extends Controller
{
    protected $rules = array(
        'nombre' => 'required|max:50'        
    );

    public function index()
    {
        $puestos = DB::table('puestos')
            ->leftJoin('departamentos', 'puestos.departamento_id', '=', 'departamentos.id')
            ->leftJoin('direcciones_administrativas', 'departamentos.direccion_administrativa_id', '=', 'direcciones_administrativas.id')
            ->select('puestos.*', 'departamentos.nombre AS departamento', 'direcciones_administrativas.nombre AS direccion')
            ->get();

        return response()->json(
            $puestos->toArray()
        );
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

        $puesto = new Puesto();
        $puesto->nombre = $request->nombre;
        $puesto->area = $request->area;
        $puesto->departamento_id = $request->departamento_id;
        $puesto->nivel_o = $request->nivel_o;
        $puesto->condicion = '1';
        Utilidades::auditar($puesto, $puesto->id);
        $puesto->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $puesto = Puesto::findOrFail($request->id);
        $puesto->nombre = $request->nombre;
        $puesto->area = $request->area;
        $puesto->departamento_id = $request->departamento_id;
        $puesto->nivel_o = $request->nivel_o;
        $puesto->condicion = '1';
        Utilidades::auditar($puesto, $puesto->id);
        $puesto->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $puesto = Puesto::findOrFail($request->id);
        $puesto->condicion = '0';
        $puesto->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $puesto = Puesto::findOrFail($request->id);
        $puesto->condicion = '1';
        $puesto->save();
    }

    public function getList(Request $request)
    {
        $puestos = Puesto::select('id', 'nombre')->orderBy('id', 'desc')->get()->toArray();
        return response()->json($puestos);
    }
}
