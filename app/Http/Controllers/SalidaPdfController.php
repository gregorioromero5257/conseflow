<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Salida;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade as PDF;
use \App\Http\Helpers\Utilidades;

class SalidaPdfController extends Controller
{

    /**
    * [pdf Genera un archivo PDf con los datos obtenidos]
    * @param  Int $id [Id de Salida]
    * @return Response     [pdf]
    */
    public function pdf($id)
    {

        $ids = Auth::id();

        $salidas = DB::table('salidas')->select('salidas.*',
        DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS entrega"),
        DB::raw("CONCAT(ER.nombre,' ',ER.ap_paterno,' ',ER.ap_materno) AS recibe"),
        DB::raw("CONCAT(ES.nombre,' ',ES.ap_paterno,' ',ES.ap_materno) AS solicita"),
        DB::raw("CONCAT(EA.nombre,' ',EA.ap_paterno,' ',EA.ap_materno) AS autoriza"))
        ->leftJoin('empleados AS EE', 'EE.id', '=', 'salidas.empleado_entrega_id')
        ->leftJoin('empleados AS ER', 'ER.id', '=', 'salidas.empleado_recibe_id')
        ->leftJoin('empleados AS EA', 'EA.id', '=', 'salidas.empleado_autoriza_id')
        ->leftJoin('empleados AS ES', 'ES.id', '=', 'salidas.empleado_id')
        ->leftJoin('proyectos', 'proyectos.id', '=', 'salidas.proyecto_id')
        ->leftJoin('tipo_salidas', 'tipo_salidas.id', '=', 'salidas.tiposalida_id')
        ->where('salidas.id','=',$id)->first();
        $proyecto = \App\Proyecto::where('id','=',$salidas->proyecto_id)->first();
        $partidas = DB::table('partidas')
        ->leftJoin('lote_almacen AS LA','LA.id','=','partidas.lote_id')
        ->leftJoin('articulos AS A','A.id','=','LA.articulo_id')
        ->select('partidas.*','A.descripcion AS descripcion','A.unidad')
        ->where('salida_id','=',$salidas->id)->where('tiposalida_id','=',$salidas->tiposalida_id)->get();

        $count =1;
        $tamanio = 16 - count($partidas);
        $pdf = PDF::loadView('pdf.salida', compact('salidas','proyecto','partidas','ids','count','tamanio'));
        $pdf->setPaper("letter", "landscape");
        return $pdf->stream();
      }

      public function pdfnew($id)
      {
          $ids = Auth::id();

          $salidas = DB::table('salidas')->select('salidas.*',
          DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS entrega"),
          DB::raw("CONCAT(ER.nombre,' ',ER.ap_paterno,' ',ER.ap_materno) AS recibe"),
          DB::raw("CONCAT(ES.nombre,' ',ES.ap_paterno,' ',ES.ap_materno) AS solicita"),
          DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS supervisor"),
          DB::raw("CONCAT(EA.nombre,' ',EA.ap_paterno,' ',EA.ap_materno) AS autoriza"))
          ->leftJoin('empleados AS EE', 'EE.id', '=', 'salidas.empleado_entrega_id')
          ->leftJoin('empleados AS ER', 'ER.id', '=', 'salidas.empleado_recibe_id')
          ->leftJoin('empleados AS EA', 'EA.id', '=', 'salidas.empleado_autoriza_id')
          ->leftJoin('empleados AS ES', 'ES.id', '=', 'salidas.empleado_id')
          ->leftJoin('proyectos', 'proyectos.id', '=', 'salidas.proyecto_id')
          ->leftJoin('tipo_salidas', 'tipo_salidas.id', '=', 'salidas.tiposalida_id')
          ->leftJoin('supervisores_proyectos AS sp','sp.id','=','salidas.supervisores_proyectos_id')
          ->leftjoin('empleados AS E', 'E.id', '=', 'sp.supervisor_id')
          ->where('salidas.id','=',$id)->first();

          $proyecto = \App\Proyecto::where('id','=',$salidas->proyecto_id)->first();
          $partidas = DB::table('partidas')
          ->leftJoin('lote_almacen AS LA','LA.id','=','partidas.lote_id')
          ->leftJoin('articulos AS A','A.id','=','LA.articulo_id')
          ->select('partidas.*','A.descripcion AS descripcion','A.unidad')
          ->where('salida_id','=',$salidas->id)->where('tiposalida_id','=',$salidas->tiposalida_id)->get();


          $solicita_datos = \App\Empleado::
          join('puestos AS p','p.id','=','empleados.puesto_id')
          ->where('empleados.id',$salidas->empleado_id)
          ->select('p.*')->first();


          $count =1;
          $tamanio = 21 - count($partidas);
          $pdf = PDF::loadView('pdf.salidanew', compact('solicita_datos','salidas','proyecto','partidas','ids','count','tamanio'));
          $pdf->getDomPDF()->set_option("enable_php", true);
          $pdf->setPaper("A4", "portrait");
          return $pdf->stream();
        }

    /**
    * [pdfsitio Genera un archivo PDf(Formato Salidas a Sitio) con los datos obtenidos]
    * @param  Int $id [Id de salida]
    * @return Response     [pdf]
    */
    public function pdfsitio($id)
    {

        $ids = Auth::id();

        $salidas = DB::table('salidassitio')->select('salidassitio.*','proyectos.nombre AS pnombre','PU.nombre AS punombre','PU.area AS puarea',
        DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS entrega"),
        DB::raw("CONCAT(ER.nombre,' ',ER.ap_paterno,' ',ER.ap_materno) AS recibe"),
        DB::raw("CONCAT(ES.nombre,' ',ES.ap_paterno,' ',ES.ap_materno) AS solicita"),
        DB::raw("CONCAT(EA.nombre,' ',EA.ap_paterno,' ',EA.ap_materno) AS autoriza"))
        ->leftJoin('empleados AS EE', 'EE.id', '=', 'salidassitio.empleado_entrega_id')
        ->leftJoin('empleados AS ER', 'ER.id', '=', 'salidassitio.empleado_recibe_id')
        ->leftJoin('empleados AS EA', 'EA.id', '=', 'salidassitio.empleado_autoriza_id')
        ->leftJoin('empleados AS ES', 'ES.id', '=', 'salidassitio.empleado_solicita_id')
        ->leftJoin('puestos AS PU','PU.id','=','ES.puesto_id')
        ->leftJoin('proyectos', 'proyectos.id', '=', 'salidassitio.proyecto_id')
        ->leftJoin('tipo_salidas', 'tipo_salidas.id', '=', 'salidassitio.tiposalida_id')
        ->where('salidassitio.id','=',$id)->first();
        $proyecto = \App\Proyecto::where('id','=',$salidas->proyecto_id)->first();
        $partidas = DB::table('partidas')
        ->leftJoin('lote_almacen AS LA','LA.id','=','partidas.lote_id')
        ->leftJoin('articulos AS A','A.id','=','LA.articulo_id')
        ->leftJoin('grupos AS G','G.id','=','A.grupo_id')
        ->select('partidas.*','A.descripcion AS descripcion','A.unidad','G.nombre AS gnombre')
        ->where('salida_id','=',$salidas->id)->where('tiposalida_id','=',$salidas->tiposalida_id)->get();


        $count =1;
        $tamanio = 16 - count($partidas);
        $pdfsitio = PDF::loadView('pdf.salidasitio', compact('salidas','ids','count','tamanio','partidas'));
        $pdfsitio->setPaper('letter', 'landscape');
        return $pdfsitio->stream();
      }

      /**
      * [pdfsitio Genera un archivo PDf(Formato Salidas a Sitio) con los datos obtenidos]
      * @param  Int $id [Id de salida]
      * @return Response     [pdf]
      */
      public function pdfsitionew($id)
      {

          $ids = Auth::id();

          $salidas = DB::table('salidassitio')->select('salidassitio.*','proyectos.nombre AS pnombre','PU.nombre AS punombre','PU.area AS puarea',
          DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS entrega"),
          DB::raw("CONCAT(ER.nombre,' ',ER.ap_paterno,' ',ER.ap_materno) AS recibe"),
          DB::raw("CONCAT(ES.nombre,' ',ES.ap_paterno,' ',ES.ap_materno) AS solicita"),
          DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS supervisor"),
          DB::raw("CONCAT(EA.nombre,' ',EA.ap_paterno,' ',EA.ap_materno) AS autoriza"))
          ->leftJoin('empleados AS EE', 'EE.id', '=', 'salidassitio.empleado_entrega_id')
          ->leftJoin('empleados AS ER', 'ER.id', '=', 'salidassitio.empleado_recibe_id')
          ->leftJoin('empleados AS EA', 'EA.id', '=', 'salidassitio.empleado_autoriza_id')
          ->leftJoin('empleados AS ES', 'ES.id', '=', 'salidassitio.empleado_solicita_id')
          ->leftJoin('puestos AS PU','PU.id','=','ES.puesto_id')
          ->leftJoin('proyectos', 'proyectos.id', '=', 'salidassitio.proyecto_id')
          ->leftJoin('tipo_salidas', 'tipo_salidas.id', '=', 'salidassitio.tiposalida_id')
          ->leftJoin('supervisores_proyectos AS sp','sp.id','=','salidassitio.supervisores_proyectos_id')
          ->leftjoin('empleados AS E', 'E.id', '=', 'sp.supervisor_id')
          ->where('salidassitio.id','=',$id)->first();
          $proyecto = \App\Proyecto::where('id','=',$salidas->proyecto_id)->first();
          $partidas = DB::table('partidas')
          ->leftJoin('lote_almacen AS LA','LA.id','=','partidas.lote_id')
          ->leftJoin('articulos AS A','A.id','=','LA.articulo_id')
          ->leftJoin('grupos AS G','G.id','=','A.grupo_id')
          ->select('partidas.*','A.descripcion AS descripcion','A.unidad','G.nombre AS gnombre')
          ->where('salida_id','=',$salidas->id)->where('tiposalida_id','=',$salidas->tiposalida_id)->get();
          $solicita_datos = \App\Empleado::
          join('puestos AS p','p.id','=','empleados.puesto_id')
          ->where('empleados.id',$salidas->empleado_solicita_id)
          ->select('p.*')->first();

          $count =1;
          $tamanio = 16 - count($partidas);
          $pdfsitio = PDF::loadView('pdf.salidanew', compact('solicita_datos','salidas','ids','count','proyecto','tamanio','partidas'));
          $pdfsitio->getDomPDF()->set_option("enable_php", true);
          $pdfsitio->setPaper('letter', 'portrait');
          return $pdfsitio->stream();
        }
    }
