<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresas;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class EmpresasController extends Controller
{
     protected $rules = array(
        'nombre' => 'required|max:255',
        'razon' => 'required|max:255', 
        'rfc' => 'max:15',        
    );
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        $empresas = Empresas::select('empresas.*')
        ->orderBy('empresas.id', 'ASC')->get();
        return response()->json($empresas);
    }

    public function store(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');

        $this->rules['nombre'] = 'required|unique:empresas,nombre,0,id|max:255';
        $this->rules['razon'] = 'required|unique:empresas,razon,0,id|max:255';
        $this->rules['rfc'] = 'max:15';
        
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all() 
            ));
        }

        $empresas = new Empresas();
        $empresas->nombre = $request->nombre;
        $empresas->razon = $request->razon;
        $empresas->rfc = $request->rfc;
        $empresas->direccion = $request->direccion;
        $empresas->representante = $request->representante;
        Utilidades::auditar($empresas, $empresas->id);
        $empresas->save();

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

        $empresas = Empresas::findOrFail($request->id);
        $empresas->nombre = $request->nombre;
        $empresas->razon = $request->razon;
        $empresas->rfc = $request->rfc;
        $empresas->direccion = $request->direccion;
        $empresas->representante = $request->representante;
        Utilidades::auditar($empresas, $empresas->id.','.$empresas->nombre);
        $empresas->save();

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
