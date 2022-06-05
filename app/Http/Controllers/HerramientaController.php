<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Herramienta;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class HerramientaController extends Controller
{
     protected $rules = array(
        'descripcion' => 'required|max:255',
        'cantidad' => 'required|between:0,99.9',
        'marca' => 'required',      
        'unidad' => 'required',
    );
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        $herramienta = Herramienta::select('herramienta.*')
        ->orderBy('herramienta.id', 'ASC')->get();
        return response()->json($herramienta);
    }

    public function store(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');

        $this->rules['descripcion'] = 'required|unique:herramienta,descripcion,0,id|max:45';
        $this->rules['cantidad'] = 'integer';
        $this->rules['marca'] = 'required';
        $this->rules['unidad'] = 'required';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all() 
            ));
        }

        $herramienta = new Herramienta();
        $herramienta->descripcion = $request->descripcion;
        $herramienta->cantidad = $request->cantidad;
        $herramienta->marca = $request->marca;
        $herramienta->unidad = $request->unidad;
        Utilidades::auditar($herramienta, $herramienta->id);
        $herramienta->save();

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

        $herramienta = Herramienta::findOrFail($request->id);
        $herramienta->descripcion = $request->descripcion;
        $herramienta->cantidad = $request->cantidad;
        $herramienta->marca = $request->marca;
        $herramienta->unidad = $request->unidad;
        Utilidades::auditar($herramienta, $herramienta->id.','.$herramienta->descripcion);
        $herramienta->save();

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
