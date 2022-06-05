<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Cotizacion;
use Validator;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Support\Facades\Storage;
use \App\Http\Helpers\Utilidades;


class CotizacionController extends Controller
{
    protected $rules = array(

        // 'edad' => 'required|max:3',

    );
    public function index()
    {

        $cotizacion = DB::table('cotizaciones')
        ->join('proveedores', 'cotizaciones.proveedor_id', '=', 'proveedores.id')
        ->select('cotizaciones.*', 'proveedores.razon_social AS proveedor')
        ->get();
        return response()->json(
            $cotizacion->toArray()
        );

    }

    public function store(Request $request)
    {
      try{
      if (!$request->ajax()) return redirect('/');
      $validator = Validator::make($request->all(), $this->rules);
      if ($validator->fails()) {
        return response()->json(array(
          'status' => false,
          'errors' => $validator->errors()->all()
        ));
      }

      $cotizacion = new Cotizacion();
      $cotizacion->folio = $request->folio;
      $cotizacion->proveedor_id = $request->proveedor_id;
      $cotizacion->proyecto_id = $request->proyecto_id;
      Utilidades::auditar($cotizacion, $cotizacion->id);
      $cotizacion->save();

      $nombre_archivo_nuevo = $request->proyecto_id.'_'.$request->proveedor_id.'_'.$cotizacion->id.'_'.$request->folio. '.pdf';
      
      echo $nombre_archivo_nuevo;
      
      return;
      Storage::disk('local')->put('Archivos/'.$nombre_archivo_nuevo, fopen($request->file('cotizacion_file'), 'r+'));

      $cotizacion = Cotizacion::findOrFail($cotizacion->id);
      $cotizacion->adjunto = $nombre_archivo_nuevo;
      Utilidades::auditar($cotizacion, $cotizacion->id);
      $cotizacion->save();
      return response()->json(array(
        'status' => true,
        'proveedor' => $request->proveedor_id,
        'proyecto' => $request->proyecto_id,
        'nuevo_nombre' => $nombre_archivo_nuevo,
      ));
      } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }


    public function update(Request $request, $id)
    {
      try{
        if (!$request->ajax()) return redirect('/');
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
        return response()->json(array(
          'status' => false,
          'errors' => $validator->errors()->all()
      ));
  }

        $cotizacion = Cotizacion::findOrFail($request->id);
        $cotizacion->fill($request->all());
        Utilidades::auditar($cotizacion, $cotizacion->id);
        $cotizacion->save();

        return response()->json(array(
            'status' => true
        ));
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }



    public function edit($id)
    {
      $valores = explode("&",$id);

      if ($valores[2] == 2) {
        //
      }

      if ($valores[2] == 1) {
        //$valores = explode("&",$id);
        $proyecto = $valores[0];
        $proveedor = $valores[1];

        $cotizacion = DB::table('cotizaciones')
          ->join('proveedores', 'cotizaciones.proveedor_id', '=', 'proveedores.id')
          ->join('proyectos', 'cotizaciones.proyecto_id', '=', 'proyectos.id')
          ->select('cotizaciones.*', 'proveedores.razon_social AS proveedor', 'proyectos.nombre AS proyecto')
          ->where('cotizaciones.proyecto_id', '=', $proyecto)
          ->where('cotizaciones.proveedor_id', '=', $proveedor)
          ->get();
          return response()->json($cotizacion);
      }

    }

    public function show($id)
    {
      $cotizacion = DB::table('cotizaciones')
      ->where('proveedor_id','=',$id)->get();
      return response()->json($cotizacion);

    }

    public function activar(Request $request)
    {

    }

    public function cotizacionesrequeridas($id){
      $valores = explode("&",$id);

      if ($valores[1] == 1) {
        $cot_id = $valores[0];
        //if (!$request->ajax()) return redirect('/');
        $documentos =DB::table('cotizaciones')->select('cotizaciones.*')->where('cotizaciones.id','=',$cot_id)->get();
        return response()->json($documentos);
      }


      if ($valores[1] == 2) {
        $orden_compra = $valores[0];

        $documentos = DB::table('partida_cotizacion_compra')
          ->leftjoin('cotizaciones', 'cotizaciones.id', '=', 'partida_cotizacion_compra.cotizacion_id')
          ->select('cotizaciones.*')
          ->where('partida_cotizacion_compra.orden_compra_id', '=', $orden_compra)
          ->get();
        return response()->json($documentos);
      }

    }

    public function vercotizacionordencompra($id)
    {
      $coti = DB::table('partida_cotizacion_compra')
      ->join('cotizaciones AS c','c.id','=','partida_cotizacion_compra.cotizacion_id')
      ->select('partida_cotizacion_compra.*','c.folio')
      ->where('orden_compra_id','=',$id)->get();
      return response()->json($coti);

    }

    public function obtenerarchivos($id)
    {
      $valores = explode("&",$id);
      $proyecto = $valores[1];
      $proveedor = $valores[2];

      $archivos = DB::table('cotizaciones')
          ->join('proveedores', 'cotizaciones.proveedor_id', '=', 'proveedores.id')
          ->join('proyectos', 'cotizaciones.proyecto_id', '=', 'proyectos.id')
          ->select('cotizaciones.*')
          ->where('cotizaciones.proyecto_id', '=', $proyecto)
          ->where('cotizaciones.proveedor_id', '=', $proveedor)
          ->get();
      if (count($archivos) == 0) {
        return response()->json(array(
          'status' => true,
        ));
      }

      if ($valores[0] == 1) {
        foreach ($archivos as $key => $value) {
          $nombre_archivo = $value->adjunto;

          // Se obtiene el archivo del FTP serve
          $archivo = Utilidades::ftpSolucion($nombre_archivo);
          // Se coloca el archivo en una ruta local
          Storage::disk('cotizaciones')->put($nombre_archivo, $archivo);
        }
      }

      if ($valores[0] == 2) {
        foreach ($archivos as $key => $value) {
          $nombre_archivo = $value->adjunto;

          //elimina de la ruta local el archivo descargado
          Storage::disk('cotizaciones')->delete($nombre_archivo);
        }
      }

      return response()->json(array(
        'status' => true
      ));
    }



}
