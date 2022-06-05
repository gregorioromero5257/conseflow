<?php

namespace App\Http\Controllers;

use App\Almacene;
use Illuminate\Http\Request;
use App\Lote;
use App\LoteAlmacen;
use Carbon\Carbon;
use Validator;
use \App\Http\Helpers\Utilidades;
use App\Nivel;
use App\Nivele;
use App\Stand;
use Exception;
use Illuminate\Support\Facades\DB;

class LoteController extends Controller
{
  protected $rules = array(
    'nombre' => 'required|max:200',
    'caducidad' => 'required',
    'cantidad' => 'integer|min:0',
    'articulo_id' => 'required'
  );

  public function getByArticulo(Request $request)
  {
    $lotes = Lote::orderBy('lotes.id', 'desc')
    ->select('lotes.*', 'entradas.fecha AS entrada', 'partidas_entradas.cantidad AS cantidad_entrada')
    ->leftJoin('entradas', 'lotes.entrada_id', '=', 'entradas.id')
    ->leftJoin('partidas_entradas', 'entradas.id', '=', 'partidas_entradas.entrada_id')
    ->whereRaw('lotes.articulo_id = ' . $request->articulo_id . ' AND (partidas_entradas.articulo_id IS NULL OR partidas_entradas.articulo_id = ' . $request->articulo_id . ')')
    ->get()->toArray();
    return response()->json($lotes);
  }

  public function index(Request $request)
  {
    return $this->get();
  }

  public function desactivar(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $lote = \App\LoteAlmacen::findOrFail($request->id);
    $lote->condicion = '0';
    Utilidades::auditar($lote, $lote->articulo_id . ',' . $lote->id);
    $lote->save();
  }

  public function activar(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $lote = \App\LoteAlmacen::findOrFail($request->id);
    $lote->condicion = '1';
    Utilidades::auditar($lote, $lote->articulo_id . ',' . $lote->id);
    $lote->save();
  }

  public function get()
  {
    extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));

    $data = LoteAlmacen::select(
      [
        "a.id", "l.articulo_id", "a.descripcion", "a.marca", "lote_almacen.lote_id", "l.nombre",
        "lote_almacen.condicion", "l.entrada_id",
        "lote_almacen.cantidad as total", "l.cantidad as total_entrada", "oc.folio"
      ]
      )
      ->leftJoin('articulos AS a', 'lote_almacen.articulo_id', '=', 'a.id')
      ->leftJoin('lotes AS l', 'lote_almacen.lote_id', '=', 'l.id')
      ->leftJoin('partidas_entradas AS pe', 'pe.id', '=', 'l.entrada_id')
      ->leftJoin('entradas as e', 'l.entrada_id', '=', 'e.id')
      ->leftJoin('requisicion_has_ordencompras as rho', 'rho.id', 'pe.req_com_id')
      ->leftJoin('ordenes_compras as oc', 'rho.orden_compra_id', '=', 'oc.id')
      ->leftJoin('partidas AS pq','pq.lote_id','lote_almacen.id')
      ;

      if (isset($query) && $query)
      {
        $data = $byColumn == 1 ?
        $this->filterByColumn($data, $query) :
        $this->filter($data, $query, $fields);
      }

      // $count = $data->get()->n;
      $count = $data->count();

      $data->limit($limit)
      ->skip($limit * ($page - 1));

      if (isset($orderBy))
      {
        $direction = $ascending == 1 ? 'ASC' : 'DESC';
        $data->orderBy($orderBy, $direction);
      }

      $results = $data->get()->toArray();

      return [
        'data' => $results,
        'count' => $count,
      ];
    }

    protected function filterByColumn($data, $queries)
    {
      $queries = json_decode($queries, true);

      return $data->where(function ($q) use ($queries)
      {
        foreach ($queries as $field => $query)
        {
          $_field = $field;
          if ($field == 'condicion')
          $_field = 'b.condicion';
          else if ($field == 'nombre')
          $_field = 'b.nombre';

          if (is_string($query))
          {
            $q->where($_field, 'LIKE', "%{$query}%");
          }
          else
          {
            $start = Carbon::createFromFormat('Y-m-d', substr($query['start'], 0, 10))->startOfDay();
            $end = Carbon::createFromFormat('Y-m-d', substr($query['end'], 0, 10))->endOfDay();

            $q->whereBetween($_field, [$start, $end]);
          }
        }
      });
    }

    protected function filter($data, $query, $fields)
    {
      return $data->where(function ($q) use ($query, $fields)
      {
        foreach ($fields as $index => $field)
        {
          $method = $index ? 'orWhere' : 'where';
          $q->{$method}($field, 'LIKE', "%{$query}%");
        }
      });
    }

    public function store(Request $request)
    {
      try
      {
        if (!$request->ajax()) return redirect('/');

        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails())
        {
          return response()->json(array(
            'status' => false,
            'errors' => $validator->errors()->all()
          ));
        }

        $lote = new Lote();
        $lote->articulo_id = $request->articulo_id;
        $lote->caducidad = $request->caducidad;
        $lote->nombre = $request->nombre;
        $lote->cantidad = $request->cantidad;
        $lote->condicion = '1';
        //  $lote->entrada_id = '0';
        Utilidades::auditar($lote, $lote->id);
        $lote->save();

        return response()->json(array(
          'status' => true
        ));
      }
      catch (\Throwable $e)
      {
        Utilidades::errors($e);
      }
    }

    public function update(Request $request)
    {
      try
      {
        if (!$request->ajax()) return redirect('/');

        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails())
        {
          return response()->json(array(
            'status' => false,
            'errors' => $validator->errors()->all()
          ));
        }

        if (!$request->ajax()) return redirect('/');
        $lote = Lote::findOrFail($request->id);
        $lote->articulo_id = $request->articulo_id;
        $lote->caducidad = $request->caducidad;
        $lote->nombre = $request->nombre;
        $lote->cantidad = $request->cantidad;
        Utilidades::auditar($lote, $lote->articulo_id . ',' . $lote->id);
        $lote->save();

        return response()->json(array(
          'status' => true
        ));
      }
      catch (\Throwable $e)
      {
        Utilidades::errors($e);
      }
    }

    /**
    * Obtiene los lotes/artículos de almacén
    */
    public function Obtener()
    {
      try
      {
        $data = LoteAlmacen::select(
          [
            "a.id",
            "l.articulo_id",
            "a.descripcion",
            "a.marca",
            "lote_almacen.lote_id",
            "l.nombre",
            "lote_almacen.condicion",
            "l.entrada_id",
            "lote_almacen.cantidad as total",
            "l.cantidad as total_entrada",
            "oc.folio"
          ]
          )->leftJoin('articulos AS a', 'lote_almacen.articulo_id', '=', 'a.id')
          ->leftJoin('lotes AS l', 'lote_almacen.lote_id', '=', 'l.id')
          ->leftJoin('partidas_entradas AS pe', 'pe.id', '=', 'l.entrada_id')
          ->leftJoin('entradas as e', 'l.entrada_id', '=', 'e.id')
          ->leftJoin('requisicion_has_ordencompras as rho', 'rho.id', 'pe.req_com_id')
          ->leftJoin('ordenes_compras as oc', 'rho.orden_compra_id', '=', 'oc.id')
          ->get();
          return response()->json(["status" => true, "articulos" => $data]);
        }
        catch (Exception $e)
        {
          Utilidades::errors($e);
          return response()->json(["status" => false, "mensage" => "Error al obtener los artículos"]);
        }
      }

      /**
      * Realiza la búsqueda de los lotes con el artículo ingresado
      *
      */
      public function Buscar($buscar)
      {
        try
        {
          $data = DB::table('lote_almacen')
          ->Join('lotes AS L','L.id','=','lote_almacen.lote_id')
          ->Join('stocks AS S','S.id','=','lote_almacen.stocke_id')
          ->Join('proyectos AS P','P.id','=','S.proyecto_id')
          ->Join('articulos AS A','A.id','=','lote_almacen.articulo_id')
          ->select('lote_almacen.*','lote_almacen.cantidad AS total','L.nombre AS lote_nombre','L.cantidad AS total_entrada','A.nombre AS anombre','A.descripcion AS descripcion','A.codigo AS acodigo',
          'A.marca AS amarca','A.unidad AS aunidad','P.nombre_corto AS folio','P.id AS proyectoi')
          // ->where('P.id','=',$id)
          ->where("A.descripcion","like","%".$buscar."%")
          //->where('lote_almacen.cantidad','>','0')
          ->get();

          $arreglo = [];
          foreach ($data as $key => $value) {
            $partidas = DB::table('partidas AS p')->where('p.lote_id',$value->id)->select(DB::raw("SUM(cantidad) AS cs"))->first();
            $arreglo [] = [
              'data' => $value,
              'salida' => $partidas,
            ];
          }

          return response()->json(["status" => true, "articulos" => $arreglo]);

          // return response()->json(["status" => true, "articulos" => $data]);
        }
        catch (Exception $e)
        {
          Utilidades::errors($e);
          return response()->json(["status" => false, "mensage" => "Error al obtener los artículos"]);
        }
      }

      /**
      * Realiza la búsqueda de los lotes con el artículo ingresado
      *
      */
      public function BuscarProyecto($id)
      {
        try
        {
          $data = DB::table('lote_almacen')
          ->Join('lotes AS L','L.id','=','lote_almacen.lote_id')
          ->Join('stocks AS S','S.id','=','lote_almacen.stocke_id')
          ->Join('proyectos AS P','P.id','=','S.proyecto_id')
          ->Join('articulos AS A','A.id','=','lote_almacen.articulo_id')
          ->select('lote_almacen.*',
          'lote_almacen.cantidad AS total','L.nombre AS lote_nombre','L.cantidad AS total_entrada','A.nombre AS anombre','A.descripcion AS descripcion','A.codigo AS acodigo',
          'A.marca AS amarca','A.unidad AS aunidad','P.nombre_corto AS folio','P.id AS proyectoi')
          ->where('P.id','=',$id)
          // ->where("A.descripcion","like","%".$buscar."%")
          //->where('lote_almacen.cantidad','>','0')
          ->get();

          $arreglo = [];
          foreach ($data as $key => $value) {
            $partidas = DB::table('partidas AS p')->where('p.lote_id',$value->id)->select(DB::raw("SUM(cantidad) AS cs"))->first();
            $arreglo [] = [
              'data' => $value,
              'salida' => $partidas,
            ];
          }

          return response()->json(["status" => true, "articulos" => $arreglo]);

          // return response()->json(["status" => true, "articulos" => $data]);
        }
        catch (Exception $e)
        {
          Utilidades::errors($e);
          return response()->json(["status" => false, "mensage" => "Error al obtener los artículos"]);
        }
      }

      public function Cambiar(Request $request)
      {
        try
        {
          $lote = Lote::find($request->lote_id);
          $lote_almacen = LoteAlmacen::where("lote_id", $lote->id)->first();
          if ($lote_almacen == null) return response()->json(["status" => false, "mensaje" => "Ubicación no encontrada"]);
          $lote_almacen->almacene_id = $request->almacen;
          $lote_almacen->stand_id = $request->stand;
          $lote_almacen->nivel_id = $request->nivel;
          $lote_almacen->update();

          return response()->json(["status" => true]);
        }
        catch (Exception $e)
        {
          Utilidades::errors($e);
          return response()->json(["status" => false, "mensaje" => "Error al guardar la ubicación"]);
        }
      }

      public function ObtenerActual($id)
      {
        try
        {
          $lote = Lote::find($id);

          if ($lote == null) return response()->json(["status" => false, "mensaje" => "Ubicación no encontrada"]);
          $lote_almacen = LoteAlmacen::where("lote_id", $lote->id)->first();
          if ($lote_almacen == null) return response()->json(["status" => false, "mensaje" => "Ubicación no encontrada"]);
          $ubicacion = "";

          $nombre_almacen = Almacene::find($lote_almacen->almacene_id)->nombre;
          $ubicacion .= "Almacén: " . $nombre_almacen;
          if ($lote_almacen->stand_id != null)
          $ubicacion .= " Stand: " . Stand::find($lote_almacen->stand_id)->nombre;
          if ($lote_almacen->nivel_id != null)
          $ubicacion .= " Nivel: " . Nivele::find($lote_almacen->nivel_id)->nombre;
          return response()->json(["status" => true, "ubicacion" => $ubicacion]);
        }
        catch (Exception $e)
        {
          Utilidades::errors($e);
          return response()->json(["status" => false, "mensaje" => "Error al obtener la ubicación"]);
        }
      }
    }
