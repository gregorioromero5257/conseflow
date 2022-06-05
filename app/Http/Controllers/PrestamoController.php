<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Prestamo;
use App\Empleado;
use Validator;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;
use \App\Http\Helpers\Utilidades;


class PrestamoController extends Controller
{
  protected $rules = array(
      'cantidad' => 'required|max:8',


  );

  public function index(Request $request, $id)
  {
    $prestamo = DB::table('prestamos')
    ->join('empleados AS Empleado', 'Empleado.id', '=', 'prestamos.empleado_id')
    ->select('prestamos.*',
    DB::raw("CONCAT(Empleado.nombre,' ',Empleado.ap_paterno,' ',Empleado.ap_materno) AS empleado"))
    ->where('prestamos.empleado_id', '=', $id)
    ->get();
    foreach ($prestamo as $key => $value) {
      $descuentoid = $value->id;
      $pagos_prestamo = DB::table('pagos_prestamos')
      ->select('pagos_prestamos.*')
      ->where('pagos_prestamos.prestamo_id', '=', $descuentoid)
      ->get()->last();
      $pdnumeropago = '';
      if (!is_null($pagos_prestamo)) {$pdnumeropago = $pagos_prestamo->numero_pago;   }else { $pdnumeropago = '0';  }
      $pagosc = $pdnumeropago.' de '.$value->numero_pago;
      $arreglo [] =[
        'prestamo' => $value,
        'pago' => $pagos_prestamo,
        'c' => $pagosc,
      ];

    }

    return response()->json(
      $arreglo
    );


  }

  public function store(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    if($request->metodo == 'Nuevo'){
            /*NUEVO REGISTRO*/
            //--Si el request no contiene archivos, solo se actualizan los campos listados--//
            if(!$request->hasFile('adjunto'))
            {

                $prestamo = new Prestamo();
                $prestamo->cantidad = $request->cantidad;
                $prestamo->fecha = $request->fecha;
                $prestamo->numero_pago = $request->numero_pago;
                $prestamo->observaciones = $request->observaciones;
                $prestamo->adjunto = null;
                $prestamo->empleado_id = $request->empleado_id;
                $prestamo->save();
            }

            //--valida que campos del request contienen archivos y realiza el guardado--//
            if($request->hasFile('adjunto'))
            {
                    //obtiene el nombre del archivo y su extension
                    $AdjuntoNE = $request->file('adjunto')->getClientOriginalName();
                    //Obtiene el nombre del archivo
                    $AdjuntoNombre = pathinfo($AdjuntoNE, PATHINFO_FILENAME);
                    //obtiene la extension
                    $AdjuntoExt = $request->file('adjunto')->getClientOriginalExtension();
                    //nombre que se guarad en BD
                    $AdjuntoStore = $AdjuntoNombre.'_'.uniqid().'.'.$AdjuntoExt;
                    //Subida del archivo al servidor ftp
                    Storage::disk('local')->put('Archivos/'.$AdjuntoStore, fopen($request->file('adjunto'), 'r+'));

                $prestamo = new Prestamo();
                $prestamo->cantidad = $request->cantidad;
                $prestamo->fecha = $request->fecha;
                $prestamo->numero_pago = $request->numero_pago;
                $prestamo->observaciones = $request->observaciones;
                $prestamo->adjunto = $AdjuntoStore;
                $prestamo->empleado_id = $request->empleado_id;
                $prestamo->save();
            }

            return response()->json(array(
                'status' => true,
            ));
            /*Fin NUEVO REGISTRO*/
        }





        if($request->metodo == "Actualizar"){
            /*ACTUALIZAR REGISTRO*/
            //*Variables para actualizar nuevos archivos y eliminar existentes
            $ValorAdjunto = '';
            $prestamos = Prestamo::where('id',$request->id)->get();

            foreach ($prestamos as $key => $item) {
                $ValorAdjunto = $item->adjunto;
            }
            //*FIN

            //--Si el request no contiene archivos, solo se actualizan los campos listados--//
            if(!$request->hasFile('adjunto'))
            {

                $prestamo = Prestamo::findOrFail($request->id);
                $prestamo->cantidad = $request->cantidad;
                $prestamo->fecha = $request->fecha;
                $prestamo->numero_pago = $request->numero_pago;
                $prestamo->observaciones = $request->observaciones;
                $prestamo->empleado_id = $request->empleado_id;
                $prestamo->save();
            }

            //--valida que campos del request contienen archivos y realiza el guardado--//
            if($request->hasFile('adjunto'))
            {
                    //obtiene el nombre del archivo y su extension
                    $AdjuntoNE = $request->file('adjunto')->getClientOriginalName();
                    //Obtiene el nombre del archivo
                    $AdjuntoNombre = pathinfo($AdjuntoNE, PATHINFO_FILENAME);
                    //obtiene la extension
                    $AdjuntoExt = $request->file('adjunto')->getClientOriginalExtension();
                    //nombre que se guarad en BD
                    $AdjuntoStore = $AdjuntoNombre.'_'.uniqid().'.'.$AdjuntoExt;
                    //Subida del archivo al servidor ftp
                    Storage::disk('local')->put('Archivos/'.$AdjuntoStore, fopen($request->file('adjunto'), 'r+'));
                    if ($ValorAdjunto != null) {
                        //Elimina el archivo en el servidor si requiere ser actualizado
                        Utilidades::ftpSolucionEliminar($ValorAdjunto);
                    }

                $prestamo = Prestamo::findOrFail($request->id);
                $prestamo->cantidad = $request->cantidad;
                $prestamo->fecha = $request->fecha;
                $prestamo->numero_pago = $request->numero_pago;
                $prestamo->observaciones = $request->observaciones;
                $prestamo->adjunto = $AdjuntoStore;
                $prestamo->empleado_id = $request->empleado_id;
                $prestamo->save();
            }

            return response()->json(array(
                'status' => true
            ));
            /*FIN ACTUALIZAR REGISTRO*/
        }
  }


  public function update(Request $request, $id)
  {
    //
  }

  public function show($id)
  {
    $prestamo = DB::table('prestamos')
    ->join('empleados AS Empleado', 'Empleado.id', '=', 'prestamos.empleado_id')
    ->select('prestamos.*',
    DB::raw("CONCAT(Empleado.nombre,' ',Empleado.ap_paterno,' ',Empleado.ap_materno) AS empleado"))
    ->get();

    return response()->json(
      $prestamo->toArray()
    );

  }

  public function edit($id)
  {
    // Se obtiene el archivo del FTP serve
      $archivo = Utilidades::ftpSolucion($id);
      // Se coloca el archivo en una ruta local
      Storage::disk('descarga')->put($id, $archivo);
      //--Devuelve la respuesta y descarga el archivo--//
      return response()->download(storage_path().'/app/descargas/'.$id);

  }

  public function politica($id){
    try {



    $array = [];

    $prestamos = DB::table('prestamos')->where('empleado_id','=',$id)->get();
    if (count($prestamos) == 0) {
      // $array [] =[
      //   'prestamo' => '',
      //   'pagosprestamos' => '',
      //   'total' => '',
      // ];
      return response()->json($array);
    }else{
      foreach ($prestamos as $key => $prestamo) {
        $prestamoid = $prestamo->id;
        if(!is_null($prestamoid)){
          $pagosprestamos = DB::table('pagos_prestamos')
          ->select('pagos_prestamos.*')
          ->where('pagos_prestamos.prestamo_id', '=', $prestamoid)
          ->get();

          $total = DB::table('pagos_prestamos')
          ->join('prestamos', 'prestamos.id', '=', 'pagos_prestamos.prestamo_id')
          ->select(DB::raw('SUM(pagos_prestamos.cantidad_a_pagar) AS total')
          )->where('pagos_prestamos.prestamo_id','=',$prestamoid)->get();
          $contratos =  DB::table('contratos')->select('contratos.*')->where('contratos.empleado_id','=',$id)->get();
          $sueldos = [];
          if (count($contratos) != 0) {
            foreach ($contratos as $key => $contrato) {  }
            $idcontrato =$contrato->id;
            $sueldos = DB::table('sueldos')->select('sueldos.*')->where('sueldos.contrato_id','=',$idcontrato)->get();
          }

        }
        $array [] =[
          'prestamo' => $prestamo,
          'pagosprestamos' => $pagosprestamos,
          'total' => $total ,
          'sueldo' => $sueldos,
        ];
      }
      return response()->json($array);
    }

  } catch (\Throwable $e) {
    Utilidades::errors($e);
  }

  }

  public function destroy($id)
  {
    //elimina de la ruta local el archivo descargado
    Storage::disk('descarga')->delete($id);
  }

  public function pdf($id)
  {
    $prestamos = Prestamo::where('prestamos.id', '=', $id)
            ->leftJoin('empleados', 'empleados.id', '=', 'prestamos.empleado_id')
            ->select('prestamos.*',
                'empleados.nombre',
                'empleados.ap_paterno',
                'empleados.ap_materno')
            ->get()->first();


    //return response()->json($recibos);
    $pdf = PDF::loadView('pdf.solicitud-prestamo', compact('prestamos'));

    return $pdf->download('solicitud-prestamo.pdf');
    //return $pdf->stream();
  }

    public function reportePrestamosPorDepto(Request $request)
    {
        $prestamos = DB::table('prestamos')
        ->join('pagos_prestamos', 'prestamos.id', '=', 'pagos_prestamos.prestamo_id')
        ->join('empleados', 'prestamos.empleado_id', '=', 'empleados.id')
        ->leftJoin('puestos', 'empleados.puesto_id', '=', 'puestos.id')
        ->leftJoin('departamentos', 'puestos.departamento_id', '=', 'departamentos.id')
        ->select('prestamos.id', 'cantidad', 'fecha', 'pagado', DB::Raw('SUM(cantidad_a_pagar) AS cantidad_pagada'), 'empleados.nombre', 'ap_paterno', 'ap_materno', 'departamentos.nombre AS departamento' )
        ->groupBy('prestamos.id', 'cantidad', 'fecha', 'pagado', 'empleados.nombre', 'ap_paterno', 'ap_materno', 'departamentos.nombre')
        ->where([
            ['prestamos.fecha', '>=', $request->fecha_inicial],
            ['prestamos.fecha', '<=', $request->fecha_final],
            ['prestamos.pagado', $request->pagado < 2 ? '=' : '<', $request->pagado],
            ['puestos.departamento_id', $request->departamento_id == 0 ? '>' : '=', $request->departamento_id],
            ['pagos_prestamos.fecha_pago', '<=', $request->fecha_pago]
        ])
        ->orderBy('fecha')
        ->get()->toArray();

        $total_prestamos = array_sum(array_column($prestamos, 'cantidad'));
        $total_pagado = array_sum(array_column($prestamos, 'cantidad_pagada'));

        return response()->json([
          'prestamos' => $prestamos,
          'total_prestamos' => round($total_prestamos, 2),
          'total_pagado' => round($total_pagado, 2)
        ]);
    }

    public function pdfPrestamosPorDepto($pagado, $fecha_inicial, $fecha_final, $fecha_pago, $departamento_id)
    {
        $prestamos = DB::table('prestamos')
          ->join('pagos_prestamos', 'prestamos.id', '=', 'pagos_prestamos.prestamo_id')
          ->join('empleados', 'prestamos.empleado_id', '=', 'empleados.id')
          ->leftJoin('puestos', 'empleados.puesto_id', '=', 'puestos.id')
          ->leftJoin('departamentos', 'puestos.departamento_id', '=', 'departamentos.id')
          ->select('prestamos.id', 'cantidad', 'fecha', 'pagado', DB::Raw('SUM(cantidad_a_pagar) AS cantidad_pagada'), 'empleados.nombre', 'ap_paterno', 'ap_materno', 'departamentos.nombre AS departamento' )
          ->groupBy('prestamos.id', 'cantidad', 'fecha', 'pagado', 'empleados.nombre', 'ap_paterno', 'ap_materno', 'departamentos.nombre')
          ->where([
              ['prestamos.fecha', '>=', $fecha_inicial],
              ['prestamos.fecha', '<=', $fecha_final],
              ['prestamos.pagado', $pagado < 2 ? '=' : '<', $pagado],
              ['puestos.departamento_id', $departamento_id == 0 ? '>' : '=', $departamento_id],
              ['pagos_prestamos.fecha_pago', '<=', $fecha_pago]
          ])
          ->orderBy('fecha')
          ->get()->toArray();

        $total_prestamos = array_sum(array_column($prestamos, 'cantidad'));
        $total_pagado = array_sum(array_column($prestamos, 'cantidad_pagada'));

        $pdf = PDF::loadView('pdf.prestamosdepto', compact('prestamos', 'total_prestamos', 'total_pagado', 'fecha_inicial', 'fecha_final', 'fecha_pago'));
        $pdf->setPaper("letter","landscape");
        $pdf->setOptions(['isPhpEnabled' => true]);

        return $pdf->stream();
    }

}
