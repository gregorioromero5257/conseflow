<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conductores;
use App\ConductoresImg;
use Validator;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Support\Facades\Storage;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\DB;

class ConductoresController extends Controller
{
     protected $rules = array(
        'empleado_id' => 'required|max:45',
    );
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $conductores = Conductores::
              join('empleados AS e', 'e.id', '=', 'choferes.nombre')
            ->select('choferes.*', DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS nombre_empleado"))
            ->orderBy('choferes.id', 'ASC')
            ->get();
            $arreglo = [];
            foreach ($conductores as $key => $value) {
              $conductor_img = ConductoresImg::where('conductor_id',$value->id)->orderBy('id','DESC')->first();

              $arreglo [] = [
                'conductores' => $value,
                'imagen' => $conductor_img,
              ];
            }

        return response()->json($arreglo);
    }

    public function getImagenes($id)
    {
      try {
        $img = DB::table('conductores_img AS ervi')->where('ervi.conductor_id',$id)
        ->where('ervi.condicion','1')->get();
        $arreglo = [];
        foreach ($img as $key => $value) {

          $img_u = Storage::disk('local')->get('Trafico/'.$value->nombre);
          $type = explode('.',$value->nombre);
          $base64 = 'data:image/' . $type[1] . ';base64,' .base64_encode($img_u);

          $arreglo [] = [
            'id' => $value->id,
            'img' => $base64,
          ];
        }
        return response()->json($arreglo);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function deleteImagenes($id)
    {
      try {
        $img = ConductoresImg::where('id',$id)->first();
        $img->condicion = 0;
        Utilidades::auditar($img, $img->id);
        $img->save();

        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function gua($image)
    {
      try {
        // return response()->json($request);

        $image_64 = $image; //your base64 encoded data

        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf

        $replace = substr($image_64, 0, strpos($image_64, ',')+1);

        // find substring fro replace here eg: data:image/png;base64,

        $image = str_replace($replace, '', $image_64);

        $image = str_replace(' ', '+', $image);

        $imageName = uniqid().'.'.$extension;


        Storage::disk('local')->put('Trafico/'.$imageName, base64_decode($image));

        // return response()->json(['status' => true]);
        return $imageName;

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
      try {

        if ($request->metodo == 1) {
          $new = new Conductores();
          $new->nombre = $request->nombre;
          $new->licencia = $request->licencia;
          $new->tipo = $request->tipo;
          $new->vigencia = $request->vigencia == 'null' ? NULL : $request->vigencia;
          $new->save();

          if ($request->hasFile('poliza')) {
            $nombre_archivo = $this->obtenerNombre($request,'poliza');
            $this->subirAdjunto($request, $nombre_archivo,'poliza');

              $new_img = new ConductoresImg();
              $new_img->conductor_id = $new->id;
              $new_img->nombre = $nombre_archivo;
              $new_img->save();
          }
        }elseif ($request->metodo == 2) {
          $new = Conductores::where('id',$request->id)->first();
          $new->nombre = $request->nombre;
          $new->licencia = $request->licencia;
          $new->tipo = $request->tipo;
          $new->vigencia = $request->vigencia == 'null' ? NULL : $request->vigencia;
          $new->save();

          if ($request->hasFile('poliza')) {
            $nombre_archivo = $this->obtenerNombre($request,'poliza');
            $this->subirAdjunto($request, $nombre_archivo,'poliza');

              $new_img = new ConductoresImg();
              $new_img->conductor_id = $new->id;
              $new_img->nombre = $nombre_archivo;
              $new_img->save();
          }
        }

        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function obtenerNombre($request, $name)
    {
      //obtiene el nombre del archivo y su extension
      $FacturaNE = $request->file($name)->getClientOriginalName();
      //Obtiene el nombre del archivo
      $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
      //obtiene la extension
      $FacturaExt = $request->file($name)->getClientOriginalExtension();
      //nombre que se guarad en BD
      $FacturaStore = 'L_'.uniqid().'.'.$FacturaExt;

      return $FacturaStore;
    }

    public function subirAdjunto($request, $nombre_archivo,$name)
    {
      try {
        Storage::disk('local')->put('Trafico/'.$nombre_archivo, fopen($request->file($name), 'r+'));
        return true;
      } catch (\Exception $e) {
        Utilidades::errors($e);
      }
    }


    public function actualizar(Request $request)
    {
      try {


        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
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
            // $Licencia_aStore='';
            // $Licencia_bStore='';

            if (!$request->ajax()) return redirect('/');

            //--Si el request no contiene archivos, solo se actualizan los campos listados--//
            // if(!$request->hasFile('licencia_a') && !$request->hasFile('licencia_b'))
            // {

                $conductor = new Conductores();
                $conductor->nombre = $request->nombre;
                $conductor->licencia = $request->licencia;
                $conductor->tipo = $request->licencia;
                $conductor->vigencia = $request->vigencia;
                $conductor->save();
            // }
            //
            // //--valida que campos del request contienen archivos y realiza el guardado--//
            // if($request->hasFile('licencia_a') || $request->hasFile('licencia_b'))
            // {
            //     if ($request->hasFile('licencia_a')) {
            //         //obtiene el nombre del archivo y su extension
            //         $Licencia_aNE = $request->file('licencia_a')->getClientOriginalName();
            //         //Obtiene el nombre del archivo
            //         $Licenca_aNombre = pathinfo($Licencia_aNE, PATHINFO_FILENAME);
            //         //obtiene la extension
            //         $Licencia_aExt = $request->file('licencia_a')->getClientOriginalExtension();
            //         //nombre que se guarad en BD
            //         $Licencia_aStore = $Licenca_aNombre.'_'.uniqid().'.'.$Licencia_aExt;
            //         //Subida del archivo al servidor ftp
            //         Storage::disk('local')->put('Archivos/'.$Licencia_aStore, fopen($request->file('licencia_a'), 'r+'));
            //     }
            //     if ($request->hasFile('licencia_b')) {
            //         //obtiene el nombre del archivo y su extension
            //         $Licencia_bNE = $request->file('licencia_b')->getClientOriginalName();
            //         //Obtiene el nombre del archivo
            //         $Licenca_bNombre = pathinfo($Licencia_bNE, PATHINFO_FILENAME);
            //         //obtiene la extension
            //         $Licencia_bExt = $request->file('licencia_b')->getClientOriginalExtension();
            //         //nombre que se guarad en BD
            //         $Licencia_bStore = $Licenca_bNombre.'_'.uniqid().'.'.$Licencia_bExt;
            //         //Subida del archivo al servidor ftp
            //         Storage::disk('local')->put('Archivos/'.$Licencia_bStore, fopen($request->file('licencia_b'), 'r+'));
            //     }
            //
            //     $conductor = new Conductores();
            //     $conductor->empleado_id = $request->empleado_id;
            //     $conductor->licencia_a = $Licencia_aStore;
            //     $conductor->licencia_b = $Licencia_bStore;
            //     $conductor->save();
            // }

            return response()->json(array(
                'status' => true,
            ));
            /*FIN NUEVO REGISTRO*/
        }









        if($request->metodo == "Actualizar"){
            /*ACTUALIZAR REGISTRO*/
            //Variables de archivo
            // $Licencia_aStore='';
            // $Licencia_bStore='';
            // //*Variables para actualizar nuevos archivos y eliminar existentes
            // $ValorLicencia_a = '';
            // $ValorLicencia_b = '';
            // $conductores = Conductores::where('id',$request->id)->get();
            //
            // foreach ($conductores as $key => $item) {
            //     $ValorLicencia_a = $item->licencia_a;
            //     $ValorLicencia_b = $item->licencia_b;
            //
            //     $Licencia_aStore=$item->licencia_a;
            //     $Licencia_bStore=$item->licencia_b;
            // }
            //*FIN

            // if (!$request->ajax()) return redirect('/');

            //--Si el request no contiene archivos, solo se actualizan los campos listados--//
            // if(!$request->hasFile('licencia_a') && !$request->hasFile('licencia_b'))
            // {

                $conductor = Conductores::where('id',$request->id)->first();
                $conductor->nombre = $request->nombre;
                $conductor->licencia = $request->licencia;
                $conductor->tipo = $request->tipo;
                $conductor->vigencia = $request->vigencia;
                $conductor->update();
            // }

            // //--valida que campos del request contienen archivos y realiza el guardado--//
            // if($request->hasFile('licencia_a') || $request->hasFile('licencia_b'))
            // {
            //     if ($request->hasFile('licencia_a')) {
            //         //obtiene el nombre del archivo y su extension
            //         $Licencia_aNE = $request->file('licencia_a')->getClientOriginalName();
            //         //Obtiene el nombre del archivo
            //         $Licenca_aNombre = pathinfo($Licencia_aNE, PATHINFO_FILENAME);
            //         //obtiene la extension
            //         $Licencia_aExt = $request->file('licencia_a')->getClientOriginalExtension();
            //         //nombre que se guarad en BD
            //         $Licencia_aStore = $Licenca_aNombre.'_'.uniqid().'.'.$Licencia_aExt;
            //         //Subida del archivo al servidor ftp
            //         Storage::disk('local')->put('Archivos/'.$Licencia_aStore, fopen($request->file('licencia_a'), 'r+'));
            //         if ($ValorLicencia_a != '') {
            //             //Elimina el archivo en el servidor si requiere ser actualizado
            //             Utilidades::ftpSolucionEliminar($ValorLicencia_a);
            //         }
            //     }
            //     if ($request->hasFile('licencia_b')) {
            //         //obtiene el nombre del archivo y su extension
            //         $Licencia_bNE = $request->file('licencia_b')->getClientOriginalName();
            //         //Obtiene el nombre del archivo
            //         $Licenca_bNombre = pathinfo($Licencia_bNE, PATHINFO_FILENAME);
            //         //obtiene la extension
            //         $Licencia_bExt = $request->file('licencia_b')->getClientOriginalExtension();
            //         //nombre que se guarad en BD
            //         $Licencia_bStore = $Licenca_bNombre.'_'.uniqid().'.'.$Licencia_bExt;
            //         //Subida del archivo al servidor ftp
            //         Storage::disk('local')->put('Archivos/'.$Licencia_bStore, fopen($request->file('licencia_b'), 'r+'));
            //         if ($ValorLicencia_b != '') {
            //             //Elimina el archivo en el servidor si requiere ser actualizado
            //             Utilidades::ftpSolucionEliminar($ValorLicencia_b);
            //         }
            //     }
            //
            //     $conductor = Conductores::findOrFail($request->id);
            //     $conductor->empleado_id = $request->empleado_id;
            //     $conductor->licencia_a = $Licencia_aStore;
            //     $conductor->licencia_b = $Licencia_bStore;
            //     $conductor->save();
            // }

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

    /**
    * Se elimina el archivo resguardado en la carpeta de descargas
    * se utiliza como un temporal
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function editar($id)
    {
      //elimina de la ruta local el archivo descargado
      Storage::disk('descarga')->delete($id);
      Storage::disk('local')->delete($id);
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

    public static function ftpSolucion($id)
    {
      // Se obtiene el archivo del local server
      //Se busca en disk o carpeta -----
      if (Storage::exists('Trafico/' . $id))
      {
        // Se coloca el archivo en una ruta local
        $archivo = Storage::disk('local')->get('Trafico/' . $id);
      }
      else
      {
        $output = shell_exec("mkdir /var/www/html/conserflow2020/conserflow-sistema2018/storage/app/Trafico/ 2> errormk.txt;cp /home/vsftpuser/ftp/Archivos/$id /var/www/html/conserflow2020/conserflow-sistema2018/storage/app/Trafico/ 2> error.txt;");
        $archivo = Storage::disk('local')->get('Trafico/' . $id);
      }
      return $archivo;
    }
}
