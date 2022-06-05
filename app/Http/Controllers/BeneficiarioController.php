<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use App\Beneficiario;
use App\Empleado;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class BeneficiarioController extends Controller
{
    protected $rules = array(
        'nombre' => 'required|max:45',
        'telefono' => 'required|max:10',
        'tel_celular' => 'required|max:10',
        'parentesco' => 'max:25',
        'porcentaje' => 'max:3',
    );

    public function index(Request $request, $id)
    {
        $beneficiarios = DB::table('beneficiarios')
        ->join('empleados', 'empleados.id', '=', 'beneficiarios.empleado_id')
        ->select("beneficiarios.*"
        ,DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) as empleado"))
        ->where('beneficiarios.empleado_id', '=' , $id)
        ->where('beneficiarios.condicion', 1)

        ->get();
    return response()->json(
        $beneficiarios->toArray()
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

        $beneficiario = new Beneficiario();
        $beneficiario->fill($request->all());
        Utilidades::auditar($beneficiario, $beneficiario->id);
        $beneficiario->save();

        return response()->json(array(
            'status' => true
        ));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
      return response()->json(["status"]);
    }
    }

    public function update(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');

        // $this->rules['nombre'] = 'required|unique:beneficiarios,'.$request->id.',id|max:45';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $beneficiario = Beneficiario::findOrFail($request->id);
        $beneficiario->fill($request->all());
        Utilidades::auditar($beneficiario, $beneficiario->id);
        $beneficiario->save();

        return response()->json(array(
            'status' => true
        ));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }
   
    public function edit($id)
    {
        try{
        $beneficiario = Beneficiario::findOrFail($id);
        if ($beneficiario->condicion==0) {
            $beneficiario->condicion = 1;
        }else{
            $beneficiario->condicion = 0;
        }
        Utilidades::auditar($beneficiario, $beneficiario->id);
        $beneficiario->update();
        return $beneficiario;
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function show($id)
    {
        $beneficiarios = DB::table('beneficiarios')
        ->join('empleados', 'empleados.id', '=', 'beneficiarios.empleado_id')
       ->select("beneficiarios.*"
    ,DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) as empleado"))
        ->get();
    return response()->json(
        $beneficiarios->toArray()
    );
    }



    public function activar(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');
        $beneficiario = Beneficiario::findOrFail($request->id);
        $beneficiario->condicion = '1';
        Utilidades::auditar($beneficiario, $beneficiario->id);
        $beneficiario->save();
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function getList(Request $request)
    {
        $empleados = Beneficiario::select('id', 'nombre')->where('empleado_id', $id)->orderBy('id', 'desc')->get()->toArray();
        return response()->json($empleados);
    }
}