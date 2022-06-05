<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Grupo;
use Validator;
use \App\Http\Helpers\Utilidades;

class GrupoController extends Controller
{
    protected $rules = array(
        'nombre' => 'required|max:50'
    );

    public function index()
    {
        $grupos = DB::table('grupos')
            ->join('categorias', 'categorias.id', '=', 'grupos.categoria_id')
            ->select('grupos.*', 'categorias.nombre AS categoria')
            ->get();

        return response()->json(
            $grupos->toArray()
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

        $grupo = new Grupo();
        $grupo->nombre = $request->nombre;
        $grupo->categoria_id = $request->categoria_id;
        $grupo->condicion = '1';
        Utilidades::auditar($grupo, $grupo->id);
        $grupo->save();

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

        $grupo = Grupo::findOrFail($request->id);
        $grupo->nombre = $request->nombre;
        $grupo->categoria_id = $request->categoria_id;
        $grupo->condicion = '1';
        Utilidades::auditar($grupo, $grupo->id);
        $grupo->save();

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
        $grupo = Grupo::findOrFail($request->id);
        $grupo->condicion = '0';
        $grupo->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $grupo = Grupo::findOrFail($request->id);
        $grupo->condicion = '1';
        $grupo->save();
    }

    public function getList(Request $request, $id)
    {
        $categorias = Grupo::select('id', 'nombre')->where('categoria_id', $id)->orderBy('id', 'desc')->get()->toArray();
        return response()->json($categorias);
    }

    public function find($id)
    {
      $grupo = Grupo::where('id','=',$id)->first();
      return response()->json($grupo);

    }

}
