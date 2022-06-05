<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;
use Validator;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class CategoriaController extends Controller
{
    protected $rules = array(
        'descripcion' => 'required|max:255',
    );

    public function index(Request $request)
    {
        $categorias = Categoria::orderBy('id', 'desc')->get()->toArray();
        return response()->json($categorias);
    }

    public function store(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');

        $this->rules['nombre'] = 'required|unique:categorias,nombre,0,id|max:50';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $categoria = new Categoria();
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion = '1';
        Utilidades::auditar($categoria, $categoria->id);
        $categoria->save();

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

        $this->rules['nombre'] = 'required|unique:categorias,nombre,'.$request->id.',id|max:50';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $categoria = Categoria::findOrFail($request->id);
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion = '1';
        Utilidades::auditar($categoria, $categoria ->id);
        $categoria->save();

        return response()->json(array(
            'status' => true
        ));
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function desactivar(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');
        $categoria = Categoria::findOrFail($request->id);
        $categoria->condicion = '0';
        Utilidades::auditar($categoria, $categoria->id);
        $categoria->save();
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function activar(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');
        $categoria = Categoria::findOrFail($request->id);
        $categoria->condicion = '1';
        Utilidades::auditar($categoria, $categoria->id);
        $categoria->save();
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function getList(Request $request)
    {
        $categorias = Categoria::select('id', 'nombre')->orderBy('id', 'desc')->get()->toArray();
        return response()->json($categorias);
    }

}
