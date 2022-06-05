<?php

namespace App\Http\Controllers;


use App\Transferencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class TransferenciaController extends Controller
{
    
    /**
    * [uploadTransferencia Genera los encabezados de un archivo de excel]
    * @param  Reques $reques [Datos del POST] 
    * @return Response          [collection]
    */
    public function uploadTransferencia(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        if ($request->hasFile('import_file')) {

            ini_set('max_execution_time', 300);

            $collection = (new FastExcel)->import($request->file('import_file')->getRealPath());

            foreach ($collection as $item) {

                $transferencia = new Transferencia();
                $transferencia->codigo = $item['CODIGO'];
                $transferencia->articulos = $item['ARTICULOS'];
                $transferencia->precio_unitario = $item['PRECIO UNITARIO'];
                $transferencia->cantidad = $item['CANTIDAD'];
                $transferencia->importe = $item['IMPORTE'];
                $transferencia->total = $item['TOTAL'];
                $transferencia->save();

            }

            return response()->json($collection);

        }
    }

    /**
    * [listaArticulos Obtiene los datos necesarios para generar un archivo de excel con los datos de las
    * partidas]
    * @param  Reques $reques [Datos del POST] 
    * @return Response          [transferenciasgeneral]
    */
    public function listaArticulos(Request $request)
    {
        $lista = DB::table('transferencia')
            ->select('transferencia.*')
            ->distinct()->get()->toArray();

        $partidasTaller = DB::table('partidas')
            ->leftJoin('lote_almacen AS LA', 'LA.id', '=', 'partidas.lote_id')
            ->leftJoin('articulos AS A', 'A.id', '=', 'LA.articulo_id')
            ->leftJoin('stocks AS S', 'S.id', '=', 'LA.stocke_id')
            ->leftJoin('salidas AS sl', 'sl.id', '=', 'partidas.salida_id')
            ->leftJoin('proyectos AS PR', 'PR.id', '=', 'sl.proyecto_id')
            ->leftJoin('precios AS P', 'P.lote_id', '=', 'LA.id')
            ->leftJoin('tipo_salidas AS TS', 'TS.id', '=', 'partidas.tiposalida_id')
            ->select('partidas.*', 'S.proyecto_id', 'P.precio_unitario as precio_unitario', 'A.descripcion as articulos', 'PR.nombre', 'TS.nombre AS tipo_salida_nombre', DB::raw('(partidas.cantidad * P.precio_unitario) AS total'))
            ->distinct()->get()->toArray();

        $compras = DB::table('requisicion_has_ordencompras as c')
            ->select('c.id', 'c.cantidad as cantidad', 'c.precio_unitario as precio_unitario', 'a.nombre', 'a.descripcion as articulos', 'a.marca as codigo', DB::raw('(c.precio_unitario * c.cantidad) AS total'))
            ->join('articulos as a', 'c.articulo_id', '=', 'a.id')
            ->distinct()->get()->toArray();

        $transferenciasgeneral = array_merge($lista, $compras,$partidasTaller);
        return $transferenciasgeneral;

    }

        // code...




}
