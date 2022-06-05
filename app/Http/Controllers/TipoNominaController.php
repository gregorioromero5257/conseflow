<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoNomina;
use Validator;
use Illuminate\Validation\Rule;

class TipoNominaController extends Controller
{
    /*Valida que el campo indicado no contenga nulos o vacíos*/
    protected $rules = array(
        'nombre' => 'required|max:45',
    );
    /********************************************************/

    public function index(Request $request)
    {
        /*Obtiene todos los registros del tipo de nomina*/
        $tiposNomina = TipoNomina::orderBy('id', 'desc')->get()->toArray();
        return response()->json($tiposNomina);
        /******************************************************/
    }

    public function store(Request $request)
    {
        /*Inserta un nuevo registro en la BD*/
        //Valida si la peticion del request es por ajax
        if (!$request->ajax()) return redirect('/');
        //
        //Valida que el campo no sea nulo o vacío
        $this->rules['nombre'] = 'required|unique:tipo_nomina,nombre,0,id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $tipoNomina = new TipoNomina();
        $tipoNomina->nombre = $request->nombre;
        $tipoNomina->save();

        return response()->json(array(
            'status' => true
        ));
        /****************************************/
    }

    public function update(Request $request)
    {
        /*Actualiza el registro en la BD*/
        if (!$request->ajax()) return redirect('/');

        $this->rules['nombre'] = 'required|unique:tipo_nomina,nombre,'.$request->id.',id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $tipoNomina = TipoNomina::findOrFail($request->id);
        $tipoNomina->nombre = $request->nombre;
        $tipoNomina->save();

        return response()->json(array(
            'status' => true
        ));
        /********************************/
    }

    public function getList(Request $request)
    {
        $tiposNomina = TipoNomina::select('id', 'nombre')->orderBy('id', 'desc')->get()->toArray();
        return response()->json($tiposNomina);
    }

}
