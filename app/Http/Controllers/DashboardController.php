<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Response;
use File;

class DashboardController extends Controller
{
    public function __construct()
	{
		$this->middleware('auth');
	}


    public function index()
    {
    	$browser = $_SERVER['HTTP_USER_AGENT'];
        $userlogueado = Auth::user();
        //obtenemos el valor del navegador
        $iduser = auth()->id();
        $navegador = $userlogueado->navegador;
        //Fin

        if ($browser == $navegador) {
            //Modifica la sesion para permitir solo una por usuario
            $usuario = User::findOrFail($iduser);
            $usuario->session_id = '1';
            $usuario->save();
            //Fin
            return view('contenido/contenido');
        }
        else {
            return redirect('Filtro');
        }
    }

    public function privacidad()
    {
        $id ='Privacidad.pdf';
        $file = Storage::disk('archivos')->get($id);
        $response = Response::make($file, 200); 
        $response->header('Content-Type', 'application/pdf'); 
        return $response;
    }
    public function ayuda()
    {
        $id ='Ayuda.pdf';
        $file = Storage::disk('archivos')->get($id);
        $response = Response::make($file, 200); 
        $response->header('Content-Type', 'application/pdf'); 
        return $response;
    }

    public function traerArchivos(Request $request)
    {
        $directorio='';
        $response= [];
        if($request->modulo !=''){$directorio.= $request->modulo.'/'; }
        if($request->menu !=''){$directorio.= $request->menu.'/'; }
        if($request->carpeta !=''){$directorio.= $request->carpeta.'/'; }
        //si necesitas añadir mas carpetas puede poner carpeta1, carpeta2, etc...
        $path = storage_path('app/'. $directorio);
        if(File::exists($path)) {
            $files = File::files($path);
            foreach($files as $key => $value) {
                $ext = strtolower($value->getExtension());
                switch ($ext) {
                    case 'pdf':
                        $response['pdf'][$key]=[
                            "filename"=>$value->getFilename(),
                            "extension"=>$value->getExtension(),
                            "clase"=>'fas fa-file-pdf'] ;
                        break;
                    case 'xls':
                    case 'xlsx':
                    case 'xlsm':
                        $response['excel'][$key]=[
                            "filename"=>$value->getFilename(),
                            "extension"=>$value->getExtension(),
                            "clase"=>'fas fa-file-excel'] ;
                        break;
                    case 'doc':
                    case 'docx':
                    case 'docm':
                        $response['word'][$key]=[
                            "filename"=>$value->getFilename(),
                            "extension"=>$value->getExtension(),
                            "clase"=>'fas fa-file-word'] ;
                        break;                            
                }                
            }
        } else{
            $response = [
                'error' =>'La ruta no existe, favor de verificar ',
                'ruta' => $directorio];
        }       
        return response()->json($response);
    }

    public function descargarArchivos(Request $request)
    {
        switch ($request->tipo) {
            case 'pdf':
                $header = 'application/pdf'; 
                break;
            case 'doc':
            case 'docx':
            case 'docm':
                $header ='application/msword'; 
                break;
            case 'xls':
            case 'xlsx':
            case 'xlsm':
                $header= 'application/vnd.ms-excel';
                break;
        }
        $path = storage_path('app/' .$request->modulo .'/'. $request->menu . '/' . $request->nombre);

        return response()->download($path, $request->nombre, [
            'Content-Type' => $header,
            'Content-Disposition' => 'inline; filename="' . $request->nombre . '"'
        ]);
    }

}
