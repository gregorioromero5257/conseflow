<?php

namespace App\Http\Controllers;

use \App\Articulo;
use \App\Proyecto;
use \App\QualityCert;
use \App\Requisicion;
use \App\Salida;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\Storage;
use Validator;



class ControlDocumentosController extends Controller
{
    protected $rules = array(
        'adjunto' => 'required',

    );

    public function index(Request $request)
    {
        $proyectos = Proyecto::orderBy('id', 'asc')->get();
        return response()->json($proyectos);
    }

    public function busquedaControlP(Request $request)
    {
        $articulos_p = DB::table('partidas')
            ->select('A.*')
            ->leftJoin('lote_almacen AS LA', 'LA.id', '=', 'partidas.lote_id')
            ->leftJoin('articulos AS A', 'A.id', '=', 'LA.articulo_id')
            ->leftJoin('stocks AS S', 'S.id', '=', 'LA.stocke_id')
            ->where('S.proyecto_id', '=', $request->proyecto_id)
            ->get();

        return response()->json($articulos_p);

    }

/*    public function busquedaControlA($id)
    {
        $valores = explode('&', $id);
        $articulos_a = DB::select("SELECT DISTINCT la.id AS lote_almacen_id,a.id AS articulo_id, l.*, a.descripcion  FROM partidas p LEFT JOIN lote_almacen la ON la.id = p.lote_id LEFT JOIN lotes l ON l.id = la.lote_id LEFT JOIN articulos a ON a.id = la.articulo_id LEFT JOIN stocks s ON s.id = la.stocke_id JOIN partidas_entradas pa ON pa.id = l.entrada_id  WHERE a.id = '$valores[0]' AND s.proyecto_id = '$valores[1]'");

        foreach ($articulos_a as $key => $value) {
            $qualitycert = DB::table('quality_certificates')
                ->select('quality_certificates.*')
                ->where('partidaentrada_id', '=', $value->entrada_id)->get();

            $arreglo [] = [
                'partida' => $value,
                'documentos' => $qualitycert
            ];
        }
        return response()->json($arreglo);
    }*/

    public function busquedaControlB($id)
    {

        $partidasTaller = DB::table('partidas')
            ->leftJoin('lote_almacen AS LA', 'LA.id', '=', 'partidas.lote_id')
            ->leftJoin('articulos AS A', 'A.id', '=', 'LA.articulo_id')
            ->leftJoin('stocks AS S', 'S.id', '=', 'LA.stocke_id')
            ->leftJoin('salidas AS sl', 'sl.id', '=', 'partidas.salida_id')
            ->leftJoin('proyectos AS PR', 'PR.id', '=', 'sl.proyecto_id')
            ->leftJoin('precios AS P', 'P.lote_id', '=', 'LA.id')
            ->leftJoin('tipo_salidas AS TS', 'TS.id', '=', 'partidas.tiposalida_id')
            ->Join('lotes as l', 'l.id', '=', 'LA.lote_id')
            ->join('partidas_entradas as pe', 'pe.id', '=', 'l.entrada_id')
            ->Join('quality_certificates as qc', 'qc.partidaentrada_id', '=', 'pe.id')
            ->Join('empleados', 'empleados.id', '=', 'qc.empleado_responsable_id')
            ->select('qc.*', 'S.proyecto_id', 'P.precio_unitario', 'A.descripcion', 'PR.nombre',
            'TS.nombre AS tipo_salida_nombre','A.codigo as codigo','A.marca as marca','A.unidad as unidad','l.nombre as lote',
                DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) AS nombreEmp"))
            ->where('sl.proyecto_id', '=', $id)
            ->where('partidas.tiposalida_id', '=', '1')
            ->get()->toArray();

        $partidasSitio = DB::table('partidas')
            ->leftJoin('lote_almacen AS LA', 'LA.id', '=', 'partidas.lote_id')
            ->leftJoin('articulos AS A', 'A.id', '=', 'LA.articulo_id')
            ->leftJoin('stocks AS S', 'S.id', '=', 'LA.stocke_id')
            ->leftJoin('salidassitio AS sl', 'sl.id', '=', 'partidas.salida_id')
            ->leftJoin('proyectos AS PR', 'PR.id', '=', 'sl.proyecto_id')
            ->leftJoin('precios AS P', 'P.lote_id', '=', 'LA.id')
            ->leftJoin('tipo_salidas AS TS', 'TS.id', '=', 'partidas.tiposalida_id')
            ->Join('lotes as l', 'l.id', '=', 'LA.lote_id')
            ->join('partidas_entradas as pe', 'pe.id', '=', 'l.entrada_id')
            ->Join('quality_certificates as qc', 'qc.partidaentrada_id', '=', 'pe.id')
            ->Join('empleados', 'empleados.id', '=', 'qc.empleado_responsable_id')
            ->select('qc.*', 'S.proyecto_id', 'P.precio_unitario', 'A.descripcion', 'PR.nombre',
            'TS.nombre AS tipo_salida_nombre','A.codigo as codigo','A.marca as marca','A.unidad as unidad','l.nombre as lote',
                DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) AS nombreEmp"))
            ->where('sl.proyecto_id', '=', $id)
            ->where('partidas.tiposalida_id', '=', '2')
            ->get()->toArray();

        $partidasResguardo = DB::table('partidas')
            ->leftJoin('lote_almacen AS LA', 'LA.id', '=', 'partidas.lote_id')
            ->leftJoin('articulos AS A', 'A.id', '=', 'LA.articulo_id')
            ->leftJoin('stocks AS S', 'S.id', '=', 'LA.stocke_id')
            ->leftJoin('salidasresguardo AS sl', 'sl.id', '=', 'partidas.salida_id')
            ->leftJoin('proyectos AS PR', 'PR.id', '=', 'sl.proyecto_id')
            ->leftJoin('precios AS P', 'P.lote_id', '=', 'LA.id')
            ->leftJoin('tipo_salidas AS TS', 'TS.id', '=', 'partidas.tiposalida_id')
            ->Join('lotes as l', 'l.id', '=', 'LA.lote_id')
            ->join('partidas_entradas as pe', 'pe.id', '=', 'l.entrada_id')
            ->Join('quality_certificates as qc', 'qc.partidaentrada_id', '=', 'pe.id')
            ->Join('empleados', 'empleados.id', '=', 'qc.empleado_responsable_id')
            ->select('qc.*', 'S.proyecto_id', 'P.precio_unitario', 'A.descripcion', 'PR.nombre',
            'TS.nombre AS tipo_salida_nombre','A.codigo as codigo','A.marca as marca','A.unidad as unidad','l.nombre as lote',
                DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) AS nombreEmp"))
            ->where('sl.proyecto_id', '=', $id)
            ->where('partidas.tiposalida_id', '=', '3')
            ->get()->toArray();

        $partidas = array_merge($partidasTaller, $partidasSitio, $partidasResguardo);

        return response()->json($partidas);
    }


    public function articuloBusqueda($id)
    {
        $total = 0;
        $articulorequi = $this->busquedaControl($id);

        foreach ($articulorequi as $key => $value) {
            $total += $value->$total;
        }
    }

    public function documentosEntradas($id)
    {
        $partidas = DB:: table('lote_almacen')
            ->Join('lotes', 'lotes.id', '=', 'lote_almacen.lote_id')
            ->Join('articulos', 'articulos.id', '=', 'lotes.articulo_id')
            ->Join('stocks', 'stocks.id', '=', 'lote_almacen.stocke_id')
            ->Join('partidas_entradas', 'partidas_entradas.id', '=', 'lotes.entrada_id')
            ->Join('quality_certificates', 'quality_certificates.partidaentrada_id', '=', 'partidas_entradas.id')
            ->Join('empleados', 'empleados.id', '=', 'quality_certificates.empleado_responsable_id')
            ->select('quality_certificates.*', 'lotes.nombre', 'partidas_entradas.id as pEntrada_id', 'articulos.descripcion as descripcion',
                'articulos.marca as marca', 'articulos.unidad as unidad', 'articulos.codigo as codigo',
                DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) AS nombreEmp"))
            ->where('stocks.proyecto_id', '=', $id)
            ->orderBy('lote_almacen.articulo_id')->get();

        return response()->json($partidas);

    }

    public function store(Request $request)
    {
        try{
        if ($request->metodo == 2) {
            $DocumentoStore=null;
            //*Variables para actualizar nuevos archivos y eliminar existentes
            $ValorDocumento = null;
            $certificados = QualityCert::where('id',$request->id)->get();

            foreach ($certificados as $key => $item) {
                $ValorDocumento = $item->adjunto;
                $DocumentoStore=$item->adjunto;
            }
            //*FIN

            //obtiene el nombre del archivo y su extension
            $DocumentoNE = $request->file('adjunto')->getClientOriginalName();
            //Obtiene el nombre del archivo
            $DocumentoNombre = pathinfo($DocumentoNE, PATHINFO_FILENAME);
            //obtiene la extension
            $DocumentoExt = $request->file('adjunto')->getClientOriginalExtension();
            //nombre que se guarad en BD
            $DocumentoStore = $DocumentoNombre.'_'.uniqid().'.'.$DocumentoExt;
            //Subida del archivo al servidor
            Storage::disk('local')->put('Archivos/'.$DocumentoStore, fopen($request->file('adjunto'), 'r+'));
            if ($ValorDocumento != null) {
                //Elimina el archivo en el servidor si requiere ser actualizado
                Utilidades::ftpSolucionEliminar($ValorDocumento);
            }
            $certificado = QualityCert::findOrFail($request->id);
            $certificado->adjunto = $DocumentoStore;
            $certificado->estado_documento = 1;
            Utilidades::auditar($certificado, $certificado->id);
            $certificado->save();

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
        // Se obtiene el archivo del  serve
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

    // public function store(Request $request)
    // {
    //     if($request->metodo == "Nuevo"){
    //         /*NUEVO REGISTRO*/
    //         //Variables de archivo
    //         $Licencia_aStore='';
    //         $Licencia_bStore='';
    //
    //         if (!$request->ajax()) return redirect('/');
    //
    //         //--Si el request no contiene archivos, solo se actualizan los campos listados--//
    //         if(!$request->hasFile('licencia_a') && !$request->hasFile('licencia_b'))
    //         {
    //
    //             $conductor = new Conductores();
    //             $conductor->licencia_a = '';
    //             $conductor->licencia_b = '';
    //             $conductor->empleado_id = $request->empleado_id;
    //             $conductor->save();
    //         }
    //
    //         //--valida que campos del request contienen archivos y realiza el guardado--//
    //         if($request->hasFile('licencia_a') || $request->hasFile('licencia_b'))
    //         {
    //             if ($request->hasFile('licencia_a')) {
    //                 //obtiene el nombre del archivo y su extension
    //                 $Licencia_aNE = $request->file('licencia_a')->getClientOriginalName();
    //                 //Obtiene el nombre del archivo
    //                 $Licenca_aNombre = pathinfo($Licencia_aNE, PATHINFO_FILENAME);
    //                 //obtiene la extension
    //                 $Licencia_aExt = $request->file('licencia_a')->getClientOriginalExtension();
    //                 //nombre que se guarad en BD
    //                 $Licencia_aStore = $Licenca_aNombre.'_'.uniqid().'.'.$Licencia_aExt;
    //                 //Subida del archivo al servidor ftp
    //                 Storage::disk('ftp')->put($Licencia_aStore, fopen($request->file('licencia_a'), 'r+'));
    //             }
    //             if ($request->hasFile('licencia_b')) {
    //                 //obtiene el nombre del archivo y su extension
    //                 $Licencia_bNE = $request->file('licencia_b')->getClientOriginalName();
    //                 //Obtiene el nombre del archivo
    //                 $Licenca_bNombre = pathinfo($Licencia_bNE, PATHINFO_FILENAME);
    //                 //obtiene la extension
    //                 $Licencia_bExt = $request->file('licencia_b')->getClientOriginalExtension();
    //                 //nombre que se guarad en BD
    //                 $Licencia_bStore = $Licenca_bNombre.'_'.uniqid().'.'.$Licencia_bExt;
    //                 //Subida del archivo al servidor ftp
    //                 Storage::disk('ftp')->put($Licencia_bStore, fopen($request->file('licencia_b'), 'r+'));
    //             }
    //
    //             $conductor = new Conductores();
    //             $conductor->empleado_id = $request->empleado_id;
    //             $conductor->licencia_a = $Licencia_aStore;
    //             $conductor->licencia_b = $Licencia_bStore;
    //             $conductor->save();
    //         }
    //
    //         return response()->json(array(
    //             'status' => true,
    //         ));
    //         /*FIN NUEVO REGISTRO*/
    //     }
    //
    //     if($request->metodo == "Actualizar"){
    //         /*ACTUALIZAR REGISTRO*/
    //         //Variables de archivo
    //         $Licencia_aStore='';
    //         $Licencia_bStore='';
    //         //*Variables para actualizar nuevos archivos y eliminar existentes
    //         $ValorLicencia_a = '';
    //         $ValorLicencia_b = '';
    //         $conductores = Conductores::where('id',$request->id)->get();
    //
    //         foreach ($conductores as $key => $item) {
    //             $ValorLicencia_a = $item->licencia_a;
    //             $ValorLicencia_b = $item->licencia_b;
    //
    //             $Licencia_aStore=$item->licencia_a;
    //             $Licencia_bStore=$item->licencia_b;
    //         }
    //         //*FIN
    //
    //         if (!$request->ajax()) return redirect('/');
    //
    //         //--Si el request no contiene archivos, solo se actualizan los campos listados--//
    //         if(!$request->hasFile('licencia_a') && !$request->hasFile('licencia_b'))
    //         {
    //
    //             $conductor = Conductores::findOrFail($request->id);
    //             $conductor->empleado_id = $request->empleado_id;
    //             $conductor->save();
    //         }
    //
    //         //--valida que campos del request contienen archivos y realiza el guardado--//
    //         if($request->hasFile('licencia_a') || $request->hasFile('licencia_b'))
    //         {
    //             if ($request->hasFile('licencia_a')) {
    //                 //obtiene el nombre del archivo y su extension
    //                 $Licencia_aNE = $request->file('licencia_a')->getClientOriginalName();
    //                 //Obtiene el nombre del archivo
    //                 $Licenca_aNombre = pathinfo($Licencia_aNE, PATHINFO_FILENAME);
    //                 //obtiene la extension
    //                 $Licencia_aExt = $request->file('licencia_a')->getClientOriginalExtension();
    //                 //nombre que se guarad en BD
    //                 $Licencia_aStore = $Licenca_aNombre.'_'.uniqid().'.'.$Licencia_aExt;
    //                 //Subida del archivo al servidor ftp
    //                 Storage::disk('ftp')->put($Licencia_aStore, fopen($request->file('licencia_a'), 'r+'));
    //                 if ($ValorLicencia_a != '') {
    //                     //Elimina el archivo en el servidor si requiere ser actualizado
    //                     Storage::disk('ftp')->delete($ValorLicencia_a);
    //                 }
    //             }
    //             if ($request->hasFile('licencia_b')) {
    //                 //obtiene el nombre del archivo y su extension
    //                 $Licencia_bNE = $request->file('licencia_b')->getClientOriginalName();
    //                 //Obtiene el nombre del archivo
    //                 $Licenca_bNombre = pathinfo($Licencia_bNE, PATHINFO_FILENAME);
    //                 //obtiene la extension
    //                 $Licencia_bExt = $request->file('licencia_b')->getClientOriginalExtension();
    //                 //nombre que se guarad en BD
    //                 $Licencia_bStore = $Licenca_bNombre.'_'.uniqid().'.'.$Licencia_bExt;
    //                 //Subida del archivo al servidor ftp
    //                 Storage::disk('ftp')->put($Licencia_bStore, fopen($request->file('licencia_b'), 'r+'));
    //                 if ($ValorLicencia_b != '') {
    //                     //Elimina el archivo en el servidor si requiere ser actualizado
    //                     Storage::disk('ftp')->delete($ValorLicencia_b);
    //                 }
    //             }
    //
    //             $conductor = Conductores::findOrFail($request->id);
    //             $conductor->empleado_id = $request->empleado_id;
    //             $conductor->licencia_a = $Licencia_aStore;
    //             $conductor->licencia_b = $Licencia_bStore;
    //             $conductor->save();
    //         }
    //
    //         return response()->json(array(
    //             'status' => true
    //         ));
    //         /*FIN ACTUALIZAR REGISTRO*/
    //     }
    // }

}
