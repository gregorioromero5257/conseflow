<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Accesorio;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class AccesorioController extends Controller
{
     protected $rules = array(
        'tipo'=> 'required|max:255',
    );

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        $accesorio = Accesorio::select('accesorio.*')
        ->orderBy('accesorio.id', 'ASC')->get();
        return response()->json($accesorio);
    }

    public function store(Request $request)
    {

      try {

        if (!$request->ajax()) return redirect('/');

        $this->rules['tipo'] = 'required|unique:accesorio,tipo,0,id|max:255';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $accesorio = new Accesorio();
        $accesorio->tipo = $request->tipo;
        $accesorio->etiqueta = $request->etiqueta;
        $accesorio->ns = $request->ns;
        Utilidades::auditar($accesorio, $accesorio->id);
        $accesorio->save();

        return response()->json(array(
            'status' => true
        ));

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }


    }

    public function update(Request $request)
    {
      try {
        if (!$request->ajax()) return redirect('/');

        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $accesorios = Accesorio::findOrFail($request->id);
        $accesorios-> tipo = $request->tipo;
        $accesorios-> etiqueta = $request->etiqueta;
        $accesorios-> ns = $request->ns;
        Utilidades::auditar($accesorios , $accesorios->id);
        $accesorios->save();

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
