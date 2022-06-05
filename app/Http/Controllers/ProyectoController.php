<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proyecto;
use App\Stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Support\Facades\Storage;
use Validator;
use \App\Http\Helpers\Utilidades;
use App\Controltiempo;
use App\SolicitudViaticos;




class ProyectoController extends Controller
{
    /**
   * [protected Se definen las reglas para guardado y actualizacion de registro]
   * @var [type]
   */
    protected $rules = array(
        'nombre' => 'required|max:500',

    );
      /**
   * [index Obtiene todos los registros del modelo Proyectos]
   * @return Response [lista los proyectos por categoria y subcategoria]
   */
    public function index()
    {
      $arreglo=[];
        /*Obtiene los registros de Proyectos*/
        $proyectos = DB::table('proyectos')
        ->join('usuario_categoria as s','proyectos.proyecto_subcategorias_id','=','s.proyecto_subcategoria_id')
        ->select('proyectos.*')
        ->where(['s.user_id' => Auth::user()->id])
        ->orderBy('proyectos.id', 'asc')->get();

        foreach ($proyectos as $key => $proyecto)
        {

            $arreglo[] =
            [
                'proyecto' => $proyecto,
            ];
        }

        return response()->json($arreglo);
        /***********************************/
    }

    /**
    ** OBTIENE TODOS LOS PROYECTOS Y DEVUELVE ID Y NOMBRE CORTO PARA SE AGREGADO EN UN VUE SELECT EN LA VISTA
    **/
    public function getAll()
    {
      $proyectos = DB::table('proyectos')
      ->select('proyectos.id','proyectos.nombre_corto AS name')
      ->orderBy('proyectos.id', 'asc')
      ->get();

      return response()->json($proyectos);
    }
    /**
 * [index Obtiene todos los registros del modelo Proyectos]
 * @return Response [lista los proyectos por categoria y subcategoria]
 */
  public function proyectosPermisos()
  {
      /*Obtiene los registros de Proyectos*/
      $proyectos = DB::table('proyectos')
      ->join('usuario_categoria as s','proyectos.proyecto_subcategorias_id','=','s.proyecto_subcategoria_id')
      ->select('proyectos.*')
      ->where(['s.user_id' => Auth::user()->id])
      ->orderBy('proyectos.id', 'asc')->get();

      return response()->json($proyectos);
      /***********************************/
  }
/**
   * [todos Obtiene todos los registros del modelo Proyectos]
   * @return Response [lista todos los proyectos]
   */
    public function todos()
    {
        $proyectos = Proyecto::orderBy('id', 'asc')->get();
        $arreglo = [];
        foreach ($proyectos as $key => $proyecto)
        {
            $arreglo[] =
            [
                'proyecto' => $proyecto,
            ];
        }

        return response()->json($arreglo);
    }

    /**
   * [listar Obtiene todos los registros del modelo Proyectos]
   * @return Response [lista los proyectos por categoria y subcategoria]
   */
    public function listar()
    {
        $proyectos = DB::table('proyectos')
        ->join('usuario_categoria as s','proyectos.proyecto_subcategorias_id','=','s.proyecto_subcategoria_id')
        ->select('proyectos.*')
        ->where(['s.user_id' => Auth::user()->id])
        ->orderBy('proyectos.fecha_inicio', 'desc')->get();
        return response()->json($proyectos);
    }

    public function listarTodos()
    {
        // if ($id == 1) {
          $proyectos = Proyecto::leftJoin('proyecto_subcategorias AS PS','PS.id','=','proyectos.proyecto_subcategorias_id')
          ->leftJoin('proyecto_categorias AS PC','PC.id','=','PS.proyecto_categoria_id')
          ->select('proyectos.*','PC.nombre AS nombre_categoria')->orderBy('proyectos.id')
          ->get();
          return response()->json($proyectos);
        // }elseif ($id == 2) {
        //   $proyectos = \App\ProyectoDBP::leftJoin('proyecto_subcategorias AS PS','PS.id','=','proyectos.proyecto_subcategorias_id')
        //   ->leftJoin('proyecto_categorias AS PC','PC.id','=','PS.proyecto_categoria_id')
        //   ->select('proyectos.*','PC.nombre AS nombre_categoria')->orderBy('proyectos.id')
        //   ->get();
        //   return response()->json($proyectos);
        // }
    }

    public function buscarap()
    {

      $proyectos = Proyecto::leftJoin('proyecto_subcategorias AS PS','PS.id','=','proyectos.proyecto_subcategorias_id')
      ->leftJoin('proyecto_categorias AS PC','PC.id','=','PS.proyecto_categoria_id')
      ->select('proyectos.*','PC.nombre AS nombre_categoria')
      // ->whereIn('PC.nombre',['Proyectos','Administrativo'])
      ->orderBy('proyectos.id')
      ->get();

      return response()->json($proyectos);
      // code...
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

    public function subirDocumento(Request $request)
    {
      try {
        //obtiene el nombre del archivo y su extension
        $DocumentoNE = $request->file('documento_po')->getClientOriginalName();
        //Obtiene el nombre del archivo
        $DocumentoNombre = pathinfo($DocumentoNE, PATHINFO_FILENAME);
        //obtiene la extension
        $DocumentoExt = $request->file('documento_po')->getClientOriginalExtension();
        //nombre que se guarad en BD
        $DocumentoStore = $DocumentoNombre.'_'.uniqid().'.'.$DocumentoExt;
        //Subida del archivo al servidor ftp
        Storage::disk('local')->put('Archivos/'.$DocumentoStore, fopen($request->file('documento_po'), 'r+'));

        $proyecto = new \App\ProyectoDocumentos();
        $proyecto->proyecto_id = $request->id;
        $proyecto->documento = $DocumentoStore;
        Utilidades::auditar($proyecto, $proyecto->id);
        $proyecto->save();

        return response()->json(['status' => true]);

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function getDocumentosProyectos($id)
    {
      $proyecto = \App\ProyectoDocumentos::where('proyecto_id',$id)->where('condicion','1')->get();
      return response()->json($proyecto);
    }
    public function deleteDocTempProyectos($id)
    {
      $proyecto = \App\ProyectoDocumentos::where('proyecto_id',$id)->where('condicion','1')->get();
      foreach ($proyecto as $key => $value) {
        Storage::disk('temporal')->delete($value->documento);
      }
      return response()->json(['status' => true]);
    }

    public function deleteDocumentosProyectos($id)
    {
      $proyecto = \App\ProyectoDocumentos::where('id',$id)->first();
      $proyecto->condicion = 0;
      Utilidades::auditar($proyecto, $proyecto->id);
      $proyecto->save();
      return response()->json(['status' => true]);
    }
     /**
  * [Guarda en BD los registros de la tabla proyectos y en la tabla stock guarda el nombre_corto de la tabla proyectos,
  * guarda el documento_po en la tabla proyectos]
  * @param Request   $request [Objeto de datos del POST]
  * @return Response          [Array con estatus true]
  */

    public function store(Request $request)
    {

        if ($request->metodo == 0) {
          $validator = Validator::make($request->all(), $this->rules);

          if ($validator->fails()) {
            return response()->json(array(
              'status' => false,
              'errors' => $validator->errors()->all()
            ));
          }
          else {
           // if ($request->empresa == 1) {
              /*Inserta un nuevo registro en la BD*/
              $proyecto = new Proyecto();
              $proyecto->fill($request->all());
              Utilidades::auditar($proyecto, $proyecto->id);
              $proyecto->save();

              $stock = new Stock();
              $stock->nombre = $proyecto->nombre_corto;
              $stock->proyecto_id = $proyecto->id;
              Utilidades::auditar($stock, $stock->id);
              $stock->condicion = '1';
              $stock->save();
           // }elseif ($request->empresa == 2) {
              /*Inserta un nuevo registro en la BD*/
             /* $proyecto = new ProyectoDBP();
              $proyecto->fill($request->all());
              Utilidades::auditar($proyecto, $proyecto->id);
              $proyecto->save();

              $stock = new Stock();
              $stock->nombre = $proyecto->nombre_corto;
              $stock->proyecto_id = $proyecto->id;
              Utilidades::auditar($stock, $stock->id);
              $stock->condicion = '1';
              $stock->save();

            }*/

            /************************************/
            return response()->json(array('status' => true));
          }
        }

        if ($request->metodo == 1) {
          $DocumentoStore=null;
          //*Variables para actualizar nuevos archivos y eliminar existentes
          $ValorDocumento = null;
          $proyectos = Proyecto::where('id',$request->id)->get();

          foreach ($proyectos as $key => $item) {
            $ValorDocumento = $item->documento_po;

            $DocumentoStore=$item->documento_po;
          }
          //*FIN

          //obtiene el nombre del archivo y su extension
          $DocumentoNE = $request->file('documento_po')->getClientOriginalName();
          //Obtiene el nombre del archivo
          $DocumentoNombre = pathinfo($DocumentoNE, PATHINFO_FILENAME);
          //obtiene la extension
          $DocumentoExt = $request->file('documento_po')->getClientOriginalExtension();
          //nombre que se guarad en BD
          $DocumentoStore = $DocumentoNombre.'_'.uniqid().'.'.$DocumentoExt;
          //Subida del archivo al servidor ftp
          Storage::disk('local')->put('Archivos/'.$DocumentoStore, fopen($request->file('documento_po'), 'r+'));
          if ($ValorDocumento != null) {
            //Elimina el archivo en el servidor si requiere ser actualizado
            Utilidades::ftpSolucionEliminar($ValorDocumento);
          }
          $proyecto = Proyecto::findOrFail($request->id);
          $proyecto->documento_po = $DocumentoStore;
          $proyecto->save();



          return response()->json(array(
            'status' => true,
          ));
        }

        if ($request->metodo == 2) {
          $DocumentoStore=null;
          //*Variables para actualizar nuevos archivos y eliminar existentes
          $ValorDocumento = null;
          $proyectos = Proyecto::where('id',$request->id)->get();

          foreach ($proyectos as $key => $item) {
            $ValorDocumento = $item->documento_po;

            $DocumentoStore=$item->documento_po;
          }
          //*FIN

          //obtiene el nombre del archivo y su extension
          $DocumentoNE = $request->file('documento_po')->getClientOriginalName();
          //Obtiene el nombre del archivo
          $DocumentoNombre = pathinfo($DocumentoNE, PATHINFO_FILENAME);
          //obtiene la extension
          $DocumentoExt = $request->file('documento_po')->getClientOriginalExtension();
          //nombre que se guarad en BD
          $DocumentoStore = $DocumentoNombre.'_'.uniqid().'.'.$DocumentoExt;
          //Subida del archivo al servidor ftp
          Storage::disk('local')->put('Archivos/'.$DocumentoStore, fopen($request->file('documento_po'), 'r+'));
          if ($ValorDocumento != null) {
            //Elimina el archivo en el servidor si requiere ser actualizado
            Utilidades::ftpSolucionEliminar($ValorDocumento);
          }
          $proyecto = Proyecto::findOrFail($request->id);
          $proyecto->documento_po = $DocumentoStore;
          $proyecto->save();

          return response()->json(array(
            'status' => true,
          ));
        }
    }

     /**
   * [Consulta en BD de la tabla proyecto donde id = $id proporcionado]
   * @param  Int      $id [id del GET]
   * @return Response     [Array en formato JSON]
   */
    public function show($id)
    {
        return response()->json(Proyecto::findOrFail($id));
    }

    /**
     * [Consulta en BD que condicion tiene el Proyecto]
     *
     * @param  Int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        if ($proyecto->condicion==0 || $proyecto->condicion==2 || $proyecto->condicion==3) {
            $proyecto->condicion = 1;
        }else{
            $proyecto->condicion = 0;
            $this->cierreProyecto($proyecto);

        }
        $proyecto->update();


        return $proyecto;
    }

    /**
     * [Consulta en BD si el proyecto es condicion=2 esta en Pausa]
     *
     * @param  Int  $id
     * @return \Illuminate\Http\Response
     */

    public function pausar($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->condicion = 2;
        // $this->cierreProyecto($proyecto);
        $proyecto->update();
        return $proyecto;
    }
 /**
     * [Consulta en BD si el proyecto es condicion=3 fue rechazado]
     *
     * @param  Int  $id
     * @return \Illuminate\Http\Response
     */
    public function rechazar($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->condicion = 3;
        $this->cierreProyecto($proyecto);
        $proyecto->update();
        return $proyecto;
    }

    /**
     * [cierreProyecto Pasa al stoke general al  desactivar el proyecto]
     * @param  Datos   $datos [description]
     * @return Response        [description]
     */
    public function cierreProyecto($datos)
    {
      $proyecto_id = $datos->id;
      $Requisicion = \App\Requisicion::where('proyecto_id','=',$proyecto_id)->get()->first();
      if (!is_null($Requisicion)) {
        if ($Requisicion->condicion==0) {
            $Requisicion->condicion = 1;
        }
        else{
            $Requisicion->condicion = 0;

            $stocke = \App\Stock::where('proyecto_id','=',$proyecto_id)->get()->first();
            $stocke_id = $stocke->id;
            $stock_articulos = \App\StockArticulo::where('stocke_id','=',$stocke_id)->get()->first();
            if (!is_null($stock_articulos)) {
              $stock_articulos->stocke_id = 1;//por default debe ser 1 al perteccer al proyecto General
              $stock_articulos->update();
              $hoy = date("Y-m-d");
              $hora = date("H:i:s");
               $movimientos = \App\Movimiento::where('stocke_id','=',$stocke_id)->get();
               foreach ($movimientos as $key => $movimiento) {
                 $movientos_traspaso = new \App\Movimiento();
                 $movientos_traspaso->cantidad = $movimiento->cantidad;
                 $movientos_traspaso->fecha = $hoy;
                 $movientos_traspaso->hora = $hora;
                 $movientos_traspaso->tipo_movimiento = 'TraspasoSG';
                 $movientos_traspaso->folio = 'Traspaso -'.$movimiento->proyecto_id;
                 $movientos_traspaso->lote_id = $movimiento->lote_id;
                 $movientos_traspaso->stocke_id = 1;//por default debe ser 1 al pertenecer al proyecto General
                 $movientos_traspaso->almacene_id = $movimiento->almacene_id;
                 $movientos_traspaso->stand_id = $movimiento->stand_id;
                 $movientos_traspaso->nivel_id = $movimiento->nivel_id;
                 $movientos_traspaso->articulo_id = $movimiento->articulo_id;
                 $movientos_traspaso->save();
               }
            }


        }
          $Requisicion->update();
      }




      return response()->json(array(
        'status' => true
      ));

    }

      /**
  * [Actualiza en BD los registros de la tabla proyectos apartir de un id proporcionado respetando
  * las reglas definidas]
  * @param  Request  $request [Objeto de datos del PUT]
  * @param  Int      $id      [id del PUT]
  * @return Response          [Array con estatus true]
  */
    public function update(Request $request, $id)
    {
      $validator = Validator::make($request->all(), $this->rules);

      if ($validator->fails()) {
          return response()->json(array(
              'status' => false,
              'errors' => $validator->errors()->all()
          ));
      }
      else {

        $proyecto = Proyecto::findOrFail($id);
        $proyecto->fill($request->all());
        $proyecto->update();

        $stock = Stock::where('proyecto_id','=',$id)->first();
        $stock->nombre = $request->nombre_corto;
        $stock->save();

        return response()->json(array('status' => true));

      }

        // $stock = Stock::findOrFail($id);
        // $stock->nombre = $proyecto->nombre_corto;
        // $stock->proyecto_id = $proyecto->id;
        // $stock->condicion = '1';
        // $stock->update();
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


  /**
   * [Ordena y limita los datos de la busqueda con los filtros ]
   * @param  Arrray $datos            [Array recibido en la función]
   * @return String                    [filtra los datos]
   */
    public function busqueda($datos)
    {
        extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));

        $data = $datos;

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

        $results = $data->get()->toArray();

        return [
            'data' => $results,
            'count' => $count,
        ];
    }

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

    protected function busqueda_filter($data, $query, $fields)
    {
        return $data->where(function ($q) use ($query, $fields) {
            foreach ($fields as $index => $field) {
                $method = $index ? 'orWhere' : 'where';
                $q->{$method}($field, 'LIKE', "%{$query}%");
            }
        });
    }

    /**
   * [Obtiene los archivos del servidor ftp id = $id proporcionado]
   * @param  Int      $id [id del GET]
   * @return Response     [Array en formato JSON]
   */

    public function obtenerarchivos(Request $request)
    {
      // $valores = explode("&",$id);
      // $validador = $valores[0];
      // $nombre_archivo = $valores[1];

      // if ($validador == 1) {
        // Se obtiene el archivo del FTP serve
        $proyecto = \App\ProyectoDocumentos::where('id',$request->id)->first();

        $archivo = Utilidades::ftpSolucion($proyecto->documento);
        // Se coloca el archivo en una ruta local
        Storage::disk('temporal')->put($proyecto->documento, $archivo);
      // }

      // if ($validador == 2) {
        //elimina de la ruta local el archivo descargado
        // Storage::disk('cotizaciones')->delete($nombre_archivo);
      // }
      return response()->json($proyecto->documento);
    }

     /**
     * [Calcula y Obtiene el monto total, total ejecutado, total_cotizado y utilidad]
     * @param Int    $id [id del proyecto]
     * @return Array     [Array con suma_total, tejecu,total_cotizado,utilidad]
     */
    public function sumamontos($id)
    {

      $suma_total=0;
      $mano_obra = 0;
      $tejecu=0;
      $pro = (\DB::table('proyectos')
      ->select('monto_total'))
      ->where('id' ,'=', $id)->first();

      $sum = \DB::table('proyectos')
      ->select(\DB::raw('SUM(monto_total) AS suma'))
      ->where('proyecto_id',  $id)
      ->where('condicion', '=', '1')
      ->first();

      //
      // $tej =\DB::table('partidas_costos')
      // ->select(\DB::raw('SUM(monto_ejecutado) AS total_ejecutado'))
      // ->where('proyecto_id',  $id)
      // ->first();

      // $tejecu = $tej->total_ejecutado;
      // $suma_total = $pro->monto_total + $sum->suma;

      /**
      * MAno de obra
      */

      $controltiempo = Controltiempo::
      join('empleados AS E','E.id','=','control_tiempo.empleado_asignado_id')
      ->leftJoin('contratos AS C','C.empleado_id','=','E.id')
      ->leftJoin('sueldos AS S','S.contrato_id','=','C.id')
      ->join('proyectos AS P','P.id','=','control_tiempo.proyecto_id')
      ->select(DB::raw('SUM(S.sueldo_diario_integral + S.sueldo_diario_real) AS suma_sueldos'))
      ->where('control_tiempo.empresa','CONSERFLOW')
      ->where('control_tiempo.proyecto_id',$id)
      ->first();
      if (isset($controltiempo) == true) {
        // code...
        $mano_obra = $controltiempo->suma_sueldos;
      }
      /**FINF DE MANO DE OBRA*/
      $tejecu = $mano_obra;

      $utilidad =  $suma_total - $tejecu;
      return response()->json(array(
        'resultado' => $suma_total,
        'total_ejecutado' => $tejecu,
        'mano_obra' => $mano_obra,
        'utilidad' => $utilidad));
    }

    /**
  * [Consulta en BD los registro de la tabla proyectos y los filtra donde adicional=0 y condicion=1]
  * @return Response [Array de tipo JSON]
  */

    public function principales()
    {
      $principales = DB::table('proyectos')
        ->leftJoin('proyectos as p','p.id','=','proyectos.proyecto_id')
        ->select('proyectos.*','p.nombre_corto as superior')
        //->where('p.adicional', '=', '0')
        //->where('p.condicion', '=', '1')
        ->get();
        return response()->json($principales);
    }

    public function proyectosMaster()
    {
      // code...
      $master = DB::table('proyectos')
        ->leftJoin('proyecto_subcategorias AS ps','ps.id','=','proyectos.proyecto_subcategorias_id')
        ->select('proyectos.*')
        ->whereIn('ps.proyecto_categoria_id',['1','2'])
        ->get();
        return response()->json($master);
    }

    public function proyectocompra()
    {
      $proyectos = DB::table('proyectos')
      ->join('usuario_categoria as s','proyectos.proyecto_subcategorias_id','=','s.proyecto_subcategoria_id')
      ->select('proyectos.*')
      ->where(['s.user_id' => Auth::user()->id])
      ->orderBy('proyectos.id', 'asc')->get();


      return response()->json($proyectos);
    }

    public function listarTodosCompras()
    {
        $proyectos = DB::table('proyectos')
        ->leftJoin('proyecto_subcategorias AS PS','PS.id','=','proyectos.proyecto_subcategorias_id')
        ->leftJoin('proyecto_categorias AS PC','PC.id','=','PS.proyecto_categoria_id')
        ->select('proyectos.*','PC.nombre AS nombre_categoria')->orderBy('proyectos.fecha_inicio','desc');
        return $proyectos;
    }

    public function listarTodosViaticos($id)
    {
      if ($id == 1) {
        $svcfw = SolicitudViaticos::select('proyecto_id')->groupBy('proyecto_id')->get();
        $proyectos = DB::table('proyectos')
        ->leftJoin('proyecto_subcategorias AS PS','PS.id','=','proyectos.proyecto_subcategorias_id')
        ->leftJoin('proyecto_categorias AS PC','PC.id','=','PS.proyecto_categoria_id')
        ->select('proyectos.*','PC.nombre AS nombre_categoria')
        ->whereIn('proyectos.id',$svcfw)
        ->orderBy('proyectos.id');
      }elseif ($id == 2) {
        $svcsct = \App\SolicitudViaticosDBP::select('proyecto_id')->groupBy('proyecto_id')->get();
        $proyectos = \App\ProyectoDBP::
        leftJoin('proyecto_subcategorias AS PS','PS.id','=','proyectos.proyecto_subcategorias_id')
        ->leftJoin('proyecto_categorias AS PC','PC.id','=','PS.proyecto_categoria_id')
        ->select('proyectos.*','PC.nombre AS nombre_categoria')
        ->whereIn('proyectos.id',$svcsct)
        ->orderBy('proyectos.id');
      }


        return $proyectos;
    }

    public function buscarproyectosviaticos($id)
    {
      extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));

      $data = $this->listarTodosViaticos($id);
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
          $results = $data->get();
          // $count = sizeof($results);


          return [
            'data' => $results,
            'count' => $count,
          ];
        }

    public function buscarcompras()
    {
      extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));

      $data = $this->listarTodosCompras();
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
          $results = $data->get();
          // $count = sizeof($results);


          return [
            'data' => $results,
            'count' => $count,
          ];
        }

    public function buscar()
    {
      extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));

      $data = $this->Joins();
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
          $results = $data->get();
          // $count = sizeof($results);


          return [
            'data' => $results,
            'count' => $count,
          ];
        }

        public function buscarcentrocostos()
        {
          extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));

          $data = $this->centrocostos();
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
              $results = $data->get();
              // $count = sizeof($results);


              return [
                'data' => $results,
                'count' => $count,
              ];
            }
        /**
        * [Aquis e definen todos los JOINS para cada consilata partiendo de la tabla padre que esta almacenado en la variable $data]
        * @param Array  $data [Array recibido en la función]
        * @param String $arreglo_final  [Cadena concatenada]
        */


        public function Joins()
        {
          // $arreglo_final = [];

          $proyectos = DB::table('proyectos')
          ->join('usuario_categoria as s','proyectos.proyecto_subcategorias_id','=','s.proyecto_subcategoria_id')
          ->select('proyectos.*')
          ->where(['s.user_id' => Auth::user()->id]);

            return $proyectos;
          }

          public function centrocostos()
          {
            $proyectos = Proyecto::orderBy('id', 'asc')
                // ->leftJoin('proyectos as p','p.id','=','proyectos.proyecto_id')
                ->select('proyectos.*');

                return $proyectos;
          }

}
