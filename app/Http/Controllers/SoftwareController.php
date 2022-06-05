<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Software;
use Validator;

class SoftwareController extends Controller
{
    protected $rules = array(
        'nombre' => 'required|max:100'
    );

    public function index()
    {
        $deptos = DB::table('departamentos')
            ->leftJoin('direcciones_administrativas', 'departamentos.direccion_administrativa_id', '=', 'direcciones_administrativas.id')
            ->select('departamentos.*', 'direcciones_administrativas.nombre AS direccion')
            ->get();

        return response()->json(
            $deptos->toArray()
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

        $depto = new Departamento();
        $depto->nombre = $request->nombre;
        $depto->direccion_administrativa_id = $request->direccion_administrativa_id;
        $depto->condicion = '1';
        $depto->save();

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

        $depto = Departamento::findOrFail($request->id);
        $depto->nombre = $request->nombre;
        $depto->direccion_administrativa_id = $request->direccion_administrativa_id;
        $depto->condicion = '1';
        $depto->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $depto = Departamento::findOrFail($request->id);
        $depto->condicion = '0';
        $depto->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $depto = Departamento::findOrFail($request->id);
        $depto->condicion = '1';
        $depto->save();
    }

    public function getList(Request $request)
    {
        $deptos = Departamento::select('id', 'nombre')->orderBy('id', 'desc')->get()->toArray();
        return response()->json($deptos);
    }

}
