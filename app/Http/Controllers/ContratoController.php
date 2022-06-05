<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contrato;
use App\ContratoDBP;
use App\Antiguedad;
use App\Bajas;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use File;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;
use \App\Http\Helpers\Utilidades;
use App\TestigoContrato;
use PHPUnit\Util\Test;

class ContratoController extends Controller
{
  protected $rules = array(
    'tipo_nomina_id' => 'required|max:45',
  );

  public function index(Request $request, $id)
  {
    if (!$request->ajax()) return redirect('/');

    $ubicacion_id = Utilidades::ubicacion();
    $tiposContrato = DB::table('contratos')
      ->leftJoin('tipo_contratos', 'tipo_contratos.id', '=', 'contratos.tipo_contrato_id')
      ->leftJoin('tipo_ubicacion', 'tipo_ubicacion.id', '=', 'contratos.tipo_ubicacion_id')
      ->leftJoin('tipo_nomina', 'tipo_nomina.id', '=', 'contratos.tipo_nomina_id')
      ->leftJoin('empleados', 'empleados.id', '=', 'contratos.empleado_id')
      ->leftJoin('horarios', 'horarios.id', '=', 'contratos.horario_id')
      ->leftJoin('tipo_horario', 'tipo_horario.id', '=', 'horarios.tipo_horario_id')
      ->leftJoin('proyectos', 'proyectos.id', '=', 'contratos.proyecto_id')
      ->select('contratos.*', 'tipo_contratos.nombre AS tc_nombre', 'tipo_nomina.nombre AS tn_nombre', 'empleados.nombre AS e_nombre', 'tipo_horario.nombre AS th_nombre', DB::raw('IFNULL(horarios.hora_entrada, "No Establecida") as h_he'), DB::raw('IFNULL(horarios.hora_salida, "No Establecida") as h_hs'), DB::raw('IFNULL(tipo_ubicacion.nombre, "Sin Definir") as tu_nombre'), DB::raw('IFNULL(proyectos.nombre_corto, "No Asignado") p_nombre'))
      ->where('contratos.empleado_id', '=', $id)
      // ->where('contratos.ubicacion_id','=',$ubicacion_id)
      ->orderBy('contratos.id', 'DESC')
      ->get()->toArray();
    return response()->json($tiposContrato);
  }
  public function contratosactivos($id)
  {
      $valores = explode('&',$id);
      if ($valores[1] == 1) {

        $tiposContrato = \App\Contrato::
           leftJoin('tipo_contratos', 'tipo_contratos.id', '=', 'contratos.tipo_contrato_id')
          ->leftJoin('tipo_ubicacion', 'tipo_ubicacion.id', '=', 'contratos.tipo_ubicacion_id')
          ->leftJoin('tipo_nomina', 'tipo_nomina.id', '=', 'contratos.tipo_nomina_id')
          ->leftJoin('empleados', 'empleados.id', '=', 'contratos.empleado_id')
          ->leftJoin('horarios', 'horarios.id', '=', 'contratos.horario_id')
          ->leftJoin('tipo_horario', 'tipo_horario.id', '=', 'horarios.tipo_horario_id')
          ->leftJoin('proyectos', 'proyectos.id', '=', 'contratos.proyecto_id')
          ->leftJoin('tipo_fin_contrato', 'tipo_fin_contrato.id', '=', 'contratos.motivo_fecha_fin')
          ->select('contratos.*', 'tipo_fin_contrato.nombre as fin_de_contratos', 'tipo_contratos.nombre AS tc_nombre', 'tipo_nomina.nombre AS tn_nombre', 'empleados.nombre AS e_nombre', 'tipo_horario.nombre AS th_nombre', DB::raw('IFNULL(horarios.hora_entrada, "No Establecida") as h_he'), DB::raw('IFNULL(horarios.hora_salida, "No Establecida") as h_hs'), DB::raw('IFNULL(tipo_ubicacion.nombre, "Sin Definir") as tu_nombre'), DB::raw('IFNULL(proyectos.nombre_corto, "No Asignado") p_nombre'))
          ->where('contratos.empleado_id', '=', $valores[0])
          ->orderBy('contratos.id', 'DESC')
          ->get();

          $contratos = [];
          foreach ($tiposContrato as $contrato)
          {

            $testigos = \App\TestigoContrato::
              join("empleados as e", "e.id", "testigos_contratos.empleado_id")
              ->where("testigos_contratos.contrato_id", $contrato->id)
              ->select(
                "testigos_contratos.*",
                DB::raw("concat_ws(' ',e.nombre,e.ap_paterno,e.ap_materno) as nombre_testigo")
              )->get()->toArray();

            $aux1 = ['testigos' => $testigos];
            $aux2 = $contrato->toArray();
            $resultado = array_merge($aux1,$aux2);
            $contratos [] = $resultado;
          }

      }elseif ($valores[1] == 2) {
        $tiposContrato = \App\ContratoDBP::
        leftJoin('tipo_contratos', 'tipo_contratos.id', '=', 'contratos.tipo_contrato_id')
       ->leftJoin('tipo_ubicacion', 'tipo_ubicacion.id', '=', 'contratos.tipo_ubicacion_id')
       ->leftJoin('tipo_nomina', 'tipo_nomina.id', '=', 'contratos.tipo_nomina_id')
       ->leftJoin('empleados', 'empleados.id', '=', 'contratos.empleado_id')
       ->leftJoin('horarios', 'horarios.id', '=', 'contratos.horario_id')
       ->leftJoin('tipo_horario', 'tipo_horario.id', '=', 'horarios.tipo_horario_id')
       ->leftJoin('proyectos', 'proyectos.id', '=', 'contratos.proyecto_id')
       ->leftJoin('tipo_fin_contrato', 'tipo_fin_contrato.id', '=', 'contratos.motivo_fecha_fin')
       ->select('contratos.*', 'tipo_fin_contrato.nombre as fin_de_contratos', 'tipo_contratos.nombre AS tc_nombre', 'tipo_nomina.nombre AS tn_nombre', 'empleados.nombre AS e_nombre', 'tipo_horario.nombre AS th_nombre', DB::raw('IFNULL(horarios.hora_entrada, "No Establecida") as h_he'), DB::raw('IFNULL(horarios.hora_salida, "No Establecida") as h_hs'), DB::raw('IFNULL(tipo_ubicacion.nombre, "Sin Definir") as tu_nombre'), DB::raw('IFNULL(proyectos.nombre_corto, "No Asignado") p_nombre'))
       ->where('contratos.empleado_id', '=', $valores[0])
       ->orderBy('contratos.id', 'DESC')
       ->get();
       $contratos = [];
       foreach ($tiposContrato as $contrato)
       {
         $testigos = \App\TestigoContratoDBP::
           join("empleados as e", "e.id", "testigos_contratos.empleado_id")
           ->where("testigos_contratos.contrato_id", $contrato->id)
           ->select(
             "testigos_contratos.*",
             DB::raw("concat_ws(' ',e.nombre,e.ap_paterno,e.ap_materno) as nombre_testigo")
           )->get()->toArray();

           $aux1 = ['testigos' => $testigos];
           $aux2 = $contrato->toArray();
           $resultado = array_merge($aux1,$aux2);
           $contratos [] = $resultado;
       }

      }

    return response()->json($contratos);
  }

  public function store(Request $request)
  {
    try
    {
      $id_antiguedad = 0;
      $condicion_antiguedad = 0;
      $contratoid = 0;
      if (!$request->ajax()) return redirect('/');

      if ($request->metodo == "Nuevo")
      {
        if ($request->empresa == 1) {
          $antiguedades = Antiguedad::orderBy('id', 'ASC')
            ->select('antiguedad.*')
            ->where('antiguedad.empleado_id', '=', $request->empleado_id)
            ->where('antiguedad.condicion', '=', 1)
            ->first();

          if ($antiguedades == '')
          {
            $antiguedad = new Antiguedad();
            $antiguedad->fecha_inicial = $request->fecha_ingreso;
            $antiguedad->fecha_final = $request->fecha_fin;
            $antiguedad->empleado_id = $request->empleado_id;
            $antiguedad->condicion = 1;
            Utilidades::auditar($antiguedad, $antiguedad->id);
            $antiguedad->save();

            $responseAntiguedad = Antiguedad::where('empleado_id', $request->empleado_id)->where('condicion', 1)->first();
            $id_antiguedad = $responseAntiguedad->id;
          }

          if ($antiguedades != '')
          {
            $responseAntiguedad = Antiguedad::where('empleado_id', $request->empleado_id)->where('condicion', 1)->first();
            $antiguedad = Antiguedad::findOrFail($responseAntiguedad->id);
            $id_antiguedad = $antiguedad->id;
            if ($responseAntiguedad->fecha_final < $request->fecha_fin)
            {
              $antiguedad->fecha_final = $request->fecha_fin;
              Utilidades::auditar($antiguedad, $antiguedad->id);
              $antiguedad->save();
            }
          }
          /*NUEVO REGISTRO*/
          $tipoContrato = new Contrato();
          $tipoContrato->id = $request->id;
          $tipoContrato->tipo_ubicacion_id = $request->tipo_ubicacion_id;
          $tipoContrato->fecha_ingreso = $request->fecha_ingreso;
          $tipoContrato->fecha_fin = $request->fecha_fin;
          $tipoContrato->tipo_nomina_id = $request->tipo_nomina_id;
          $tipoContrato->empleado_id = $request->empleado_id;
          $tipoContrato->horario_id = $request->horario_id;
          $tipoContrato->tipo_contrato_id = $request->tipo_contrato_id;
          $tipoContrato->proyecto_id = $request->proyecto_id;

          $tipoContrato->antiguedad_id = $id_antiguedad;

          // $tipoContrato->fill($request->all());
          Utilidades::auditar($tipoContrato, $tipoContrato->id);
          $tipoContrato->save();
          /*FIN NUEVO REGISTRO*/
          return response()->json(array(
            'status' => true
          ));
        }elseif ($request->empresa == 2) {
          $antiguedades = \App\AntiguedadDBP::orderBy('id', 'ASC')
            ->select('antiguedad.*')
            ->where('antiguedad.empleado_id', '=', $request->empleado_id)
            ->where('antiguedad.condicion', '=', 1)
            ->first();

          if ($antiguedades == '')
          {
            $antiguedad = new \App\AntiguedadDBP();
            $antiguedad->fecha_inicial = $request->fecha_ingreso;
            $antiguedad->fecha_final = $request->fecha_fin;
            $antiguedad->empleado_id = $request->empleado_id;
            $antiguedad->condicion = 1;
            Utilidades::auditar($antiguedad, $antiguedad->id);
            $antiguedad->save();

            $responseAntiguedad = \App\AntiguedadDBP::where('empleado_id', $request->empleado_id)->where('condicion', 1)->first();
            $id_antiguedad = $responseAntiguedad->id;
          }

          if ($antiguedades != '')
          {
            $responseAntiguedad = \App\AntiguedadDBP::where('empleado_id', $request->empleado_id)->where('condicion', 1)->first();
            $antiguedad = \App\AntiguedadDBP::findOrFail($responseAntiguedad->id);
            $id_antiguedad = $antiguedad->id;
            if ($responseAntiguedad->fecha_final < $request->fecha_fin)
            {
              $antiguedad->fecha_final = $request->fecha_fin;
              Utilidades::auditar($antiguedad, $antiguedad->id);
              $antiguedad->save();
            }
          }
          /*NUEVO REGISTRO*/
          $tipoContrato = new \App\ContratoDBP();
          $tipoContrato->id = $request->id;
          $tipoContrato->tipo_ubicacion_id = $request->tipo_ubicacion_id;
          $tipoContrato->fecha_ingreso = $request->fecha_ingreso;
          $tipoContrato->fecha_fin = $request->fecha_fin;
          $tipoContrato->tipo_nomina_id = $request->tipo_nomina_id;
          $tipoContrato->empleado_id = $request->empleado_id;
          $tipoContrato->horario_id = $request->horario_id;
          $tipoContrato->tipo_contrato_id = $request->tipo_contrato_id;
          $tipoContrato->proyecto_id = $request->proyecto_id;

          $tipoContrato->antiguedad_id = $id_antiguedad;

          // $tipoContrato->fill($request->all());
          Utilidades::auditar($tipoContrato, $tipoContrato->id);
          $tipoContrato->save();
          /*FIN NUEVO REGISTRO*/
          return response()->json(array(
            'status' => true
          ));
        }

      }


      if ($request->metodo == "Bajas")
      {

        if ($request->empresa == 1) {
          /*ACTUALIZAR REGISTRO*/

          //--Si el request no contiene archivos, solo se actualizan los campos listados--//
          if (!$request->hasFile('adjunto'))
          {
            $tipoContrato = Contrato::findOrFail($request->id);
            $tipoContrato->tipo_nomina_id = $request->tipo_nomina_id;
            $tipoContrato->motivo_fecha_fin = $request->motivo_fecha_fin;

            $tipoContrato->condicion = 0;
            $tipoContrato->update();

            /*guardado en Bajas*/
            $hoy = date("Y-m-d");
            $bajas = new Bajas();
            $bajas->fecha_baja = $hoy;
            $bajas->contrato_id = $request->id;
            Utilidades::auditar($bajas, $bajas->id);
            $bajas->save();
            /*----------------------*/

            /*Actualiza fecha_final en Antiguedad*/
            $contrato = Contrato::where('id', $request->id)->first();
            $contrato->refresh();

            $antiguedad = Antiguedad::findOrFail($contrato->antiguedad_id);
            $antiguedad->fecha_final = $hoy;
            if ($request->renovar != 1)
            {
              $antiguedad->condicion = 0;
            }
            $antiguedad->update();
            /*----------------------*/
            $antiguedadresponse = Antiguedad::where('id', $contrato->antiguedad_id);
            $condicion_antiguedad = $antiguedad->condicion;
            $contratoid = $contrato->id;

            $tipoContrato->id = $request->id;
            $tipoContrato->tipo_ubicacion_id = $request->tipo_ubicacion_id;
            $tipoContrato->fecha_ingreso = $request->fecha_ingreso;
            $tipoContrato->fecha_fin = $request->fecha_fin;
            $tipoContrato->empleado_id = $request->empleado_id;
            $tipoContrato->horario_id = $request->horario_id;
            $tipoContrato->tipo_contrato_id = $request->tipo_contrato_id;
            $tipoContrato->proyecto_id = $request->proyecto_id;
            Utilidades::auditar($tipoContrato, $tipoContrato->id);
            $tipoContrato->save();
          }

          //--valida que campos del request contienen archivos y realiza el guardado--//
          if ($request->hasFile('adjunto'))
          {
            //obtiene el nombre del archivo y su extension
            $AdjuntoNE = $request->file('adjunto')->getClientOriginalName();
            //Obtiene el nombre del archivo
            $AdjuntoNombre = pathinfo($AdjuntoNE, PATHINFO_FILENAME);
            //obtiene la extension
            $AdjuntoExt = $request->file('adjunto')->getClientOriginalExtension();
            //nombre que se guarad en BD
            $AdjuntoStore = $AdjuntoNombre . '_' . uniqid() . '.' . $AdjuntoExt;
            //Subida del archivo al servidor ftp
            Storage::disk('local')->put('Archivos/' . $AdjuntoStore, fopen($request->file('adjunto'), 'r+'));

            $tipoContrato = Contrato::findOrFail($request->id);
            $tipoContrato->tipo_nomina_id = $request->tipo_nomina_id;
            $tipoContrato->motivo_fecha_fin = $request->motivo_fecha_fin;
            $tipoContrato->condicion = 0;
            $tipoContrato->update();

            /*Guardado en Bajas y Subida de archivo en Renuncias*/
            $hoy = date("Y-m-d");
            $bajas = new Bajas();
            $bajas->fecha_baja = $hoy;
            $bajas->contrato_id = $request->id;
            Utilidades::auditar($baja, $bajas->id);
            $bajas->save();

            $baja = Bajas::where('contrato_id', $request->id)->first();
            $baja->refresh();

            $renuncia = new Renuncia();
            $renuncia->formato = $AdjuntoStore;
            $renuncia->bajas_id = $baja->id;
            Utilidades::auditar($renuncia, $renuncia->id);
            $renuncia->save();
            /*----------------------*/

            /*Actualiza fecha_final en Antiguedad*/
            $contrato = Contrato::where('id', $request->id)->first();
            $contrato->refresh();

            $antiguedad = Antiguedad::findOrFail($contrato->antiguedad_id);
            $antiguedad->fecha_final = $hoy;
            $antiguedad->condicion = 0;
            $antiguedad->update();
            /*----------------------*/
            $antiguedadresponse = Antiguedad::where('id', $contrato->antiguedad_id);
            $condicion_antiguedad = $antiguedadresponse->condicion;
            $contratoid = $contrato->id;
          }

          return response()->json(array(
            'status' => true,
            'condicion' => $condicion_antiguedad,
            'contrato' => $contratoid,
          ));
          /*FIN ACTUALIZAR REGISTRO*/
        }elseif ($request->empresa == 2) {
          /*ACTUALIZAR REGISTRO*/

          //--Si el request no contiene archivos, solo se actualizan los campos listados--//
          if (!$request->hasFile('adjunto'))
          {
            $tipoContrato = \App\ContratoDBP::findOrFail($request->id);
            $tipoContrato->tipo_nomina_id = $request->tipo_nomina_id;
            $tipoContrato->motivo_fecha_fin = $request->motivo_fecha_fin;

            $tipoContrato->condicion = 0;
            $tipoContrato->update();

            /*guardado en Bajas*/
            $hoy = date("Y-m-d");
            $bajas = new \App\BajasDBP();
            $bajas->fecha_baja = $hoy;
            $bajas->contrato_id = $request->id;
            Utilidades::auditar($bajas, $bajas->id);
            $bajas->save();
            /*----------------------*/

            /*Actualiza fecha_final en Antiguedad*/
            $contrato = \App\ContratoDBP::where('id', $request->id)->first();
            $contrato->refresh();

            $antiguedad = \App\AntiguedadDBP::findOrFail($contrato->antiguedad_id);
            $antiguedad->fecha_final = $hoy;
            if ($request->renovar != 1)
            {
              $antiguedad->condicion = 0;
            }
            $antiguedad->update();
            /*----------------------*/
            $antiguedadresponse = \App\AntiguedadDBP::where('id', $contrato->antiguedad_id);
            $condicion_antiguedad = $antiguedad->condicion;
            $contratoid = $contrato->id;

            $tipoContrato->id = $request->id;
            $tipoContrato->tipo_ubicacion_id = $request->tipo_ubicacion_id;
            $tipoContrato->fecha_ingreso = $request->fecha_ingreso;
            $tipoContrato->fecha_fin = $request->fecha_fin;
            $tipoContrato->empleado_id = $request->empleado_id;
            $tipoContrato->horario_id = $request->horario_id;
            $tipoContrato->tipo_contrato_id = $request->tipo_contrato_id;
            $tipoContrato->proyecto_id = $request->proyecto_id;
            Utilidades::auditar($tipoContrato, $tipoContrato->id);
            $tipoContrato->save();
          }

          //--valida que campos del request contienen archivos y realiza el guardado--//
          if ($request->hasFile('adjunto'))
          {
            //obtiene el nombre del archivo y su extension
            $AdjuntoNE = $request->file('adjunto')->getClientOriginalName();
            //Obtiene el nombre del archivo
            $AdjuntoNombre = pathinfo($AdjuntoNE, PATHINFO_FILENAME);
            //obtiene la extension
            $AdjuntoExt = $request->file('adjunto')->getClientOriginalExtension();
            //nombre que se guarad en BD
            $AdjuntoStore = $AdjuntoNombre . '_' . uniqid() . '.' . $AdjuntoExt;
            //Subida del archivo al servidor ftp
            Storage::disk('local')->put('Archivos/' . $AdjuntoStore, fopen($request->file('adjunto'), 'r+'));

            $tipoContrato = \App\ContratoDBP::findOrFail($request->id);
            $tipoContrato->tipo_nomina_id = $request->tipo_nomina_id;
            $tipoContrato->motivo_fecha_fin = $request->motivo_fecha_fin;
            $tipoContrato->condicion = 0;
            $tipoContrato->update();

            /*Guardado en Bajas y Subida de archivo en Renuncias*/
            $hoy = date("Y-m-d");
            $bajas = new \App\BajasDBP();
            $bajas->fecha_baja = $hoy;
            $bajas->contrato_id = $request->id;
            Utilidades::auditar($baja, $bajas->id);
            $bajas->save();

            $baja = \App\BajasDBP::where('contrato_id', $request->id)->first();
            $baja->refresh();

            $renuncia = new \App\RenunciaDBP();
            $renuncia->formato = $AdjuntoStore;
            $renuncia->bajas_id = $baja->id;
            Utilidades::auditar($renuncia, $renuncia->id);
            $renuncia->save();
            /*----------------------*/

            /*Actualiza fecha_final en Antiguedad*/
            $contrato = \App\ContratoDBP::where('id', $request->id)->first();
            $contrato->refresh();

            $antiguedad = \App\AntiguedadDBP::findOrFail($contrato->antiguedad_id);
            $antiguedad->fecha_final = $hoy;
            $antiguedad->condicion = 0;
            $antiguedad->update();
            /*----------------------*/
            $antiguedadresponse = \App\AntiguedadDBP::where('id', $contrato->antiguedad_id);
            $condicion_antiguedad = $antiguedadresponse->condicion;
            $contratoid = $contrato->id;
          }

          return response()->json(array(
            'status' => true,
            'condicion' => $condicion_antiguedad,
            'contrato' => $contratoid,
          ));
          /*FIN ACTUALIZAR REGISTRO*/
        }

      }
    }
    catch (\Throwable $e)
    {
      Utilidades::errors($e);
      return response()->json(['status' => false]);
    }
  }

  public function edit($id)
  {
    //
  }

  public function show($id)
  {
    // $ubicacion_id = Utilidades::ubicacion();


    $tiposContrato = DB::table('contratos')
      ->leftJoin('tipo_contratos', 'tipo_contratos.id', '=', 'contratos.tipo_contrato_id')
      ->leftJoin('tipo_ubicacion', 'tipo_ubicacion.id', '=', 'contratos.tipo_ubicacion_id')
      ->leftJoin('tipo_nomina', 'tipo_nomina.id', '=', 'contratos.tipo_nomina_id')
      ->leftJoin('empleados', 'empleados.id', '=', 'contratos.empleado_id')
      ->leftJoin('horarios', 'horarios.id', '=', 'contratos.horario_id')
      ->leftJoin('tipo_horario', 'tipo_horario.id', '=', 'horarios.tipo_horario_id')
      ->select(
        'contratos.*',
        'tipo_contratos.nombre AS tc_nombre',
        'tipo_nomina.nombre AS tn_nombre',
        'tipo_horario.nombre AS th_nombre',
        DB::raw('CONCAT(empleados.nombre," ",empleados.ap_paterno," ",empleados.ap_materno) AS e_nombre'),
        DB::raw('IFNULL(horarios.hora_entrada, "No Establecida") as h_he'),
        DB::raw('IFNULL(horarios.hora_salida, "No Establecida") as h_hs'),
        DB::raw('IFNULL(tipo_ubicacion.nombre, "Sin Definir") as tu_nombre')
      )
      ->where('contratos.empleado_id', '=', $id)
      ->orderBy('contratos.id', 'DESC')
      ->get();
    return response()->json($tiposContrato);
  }
  public function contratobusqueda($id)
  {


    $tiposContrato = DB::table('contratos')
      ->leftJoin('tipo_contratos', 'tipo_contratos.id', '=', 'contratos.tipo_contrato_id')
      ->leftJoin('tipo_ubicacion', 'tipo_ubicacion.id', '=', 'contratos.tipo_ubicacion_id')
      ->leftJoin('tipo_nomina', 'tipo_nomina.id', '=', 'contratos.tipo_nomina_id')
      ->leftJoin('empleados', 'empleados.id', '=', 'contratos.empleado_id')
      ->leftJoin('horarios', 'horarios.id', '=', 'contratos.horario_id')
      ->leftJoin('tipo_horario', 'tipo_horario.id', '=', 'horarios.tipo_horario_id')
      ->select(
        'contratos.*',
        'tipo_contratos.nombre AS tc_nombre',
        'tipo_nomina.nombre AS tn_nombre',
        'tipo_horario.nombre AS th_nombre',
        DB::raw('CONCAT(empleados.nombre," ",empleados.ap_paterno," ",empleados.ap_materno) AS e_nombre'),
        DB::raw('IFNULL(horarios.hora_entrada, "No Establecida") as h_he'),
        DB::raw('IFNULL(horarios.hora_salida, "No Establecida") as h_hs'),
        DB::raw('IFNULL(tipo_ubicacion.nombre, "Sin Definir") as tu_nombre')
      )
      ->where('contratos.id', '=', $id)
      ->orderBy('contratos.id', 'ASC')
      ->get();
    return response()->json($tiposContrato);
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
      if ($request->empresa == 1) {
        $tipoContrato = Contrato::findOrFail($request->id);
        $tipoContrato->id = $request->id;
        $tipoContrato->tipo_ubicacion_id = $request->tipo_ubicacion_id;
        $tipoContrato->fecha_ingreso = $request->fecha_ingreso;
        $tipoContrato->fecha_fin = $request->fecha_fin;
        $tipoContrato->tipo_nomina_id = $request->tipo_nomina_id;
        $tipoContrato->empleado_id = $request->empleado_id;
        $tipoContrato->horario_id = $request->horario_id;
        $tipoContrato->tipo_contrato_id = $request->tipo_contrato_id;
        $tipoContrato->proyecto_id = $request->proyecto_id;

        $tipoContrato->update();

        $contrato = Contrato::where('id', $request->id)->first();
        $contrato->refresh();

        $antiguedad = Antiguedad::findOrFail($contrato->antiguedad_id);
        $antiguedad->fecha_inicial = $request->fecha_ingreso;
        $antiguedad->fecha_final = $request->fecha_fin;
        Utilidades::auditar($antiguedad, $antiguedad->id);
        $antiguedad->save();

        // Actualiza los testigos del contrato
        $empleado1 = $request->testigo_1_id;
        $empleado2 = $request->testigo_2_id;

        $n_testigos = TestigoContrato::where("contrato_id", $tipoContrato->id)->count();
        $testigos = TestigoContrato::where("contrato_id", $tipoContrato->id)->get();
        // return response()->json(["status" => true, "uno" => $empleado1, "dos" => $empleado2]);
        if ($n_testigos == 0)
        {
          // Guardar 1 o 2
          $this->GuardarTestigo($tipoContrato->id, $empleado1, $request->empresa);
          $this->GuardarTestigo($tipoContrato->id, $empleado2, $request->empresa);
        }
        else if ($n_testigos == 1)
        {
          // Actualizar 1. Guardar 2
          $this->ActualizarTestigo($testigos[0]->id, $empleado1, $request->empresa);
          $this->GuardarTestigo($tipoContrato->id, $empleado2, $request->empresa);
        }
        else
        {
          // Actualzar 1 y 2
          $this->ActualizarTestigo($testigos[0]->id, $empleado1, $request->empresa);
          $this->ActualizarTestigo($testigos[1]->id, $empleado2, $request->empresa);
        }

      }elseif ($request->empresa == 2) {
        $tipoContrato = \App\ContratoDBP::findOrFail($request->id);
        $tipoContrato->id = $request->id;
        $tipoContrato->tipo_ubicacion_id = $request->tipo_ubicacion_id;
        $tipoContrato->fecha_ingreso = $request->fecha_ingreso;
        $tipoContrato->fecha_fin = $request->fecha_fin;
        $tipoContrato->tipo_nomina_id = $request->tipo_nomina_id;
        $tipoContrato->empleado_id = $request->empleado_id;
        $tipoContrato->horario_id = $request->horario_id;
        $tipoContrato->tipo_contrato_id = $request->tipo_contrato_id;
        $tipoContrato->proyecto_id = $request->proyecto_id;

        $tipoContrato->update();

        $contrato = \App\ContratoDBP::where('id', $request->id)->first();
        $contrato->refresh();

        $antiguedad = \App\AntiguedadDBP::findOrFail($contrato->antiguedad_id);
        $antiguedad->fecha_inicial = $request->fecha_ingreso;
        $antiguedad->fecha_final = $request->fecha_fin;
        Utilidades::auditar($antiguedad, $antiguedad->id);
        $antiguedad->save();

        // Actualiza los testigos del contrato
        $empleado1 = $request->testigo_1_id;
        $empleado2 = $request->testigo_2_id;

        $n_testigos = \App\TestigoContratoDBP::where("contrato_id", $tipoContrato->id)->count();
        $testigos = \App\TestigoContratoDBP::where("contrato_id", $tipoContrato->id)->get();
        // return response()->json(["status" => true, "uno" => $empleado1, "dos" => $empleado2]);
        if ($n_testigos == 0)
        {
          // Guardar 1 o 2
          $this->GuardarTestigo($tipoContrato->id, $empleado1, $request->empresa);
          $this->GuardarTestigo($tipoContrato->id, $empleado2, $request->empresa);
        }
        else if ($n_testigos == 1)
        {
          // Actualizar 1. Guardar 2
          $this->ActualizarTestigo($testigos[0]->id, $empleado1, $request->empresa);
          $this->GuardarTestigo($tipoContrato->id, $empleado2, $request->empresa);
        }
        else
        {
          // Actualzar 1 y 2
          $this->ActualizarTestigo($testigos[0]->id, $empleado1, $request->empresa);
          $this->ActualizarTestigo($testigos[1]->id, $empleado2, $request->empresa);
        }

      }

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
   * Guarda un nuevo testigo
   */
  public function GuardarTestigo($contrato_id, $empleado_id, $empresa)
  {
    if ($empleado_id == null) return; // Sin testigo
    if ($empresa == 1) {
      $testigo = new TestigoContrato();
      $testigo->contrato_id = $contrato_id;
      $testigo->empleado_id = $empleado_id;
      $testigo->save();
    }elseif ($empresa == 2) {
      $testigo = new \App\TestigoContratoDBP();
      $testigo->contrato_id = $contrato_id;
      $testigo->empleado_id = $empleado_id;
      $testigo->save();
    }

  }

  /**
   * Actualiza el testigo con los datos ingresados
   */
  public function ActualizarTestigo($testigo_id, $empleado_id, $empresa)
  {
    if ($empresa == 1) {
      $testigo = TestigoContrato::where('id',$testigo_id)->first();
      $testigo->empleado_id = $empleado_id;
      $testigo->update();
    }elseif ($empresa == 2) {
      $testigo = \App\TestigoContrato::where('id',$testigo_id)->first();
      $testigo->empleado_id = $empleado_id;
      $testigo->update();
    }

  }

  public function condicionc($id)
  {
    $contratos = DB::table('contratos')->select('contratos.*')->where('contratos.empleado_id', '=', $id)
      ->whereIn('contratos.condicion', ['1', '2'])->get();
    return response()->json($contratos);
  }

  public function getList(Request $request)
  {
    $tiposContrato = DB::table('contratos')
      ->leftJoin('tipo_contratos', 'tipo_contratos.id', '=', 'contratos.tipo_contrato_id')
      ->leftJoin('tipo_ubicacion', 'tipo_ubicacion.id', '=', 'contratos.tipo_ubicacion_id')
      ->leftJoin('tipo_nomina', 'tipo_nomina.id', '=', 'contratos.tipo_nomina_id')
      ->leftJoin('empleados', 'empleados.id', '=', 'contratos.empleado_id')
      ->leftJoin('horarios', 'horarios.id', '=', 'contratos.horario_id')
      ->leftJoin('tipo_horario', 'tipo_horario.id', '=', 'horarios.tipo_horario_id')
      ->select('contratos.*', 'tipo_contratos.nombre AS tc_nombre', 'tipo_nomina.nombre AS tn_nombre', 'empleados.nombre AS e_nombre', 'tipo_horario.nombre AS th_nombre', DB::raw('IFNULL(horarios.hora_entrada, "No Establecida") as h_he'), DB::raw('IFNULL(horarios.hora_salida, "No Establecida") as h_hs'), DB::raw('IFNULL(tipo_ubicacion.nombre, "Sin Definir") as tu_nombre'))
      ->orderBy('contratos.id', 'ASC')
      ->get();
    return response()->json($tiposContrato);
  }
  public function pdfcne($id)
  {
    $tiposContratov1 = Contrato::where('contratos.empleado_id', '=', $id)
      // $tiposContrato = DB::table('contratos')
      ->leftJoin('tipo_ubicacion', 'tipo_ubicacion.id', '=', 'contratos.tipo_ubicacion_id')
      ->leftJoin('empleados', 'empleados.id', '=', 'contratos.empleado_id')
      ->select(
        'contratos.*',
        DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) AS e_nombre"),
        DB::raw('IFNULL(tipo_ubicacion.nombre, "") as ubicacion')
      )

      ->get()->first();
    $dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado");
    $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $hoy = $dias[date('w')] . " " . date('d') . " de " . $meses[date('n') - 1] . " del " . date('Y');
    $count = 1;

    $pdfcne = PDF::loadView('pdf.canoen', compact('tiposContratov1', 'hoy'));
    $pdfcne->setPaper("letter", "portrait");

    return $pdfcne->stream();
  }
  public function pdfcii($id)
  {
    $tiposContratov2 = Contrato::where('contratos.empleado_id', '=', $id)
      // $tiposContrato = DB::table('contratos')
      ->leftJoin('tipo_ubicacion', 'tipo_ubicacion.id', '=', 'contratos.tipo_contrato_id')
      ->leftJoin('empleados', 'empleados.id', '=', 'contratos.empleado_id')
      ->select(
        'contratos.*',
        DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) AS e_nombre"),
        DB::raw('IFNULL(tipo_ubicacion.nombre, "") as ubicacion')
      )

      ->get()->first();
    $dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado");
    $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $hoy = $dias[date('w')] . " " . date('d') . " de " . $meses[date('n') - 1] . " del " . date('Y');
    $count = 1;

    $pdfcii = PDF::loadView('pdf.cainin', compact('tiposContratov2', 'hoy'));
    $pdfcii->setPaper("letter", "portrait");

    return $pdfcii->stream();
  }
  public function pdfcom($id)
  {
    $tiposContratov3 = Contrato::where('contratos.empleado_id', '=', $id)
      // $tiposContrato = DB::table('contratos')
      ->leftJoin('tipo_ubicacion', 'tipo_ubicacion.id', '=', 'contratos.tipo_contrato_id')
      ->leftJoin('empleados', 'empleados.id', '=', 'contratos.empleado_id')
      ->leftJoin('puestos', 'puestos.id', '=', 'empleados.puesto_id')
      ->select(
        'contratos.*',
        'puestos.nombre AS nombre_puesto',
        DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) AS e_nombre"),
        DB::raw('IFNULL(tipo_ubicacion.nombre, "") as ubicacion')
      )

      ->get()->first();
    $dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado");
    $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $hoy = $dias[date('w')] . " " . date('d') . " de " . $meses[date('n') - 1] . " del " . date('Y');
    $count = 1;

    $pdfcom = PDF::loadView('pdf.cacomp', compact('tiposContratov3', 'hoy'));
    $pdfcom->setPaper("letter", "portrait");

    return $pdfcom->stream();
  }

  public function pdfvol($id)
  {
    $tiposContratov4 = Contrato::where('contratos.empleado_id', '=', $id)
      // $tiposContrato = DB::table('contratos')
      ->leftJoin('tipo_ubicacion', 'tipo_ubicacion.id', '=', 'contratos.tipo_contrato_id')
      ->leftJoin('empleados', 'empleados.id', '=', 'contratos.empleado_id')
      ->select(
        'contratos.*',
        DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) AS e_nombre"),
        DB::raw('IFNULL(tipo_ubicacion.nombre, "") as ubicacion')
      )

      ->get()->first();
    $dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado");
    $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $hoy = $dias[date('w')] . " " . date('d') . " de " . $meses[date('n') - 1] . " del " . date('Y');
    $count = 1;

    $pdfcom = PDF::loadView('pdf.voluntaria', compact('tiposContratov4', 'hoy'));
    $pdfcom->setPaper("letter", "portrait");

    return $pdfcom->stream();
  }

  public function contratosproxvencer()
  {
    $arreglo = [];
    $today = date("Y-m-d");
    $nuevafecha = strtotime('+15 day', strtotime($today));
    $nuevafecha = date('Y-m-d', $nuevafecha);
    $estilo = '';
    $contratos = DB::table('contratos')
      ->leftJoin('empleados AS E', 'E.id', '=', 'contratos.empleado_id')
      ->select('contratos.*', 'E.id AS empleado_id', DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre"))
      ->whereBetween('fecha_fin', [$today, $nuevafecha])
      ->where('contratos.condicion', '=', '1')->orderBy('contratos.fecha_fin')->get();
    foreach ($contratos as $key => $value)
    {
      $diferencia = (strtotime($value->fecha_fin)) - (strtotime($today));
      $time = date("j", $diferencia);
      if ($diferencia <= 3)
      {
        $estilo = 'bg-danger';
      }
      if ($diferencia <= 10 && $diferencia > 3)
      {
        $estilo = 'bg-warning';
      }
      if ($diferencia <= 15 && $diferencia > 10)
      {
        $estilo = 'bg-primary';
      }
      $arreglo[] = [
        'contratos' => $value,
        'diff' => $time,
        'estilo' => $estilo,
      ];
    }
    return response()->json($arreglo);
  }

  public function renovar(Request $request)
  {
    $contrato = Contrato::where('id', '=', $request->id)->first();
    $contrato->condicion = 2;
    $contrato->save();

    $contrato_renovar = new \App\ContratoRenovar();
    $contrato_renovar->contrato_id = $request->id;
    $contrato_renovar->save();

    return response()->json(array('status' => true));
  }

  public function norenovar(Request $request)
  {
    $contrato = Contrato::where('id', '=', $request->id)->first();
    $contrato->condicion = 0;
    $contrato->save();
    if ($contrato->antiguedad_id != null)
    {
      $antiguedad = Antiguedad::where('empleado_id', '=', $contrato->empleado_id)->where('condicion', '=', '1')->first();
      // $antiguedad->fecha_final = $hoy;
      if (isset($antiguedad) == true)
      {
        $antiguedad->condicion = 0;
        $antiguedad->save();
      }
    }

    $hoy = date("Y-m-d");
    $bajas = new Bajas();
    $bajas->fecha_baja = $hoy;
    $bajas->contrato_id = $contrato->id;
    $bajas->save();
    return response()->json(array('status' => true));
  }

  public function porenovar(Request $request)
  {
    $contrato = Contrato::where('id', '=', $request->id_contrato)->first();
    $contrato->fecha_fin = $request->fecha_fin;
    $contrato->condicion = 1;
    $contrato->save();
    if ($contrato->antiguedad_id != null)
    {
      $antiguedad = \App\Antiguedad::where('empleado_id', '=', $contrato->empleado_id)->where('condicion', '=', '1')->first();
      $antiguedad->fecha_final = $request->fecha_fin;
      $antiguedad->save();
    }
    $contrato_renovar = \App\ContratoRenovar::where('id', '=', $request->id)->first();
    $contrato_renovar->condicion = 0;
    $contrato_renovar->save();
    return response()->json(array('status' => true));
    //  return response()->json($contrato);

  }

  public function nuevoc(Request $request)
  {
    try
    {
      $contrato = Contrato::where('id', '=', $request->contrato_id)->first();
      $contrato->condicion = 0;
      $contrato->save();
      if ($contrato->antiguedad_id != null)
      {
        $antiguedad = \App\Antiguedad::where('empleado_id', '=', $contrato->empleado_id)->where('condicion', '=', '1')
          ->where('empresa_id', '=', $contrato->empresa_id)->first();
        $antiguedad->condicion = 0;
        $antiguedad->save();
      }
      $hoy = date("Y-m-d");
      $bajas = new Bajas();
      $bajas->fecha_baja = $hoy;
      $bajas->contrato_id = $request->contrato_id;
      $bajas->save();
      $contrato_renovar = \App\ContratoRenovar::where('id', '=', $request->id)->first();
      $contrato_renovar->condicion = 0;
      $contrato_renovar->save();
      return response()->json(array('status' => true));
    }
    catch (\Throwable $e)
    {
      Utilidades::errors($e);
    }
  }

  public function finalizar(Request $request)
  {
    try
    {

      $tipoContrato = Contrato::findOrFail($request->id);
      $tipoContrato->tipo_nomina_id = $request->tipo_nomina_id;
      $tipoContrato->motivo_fecha_fin = $request->motivo_fecha_fin;
      $tipoContrato->fecha_real_fincontrato = date('Y/m/d');
      $tipoContrato->condicion = 0;
      $tipoContrato->update();

      //
      //   $contrato = DB::table('contratos')
      //   ->select('contratos.empleado_id')
      //   ->get();
      //
      //   $empleado = DB::table('empleados')
      //   ->select('empleados.id')
      //   ->get();
      //
      //   $sueldo = DB::table('sueldos')
      //   ->select('sueldos.id')
      //   ->get();
      //
      //
      //
      //   $contratos_ = DB::table('contratos')->where('contratos.id'->$request->id)->first();
      // //  $date2 = DB::table('contratos')->select('contratos.fecha_real_fincontrato')->where('contratos.id'->$request->id)->first();
      //   $diff = ($contratos_->fecha_ingreso)->diff($contratos_->fecha_real_fincontrato);
      //
      //   $dias = $diff->days;
      //
      //   // Aguinaldo proporcional (se obtiene de sueldos el sueldo diario integrado)
      //   $sdi = $sueldo->sueldo_diario_integral;
      //
      // $dias_lab_quince = $dias * 15;
      //
      // $dias_a_pagar_aguinaldo = $dias_lab_quince / 365;
      //
      //   $aguinaldo_proporcional = $sdi * $dias_a_pagar_aguinaldo;
      //
      //
      //   // Aguinald proporcional - Factor
      //   $factor_aguinaldo = 0.04109;
      //
      //   $factor_dias_lab = $factor_aguinaldo * $dias;
      //
      //   $aguinaldo_proporcional_factor = $sdi * $factor_dias_lab;
      //
      //   // Vacaciones proporcionales
      //   $factor_vacaciones = 0.0164383561643836;
      //
      //   $dias_factor = $dias * $factor_vacaciones;
      //
      //   $vacaciones_proporcionales = $sdi * $dias_factor;
      //   // Prima vacacional
      //   $prima_vacacional = $vacaciones_proporcionales * 0.25;
      //
      //   // Finiquito
      //   $total = $aguinaldo_proporcional + $vacaciones_proporcionales + $prima_vacacional;
      //
      //   $finiquito = new Finiquito();
      //   $finiquito->total = $total;
      //   $finiquito->aguinaldo_proporcional = $aguinaldo_proporcional;
      //   $finiquito->prima_vacacional = $vacaciones_proporcionales;
      //   $finiquito->vacaciones_proporcional = $prima_vacacional;
      //   $finiquito->antiguedad_id = $antiguedad->id;
      //   $finiquito->contrato_id = $request->contrato_id;
      //   $finiquito->save();

      $this->norenovar($request);
      return response()->json(array('status' => true));
    }
    catch (\Throwable $e)
    {
      Utilidades::errors($e);
      return response()->json(array('status' => 'error'));
    }
  }
}
