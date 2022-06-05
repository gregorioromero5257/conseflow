<?php

namespace App\Http\Controllers;

use App\DetalleCapacitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use File;
use Illuminate\Support\Facades\Storage;
use \App\Http\Helpers\Utilidades;


class DetalleCapacitacionController extends Controller
{

    public function index ($id){

        $detalles = DB::table('capacitacion_empleado')
            ->select('capacitacion_empleado.*',
             DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) AS nombre"))
           ->leftjoin('empleados', 'empleados.id', '=', 'capacitacion_empleado.empleado_id')
            ->where('capacitacion_empleado.id_capacitacion','=',$id)
            ->get();

        return response()->json($detalles);

    }

    public function store (Request $request){

        try{
        if(!$request->ajax()) return redirect ('/');

        $detallec = new DetalleCapacitacion();
        $detallec->id_capacitacion = $request->id_capacitacion;
        $detallec->empleado_id = $request->empleado_id;
        $detallec->documento = $request->formato;
        Utilidades::auditar($detallec, $detallec->id);
        $detallec->save();

        return response()->json(array('status'=>true));
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function update (Request $request, $id){
        try{

        if(!$request->ajax()) return redirect('/');

        $detalleactualizacion = DetalleCapacitacion::where('id',$id)->first();
        $detalleactualizacion->id_capacitacion = $request->id_capacitacion;
        $detalleactualizacion->empleado_id = $request->empleado_id;
        $detalleactualizacion->documento = $request->formato;
        Utilidades::auditar($detalleactualizacion, $detalleactualizacion->id);
        $detalleactualizacion->save();

        return response()->json(array('status'=>true));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function eliminar (Request $request){
        try{

        if (!$request->ajax()) return redirect('/');

        $deletedetalle = DetalleCapacitacion::findOrFail($request->id);
        Utilidades::auditar($deletedetalle, $deletedetalle->id);
        $deletedetalle->delete();
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function subdocumentos (Request $request){
        try{
        if ($request->metodo == 1) {
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

            $detallecap = DetalleCapacitacion::findOrFail($request->id);
            $detallecap->adjunto_pdf = $DocumentoStore;
            Utilidades::auditar($detallecap, $detallecap->id);
            $detallecap->save();

            return response()->json(array(
                'status' => true,
            ));
        }

        if ($request->metodo == 2) {
            $DocumentoStore=null;
            //*Variables para actualizar nuevos archivos y eliminar existentes
            $ValorDocumento = null;
            $detallescap = DetalleCapacitacion::where('id',$request->id)->get();

            foreach ($detallescap as $key => $item) {
                $ValorDocumento = $item->adjunto_pdf;

                $DocumentoStore=$item->adjunto_pdf;
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
            //Subida del archivo al servidor
            Storage::disk('local')->put('Archivos/'.$DocumentoStore, fopen($request->file('documento_po'), 'r+'));
                if ($ValorDocumento != null) {
                    //Elimina el archivo en el servidor si requiere ser actualizado
                    Utilidades::ftpSolucionEliminar($ValorDocumento);
                }
            $proyecto = DetalleCapacitacion::findOrFail($request->id);
            $proyecto->adjunto_pdf = $DocumentoStore;
             Utilidades::auditar($proyecto, $proyecto->id);
            $proyecto->save();

            return response()->json(array(
                'status' => true,
            ));
        }
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function show($id)
    {
        // Se obtiene el archivo del FTP serve
        $archivo = Utilidades::ftpSolucion($id);
        // Se coloca el archivo en una ruta local
        Storage::disk('descarga')->put($id, $archivo);
        //--Devuelve la respuesta y descarga el archivo--//
        return response()->download(storage_path().'/app/descargas/'.$id);
    }

    public function edit($id)
    {
        //elimina de la ruta local el archivo descargado
        Storage::disk('descarga')->delete($id);
          Storage::disk('local')->delete($id);
    }

}
