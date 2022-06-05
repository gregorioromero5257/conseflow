<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Subirfacturas;
use Validator;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Support\Facades\Storage;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\DB;


class SubirfacturasController extends Controller
{

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
     $conductores = Subirfacturas::orderBy('subir_factura.id', 'ASC')
         ->Join('proyectos as p','p.id','=','subir_factura.proyecto_id')
         ->select('subir_factura.*','p.nombre_corto as proyecto')
         ->orderBy('subir_factura.id', 'ASC')
         ->get()->toArray();
     return response()->json($conductores);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
     $conductores = Subirfacturas::orderBy('id', 'ASC')->get();

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
         if(!$request->hasFile('nombre_archivo') )
         {

             $conductor = new Subirfacturas();
             $conductor->nombre_archivo = '';
             $conductor->uuid = $request->uuid;
             $conductor->ordenes_compras_id = $request->oc;
             $conductor->proyecto_id = $request->proyecto_id;
             $conductor->save();
         }

         //--valida que campos del request contienen archivos y realiza el guardado--//
         if($request->hasFile('nombre_archivo'))
         {
             if ($request->hasFile('nombre_archivo')) {
                 //obtiene el nombre del archivo y su extension
                 $Licencia_aNE = $request->file('nombre_archivo')->getClientOriginalName();
                 //Obtiene el nombre del archivo
                 $Licenca_aNombre = pathinfo($Licencia_aNE, PATHINFO_FILENAME);
                 //obtiene la extension
                 $Licencia_aExt = $request->file('nombre_archivo')->getClientOriginalExtension();
                 //nombre que se guarad en BD

                 $Licencia_aStore = 'FacturaUUID_'.uniqid().'.'.$Licencia_aExt;
                 //Subida del archivo al servidor ftp
                 Storage::disk('local')->put('Archivos/'.$Licencia_aStore, fopen($request->file('nombre_archivo'), 'r+'));
             }


             $conductor = new Subirfacturas();
             $conductor->nombre_archivo = $Licencia_aStore;
             $conductor->uuid = $request->uuid;
             $conductor->ordenes_compras_id = $request->oc;
             $conductor->proyecto_id = $request->proyecto_id;
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

         $conductores = Subirfacturas::where('id',$request->id)->get();

         foreach ($conductores as $key => $item) {

             $ValorLicencia_a = $item->nombre_archivo;
         }
         //*FIN

         if (!$request->ajax()) return redirect('/');

         //--Si el request no contiene archivos, solo se actualizan los campos listados--//
         if(!$request->hasFile('nombre_archivo'))
         {

             $conductor = Subirfacturas::findOrFail($request->id);
             $conductor->nombre_archivo = '';
             $conductor->uuid = $request->uuid;
             $conductor->ordenes_compras_id = $request->oc;
             $conductor->proyecto_id = $request->proyecto_id;
             $conductor->save();
         }

         //--valida que campos del request contienen archivos y realiza el guardado--//
         if($request->hasFile('nombre_archivo'))
         {
             if ($request->hasFile('nombre_archivo')) {
                 //obtiene el nombre del archivo y su extension
                 $Licencia_aNE = $request->file('nombre_archivo')->getClientOriginalName();
                 //Obtiene el nombre del archivo
                 $Licenca_aNombre = pathinfo($Licencia_aNE, PATHINFO_FILENAME);
                 //obtiene la extension
                 $Licencia_aExt = $request->file('nombre_archivo')->getClientOriginalExtension();
                 //nombre que se guarad en BD
                 $Licencia_aStore = 'FacturaUUID_'.uniqid().'.'.$Licencia_aExt;
                 //Subida del archivo al servidor ftp
                 Storage::disk('local')->put('Archivos/'.$Licencia_aStore, fopen($request->file('nombre_archivo'), 'r+'));
                 if ($ValorLicencia_a != '') {
                     //Elimina el archivo en el servidor si requiere ser actualizado
                     Utilidades::ftpSolucionEliminar($ValorLicencia_a);
                 }
             }

                          $conductor = new Subirfacturas();
                          $conductor->nombre_archivo = $Licencia_aStore;
                          $conductor->uuid = $request->uuid;
                          $conductor->ordenes_compras_id = $request->oc;
                          $conductor->proyecto_id = $request->proyecto_id;
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

 public function ordenporProyecto ($id){

   $ordenP = DB::table('ordenes_compras')
   ->select('ordenes_compras.folio')
   ->where('ordenes_compras.proyecto_id','=',$id)
   ->get();
   return response()->json($ordenP);
 }


}
