<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Familiare;
use App\Empleado;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;
use Exception;

class FamiliareController extends Controller
{
    protected $rules = array(
        'nombre' => 'required|max:50',
        'edad' => 'required|max:3',

    );

    public function index(Request $request, $id)
    {
        $familiare = Familiare::orderBy('id', 'desc')
        ->where('empleado_id', '=', $id)
        ->where('condicion',1)
        ->get()->toArray();
        return response()->json($familiare);
    }

    public function store(Request $request)
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
        $familiare = new Familiare();
        $familiare->fill($request->all());
        Utilidades::auditar($familiare, $familiare->id);
        $familiare->save();
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

        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $familiare = Familiare::findOrFail($request->id);
        $familiare->fill($request->all());
        Utilidades::auditar($familiare, $familiare->id);
        $familiare->save();

        return response()->json(array(
            'status' => true
        ));
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }



    public function edit($id)
    {try{
        $familiare = Familiare::findOrFail($id);
        if ($familiare->condicion==0) {
            $familiare->condicion = 1;
        }else{
            $familiare->condicion = 0;
        }
        Utilidades::auditar($familiare, $familiare->id);
        $familiare->update();
        return $familiare;
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function show($id)
    {
        return response()->json(Familiare::findOrFail($id));
    }



    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $familiare = Familiare::findOrFail($request->id);
        $familiare->condicion = '1';
        $familiare->save();
    }

    public function getList(Request $request)
    {
        $empleados = Familiare::select('id', 'nombre')->where('empleado_id', $id)->orderBy('id', 'desc')->get()->toArray();
        return response()->json($empleados);
    }

}
