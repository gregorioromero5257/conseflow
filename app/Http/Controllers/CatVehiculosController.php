<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CatServicios;
use App\CatVehiculos;
use Validator;
use App\Articulo;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;


class CatVehiculosController extends Controller
{

  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    $catServicio = CatVehiculos::select('vehiculos.*')
    ->get();
    return response()->json(
      $catServicio->toArray()
    );
  }

  /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function create()
  {
    //
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    try {

      if (!$request->ajax()) return redirect('/');


      $catServicio = new CatVehiculos();
      $catServicio->fill($request->all());
      Utilidades::auditar($catServicio , $catServicio->id);
      $catServicio->save();
      return response()->json(array(
        'status' => true
      ));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return response()->json(array('status' => 'error'));
    }

  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show($id)
  {
    $arreglo =[];
    $requisiciones = DB::table('requisiciones')
    ->leftJoin('partidas_requisiciones AS pr', 'pr.requisicione_id', '=', 'requisiciones.id')
    ->leftJoin('servicios', 'servicios.id', 'pr.servicio_id')
    ->select('requisiciones.*',
    'pr.peso AS peso',
    'pr.cantidad AS cantidades',
    'pr.equivalente',
    'pr.fecha_requerido AS frequerido',
    'pr.condicion AS condicion',
    'pr.requisicione_id',
    'pr.cantidad_compra',
    'pr.cantidad_almacen',
    'pr.articulo_id',
    'pr.servicio_id',
    'pr.pda',
    'pr.comentario AS comentario_partida',
    'servicios.nombre_servicio as nservicio',
    'servicios.proveedor_marca as nproveedor',
    'servicios.unidad_medida as umservicio'
    )
    ->where('pr.requisicione_id', '=', $id)
    ->where('pr.servicio_id', '!=', null)
    ->orderBy('requisiciones.id', 'ASC')
    ->get();

    foreach ($requisiciones as $key => $value) {

      $documentos = DB::table('partidarequisicion_documentos')
      ->leftJoin('documentos_proveedores AS DP','DP.id','=','partidarequisicion_documentos.documento_id')
      ->select('partidarequisicion_documentos.*','DP.nombre','DP.nombre_corto')->where('partidarequisicion_documentos.partidarequisicione_serv','=',$value->servicio_id)
      ->where('partidarequisicion_documentos.partidarequisicione_req','=',$value->requisicione_id)->get();

      $arreglo [] = [
        'req' => $value,
        'doc' => $documentos,
      ];
    }
    return response()->json($arreglo);
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function edit($id)
  {
    //
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, $id)
  {
    try {

      if (!$request->ajax()) return redirect('/');
      //$this->rules['proveedor_marca'] = 'required|unique:servicios,proveedor_marca,0,id|max:50';


      $catServicio = CatVehiculos::findOrFail($request->id);
      $catServicio->fill($request->all());
      $catServicio->save();

      return response()->json(array(
        'status' => true
      ));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return response()->json(array('status' => 'error'));
    }

  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    //
  }



  }
