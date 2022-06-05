<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;
use Mail;
use App\Exports\HistoricoValeEppExport;
use Maatwebsite\Excel\Facades\Excel;

class ValeEppController extends Controller
{
    //
    public function get()
    {
        try {
            $empleado = \App\EmpleadoValeEpp::join('empleados AS e','e.id','empleados_vale_epp.empleado_id')
            ->select('empleados_vale_epp.empleado_id', DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado"))
            ->groupBy('empleados_vale_epp.empleado_id')->get();

            return response()->json($empleado);
        } catch (\Throwable $e) {
            Utilidades::errors($e);
        }
    }

    public function getDetalle($id)
    {
        try {
            $emp = \App\EmpleadoValeEpp::where('empleado_id',$id)->select('id')->get();
            $a = [];
            foreach ($emp as $key => $value) {
                $a [] = $value->id;
            }
            // return response()->json($a);

            $epp_partidas = \App\PartidasValeEpp::
            join('articulos AS a','a.id','partidas_vale_epp.articulo_id')
            ->join('empleados AS e','e.id','partidas_vale_epp.autoriza_id')
            ->select('partidas_vale_epp.*','a.descripcion',
            DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS autoriza"))
            ->whereIn('empleado_vale_id',$a)->get();

            return response()->json($epp_partidas);

        } catch (\Exception $e) {
            Utilidades::errors($e);
        }
    }

    public function getArt(Request $request)
    {
        $art = \App\Articulo::where('descripcion','LIKE','%'.$request->des.'%')->get();

        return response()->json($art);
    }

    public function guardar(Request $request)
    {
        try {
            $user = Auth::user();

            DB::beginTransaction();
            foreach ($request->datos as $key => $value) {

            $empleado = \App\EmpleadoValeEpp::where('empleado_id',$value['empleado_id'])
            ->where('autoriza_id',$user->empleado_id)->first();

            if (isset($empleado) == false) {
                $empleado = new \App\EmpleadoValeEpp();
                $empleado->empleado_id = $value['empleado_id'];
                $empleado->autoriza_id = $user->empleado_id;
                Utilidades::auditar($empleado, $empleado->id);
                $empleado->save();
            }

                $epp_partidas = new \App\PartidasValeEpp();
                $epp_partidas->empleado_vale_id = $empleado->id;
                $epp_partidas->articulo_id = $value['id'];
                $epp_partidas->cantidad = $value['cantidad'];
                $epp_partidas->fecha = $value['fecha'];
                $epp_partidas->autoriza_id = $value['autoriza'];
                $epp_partidas->proyecto_id = $value['proyecto_id'];
                Utilidades::auditar($epp_partidas, $epp_partidas->id);
                $epp_partidas->save();
            }

            DB::commit();

            // dd($empleado->id);
            $this->enviarcorreo($empleado->id);

            return response()->json(['status' => true]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Utilidades::errors($e);
        }
    }

    public function enviarcorreo($id)
    {
        try {

            // $emp = \App\EmpleadoValeEpp::where('id',$id)->first();
            //
            // $empleado_data = DB::table('empleados AS e')
            // ->leftJoin('puestos AS p','p.id','e.puesto_id')
            // ->select(DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS nombre"), 'p.nombre AS puesto')
            // ->where('e.id',$emp->empleado_id)
            // ->first();
            // $epp_partidas = \App\PartidasValeEpp::
            // join('articulos AS a','a.id','partidas_vale_epp.articulo_id')
            // ->join('empleados AS e','e.id','partidas_vale_epp.autoriza_id')
            // ->select('partidas_vale_epp.*','a.descripcion',DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS autoriza"))
            // ->where('partidas_vale_epp.empleado_vale_id',$emp->id)->get();
            // // dd($epp_partidas);
            //
            // $pdf = PDF::loadView('pdf.valeepp', compact('empleado_data','epp_partidas'));
            // $pdf->setPaper('letter', 'landscape');

            $data = [
                'nombre' => 'Solicitud EPP',
                'mensaje' => 'Solcitud de EPP',
            ];

            // Mail::send('emails.eppvale', $data, function ($message) use ($data, $pdf)
            Mail::send('emails.eppvale', $data, function ($message) use ($data)
            {
                $core = 'almacen.tehuacan@conserflow.com';
                $mensaje = $data['mensaje'];
                $message->to($core, $mensaje)
                ->subject($mensaje);
                $message->from('webmaster@conserflow.com', 'Conserflow');
                // $message->from('gregorio.romero@conserflow.com', 'Conserflow');
                // $message->attachData($pdf->output(), 'ValeEpp.pdf');
            });

        } catch (\Throwable $e) {
            Utilidades::errors($e);
        }
    }

    public function getAlmacen()
    {
        $dia = date('Y-m-d');
        $epp_partidas = \App\PartidasValeEpp::
        join('empleados_vale_epp AS evp','evp.id','partidas_vale_epp.empleado_vale_id')
        ->join('articulos AS a','a.id','partidas_vale_epp.articulo_id')
        ->join('empleados AS e','e.id','partidas_vale_epp.autoriza_id')
        ->join('empleados AS es','es.id','evp.empleado_id')
        ->select('partidas_vale_epp.*','a.descripcion',
        DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS nombre_autoriza"),
        DB::raw("CONCAT(es.nombre,' ',es.ap_paterno,' ',es.ap_materno) AS nombre_solicita")
        )
        // ->where('partidas_vale_epp.empleado_vale_id',$emp->id)
        ->where('partidas_vale_epp.condicion','1')
        ->where('fecha',$dia)
        ->orderBy('partidas_vale_epp.created_at','DESC')
        ->get();

        return response()->json($epp_partidas);
    }

    public function eliminar($id)
    {
        // code...
        $epp_partidas = \App\PartidasValeEpp::where('id',$id)->delete();
        return response()->json(['status' => true]);
    }

    public function descargar($id)
    {

        $emp = \App\EmpleadoValeEpp::where('empleado_id',$id)->select('id')->get();
        $empleado_data = DB::table('empleados AS e')
        ->leftJoin('puestos AS p','p.id','e.puesto_id')
        ->select(DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS nombre"),'e.id', 'p.nombre AS puesto')
        ->where('e.id',$id)
        ->first();

        $a = [];
        foreach ($emp as $key => $value) {
            $a [] = $value->id;
        }
        // return response()->json($a);

        $epp_partidas = \App\PartidasValeEpp::
        join('articulos AS a','a.id','partidas_vale_epp.articulo_id')
        ->join('empleados AS e','e.id','partidas_vale_epp.autoriza_id')
        ->select('partidas_vale_epp.*','a.descripcion',DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS autoriza"))
        ->whereIn('empleado_vale_id',$a)->get();

        $pdf = PDF::loadView('pdf.valeepp', compact('empleado_data','epp_partidas'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('letter', 'landscape');
        return $pdf->stream();

    }

    public function Actualizar(Request $request)
    {
        try {
            $epp_partidas = \App\PartidasValeEpp::where('id',$request->id)->first();
            $epp_partidas->articulo_id = $request->articulo_id;
            $epp_partidas->cantidad = $request->cantidad;
            $epp_partidas->fecha = $request->fecha;
            $epp_partidas->autoriza_id = $request->autoriza_id;
            Utilidades::auditar($epp_partidas, $epp_partidas->id);
            $epp_partidas->save();

            return response()->json(['status' => true]);
        } catch (\Throwable $e) {
            Utilidades::errors($e);
        }
    }

    public function getPartidas(Request $request)
    {
        $valores = explode(' ',$request->desc);
        $lote_almacen = DB::table('lote_almacen AS la')
        ->join('stocks AS s','s.id','la.stocke_id')
        ->join('proyectos AS p','p.id','s.proyecto_id')
        ->join('articulos AS a','a.id','la.articulo_id')
        ->where(function ($query) use ($valores) {
            foreach ($valores as $key => $value) {
                $tipo = $this->getValido($value);
                if ($tipo != true) {
                    if ($key === 0)
                    $query->where('a.descripcion','LIKE','%'.$value.'%');
                    else
                    $query->orWhere('a.descripcion','LIKE','%'.$value.'%');
                }
            }
        })->where('la.cantidad','>','0')
        ->select('la.id','a.descripcion','la.articulo_id','la.cantidad','p.nombre_corto')
        ->get();

        return response()->json($lote_almacen);
    }

    public function getValido($data)
    {
        $palabras = ['DE', 'CON', 'PARA','TIPO', 'A' ,'POR','EN','tipo','en', 'de', 'con', 'para', 'a','por'];

        $busqueda = array_search($data, $palabras);
        $resultado = is_numeric($busqueda) == true ? true : $busqueda;

        return $resultado;
    }

    public function getArticulos()
    {
        $articulos = DB::table('partidas_vale_epp AS pve')
        ->join('articulos AS a','a.id','pve.articulo_id')
        ->select('a.id','a.descripcion')
        ->groupBy('a.id')
        ->get();

         return response()->json($articulos);
    }

    public function getArticulosEpp($id)
    {
        if ($id == 0) {
            $articulos_epp =  DB::table('partidas_vale_epp AS pve')
            ->join('empleados_vale_epp AS evp','evp.id','pve.empleado_vale_id')
            ->join('empleados AS e','e.id','evp.empleado_id')
            ->join('empleados AS ea','ea.id','pve.autoriza_id')
            ->join('articulos AS a','a.id','pve.articulo_id')
            ->select(
                'pve.*','a.descripcion',
                DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleador"),
                DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleadoa")
                )
            ->get();
        }else {
            $articulos_epp =  DB::table('partidas_vale_epp AS pve')
            ->join('empleados_vale_epp AS evp','evp.id','pve.empleado_vale_id')
            ->join('empleados AS e','e.id','evp.empleado_id')
            ->join('empleados AS ea','ea.id','pve.autoriza_id')
            ->join('articulos AS a','a.id','pve.articulo_id')
            ->select(
                'pve.*','a.descripcion',
                DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleador"),
                DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleadoa")
                )
                ->where('pve.articulo_id',$id)
                ->get();
        }

        return response()->json($articulos_epp);
    }

    public function ExportarArtEpp($id)
    {
        ini_set('max_execution_time', 3000);
        return Excel::download(new HistoricoValeEppExport($id), 'ReporteHistoricoEpp.xlsx');
    }
}
