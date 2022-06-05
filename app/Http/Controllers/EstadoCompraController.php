<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\EstadoCompra;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class EstadoCompraController extends Controller
{
  protected $rules = array(
     'nombre' => 'required|max:255'
 );

    public function index()
    {
        $edoCompra = EstadoCompra::select('estado_compras.*')
        ->get();
        return response()->json(
            $edoCompra->toArray()
        );

    }

    public function store(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');

        $edoCompra = new EstadoCompra();
        $edoCompra->fill($request->all());
        Utilidades::auditar($edoCompra, $edoCompra->id);
        $edoCompra->save();
        return response()->json(array(
            'status' => true
        ));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }


    public function update(Request $request, $id)
    {
        try{
        if (!$request->ajax()) return redirect('/');


        $edoCompra = EstadoCompra::findOrFail($request->id);
        $edoCompra->fill($request->all());
        Utilidades::auditar($edoCompra, $edoCompra->id);
        $edoCompra->save();

        return response()->json(array(
            'status' => true
        ));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }



    public function edit($id)
    {

    }

    public function show($id)
    {

    }

    public function activar(Request $request)
    {

    }



}
