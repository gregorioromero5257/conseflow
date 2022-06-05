<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoServicio;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class TipoServicioController extends Controller
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
        $tipoServicio = TipoServicio::select('tipo_servicio.*')
        ->orderBy('tipo_servicio.id', 'ASC')->get();
        return response()->json($tipoServicio);
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

        $tipoServicio = new TipoServicio();
        $tipoServicio-> nombre = $request->nombre;
        $tipoServicio->save();

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

        $tipoServicio = TipoServicio::findOrFail($request->id);
        $tipoServicio-> nombre = $request->nombre;        
        //Utilidades::auditar($accesorio, $empresas->id.','.$empresas->nombre);
        $tipoServicio->save();

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
