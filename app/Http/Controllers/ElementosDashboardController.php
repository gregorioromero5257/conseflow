<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\ElementosDashboard;
use Validator;
use \App\Http\Helpers\Utilidades;

class ElementosDashboardController extends Controller
{
    protected $rules = array(
        'nombre' => 'required|max:50',
    );

    public function index()
    {
        $puestos = DB::table('elementos_dashboards')
            ->join('modulos', 'elementos_dashboards.modulo_id', '=', 'modulos.id')
            ->select('elementos_dashboards.*', 'modulos.nombre AS modulo')
            ->get();

        return response()->json(
            $puestos->toArray()
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

        $elementoDashboard = new ElementosDashboard();
        $elementoDashboard->nombre = $request->nombre;
        $elementoDashboard->modulo_id = $request->modulo_id;
        Utilidades::auditar($elementoDashboard, $elementoDashboard->id);
        $elementoDashboard->save();

        return response()->json(array(
            'status' => true
        ));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
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

        $elementoDashboard = ElementosDashboard::findOrFail($request->id);
        $elementoDashboard->nombre = $request->nombre;
        $elementoDashboard->modulo_id = $request->modulo_id;
        $elementoDashboard->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function eliminar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $elementoDashboard = ElementosDashboard::findOrFail($request->id);
        $elementoDashboard->delete();
    }

}
