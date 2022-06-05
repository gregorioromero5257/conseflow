<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DigitoNivelUno;
use App\DigitoNivelDos;
use App\AsignacionDigitoAgrupador;
use \App\Http\Helpers\Utilidades;
use App\CodigoAgrupador;


class DigitoController extends Controller
{
  public $json_list = array();
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $digito_nivel_uno = DigitoNivelUno::orderBy('id', 'desc')
        ->get()->toArray();
        return response()->json($digito_nivel_uno);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
        $digito_nivel_uno = new DigitoNivelUno();
        $digito_nivel_uno->fill($request->all());
        Utilidades::auditar($digito_nivel_uno, $digito_nivel_uno->id);
        $digito_nivel_uno->save();

        return response()->json(array('status' => true));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
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
        //
        $digito_nivel_dos = DigitoNivelDos::where('nivel_uno_digito_id',$id)->get();
        return response()->json($digito_nivel_dos);
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
      try{
      $digito_nivel_uno = DigitoNivelUno::where('id',$id)->first();
      $digito_nivel_uno->fill($request->all());
      Utilidades::auditar($digito_nivel_uno, $digito_nivel_uno->id);
      $digito_nivel_uno->save();

      return response()->json(array('status' => true));
       } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function digitoniveldosguardar(Request $request)
    {
      try{
        $digito_nivel_dos = new DigitoNivelDos();
        $digito_nivel_dos->fill($request->all());
        Utilidades::auditar($digito_nivel_dos, $digito_nivel_dos->id);
        $digito_nivel_dos->save();
        return response()->json(array('status' => true));
       } catch (\Throwable $e) {
      Utilidades::errors($e);
    }  
    }

    public function digitoniveldosactualizar(Request $request)
    {
        try{
        $digito_nivel_dos = DigitoNivelDos::where('id',$request->id)->first();
        $digito_nivel_dos->fill($request->all());
        Utilidades::auditar($digito_nivel_dos, $digito_nivel_dos->id);
        $digito_nivel_dos->save();

        return response()->json(array('status' => true));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function guardarasignacion(Request $request)
    {
      try{
      $asignacion = new AsignacionDigitoAgrupador();
      $asignacion->partida_compra_id = $request->id;
      $asignacion->nivel_dos_id = $request->nivel_dos_id;
      Utilidades::auditar($asignacion, $asignacion->id);
      $asignacion->save();

      return response()->json(array('status' => true));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function eliminarasignacion($id)
    {
      // code...
      $asignacion = AsignacionDigitoAgrupador::where('id',$id)->delete();
      return response()->json(array('status' => true));

    }

    public function codigoagrupador()
    {
      $json_list = array();

        $json_list[] = $this->hasChilden();
    return response()->json($json_list);
    }

    public function hasChilden($id=0){
	    $inicio = '';
      $json_list = array();
	    $empleados = $this->getCodigo($id);
	    foreach ($empleados as $k => $e) {
	    	if ($this->isLast($e->id)) {
	    		$json_list[] =	[
									'id' => $e->id,
									'name' => $e->nombre_cuenta_sub,
                  'pertenece_id' => $e->pertenece_id,
                  'nivel' => $e->nivel,
                  'codigo_agrupador' => $e->codigo_agrupador,
								];
	    	}else{
	    		$json_list[] =
	    		[
					'id' => $e->id,
					'name' => $e->nombre_cuenta_sub,
          'pertenece_id' => $e->pertenece_id,
          'nivel' => $e->nivel,
          'codigo_agrupador' => $e->codigo_agrupador,
	    			'children' => $this->hasChilden($e->id)
				];
	    	}
	    }
	    return $json_list;
	}
  public function islast($id)
  {
    $empleados = \DB::table('codigo_agrupador')->where('codigo_agrupador.pertenece_id', $id)->count();
    if ($empleados==0) {
      return true;
    }
    return false;
  }
  public function getCodigo($id=0)
  {
    $empleados = \DB::table('codigo_agrupador')
                ->select('codigo_agrupador.*')
                ->where('codigo_agrupador.pertenece_id' , $id)
                ->get();
    return $empleados;
  }

  public function get($id)
  {
    $dato = \DB::table('codigo_agrupador')
                ->select('codigo_agrupador.*')
                ->where('codigo_agrupador.pertenece_id' , $id)
                ;
    return $this->busqueda($dato);
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
    // leftJoin('tipo_calidad AS TC','TC.id','=','articulos.calidad_id')
    // ->
    $results = $data->get();

    return [
        'data' => $results,
        'count' => $count,
    ];
  }

  public function guardar(Request $request)
  {
    try{
    $numero = CodigoAgrupador::where('pertenece_id',$request->pertenece_id)->get();
    $valor = count($numero);
    $codigo = new CodigoAgrupador();
    $codigo->pertenece_id = $request->pertenece_id;
    $codigo->nuvel_estado = $request->nivel + 1;
    $codigo->codigo_agrupador = $valor + 1;
    $codigo->nombre_cuenta_sub = $request->nombre_subcuenta;
    Utilidades::auditar($codigo, $codigo->id);
    $codigo->save();
    return response()->json(array('status' => true,'id' => $request->pertenece_id));
     } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function put(Request $request)
  {
    try{
    $codigo = CodigoAgrupador::where('id',$request->id)->first();
    $codigo->nombre_cuenta_sub = $request->nombre_subcuenta;
    Utilidades::auditar($codigo, $codigo->id);
    $codigo->save();
    return response()->json(array('status' => true,'id' => $request->pertenece_id));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }
}
