<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use App\Articulo;
use App\Requisicionhasordencompras;
use Illuminate\Validation\Rule;
use Carbon\Carbon;



class PrecioVentaController extends Controller
{

    public function index()
    {


    }

    /**
     * [Calcula 
     * suma las  precios de todos los lotes
     * suma todos los adicionales
     * suma todas las cantidades
     * suma precio_lotes + adicionales / cantidades * 1.3]
     * @param  Int      $id [id del articulo]
     * @return Response     [Float o 0 del total]
     */
    public function show($id)
    {
      $total_precios_lotes = 0;
      $total_cantidad_lote = 0;
        $total_adicionales = 0;
      $arreglo = [];
      $var  = 0;
      $lotes_almacen = DB::table('lote_almacen')
      ->leftJoin('lotes AS L','L.id','=','lote_almacen.lote_id')
      ->leftJoin('partidas_entradas AS PE','PE.id','=','L.entrada_id')
      ->leftJoin('requisicion_has_ordencompras AS RHC','RHC.id','=','PE.req_com_id')
      ->select('RHC.*','lote_almacen.cantidad AS lote_cantidad')
      ->where('lote_almacen.articulo_id','=',$id)
      ->get();
      foreach ($lotes_almacen as $key => $value) {
        $adicionales = \App\Adicionales::where('req_has_comp','=',$value->id)->first();
        $arreglo [] = [
          'rhc' => $value,
          'a' => $adicionales,
        ];
      }

      for ($i=0; $i < count($arreglo); $i++) {
        $total_precios_lotes += $arreglo[$i]['rhc'] == null ? 0 : floatval($arreglo[$i]['rhc']->precio_unitario);
        $total_cantidad_lote += $arreglo[$i]['rhc'] == null ? 0 : floatval($arreglo[$i]['rhc']->cantidad);
        $total_adicionales += $arreglo[$i]['a'] == null ? 0 : $arreglo[$i]['a']->cantidad;
      }
      if ($total_cantidad_lote != 0) {
        $var = ($total_precios_lotes + $total_adicionales) / $total_cantidad_lote;
        $var = floatval($var) * 1.3;
      }
      else {
        $var = 0;
      }

      // $h = $total_precios_lotes .'-'. $total_adicionales.'-'. $total_cantidad_lote ;

      return response()->json($var);
    }

    /**
     * [Calcula el precio unitario y el precio de venta de cada lote ingresado]
     * @param  Array $data            [Datos de los articulos]
     * @return Array $arreglo_final[] [Array anidado de articulos,precio unitario y precio venta]
     */
    public function calcular($data)
    {
      $total_precios_lotes = 0;
      $total_cantidad_lote = 0;
      $total_adicionales = 0;
      $arreglo = [];
      $arreglo_final = [];
      $var  = 0;
      // $articulos = \App\Articulo::all();
      foreach ($data as $key => $articulo) {

        $lotes_almacen = DB::table('lote_almacen')
        ->leftJoin('lotes AS L','L.id','=','lote_almacen.lote_id')
        ->leftJoin('partidas_entradas AS PE','PE.id','=','L.entrada_id')
        ->leftJoin('requisicion_has_ordencompras AS RHC','RHC.id','=','PE.req_com_id')
        ->select('RHC.*','lote_almacen.cantidad AS lote_cantidad')
        ->where('lote_almacen.articulo_id','=',$articulo->id)
        ->get();
        foreach ($lotes_almacen as $key => $value) {
          $adicionales = \App\Adicionales::where('req_has_comp','=',$value->id)->first();
          $arreglo [] = [
            'rhc' => $value,
            'a' => $adicionales,
          ];
        }

        for ($i=0; $i < count($arreglo); $i++) {
          $total_precios_lotes += $arreglo[$i]['rhc'] == null ? 0 : floatval($arreglo[$i]['rhc']->precio_unitario);
          $total_cantidad_lote += $arreglo[$i]['rhc'] == null ? 0 : floatval($arreglo[$i]['rhc']->cantidad);
          $total_adicionales += $arreglo[$i]['a'] == null ? 0 : $arreglo[$i]['a']->cantidad;
        }
        if ($total_cantidad_lote != 0) {
          $var = ($total_precios_lotes + $total_adicionales) / $total_cantidad_lote;
          $var1 = floatval($var) * 1.3;
          $variable=number_format($var, 2);
          $precio_Venta = number_format($var1,2);
        }
        else {
          $variable = 0;
          $precio_Venta = 0;
        }
        $arreglo_final [] =
          array_merge($articulo->toArray(), ['preciounitario' => $variable],['precioventa'=>$precio_Venta])
        ;
      }

      return $arreglo_final;
    }

    /**
     * [Query del lado del servidor de el modelo Articulo]
     * @return Array [Arrat que contiene data y count]
     */
    public function busqueda()
    {
          extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));

          $data = Articulo::select(
              [
              'id', 'codigo', 'nombre', 'descripcion', 'marca', 'unidad'
              ])->where('condicion','=','1');

          // $data = \App\Articulo::leftJoin('lote_almacen','lote_almacen.articulo_id','=','articulos.id')
          // ->leftJoin('requisicion_has_ordencompras AS RHC','RHC.articulo_id','=','articulos.id')
          // ->select('articulos.*');
          // // ->where('lote_almacen.articulo_id','=','A.id');

          if (isset($query) && $query) {
              $data = $byColumn == 1 ?
                  $this->busqueda_filterByColumn($data, $query) :
                  $this->busqueda_filter($data, $query, $fields);
          }

          $count = $data->count();

          $data->limit($limit)
              ->skip($limit * ($page - 1));

          if (isset($orderBy)) {
              $direction = $ascending == 1 ? 'ASC' : 'DESC';
              $data->orderBy($orderBy, $direction);
          }

          $results = $this->calcular($data->get());

          return [
              'data' => $results,
              'count' => $count,
          ];
    }

    /**
     * [Función protegida que realiza un filtro de busqueda con la Paqueteria Carbon donde se 
     * crea un JSON ejecutando un Query de un registro coincidente o una fecha establecida de una columna establecida]
     * @param  Array    $data    [Datos de articulo]
     * @param  Array    $queries [Dato a buscar]
     * @return Function
     */
    protected function busqueda_filterByColumn($data, $queries)
    {
        $queries = json_decode($queries, true);

        return $data->where(function ($q) use ($queries) {
            foreach ($queries as $field => $query) {
                $_field = $field;

                if (is_string($query)) {
                    $q->where($_field, 'LIKE', "%{$query}%");
                } else {
                    $start = Carbon::createFromFormat('Y-m-d', substr($query['start'], 0, 10))->startOfDay();
                    $end = Carbon::createFromFormat('Y-m-d', substr($query['end'], 0, 10))->endOfDay();

                    $q->whereBetween($_field, [$start, $end]);
                }
            }
        });
    }

    /**
     * [Función protegida que realiza un filtro de busqueda con la Paqueteria Carbon donde se 
     * crea un JSON ejecutando un Query de un registro coincidente]
     * @param  Array    $data    [Datos de articulo]
     * @param  Array    $query   [Dato a buscar]
     * @param  Array    $queries [Columna a buscar]
     * @return Function 
     */
    protected function busqueda_filter($data, $query, $fields)
    {
        return $data->where(function ($q) use ($query, $fields) {
            foreach ($fields as $index => $field) {
                $method = $index ? 'orWhere' : 'where';
                $q->{$method}($field, 'LIKE', "%{$query}%");
            }
        });
    }


}
