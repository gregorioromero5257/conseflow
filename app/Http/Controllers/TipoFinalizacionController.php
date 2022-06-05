<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoFinalizacion;
use Validator;
use Illuminate\Validation\Rule;

class TipoFinalizacionController extends Controller
{
    /*Valida que el campo indicado no contenga nulos o vacíos*/
    protected $rules = array(
        'nombre' => 'required|max:45',
    );
    /*********************************************************/
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*Obtiene todos los registros del tipo de horario*/
        $tiposFinalizacion = TipoFinalizacion::orderBy('id', 'ASC')->get()->toArray();
        return response()->json($tiposFinalizacion);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*Obtiene todos los registros donde el ID sea mayor a 1*/
        $tiposFinalizacion = TipoFinalizacion::orderBy('id', 'ASC')
        // ->where('id', '>', 1)
        ->get();
        return response()->json($tiposFinalizacion);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*Inserta un nuevo registro en la BD*/
        //Valida si la peticion del request es por ajax
        if (!$request->ajax()) return redirect('/');
        //
        //Valida que el campo no sea nulo o vacío
        $this->rules['nombre'] = 'required|unique:tipo_fin_contrato,nombre,0,id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $tipoFinalizacion = new TipoFinalizacion();
        $tipoFinalizacion->nombre = $request->nombre;
        $tipoFinalizacion->save();

        return response()->json(array(
            'status' => true
        ));
        /***********************************************/
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /*Actualiza el registro en la BD*/
        if (!$request->ajax()) return redirect('/');

        $this->rules['nombre'] = 'required|unique:tipo_fin_contrato,nombre,'.$request->id.',id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $tipoFinalizacion = TipoFinalizacion::findOrFail($request->id);
        $tipoFinalizacion->nombre = $request->nombre;
        $tipoFinalizacion->save();

        return response()->json(array(
            'status' => true
        ));
        /**********************************/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
