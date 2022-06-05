<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Banco;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class BancoController extends Controller
{
  /**
   * [Reglas para el guardado y la actualizacion de registros en la BD]
   */
    protected $rules = array(
        'nombre' => 'required|max:45',
    );

    /**
     * [Obtencion de datos del modelo Banco]
     * @param  Request $request [URL del GET]
     * @return Response           [Array en formato JSON]
     */
    public function index(Request $request)
    {
        $bancos = Banco::orderBy('id', 'ASC')->get()->toArray();
        return response()->json($bancos);
    }

    /**
     * [Guardado en la DB]
     * @param  Request $request [Objeto del POST]
     * @return Response           [Array con estatus true y $validator con sus reglas]
     */
    public function store(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');

        $this->rules['nombre'] = 'required|unique:catalogo_bancos,nombre,0,id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $banco = new Banco();
        $banco->nombre = $request->nombre;
        Utilidades::auditar($banco, $banco->id);
        $banco->save();

        return response()->json(array(
            'status' => true
        ));
          } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    /**
     * [Actualiza un registro buscado por un id en la base de datos ]
     * @param  Request $request [Objeto del PUT]
     * @return Response          [Array con estatus true y $validator con sus reglas]
     */
    public function update(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');

        $this->rules['nombre'] = 'required|unique:catalogo_bancos,nombre,'.$request->id.',id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }
        $banco = Banco::findOrFail($request->id);
        $banco->nombre = $request->nombre;
        Utilidades::auditar($banco, $banco->id);
        $banco->save();

        return response()->json(array(
            'status' => true
        ));
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    /**
     * [Obtiene un listado especifico de la BD]
     * @param  Request $request [URL del GET]
     * @return Response           [Array en formato JSON]
     */
    public function getList(Request $request)
    {
        $bancos = Bancos::select('id', 'nombre')->orderBy('id', 'desc')->get()->toArray();
        return response()->json($bancos);
    }
}
