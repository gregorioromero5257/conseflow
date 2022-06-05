<?php

namespace App\Http\Controllers;

use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use Barryvdh\DomPDF\Facade as PDF;
use App\Proveedor;
use App\BancoProveedor;
use App\Exports\ProveedoresReporteExport;
use App\Http\Controllers\APIControllers\ProveedoresController as APIControllersProveedoresController;
use App\OpinionSatProveedor;
use App\RFCSatProveedor;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class ProveedoresController extends Controller
{
  /**
   * [Reglas para el guardado y la actualizacion de registros en la BD]
   */
  protected $rules = array('nombre' => 'required|max:255');

  /**
   * [Obtencion de datos del modelo Proveedor]
   * @param  Request $request [URL del GET]
   * @return Response           [Array en formato JSON]
   */
  public function index(Request $request)
  {
    $proveedor1 = Proveedor::leftJoin('evaluacion_provee AS ep', 'ep.proveedor_id', 'proveedores.id')
      ->select('proveedores.*', DB::raw("(ep.uno+ep.dos+ep.tres+ep.cuatro+ep.cinco+ep.seis+ep.siete+ep.ocho+ep.nueve+ep.diez+ep.once+ep.doce+ep.trece+ep.catorce+ep.quince+ep.diesiseis+ep.diesisiete+ep.diesiocho) AS total_eval"))
      ->orderBy('id', 'desc')->get()->toArray();
    $aux = [];
    foreach ($proveedor1 as $p)
    {
      $c = DB::table('evaluacion_provee')->where('proveedor_id', $p["id"])->first();
      $total = 0;
      if (isset($c) == true)
      {
        $total = $c->uno + $c->dos + $c->tres + $c->cuatro + $c->cinco + $c->seis + $c->siete + $c->ocho + $c->nueve + $c->diez + $c->once + $c->doce + $c->trece
          + $c->catorce + $c->quince + $c->diesiseis + $c->diesisiete + $c->diesiocho;
      }

      $banco = BancoProveedor::where("proveedor_id", "=", $p["id"])->get()->toArray();
      if (count($banco) > 0)
      {
        $p["banco_transferencia"] = $banco[0]["banco"];
        $p["numero_cuenta"] = $banco[0]["cuenta"];
        $p["clabe"] = $banco[0]["clabe"];
      }

      array_push($p, $banco);
      array_push($aux, $p);
    }
    return response()->json($aux);
  }

  public function proveedoresCFW()
  {
    try
    {

      $oc = DB::table('ordenes_compras')->select('proveedore_id')->groupBy('proveedore_id')->get()->toArray();
      $pro = [];
      foreach ($oc as $koc => $voc)
      {
        $pro[] = $voc->proveedore_id;
      }

      $proveedor1 = Proveedor::leftJoin('evaluacion_provee AS ep', 'ep.proveedor_id', 'proveedores.id')
        ->select('proveedores.*', DB::raw("(ep.uno+ep.dos+ep.tres+ep.cuatro+ep.cinco+ep.seis+ep.siete+ep.ocho+ep.nueve+ep.diez+ep.once+ep.doce+ep.trece+ep.catorce+ep.quince+ep.diesiseis+ep.diesisiete+ep.diesiocho) AS total_eval"))
        // ->whereIn('proveedores.id', $pro)
        ->orderBy('id', 'desc')->get()->toArray();
      $aux = [];
      foreach ($proveedor1 as $p)
      {
        $c = DB::table('evaluacion_provee')->where('proveedor_id', $p["id"])->first();
        $total = 0;
        if (isset($c) == true)
        {
          $total = $c->uno + $c->dos + $c->tres + $c->cuatro + $c->cinco + $c->seis + $c->siete + $c->ocho + $c->nueve + $c->diez + $c->once + $c->doce + $c->trece
            + $c->catorce + $c->quince + $c->diesiseis + $c->diesisiete + $c->diesiocho;
        }

        $banco = BancoProveedor::where("proveedor_id", "=", $p["id"])->get()->toArray();
        if (count($banco) > 0)
        {
          $p["banco_transferencia"] = $banco[0]["banco"];
          $p["numero_cuenta"] = $banco[0]["cuenta"];
          $p["clabe"] = $banco[0]["clabe"];
        }

        array_push($p, $banco);
        array_push($aux, $p);
      }
      return response()->json($aux);
    }
    catch (\Throwable $e)
    {
      Utilidades::errors($e);
    }
  }

  public function proveedoresActivos($id)
  {
    // code...
    $proveedor = Proveedor::select('proveedores.*')->where('condicion', '1')->orderBy('id', 'desc')->get();
    return response()->json($proveedor);
  }

  /**
   * [Guardado en la DB]
   * @param  Request $request [Objeto del POST]
   * @return Response           [Array con estatus true]
   */
  public function store(Request $request)
  {
    if (!$request->ajax()) return redirect('/');


    $validator = Validator::make($request->all(), $this->rules);

    $proveedor = new Proveedor();
    $proveedor->fill($this->CambioMayus($request->all()));
    // Utilidades::auditar($proveedor, $proveedor->id);
    $proveedor->save();
    // NOTE: se agrega un nuevo campo en credito donde se almacena el limite de credito del proveedor
    $credito = new \App\Credito();
    $credito->proveedor_id = $proveedor->id;
    $credito->monto_credito = $request->limite_credito;
    $credito->save();

    $s = [];
    $ms = [];
    // guardar bancos
    foreach ($request->lista_bancos as $banco)
    {
      if ($banco['temp'] == true)
      {
        array_push($ms, "nuevo");
        $nuevo = new BancoProveedor();
        $nuevo->banco = $banco["banco"];
        $nuevo->cuenta = $banco["cuenta"];
        $nuevo->clabe = $banco["clabe"];
        $nuevo->proveedor_id = $proveedor->id;
        $nuevo->save();
        array_push($s, $nuevo);
      }
      else
      {
        array_push($ms, "ignorar");
      }
    }
    return response()->json(array(
      'status' => true,
      "bancos" => $s,
    ));
  }

  /**
   * [Actualiza un registro buscado por un id en la base de datos ]
   * @param  Request $request [Objeto del PUT]
   * @return Response          [Array con estatus true ]
   */
  public function update(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    $validator = Validator::make($request->all(), $this->rules);
    // return $request->lista_bancos;
    if ($validator->fails())
    {
      return response()->json(array(
        'status' => false,
        'errors' => $validator->errors()->all()
      ));
    }

    $proveedor = Proveedor::findOrFail($request->id);

    $proveedor->fill($this->CambioMayus($request->all()));
    Utilidades::auditar($proveedor, $proveedor->id);
    $proveedor->save();

    $credito = \App\Credito::where('proveedor_id', '=', $proveedor->id)->first();
    if (isset($credito) == true)
    {
      $credito->proveedor_id = $proveedor->id;
      $credito->monto_credito = $request->limite_credito;
      $credito->save();
    }

    $bancos = $request->lista_bancos;
    // guardar bancos
    foreach ($bancos as $b)
    {

      if (array_key_exists("temp", $b))
      {
        $nuevo = new BancoProveedor();
        $nuevo->banco = $b["banco"];
        $nuevo->cuenta = $b["cuenta"];
        $nuevo->clabe = $b["clabe"];
        $nuevo->proveedor_id = $proveedor->id;
        $nuevo->save();
      }
      else
      {
        $actualizar = BancoProveedor::findOrFail($b["id"]);
        $actualizar->banco = $b["banco"];
        $actualizar->cuenta = $b["cuenta"];
        $actualizar->clabe = $b["clabe"];
        $actualizar->save();
      }
    }
    return response()->json(array(
      'status' => true,
    ));
  }

  /**
   * [Actualiza el campo condicion del proveedor buscado por id ]
   * @param Int    $id        [id del proveedor]
   * @return Array $proveedor [Datos del proveedor]
   */
  public function edit($id)
  {
    $proveedor = Proveedor::findOrFail($id);
    if ($proveedor->condicion == 0)
    {
      $proveedor->condicion = 1;
    }
    else
    {
      $proveedor->condicion = 0;
    }
    $proveedor->update();
    return $proveedor;
  }

  public function Cargar()
  {
    $proveedores = Proveedor::orderBy('id', 'desc')->get()->toArray();
    $bancos = [];
    foreach ($proveedores as $p)
    {
      $id = $p["id"];
      $exist = BancoProveedor::where("proveedor_id", "=", $p["id"])->get()->toArray();
      if (count($exist) == 0)
      {
        if ($p["banco_transferencia"] != null && $p["numero_cuenta"] != null && $p["clabe"] != null)
        {
          $banco = new BancoProveedor();
          $banco->banco = $p["banco_transferencia"];
          $banco->cuenta = $p["numero_cuenta"];
          $banco->clabe = $p["clabe"];
          $banco->proveedor_id = $p["id"];
          $banco->save();
        }
      }
    }
    return ["status" => true, "mensaje" => "Datos bancarios cargados"];
  }

  public function desactiva(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $proveedor = Proveedor::findOrFail($request->id);
    $proveedor->condicion = '0';
    $proveedor->save();
  }

  public function activar(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $proveedor = Proveedor::findOrFail($request->id);
    $proveedor->condicion = '1';
    $proveedor->save();
  }

  /**
   * [Obtiene el id y el nombre de todos los proveedores]
   * @param  Request $request [URL del GET]
   * @return Response         [Array en formato JSON]
   */
  public function getList(Request $request)
  {
    $proveedor = Proveedor::select('id', 'nombre')->orderBy('id', 'desc')->get()->toArray();
    return response()->json($proveedor);
  }

  /**
   * [Obtiene todos los registros del proveedor buscado]
   * @param  Int $id [id del provedor]
   * @return Response         [Array en formato JSON]
   */
  public function show($id)
  {
    $proveedor = Proveedor::select('proveedores.*')->where('id', $id)->orderBy('id', 'desc')->first();
    return response()->json($proveedor);
  }

  public function actualizarTipo(Request $request)
  {
    DB::beginTransaction();
    try
    {
      $dts = explode("&", $request->ids);
      $id = $dts[0];
      $tipo = $dts[1];
      $proveedor = Proveedor::findOrFail($id);
      $proveedor->tipo = $tipo;
      $proveedor->save();
      DB::commit();
      return response()->json(["success" => true]);
    }
    catch (\Exceptio $e)
    {
      DB::rollback();
      return response()->json(["success" => false, "mensaje" => $e->getMessage()]);
    }
  }

  protected function busqueda_filterByColumn($data, $queries)
  {
    $queries = json_decode($queries, true);

    return $data->where(function ($q) use ($queries)
    {
      foreach ($queries as $field => $query)
      {
        $_field = $field;

        if (is_string($query))
        {
          $q->where($_field, 'LIKE', "%{$query}%");
        }
        else
        {
          $start = Carbon::createFromFormat('Y-m-d', substr($query['start'], 0, 10))->startOfDay();
          $end = Carbon::createFromFormat('Y-m-d', substr($query['end'], 0, 10))->endOfDay();

          $q->whereBetween($_field, [$start, $end]);
        }
      }
    });
  }

  protected function busqueda_filter($data, $query, $fields)
  {
    return $data->where(function ($q) use ($query, $fields)
    {
      foreach ($fields as $index => $field)
      {
        $method = $index ? 'orWhere' : 'where';
        $q->{$method}($field, 'LIKE', "%{$query}%");
      }
    });
  }

  public function servertable()
  {
    extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));

    $data = $this->Proveedor1();

    if (isset($query) && $query)
    {
      $data = $byColumn == 1 ?
        $this->busqueda_filterByColumn($data, $query) :
        $this->busqueda_filter($data, $query, $fields);
    }

    $count = $data->count();

    $data->limit($limit)
      ->skip($limit * ($page - 1));

    if (isset($orderBy))
    {
      $direction = $ascending == 1 ? 'ASC' : 'DESC';
      $data->orderBy($orderBy, $direction);
    }
    // leftJoin('tipo_calidad AS TC','TC.id','=','articulos.calidad_id')
    // ->
    $results = $data->get();

    return [
      'data' => $results,
      'count' => $count,
    ];
  }

  public function Proveedor1()
  {
    $aux = Proveedor::orderBy('id', 'desc');
    return $aux;
  }

  public function getEvaluacion($id)
  {
    $proveedor = DB::table('evaluacion_provee')
      ->join('empleados AS e', 'e.id', 'evaluacion_provee.evaluador')
      ->select('evaluacion_provee.*', DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado"))
      ->where('proveedor_id', $id)->first();
    return response()->json($proveedor);
  }

  public function guardarEvaluacion(Request $request)
  {
    try
    {
      $evaluacion = new \App\EvaluacionPro();
      $evaluacion->fill($request->all());
      Utilidades::auditar($evaluacion, $evaluacion->id);
      $evaluacion->save();
      return response()->json(['status' => true]);
    }
    catch (\Throwable $e)
    {
      Utilidades::errors($e);
    }
  }

  public function descargarEvaluacion($id)
  {
    try
    {

      $evaluacion = DB::table('evaluacion_provee AS ep')
        ->join('empleados AS e', 'e.id', 'ep.evaluador')
        ->select('ep.*', DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado"))
        ->where('ep.proveedor_id', $id)->first();

      $calificacion = 0;
      $categoria = '';
      if (isset($evaluacion) == true)
      {
        $calificacion = $evaluacion->uno + $evaluacion->dos + $evaluacion->tres + $evaluacion->cuatro + $evaluacion->cinco + $evaluacion->seis + $evaluacion->siete +
          $evaluacion->ocho + $evaluacion->nueve + $evaluacion->diez + $evaluacion->once + $evaluacion->doce + $evaluacion->trece + $evaluacion->catorce + $evaluacion->quince +
          $evaluacion->diesiseis + $evaluacion->diesisiete + $evaluacion->diesiocho;
        if ($calificacion >= 54)
        {
          $categoria = 'IMPORTANTE';
        }
        if ($calificacion >= 36 && $calificacion <= 53)
        {
          $categoria = 'NO CRÍTICO';
        }
        if ($calificacion <= 35)
        {
          $categoria = 'CRÍTICO';
        }
      }
      $proveedor = DB::table('proveedores AS p')->where('id', $id)->first();


      $pdf = PDF::loadView('pdf.evaluacion', compact('id', 'evaluacion', 'proveedor', 'calificacion', 'categoria'));
      $pdf->setPaper('A4', 'portrait');

      return $pdf->stream();
    }
    catch (\Throwable $e)
    {
      Utilidades::errors($e);
      return view('errors.204');
    }
  }

  /**
   * Valida el documento de RFC del portal de proveedores
   */
  public function ObtenerRFCPendientes()
  {
    $opiniones = DB::table("validacion_opinion_proveedores as vop")
      ->join("proveedores as p", "p.id", "vop.proveedor_id")
      ->where("vop.estado", "=", "0")
      ->select(
        "vop.*",
        "p.nombre",
        "p.rfc",
        DB::raw("1 as tipo")
      )->get()->toArray();

    $sat = DB::table("validacion_rfc_proveedores as vrp")
      ->join("proveedores as p", "p.id", "vrp.proveedor_id")
      ->where("vrp.estado", "=", "0")
      ->select(
        "vrp.*",
        "p.nombre",
        "p.rfc",
        DB::raw("2 as tipo")
      )->get()->toArray();

    $datos = array_merge($opiniones, $sat);
    return response()->json(["status" => true, "documentos" => $datos]);
  }

  /**
   *
   */
  public function AceptarDocumento(Request $request)
  {
    try
    {
      $documento = null;
      if ($request->tipo == 1) // Opinión
      {
        $documento = OpinionSatProveedor::find($request->id_documento);
      }
      else // SAT
      {
        $documento = RFCSatProveedor::find($request->id_documento);
      }
      $documento->estado = 1; // Rechazado
      $documento->aviso = "Aceptado";
      $documento->save();
      return response()->json(["status" => true]);
    }
    catch (Exception $e)
    {
      Utilidades::errors($e);
      return response()->json(["status" => false, "mensaje" => "Error al guardar estado"]);
    }
  }

  /**
   * Rechza el documento ingresado
   */
  public function RechazarDocumento(Request $request)
  {
    try
    {
      $documento = null;
      if ($request->tipo == 1) // Opinión
      {
        $documento = OpinionSatProveedor::find($request->id_documento);
      }
      else // SAT
      {
        $documento = RFCSatProveedor::find($request->id_documento);
      }
      $documento->estado = 2; // Rechazado
      $documento->aviso = $request->mensaje;
      $documento->save();
      return response()->json(["status" => true]);
    }
    catch (Exception $e)
    {
      Utilidades::errors($e);
      return response()->json(["status" => false, "mensaje" => "Error al guardar estado"]);
    }
  }

  /**
   * Descarga el RFC del proveedore
   */
  public function DescargarRFC($id)
  {
    try
    {
      $documento = DB::table("validacion_rfc_proveedores as vrp")
        ->join("proveedores as p", "p.id", "vrp.proveedor_id")
        ->select(
          "vrp.*",
          "p.id as p_id",
          "p.rfc"
        )
        ->where("vrp.id", $id)
        ->first();

      // obtener proveedore para rfc
      $controll = new APIControllersProveedoresController();
      $has_proveedor = $controll->GetHashProveedor($documento->rfc);

      // Documento
      $nombre = $documento->documento;
      $ruta = storage_path('app' . DIRECTORY_SEPARATOR . "Archivos" .
        DIRECTORY_SEPARATOR . "FacturasProveedores" . DIRECTORY_SEPARATOR
        . $has_proveedor . DIRECTORY_SEPARATOR . $nombre);
      // Se coloca el archivo en una ruta local
      if (!file_exists($ruta))
      {
        return  "<h3>Archivo no encontrado</h3>";
      }
      return response()->download($ruta, $nombre, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $nombre . '"'
      ]);
    }
    catch (Exception $e)
    {
      Utilidades::errors($e);
      echo "<h4> Error al obtener el documento </h4>";
    }
  }

  /**
   * Decarga la opinión
   */
  public function DescargarOpinion($id)
  {
    try
    {
      $documento = DB::table("validacion_opinion_proveedores as vrp")
        ->join("proveedores as p", "p.id", "vrp.proveedor_id")
        ->select(
          "vrp.*",
          "p.id as p_id",
          "p.rfc"
        )
        ->where("vrp.id", $id)
        ->first();

      // obtener proveedore para rfc
      $controll = new APIControllersProveedoresController();
      $has_proveedor = $controll->GetHashProveedor($documento->rfc);

      // Documento
      $nombre = $documento->documento;
      $ruta = storage_path('app' . DIRECTORY_SEPARATOR . "Archivos" .
        DIRECTORY_SEPARATOR . "FacturasProveedores" . DIRECTORY_SEPARATOR
        . $has_proveedor . DIRECTORY_SEPARATOR . $nombre);
      // Se coloca el archivo en una ruta local
      if (!file_exists($ruta))
      {
        return  "<h3>Archivo no encontrado</h3>";
      }
      return response()->download($ruta, $nombre, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $nombre . '"'
      ]);
    }
    catch (Exception $e)
    {
      Utilidades::errors($e);
      echo "<h4> Error al obtener el documento </h4>";
    }
  }
  public function getDataBankProveedor($id)
  {
    $datos_bancarios = DB::table('bancos_proveedores AS bp')
      ->where('proveedor_id', $id)->get();

    return response()->json($datos_bancarios);
  }
  public function DescargarReporte()
  {
    return Excel::download(new ProveedoresReporteExport(), 'PCO-02_F-02 Catálogo de proveedores.xlsx');
  }

  public function CambioMayus($proveedor)
  {
    $proveedor["nombre"] = $this->QuitarAcentos($proveedor["nombre"]);
    $proveedor["razon_social"] = $this->QuitarAcentos($proveedor["razon_social"]);
    $proveedor["direccion"] = $this->QuitarAcentos($proveedor["direccion"]);
    $proveedor["giro"] = $this->QuitarAcentos($proveedor["giro"]);
    $proveedor["contacto"] = $this->QuitarAcentos($proveedor["contacto"]);
    $proveedor["descripcion"] = $this->QuitarAcentos($proveedor["descripcion"]);
    //    $proveedor["regimen_fiscal"] = $this->QuitarAcentos($proveedor["regimen_fiscal"]);
    return $proveedor;
  }

  public function QuitarAcentos($cadena)
  {
    $no_permitidas = [
      "á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì",
      "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®",
      "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«",
      "Ò", "Ã", "Ã„", "Ã‹"
    ];
    $permitidas = [
      "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N",
      "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i",
      "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E"
    ];
    $texto = strtoupper(str_replace($no_permitidas, $permitidas, $cadena));
    return $texto;
  }
}
