<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;

class TraficoEntregaRecepcionController extends Controller
{
  //
  public function Guardar(Request $request)
  {
    try {
      // return response()->json(gettype(json_encode($request->accesorios)));
      DB::beginTransaction();
      $data = new \App\TraficoEntregaRecepcion();
      $data->accesorios = json_encode($request->accesorios);
      $data->accesorios_e = json_encode($request->accesorios_e);
      $data->accesorios_i = json_encode($request->accesorios_i);
      $data->carroceria = json_encode($request->carroceria);
      $data->cofre = json_encode($request->cofre);
      $data->datos = json_encode($request->datos);
      $data->funcionamiento = json_encode($request->funcionamiento);
      $data->llantas = json_encode($request->llantas);
      $data->luces = json_encode($request->luces);
      $data->niveles = json_encode($request->niveles);
      $data->entrega = $request['personal']['entrega']['id'];
      $data->recibe = $request['personal']['recibe']['id'];
      $data->usuario = $request['usuario']['nombre']['id'];
      $data->tipo = 1;
      $data->observaciones = $request->observaciones;
      Utilidades::auditar($data, $data->id);
      $data->save();

      foreach ($request->imagenes as $key => $value) {
        $imagenes = new \App\TraficoEntregaRecepcionImg();
        $imagenes->unidades_ent_rep_id = $data->id;
        $imagenes->nombre = $this->gua($value['name']);
        Utilidades::auditar($imagenes, $imagenes->id);
        $imagenes->save();
      }
      DB::commit();
      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      DB::rollBack();
      Utilidades::errors($e);
    }

  }
  public function GuardarRep(Request $request)
  {
    try {
      // return response()->json(gettype(json_encode($request->accesorios)));
      DB::beginTransaction();

      $data_rel = \App\TraficoEntregaRecepcion::where('id',$request->id_rel)->first();
      $data_rel->condicion = 2;
      $data_rel->save();

      $data = new \App\TraficoEntregaRecepcion();
      $data->accesorios = json_encode($request->accesorios);
      $data->accesorios_e = json_encode($request->accesorios_e);
      $data->accesorios_i = json_encode($request->accesorios_i);
      $data->carroceria = json_encode($request->carroceria);
      $data->cofre = json_encode($request->cofre);
      $data->datos = json_encode($request->datos);
      $data->funcionamiento = json_encode($request->funcionamiento);
      $data->llantas = json_encode($request->llantas);
      $data->luces = json_encode($request->luces);
      $data->niveles = json_encode($request->niveles);
      $data->entrega = $request['personal']['entrega']['id'];
      $data->recibe = $request['personal']['recibe']['id'];
      $data->usuario = $request['usuario']['nombre']['id'];
      $data->tipo = 2;
      $data->observaciones = $request->observaciones;
      $data->id_rel = $request->id_rel;
      Utilidades::auditar($data, $data->id);
      $data->save();

      foreach ($request->imagenes as $key => $value) {
        $imagenes = new \App\TraficoEntregaRecepcionImg();
        $imagenes->unidades_ent_rep_id = $data->id;
        $imagenes->nombre = $this->gua($value['name']);
        Utilidades::auditar($imagenes, $imagenes->id);
        $imagenes->save();
      }
      DB::commit();
      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      DB::rollBack();
      Utilidades::errors($e);
    }

  }

  public function Actualizar(Request $request)
  {
    try {
      // return response()->json(gettype(json_encode($request->accesorios)));
      DB::beginTransaction();
      $data = \App\TraficoEntregaRecepcion::where('id',$request->id)->first();
      $data->accesorios = json_encode($request->accesorios);
      $data->accesorios_e = json_encode($request->accesorios_e);
      $data->accesorios_i = json_encode($request->accesorios_i);
      $data->carroceria = json_encode($request->carroceria);
      $data->cofre = json_encode($request->cofre);
      $data->datos = json_encode($request->datos);
      $data->funcionamiento = json_encode($request->funcionamiento);
      $data->llantas = json_encode($request->llantas);
      $data->luces = json_encode($request->luces);
      $data->niveles = json_encode($request->niveles);
      $data->entrega = $request['personal']['entrega']['id'];
      $data->recibe = $request['personal']['recibe']['id'];
      $data->usuario = $request['usuario']['nombre']['id'];
      $data->tipo = 1;
      $data->observaciones = $request->observaciones;
      Utilidades::auditar($data, $data->id);
      $data->save();

      foreach ($request->imagenes as $key => $value) {
        if ($value['id'] == 0) {
          $imagenes = new \App\TraficoEntregaRecepcionImg();
          $imagenes->unidades_ent_rep_id = $data->id;
          $imagenes->nombre = $this->gua($value['name']);
          Utilidades::auditar($imagenes, $imagenes->id);
          $imagenes->save();
        }
      }
      DB::commit();
      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      DB::rollBack();
      Utilidades::errors($e);
    }

  }

  public function ActualizarRep(Request $request)
  {
    try {
      // return response()->json(gettype(json_encode($request->accesorios)));
      DB::beginTransaction();
      $data = \App\TraficoEntregaRecepcion::where('id',$request->id)->first();
      $data->accesorios = json_encode($request->accesorios);
      $data->accesorios_e = json_encode($request->accesorios_e);
      $data->accesorios_i = json_encode($request->accesorios_i);
      $data->carroceria = json_encode($request->carroceria);
      $data->cofre = json_encode($request->cofre);
      $data->datos = json_encode($request->datos);
      $data->funcionamiento = json_encode($request->funcionamiento);
      $data->llantas = json_encode($request->llantas);
      $data->luces = json_encode($request->luces);
      $data->niveles = json_encode($request->niveles);
      $data->entrega = $request['personal']['entrega']['id'];
      $data->recibe = $request['personal']['recibe']['id'];
      $data->usuario = $request['usuario']['nombre']['id'];
      // $data->tipo = 2;
      $data->observaciones = $request->observaciones;
      // $data->id_rel = $request->id_rel;
      Utilidades::auditar($data, $data->id);
      $data->save();

      foreach ($request->imagenes as $key => $value) {
        if ($value['id'] == 0) {
          $imagenes = new \App\TraficoEntregaRecepcionImg();
          $imagenes->unidades_ent_rep_id = $data->id;
          $imagenes->nombre = $this->gua($value['name']);
          Utilidades::auditar($imagenes, $imagenes->id);
          $imagenes->save();
        }
      }
      DB::commit();
      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      DB::rollBack();
      Utilidades::errors($e);
    }

  }

  public function get()
  {
    $data = \App\TraficoEntregaRecepcion::
    join('empleados AS ee','ee.id','entrega_recepcion_vehiculos.entrega')
    ->join('empleados AS er','er.id','entrega_recepcion_vehiculos.recibe')
    ->join('empleados AS eu','eu.id','entrega_recepcion_vehiculos.usuario')
    ->select(
      'entrega_recepcion_vehiculos.*',
      DB::raw("CONCAT(ee.nombre,' ',ee.ap_paterno,' ',ee.ap_materno) AS nombre_entrega"),
      DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS nombre_recibe"),
      DB::raw("CONCAT(eu.nombre,' ',eu.ap_paterno,' ',eu.ap_materno) AS nombre_usuario")
      )
    ->where('entrega_recepcion_vehiculos.tipo','1')
    ->get();
    $arreglo = [];
    foreach ($data as $key => $value) {
      $datos = json_decode($value['datos']);

      $arreglo [] = [
        'data' => $value,
        'unidad' => $datos->unidad->marca.' '.$datos->unidad->modelo.' '.$datos->unidad->numero_serie,' '.$datos->unidad->unidad,
        'placas' => $datos->unidad->placas,
        'proyecto' => $datos->proyecto->name,
        'fecha_entrega' => $datos->fecha_entrega,
      ];
    }

    return response()->json($arreglo);
  }

  public function getRecepcion($id)
  {
    $arreglo = [];
    $data = \App\TraficoEntregaRecepcion::
    join('empleados AS ee','ee.id','entrega_recepcion_vehiculos.entrega')
    ->join('empleados AS er','er.id','entrega_recepcion_vehiculos.recibe')
    ->join('empleados AS eu','eu.id','entrega_recepcion_vehiculos.usuario')
    ->select(
      'entrega_recepcion_vehiculos.*',
      DB::raw("CONCAT(ee.nombre,' ',ee.ap_paterno,' ',ee.ap_materno) AS nombre_entrega"),
      DB::raw("CONCAT(ee.nombre,' ',ee.ap_paterno,' ',ee.ap_materno) AS nombre_recibe"),
      DB::raw("CONCAT(ee.nombre,' ',ee.ap_paterno,' ',ee.ap_materno) AS nombre_usuario")
      )
    ->where('id_rel',$id)
    ->first();
    return response()->json($data);

    $datos = json_decode($data->datos);


      $arreglo = [
        'data' => $data,
        'unidad' => $datos->unidad->marca.' '.$datos->unidad->modelo.' '.$datos->unidad->numero_serie,' '.$datos->unidad->unidad,
        'placas' => $datos->unidad->placas,
        'proyecto' => $datos->proyecto->name,
        'fecha_entrega' => $datos->fecha_entrega,
      ];


    return response()->json($arreglo);
  }

  public function getImg($id)
  {
    try {
      $img = DB::table('entrega_recepcion_vehiculos_imagenes AS ervi')->where('ervi.unidades_ent_rep_id',$id)
      ->where('ervi.condicion','1')->get();
      $arreglo = [];
      foreach ($img as $key => $value) {

        $img_u = Storage::disk('local')->get('Trafico/'.$value->nombre);
        $type = explode('.',$value->nombre);
        $base64 = 'data:image/' . $type[1] . ';base64,' .base64_encode($img_u);

        $arreglo [] = [
          'id' => $value->id,
          'id_rel' => $value->unidades_ent_rep_id,
          'img' => $base64,
        ];
      }
      return response()->json($arreglo);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function deleteImg($id)
  {
    try {
      $img = \App\TraficoEntregaRecepcionImg::where('id',$id)->first();
      $img->condicion = 0;
      Utilidades::auditar($img, $img->id);
      $img->save();

      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
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


      Storage::disk('local')->put('Trafico/'.$imageName, base64_decode($image));

      // return response()->json(['status' => true]);
      return $imageName;

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }

  public function Descargar($id)
  {
    try {
      $arreglo = [];
      $data = \App\TraficoEntregaRecepcion::
      join('empleados AS ee','ee.id','entrega_recepcion_vehiculos.entrega')
      ->join('empleados AS er','er.id','entrega_recepcion_vehiculos.recibe')
      ->join('empleados AS eu','eu.id','entrega_recepcion_vehiculos.usuario')
      ->select(
        'entrega_recepcion_vehiculos.*',
        DB::raw("CONCAT(ee.nombre,' ',ee.ap_paterno,' ',ee.ap_materno) AS nombre_entrega"),
        DB::raw("CONCAT(ee.nombre,' ',ee.ap_paterno,' ',ee.ap_materno) AS nombre_recibe"),
        DB::raw("CONCAT(ee.nombre,' ',ee.ap_paterno,' ',ee.ap_materno) AS nombre_usuario")
        )
      ->where('entrega_recepcion_vehiculos.id',$id)
      ->first();

      $data_r = \App\TraficoEntregaRecepcion::
      join('empleados AS ee','ee.id','entrega_recepcion_vehiculos.entrega')
      ->join('empleados AS er','er.id','entrega_recepcion_vehiculos.recibe')
      ->join('empleados AS eu','eu.id','entrega_recepcion_vehiculos.usuario')
      ->select(
        'entrega_recepcion_vehiculos.*',
        DB::raw("CONCAT(ee.nombre,' ',ee.ap_paterno,' ',ee.ap_materno) AS nombre_entrega"),
        DB::raw("CONCAT(ee.nombre,' ',ee.ap_paterno,' ',ee.ap_materno) AS nombre_recibe"),
        DB::raw("CONCAT(ee.nombre,' ',ee.ap_paterno,' ',ee.ap_materno) AS nombre_usuario")
        )
      ->where('entrega_recepcion_vehiculos.id_rel',$id)
      ->first();

      $img = DB::table('entrega_recepcion_vehiculos_imagenes AS ervi')->where('ervi.unidades_ent_rep_id',$data->id)
      ->where('ervi.condicion','1')->get();
      $arreglo_img = [];
      foreach ($img as $key => $value) {
        $img_u = Storage::disk('local')->get('Trafico/'.$value->nombre);
        $type = explode('.',$value->nombre);
        $base64 = 'data:image/' . $type[1] . ';base64,' .base64_encode($img_u);
        $arreglo_img [] = $base64;
      }

      $datos = json_decode($data->datos);
      $accesorios_e = json_decode($data->accesorios_e);
      $accesorios_i = json_decode($data->accesorios_i);
      $accesorios = json_decode($data->accesorios);
      $niveles = json_decode($data->niveles);
      $carroceria = json_decode($data->carroceria);
      $funcionamiento = json_decode($data->funcionamiento);
      $cofre = json_decode($data->cofre);
      $llantas = json_decode($data->llantas);
      $luces = json_decode($data->luces);

      $accesorios_e_r = null;
      $accesorios_i_r = null;
      $accesorios_r = null;
      $niveles_r = null;
      $carroceria_r = null;
      $funcionamiento_r = null;
      $cofre_r = null;
      $llantas_r = null;
      $luces_r = null;
      $arreglo_img_r = [];
      if (isset($data_r) == true) {
        $datos_r = json_decode($data_r->datos);
        $accesorios_e_r = json_decode($data_r->accesorios_e);
        $accesorios_i_r = json_decode($data_r->accesorios_i);
        $accesorios_r = json_decode($data_r->accesorios);
        $niveles_r = json_decode($data_r->niveles);
        $carroceria_r = json_decode($data_r->carroceria);
        $funcionamiento_r = json_decode($data_r->funcionamiento);
        $cofre_r = json_decode($data_r->cofre);
        $llantas_r = json_decode($data_r->llantas);
        $luces_r = json_decode($data_r->luces);
        $img = DB::table('entrega_recepcion_vehiculos_imagenes AS ervi')->where('ervi.unidades_ent_rep_id',$data_r->id)
        ->where('ervi.condicion','1')->get();
        foreach ($img as $key => $value) {
          $img_u = Storage::disk('local')->get('Trafico/'.$value->nombre);
          $type = explode('.',$value->nombre);
          $base64 = 'data:image/' . $type[1] . ';base64,' .base64_encode($img_u);
          $arreglo_img_r [] = $base64;
        }
      }

      $poliza = DB::table('poliza_unidades')->where('unidad_id',$datos->unidad->id)->orderBy('id','DESC')->first();
      $conductor = DB::table('choferes AS c')->join('empleados AS e','e.id','c.nombre')
      ->select('c.*',DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',ap_materno) AS nombre_empleado"))
      ->where('c.nombre',$data->usuario)
      ->orderBy('vigencia','DESC')->first();

        $arreglo = [
          // 'data' => $data,
          'unidad' => $datos->unidad->unidad,
          'modelo' => $datos->unidad->modelo,
          'numero_tarjeta_circulacion' => $datos->unidad->numero_tarjeta_circulacion,
          'numero_serie' => $datos->unidad->numero_serie,
          'placas' => $datos->unidad->placas,
          'numero_poliza_seguro' => $poliza->numero_poliza,
          'fecha_entrega' => $datos->fecha_entrega,
          'vigencia_poliza' => $poliza->fecha_termino,
          'kilometraje_entrega' => $datos->kilometraje_entrega,
          'lugar_entrega' => $datos->lugar_entrega,
          'proyecto' => $datos->proyecto->name,
          'destinatario' => $datos->destinatario,
          //////
          'fecha_recepcion' => isset($data_r) == false ? '' : $datos_r->fecha_entrega,
          'kilometraje_recepcion' => isset($data_r) == false ? '' : $datos_r->kilometraje_entrega,
          'lugar_recepcion' => isset($data_r) == false ? '' : $datos_r->lugar_entrega,
        ];

        $pdf = PDF::loadView('pdf.entregarecepcionv', compact('arreglo','data','data_r',
        'accesorios_e','accesorios_i','niveles','carroceria','funcionamiento','accesorios','cofre','llantas','luces',
        'accesorios_e_r','accesorios_i_r','niveles_r','carroceria_r','funcionamiento_r','accesorios_r','cofre_r','llantas_r','luces_r',
        'conductor','arreglo_img_r','arreglo_img'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper("letter", "portrait");
        return $pdf->stream();

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }



}
