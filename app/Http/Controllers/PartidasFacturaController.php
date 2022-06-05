<?php

namespace App\Http\Controllers;

use \App\Http\Helpers\Utilidades;
use Illuminate\Http\Request;
use App\Imports\PartidasFacturasImport;
use Maatwebsite\Excel\Facades\Excel;


class PartidasFacturaController extends Controller
{
  /**
  * [Guarda en BD las partidas en la tabla partidas_factura donde factura_id = $factura_id proporcionado]
  * @param  Request  $request [Objeto de datos del POST]
  * @return Response          [Array en formato JSON]
  */
  public function store(Request $request)
  {
    $partida_factura = new \App\PartidasFactura();
    $partida_factura->fill($request->all());
    $partida_factura->save();

    return response()->json(array('status' => true));
  }

  /**
  * [Actualiza en BD las partidas en la tabla partidas_factura donde id = $id proporcionado]
  * @param  Request  $request [Objeto de datos del PUT]
  * @param  Int      $id      [id del PUT]
  * @return Response          [Array en formato JSON]
  */
  public function update(Request $request,$id)
  {
    $partida_factura = \App\PartidasFactura::where('id',$id)->first();
    $partida_factura->fill($request->all());
    $partida_factura->save();
    return response()->json(array('status' => true));
  }

  /**
  * [Consulta en BD de la tabla partidas_factura donde id = $id proporcionado]
  * @param  Int      $id [id del GET]
  * @return Response     [Array en formato JSON]
  */
  public function show($id)
  {
    $partida_factura = \App\PartidasFactura::
    leftJoin('sat_cat_prodser AS SCPS','SCPS.id','=','partidas_factura.productos_servicios_id')
    ->join('sat_cat_unidades AS SCU','SCU.id','=','partidas_factura.unidad_id')
    ->join('factura AS F','F.id','=','partidas_factura.factura_id')
    ->select('partidas_factura.*','SCPS.clave AS scps_clave','SCPS.descripcion AS scps_descripcion',
    'SCU.c_unidad AS scu_unidad','SCU.nombre AS scu_nombre')
    ->where('partidas_factura.factura_id',$id);

    return $this->busqueda($partida_factura);
  }

  public function busqueda($dato)
  {
    extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));

    $data = $dato;

    if (isset($query) && $query) {
      $data = $byColumn == 1 ?
      Utilidades::busqueda_filterByColumn($data, $query) :
      Utilidades::busqueda_filter($data, $query, $fields);
    }

    $count = $data->count();

    $data->limit($limit)
    ->skip($limit * ($page - 1));

    if (isset($orderBy)) {
      $direction = $ascending == 1 ? 'ASC' : 'DESC';
      $data->orderBy($orderBy, $direction);
    }

    $results = $data->get();

    return [
      'data' => $results,
      'count' => $count,
    ];
  }

  /**
  * [Elimina en BD de la tabla partidas_factura donde id = $id proporcionado]
  * @param  Int      $id [id del GET]
  * @return Response     [Array en formato JSON]
  */
  public function destroy($id)
  {
    $partidas_facturas_eliminar = \App\PartidasFactura::where('id','=',$id)->delete();
    return response()->json(array('status' => true));
  }

  /**
  *
  **/

  public function subirExcel(Request $request)
  {
    try {
      if (!$request->ajax()) return redirect('/');
      if($request->hasFile('import_file')){
        ini_set('max_execution_time', 300);
        $collection = Excel::import(new PartidasFacturasImport($request->id), request()->file('import_file'));
        return response()->json($collection);
      }
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }
}
