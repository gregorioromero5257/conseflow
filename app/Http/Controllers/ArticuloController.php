<?php

namespace App\Http\Controllers;

use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Articulo;
use App\Lote;
use App\Almacene;
use App\Stand;
use App\Nivele;
use Rap2hpoutre\FastExcel\FastExcel;
use Validator;
use Carbon\Carbon;
use File;
use Illuminate\Support\Facades\Storage;
use Mail;


class ArticuloController extends Controller
{

  /**
  * [Definición de las reglas para ser utilizadas en la actualizacion y la insercion]
  */
    protected $rules = array(


        'unidad' => 'required|max:30',

    );


  /**
  * [Consulta en BD los registro de la tabla articulo con sus respectivas tablas recacionadas]
  * @return Response [Array de tipo JSON]
  */
    public function index(Request $request)
    {
        $articulos = Articulo::select('articulos.id','articulos.id as item','articulos.nombre','articulos.codigo','articulos.descripcion','nombreproveedor','articulos.marca',
        'articulos.unidad','articulos.comentarios','articulos.minimo','articulos.maximo','articulos.ficha_tecnica','articulos.fotografia',
        'grupos.nombre as grupo','categorias.nombre as categoria','articulos.condicion', 'categorias.id AS categoria_id', 'grupos.id AS grupo_id',
        'tipo_calidad.id AS calidad_id','tipo_calidad.nombre AS calidad','tipo_calidad.descripcion as descal','tipo_resguardo.id AS trid','tipo_resguardo.nombre AS trnom','articulos.centro_costo_id',
        "um_id"
        )
        ->leftJoin('grupos', 'grupos.id', '=', 'articulos.grupo_id')
        ->leftJoin('categorias', 'categorias.id', '=', 'grupos.categoria_id')
        ->leftJoin('tipo_calidad', 'tipo_calidad.id', '=', 'articulos.calidad_id')
        ->leftJoin('tipo_resguardo','tipo_resguardo.id','=','articulos.tipo_resguardo_id')
        ->orderBy('articulos.id', 'asc')->get()->toArray();

        return response()->json($articulos);
    }

    /**
  * [Guarda en BD los encabezados en la tabla articulo respetando las condiciones y reglas
  *  establecidas]
  * @param Request   $request [Objeto de datos del POST]
  * @return Response          [Array con estatus true]
  */
    public function store(Request $request)
    {
      try {

                if (!$request->ajax()) return redirect('/');

                $this->rules['maximo'] = 'integer|min:'.$request->minimo;

                //$validator = Validator::make($request->all(), $this->rules);

                //if ($validator->fails()) {
                  //  return response()->json(array(
                    //    'status' => false,
                      //  'errors' => $validator->errors()->all()
                    //));
                //}

                if($request->metodo == "Nuevo"){
                    /*NUEVO REGISTRO*/
                    //Variables de archivo
                    $FichaTecnicaStore=NULL;
                    $FotografiaStore=NULL;

                    //--Si el request no contiene archivos, solo se guardan los campos listados--//

                    if(!$request->hasFile('ficha_tecnica') && !$request->hasFile('fotografia'))
                    {

                        $articulo = new Articulo();
                        $articulo->grupo_id = $request->grupo_id;
                        $articulo->tipo_resguardo_id=$request->trid;
                        $articulo->calidad_id = $request->calidad_id;
                        $articulo->codigo = $request->codigo;
                        $articulo->nombre = $request->nombre;
                        $articulo->descripcion = $request->descripcion;
                        // $articulo->nombreproveedor = $request->nombreproveedor;
                        $articulo->marca = $request->marca;
                        $articulo->unidad = $request->unidad;
                        $articulo->um_id = $request->um_id;
                        $articulo->comentarios = $request->comentarios;
                        $articulo->minimo = $request->minimo;
                        $articulo->maximo = $request->maximo;
                        $articulo->condicion = '1';
                        $articulo->centro_costo_id = $request->centro_costo_id;
                        Utilidades::auditar($articulo, $articulo->id);
                        // $articulo->codigo_producto=$request->codigoProductoSat;
                        $articulo->save();

                        return response()->json(array(
                            'status' => true
                        ));
                    }

                    //--valida que campos del request contienen archivos y realiza el guardado--//
                    if($request->hasFile('ficha_tecnica') || $request->hasFile('fotografia'))
                    {
                        if ($request->hasFile('ficha_tecnica')) {
                            //obtiene el nombre del archivo y su extension
                            $FichaTecnicaNE = $request->file('ficha_tecnica')->getClientOriginalName();
                            //Obtiene el nombre del archivo
                            $FichaTecnicaNombre = pathinfo($FichaTecnicaNE, PATHINFO_FILENAME);
                            //obtiene la extension
                            $FichaTecnicaExt = $request->file('ficha_tecnica')->getClientOriginalExtension();
                            //nombre que se guarad en BD
                            $FichaTecnicaStore = $FichaTecnicaNombre.'_'.uniqid().'.'.$FichaTecnicaExt;
                            //Subida del archivo al servidor local
                            Storage::disk('local')->put('Archivos/'.$FichaTecnicaStore, fopen($request->file('ficha_tecnica'), 'r+'));
                        }
                        if ($request->hasFile('fotografia')) {
                            //obtiene el nombre del archivo y su extension
                            $FotografiaNE = $request->file('fotografia')->getClientOriginalName();
                            //Obtiene el nombre del archivo
                            $FotografiaNombre = pathinfo($FotografiaNE, PATHINFO_FILENAME);
                            //obtiene la extension
                            $FotografiaExt = $request->file('fotografia')->getClientOriginalExtension();
                            //nombre que se guarad en BD
                            $FotografiaStore = $FotografiaNombre.'_'.uniqid().'.'.$FotografiaExt;
                            //Subida del archivo al servidor local
                            Storage::disk('local')->put('Archivos/'.$FotografiaStore, fopen($request->file('fotografia'), 'r+'));
                        }

                        $articulo = new Articulo();
                        $articulo->grupo_id = $request->grupo_id;
                        $articulo->tipo_resguardo_id = $request->trid;
                        $articulo->calidad_id = $request->calidad_id;
                        $articulo->codigo = $request->codigo;
                        $articulo->nombre = $request->nombre;
                        $articulo->descripcion = $request->descripcion;
                        // $articulo->nombreproveedor = $request->nombreproveedor;
                        $articulo->marca = $request->marca;
                        $articulo->unidad = $request->unidad;
                        $articulo->um_id = $request->um_id;
                        $articulo->comentarios = $request->comentarios;
                        $articulo->minimo = $request->minimo;
                        $articulo->maximo = $request->maximo;
                        $articulo->ficha_tecnica = $FichaTecnicaStore;
                        $articulo->fotografia = $FotografiaStore;
                        $articulo->condicion = '1';
                        $articulo->centro_costo_id = $request->centro_costo_id;
                        // $articulo->codigo_producto=$request->codigoProductoSat;
                        Utilidades::auditar($articulo, $articulo->id);
                        $articulo->save();
                    }
                    return response()->json(array(
                        'status' => true,
                    ));
                    /*FIN NUEVO REGISTRO*/
                }


                if($request->metodo == "Actualizar"){
                    /*ACTUALIZAR REGISTRO*/
                    //Variables de archivo
                    $FichaTecnicaStore=NULL;
                    $FotografiaStore=NULL;
                    //*Variables para actualizar nuevos archivos y eliminar existentes
                    $ValorFichaTecnica = NULL;
                    $ValorFotografia = NULL;
                    $articulos = Articulo::where('id',$request->id)->get();

                    foreach ($articulos as $key => $item) {
                        $ValorFichaTecnica = $item->ficha_tecnica;
                        $ValorFotografia = $item->fotografia;

                        $FichaTecnicaStore=$item->ficha_tecnica;
                        $FotografiaStore=$item->fotografia;
                    }
                    //*FIN

                    //--Si el request no contiene archivos, solo se actualizan los campos listados--//
                    if(!$request->hasFile('ficha_tecnica') && !$request->hasFile('fotografia'))
                    {

                        $articulo = Articulo::findOrFail($request->id);
                        $articulo->grupo_id = $request->grupo_id;
                        //$articulo->tipo_resguardo_id = $request->trid;
                        $articulo->calidad_id = $request->calidad_id;
                        $articulo->codigo = $request->codigo;
                        $articulo->nombre = $request->nombre;
                        $articulo->descripcion = $request->descripcion;
                        // $articulo->nombreproveedor = $request->nombreproveedor;
                        $articulo->marca = $request->marca;
                        $articulo->unidad = $request->unidad;
                        $articulo->um_id = $request->um_id;
                        $articulo->comentarios = $request->comentarios;
                        $articulo->minimo = $request->minimo;
                        $articulo->maximo = $request->maximo;
                        $articulo->condicion = $articulo->condicion;
                        $articulo->centro_costo_id = $request->centro_costo_id;
                        // $articulo->codigo_producto=$request->codigoProductoSat;
                        Utilidades::auditar($articulo, $articulo->id);
                        $articulo->save();

                        return response()->json(array(
                            'status' => true
                        ));
                    }

                    //--valida que campos del request contienen archivos y realiza el guardado--//
                    if($request->hasFile('ficha_tecnica') || $request->hasFile('fotografia'))
                    {
                        if ($request->hasFile('ficha_tecnica')) {
                            //obtiene el nombre del archivo y su extension
                            $FichaTecnicaNE = $request->file('ficha_tecnica')->getClientOriginalName();
                            //Obtiene el nombre del archivo
                            $FichaTecnicaNombre = pathinfo($FichaTecnicaNE, PATHINFO_FILENAME);
                            //obtiene la extension
                            $FichaTecnicaExt = $request->file('ficha_tecnica')->getClientOriginalExtension();
                            //nombre que se guarad en BD
                            $FichaTecnicaStore = $FichaTecnicaNombre.'_'.uniqid().'.'.$FichaTecnicaExt;
                            //Subida del archivo al servidor local
                            Storage::disk('local')->put('Archivos/'.$FichaTecnicaStore, fopen($request->file('ficha_tecnica'), 'r+'));
                            if ($ValorFichaTecnica != NULL) {
                                //Elimina el archivo en el servidor si requiere ser actualizado
                                //Se busca en disk o carpeta -----
                                Utilidades::ftpSolucionEliminar($ValorFichaTecnica);
                            }
                        }
                        if ($request->hasFile('fotografia')) {
                            //obtiene el nombre del archivo y su extension
                            $FotografiaNE = $request->file('fotografia')->getClientOriginalName();
                            //Obtiene el nombre del archivo
                            $FotografiaNombre = pathinfo($FotografiaNE, PATHINFO_FILENAME);
                            //obtiene la extension
                            $FotografiaExt = $request->file('fotografia')->getClientOriginalExtension();
                            //nombre que se guarad en BD
                            $FotografiaStore = $FotografiaNombre.'_'.uniqid().'.'.$FotografiaExt;
                            //Subida del archivo al servidor local
                            Storage::disk('local')->put('Archivos/'.$FotografiaStore, fopen($request->file('fotografia'), 'r+'));
                            if ($ValorFotografia != NULL) {
                                //Elimina el archivo en el servidor si requiere ser actualizado
                                //Se busca en disk o carpeta -----
                              Utilidades::ftpSolucionEliminar($ValorFotografia);
                            }
                        }

                        $articulo = Articulo::findOrFail($request->id);
                        $articulo->grupo_id = $request->grupo_id;
                        //$articulo->tipo_resguardo_id = $request->trid;
                        $articulo->calidad_id = $request->calidad_id;
                        $articulo->codigo = $request->codigo;
                        $articulo->nombre = $request->nombre;
                        $articulo->descripcion = $request->descripcion;
                        // $articulo->nombreproveedor = $request->nombreproveedor;
                        $articulo->marca = $request->marca;
                        $articulo->unidad = $request->unidad;
                        $articulo->um_id = $request->um_id;
                        $articulo->comentarios = $request->comentarios;
                        $articulo->minimo = $request->minimo;
                        $articulo->maximo = $request->maximo;
                        $articulo->ficha_tecnica = $FichaTecnicaStore;
                        $articulo->fotografia = $FotografiaStore;
                        $articulo->condicion = $articulo->condicion;
                        $articulo->centro_costo_id = $request->centro_costo_id;
                        // $articulo->codigo_producto=$request->codigoProductoSat;
                        Utilidades::auditar($articulo, $articulo->id);
                        $articulo->save();
                    }

                    return response()->json(array(
                        'status' => true
                    ));
                    /*FIN ACTUALIZAR REGISTRO*/
                }
            }
       catch (\Throwable $e) {
        Utilidades::errors($e);
        return response()->json(array('status' => 'error'));
      }

    }

    /**
   * [Consulta el archivo almacenado en el servidor]
   * @param  Int      $id [id del GET]
   * @return Response     [Array en formato JSON]
   */
    public function show($id)
    {
        $archivo = Utilidades::ftpSolucion($id);
        //--Devuelve la respuesta y descarga el archivo--//
        Storage::disk('descarga')->put($id, $archivo);

        return response()->download(storage_path().'/app/descargas/'.$id);
    }

     /**
   * [Elimina el archivo almacenado en el servidor configurado]
   * @param  String $id [Nombre del archivo]
   * @return         [objeto de tipo descarga con la url del archivo]
   */
    public function edit($id)
    {
        //elimina de la ruta local el archivo descargado
        Storage::disk('descarga')->delete($id);
        Storage::disk('local')->delete($id);
    }

    public function update(Request $request)
    {
        //
    }
/**
  * [activar Actualiza el campo condición a 1 de un registro en el modelo Articulo]
  * @param  Request $request [description]
  * @return Response           [description]
  */
    public function desactivar(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = '0';
        Utilidades::auditar($articulo, $articulo->id);
        $articulo->save();
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }
/**
  * [desactivar Actualiza el campo condición a 1 de un registro en el modelo Articulo]
  * @param  Request $request [description]
  * @return Response           [description]
  */
    public function activar(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = '1';
        Utilidades::auditar($articulo, $articulo->id);
        $articulo->save();
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }
 /**
     * [Query del lado del servidor de el modelo Articulo]
     * @return Array [Array que contiene data y count]
     */
    public function busqueda()
    {
        extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));

        $data = Articulo::select(
            [
            'id', 'codigo', 'nombre', 'descripcion', 'marca','calidad_id','unidad','comentarios','minimo','maximo','tipo_resguardo_id','ficha_tecnica','fotografia','grupo_id','condicion','grupo_id','calidad_id'
            ])->where('condicion','=','1');

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
        // leftJoin('tipo_calidad AS TC','TC.id','=','articulos.calidad_id')
        // ->
        $results = $this->tipoCalidad($data->get());

        return [
            'data' => $results,
            'count' => $count,
        ];
    }
 /**
     * [Consulta en la BD el nombre y descripcion del tipo de calidad]
     * @param Array  $data [Array recibido en la función]
     * @param String $arreglo_final  [Cadena concatenada]
     */
    public function tipoCalidad($data)
    {
      $arreglo_final = [];
      foreach ($data as $key => $value) {
        $tipo_calidad = \App\TipoCalidad::where('id','=',$value->calidad_id)->first();
        $arreglo_final [] = array_merge($value->toArray(), [
          'calidad' => $tipo_calidad == null ? '' : $tipo_calidad->nombre,
           'descal' => $tipo_calidad == null ? '' : $tipo_calidad->descripcion]);
      }
      return $arreglo_final;
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
     * [Cargar los articulos en las tablas:
     * ArticuloS, Lotes, Lote_stocks, Almacenes, Stands, Niveles, Nivel_lotes
     * Recorrer los registros del archivo con FastExcel
     * Si el articulo no se encuentra en la tabla Articulos se insertara tambien Lotes y Lote_stocks
     * Se buscara si el Almacen se encuentra en a base si no esta se agregara
     * Se buscara el Stand en el Almacen si no se encuentra se agregara
     * Se buscara el Nivel en el Stand si no se encuentra se agregara
     * Para la columna Nivel en el excel se agrega se reemplaza la 'Y ' por un ','
     * para convertir el string en un array de los niveles, se aplica trim
     * para buscar si existe el nivel o para agregarlo
     * Se inserta en Nivel_lotes despues de obtener el id del lote y nivel
     * Se aumento el tiempo de ejecucion del script a 5 min]
     * @return array Regresa el numero de los registros nuevos, repetidos, la fecha y hora de inicio y fin del script
     */
    public function uploadArticulos(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        if($request->hasFile('import_file')){

        ini_set('max_execution_time', 300);

        $collection = (new FastExcel)->import($request->file('import_file')->getRealPath());

        $nuevos = 0;
        $repetidos = 0;
        $descripcionRepetidos = array();
        $inicio = date('Y-m-d H:i:s');

        foreach($collection as $item) {

            if ( empty($item['DESCRIPCIÓN']) ) continue;

            $articulo = Articulo::where('descripcion', $item['DESCRIPCIÓN'])
                        ->where('codigo', $item['CODIGO DE FABRICA'])
                        ->where('marca', $item['MARCA'])
                        ->first();

            if (empty($articulo)) {

                $articulo = new Articulo();
                $articulo->codigo = $item['CODIGO DE FABRICA'];
                $articulo->descripcion = $item['DESCRIPCIÓN'];
                $articulo->marca = $item['MARCA'];
                $articulo->unidad = $item['UNIDAD'];
                $articulo->comentarios = $item['COMENTARIOS'];
                $articulo->save();
                $nuevos++;

                $lote = new Lote();
                $lote->articulo_id = $articulo->id;
                $lote->cantidad = $item['CANTIDAD'] ?? 0;
                $lote->entrada_id = 0;
                $lote->condicion = 1;
                $lote->precio_unitario = 0;
                $lote->iva = 0;
                $lote->descuento = 0;
                $lote->t_cambio = 0;
                $lote->total = 0;
                $lote->moneda = 'MXN';
                $lote->save();

                DB::table('lote_stocks')->insert([
                    'lote_id' => $lote->id,
                    'stocke_id' => 1,
                    'cantidad' => $item['CANTIDAD'] ?? 0
                ]);

                // Articulo, Nivel y Stand

                if ( empty($item['NUMERO DE INVENTARIO']) ) continue;

                if ( empty($item['STAND']) ) continue;

                if ( empty($item['NIVEL']) ) continue;

                $almacen = Almacene::where('nombre', $item['NUMERO DE INVENTARIO'])->first();

                $idAlmacen = 0;
                if (empty($almacen)) {
                    $newAlmacen = new Almacene();
                    $newAlmacen->nombre = $item['NUMERO DE INVENTARIO'];
                    $newAlmacen->save();
                    $idAlmacen = $newAlmacen->id;
                } else {
                    $idAlmacen = $almacen->id;
                }

                $stand = Stand::where('nombre', $item['STAND'])
                                ->where('almacene_id', $idAlmacen)
                                ->first();

                $idStand = 0;
                if (empty($stand)) {
                    $newStand = new Stand();
                    $newStand->nombre = $item['STAND'];
                    $newStand->almacene_id = $idAlmacen;
                    $newStand->save();
                    $idStand = $newStand->id;
                } else {
                    $idStand = $stand->id;
                }

                $item['NIVEL'] = str_ireplace(' Y', ',', $item['NIVEL']);
                $nivelesArticulo = explode(',', $item['NIVEL']);

                foreach($nivelesArticulo as $nivelArticulo)
                {
                    $nivel = Nivele::where('nombre', trim($nivelArticulo))
                                ->where('stande_id', $idStand)
                                ->first();

                    $idNivel = 0;
                    if (empty($nivel)) {
                        $newNivel = new Nivele();
                        $newNivel->nombre = $nivelArticulo;
                        $newNivel->stande_id = $idStand;
                        $newNivel->save();
                        $idNivel = $newNivel->id;
                    } else {
                        $idNivel = $nivel->id;
                    }

                    DB::table('nivel_lotes')->insert([
                        'lote_id' => $lote->id,
                        'nivele_id' => $idNivel,
                    ]);

                }

            } else {
                $repetidos++;
                array_push($descripcionRepetidos, $item['DESCRIPCIÓN']);
            }
        }

        $fin = date('Y-m-d H:i:s');

        return response()->json(
            array(
                'nuevos'=> $nuevos,
                'repetidos' => $repetidos,
                'inicio' => $inicio,
                'fin'=> $fin,
                'descripcionrepetidos' => $descripcionRepetidos
            ));
        }
    }
/**
  * [Consulta en la BD las existencias por lote y stock]
  * @param  Request  $request [Objeto de datos del PUT]
  * @return Response          [ exitencias,lotes y stock]
  */
    public function existencias(Request $request)
    {
        $existencias = DB::table('existencias')
        ->select('existencias.*', 'almacenes.nombre AS almacen', 'stands.nombre AS stand', 'niveles.nombre AS nivel')
        ->join('almacenes', 'existencias.almacene_id', '=', 'almacenes.id')
        ->leftJoin('stands', 'existencias.stand_id', '=', 'stands.id')
        ->leftJoin('niveles', 'existencias.nivel_id', '=', 'niveles.id')
        ->where([
            ['articulo_id', '=', $request->articulo_id],
            ['cantidad', '>', '0']
        ])->get();

        $loteuno = DB::table('lote_almacen')
        ->where([
            ['articulo_id', '=', $request->articulo_id],
            ['cantidad', '>', '0']
            ])
        ->get();
        // tunderbird
        $lotedos = DB::table('lote_temporal')
        ->leftJoin('lote_almacen AS LA','LA.id','=','lote_temporal.lote_almacen_id')
        ->select('LA.id','LA.caducidad','lote_temporal.comentario','lote_temporal.cantidad')
        ->where([
            ['lote_temporal.articulo_id', '=', $request->articulo_id],
            ['lote_temporal.cantidad', '>', '0']
            ])
            ->get();

        $lotes = $loteuno->merge($lotedos);

        //Obtener las articulos defectuosos en almacén
        $defectuosos=[];
        $defectuosos=DB::select("
            select la.id,la.articulo_id ,la.id as lote, pr.cantidad_defectuoso as cantidad
            from lote_almacen la
            join partidas_retorno pr
            on pr.articulo_id =la.articulo_id
            where la.articulo_id =$request->articulo_id and pr.cantidad_defectuoso >0 "
        );


        $stocks = DB::table('stock_articulos')
        ->join('stocks', 'stock_articulos.stocke_id', '=', 'stocks.id')
        ->where([
            ['articulo_id', '=', $request->articulo_id],
            ['cantidad', '>', '0']
            ])
        ->get();

        return response()->json([
            'existencias' => $existencias->toArray(),
            'lotes' => $lotes->toArray(),
            'stocks' => $stocks->toArray(),
            "defectuosos"   => $defectuosos,
        ]);
    }

    /**
  * [Consulta los movimientos de los articulos]
  * @param  Request  $request [Objeto de datos del PUT]
  * @return Response          [Movimientos]
  */
    public function kardex(Request $request)
    {
        $movimientos = DB::table('movimientos')
            ->select('movimientos.*', 'almacenes.nombre AS almacen', 'lotes.nombre AS lote', 'stocks.nombre AS stock')
            ->join('almacenes', 'movimientos.almacene_id', '=', 'almacenes.id')
            ->leftJoin('lotes', 'movimientos.lote_id', '=', 'lotes.id')
            ->leftJoin('stocks', 'movimientos.stocke_id', '=', 'stocks.id')
            ->where('movimientos.articulo_id', '=', $request->get('articulo_id'))
            ->orderBy('movimientos.id', 'DESC')
            ->paginate(5);
        return response()->json([
            'movimientos' => $movimientos,
        ]);
    }
    /**
  * [Consulta los maximos]
  * @param  Request  $request [Objeto de datos del PUT]
  * @return Response          [resultado]
  */
    public function maximos(Request $request)
    {
        $resultado = DB::select('select * from (select id, nombre, codigo, descripcion, maximo from articulos where maximo is not null and maximo > 0 and condicion = 1) as t1 join (select articulo_id, sum(cantidad) as existencia from existencias group by articulo_id HAVING existencia > 0) as t2 on t1.id = t2.articulo_id WHERE existencia > maximo');
        return response()->json([
            'registros' => $resultado,
        ]);
    }
 /**
  * [Consulta los minimos]
  * @param  Request  $request [Objeto de datos del PUT]
  * @return Response          [resultado]
  */
    public function minimos(Request $request)
    {
        $resultado = DB::select('select * from (select id, nombre, codigo, descripcion, minimo from articulos where minimo is not null and minimo > 0 and condicion = 1) as t1 join (select articulo_id, sum(cantidad) as existencia from existencias group by articulo_id HAVING existencia > 0) as t2 on t1.id = t2.articulo_id WHERE existencia < minimo');
        return response()->json([
            'registros' => $resultado,
        ]);
    }
 /**
  * [Consulta los articulos proximos a caducar]
  * @param  Request  $request [Objeto de datos del PUT]
  * @return Response          [resultado,fecha y nueva fecha]
  */
    public function proximosCaducar(Request $request)
    {
        $fecha = date('Y-m-d');
        $nuevafecha = strtotime ( '+30 day' , strtotime ( $fecha ) ) ;
        $nuevafecha = date ( 'Y-m-d' , $nuevafecha );

        $resultado = DB::select("SELECT l.id, a.codigo, a.nombre, a.descripcion, l.cantidad, l.caducidad FROM lote_almacen AS l join articulos AS a ON l.articulo_id=a.id WHERE CANTIDAD > 0 AND (caducidad >= '$fecha' AND caducidad < '$nuevafecha')");

        return response()->json([
            'fecha_actual' => $fecha,
            'fecha_limite' => $nuevafecha,
            'registros' => $resultado,
        ]);
    }
 /**
  * [Consulta los articulos caducados]
  * @param  Request  $request [Objeto de datos del PUT]
  * @return Response          [resultado y fecha]
  */
    public function caducados(Request $request)
    {
        $fecha = date('Y-m-d');

        $resultado = DB::select("SELECT l.id, a.codigo, a.nombre, a.descripcion, l.cantidad, l.caducidad FROM lote_almacen AS l join articulos AS a ON l.articulo_id=a.id WHERE CANTIDAD > 0 AND caducidad < '$fecha'");

        return response()->json([
            'fecha_actual' => $fecha,
            'registros' => $resultado,
        ]);
    }

    public function getArt(Request $request)
    {
      try {
        $articulo = DB::table('articulos AS a')
        // ->where('a.nombre','LIKE','%'.$request->articulo.'%')
        ->where('a.descripcion','LIKE','%'.$request->articulo.'%')
        ->get();

        return response()->json($articulo);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

}
