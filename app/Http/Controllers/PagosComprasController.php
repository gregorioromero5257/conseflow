<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\PagoCompra;
use App\PagosNoRecurrentes;
use Validator;
use \App\Http\Helpers\Utilidades;
use File;
use Illuminate\Support\Facades\Storage;


class PagosComprasController extends Controller
{
  protected $rules = array(
    // 'titulo' => 'required|max:50',
  );

  public function index(Request $request, $id)
  {
    $puestos = DB::table('pagos_compras')
    ->leftjoin('tipo_pago_compras AS tpc','tpc.id','=','pagos_compras.tipo_id')
    ->select('pagos_compras.*','tpc.id AS id_tipo','tpc.nombre AS nombre_tipo')
    ->where('pagos_no_recurrentes_id', '=', $id)
    ->get();

    return response()->json(
      $puestos->toArray()
    );
  }

  public function store(Request $request)
  {
    try {
      if (!$request->ajax()) return redirect('/');

      if ($request->metodo == 'Nuevo') {

        $pagoCompra = new PagoCompra();
        $pagoCompra->fecha = $request->fecha;
        $pagoCompra->tipo_id = $request->tipo;
        $pagoCompra->suc = $request->suc;
        $pagoCompra->descripcion = $request->descripcion;
        $pagoCompra->cargo = $request->cargo;
        $pagoCompra->moneda = $request->moneda;
        $pagoCompra->referencia = $request->referencia;
        $pagoCompra->concepto = $request->concepto;
        $pagoCompra->proveedor_acredor = $request->proveedor_acredor;
        $pagoCompra->proyecto = $request->proyecto;
        $pagoCompra->banco = $request->banco;
        $pagoCompra->pagos_no_recurrentes_id = $request->pago_no_recurrente_id;
        $pagoCompra->tipo_cambio = $request->tipo_cambio;
        $pagoCompra->banco_ordenante = $request->banco_ordenante;

        if ($request->hasFile('adjunto')) {
          $nombre_archivo = $this->obtenerNombre($request,'adjunto');
          $pagoCompra->adjunto = $nombre_archivo;
          $this->subirAdjunto($request, $nombre_archivo,'adjunto');
        }
        if (!$request->hasFile('adjunto') && $request->adjunto != '' && strlen($request->adjunto) > 100) {
          $nombre_archivo = $this->gua($request->adjunto);
          $pagoCompra->adjunto = $nombre_archivo;
        }
        if ($request->hasFile('poliza')) {
          $nombre_archivo_poliza = $this->obtenerNombre($request,'poliza');
          $pagoCompra->num_poliza = $request->num_poliza;
          $pagoCompra->poliza = $nombre_archivo_poliza;
          $this->subirAdjunto($request,$nombre_archivo_poliza,'poliza');
        }


        Utilidades::auditar($pagoCompra, $pagoCompra->id);
        $pagoCompra->save();

        $pnr =PagosNoRecurrentes::where('id',$request->pago_no_recurrente_id)->first();
        $pnr->condicion = 7;
        $pnr->save();

        return response()->json(array(
          'status' => true
        ));
      }
      if ($request->metodo == 'Actualizar') {
        $ValorAdjunto = '';
        $ValorAdjunto_p = '';
        $pagoComprac = PagoCompra::where('id',$request->id)->first();
        $ValorAdjunto = $pagoComprac->adjunto;
        $ValorAdjunto_p = $pagoComprac->poliza;



        $nombre_archivo_actualizar = '';
        $pagoCompra = PagoCompra::where('id',$request->id)->first();
        $pagoCompra->fecha = $request->fecha;
        $pagoCompra->tipo_id = $request->tipo;
        $pagoCompra->suc = $request->suc;
        $pagoCompra->descripcion = $request->descripcion;
        $pagoCompra->cargo = $request->cargo;
        $pagoCompra->moneda = $request->moneda;
        $pagoCompra->referencia = $request->referencia;
        $pagoCompra->concepto = $request->concepto;
        $pagoCompra->proveedor_acredor = $request->proveedor_acredor;
        $pagoCompra->proyecto = $request->proyecto;
        $pagoCompra->banco = $request->banco;
        $pagoCompra->pagos_no_recurrentes_id = $request->pago_no_recurrente_id;
        $pagoCompra->tipo_cambio = $request->tipo_cambio;
        $pagoCompra->banco_ordenante = $request->banco_ordenante;

        if (!$request->hasFile('adjunto') && $request->adjunto != '' && strlen($request->adjunto) > 100) {
        $nombre_archivo = $this->gua($request->adjunto);
        $pagoCompra->adjunto = $nombre_archivo;
        if ($ValorAdjunto != '') {
          //Elimina el archivo en el servidor si requiere ser actualizado
          Utilidades::ftpSolucionEliminar($ValorAdjunto);
        }
      }

        if ($request->hasFile('adjunto')) {
          $nombre_archivo = $this->obtenerNombre($request,'adjunto');
          $pagoCompra->adjunto = $nombre_archivo;
          $this->subirAdjunto($request, $nombre_archivo,'adjunto');
          if ($ValorAdjunto != '') {
            //Elimina el archivo en el servidor si requiere ser actualizado
            Utilidades::ftpSolucionEliminar($ValorAdjunto);
          }
        }

        if ($request->hasFile('poliza')) {
          $nombre_archivo = $this->obtenerNombre($request,'poliza');
          $pagoCompra->num_poliza = $request->num_poliza;
          $pagoCompra->poliza = $nombre_archivo;
          $this->subirAdjunto($request, $nombre_archivo,'poliza');
          if ($ValorAdjunto_p != '') {
            //Elimina el archivo en el servidor si requiere ser actualizado
            Utilidades::ftpSolucionEliminar($ValorAdjunto_p);
          }
        }

        // if ($request->hasFile('poliza')) {
        // //   $nombre_archivo_poliza = $this->obtenerNombre($request,'poliza');
        // //   $pagoCompra->num_poliza = $request->num_poliza;
        // //   $pagoCompra->poliza = $nombre_archivo_poliza;
        // //   $this->subirAdjunto($request,$nombre_archivo_poliza,'poliza');
        // // }

        Utilidades::auditar($pagoCompra, $pagoCompra->id);
        $pagoCompra->save();

        return response()->json(array(
          'status' => true
        ));
      }


    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }

  public function update(Request $request)
  {
    try {
      if (!$request->ajax()) return redirect('/');
      $pagoCompra = PagoCompra::where('id',$request->id)->first();
      $pagoCompra->fecha = $request->fecha;
      $pagoCompra->tipo_id = $request->tipo;
      $pagoCompra->suc = $request->suc;
      $pagoCompra->descripcion = $request->descripcion;
      $pagoCompra->cargo = $request->cargo;
      $pagoCompra->moneda = $request->moneda;
      $pagoCompra->referencia = $request->referencia;
      $pagoCompra->concepto = $request->concepto;
      $pagoCompra->proveedor_acredor = $request->proveedor_acredor;
      $pagoCompra->proyecto = $request->proyecto;
      $pagoCompra->banco = $request->banco;
      $pagoCompra->pagos_no_recurrentes_id = $request->pago_no_recurrente_id;
      $pagoCompra->tipo_cambio = $request->tipo_cambio;

      if ($request->hasFile('adjunto')) {
        $nombre_archivo = $this->obtenerNombre($request,'adjunto');
        $pagoCompra->adjunto = $nombre_archivo;
        $this->subirAdjunto($request, $nombre_archivo,'adjunto');
      }
      $pagoCompra->save();

      return response()->json(array(
        'status' => true
      ));
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
    $FacturaStore = 'ReportePagoC_'.uniqid().'.'.$FacturaExt;

    return $FacturaStore;
  }

  public function subirAdjunto($request, $nombre_archivo,$name)
  {
    Storage::disk('local')->put('Archivos/'.$nombre_archivo, fopen($request->file($name), 'r+'));
    return true;
  }

  public function getfacurapago()
  {
    $tipos = DB::table('tipo_pago_compras')->get();
    return response()->json($tipos);
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


      Storage::disk('local')->put('Archivos/'.$imageName, base64_decode($image));

      // return response()->json(['status' => true]);
      return $imageName;

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }


}
