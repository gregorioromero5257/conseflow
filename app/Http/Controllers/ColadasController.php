<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Coladas;
use Validator;
use File;
use Illuminate\Support\Facades\Storage;
use \App\Http\Helpers\Utilidades;


class ColadasController extends Controller
{
      public function index(Request $request){

      $conductores = Coladas::orderBy('coladas.id', 'ASC')
         ->leftJoin('articulos as a','a.id','=','coladas.articulo_id')
         ->leftJoin('lotes as l','l.id','=','a.id')
         ->leftJoin('entradas as e','e.id','=','l.id')
         ->leftJoin('ordenes_compras as oc','oc.id','=','e.orden_compra_id')
         ->leftJoin('proveedores','proveedores.id','=','oc.proveedore_id')
          ->select('coladas.*','l.nombre as nombre','e.fecha as fecha','oc.folio as folio','proveedores.razon_social as nombre_proveedor')
          ->orderBy('coladas.id', 'ASC')
          ->get()->toArray();
      return response()->json($conductores);
    }

    public function descripcion(Request $request){

      $descripcionId = DB::table('articulos')
      ->select('articulos.*')
      ->get();

      return response()->json($descripcionId);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $conductores = Coladas::orderBy('id', 'ASC')->get();

        return response()->json($conductores);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->metodo == "Nuevo"){
            /*NUEVO REGISTRO*/
            //Variables de archivo


            if (!$request->ajax()) return redirect('/');

            //--Si el request no contiene archivos, solo se actualizan los campos listados--//
            if(!$request->hasFile('certificado') )
            {

                $conductor = new Coladas();
                $conductor->certificado = '';
                $conductor->articulo_id = $request->articulo_id;
                $conductor->numero_colada = $request->numero_colada;
                $conductor->save();
            }

            //--valida que campos del request contienen archivos y realiza el guardado--//
            if($request->hasFile('certificado'))
            {
                if ($request->hasFile('certificado')) {
                    //obtiene el nombre del archivo y su extension
                    $Licencia_aNE = $request->file('certificado')->getClientOriginalName();
                    //Obtiene el nombre del archivo
                    $Licenca_aNombre = pathinfo($Licencia_aNE, PATHINFO_FILENAME);
                    //obtiene la extension
                    $Licencia_aExt = $request->file('certificado')->getClientOriginalExtension();
                    //nombre que se guarad en BD

                    $Licencia_aStore = 'Certificado_'.uniqid().'.'.$Licencia_aExt;
                    //Subida del archivo al servidor ftp
                    Storage::disk('local')->put('Archivos/'.$Licencia_aStore, fopen($request->file('certificado'), 'r+'));
                }


                $conductor = new Coladas();
                $conductor->certificado = $Licencia_aStore;
                $conductor->articulo_id = $request->articulo_id;
                $conductor->numero_colada = $request->numero_colada;
                $conductor->save();
            }

            return response()->json(array(
                'status' => true,
            ));
            /*FIN NUEVO REGISTRO*/
        }


        if($request->metodo == "Actualizar"){
            /*ACTUALIZAR REGISTRO*/
            //Variables de archivo

            //*Variables para actualizar nuevos archivos y eliminar existentes
            $ValorLicencia_a = '';

            $conductores = Coladas::where('id',$request->id)->get();

            foreach ($conductores as $key => $item) {

                $ValorLicencia_a = $item->certificado;
            }
            //*FIN

            if (!$request->ajax()) return redirect('/');

            //--Si el request no contiene archivos, solo se actualizan los campos listados--//
            if(!$request->hasFile('certificado'))
            {

                $conductor = Coladas::findOrFail($request->id);
                $conductor->certificado = '';
                $conductor->articulo_id = $request->articulo_id;
                $conductor->numero_colada = $request->numero_colada;
                $conductor->save();
            }

            //--valida que campos del request contienen archivos y realiza el guardado--//
            if($request->hasFile('certificado'))
            {
                if ($request->hasFile('certificado')) {
                    //obtiene el nombre del archivo y su extension
                    $Licencia_aNE = $request->file('certificado')->getClientOriginalName();
                    //Obtiene el nombre del archivo
                    $Licenca_aNombre = pathinfo($Licencia_aNE, PATHINFO_FILENAME);
                    //obtiene la extension
                    $Licencia_aExt = $request->file('certificado')->getClientOriginalExtension();
                    //nombre que se guarad en BD
                    $Licencia_aStore = 'Certificado_'.uniqid().'.'.$Licencia_aExt;
                    //Subida del archivo al servidor ftp
                    Storage::disk('local')->put('Archivos/'.$Licencia_aStore, fopen($request->file('certificado'), 'r+'));
                    if ($ValorLicencia_a != '') {
                        //Elimina el archivo en el servidor si requiere ser actualizado
                        Utilidades::ftpSolucionEliminar($ValorLicencia_a);
                    }
                }

                             $conductor = new Coladas();
                             $conductor->certificado = $Licencia_aStore;
                             $conductor->articulo_id = $request->articulo_id;
                             $conductor->numero_colada = $request->numero_colada;
                             $conductor->save();
            }

            return response()->json(array(
                'status' => true
            ));
            /*FIN ACTUALIZAR REGISTRO*/
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

}
