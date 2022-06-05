<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Telefono;
use App\Empleado;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;


class TelefonosController extends Controller
{
  protected $rules = array(
    'nombre' => 'required|max:10',
    // 'edad' => 'required|max:3',

  );

  public function index(Request $request, $id)
  {
    $telefonos = Telefono::orderBy('id', 'desc')
    ->where('empleado_id', '=', $id)
    ->where('condicion', 1)
    ->get();
    return response()->json(
      $telefonos->toArray()
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
    $telefono = new Telefono();
    $telefono->fill($request->all());
    $telefono->save();
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

    $telefono = Telefono::findOrFail($request->id);
    $telefono->fill($request->all());
    $telefono->save();

    return response()->json(array(
      'status' => true
    ));
  }



  public function edit($id)
  {
    $telefono = Telefono::findOrFail($id);
    if ($telefono->condicion==0) {
      $telefono->condicion = 1;
    }else{
      $telefono->condicion = 0;
    }
    $telefono->update();
    return $telefono;
  }

  public function show($id)
  {
    $telefono = Telefono::orderBy('id', 'desc')

    ->get();
    return response()->json(
      $telefono->toArray()
    );
  }

  public function leerxml(){
    // $xml_object = simplexml_load_file($request->file('action')->getRealPath());'L,'
    $xml_string = Storage::disk('local')->get('timbrado\timbradoARS.xml');
    // $valores_ = explode('>',$valores);
    //$xml = \XmlParser::load($xml_string);
    $xml = simplexml_load_string($xml_string);
    //  $json = json_encode($xml);
    //    $array = json_decode($json,TRUE);

    foreach ($xml->xpath('//cfdi:Comprobante') as $Concepto){
      echo var_dump($Concepto);
    }
    //echo var_dump($xml_string);
    //  return response()->json($xml_string);
  }
}
