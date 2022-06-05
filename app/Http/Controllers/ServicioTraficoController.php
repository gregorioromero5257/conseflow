<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ServicioTrafico;
use App\Unidades;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ServicioTraficoController extends Controller
{
    protected $rules = array(
        'proveedor' => 'required|max:75',
        'fecha' => 'required'
    );

    public function index($id)
    {
        $servicio = DB::table('servicios_trafico')
        ->join('choferes','servicios_trafico.chofer_id','=','choferes.id')
        ->join('empleados','choferes.empleado_id','=','empleados.id')
        ->select('servicios_trafico.*',DB::RAW("CONCAT(empleados.nombre,' ', empleados.ap_paterno,' ',empleados.ap_materno)  AS chofer"))
        ->where('unidad_id', $id)->orderBy('servicios_trafico.id', 'desc')->get()->toArray();
        return response()->json($servicio);
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

        $servicio = new ServicioTrafico();
        $servicio->proveedor = $request->proveedor;
        $servicio->fecha = $request->fecha;
        $servicio->chofer_id = $request->chofer_id;
        $servicio->unidad_id = $request->unidad_id;
        $servicio->total = 0;
        $servicio->save();

        $unidad = Unidades::find($servicio->unidad_id);
        $unidad->ultimo_servicio = $request->fecha;
        $unidad->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $servicio = ServicioTrafico::findOrFail($request->id);
        $servicio->proveedor = $request->proveedor;
        $servicio->fecha = $request->fecha;
        $servicio->chofer_id = $request->chofer_id;
        $servicio->unidad_id = $request->unidad_id;
        $servicio->save();

        return response()->json(array(
            'status' => true
        ));
    }

}
