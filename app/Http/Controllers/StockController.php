<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use Validator;
use Illuminate\Validation\Rule;

class StockController extends Controller
{
    protected $rules = array(
        'nombre' => 'required|max:125',
    );

    public function index(Request $request)
    {
        $stocks = Stock::select('stocks.*', 'proyectos.nombre_corto AS proyecto')->leftjoin('proyectos', 'stocks.proyecto_id', '=', 'proyectos.id')->orderBy('stocks.id', 'desc')->get()->toArray();
        return response()->json($stocks);
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $this->rules['nombre'] = 'required|unique:stocks,nombre,0,id|max:125';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $stock = new Stock();
        $stock->nombre = $request->nombre;
        $stock->condicion = '1';
        $stock->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $this->rules['nombre'] = 'required|unique:stocks,nombre,'.$request->id.',id|max:125';
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $stock = Stock::findOrFail($request->id);
        $stock->nombre = $request->nombre;
        $stock->condicion = '1';
        $stock->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $stock = Stock::findOrFail($request->id);
        $stock->condicion = '0';
        $stock->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $stock = Stock::findOrFail($request->id);
        $stock->condicion = '1';
        $stock->save();
    }

    public function getList(Request $request)
    {
        $stocks = Stock::select('id', 'nombre')->orderBy('id', 'desc')->get()->toArray();
        return response()->json($stocks);
    }

}
