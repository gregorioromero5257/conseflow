<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ControlesSistema;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Validator;
use \App\Http\Helpers\Utilidades;

class ControlesSistemaController extends Controller
{
    protected $rules = array(        
        'nombre'=> 'required|max:50',               
    );
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $controles = ControlesSistema::select('controles.*')
            ->orderBy('controles.id', 'ASC')
            ->get();

        return response()->json($controles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');

        if ($request->identificador == 1) {
            $usuario = auth()->id();
            $ruta = $request->ruta;
            
            $data = DB::table('permisos_controles')
                ->select(
                    'permisos_controles.control_id'
                )
                ->where('permisos_controles.usuario_id', '=', $usuario)
                ->where('permisos_controles.ruta', '=', $ruta)
                ->get();

            return response()->json($data);
        }

        if ($request->identificador == 2) {
            $this->rules['nombre'] = 'required|unique:controles,nombre,0,id|max:50';
            $validator = Validator::make($request->all(), $this->rules);

            if ($validator->fails()) {
                return response()->json(array(
                    'status' => false,
                    'errors' => $validator->errors()->all() 
                ));
            }

            $controles = new ControlesSistema();
            $controles->nombre = $request->nombre;
            Utilidades::auditar($controles, $controles->id);
            $controles->save();

            return response()->json(array(
                'status' => true
            ));
        }
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
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
        try{
        if (!$request->ajax()) return redirect('/');

        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $controles = ControlesSistema::findOrFail($request->id);
        $controles->nombre = $request->nombre;
        Utilidades::auditar($controles, $controles->id);

        $controles->save();

        return response()->json(array(
            'status' => true,
        ));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
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
