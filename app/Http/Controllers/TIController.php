<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Helpers\Utilidades;
use App\TiAccesorio;
use Illuminate\Support\Facades\DB;
use App\TiMaterialResguardo;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;
use Exception;

class TIController extends Controller
{

  public function getGral(Request $request)
  {
    $cdata_uno = \App\TiComputo::where('no_serie', 'LIKE', '%' . $request->des . '%')
    ->orWhere('marca_modelo', 'LIKE', '%' . $request->des . '%')
    ->orWhere('cpu', 'LIKE', '%' . $request->des . '%')
    ->orWhere('ram', 'LIKE', '%' . $request->des . '%')
    ->orWhere('almacenamiento', 'LIKE', '%' . $request->des . '%')
    ->orWhere('tarjeta_video', 'LIKE', '%' . $request->des . '%')
    ->orWhere('tarjeta_red', 'LIKE', '%' . $request->des . '%')
    ->orWhere('observaciones', 'LIKE', '%' . $request->des . '%')
    ->orWhere('mac', 'LIKE', '%' . $request->des . '%')
    ->where('cantidad', '>', '0')
    ->select('id', DB::raw("CONCAT(no_serie,' ',marca_modelo,' ',cpu,' ',ram,' ',almacenamiento,' ',tarjeta_video,' ',tarjeta_red,' ',observaciones,' ',mac) AS descripcion"), 'cantidad')
    ->get()->toArray();

    $cdata_dos = \App\TiAccesorio::where('descripcion', 'LIKE', '%' . $request->des . '%')
    ->orWhere('modelo', 'LIKE', '%' . $request->des . '%')
    ->orWhere('marca', 'LIKE', '%' . $request->des . '%')
    ->orWhere('no_serie', 'LIKE', '%' . $request->des . '%')
    ->where('cantidad', '>', '0')
    ->select('id', DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS descripcion"), 'cantidad')
    ->get()->toArray();

    $union_uno = array_merge($cdata_uno, $cdata_dos);

    $cdata_tres = \App\TiImpresion::where('descripcion', 'LIKE', '%' . $request->des . '%')
    ->orWhere('modelo', 'LIKE', '%' . $request->des . '%')
    ->orWhere('marca', 'LIKE', '%' . $request->des . '%')
    ->orWhere('no_serie', 'LIKE', '%' . $request->des . '%')
    ->where('cantidad', '>', '0')
    ->select('id', DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS descripcion"), 'cantidad')
    ->get()->toArray();

    $union_dos = array_merge($union_uno, $cdata_tres);

    $cdata_cuatro = \App\TiVideo::where('descripcion', 'LIKE', '%' . $request->des . '%')
    ->orWhere('no_serie', 'LIKE', '%' . $request->des . '%')
    ->where('cantidad', '>', '0')
    ->select('id', DB::raw("CONCAT(descripcion,' ',no_serie) AS descripcion"), 'cantidad')
    ->get()->toArray();

    $union_final = array_merge($union_dos, $cdata_cuatro);

    return response()->json($union_final);
  }

  /**
  * Obtiene los equipos
  */
  public function getPorTipo(Request $request)
  {
    try{
      if ($request->tipo == 1){
        // Equipo de cómputo que contenga la descripción ingresada y que esté activo
        $cdata = \App\TiComputo::where(function ($query) use ($request)
        {
          $query->Where('marca_modelo', 'LIKE', '%' . $request->des . '%')
          ->Orwhere('no_serie', 'LIKE', '%' . $request->des . '%')
          ->orWhere('cpu', 'LIKE', '%' . $request->des . '%')
          ->orWhere('ram', 'LIKE', '%' . $request->des . '%')
          ->orWhere('almacenamiento', 'LIKE', '%' . $request->des . '%')
          ->orWhere('tarjeta_video', 'LIKE', '%' . $request->des . '%')
          ->orWhere('tarjeta_red', 'LIKE', '%' . $request->des . '%')
          ->orWhere('observaciones', 'LIKE', '%' . $request->des . '%')
          ->orWhere('mac', 'LIKE', '%' . $request->des . '%')
          ;
        })
        ->where('condicion','>','0')
        ->where("eliminado", 1)
        ->where('cantidad', '>', '0')
        ->select(
          'id',
          'marca_modelo AS marca',
          'marca_modelo AS modelo',
          'no_serie',
          DB::raw("CONCAT(no_serie,' ',marca_modelo,' ',cpu,' ',ram,' ',almacenamiento,' ',tarjeta_video,' ',tarjeta_red,' ',observaciones,' ',mac) AS descripcion"),
          'cantidad'
          )
          ->get();
        }

        if ($request->tipo == 2){ //Acesorios
          $cdata = TiAccesorio::where(function ($query) use ($request)
          {
            $query->where('descripcion', 'LIKE', '%' . $request->des . '%')
            ->orWhere('modelo', 'LIKE', '%' . $request->des . '%')
            ->orWhere('marca', 'LIKE', '%' . $request->des . '%')
            ->orWhere('no_serie', 'LIKE', '%' . $request->des . '%');
          })
          ->where('cantidad', '>', '0')
          ->where('condicion','>','0')
          ->where("eliminado", 1)
          // ->where("condicion",1)
          ->select('id', DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS descripcion"), 'cantidad')
          ->get();
        }

        if ($request->tipo == 3){ //Impresion
          $cdata = \App\TiImpresion::where(function ($query) use ($request)
          {
            $query->where('descripcion', 'LIKE', '%' . $request->des . '%')
            ->orWhere('modelo', 'LIKE', '%' . $request->des . '%')
            ->orWhere('marca', 'LIKE', '%' . $request->des . '%')
            ->orWhere('no_serie', 'LIKE', '%' . $request->des . '%');
          })
          ->where('cantidad', '>', '0')
          ->where('condicion','>','0')
          ->where("eliminado", 1)
          ->select(
            'id',
            'marca',
            'modelo',
            'no_serie',
            DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS descripcion"),
            'cantidad'
            )
            ->get();
          }

          if ($request->tipo == 4){ //Video
            $cdata = \App\TiVideo::where(function ($query) use ($request)
            {
              $query->where('descripcion', 'LIKE', '%' . $request->des . '%')
              ->orWhere('no_serie', 'LIKE', '%' . $request->des . '%');
            })
            ->where('cantidad', '>', '0')
            ->where('condicion','>','0')
            ->where("eliminado", 1)
            // ->where('condicion',1)
            ->select('id', DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS descripcion"), 'cantidad')
            ->get();
          }

          return response()->json($cdata);
        }catch (\Throwable $e){
          Utilidades::errors($e);
        }
      }

      /**
      * Obtiene los equipos
      */
      public function getPorTipoPrograma(Request $request)
      {
        try{
          if ($request->tipo == 1){
            // Equipo de cómputo que contenga la descripción ingresada y que esté activo
            $cdata = \App\TiComputo::where(function ($query) use ($request)
            {
              $query->Where('marca_modelo', 'LIKE', '%' . $request->des . '%')
              ->Orwhere('no_serie', 'LIKE', '%' . $request->des . '%')
              ->orWhere('cpu', 'LIKE', '%' . $request->des . '%')
              ->orWhere('ram', 'LIKE', '%' . $request->des . '%')
              ->orWhere('almacenamiento', 'LIKE', '%' . $request->des . '%')
              ->orWhere('tarjeta_video', 'LIKE', '%' . $request->des . '%')
              ->orWhere('tarjeta_red', 'LIKE', '%' . $request->des . '%')
              ->orWhere('observaciones', 'LIKE', '%' . $request->des . '%')
              ->orWhere('mac', 'LIKE', '%' . $request->des . '%')
              ;
            })
            // ->where("condicion", 1)
            // ->where('cantidad', '>', '0')
            ->select(
              'id',
              'marca_modelo AS marca',
              'marca_modelo AS modelo',
              'no_serie',
              DB::raw("CONCAT(no_serie,' ',marca_modelo,' ',cpu,' ',ram,' ',almacenamiento,' ',tarjeta_video,' ',tarjeta_red,' ',observaciones,' ',mac) AS descripcion"),
              'cantidad'
              )
              ->get();
            }

            if ($request->tipo == 2){ //Acesorios
              $cdata = TiAccesorio::where(function ($query) use ($request)
              {
                $query->where('descripcion', 'LIKE', '%' . $request->des . '%')
                ->orWhere('modelo', 'LIKE', '%' . $request->des . '%')
                ->orWhere('marca', 'LIKE', '%' . $request->des . '%')
                ->orWhere('no_serie', 'LIKE', '%' . $request->des . '%');
              })
              // ->where('cantidad', '>', '0')
              // ->where("condicion",1)
              ->select('id', DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS descripcion"), 'cantidad')
              ->get();
            }

            if ($request->tipo == 3){ //Impresion
              $cdata = \App\TiImpresion::where(function ($query) use ($request)
              {
                $query->where('descripcion', 'LIKE', '%' . $request->des . '%')
                ->orWhere('modelo', 'LIKE', '%' . $request->des . '%')
                ->orWhere('marca', 'LIKE', '%' . $request->des . '%')
                ->orWhere('no_serie', 'LIKE', '%' . $request->des . '%');
              })
              // ->where('cantidad', '>', '0')
              // ->where('condicion',1)
              ->select(
                'id',
                'marca',
                'modelo',
                'no_serie',
                DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS descripcion"),
                'cantidad'
                )
                ->get();
              }

              if ($request->tipo == 4){ //Video
                $cdata = \App\TiVideo::where(function ($query) use ($request)
                {
                  $query->where('descripcion', 'LIKE', '%' . $request->des . '%')
                  ->orWhere('no_serie', 'LIKE', '%' . $request->des . '%');
                })
                // ->where('cantidad', '>', '0')
                // ->where('condicion',1)
                ->select('id', DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS descripcion"), 'cantidad')
                ->get();
              }

              return response()->json($cdata);
            }catch (\Throwable $e){
              Utilidades::errors($e);
            }
          }

      public function Guardar(Request $request)
      {
        try{
          // return response()->json($request);
          $user = Auth::user();
          DB::beginTransaction();

          $resguardo = new TiMaterialResguardo();
          $resguardo->fecha = $request->fecha;
          $resguardo->caiv = $request->caiv;
          $resguardo->tipo = $request->tipo;
          $resguardo->acesorios = $request->acesorios;
          $resguardo->observacion_uno = $request->observacion_uno;
          $resguardo->observacion_dos = $request->observacion_dos;
          $resguardo->empleado_entrega = $request->empleado_entrega;
          $resguardo->empleado_recibe = $request->empleado_recibe;
          $resguardo->cantidad = $request->cantidad;
          $resguardo->empresa = $request->empresa;
          Utilidades::auditar($resguardo, $resguardo->id);
          $resguardo->save();

          /////
          if ($request->tipo == 1){
            $cdata = \App\TiComputo::where('id', $request->caiv)->first();
            $total = $cdata->cantidad - $request->cantidad;
            $cdata->cantidad = $total;
            $cdata->condicion = 2;
            if ($total < 0){
              return response()->json(['status' => true, 'estado' => 2]);
            }
            Utilidades::auditar($cdata, $cdata->id);
            $cdata->update();
          }
          if ($request->tipo == 2){
            $cdata = \App\TiAccesorio::where('id', $request->caiv)->first();
            $total = $cdata->cantidad - $request->cantidad;
            $cdata->cantidad = $total;
            $cdata->condicion = 2;
            if ($total < 0){
              return response()->json(['status' => true, 'estado' => 2]);
            }
            Utilidades::auditar($cdata, $cdata->id);
            $cdata->update();
          }
          if ($request->tipo == 3){
            $cdata = \App\TiImpresion::where('id', $request->caiv)->first();
            $total = $cdata->cantidad - $request->cantidad;
            $cdata->cantidad = $total;
            $cdata->condicion = 2;
            if ($total < 0){
              return response()->json(['status' => true, 'estado' => 2]);
            }
            Utilidades::auditar($cdata, $cdata->id);
            $cdata->update();
          }
          if ($request->tipo == 4){
            $cdata = \App\TiVideo::where('id', $request->caiv)->first();
            $total = $cdata->cantidad - $request->cantidad;
            $cdata->cantidad = $total;
            $cdata->condicion = 2;
            if ($total < 0){
              return response()->json(['status' => true, 'estado' => 2]);
            }
            Utilidades::auditar($cdata, $cdata->id);
            $cdata->update();
          }
          //////

          ///SE REGITRA LOS ACCESORIOS
          foreach ($request->accesorios as $key_a => $value_a) {
            $adata = \App\TiAccesorio::where('id', $value_a['id'])->first();
            $total = $adata->cantidad - $value_a['cantidad'];
            $adata->cantidad = $total;
            $adata->condicion = 2;
            $adata->update();
          }

          $resguardo->accesorios_listado = json_encode($request->accesorios);
          $resguardo->save();

          DB::commit();
          return response()->json(['status' => true, 'estado' => 1]);
        }catch (\Throwable $e){
          DB::rollBack();
          Utilidades::errors($e);
        }
      }

      public function Actualizar(Request $request)
      {
        try{

          $user = Auth::user();
          DB::beginTransaction();

          $resguardo = TiMaterialResguardo::where('id', $request->id)->first();
          /** SI EL MATERIAL PRINCIPAL RESGUARDADO ES DIFERENTE A AL MATERIAL DEL REQUEST
          **  SE ENVIA UN ESTADO DE ERROR YA QUE DEBE SER RETORNADO EL MATERIAL PARA CUMPLIR CON EL PROCESO
          **/
          if ($request->caiv != $resguardo->caiv){
            return response()->json(['status' => true, 'estado' => 3]);
          }

          /**
          ** SE REGISTRA LA NUEVA CANTIDAD DEL MATERIAL PRINCIPAL EVALUANDO SU EXISTENCIA
          **/

          if ($request->caiv == $resguardo->caiv){

            if ($request->tipo == 1){
              $cdata = \App\TiComputo::where('id', $request->caiv)->first();
              $total = (float)$cdata->cantidad - ((float)$request->cantidad - (float)$resguardo->cantidad);
              $cdata->cantidad = $total;
              if ($total < 0){
                DB::rollBack();
                return response()->json(['status' => true, 'estado' => 2]);
              }
              Utilidades::auditar($cdata, $cdata->id);
              $cdata->save();
            }
            if ($request->tipo == 2){
              $cdata = \App\TiAccesorio::where('id', $request->caiv)->first();
              $total = (float)$cdata->cantidad - ((float)$request->cantidad - (float)$resguardo->cantidad);
              $cdata->cantidad = $total;
              if ($total < 0){
                DB::rollBack();
                return response()->json(['status' => true, 'estado' => 2]);
              }
              Utilidades::auditar($cdata, $cdata->id);
              $cdata->save();
            }
            if ($request->tipo == 3){
              $cdata = \App\TiImpresion::where('id', $request->caiv)->first();
              $total = (float)$cdata->cantidad - ((float)$request->cantidad - (float)$resguardo->cantidad);
              $cdata->cantidad = $total;
              if ($total < 0){
                DB::rollBack();
                return response()->json(['status' => true, 'estado' => 2]);
              }
              Utilidades::auditar($cdata, $cdata->id);
              $cdata->save();
            }
            if ($request->tipo == 4){
              $cdata = \App\TiVideo::where('id', $request->caiv)->first();
              $total = (float)$cdata->cantidad - ((float)$request->cantidad - (float)$resguardo->cantidad);
              $cdata->cantidad = $total;
              if ($total < 0){
                DB::rollBack();
                return response()->json(['status' => true, 'estado' => 2]);
              }
              Utilidades::auditar($cdata, $cdata->id);
              $cdata->save();
            }
          }
          //SE ACTUALIZAN LOS DATOS DEL RESGUARDO
          $resguardo_act = TiMaterialResguardo::where('id', $request->id)->first();
          $resguardo_act->fecha = $request->fecha;
          $resguardo_act->caiv = $request->caiv;
          $resguardo_act->tipo = $request->tipo;
          $resguardo_act->acesorios = $request->acesorios;
          $resguardo_act->observacion_uno = $request->observacion_uno;
          $resguardo_act->observacion_dos = $request->observacion_dos;
          $resguardo_act->empleado_entrega =  $request->empleado_entrega;
          $resguardo_act->empleado_recibe = $request->empleado_recibe;
          $resguardo_act->cantidad = $request->cantidad;
          $resguardo_act->empresa = $request->empresa;
          Utilidades::auditar($resguardo_act, $resguardo_act->id);
          $resguardo_act->save();

          //SE EVALUA DENTRO DEL ARRAY POSTEADO EXISTE UN ELEMENTO NUEVO DE SER ASI SE RESTA LA CANTIDAD EN EL LOS ACCESORIOS
          foreach ($request->accesorios as $key_a => $value_a) {
            if (array_key_exists('nuevo', $value_a)) {
                $adata = \App\TiAccesorio::where('id', $value_a['id'])->first();
                $total = $adata->cantidad - $value_a['cantidad'];
                $adata->cantidad = $total;
                $adata->condicion = 2;
                Utilidades::auditar($adata, $adata->id);
                $adata->update();
            }
          }
          //REGISTRO DEL JSON CMPLETO DEL PUT
          $resguardo_act->accesorios_listado = json_encode($request->accesorios);
          $resguardo_act->save();




          DB::commit();
          return response()->json(['status' => true, 'estado' => 1]);
        }catch (\Throwable $e){
          DB::rollBack();
          Utilidades::errors($e);
        }
      }

      /**
      * Obtiene todos los resguardos registrados de empresa ingresada
      */
      public function getR($empresa)
      {

        $data = TiMaterialResguardo::join('empleados AS e', 'e.id', 'ti_material_resguardo.empleado_recibe')
        ->join('empleados AS ee', 'ee.id', 'ti_material_resguardo.empleado_entrega')
        ->select('ti_material_resguardo.*', DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_r"),
        DB::raw("CONCAT(ee.nombre,' ',ee.ap_paterno,' ',ee.ap_materno) AS empleado_e"))
        ->where('ti_material_resguardo.empresa', $empresa) // Empresa
        // ->where('ti_material_resguardo.estado', '<','4') // Resguardos salidos
        ->orderBy('ti_material_resguardo.fecha','DESC')
        ->get();

        $arreglo = [];
        foreach ($data as $key => $value){
          $des = '';

          if ($value->tipo == 1){
            $cdata = \App\TiComputo::where('id', $value->caiv)
            ->select(DB::raw("CONCAT(no_serie,' ',marca_modelo,' ',cpu,' ',ram,' ',almacenamiento,' ',tarjeta_video,' ',tarjeta_red,' ',observaciones,' ',mac) AS descripcion"))
            ->first();
            $des = $cdata->descripcion;
          }
          if ($value->tipo == 2){
            $cdata = \App\TiAccesorio::select(DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS descripcion"))
            ->where('id', $value->caiv)
            ->first();
            $des = $cdata->descripcion;
          }
          if ($value->tipo == 3){
            $cdata = \App\TiImpresion::select(DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS descripcion"))
            ->where('id', $value->caiv)
            ->first();
            $des = $cdata->descripcion;
          }
          if ($value->tipo == 4){
            $cdata = \App\TiVideo::select(DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS descripcion"))
            ->where('id', $value->caiv)
            ->first();
            $des = $cdata->descripcion;
          }

          $arreglo[] = [
            'data' => $value,
            'descripcion' => $des,
          ];
        }

        return response()->json($arreglo);
      }

      public function deleteR($id)
      {
        try{
          DB::beginTransaction();
          $resguardo = TiMaterialResguardo::where('id', $id)->first();

          if ($resguardo->tipo == 1){
            $cdata = \App\TiComputo::where('id', $resguardo->caiv)->first();
            $total = $cdata->cantidad + $resguardo->cantidad;
            $cdata->cantidad = $total;
            Utilidades::auditar($cdata, $cdata->id);
            $cdata->save();
          }
          if ($resguardo->tipo == 2){
            $cdata = \App\TiAccesorio::where('id', $resguardo->caiv)->first();
            $total = $cdata->cantidad + $resguardo->cantidad;
            $cdata->cantidad = $total;
            Utilidades::auditar($cdata, $cdata->id);
            $cdata->save();
          }
          if ($resguardo->tipo == 3){
            $cdata = \App\TiImpresion::where('id', $resguardo->caiv)->first();
            $total = $cdata->cantidad + $resguardo->cantidad;
            $cdata->cantidad = $total;
            Utilidades::auditar($cdata, $cdata->id);
            $cdata->save();
          }
          if ($resguardo->tipo == 4){
            $cdata = \App\TiVideo::where('id', $resguardo->caiv)->first();
            $total = $cdata->cantidad + $resguardo->cantidad;
            $cdata->cantidad = $total;
            Utilidades::auditar($cdata, $cdata->id);
            $cdata->save();
          }
          $resguardo = TiMaterialResguardo::where('id', $id)->delete();

          DB::commit();

          return response()->json(['status' => true]);
        }
        catch (\Throwable $e)
        {
          DB::rollBack();
          Utilidades::errors($e);
        }
      }

      public function descargarR($id)
      {
        try{

          $data = TiMaterialResguardo::join('empleados AS e', 'e.id', 'ti_material_resguardo.empleado_recibe')
          ->join('empleados AS ea', 'ea.id', 'ti_material_resguardo.empleado_entrega')
          ->select(
            'ti_material_resguardo.*',
            DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_r"),
            DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_e")
            )
            ->where('ti_material_resguardo.id', $id)
            ->first();

            $anio = substr($data->fecha, 0, 4);
            $mes = substr($data->fecha, 5, -3);
            $dia = substr($data->fecha, 8);

            $fecha_fin = $dia . ' / ' . $this->mesLetra($mes) . ' / ' . $anio;

            if ($data->tipo == 1){
              $cdata = \App\TiComputo::
              select('ti_computo.*',DB::raw("CONCAT(cpu,' ',ram,' ',almacenamiento,' ',tarjeta_video,' ',tarjeta_red,' ',observaciones,' ',mac) AS caracteristicas"))
              ->where('id', $data->caiv)->first();
            }
            if ($data->tipo == 2){
              $cdata = \App\TiAccesorio::
              select('ti_accesorios.*',DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS caracteristicas"))
              ->where('id', $data->caiv)->first();
            }
            if ($data->tipo == 3){
              $cdata = \App\TiImpresion::
              select('ti_impresoras.*',DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS caracteristicas"))
              ->where('id', $data->caiv)->first();
            }
            if ($data->tipo == 4){
              $cdata = \App\TiVideo::
              select('ti_impresoras.*',DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS caracteristicas"))
              ->where('id', $data->caiv)->first();
            }

            $emp_aux="";
            $accesorios = json_decode($data->accesorios_listado);

            if ($data->empresa == 1){
              $pdf = PDF::loadView('pdf.valeresguardoti', compact('data','accesorios', 'fecha_fin', 'cdata'));
              $emp_aux="CONSERFLOW_PTI-01_F-02";
            }
            elseif ($data->empresa = 2){
              $pdf = PDF::loadView('pdf.valeresguardoticsct', compact('data','accesorios', 'fecha_fin', 'cdata'));
              $emp_aux="CSCT_TI-01_F-04";
            }
            $pdf->setPaper('letter', 'portrait');
            error_reporting(E_ALL ^ E_DEPRECATED);
            $nombre=$emp_aux." VALE DE RESGUARDO DE EQUIPO DE TI- " . $data->empleado_r.'.pdf';
            return $pdf->stream($nombre);
          }catch (\Throwable $e){
            Utilidades::errors($e);
          }
        }

        public function mesLetra($value)
        {
          $mes = '';
          switch ($value)
          {
            case '01':
            $mes = 'ENE';
            break;
            case '02':
            $mes = 'FEB';
            break;
            case '03':
            $mes = 'MAR';
            break;
            case '04':
            $mes = 'ABR';
            break;
            case '05':
            $mes = 'MAY';
            break;
            case '06':
            $mes = 'JUN';
            break;
            case '07':
            $mes = 'JUL';
            break;
            case '08':
            $mes = 'AGO';
            break;
            case '09':
            $mes = 'SEP';
            break;
            case '10':
            $mes = 'OCT';
            break;
            case '11':
            $mes = 'NOV';
            break;
            case '12':
            $mes = 'DIC';
            break;
          }
          return $mes;
        }

        public function GuardarS(Request $request)
        {
          try
          {
            DB::beginTransaction();
            $user = Auth::user();

            $ti_material_salida = new \App\TISalidaSitio();
            $ti_material_salida->fecha_salida = $request->fecha_salida;
            $ti_material_salida->proyecto_id = $request->proyecto;
            $ti_material_salida->solicita = $request->solicita;
            $ti_material_salida->entrega = $user->empleado_id;
            $ti_material_salida->empresa = $request->empresa;
            Utilidades::auditar($ti_material_salida, $ti_material_salida->id);
            $ti_material_salida->save();

            foreach ($request->data as $key => $value){
              $partidas = new \App\PartidasTISalidaSitio();
              $partidas->tipo = $value['tipo'];
              $partidas->cantidad = $value['cantidad'];
              $partidas->material_id = $value['id'];
              $partidas->unidad = $value['unidad'];
              $partidas->ti_salida_sitio_id = $ti_material_salida->id;
              Utilidades::auditar($partidas, $partidas->id);
              $partidas->save();

              if ($value['tipo'] == 1){
                $cdata = \App\TiComputo::where('id', $value['id'])->first();
                $total = $cdata->cantidad - $value['cantidad'];
                $cdata->cantidad = $total;
                $cdata->condicion = 3;
                if ($total < 0){
                  DB::rollBack();
                  return response()->json(['status' => true, 'estado' => 2]);
                }
                Utilidades::auditar($cdata, $cdata->id);
                $cdata->save();
              }
              if ($value['tipo'] == 2){
                $cdata = \App\TiAccesorio::where('id', $value['id'])->first();
                $total = $cdata->cantidad - $value['cantidad'];
                $cdata->cantidad = $total;
                $cdata->condicion = 3;
                if ($total < 0){
                  DB::rollBack();
                  return response()->json(['status' => true, 'estado' => 2]);
                }
                Utilidades::auditar($cdata, $cdata->id);
                $cdata->save();
              }
              if ($value['tipo'] == 3){
                $cdata = \App\TiImpresion::where('id', $value['id'])->first();
                $total = $cdata->cantidad - $value['cantidad'];
                $cdata->cantidad = $total;
                $cdata->condicion = 3;
                if ($total < 0)
                {
                  DB::rollBack();
                  return response()->json(['status' => true, 'estado' => 2]);
                }
                Utilidades::auditar($cdata, $cdata->id);
                $cdata->save();
              }
              if ($value['tipo'] == 4){
                $cdata = \App\TiVideo::where('id', $value['id'])->first();
                $total = $cdata->cantidad - $value['cantidad'];
                $cdata->cantidad = $total;
                $cdata->condicion = 3;
                if ($total < 0)
                {
                  DB::rollBack();
                  return response()->json(['status' => true, 'estado' => 2]);
                }
                Utilidades::auditar($cdata, $cdata->id);
                $cdata->save();
              }
              if ($value['tipo'] == 5){
                $cdata = \App\TiRed::where('id', $value['id'])->first();
                $total = $cdata->cantidad - $value['cantidad'];
                $cdata->cantidad = $total;
                $cdata->condicion = 3;
                if ($total < 0){
                  DB::rollBack();
                  return response()->json(['status' => true, 'estado' => 2]);
                }
                Utilidades::auditar($cdata, $cdata->id);
                $cdata->save();
              }
            }
            DB::commit();
            return response()->json(['status' => true, 'estado' => 1]);
          }catch (\Throwable $e){
            DB::rollBack();
            Utilidades::errors($e);
          }
        }

        public function ActualizarS(Request $request)
        {
          try{
            $user = Auth::user();
            DB::beginTransaction();
            $ti_material_salida = \App\TISalidaSitio::where('id', $request->id)->first();
            $ti_material_salida->fecha_salida = $request->fecha_salida;
            $ti_material_salida->proyecto_id = $request->proyecto;
            $ti_material_salida->solicita = $request->solicita;
            $ti_material_salida->entrega = $user->empleado_id;
            $ti_material_salida->empresa = $request->empresa;
            Utilidades::auditar($ti_material_salida, $ti_material_salida->id);
            $ti_material_salida->save();
            $arreglo_delete = [];
            foreach ($request->data as $key_d => $value_d)
            {
              $arreglo_delete[] = $value_d['id'];
            }

            $data_delete = \App\PartidasTISalidaSitio::where('ti_salida_sitio_id', $request->id)
            ->whereNotIn('material_id', $arreglo_delete)->delete();

            foreach ($request->data as $key => $value){
              $data_b = \App\PartidasTISalidaSitio::where('ti_salida_sitio_id', $request->id)
              ->where('material_id', $value['id'])->first();

              if (isset($data_b) == false){
                $partidas = new \App\PartidasTISalidaSitio();
                $partidas->tipo = $value['tipo'];
                $partidas->cantidad = $value['cantidad'];
                $partidas->material_id = $value['id'];
                $partidas->unidad = $value['unidad'];
                $partidas->ti_salida_sitio_id = $ti_material_salida->id;
                Utilidades::auditar($partidas, $partidas->id);
                $partidas->save();

                if ($value['tipo'] == 1){
                  $cdata = \App\TiComputo::where('id', $value['id'])->first();
                  $total = $cdata->cantidad - $value['cantidad'];
                  $cdata->cantidad = $total;
                  $cdata->condicion = 3;
                  if ($total < 0){
                    DB::rollBack();
                    return response()->json(['status' => true, 'estado' => 2]);
                  }
                  Utilidades::auditar($cdata, $cdata->id);
                  $cdata->save();
                }
                if ($value['tipo'] == 2){
                  $cdata = \App\TiAccesorio::where('id', $value['id'])->first();
                  $total = $cdata->cantidad - $value['cantidad'];
                  $cdata->cantidad = $total;
                  $cdata->condicion = 3;
                  if ($total < 0){
                    DB::rollBack();
                    return response()->json(['status' => true, 'estado' => 2]);
                  }
                  Utilidades::auditar($cdata, $cdata->id);
                  $cdata->save();
                }
                if ($value['tipo'] == 3){
                  $cdata = \App\TiImpresion::where('id', $value['id'])->first();
                  $total = $cdata->cantidad - $value['cantidad'];
                  $cdata->cantidad = $total;
                  $cdata->condicion = 3;
                  if ($total < 0){
                    DB::rollBack();
                    return response()->json(['status' => true, 'estado' => 2]);
                  }
                  Utilidades::auditar($cdata, $cdata->id);
                  $cdata->save();
                }
                if ($value['tipo'] == 4){
                  $cdata = \App\TiVideo::where('id', $value['id'])->first();
                  $total = $cdata->cantidad - $value['cantidad'];
                  $cdata->cantidad = $total;
                  $cdata->condicion = 3;
                  if ($total < 0){
                    DB::rollBack();
                    return response()->json(['status' => true, 'estado' => 2]);
                  }
                  Utilidades::auditar($cdata, $cdata->id);
                  $cdata->save();
                }
                if ($value['tipo'] == 5){
                  $cdata = \App\TiRed::where('id', $value['id'])->first();
                  $total = $cdata->cantidad - $value['cantidad'];
                  $cdata->cantidad = $total;
                  $cdata->condicion = 3;
                  if ($total < 0){
                    DB::rollBack();
                    return response()->json(['status' => true, 'estado' => 2]);
                  }
                  Utilidades::auditar($cdata, $cdata->id);
                  $cdata->save();
                }
              }
            }

            DB::commit();
            return response()->json(['status' => true, 'estado' => 1]);
          }catch (\Throwable $e){
            DB::rollBack();
            Utilidades::errors($e);
          }
        }

        public function getDataSalida($id)
        {
          $ti_material_salida = \App\TISalidaSitio::join('empleados AS e', 'e.id', 'ti_salida_sitio.solicita')
          ->join('proyectos AS p', 'p.id', 'ti_salida_sitio.proyecto_id')
          ->select(
            'ti_salida_sitio.*',
            'p.nombre_corto',
            DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS solicita_empleado")
            )
            ->where('ti_salida_sitio.empresa', $id)
            ->get();

            return response()->json($ti_material_salida);
          }

          public function getPartidasTI($id)
          {
            $data = \App\PartidasTISalidaSitio::where('ti_salida_sitio_id', $id)->get();

            $arreglo = [];
            foreach ($data as $key => $value){
              $des = '';

              if ($value->tipo == 1){
                $cdata = \App\TiComputo::where('id', $value->material_id)
                ->select(DB::raw("CONCAT(no_serie,' ',marca_modelo,' ',cpu,' ',ram,' ',almacenamiento,' ',tarjeta_video,' ',tarjeta_red,' ',observaciones,' ',mac) AS descripcion"))
                ->first();
                $des = $cdata->descripcion;
              }
              if ($value->tipo == 2){
                $cdata = \App\TiAccesorio::select(DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS descripcion"))
                ->where('id', $value->material_id)
                ->first();
                $des = $cdata->descripcion;
              }
              if ($value->tipo == 3){
                $cdata = \App\TiImpresion::select(DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS descripcion"))
                ->where('id', $value->material_id)
                ->first();
                $des = $cdata->descripcion;
              }
              if ($value->tipo == 4){
                $cdata = \App\TiVideo::select(DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS descripcion"))
                ->where('id', $value->material_id)
                ->first();
                $des = $cdata->descripcion;
              }
              if ($value->tipo == 5){
                $cdata = \App\TiRed::select(DB::raw("CONCAT(descripcion,' ',no_serie) AS descripcion"))
                ->where('id', $value->material_id)
                ->first();
                $des = $cdata->descripcion;
              }

              $arreglo[] = [
                'data' => $value,
                'descripcion' => $des,
              ];
            }

            return response()->json($arreglo);
          }

          public function getPartidasTIActivos($id)
          {
            $data = \App\PartidasTISalidaSitio::where('ti_salida_sitio_id', $id)->where('retornado','1')->get();

            $arreglo = [];
            foreach ($data as $key => $value){
              $des = '';

              if ($value->tipo == 1){
                $cdata = \App\TiComputo::where('id', $value->material_id)
                ->select(DB::raw("CONCAT(no_serie,' ',marca_modelo,' ',cpu,' ',ram,' ',almacenamiento,' ',tarjeta_video,' ',tarjeta_red,' ',observaciones,' ',mac) AS descripcion"))
                ->first();
                $des = $cdata->descripcion;
              }
              if ($value->tipo == 2){
                $cdata = \App\TiAccesorio::select(DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS descripcion"))
                ->where('id', $value->material_id)
                ->first();
                $des = $cdata->descripcion;
              }
              if ($value->tipo == 3){
                $cdata = \App\TiImpresion::select(DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS descripcion"))
                ->where('id', $value->material_id)
                ->first();
                $des = $cdata->descripcion;
              }
              if ($value->tipo == 4){
                $cdata = \App\TiVideo::select(DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS descripcion"))
                ->where('id', $value->material_id)
                ->first();
                $des = $cdata->descripcion;
              }
              if ($value->tipo == 5){
                $cdata = \App\TiRed::select(DB::raw("CONCAT(descripcion,' ',no_serie) AS descripcion"))
                ->where('id', $value->material_id)
                ->first();
                $des = $cdata->descripcion;
              }

              $arreglo[] = [
                'data' => $value,
                'descripcion' => $des,
              ];
            }

            return response()->json($arreglo);
          }

          public function descargarS($id)
          {
            try{

              $data = \App\TISalidaSitio::join('empleados AS e', 'e.id', 'ti_salida_sitio.solicita')
              ->join('empleados AS ea', 'ea.id', 'ti_salida_sitio.entrega')
              ->leftjoin('empleados AS er', 'er.id', 'ti_salida_sitio.retorno')
              ->join('proyectos AS p', 'p.id', 'ti_salida_sitio.proyecto_id')
              ->select(
                'ti_salida_sitio.*',
                'p.nombre_corto',
                'p.ciudad',
                DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_r"),
                DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS empleado_e"),
                DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS empleado_retorno")
                )
                ->where('ti_salida_sitio.id', $id)
                ->first();



                $fecha_salida = $this->getFechaNombre($data->fecha_salida);
                $fecha_retorno = $this->getFechaNombre($data->fecha_retorno);

                $partidas = DB::table('ti_salida_sitio_partidas')->where('ti_salida_sitio_id', $id)->get();

                $arreglo = [];
                foreach ($partidas as $key => $value)
                {
                  $des = '';

                  if ($value->tipo == 1){
                    $cdata = \App\TiComputo::where('id', $value->material_id)
                    ->select(DB::raw("CONCAT(no_serie,' ',marca_modelo,' ',cpu,' ',ram,' ',almacenamiento,' ',tarjeta_video,' ',tarjeta_red,' ',observaciones,' ',mac) AS descripcion"))
                    ->first();
                    $des = $cdata->descripcion;
                  }
                  if ($value->tipo == 2){
                    $cdata = \App\TiAccesorio::select(DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS descripcion"))
                    ->where('id', $value->material_id)
                    ->first();
                    $des = $cdata->descripcion;
                  }
                  if ($value->tipo == 3){
                    $cdata = \App\TiImpresion::select(DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS descripcion"))
                    ->where('id', $value->material_id)
                    ->first();
                    $des = $cdata->descripcion;
                  }
                  if ($value->tipo == 4){
                    $cdata = \App\TiVideo::select(DB::raw("CONCAT(descripcion,' ',modelo,' ',marca,' ',no_serie) AS descripcion"))
                    ->where('id', $value->material_id)
                    ->first();
                    $des = $cdata->descripcion;
                  }
                  if ($value->tipo == 5){
                    $cdata = \App\TiRed::select(DB::raw("CONCAT(descripcion,' ',no_serie) AS descripcion"))
                    ->where('id', $value->material_id)
                    ->first();
                    $des = $cdata->descripcion;
                  }

                  $arreglo[] = [
                    'data' => $value,
                    'descripcion' => $des,
                  ];
                }


                $pdf = PDF::loadView('pdf.valesalidati', compact('data', 'fecha_salida','fecha_retorno', 'arreglo'));
                $pdf->setPaper('letter', 'portrait');
                return $pdf->stream();
              }catch (\Throwable $e){
                Utilidades::errors($e);
              }
            }

            public function getFechaNombre($date)
            {

              $anio = substr($date, 0, 4);
              $mes = substr($date, 5, -3);
              $dia = substr($date, 8);

              $fecha_salida = $dia . ' / ' . $this->mesLetra($mes) . ' / ' . $anio;

              return $fecha_salida;
            }

            /**
            * Registra el retorno de un resguardo de TI
            */
            public function Regresar(Request $request)
            {
              try{
                DB::beginTransaction();
                // Liberar resguardo
                $resguardo = TiMaterialResguardo::find($request->id);
                $resguardo->estado = $request->cantidad_defectuoso == 0 ? 2 : 3; // Liberado
                $resguardo->observacion_recepcion = $request->observacion_recepcion; // Observaciones de repepcion
                $resguardo->fecha_retorno = $request->fecha;
                $resguardo->update();

                // Actualizar inventario
                $tablas = ["ti_computo", "ti_accesorios", "ti_impresoras", "ti_video", "ti_red"];
                $tbl = $tablas[$resguardo->tipo - 1];

                if ($request->cantidad_defectuoso == 0) {

                  // Buscar equipo en inventario
                  $n = DB::table($tbl)->where("id", $resguardo->caiv)->first()->cantidad;

                  $equipo = DB::table($tbl)->where("id", $resguardo->caiv)
                  ->update([
                    "cantidad" => ($n + $resguardo->cantidad),
                    "condicion" => 1
                  ]);
                }else {
                  // Desactivamos el articulo
                  // Buscar equipo en inventario
                  $n = DB::table($tbl)->where("id", $resguardo->caiv)->first()->cantidad;

                  $equipo = DB::table($tbl)->where("id", $resguardo->caiv)
                  ->update([
                    "cantidad" => ($n + $resguardo->cantidad),
                    "condicion" => 0
                  ]);
                }

                DB::commit();
                return Status::Success();
              }catch (Exception $e){
                DB::rollBack();
                dd($e);
                return Status::Error($e, "registrar retorno del equipo");
              }
            }


            public function eliminarAccesorio($id)
            {
              $valores = explode('&',$id);

              $resguardo = TiMaterialResguardo::where('id',$valores[1])->first();
              $json_accesorio = json_decode($resguardo->accesorios_listado);

              foreach ($json_accesorio as $key => $value) {
                if($value->id == $valores[0]){
                  $accesorios = \App\TiAccesorio::where('id',$value->id)->first();
                  $total = $accesorios->cantidad + $value->cantidad;
                  $accesorios->cantidad = $total;
                  $accesorios->update();
                  unset($json_accesorio[$key]);
                };
              }
              $resguardo->accesorios_listado = json_encode($json_accesorio);
              $resguardo->save();

              return response()->json(['status' => true]);
            }

            public function retornarPartida($id)
            {
              try {
                $user = Auth::user();

                $valores = explode('&',$id);
                DB::beginTransaction();
                $partida = \App\PartidasTISalidaSitio::where('material_id',$valores[0])->where('ti_salida_sitio_id',$valores[1])->first();
                $partida->retornado = $valores[2];
                Utilidades::auditar($partida, $partida->id);
                $partida->save();


                $tablas = ["ti_computo", "ti_accesorios", "ti_impresoras", "ti_video", "ti_red"];
                $tbl = $tablas[$partida->tipo - 1];

                if ($valores[2] == 2) {

                  // Buscar equipo en inventario
                  $n = DB::table($tbl)->where("id", $partida->material_id)->first()->cantidad;

                  $equipo = DB::table($tbl)->where("id", $partida->material_id)
                  ->update([
                    "cantidad" => ($n + $partida->cantidad),
                    "condicion" => 1
                  ]);
                }elseif ($valores[2] == 0){
                  // Desactivamos el articulo
                  // Buscar equipo en inventario
                  $n = DB::table($tbl)->where("id", $partida->material_id)->first()->cantidad;

                  $equipo = DB::table($tbl)->where("id", $partida->material_id)
                  ->update([
                    "cantidad" => ($n + $partida->cantidad),
                    "condicion" => 0
                  ]);
                }

                $partida_totales = \App\PartidasTISalidaSitio::
                where('ti_salida_sitio_id',$valores[1])
                ->count();

                $partida_retornadas = \App\PartidasTISalidaSitio::
                where('ti_salida_sitio_id',$valores[1])
                ->where('retornado','!=','1')
                ->count();

                if ($partida_retornadas == $partida_totales) {
                  $salida = \App\TISalidaSitio::where('id',$valores[1])->first();
                  $salida->condicion = 2;
                  $salida->retorno = $user->empleado_id;
                  $salida->fecha_retorno = date('Y-m-d');
                  Utilidades::auditar($salida, $salida->id);
                  $salida->save();
                }
                DB::commit();

                return response()->json(['status' => true]);
                // return response()->json($partida_retornadas);
              } catch (\Throwable $e) {
                DB::rollBack();
                Utilidades::errors($e);
              }
            }

}
