<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PartidaServicioTrafico;
use App\ServicioTrafico;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PartidaServicioTraficoController extends Controller
{
    protected $rules = array(
        'tipo_servicio_id' => 'required',
        'descripcion' => 'required|max:50',
        'precio' => 'required'
    );

    public function index($id)
    {
        $servicio = DB::table('partidas_servicios_trafico')
                ->select('partidas_servicios_trafico.*', 'tipo_servicio_trafico.nombre AS tipo')
                ->join('tipo_servicio_trafico','partidas_servicios_trafico.tipo_servicio_id','=','tipo_servicio_trafico.id')
                ->where('servicio_trafico_id', $id)
                ->orderBy('partidas_servicios_trafico.id', 'asc')->get()->toArray();
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

        $partidaServicio = new PartidaServicioTrafico();
        $partidaServicio->tipo_servicio_id = $request->tipo_servicio_id;
        $partidaServicio->descripcion = $request->descripcion;
        $partidaServicio->precio = $request->precio;
        $partidaServicio->servicio_trafico_id = $request->servicio_trafico_id;
        $partidaServicio->save();

        $total = $this->actualizarTotal($request->servicio_trafico_id);

        return response()->json(array(
            'status' => true,
            'total' => $total
        ));
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $partidaServicio = PartidaServicioTrafico::findOrFail($request->id);
        $partidaServicio->tipo_servicio_id = $request->tipo_servicio_id;
        $partidaServicio->descripcion = $request->descripcion;
        $partidaServicio->precio = $request->precio;
        $partidaServicio->servicio_trafico_id = $request->servicio_trafico_id;
        $partidaServicio->save();

        $total = $this->actualizarTotal($request->servicio_trafico_id);

        return response()->json(array(
            'status' => true,
            'total' => $total
        ));
    }

    public function delete(Request $request) 
    {
        if (!$request->ajax()) return redirect('/');

        PartidaServicioTrafico::destroy($request->id);

        $total = $this->actualizarTotal($request->servicio_trafico_id);

        return response()->json(array(
            'status' => true,
            'total' => $total
        ));
    }

    private function actualizarTotal($id)
    {
        $result = DB::table('partidas_servicios_trafico')->select(DB::raw('SUM(precio) AS total'))->where('servicio_trafico_id', $id)->get();

        $servicio = ServicioTrafico::find($id);
        $servicio->total = $result[0]->total;
        $servicio->save();

        return $result[0]->total;
    }

}
