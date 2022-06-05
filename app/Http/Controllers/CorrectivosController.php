<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Unidades;
use Validator;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Support\Facades\Storage;
use \App\Http\Helpers\Utilidades;


class CorrectivosController extends Controller
{
    protected $rules = array(
        'modelo' => 'required|max:45',
    );

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $unidades = Unidades::orderBy('id', 'ASC')->get()->toArray();
        return response()->json($unidades);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $unidades = Unidades::orderBy('id', 'ASC')->get();

        return response()->json($unidades);
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
        if($request->metodo == "Nuevo"){
            /*NUEVO REGISTRO*/
            //Variables de archivo
            $PolizaStore='';
            $TarjetaStore='';
            $VerificacionStore='';

            if (!$request->ajax()) return redirect('/');
            //--Si el request no contiene archivos, se guardan los campos listados--//
            if(!$request->hasFile('poliza') && !$request->hasFile('tarjeta_circulacion') && !$request->hasFile('verificacion')) {
                $unidad = new Unidades();
                $unidad->unidad = $request->unidad;
                $unidad->modelo = $request->modelo;
                $unidad->placas = $request->placas;
                $unidad->ultimo_servicio = $request->ultimo_servicio;
                $unidad->poliza = '';
                $unidad->tarjeta_circulacion = '';
                $unidad->verificacion = '';
                $unidad->historial_servicio ='';
                Utilidades::auditar($unidad, $unidad->id);
                $unidad->save();
            }

            //--valida que campos del request contienen archivos y realiza el guardado--//
            if($request->hasFile('poliza') || $request->hasFile('tarjeta_circulacion') || $request->hasFile('verificacion'))
            {
                if ($request->hasFile('poliza')) {
                    //obtiene el nombre del archivo y su extension
                    $PolizaNE = $request->file('poliza')->getClientOriginalName();
                    //Obtiene el nombre del archivo
                    $PolizaNombre = pathinfo($PolizaNE, PATHINFO_FILENAME);
                    //obtiene la extension
                    $PolizaExt = $request->file('poliza')->getClientOriginalExtension();
                    //nombre que se guarad en BD
                    $PolizaStore = $PolizaNombre.'_'.uniqid().'.'.$PolizaExt;
                    //Subida del archivo al servidor ftp
                    Storage::disk('local')->put('Archivos/'.$PolizaStore, fopen($request->file('poliza'), 'r+'));
                }
                if ($request->hasFile('tarjeta_circulacion')) {
                    //obtiene el nombre del archivo y su extension
                    $TarjetaNE = $request->file('tarjeta_circulacion')->getClientOriginalName();
                    //Obtiene el nombre del archivo
                    $TarjetaNombre = pathinfo($TarjetaNE, PATHINFO_FILENAME);
                    //obtiene la extension
                    $TarjetaExt = $request->file('tarjeta_circulacion')->getClientOriginalExtension();
                    //nombre que se guarad en BD
                    $TarjetaStore = $TarjetaNombre.'_'.uniqid().'.'.$TarjetaExt;
                    //Subida del archivo al servidor ftp
                    Storage::disk('local')->put('Archivos/'.$TarjetaStore, fopen($request->file('tarjeta_circulacion'), 'r+'));
                }
                if ($request->hasFile('verificacion')) {
                    //obtiene el nombre del archivo y su extension
                    $VerificacionNE = $request->file('verificacion')->getClientOriginalName();
                    //Obtiene el nombre del archivo
                    $VerificacionNombre = pathinfo($VerificacionNE, PATHINFO_FILENAME);
                    //obtiene la extension
                    $VerificacionExt = $request->file('verificacion')->getClientOriginalExtension();
                    //nombre que se guarad en BD
                    $VerificacionStore = $VerificacionNombre.'_'.uniqid().'.'.$VerificacionExt;
                    //Subida del archivo al servidor ftp
                    Storage::disk('local')->put('Archivos/'.$VerificacionStore, fopen($request->file('verificacion'), 'r+'));
                }

                $unidad = new Unidades();
                $unidad->unidad = $request->unidad;
                $unidad->modelo = $request->modelo;
                $unidad->placas = $request->placas;
                $unidad->ultimo_servicio = $request->ultimo_servicio;
                $unidad->poliza = $PolizaStore;
                $unidad->tarjeta_circulacion = $TarjetaStore;
                $unidad->verificacion = $VerificacionStore;
                $unidad->historial_servicio = '';
                Utilidades::auditar($unidad, $unidad->id);
                $unidad->save();
            }

            return response()->json(array(
                'status' => true,
            ));
            /*FIN NUEVO REGISTRO*/
        }










        if($request->metodo == "Actualizar"){
            /*ACTUALIZAR REGISTRO*/
            //Variables de archivo
            $PolizaStore='';
            $TarjetaStore='';
            $VerificacionStore='';
            //*Variables para actualizar nuevos archivos y eliminar existentes
            $ValorPoliza = '';
            $ValorTarjeta = '';
            $ValorVerificacion = '';
            $unidades = Unidades::where('id',$request->id)->get();

            foreach ($unidades as $key => $item) {
                $ValorPoliza = $item->poliza;
                $ValorTarjeta = $item->tarjeta_circulacion;
                $ValorVerificacion = $item->verificacion;

                $PolizaStore=$item->poliza;
                $TarjetaStore=$item->tarjeta_circulacion;
                $VerificacionStore=$item->verificacion;
            }
            //*FIN

            if (!$request->ajax()) return redirect('/');

            //--Si el request no contiene archivos, solo se actualizan los campos listados--//
            if(!$request->hasFile('poliza') && !$request->hasFile('tarjeta_circulacion') && !$request->hasFile('verificacion'))
            {

                $unidad = Unidades::findOrFail($request->id);
                $unidad->unidad = $request->unidad;
                $unidad->modelo = $request->modelo;
                $unidad->placas = $request->placas;
                $unidad->ultimo_servicio = $request->ultimo_servicio;
                $unidad->save();
            }

            //--valida que campos del request contienen archivos y realiza el guardado--//
            if($request->hasFile('poliza') || $request->hasFile('tarjeta_circulacion') || $request->hasFile('verificacion'))
            {
                if ($request->hasFile('poliza')) {
                    //obtiene el nombre del archivo y su extension
                    $PolizaNE = $request->file('poliza')->getClientOriginalName();
                    //Obtiene el nombre del archivo
                    $PolizaNombre = pathinfo($PolizaNE, PATHINFO_FILENAME);
                    //obtiene la extension
                    $PolizaExt = $request->file('poliza')->getClientOriginalExtension();
                    //nombre que se guarad en BD
                    $PolizaStore = $PolizaNombre.'_'.uniqid().'.'.$PolizaExt;
                    //Subida del archivo al servidor ftp
                    Storage::disk('local')->put('Archivos/'.$PolizaStore, fopen($request->file('poliza'), 'r+'));
                    if ($ValorPoliza != '') {
                        //Elimina el archivo en el servidor si requiere ser actualizado
                        Utilidades::ftpSolucionEliminar($ValorPoliza);
                    }
                }
                if ($request->hasFile('tarjeta_circulacion')) {
                    //obtiene el nombre del archivo y su extension
                    $TarjetaNE = $request->file('tarjeta_circulacion')->getClientOriginalName();
                    //Obtiene el nombre del archivo
                    $TarjetaNombre = pathinfo($TarjetaNE, PATHINFO_FILENAME);
                    //obtiene la extension
                    $TarjetaExt = $request->file('tarjeta_circulacion')->getClientOriginalExtension();
                    //nombre que se guarad en BD
                    $TarjetaStore = $TarjetaNombre.'_'.uniqid().'.'.$TarjetaExt;
                    //Subida del archivo al servidor ftp
                    Storage::disk('local')->put('Archivos/'.$TarjetaStore, fopen($request->file('tarjeta_circulacion'), 'r+'));
                    if ($ValorTarjeta != '') {
                        //Elimina el archivo en el servidor si requiere ser actualizado
                        Utilidades::ftpSolucionEliminar($ValorTarjeta);
                    }
                }
                if ($request->hasFile('verificacion')) {
                    //obtiene el nombre del archivo y su extension
                    $VerificacionNE = $request->file('verificacion')->getClientOriginalName();
                    //Obtiene el nombre del archivo
                    $VerificacionNombre = pathinfo($VerificacionNE, PATHINFO_FILENAME);
                    //obtiene la extension
                    $VerificacionExt = $request->file('verificacion')->getClientOriginalExtension();
                    //nombre que se guarad en BD
                    $VerificacionStore = $VerificacionNombre.'_'.uniqid().'.'.$VerificacionExt;
                    //Subida del archivo al servidor ftp
                    Storage::disk('local')->put('Archivos/'.$VerificacionStore, fopen($request->file('verificacion'), 'r+'));
                    if ($ValorVerificacion != '') {
                        //Elimina el archivo en el servidor si requiere ser actualizado
                        Utilidades::ftpSolucionEliminar($ValorVerificacion);
                    }
                }

                $unidad = Unidades::findOrFail($request->id);
                $unidad->unidad = $request->unidad;
                $unidad->modelo = $request->modelo;
                $unidad->placas = $request->placas;
                $unidad->ultimo_servicio = $request->ultimo_servicio;
                $unidad->poliza = $PolizaStore;
                $unidad->tarjeta_circulacion = $TarjetaStore;
                $unidad->verificacion = $VerificacionStore;
                $unidad->historial_servicio = '';
                $unidad->save();
            }

            return response()->json(array(
                'status' => true
            ));
            /*FIN ACTUALIZAR REGISTRO*/
        }
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
        // Se obtiene el archivo del FTP serve

        $archivo = Utilidades::ftpSolucion($id);
        // Se coloca el archivo en una ruta local
        Storage::disk('descarga')->put($id, $archivo);
        //--Devuelve la respuesta y descarga el archivo--//
        return response()->download(storage_path().'/app/descargas/'.$id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	//elimina de la ruta local el archivo descargado
        Storage::disk('descarga')->delete($id);
        Storage::disk('local')->delete($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
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
