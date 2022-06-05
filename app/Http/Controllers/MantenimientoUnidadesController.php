<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PartidasMantenimientoUnidades;
use App\MantenimientoUnidades;
use Validator;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Support\Facades\Storage;
use \App\Http\Helpers\Utilidades;

class MantenimientoUnidadesController extends Controller
{
    //
    public function show($id)
    {
      $arreglo = [];
      $mantenimientoUnidades = MantenimientoUnidades::where('unidad_id',$id)->get();
      foreach ($mantenimientoUnidades as $key => $value) {
        $partida_mantenimiento = PartidasMantenimientoUnidades::join('catalogo_trafico AS CT','CT.id','=','partidas_mantenimiento_unidades.catalogo_trafico_id')
        ->select('partidas_mantenimiento_unidades.*','CT.operacion_id','CT.nombre')
        ->where('unidad_id',$id)
        ->where('mantenimiento_id',$value->id)->get();
        $arreglo [] = [
          'mantenimiento' => $value,
          'partidas' => $partida_mantenimiento,
        ];
      }

      return response()->json($arreglo);
    }

    public function store(Request $request)
    {
      $valores = explode(',',$request->tipo_servicios);
      $tamanio = count($valores);




      if($request->metodo == 1){
          /*NUEVO REGISTRO*/
          //Variables de archivo
          $FacturaStore='';
          if (!$request->ajax()) return redirect('/');
          //--Si el request no contiene archivos, se guardan los campos listados--//
          if(!$request->hasFile('factura')) {
            $mantenimientoUnidades = new MantenimientoUnidades();
            // $mantenimientoUnidades->fill($request->all());
            $mantenimientoUnidades->proveedor = $request->proveedor;
            $mantenimientoUnidades->responsable = $request->responsable;
            $mantenimientoUnidades->total = $request->total;
            $mantenimientoUnidades->fecha_servicio = $request->fecha_servicio;
            $mantenimientoUnidades->kilometraje = $request->kilometraje;
            $mantenimientoUnidades->fecha_entrega = $request->fecha_entrega;
            $mantenimientoUnidades->fecha_prox_rev = $request->fecha_prox_rev;
            $mantenimientoUnidades->kilometraje_revision = $request->kilometraje_revision;
            $mantenimientoUnidades->factura = '';
            $mantenimientoUnidades->unidad_id = $request->unidad_id;
            $mantenimientoUnidades->save();
            $this->llenarCatalogo($request,$valores,$tamanio,$mantenimientoUnidades->id);
            Utilidades::auditar($mantenimientoUnidades, $mantenimientoUnidades->id);
          //    $mantenimientoUnidades->save();
          }
          //--valida que campos del request contienen archivos y realiza el guardado--//
          if($request->hasFile('factura'))
          {
              if ($request->hasFile('factura')) {
                  //obtiene el nombre del archivo y su extension
                  $FacturaNE = $request->file('factura')->getClientOriginalName();
                  //Obtiene el nombre del archivo
                  $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
                  //obtiene la extension
                  $FacturaExt = $request->file('factura')->getClientOriginalExtension();
                  //nombre que se guarad en BD
                  $FacturaStore = 'Mante_'.uniqid().'.'.$FacturaExt;
                  //Subida del archivo al servidor ftp
                  Storage::disk('local')->put('Archivos/'.$FacturaStore, fopen($request->file('factura'), 'r+'));
              }
              $mantenimientoUnidades = new MantenimientoUnidades();
              // $mantenimientoUnidades->fill($request->all());
              $mantenimientoUnidades->proveedor = $request->proveedor;
              $mantenimientoUnidades->responsable = $request->responsable;
              $mantenimientoUnidades->total = $request->total;
              $mantenimientoUnidades->fecha_servicio = $request->fecha_servicio;
              $mantenimientoUnidades->kilometraje = $request->kilometraje;
              $mantenimientoUnidades->fecha_entrega = $request->fecha_entrega;
              $mantenimientoUnidades->fecha_prox_rev = $request->fecha_prox_rev;
              $mantenimientoUnidades->kilometraje_revision = $request->kilometraje_revision;
              $mantenimientoUnidades->factura = $FacturaStore;
              $mantenimientoUnidades->unidad_id = $request->unidad_id;
              $mantenimientoUnidades->save();
              $this->llenarCatalogo($request,$valores,$tamanio,$mantenimientoUnidades->id);
              Utilidades::auditar($mantenimientoUnidades, $mantenimientoUnidades->id);
              //$poliza->save();
          }
          return response()->json(array(
              'status' => true,
          ));
          /*FIN NUEVO REGISTRO*/
      }
      if($request->metodo == 0){
          /*ACTUALIZAR REGISTRO*/
          //Variables de archivo
          $FacturaStore='';
          //*Variables para actualizar nuevos archivos y eliminar existentes
          $ValorPoliza = '';
          $polizaes = MantenimientoUnidades::where('id',$request->id)->get();
          foreach ($polizaes as $key => $item) {
              $ValorPoliza = $item->factura;
              $FacturaStore=$item->factura;
          }
          //*FIN
          if (!$request->ajax()) return redirect('/');
          //--Si el request no contiene archivos, solo se actualizan los campos listados--//
          if(!$request->hasFile('factura'))
          {
            $mantenimientoUnidades = MantenimientoUnidades::findOrFail($request->id);
            // $mantenimientoUnidades->fill($request->all());
            $mantenimientoUnidades->proveedor = $request->proveedor;
            $mantenimientoUnidades->responsable = $request->responsable;
            $mantenimientoUnidades->total = $request->total;
            $mantenimientoUnidades->fecha_servicio = $request->fecha_servicio;
            $mantenimientoUnidades->kilometraje = $request->kilometraje;
            $mantenimientoUnidades->fecha_entrega = $request->fecha_entrega;
            $mantenimientoUnidades->fecha_prox_rev = $request->fecha_prox_rev;
            $mantenimientoUnidades->kilometraje_revision = $request->kilometraje_revision;
            $mantenimientoUnidades->unidad_id = $request->unidad_id;
            $mantenimientoUnidades->save();
            $this->llenarCatalogo($request,$valores,$tamanio,$mantenimientoUnidades->id);
            Utilidades::auditar($mantenimientoUnidades, $mantenimientoUnidades->id);
            //  $poliza->save();
          }
          //--valida que campos del request contienen archivos y realiza el guardado--//
          if($request->hasFile('factura'))
          {
              if ($request->hasFile('factura')) {
                  //obtiene el nombre del archivo y su extension
                  $FacturaNE = $request->file('factura')->getClientOriginalName();
                  //Obtiene el nombre del archivo
                  $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
                  //obtiene la extension
                  $FacturaExt = $request->file('factura')->getClientOriginalExtension();
                  //nombre que se guarad en BD
                  $FacturaStore = 'Mante'.uniqid().'.'.$FacturaExt;
                  //Subida del archivo al servidor ftp
                  Storage::disk('local')->put('Archivos/'.$FacturaStore, fopen($request->file('factura'), 'r+'));
                  if ($ValorPoliza != '') {
                      //Elimina el archivo en el servidor si requiere ser actualizado
                      Utilidades::ftpSolucionEliminar($ValorPoliza);
                  }
              }
              $mantenimientoUnidades = MantenimientoUnidades::findOrFail($request->id);
              // $mantenimientoUnidades->fill($request->all());
              $mantenimientoUnidades->proveedor = $request->proveedor;
              $mantenimientoUnidades->responsable = $request->responsable;
              $mantenimientoUnidades->total = $request->total;
              $mantenimientoUnidades->fecha_servicio = $request->fecha_servicio;
              $mantenimientoUnidades->kilometraje = $request->kilometraje;
              $mantenimientoUnidades->fecha_entrega = $request->fecha_entrega;
              $mantenimientoUnidades->fecha_prox_rev = $request->fecha_prox_rev;
              $mantenimientoUnidades->kilometraje_revision = $request->kilometraje_revision;
              $mantenimientoUnidades->factura = $FacturaStore;
              $mantenimientoUnidades->unidad_id = $request->unidad_id;
              $mantenimientoUnidades->save();
              $this->llenarCatalogo($request,$valores,$tamanio,$mantenimientoUnidades->id);
              Utilidades::auditar($mantenimientoUnidades, $mantenimientoUnidades->id);
              //$poliza->save();
          }

          return response()->json(array(
              'status' => true
          ));
          /*FIN ACTUALIZAR REGISTRO*/
      }

      return response()->json($request);
    }

    public function llenarCatalogo($request,$valores,$tamanio,$mantenimiento_id)
    {
      $tipo = PartidasMantenimientoUnidades::where('mantenimiento_id',$mantenimiento_id)->first();
      if (isset($tipo) == true) {
        $data = PartidasMantenimientoUnidades::where('mantenimiento_id',$mantenimiento_id)->delete();
        for ($i=0; $i < $tamanio ; $i++) {
          $tipo_servicios = new PartidasMantenimientoUnidades();
          $tipo_servicios->catalogo_trafico_id = $valores[$i];
          $tipo_servicios->unidad_id = $request->unidad_id;
          $tipo_servicios->mantenimiento_id =$mantenimiento_id;
          $tipo_servicios->save();
        }
      }else {
        for ($i=0; $i < $tamanio ; $i++) {
          $tipo_servicios = new PartidasMantenimientoUnidades();
          $tipo_servicios->catalogo_trafico_id = $valores[$i];
          $tipo_servicios->unidad_id = $request->unidad_id;
          $tipo_servicios->mantenimiento_id =$mantenimiento_id;
          $tipo_servicios->save();
        }
      }
      return true;
    }
}
