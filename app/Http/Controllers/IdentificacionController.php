<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Identificacion;
use App\Empleado;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;


class IdentificacionController extends Controller
{
    protected $rules = array( 
        'curp' => 'required|max:18',
        'rfc' => 'required|max:13',
        'cartilla' => 'required|max:10',
        'nss_imms' => 'required|max:11',

    );

    public function index(Request $request, $id)
    {
        $identificaciones = DB::table('identificaciones')
        ->join('empleados AS Empleado', 'Empleado.id', '=', 'identificaciones.empleado_id')
        ->select('identificaciones.*',
        DB::raw("CONCAT(Empleado.nombre,' ',Empleado.ap_paterno,' ',Empleado.ap_materno) AS empleado"))
        ->where('identificaciones.empleado_id', '=', $id)
        ->get();

        return response()->json(
            $identificaciones->toArray()
        );


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
        $identificaciones = new Identificacion();
        $identificaciones->fill($request->all());
        Utilidades::auditar($identificaciones, $identificaciones->id);
        $identificaciones->save();
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

        $identificaciones = Identificacion::findOrFail($request->id);
        $identificaciones->fill($request->all());
        Utilidades::auditar($identificaciones, $identificaciones->id);
        $identificaciones->save();

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
        return response()->json(Identificacion::findOrFail($id));
    }



    public function activar(Request $request)
    {

    }

    public function getList(Request $request)
    {
        // $empleados = Identificacion::select('id', 'nombre')->where('empleado_id', $id)->orderBy('id', 'desc')->get()->toArray();
        // return response()->json($empleados);
    }

}
