<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DireccionesAdministrativas;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class DireccionesAdministrativasController extends Controller
{
    protected $rules = array(
        'nombre' => 'required|max:45',
    );

    public function index(Request $request)
    {
        $direccionesAdministrativas = DireccionesAdministrativas::orderBy('id', 'desc')->get()->toArray();
        return response()->json($direccionesAdministrativas);
    }

    public function store(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');

        $this->rules['nombre'] = 'required|unique:direcciones_administrativas,nombre,0,id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $direccionAdministrativa = new DireccionesAdministrativas();
        $direccionAdministrativa->nombre = $request->nombre;
        Utilidades::auditar($direccionAdministrativa, $direccionAdministrativa->id);
        $direccionAdministrativa->save();

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

        $this->rules['nombre'] = 'required|unique:direcciones_administrativas,nombre,'.$request->id.',id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $direccionAdministrativa = DireccionesAdministrativas::findOrFail($request->id);
        $direccionAdministrativa->nombre = $request->nombre;
        Utilidades::auditar($direccionAdministrativa, $direccionAdministrativa->id);
        $direccionAdministrativa->save();

        return response()->json(array(
            'status' => true
        ));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function getList(Request $request)
    {
        $direccionesAdministrativas = DireccionesAdministrativas::select('id', 'nombre')->orderBy('id', 'desc')->get()->toArray();
        return response()->json($direccionesAdministrativas);
    }

}
