<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Folio;
use Validator;
use \App\Http\Helpers\Utilidades;

class FolioController extends Controller
{
    protected $rules = array(
        'tipo_doc' => 'required|max:20',
    );

    public function index()
    {
        $puestos = DB::table('folios')
            ->join('proyectos', 'folios.proyecto_id', '=', 'proyectos.id')
            ->select('folios.*', 'proyectos.nombre_corto')
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

        $folio = new Folio();
        $folio->tipo_doc = $request->tipo_doc;
        $folio->proyecto_id = $request->proyecto_id;
        $folio->consecutivo = $request->consecutivo;
        Utilidades::auditar($folio, $folio->id);
        $folio->save();

        return response()->json(array(
            'status' => true
        ));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function update(Request $request)
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

        $folio = Folio::findOrFail($request->id);
        $folio->tipo_doc = $request->tipo_doc;
        $folio->proyecto_id = $request->proyecto_id;
        $folio->consecutivo = $request->consecutivo;
        Utilidades::auditar($folio, $folio->id);
        $folio->save();

        return response()->json(array(
            'status' => true
        ));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function eliminar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $folio = Folio::findOrFail($request->id);
        $folio->delete();
    }

}
