<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AreaSolicitante;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;


class AreaSolicitanteController extends Controller
{
    protected $rules = array(
        'nombre' => 'required|max:45',
    );

    public function index(Request $request)
    {
        $areasol = AreaSolicitante::orderBy('id', 'desc')->get()->toArray();
        return response()->json($areasol);
    }

    public function store(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');

        $this->rules['nombre'] = 'required|unique:area_solicitante,nombre,0,id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $areasol = new AreaSolicitante();
        $areasol->nombre = $request->nombre;
        Utilidades::auditar($areasol, $areasol->id);
        $areasol->save();

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

        $areasol = AreaSolicitante::findOrFail($request->id);
        $areasol->nombre = $request->nombre;
        Utilidades::auditar($areasol, $areasol->id);
        $areasol->save();

        return response()->json(array(
            'status' => true
        ));
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function getList(Request $request)
    {
        $areasol = AreaSolicitante::select('id', 'nombre')->orderBy('id', 'desc')->get()->toArray();
        return response()->json($areasol);
    }

}
