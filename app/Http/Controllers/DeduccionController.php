<?php

namespace App\Http\Controllers;

use App\Deduccion;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;


class DeduccionController extends Controller
{
    protected $rules = array(
        'unidad' => 'required|max:45',
    );

    public function index(Request $request)
    {
        $tiposDeduccion = DB::table('deducciones')
           ->leftJoin('tipo_deducciones', 'tipo_deducciones.id', '=', 'deducciones.tipo_deduccione_id')
           ->select('deducciones.*', DB::raw('IFNULL(tipo_deducciones.nombre, "Sin Tipo") as td_nombre'))
           ->orderBy('deducciones.id', 'ASC') 
           ->get();
        return response()->json($tiposDeduccion);
    }

    public function store(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');

        $this->rules['unidad'] = 'required|unique:deducciones,unidad,0,id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $tipoDeduccion = new Deduccion();
        $tipoDeduccion->fill($request->all());
        Utilidades::auditar($tipoDeduccion, $tipoDeduccion->id);
        $tipoDeduccion->save();

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

        $this->rules['unidad'] = 'required|unique:deducciones,unidad,'.$request->id.',id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $tipoDeduccion = Deduccion::findOrFail($request->id);
        $tipoDeduccion->fill($request->all());
        Utilidades::auditar($tipoDeduccion, $tipoDeduccion->id);
        $tipoDeduccion->save();

        return response()->json(array(
            'status' => true
        ));
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }
}

