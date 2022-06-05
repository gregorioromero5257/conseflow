<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PartidasServicioUnidades;
use App\ServicioUnidades;
use Validator;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Support\Facades\Storage;
use \App\Http\Helpers\Utilidades;

class ServicioUnidadesController extends Controller
{
    //
    public function show($id)
    {
      $arreglo = [];
      $servicioUnidades = ServicioUnidades::where('unidad_id',$id)->get();
      foreach ($servicioUnidades as $key => $value) {
        $partida_servicio = PartidasServicioUnidades::join('catalogo_trafico AS CT','CT.id','=','partidas_servicios_unidades.catalogo_trafico_id')
        ->select('partidas_servicios_unidades.*','CT.operacion_id','CT.nombre')
        ->where('unidad_id',$id)
        ->where('servicio_id',$value->id)->get();
        $arreglo [] = [
          'servicio' => $value,
          'partidas' => $partida_servicio,
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
          if(!$request->hasFile('comprobante')) {
            $servicioUnidades = new ServicioUnidades();
            // $servicioUnidades->fill($request->all());
            $servicioUnidades->fecha_servicio = $request->fecha_servicio;
            $servicioUnidades->fecha_entrega = $request->fecha_entrega;
            $servicioUnidades->kilometraje = $request->kilometraje;
            $servicioUnidades->proveedor = $request->proveedor;
            $servicioUnidades->responsable = $request->responsable;
            $servicioUnidades->total = $request->total;
            $servicioUnidades->comprobante = '';
            $servicioUnidades->unidad_id = $request->unidad_id;
            $servicioUnidades->save();
            $this->llenarCatalogo($request,$valores,$tamanio,$servicioUnidades->id);
            Utilidades::auditar($servicioUnidades, $servicioUnidades->id);
          //    $mantenimientoUnidades->save();
          }
          //--valida que campos del request contienen archivos y realiza el guardado--//
          if($request->hasFile('comprobante'))
          {
              if ($request->hasFile('comprobante')) {
                  //obtiene el nombre del archivo y su extension
                  $FacturaNE = $request->file('comprobante')->getClientOriginalName();
                  //Obtiene el nombre del archivo
                  $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
                  //obtiene la extension
                  $FacturaExt = $request->file('comprobante')->getClientOriginalExtension();
                  //nombre que se guarad en BD
                  $FacturaStore = 'Serv_'.uniqid().'.'.$FacturaExt;
                  //Subida del archivo al servidor ftp
                  Storage::disk('local')->put('Archivos/'.$FacturaStore, fopen($request->file('comprobante'), 'r+'));
              }
              $servicioUnidades = new ServicioUnidades();
              // $servicioUnidades->fill($request->all());
              $servicioUnidades->fecha_servicio = $request->fecha_servicio;
              $servicioUnidades->fecha_entrega = $request->fecha_entrega;
              $servicioUnidades->kilometraje = $request->kilometraje;
              $servicioUnidades->proveedor = $request->proveedor;
              $servicioUnidades->responsable = $request->responsable;
              $servicioUnidades->total = $request->total;
              $servicioUnidades->comprobante = $FacturaStore;
              $servicioUnidades->unidad_id = $request->unidad_id;
              $servicioUnidades->save();
              $this->llenarCatalogo($request,$valores,$tamanio,$servicioUnidades->id);
              Utilidades::auditar($servicioUnidades, $servicioUnidades->id);
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
          $polizaes = ServicioUnidades::where('id',$request->id)->get();
          foreach ($polizaes as $key => $item) {
              $ValorPoliza = $item->comprobante;
              $FacturaStore=$item->comprobante;
          }
          //*FIN
          if (!$request->ajax()) return redirect('/');
          //--Si el request no contiene archivos, solo se actualizan los campos listados--//
          if(!$request->hasFile('factura'))
          {
            $servicioUnidades = ServicioUnidades::findOrFail($request->id);
            // $servicioUnidades->fill($request->all());
            $servicioUnidades->fecha_servicio = $request->fecha_servicio;
            $servicioUnidades->fecha_entrega = $request->fecha_entrega;
            $servicioUnidades->kilometraje = $request->kilometraje;
            $servicioUnidades->proveedor = $request->proveedor;
            $servicioUnidades->responsable = $request->responsable;
            $servicioUnidades->total = $request->total;
            $servicioUnidades->unidad_id = $request->unidad_id;
            $servicioUnidades->save();
            $this->llenarCatalogo($request,$valores,$tamanio,$servicioUnidades->id);
            Utilidades::auditar($servicioUnidades, $servicioUnidades->id);
            //  $poliza->save();
          }
          //--valida que campos del request contienen archivos y realiza el guardado--//
          if($request->hasFile('comprobante'))
          {
              if ($request->hasFile('comprobante')) {
                  //obtiene el nombre del archivo y su extension
                  $FacturaNE = $request->file('comprobante')->getClientOriginalName();
                  //Obtiene el nombre del archivo
                  $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
                  //obtiene la extension
                  $FacturaExt = $request->file('comprobante')->getClientOriginalExtension();
                  //nombre que se guarad en BD
                  $FacturaStore = 'Serv'.uniqid().'.'.$FacturaExt;
                  //Subida del archivo al servidor ftp
                  Storage::disk('local')->put('Archivos/'.$FacturaStore, fopen($request->file('comprobante'), 'r+'));
                  if ($ValorPoliza != '') {
                      //Elimina el archivo en el servidor si requiere ser actualizado
                      Utilidades::ftpSolucionEliminar($ValorPoliza);
                  }
              }
              $servicioUnidades = ServicioUnidades::findOrFail($request->id);
              // $servicioUnidades->fill($request->all());
              $servicioUnidades->fecha_servicio = $request->fecha_servicio;
              $servicioUnidades->fecha_entrega = $request->fecha_entrega;
              $servicioUnidades->kilometraje = $request->kilometraje;
              $servicioUnidades->proveedor = $request->proveedor;
              $servicioUnidades->responsable = $request->responsable;
              $servicioUnidades->total = $request->total;
              $servicioUnidades->comprobante = $FacturaStore;
              $servicioUnidades->unidad_id = $request->unidad_id;
              $servicioUnidades->save();
              $this->llenarCatalogo($request,$valores,$tamanio,$servicioUnidades->id);
              Utilidades::auditar($servicioUnidades, $servicioUnidades->id);
              //$poliza->save();
          }

          return response()->json(array(
              'status' => true
          ));
          /*FIN ACTUALIZAR REGISTRO*/
      }

      return response()->json($request);
    }

    public function llenarCatalogo($request,$valores,$tamanio,$servicio_id)
    {
      $tipo = PartidasServicioUnidades::where('servicio_id',$servicio_id)->first();
      if (isset($tipo) == true) {
        $data = PartidasServicioUnidades::where('servicio_id',$servicio_id)->delete();
        for ($i=0; $i < $tamanio ; $i++) {
          $tipo_servicios = new PartidasServicioUnidades();
          $tipo_servicios->catalogo_trafico_id = $valores[$i];
          $tipo_servicios->unidad_id = $request->unidad_id;
          $tipo_servicios->servicio_id =$servicio_id;
          $tipo_servicios->save();
        }
      }else {
        for ($i=0; $i < $tamanio ; $i++) {
          $tipo_servicios = new PartidasServicioUnidades();
          $tipo_servicios->catalogo_trafico_id = $valores[$i];
          $tipo_servicios->unidad_id = $request->unidad_id;
          $tipo_servicios->servicio_id =$servicio_id;
          $tipo_servicios->save();
        }
      }
      return true;
    }
}
