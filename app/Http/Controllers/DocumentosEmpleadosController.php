<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\Storage;


class DocumentosEmpleadosController extends Controller
{
    //
    public function get(Request $request)
    {
      $empleados = DB::table('empleados')
          ->select('empleados.*')
          ->where('empleados.condicion','1')
          ->orderBy('empleados.id', 'DESC')
          ->paginate(15);

      return response()->json([
          'empleados' => $empleados,
      ]);
    }

    public function buscar(Request $request)
    {
      $empleados = DB::table('empleados')
          ->select('empleados.*')
          ->where('empleados.condicion','1')
          ->where('empleados.nombre','LIKE','%'.$request->nombre.'%')
          ->orderBy('empleados.id', 'DESC')
          ->paginate(15);

      return response()->json([
          'empleados' => $empleados,
      ]);
    }

    public function guardarCI(Request $request)
    {
      try {
        $empleados = \App\Empleado::where('id',$request->id)->first();
        $empleados->clave_ine = $request->claveine;
        $empleados->save();

        return response()->json(['status' => true]);

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function guardarNC(Request $request)
    {
      try {
        $empleados = \App\Empleado::where('id',$request->id)->first();
        $empleados->numero_credencial = $request->numero_credencial;
        $empleados->save();

        return response()->json(['status' => true]);

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function guardarRFC(Request $request)
    {
      try {
        $empleados = \App\Empleado::where('id',$request->id)->first();
        $empleados->rfc = $request->rfc;
        $empleados->save();

        return response()->json(['status' => true]);

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function guardarCURP(Request $request)
    {
      try {
        $empleados = \App\Empleado::where('id',$request->id)->first();
        $empleados->curp = $request->curp;
        $empleados->save();

        return response()->json(['status' => true]);

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function guardarNSS(Request $request)
    {
      try {
        $empleados = \App\Empleado::where('id',$request->id)->first();
        $empleados->nss_imms = $request->nss;
        $empleados->save();

        return response()->json(['status' => true]);

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function guardarDocumentos(Request $request)
    {
      try {
          $PdfStore = '';
        if ($request->hasFile('archivo') ) {
          //obtiene el nombre del archivo y su extension
          $FacturaNE = $request->file('archivo')->getClientOriginalName();
          //Obtiene el nombre del archivo
          $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
          //obtiene la extension
          $FacturaExt = $request->file('archivo')->getClientOriginalExtension();
          //nombre que se guarad en BD
          $PdfStore = 'File'.uniqid().'.'.$FacturaExt;
          //Subida del archivo al servidor ftp
          Storage::disk('local')->put('Empleados/'.$PdfStore, fopen($request->file('archivo'), 'r+'));
        }

        $documentos_empleados = new \App\DocumentoEmpleado();
        $documentos_empleados->empleado_id = $request->empleado;
        $documentos_empleados->tipo = $request->tipo;
        $documentos_empleados->archivo = $PdfStore;
        $documentos_empleados->save();

        return response()->json(['status' => true]);

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function getDocumentos($id)
    {

      $documentos_empleados = \App\DocumentoEmpleado::where('empleado_id',$id)->get();

      return response()->json($documentos_empleados);
    }

    public function descargarfoto($id)
    {
      try {

        $nombre_archivo = $id;
        // Se obtiene el archivo del FTP serve
        $archivo = $this->ftpSolucion($nombre_archivo);
        // Se coloca el archivo en una ruta local
        Storage::disk('cotizaciones')->put($nombre_archivo, $archivo);


            return response()->json(array(
              'status' => true
            ));

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function descargar($id)
    {
      $archivo = $this->ftpSolucion($id);
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

    public function ftpSolucion($id)
    {
      // return Storagse::response("Empleados/$id");
      // Se obtiene el archivo del local serve
      //Se busca en disk o carpeta -----
       if (Storage::exists('Empleados/'.$id)){
         // Se coloca el archivo en una ruta local
         $archivo = Storage::disk('local')->get('Empleados/'.$id);
       }else {
       $output = shell_exec("mkdir /var/www/html/conserflow2020/conserflow-sistema2018/storage/app/Empleados/ 2> errormk.txt;cp /home/vsftpuser/ftp/Empleados/$id /var/www/html/conserflow2020/conserflow-sistema2018/storage/app/Empleados/ 2> error.txt;");
         $archivo = Storage::disk('local')->get('Empleados/'.$id);
       }
       // shell_exec("chmod 777 /var/www/html/conserflow2020/public/FilesFTP/".$id);
       return $archivo;
    }

    public function eliminar($id)
    {
      try {
        Storage::disk('local')->delete('Empleados/'.$id);
        $documentos_empleados = \App\DocumentoEmpleado::where('archivo',$id)->delete();

        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }
}
