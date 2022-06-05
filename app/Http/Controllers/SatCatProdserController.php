<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SatCatProdSer;
use Validator;
use Illuminate\Validation\Rule;

class SatCatProdserController extends Controller
{
    /**
     * [Definición de las reglas para ser utilizadas en la actualización y la inserción]
     */
    protected $rules = array(
        'clave' => 'required|max:8',
        'descripcion' => 'required|max:250',
    );

    /**
     * [Consulta en BD los registros de la tabla sat_cat_prodser]
     * @param  Request  $request [Url del GET]
     * @return Response          [Array de tipo JSON]
     */
    public function index(Request $request)
    {
        $productoServicio = SatCatProdSer::orderBy('id', 'desc')->get()->toArray();
        return response()->json($productoServicio);
    }

    /**
     * [Guarda en BD los registros en la tabla sat_cat_prodser respetando las reglas establecidas]
     * @param  Request  $request [Objeto de datos del POST]
     * @return Response          [Array con estatus true]
     */
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

        $productoServicio = new SatCatProdSer();
        $productoServicio->clave = $request->clave;
        $productoServicio->descripcion = $request->descripcion;
        $productoServicio->fecha_ini_vig = $request->fecha_ini_vig;
        $productoServicio->fecha_fin_vig = $request->fecha_fin_vig;
        $productoServicio->incluir_iva_tras = $request->incluir_iva_tras;
        $productoServicio->incluir_ieps_tras = $request->incluir_ieps_tras;
        $productoServicio->complemento = $request->complemento;
        $productoServicio->save();

        return response()->json(array(
            'status' => true
        ));
    }

    /**
     * [Actualiza en BD los registros de la tabla sat_cat_prodser respetando las reglas establecidas]
     * @param  Request  $request [Objeto de datos del PUT]
     * @return Respomse          [Array con estatus true]
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $productoServicio = SatCatProdSer::findOrFail($request->id);
        $productoServicio->clave = $request->clave;
        $productoServicio->descripcion = $request->descripcion;
        $productoServicio->fecha_ini_vig = $request->fecha_ini_vig;
        $productoServicio->fecha_fin_vig = $request->fecha_fin_vig;
        $productoServicio->incluir_iva_tras = $request->incluir_iva_tras;
        $productoServicio->incluir_ieps_tras = $request->incluir_ieps_tras;
        $productoServicio->complemento = $request->complemento;
        $productoServicio->save();

        return response()->json(array(
            'status' => true
        ));
    }

}
