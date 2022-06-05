<?php

namespace App\Http\Controllers;

use App\TipoPercepciones;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;

class TipoPercepcionesController extends Controller
{
    /*Valida que el campo indicado no contenga nulos o vacíos*/
   protected $rules = array(
        'nombre' => 'required|max:45',
    );
   /*********************************************************/

    public function index(Request $request)
    {
        /*Obtiene todos los registros del tipo de percepciones*/
        $tiposPercepcion = TipoPercepciones::orderBy('id', 'ASC')->get()->toArray();
        return response()->json($tiposPercepcion);
        /******************************************************/
    }

    public function store(Request $request)
    {
        /*Inserta un nuevo registro en la BD*/
          //Valida si la peticion del request es por ajax
        if (!$request->ajax()) return redirect('/');
        //Valida que el campo no sea nulo o vacío
        $this->rules['nombre'] = 'required|unique:tipo_percepciones,nombre,0,id|max:200';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $tipoPercepcion = new TipoPercepciones();
        $tipoPercepcion->nombre = $request->nombre;
        $tipoPercepcion->valor =$request->valor;
        $tipoPercepcion->save();

        return response()->json(array(
            'status' => true
        ));
        /***********************************************/
    }

    public function update(Request $request)
    {
        /*Actualiza el registro en la BD*/
        if (!$request->ajax()) return redirect('/');

        $this->rules['nombre'] = 'required|unique:tipo_percepciones,nombre,'.$request->id.',id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $tipoPercepcion = TipoPercepciones::findOrFail($request->id);
        $tipoPercepcion->nombre = $request->nombre;
        $tipoPercepcion->valor = $request->valor;
        $tipoPercepcion->save();

        return response()->json(array(
            'status' => true
        ));
        /******************************/
    }

    public function getList(Request $request)
    {
        $tiposPercepcion = TipoPercepciones::select('id', 'nombre')->orderBy('id', 'desc')->get()->toArray();
        return response()->json($tiposPercepcion);
    }
}
