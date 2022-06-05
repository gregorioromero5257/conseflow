<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\Storage;


class VehiculosFotosController extends Controller
{
    //

    public function guardar(Request $request)
    {
      try {
        //obtiene el nombre del archivo y su extension
        $FacturaNE = $request->file('imagen')->getClientOriginalName();
        //Obtiene el nombre del archivo
        $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
        //obtiene la extension
        $FacturaExt = $request->file('imagen')->getClientOriginalExtension();
        //nombre que se guarad en BD
        $PdfStore = 'Imgssrc'.uniqid().'.'.$FacturaExt;
        //Subida del archivo al servidor ftp

        Storage::disk('local')->put('Fotos/'.$PdfStore, fopen($request->file('imagen'), 'r+'));

        $rhoc = new \App\VehiculosFotos();
        $rhoc->image = $PdfStore;
        $rhoc->unidad_id = $request->unidad_id;
        $rhoc->save();

        return response()->json(array(
          'status' => true
        ));

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function getFotos($id)
    {
      try {

        $fotos = \App\VehiculosFotos::where('unidad_id',$id)->get();

        foreach ($fotos as $key => $value) {
          // Se obtiene el archivo del FTP serve
          $archivo = $this->ftpSolucionT($value->image);
          // Se coloca el archivo en una ruta temporal
          Storage::disk('temporal')->put($value->image, $archivo);
        }

        return response()->json($fotos);

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function ftpSolucionT($id)
    {
      // Se obtiene el archivo del local serve
      //Se busca en disk o carpeta -----
      if (Storage::exists('Fotos/'.$id)){
        // Se coloca el archivo en una ruta local
        $archivo = Storage::disk('local')->get('Fotos/'.$id);
      }else {
        $output = shell_exec("mkdir /var/www/html/conserflow2020/conserflow-sistema2018/storage/app/Fotos/ 2> errormk.txt;cp /home/vsftpuser/ftp/Fotos/$id /var/www/html/conserflow2020/conserflow-sistema2018/storage/app/Fotos/ 2> error.txt;");
        $archivo = Storage::disk('local')->get('Fotos/'.$id);
      }
      // shell_exec("chmod 777 /var/www/html/conserflow2020/public/FilesFTP/".$id);
      return $archivo;
    }
}
