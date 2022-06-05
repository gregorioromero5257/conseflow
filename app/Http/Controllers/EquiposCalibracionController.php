<?php

namespace App\Http\Controllers;

use App\Articulo;
use App\EquiposCatalogo;
use App\Exports\CalibracionReporteExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use App\ServiciosEquiposCalibracion;
use Exception;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Servicio - Estado
 * 0. Nuevo
 * 1. fecha_servicio Modificado
 * 2. proxima_fecha Modificado
 * 3. Renovar
 */
class EquiposCalibracionController extends Controller
{
  //
  public function index()
  {
    try
    {
      $equipos = DB::table('equipos_catalogo')->orderBy("id","desc")->get();

      $arreglo = [];
      foreach ($equipos as $key => $value)
      {
        $servicios = DB::table('servicios_equipos_calibracion')->where('equipos_catalogo_id', $value->id)->orderBy('id', 'DESC')->first();

        $arreglo[] = [
          'equipos' => $value,
          'servicios' => $servicios,
        ];
      }

      return response()->json($arreglo);
    }
    catch (\Throwable $e)
    {
      Utilidades::errors($e);
    }
  }

  public function guardar(Request $request)
  {
    DB::beginTransaction();
    try
    {
      $equipos = new \App\EquiposCatalogo();
      $equipos->descripcion = $request->descripcion;
      $equipos->marca = $request->marca;
      $equipos->modelo = $request->modelo;
      $equipos->rango_medicion = $request->rango_medicion;
      $equipos->numero_serie = $request->numero_serie;
      $equipos->frecuencia = $request->frecuencia;
      $equipos->resguardo = $request->resguardo;
      $equipos->observaciones = $request->observaciones;
      Utilidades::auditar($equipos, $equipos->id);
      $equipos->save();

      if ($request->fecha_servicio != null || $request->proxima_fecha != null)
      {

        $servicios = new \App\ServiciosEquiposCalibracion();
        $servicios->equipos_catalogo_id = $equipos->id;
        $servicios->fecha_servicio = $request->fecha_servicio;
        $servicios->proxima_fecha = $request->proxima_fecha;
        $servicios->estado = 0;
        Utilidades::auditar($servicios, $servicios->id);
        $servicios->save();
      }

      $articulo=new Articulo();
      $articulo->nombre=$request->descripcion .' Modelo: '.$request->modelo.
      ' con rango de medición '.$request->rango_medicion .' Num. Serie '.$request->numero_serie;

      $articulo->descripcion=$request->descripcion .' Modelo: '.$request->modelo.
      ' con rango de medición '.$request->rango_medicion .' Num. Serie '.$request->numero_serie;
      $articulo->marca=$request->marca;
      $articulo->comentarios=$request->descripcion .' Modelo: '.$request->modelo.
      ' con rango de medición '.$request->rango_medicion .' Num. Serie '.$request->numero_serie;
      $articulo->unidad="EQUIPO";
      $articulo->save();

      $articulo_id=$articulo->id;
      $cantidad=1;
      $precio=0;
      // Registrar entrada interna

      $rhoc = new \App\requisicionhasordencompras();
        $rhoc->requisicione_id = 1;
        $rhoc->orden_compra_id = 1;
        $rhoc->articulo_id = $articulo_id;
        $rhoc->cantidad = $cantidad;
        $rhoc->precio_unitario = $precio;
        $rhoc->tipo_entrada = 'Interna';
        $rhoc->condicion = 0;
        $rhoc->antigua = 0;
        $rhoc->cantidad_entrada = 0;
        Utilidades::auditar($rhoc,$rhoc->id);
        $rhoc->save();

        // PArtidaEntrada
        $partidaEntrada = new \App\PartidaEntrada();
        $partidaEntrada->entrada_id = 1;
        $partidaEntrada->req_com_id = $rhoc->id;
        $partidaEntrada->articulo_id = $articulo_id;
        $partidaEntrada->validacion_calidad = 0;
        $partidaEntrada->cantidad = $cantidad;
        $partidaEntrada->almacene_id = 1;
        $partidaEntrada->pendiente = 0;
        $partidaEntrada->status = 0;
        $partidaEntrada->precio_unitario = $precio;
        $partidaEntrada->stocke_id = 1;
        $partidaEntrada->save();

        // Lote
        $lote = new \App\Lote();
        $lote->nombre = "lote 0002-".$articulo_id;
        $lote->entrada_id = $partidaEntrada->id;
        $lote->articulo_id = $articulo_id;
        $lote->cantidad = 1;
        $lote->save();
        $lote->nombre = ("lote 0002-" . $articulo_id . "-" . $lote->id);
        $lote->save();

        // LoteAlmacen
        $lote_almacen = new \App\LoteAlmacen();
        $lote_almacen->lote_id = $lote->id;
        $lote_almacen->almacene_id = 1;
        $lote_almacen->cantidad = $cantidad;
        $lote_almacen->stocke_id = 1;
        $lote_almacen->articulo_id = $articulo_id;
        $lote_almacen->condicion = 1;
        $lote_almacen->codigo_barras = 'MCF 0001 '.$articulo_id.' '.$lote->id;
        $lote_almacen->save();

        // Existencia ??? Crear nueva existencia para cada articulo? (Repetidos?)
        $existencia = new \App\Existencia();
        $existencia->articulo_id = $articulo_id;
        $existencia->almacene_id = 1;
        $existencia->id_lote = $lote->id;
        $existencia->cantidad = $cantidad;
        $existencia->save();

        // Movimiento
        $movimiento = new \App\Movimiento();
        $movimiento->cantidad = $cantidad;
        $movimiento->fecha = date("y-m-d");
        $movimiento->hora = date("H:i:s");
        $movimiento->tipo_movimiento = "INV";
        $movimiento->folio = "Entrada-" . 1 . 1;
        $movimiento->proyecto_id = 1;
        $movimiento->lote_id = $lote_almacen->id;
        $movimiento->stocke_id = 1;
        $movimiento->almacene_id = 1;
        $movimiento->articulo_id = $articulo_id;
        $movimiento->save();

        $stock_articulo = \App\StockArticulo::where("articulo_id", "=", $articulo_id)
            ->where("stocke_id", "=", 1)->first();
        if ($stock_articulo == null)
        {
            // Registrar nuevo
            $nuevo_stock = new \App\StockArticulo();
            $nuevo_stock->cantidad = $cantidad;
            $nuevo_stock->articulo_id = $articulo_id;
            $nuevo_stock->stocke_id = 1;
            $nuevo_stock->save();
        }
        else
        {
            // Sumar cantidad
            $n = $stock_articulo->cantidad + $cantidad;
            $stk = \App\StockArticulo::where("articulo_id", "=", $articulo_id)
                ->where("stocke_id", "=", 1)->first();
            $stk->cantidad = $n;
            $stk->update();
        }
      DB::commit();
      return response()->json(["status"=> true]);


    }
    catch (\Throwable $e)
    {
      Utilidades::errors($e);
      return response()->json(["status"=> false, "mensaje"=>"Error al registrar entrada equipo"]);
    }
  }

  /**
   * Actualiza el equipo con los datos ingesados
   */
  public function actualizar(Request $request)
  {
    if (!$request->ajax()) return;
    try
    {
      DB::beginTransaction();
      // Actualizar equipo catalogo
      $equipo = EquiposCatalogo::where('id', $request->id)->first();
      $equipo->descripcion = $request->descripcion;
      $equipo->marca = $request->marca;
      $equipo->modelo = $request->modelo;
      $equipo->rango_medicion = $request->rango_medicion;
      $equipo->numero_serie = $request->numero_serie;
      $equipo->frecuencia = $request->frecuencia;
      $equipo->resguardo = $request->resguardo;
      $equipo->observaciones = $request->observaciones;
      Utilidades::auditar($equipo, $equipo->id);
      $equipo->save();
	//dd($equipo);
      // Actualizar articulo (almacen)
      $articulo = Articulo::where('id',$equipo->articulo_id)->first();
	if(isset($articulo) == true){
      $articulo->nombre = $equipo->descripcion . ' Modelo: ' . $equipo->modelo .
        ' con rango de medición ' . $equipo->rango_medicion . ' Num. Serie ' . $equipo->numer_serie;
      $articulo->descripcion = $equipo->descripcion  . 'Modelo: ' . $equipo->modelo .
        ' con rango de medición ' . $equipo['Rango de Medición'] . ' Num. Serie ' . $equipo->numero_serie;
      $articulo->update();
	}else{
	  $articulo=new Articulo();
      $articulo->nombre=$request->descripcion .' Modelo: '.$request->modelo.
      ' con rango de medición '.$request->rango_medicion .' Num. Serie '.$request->numero_serie;

      $articulo->descripcion=$request->descripcion .' Modelo: '.$request->modelo.
      ' con rango de medición '.$request->rango_medicion .' Num. Serie '.$request->numero_serie;
      $articulo->marca=$request->marca;
      $articulo->comentarios=$request->descripcion .' Modelo: '.$request->modelo.
      ' con rango de medición '.$request->rango_medicion .' Num. Serie '.$request->numero_serie;
      $articulo->unidad="EQUIPO";
      $articulo->save();
	}
	$equipo->articulo_id = $articulo->id;
	$equipo->save();

      $servicios_buscar = ServiciosEquiposCalibracion::where('id', $request->servicio_id)
        ->first();
      if ($servicios_buscar == null)
      {
        // Nuevo
        $servicio = new \App\ServiciosEquiposCalibracion();
        $servicio->equipos_catalogo_id = $equipo->id;
        $servicio->fecha_servicio = $request->fecha_servicio;
        $servicio->proxima_fecha = $request->proxima_fecha;
        $servicio->estado = 0;
        Utilidades::auditar($servicio, 0);
        $servicio->save();
      }
      else
      {
        if ($servicios_buscar->fecha_servicio != $request->fecha_servicio)
          $servicios_buscar->estado = 1;
        if ($servicios_buscar->proxima_fecha != $request->proxima_fecha & $servicios_buscar->estado == 1)
          $servicios_buscar->estado = 2;
        if ($servicios_buscar->estado == 2)
        {
          // Nuevo
          $servicios_buscar->estado = 3;
        }
        $servicios_buscar->save();
      }

      DB::commit();
      return response()->json(['status' => true]);
    }
    catch (\Throwable $e)
    {
      DB::rollBack();
      Utilidades::errors($e);
    }
  }

  public function Yolo()
  {
    // Vencidos
    $aux_equipos = DB::table('equipos_catalogo')->get();

    $vencidos = [];
    foreach ($aux_equipos as $equipo)
    {
      $servicio = DB::table('servicios_equipos_calibracion')
        ->where('equipos_catalogo_id', $equipo->id)
        ->orderBy('id', 'DESC')
        ->first();
      // Sin registro
      if ($servicio == null)
      {
        $equipo->estado = 1;
        $equipo->fecha_servicio = "N/D";
        $equipo->proxima_fecha = "N/D";
        array_push($vencidos, $equipo);
      }
      else
      {
        // Vencido
        $proxima_fecha = $servicio->proxima_fecha;
        $hoy = date("Y-m-d");
        if ($proxima_fecha < $hoy)
        {
          $equipo->estado = 2;
          $equipo->fecha_servicio = $servicio->fecha_servicio;
          $equipo->proxima_fecha = $proxima_fecha;
          array_push($vencidos, $equipo);
        }
        if ($proxima_fecha >= $hoy & $proxima_fecha <= date("Y-m-d", strtotime($hoy . "+ 90 days")))
        {
          $equipo->estado = 3;
          $equipo->fecha_servicio = $servicio->fecha_servicio;
          $equipo->proxima_fecha = $servicio->proxima_fecha;
          array_push($vencidos, $equipo);
        }
      }
    }
    return response()->json(["status" => true, "equipos" => $vencidos]);
  }

  /**
   * Mustra los equipos de calibración en el dasboard de calidad
   */
  public function DashCalidad()
  {
    try
    {
      return $this->Yolo();
      $vencidos = DB::table("equipos_catalogo as ec")
        ->where("ec.proxima_fecha", "<=", DB::raw("curdate()"))
        ->select(
          "ec.*",
          DB::raw("1 as estado"),
          DB::raw("date(ec.fecha_servicio) as aux_servicio"),
          DB::raw("date(ec.proxima_fecha) as aux_proxima")
        )
        ->orderBy("aux_servicio")
        ->orderBy("aux_proxima")
        ->get()->toArray();

      $proximos = DB::table("equipos_catalogo as ec")
        ->where("ec.proxima_fecha", ">=", DB::raw("curdate()"))
        ->where("ec.proxima_fecha", "<=", DB::raw("adddate(curdate(),90)"))
        ->select(
          "ec.*",
          DB::raw("2 as estado"),
          DB::raw("date(ec.fecha_servicio) as aux_servicio"),
          DB::raw("date(ec.proxima_fecha) as aux_proxima")
        )
        ->orderBy("aux_servicio")
        ->orderBy("aux_proxima")
        ->get()->toArray();

      $equipos = array_merge($vencidos, $proximos);
      return response()->json(["status" => true, "equipos" => $equipos]);
    }
    catch (Exception $e)
    {
      Utilidades::errors($e);
      return response()->json(
        [
          "status" => false,
          "mensaje" => "Error al obtener los equipos por calibrar"
        ]
      );
    }
  }

  /**
   * Obtiene todos los equipos de calibración con su ultima fecha de servicio
   */
  public function Obtener()
  {
    try
    {
      $equipos = EquiposCatalogo::get();
      foreach ($equipos as $equipo)
      {
        $servicio = DB::table("servicios_equipos_calibracion as sec")
          ->where("equipos_catalogo_id", $equipo->id)
          ->orderBy("id", "desc")
          ->first();
        $proxima_fecha = "N/D";
        $fecha_servicio = "N/D";
        $id = 0;
        $estado = $servicio != null ? $servicio->estado : 0;
        if ($servicio != null)
        {
          $proxima_fecha = $servicio->proxima_fecha;
          $fecha_servicio = $servicio->fecha_servicio;
          $id = $servicio->id;
        }

        $equipo->proxima_fecha = $proxima_fecha;
        $equipo->fecha_servicio = $fecha_servicio;
        $equipo->estado_equipo = $estado;
        $equipo->servicio_id = $id;
        // $aux = (array)$equipo;
        // $aux["estado"] = $servicio->estado;
        // $equipo = (object)$aux;
      }
      return response()->json(["status" => true, "equipos" => $equipos]);
    }
    catch (Exception $e)
    {
      Utilidades::errors($e);
      return response()->json(["status" => false, "mensaje" => "Error al obtener los equipos de calibración"]);
    }
  }

  /**
   * Obtiene el historial de servicios del equipo ingresado
   */
  public function Historial($id)
  {
    try
    {
      $historial = DB::table("servicios_equipos_calibracion as sec")
        ->where("equipos_catalogo_id", $id)
        ->get();
      return response()->json(["status" => true, "historial" => $historial]);
    }
    catch (Exception $e)
    {
      Utilidades::errors($e);
      return response()->json(["status" => false, "mensaje" => "Error al obtener el historial de calibración"]);
    }
  }

  /**
   * Registra un nuevo servicio (Calibración) del equipo ingresado
   */
  public function GuardarCalib(Request $request)
  {
    try
    {
      $cer = $request->certificado;
      if ($request->hasFile('certificado'))
      {
        // Registrar servicio
        $servicio = new ServiciosEquiposCalibracion();
        $servicio->equipos_catalogo_id = $request->equipos_catalogo_id;
        $servicio->fecha_servicio = $request->fecha_servicio;
        $servicio->proxima_fecha = $request->proxima_fecha;
        $servicio->estado = 0;
        $servicio->save();

        // Documento
        $doc = $request->file("certificado");
        $nombre = $doc->getClientOriginalName();
        //$hoy_aux = date("y_m_d_h_i_s");
        $aux_nombre = $servicio->id . "_" . $nombre;
        $ruta_documento = "CertificadosCalib/" . $aux_nombre;
        Storage::disk('local')->put($ruta_documento, fopen($doc, 'r+'));
        $servicio->certificado = $aux_nombre;
        $servicio->update();
        return response()->json(["status" => true]);
      }
      else
        return response()->json(["status" => false, "mensaje" => "Certificado de calibación no encontrado"]);
    }
    catch (Exception $e)
    {
      Utilidades::errors($e);
      return response()->json(["status" => false, "mensaje" => "Error al registrar el servicio"]);
    }
  }

  /**
   * Descarga el certificado de calibración del servicio ingresado
   */
  public function DescargarCert($id)
  {
    try
    {
      $servicio = ServiciosEquiposCalibracion::find($id);
      $nombre = $servicio->certificado;
      $ruta = storage_path('app' . DIRECTORY_SEPARATOR . "CertificadosCalib" . DIRECTORY_SEPARATOR . $nombre);
      // Se coloca el archivo en una ruta local
      if (!file_exists($ruta))
      {
        return  "<h3>Archivo no encontrado</h3>";
      }
      $header = 'application/pdf';
      return response()->download($ruta, $nombre, [
        'Content-Type' => $header,
        'Content-Disposition' => 'inline; filename="' . $nombre . '"'
      ]);
    }
    catch (Exception $e)
    {
      return "<h5>Error al obtener el archivo</h5>";
    }
  }

  public function ftpSolucionT($nombre)
  {
    $archivo = null;
    $PATH = "CertificadosCalib" . DIRECTORY_SEPARATOR;
    //Se busca en disk o carpeta
    if (Storage::exists($PATH . $nombre))
    {
      // Se coloca el archivo en una ruta local
      $archivo = Storage::disk('local')->get($PATH . $nombre);
    }
    else
    {
      //$output = shell_exec("mkdir /var/www/html/conserflow2020/conserflow-sistema2018/storage/app/" . $this->PATH_ROOT . "/ 2> errormk.txt;cp /home/vsftpuser/ftp/Solicitudes/$id /var/www/html/conserflow2020/conserflow-sistema2018/storage/app/" . $this->PATH_ROOT . "/ 2> error.txt;");
      $archivo = Storage::disk('local')->get($PATH . $nombre);
    }
    return $archivo;
  }

  /**
   * Descarga el reporte general de calibración
   */
  public function DescargarReporte()
  {
    // try
    // {
      return Excel::download(new CalibracionReporteExport(), 'PAL-02_F-01 CONTROL DE CALIBRACIÓN DE EQUIPOS.xlsx');
    // }
    // catch (Exception $e)
    // {
    //   Utilidades::errors($e);
    //   return "<h5>Error al general el reporte</<h5>";
    // }
  }
}
