<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VerificacionUnidades;
use Validator;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Support\Facades\Storage;
use \App\Http\Helpers\Utilidades;

class VerificacionUnidadesController extends Controller
{
    //
    public function index()
    {
      $poliza = VerificacionUnidades::get()->toArray();
      return response()->json($poliza);
    }

    public function store(Request $request)
    {
      if($request->metodo == 1){
          /*NUEVO REGISTRO*/
          //Variables de archivo
          $FacturaStore='';
          if (!$request->ajax()) return redirect('/');
          //--Si el request no contiene archivos, se guardan los campos listados--//
          if(!$request->hasFile('comprobante')) {
              $poliza = new VerificacionUnidades();
              $poliza->fecha = $request->fecha;
              $poliza->comprobante = '';
              $poliza->costo = $request->costo;
              $poliza->costo_multa = $request->costo_multa;
              $poliza->periodo = $request->periodo;
              $poliza->unidad_id = $request->unidad_id;
              Utilidades::auditar($poliza, $poliza->id);
              $poliza->save();
          }
          //--valida que campos del request contienen archivos y realiza el guardado--//
          if($request->hasFile('comprobante'))
          {
              if ($request->hasFile('comprobante')) {
                  //obtiene el nombre del archivo y su extension
                  $FacturaNE = $request->file('comprobante')->getClientOriginalName();
                  //Obtiene el nombre del archivo
                  $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
                  //obtiene la extension
                  $FacturaExt = $request->file('comprobante')->getClientOriginalExtension();
                  //nombre que se guarad en BD
                  $FacturaStore = 'Vfca_'.uniqid().'.'.$FacturaExt;
                  //Subida del archivo al servidor ftp
                  Storage::disk('local')->put('Archivos/'.$FacturaStore, fopen($request->file('comprobante'), 'r+'));
              }
              $poliza = new VerificacionUnidades();
              $poliza->fecha = $request->fecha;

              $poliza->comprobante = $FacturaStore;
              $poliza->costo = $request->costo;
              $poliza->costo_multa = $request->costo_multa;
              $poliza->periodo = $request->periodo;
              $poliza->unidad_id = $request->unidad_id;
              Utilidades::auditar($poliza, $poliza->id);
              $poliza->save();
          }
          return response()->json(array(
              'status' => true,
          ));
          /*FIN NUEVO REGISTRO*/
      }
      if($request->metodo == 0){
          /*ACTUALIZAR REGISTRO*/
          //Variables de archivo
          $FacturaStore='';
          //*Variables para actualizar nuevos archivos y eliminar existentes
          $ValorPoliza = '';
          $polizaes = VerificacionUnidades::where('id',$request->id)->get();
          foreach ($polizaes as $key => $item) {
              $ValorPoliza = $item->comprobante;
              $FacturaStore=$item->comprobante;
          }
          //*FIN
          if (!$request->ajax()) return redirect('/');
          //--Si el request no contiene archivos, solo se actualizan los campos listados--//
          if(!$request->hasFile('factura'))
          {
              $poliza = VerificacionUnidades::findOrFail($request->id);
              $poliza->fecha = $request->fecha;
              $poliza->costo = $request->costo;
              $poliza->costo_multa = $request->costo_multa;
              $poliza->periodo = $request->periodo;
              $poliza->unidad_id = $request->unidad_id;
              Utilidades::auditar($poliza, $poliza->id);
              $poliza->save();
          }
          //--valida que campos del request contienen archivos y realiza el guardado--//
          if($request->hasFile('comprobante'))
          {
              if ($request->hasFile('comprobante')) {
                  //obtiene el nombre del archivo y su extension
                  $FacturaNE = $request->file('comprobante')->getClientOriginalName();
                  //Obtiene el nombre del archivo
                  $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
                  //obtiene la extension
                  $FacturaExt = $request->file('comprobante')->getClientOriginalExtension();
                  //nombre que se guarad en BD
                  $FacturaStore = 'Vfca_'.uniqid().'.'.$FacturaExt;
                  //Subida del archivo al servidor ftp
                  Storage::disk('local')->put('Archivos/'.$FacturaStore, fopen($request->file('comprobante'), 'r+'));
                  if ($ValorPoliza != '') {
                      //Elimina el archivo en el servidor si requiere ser actualizado
                      Utilidades::ftpSolucionEliminar($ValorPoliza);
                  }
              }
              $poliza = VerificacionUnidades::findOrFail($request->id);
              $poliza->fecha = $request->fecha;
              $poliza->comprobante = $FacturaStore;
              $poliza->costo = $request->costo;
              $poliza->costo_multa = $request->costo_multa;
              $poliza->periodo = $request->periodo;
              $poliza->unidad_id = $request->unidad_id;
              Utilidades::auditar($poliza, $poliza->id);
              $poliza->save();
          }

          return response()->json(array(
              'status' => true
          ));
          /*FIN ACTUALIZAR REGISTRO*/
      }
    }

    public function show($id)
    {
      $poliza = VerificacionUnidades::where('unidad_id',$id)->get();
      return response()->json($poliza);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function descarga($id)
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
    public function editar($id)
    {
      //elimina de la ruta local el archivo descargado
        Storage::disk('descarga')->delete($id);
        Storage::disk('local')->delete($id);

    }
}
