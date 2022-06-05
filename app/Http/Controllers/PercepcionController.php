<?php

namespace App\Http\Controllers;

use App\Percepcion;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PercepcionController extends Controller
{
    protected $rules = array(
        'unidad' => 'required|max:45',
    );

    public function index(Request $request)
    {
        $tiposPercepcion = DB::table('percepciones')
           ->leftJoin('tipo_percepciones', 'tipo_percepciones.id', '=', 'percepciones.tipo_percepcione_id')
           ->select('percepciones.*', DB::raw('IFNULL(tipo_percepciones.nombre, "Sin Tipo") as tp_nombre'))
           ->orderBy('percepciones.id', 'ASC') 
           ->get();
        return response()->json($tiposPercepcion);
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $this->rules['unidad'] = 'required|unique:percepciones,unidad,0,id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $tipoPercepcion = new Percepcion();
        $tipoPercepcion->fill($request->all());
        $tipoPercepcion->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $this->rules['unidad'] = 'required|unique:percepciones,unidad,'.$request->id.',id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $tipoPercepcion = Percepcion::findOrFail($request->id);
        $tipoPercepcion->fill($request->all());
        $tipoPercepcion->save();

        return response()->json(array(
            'status' => true
        ));
    }
}
