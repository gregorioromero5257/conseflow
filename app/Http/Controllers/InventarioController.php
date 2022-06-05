<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\ArticuloInv;
use App\CategoriaInv;
use App\UbicacionInv;
use App\RegistroInv;
use App\CodigoInv;
use App\CodigoFacInv;
use App\ProveedorInv;
use App\CompradorInv;
use App\FacturaInv;
// use App\CodigoInv;
use \App\Http\Helpers\Utilidades;
use File;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;



class InventarioController extends Controller
{
  public function articulos()
  {
    $articulo = ArticuloInv::get();
    return response()->json($articulo);
  }

  public function categoria()
  {
    $cat = CategoriaInv::get();
    return response()->json($cat);
  }

  public function store(Request $request)
  {
    try {
      $articulo = new ArticuloInv();
      $articulo->idcat = $request->categoria;
      $articulo->nombre = $request->nombre;
      $articulo->marca = $request->marca;
      $articulo->ns = $request->serie;
      $articulo->caracteristicas = $request->caracteristicas;
      $articulo->save();
      return response()->json(array(
        'status' => true
      ));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return response()->json(array('status' => 'error'));
    }

  }

  public function update(Request $request)
  {
    try {
      $articulo = ArticuloInv::where('id',$request->id)->first();
      $articulo->idcat = $request->categoria;
      $articulo->nombre = $request->nombre;
      $articulo->marca = $request->marca;
      $articulo->ns = $request->serie;
      $articulo->caracteristicas = $request->caracteristicas;
      $articulo->save();
      return response()->json(array(
        'status' => true
      ));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return response()->json(array('status' => 'error'));
    }

  }

  public function getNaves($id)
  {
    $ubicacion = UbicacionInv::where('idnav',$id)->get();

    return response()->json($ubicacion);
  }

  public function reguardar(Request $request)
  {
    try {
      $user = Auth::user();
      for ($i=1; $i <= $request->cantidad ; $i++) {
        // code...

        $reg = new RegistroInv();
        $reg->idart = $request->articulo;
        // $reg->idubi = $request->ubicacion;En
        $reg->comentario = $request->comentario;
        $reg->empleado_user_id = $user->empleado_id;
        $reg->save();

        $ubicacion =RegistroInv::
        join('ubicacion AS u','u.id','=','registro.idubi')
        ->join('nave AS n','n.id','=','u.idnav')
        ->select('n.id')->where('registro.id',$reg->id)->first();


        $cod = new CodigoInv();
        $cod->codigo = 'MBO'.str_pad($reg->id, 4, "0", STR_PAD_LEFT);
        $cod->idreg = $reg->id;
        $cod->save();
      }
      return response()->json(array(
        'status' => true
      ));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return response()->json(array('status' => 'error'));
    }
  }

  public function initial()
  {
    $registros = \App\RegistroInv::join('articulo as a','a.id','=','registro.idart')
    ->leftjoin('categoria as c','c.id','=','a.idcat')
    // ->leftjoin('ubicacion as u','u.id','=','registro.idubi')
    ->join('codigo AS cd','cd.idreg','=','registro.id')
    ->select('registro.*','a.nombre as nombre_a','a.marca as marca_a','c.nombre as nombre_c',
    'cd.codigo','cd.id AS cod_id','cd.impreso'
    )
    ->where('cd.estado','0')
    ->get();

    $arreglo = [];

    foreach ($registros as $key => $value) {
      $e = DB::table('empleados AS e')
      ->select(DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado"))
      ->where('id',$value->empleado_user_id)
      ->first();

      $arreglo [] = [
        'registro' => $value,
        'empleado' => $e,
      ];
    }

    return response()->json($arreglo);
  }

  public function guardarphotos(Request $request)
  {
    try {



      if ($request->hasFile('imagen_uno')) {
        //obtiene el nombre del archivo y su extension
        $FacturaNE_U = $request->file('imagen_uno')->getClientOriginalName();
        //Obtiene el nombre del archivo
        $FacturaNombre_U = pathinfo($FacturaNE_U, PATHINFO_FILENAME);
        //obtiene la extension
        $FacturaExt_U = $request->file('imagen_uno')->getClientOriginalExtension();
        //nombre que se guarad en BD
        $FacturaStore_U = 'imguno'.uniqid().'.'.$FacturaExt_U;
        //Subida del archivo al servidor ftp
        Storage::disk('local')->put('Archivos/'.$FacturaStore_U, fopen($request->file('imagen_uno'), 'r+'));
      }
      if ($request->hasFile('imagen_dos')) {
        //obtiene el nombre del archivo y su extension
        $FacturaNE = $request->file('imagen_dos')->getClientOriginalName();
        //Obtiene el nombre del archivo
        $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
        //obtiene la extension
        $FacturaExt = $request->file('imagen_dos')->getClientOriginalExtension();
        //nombre que se guarad en BD
        $FacturaStore = 'imgdos'.uniqid().'.'.$FacturaExt;
        //Subida del archivo al servidor ftp
        Storage::disk('local')->put('Archivos/'.$FacturaStore, fopen($request->file('imagen_dos'), 'r+'));
      }
      $reg =RegistroInv::where('id',$request->id)->first();
      $reg->foto1 = $FacturaStore_U;
      $reg->foto2 = $FacturaStore;
      $reg->save();

      $cod = CodigoInv::where('idreg',$request->id)->first();
      $cod->estado = 1;
      $cod->save();

      return response()->json(array(
        'status' => true,
      ));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return response()->json(array(
        'status' => false,
      ));
    }
  }

  public function proveedores()
  {
    $proveedores = ProveedorInv::get();
    return response()->json($proveedores);
  }

  public function compradores()
  {
    $compradores = CompradorInv::get();
    return response()->json($compradores);
  }

  public function get_facturas()
  {
    $facturas = FacturaInv::
    leftJoin('proveedor AS p','p.id','=','factura.proveedor_id')
    ->leftJoin('comprador AS c','c.id','=','factura.comprador_id')
    ->select('factura.*','p.nombre AS p_nombre','p.rfc AS p_rfc','c.nombre AS c_nombre')
    ->get();
    return response()->json($facturas);
  }

  public function store_facturas(Request $request)
  {
    try {
      $factura = new FacturaInv();
      $factura->uuid = $request->uuid;
      $factura->folio = $request->folio;
      $factura->monto = $request->monto;
      $factura->comprador_id = $request->comprador;
      $factura->proveedor_id = $request->proveedor;
      if ($request->hasFile('pdf')) {
        $nombre_archivo = $this->obtenerNombre($request,'pdf');
        $factura->pdf = $nombre_archivo;
        $this->subirAdjunto($request, $nombre_archivo,'pdf');
      }
      if ($request->hasFile('xml')) {
        $nombre_archivo = $this->obtenerNombre($request,'xml');
        $factura->xml = $nombre_archivo;
        $this->subirAdjunto($request, $nombre_archivo,'xml');
      }
      $factura->save();
      return response()->json(array('status' => true));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }

  public function update_facturas(Request $request)
  {

    try {
      $factura = FacturaInv::where('id',$request->id)->first();
      $factura->uuid = $request->uuid;
      $factura->folio = $request->folio;
      $factura->monto = $request->monto;
      $factura->comprador_id = $request->comprador;
      $factura->proveedor_id = $request->proveedor;
      if ($request->hasFile('pdf')) {
        if ($factura->pdf != '') {
            //Elimina el archivo en el servidor si requiere ser actualizado
            Utilidades::ftpSolucionEliminar($factura->pdf);
        }
        $nombre_archivo = $this->obtenerNombre($request,'pdf');
        $factura->pdf = $nombre_archivo;
        $this->subirAdjunto($request, $nombre_archivo,'pdf');
      }
      if ($request->hasFile('xml')) {
        if ($factura->xml != '') {
            //Elimina el archivo en el servidor si requiere ser actualizado
            Utilidades::ftpSolucionEliminar($factura->xml);
        }
        $nombre_archivo = $this->obtenerNombre($request,'xml');
        $factura->xml = $nombre_archivo;
        $this->subirAdjunto($request, $nombre_archivo,'xml');
      }
      $factura->save();
      return response()->json(array('status' => true));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }

  public function obtenerNombre($request,$tipo)
  {
    //obtiene el nombre del archivo y su extension
    $FacturaNE = $request->file($tipo)->getClientOriginalName();
    //Obtiene el nombre del archivo
    $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
    //obtiene la extension
    $FacturaExt = $request->file($tipo)->getClientOriginalExtension();
    //nombre que se guarad en BD
    $FacturaStore = $tipo.'factura'.uniqid().'.'.$FacturaExt;

    return $FacturaStore;
  }

  public function subirAdjunto($request, $nombre_archivo,$tipo)
  {
    Storage::disk('local')->put('Archivos/'.$nombre_archivo, fopen($request->file($tipo), 'r+'));
    return true;
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function descargar($id)
  {
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

  public function codigos()
  {
    $codigos = CodigoInv::join('registro AS r','r.id','=','codigo.idreg')
    ->join('articulo AS a','a.id','=','r.idart')
    ->join('categoria AS c','c.id','=','a.idcat')
    ->join('ubicacion AS u','u.id','=','r.idubi')
    ->join('nave AS n','n.id','=','u.idnav')
    ->select('codigo.*','a.nombre AS nombre_a','u.nombre AS nombre_u','n.nombre AS nombre_n','c.nombre AS nombre_c')->get();
    return response()->json($codigos);
  }


public function descargarC($id){
    ini_set('max_execution_time', 1000);
$valores = array_map('intval', explode('&', $id));
    //$valores = explode('&',$id);
//	unset($valores[0]);
//return response()->json($valores);
//    $tamanio_d = count($valores) ;

    $array_codigos = [];
    $array_descripciones = [];

       $emple = CodigoInv::
       join('registro AS r','r.id','=','codigo.idreg')
       ->join('articulo AS a','a.id','=','r.idart')
       ->select('codigo.*','a.nombre','a.caracteristicas')
       ->whereIn('codigo.id',$valores)
       ->get();
//return response()->json($valores);

//return response()->json($emple);

       foreach ($emple as $key => $value) {
         $barras = $this->Codigo($value->codigo);

         $array_codigos [] = $barras;
         $array_descripciones [] = $value->nombre." ".$value->caracteristicas;

         $dd = CodigoInv::where('id',$value->id)->first();
         $dd->impreso = $dd->impreso +1;
         $dd->save();
       } 
       $pdf = PDF::loadView('pdf.codigospdf', compact('array_codigos','tamanio_d','array_descripciones'));
       // $pdf->setPaper('A4', 'landscape');
       $pdf->setPaper("letter", "portrait");
       // $pdf->setPaper(array(0,0,216,144));
       // return $pdf->download('cv-interno.pdf');
       return $pdf->stream();
  }

  public function Codigo($nombre)
  {
    $barcode = new BarcodeGenerator();
    // $barcode->setText("ABCD");
    $barcode->setText($nombre);
    // $barcode->setType(BarcodeGenerator::Ean128);
    $barcode->setType(BarcodeGenerator::Code39);
    $barcode->setScale(4);
    $barcode->setThickness(25);
    $barcode->setFontSize(16);
    $code=$barcode->generate();
    return $code;



    // <div align="center" >
    // <div class="position">
    //   <img src="data:image/png;base64,'.{{$barras}}.'" alt="Goyo" width="200px" heigth="400px" style="padding-left: -25px;"/>
    // <br>
    // <label style="font-size: 8px; padding-top: -10px;
    //   font-family: Arial, Helvetica, sans-serif;
    //   text-align: justify;">{{$des}}</label>
    // </div>
    // </div>

  }

public function Codigo_($nombre)
    {
        // "codeitnowin/barcode": "^3.0"
        $barcode = new BarcodeGenerator();

        // $barcode->setScale(3);
        $barcode->setText($nombre);
      //  $barcode->setSize(10);
        $barcode->setType(BarcodeGenerator::Code39);
        $code=$barcode->generate();
        return $code;
        // Storage::disk('descarga')->put('nombre.png','<img src="data:image/png;base64,'.$code.'" />');
        //return response()->json('data:image/png;base64,' . $code);
         ///echo '<img src="data:image/png;base64,'.$code.'" />';
    }

}
