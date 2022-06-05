<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Referencia;
use App\Empleado;
use Validator;
use Illuminate\Validation\Rule;

class ReferenciaController extends Controller
{
    protected $rules = array(
        'nombre' => 'required|max:45',
        'telefono' => 'required|max:10',
        'ocupacion' => 'required|max:45',
        'direccion' => 'required|max:255',
    );

    public function index(Request $request, $id)
    {
        $referencias = DB::table('referencias_conocidos')
            ->join('empleados', 'empleados.id', '=', 'referencias_conocidos.empleado_id')
           ->select("referencias_conocidos.*" 
           ,DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) as empleado"))
           ->where('referencias_conocidos.empleado_id', '=', $id)
           ->where('referencias_conocidos.condicion',1)
            ->get();

        return response()->json(
            $referencias->toArray()
        );
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $referencia = new Referencia();
        $referencia->fill($request->all());
        $referencia->save();

        return response()->json(array(
            'status' => true
        ));
    }
  
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $referencia = Referencia::findOrFail($request->id);
        $referencia->fill($request->all());
        $referencia->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function edit($id)
    {
        $referencia = Referencia::findOrFail($id);
        if ($referencia->condicion==0) {
            $referencia->condicion = 1;
        }else{
            $referencia->condicion = 0;
        }
        $referencia->update();
        return $referencia;
    }

    public function show($id)
    {
        $referencias = DB::table('referencias_conocidos')
        ->join('empleados', 'empleados.id', '=', 'referencias_conocidos.empleado_id')
       ->select("referencias_conocidos.*"
    ,DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) as empleado"))
        ->get();
    return response()->json(
        $referencias->toArray()
    );
    }



    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $referencia = Referencia::findOrFail($request->id);
        $referencia->condicion = '1';
        $referencia->save();
    }

    public function getList(Request $request)
    {
        $empleados = Referencia::select('id', 'nombre')->where('empleado_id', $id)->orderBy('id', 'desc')->get()->toArray();
        return response()->json($empleados);
    }

}
