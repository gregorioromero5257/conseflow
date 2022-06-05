<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use Illuminate\Http\Request;
use App\Sueldo;
use App\SueldoDBP;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Rap2hpoutre\FastExcel\FastExcel;

class SueldoController extends Controller
{
     protected $rules = array(
    'sueldo_diario_integral' => 'required|between:0,99.9',
    'sueldo_mensual' => 'required|between:0,99.9',
    'infonavit' => 'required|between:0,99.9',
    'viaticos_mensuales' => 'required|between:0,99.9',
    'sueldo_diario_neto' => 'required|between:0,99.9',
    'sueldo_diario_real' => 'required|between:0,99.9',
);


    public function index(Request $request, $id)
    {
        $SueldosCont = Sueldo::orderBy('id', 'desc')->get()->toArray();
        return response()->json($SueldosCont);


    }
    public function store(Request $request)
    {
      try {

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }

        if (!$request->ajax()) return redirect('/');
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }
        if ($request->empresa == 1) {
          $SueldosCont = new Sueldo();
        }elseif ($request->empresa == 2) {
          $SueldosCont = new SueldoDBP();
        }
        $SueldosCont->fill($request->all());
        Utilidades::auditar($SueldosCont, $SueldosCont->id);
        $SueldosCont->save();
        return response()->json(array(
            'status' => true
        ));

    }
    public function update(Request $request, $id)
    {
      try {

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
        if (!$request->ajax()) return redirect('/');
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }
        if ($request->empresa == 1) {
          $SueldosCont = Sueldo::findOrFail($request->id);
        }elseif ($request->empresa == 2) {
          $SueldosCont = SueldoDBP::findOrFail($request->id);
        }
        $SueldosCont->fill($request->all());
        Utilidades::auditar($SueldosCont, $SueldosCont->id);
        $SueldosCont->save();

        return response()->json(array(
            'status' => true,
        ));
    }


    public function get(Request $request, $id)
    {
        $valores = explode('&',$id);
        if ($valores[1] == 1) {
          $SueldosCont1 = Sueldo::orderBy('id', 'desc')
          ->where('contrato_id', '=', $valores[0])
          ->get()->toArray();
        }elseif ($valores[1] == 2) {
          $SueldosCont1 = SueldoDBP::orderBy('id', 'desc')
          ->where('contrato_id', '=', $valores[0])
          ->get()->toArray();
        }

        return response()->json($SueldosCont1);

}

}
