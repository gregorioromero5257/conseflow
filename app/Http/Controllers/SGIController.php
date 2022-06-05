<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Utilidades;
use Exception;
use Faker\Provider\File;
use Illuminate\Support\Facades\Storage;

class SGIController extends Controller
{
    public $PATH = "archivos_SGI" . DIRECTORY_SEPARATOR;
    /**
     * Obtiene los archivos y directorios de la ruta ingresada
     */
    public function ObtenerArchivos($ruta)
    {
        try
        {
            $path = storage_path("app/" . $this->PATH . $ruta);
            $files = scandir($path);
            unset($files[0]);
            unset($files[1]);
            $list_files = [];
            $archivos = [];
            $carpetas = [];
            foreach ($files as $x)
            {
                if (strpos($x, "."))
                {
                    // Archivo
                    array_push($archivos, $x);
                }
                else
                {
                    // Directorio
                    $aux_path = $path . DIRECTORY_SEPARATOR . $x;
                    $files_dir = scandir($aux_path); // Obtiene los archivos del direcorio
                    $dir_name = array_reverse(explode(DIRECTORY_SEPARATOR, $aux_path))[0];
                    unset($files_dir[0]);   //Eliminar .,..
                    unset($files_dir[1]);
                    array_push($carpetas, [$files_dir, "nombre" => $dir_name]);
                }
            }
            $list_files = ["archivos" => $archivos, "carpetas" => $carpetas];
            return response()->json(["status" => true, "listaArchivos" => $list_files]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return response()->json(
                [
                    "status" => false,
                    "mensaje" => "Error al obtener los archivos"
                ]
            );
        }
    }

    /**
     * Descarga el archivo ingresao
     */
    public function Descargar($ruta)
    {
        try
        {
            $ls = explode("&", $ruta);
            if (count($ls) < 2) return "ERROR. INGRESE LOS DATOC COMPLETOS";
            $dir = $ls[0];
            $file = $ls[1];
            $path = storage_path('app' . DIRECTORY_SEPARATOR .
                $this->PATH  . $dir . DIRECTORY_SEPARATOR . $file);
            if (!file_exists($path))
            {
                return  "<h3>Archivo no encontrado</h3>";
            }
            $header = $this->GetHeader($file);
            return response()->download($path, $file, [
                'Content-Type' => $header,
                'Content-Disposition' => 'inline; filename="' . $file . '"'
            ]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return "ERROR. AL DESCARGAR EL ARCHIVO SOLICITADO";
        }
    }

    public function GetHeader($file)
    {
        $header = "";
        $ext = array_reverse(explode(".", $file))[0];
        switch ($ext)
        {
            case 'pdf':
                $header = 'application/pdf';
                break;
            case 'doc':
            case 'docx':
            case 'docm':
                $header = 'application/msword';
                break;
            case 'xls':
            case 'xlsx':
            case 'xlsm':
                $header = 'application/vnd.ms-excel';
                break;
        }
        return $header;
    }
}
