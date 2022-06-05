<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoSW;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class TipoSWController extends Controller
{
     protected $rules = array(        
        'nombre'=> 'required|max:255',               
    );
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        $tipoSW = TipoSW::select('tipo_sw.*')
        ->orderBy('tipo_sw.id', 'ASC')->get();
        return response()->json($tipoSW);
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        $this->rules['nombre'] = 'required|unique:accesorio,tipo,0,id|max:255';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all() 
            ));
        }

        $tipoSW = new TipoSW();
        $tipoSW-> nombre = $request->nombre;
        $tipoSW->save();

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

        $tipoSW = TipoSW::findOrFail($request->id);
        $tipoSW-> nombre = $request->nombre;        
        //Utilidades::auditar($accesorio, $empresas->id.','.$empresas->nombre);
        $tipoSW->save();

        return response()->json(array(
            'status' => true,
        ));
    }         
    protected function busqueda_filter($data, $query, $fields)
    {
        return $data->where(function ($q) use ($query, $fields) {
            foreach ($fields as $index => $field) {
                $method = $index ? 'orWhere' : 'where';
                $q->{$method}($field, 'LIKE', "%{$query}%");
            }
        });
    }

}
