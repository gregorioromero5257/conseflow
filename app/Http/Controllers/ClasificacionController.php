<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clasificacion;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class ClasificacionController extends Controller
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
        $clasificacion = Clasificacion::select('clasificacion.*')
        ->orderBy('clasificacion.id', 'ASC')->get();
        return response()->json($clasificacion);
    }

    public function store(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');
        
        $this->rules['nombre'] = 'required|unique:accesorio,tipo,0,id|max:255';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all() 
            ));
        }

        $clasificacion = new Clasificacion();
        $clasificacion-> nombre = $request->nombre;
        Utilidades::auditar($clasificacion, $clasificacion->id);
        $clasificacion->save();

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

        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $clasificacion = Clasificacion::findOrFail($request->id);
        $clasificacion-> nombre = $request->nombre;        
        //Utilidades::auditar($accesorio, $empresas->id.','.$empresas->nombre);
        Utilidades::auditar($compra, $compra->id);
        $clasificacion->save();

        return response()->json(array(
            'status' => true,
        ));
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
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
