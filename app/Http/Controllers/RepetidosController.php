<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RelacionSpool;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Controltiempo;
use App\Compras;
use App\Contrato;
use App\ContratoDBP;
use App\ComprasDBP;
use App\Proveedor;
use App\ProveedorDBP;
use App\Empleado;
use App\EmpleadoDBP;
use App\RequisicionhasordencomprasDBP;
use App\requisicionhasordencompras;
use App\ArticuloDBP;
use App\Articulo;
use App\CatServicios;
use App\CatServiciosDBP;
use App\Requisicion;
use App\RequisicionDBP;
use App\PartidaRe;
use App\PartidasReDBP;
use App\Adicionales;
use App\Sueldo;
use App\SueldoDBP;
use \App\Http\Helpers\Utilidades;
use App\CostosProyectosServicios;
use App\FacturasEntradas;
use App\Partidas;
use App\PagoCompra;
use App\Movimiento;
use App\PagosNoRecurrentes;
use DateInterval;
use DatePeriod;
use DateTime;
use App\SueldoSemanaEmpleado;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EquiposImport;

class RepetidosController extends Controller
{


  public function getFolioEntrada($oc)
  {

    $proyecto = DB::table('ordenes_compras AS oc')
       ->select('oc.proyecto_id AS proyecto')
       ->where('oc.id','149')
       ->first();

       $entra = DB::table('entradas AS e')
          ->leftjoin('ordenes_compras AS oc','oc.id','e.orden_compra_id')
          ->select('e.*')
          ->where('oc.proyecto_id',$proyecto->proyecto)
          ->where('e.condicion','1')->orderBy('e.fecha')->get();

    $contador = count($entra) + 1;

    return str_pad(($contador), 4, "0", STR_PAD_LEFT);
  }

  public function getStoke($id)
  {
    $stoke = \App\ComprasDBP::
      join('proyectos AS p', 'p.id', 'ordenes_compras.proyecto_id')
      ->join('stocks AS s', 's.proyecto_id', 'p.id')
      ->select('s.id as stocke_id', 'p.id AS proyecto_id')
      ->where('ordenes_compras.id', $id)
      ->first();

    return $stoke;
  }

  public function crearFolioReqCSCT($proyecto)
  {
    $requisicion_csct =  \App\RequisicionDBP::where('proyecto_id',$proyecto->id)->get();
    $tamanio = count($requisicion_csct) + 1;

    $nuevoFolio = 'REQ-' . strtoupper($proyecto->nombre_corto) . '-' . str_pad($tamanio, 3, "0", STR_PAD_LEFT);
    return $nuevoFolio;
  }


      public function crearPda($requisicion_id)
      {
        try {
          $requisicion_id = \App\PartidasReDBP::select('pda')
            ->where('requisicione_id', '=', $requisicion_id)->max('pda');
          $valor = $requisicion_id == null ? (0 + 1) : ($requisicion_id + 1);
          return $valor;
        } catch (\Throwable $e) {
          Utilidades::errors($e);
        }

      }
      public function empleadoCSCT($id)
       {
         $empleado = 1;
         $empleados = DB::table('empleados')->where('id',$id)->first();
         if(isset($empleados) == true){
         $empleado_csct = \App\EmpleadoDBP::where('nombre',$empleados->nombre)
         ->where('ap_paterno',$empleados->ap_paterno)
         ->where('ap_materno',$empleados->ap_materno)
         ->first();
             if (isset($empleado_csct) == true) {
              $empleado = $empleado_csct->id;
             }
         }
         return $empleado;
       }


  public function getFolio($proyecto)
  {
    // code...
    $requisiciones = \App\RequisicionDBP::where('proyecto_id',$proyecto->id)->get();
    $tamanio = count($requisiciones) == 0 ? 1 : count($requisiciones) + 1;

    $nuevoFolio = 'REQ-' . strtoupper($proyecto->nombre_corto) . '-' . str_pad($tamanio, 3, "0", STR_PAD_LEFT);
    return $nuevoFolio;
  }

  public function facturasentradasfixed($data){

    try {
      $req = \App\RequisicionDBP::where('proyecto_id',$id)->get();

      foreach ($req as $key => $value) {
        $req_upddate = \App\RequisicionDBP::where('id',$value->id)->first();
        $req_upddate->fecha_autorizada = $req_upddate->fecha_solicitud;
        $req_upddate->fecha_completada = $req_upddate->fecha_solicitud;
        $req_upddate->fecha_recibida = $req_upddate->fecha_solicitud + 1;
        $req_upddate->save();
      }
      return response()->json(['status' => true]);

    } catch (\Throwable $e) {
      return response()->json(['status' => false]);
    }






    $oc_csct = \App\ComprasDBP::where('proveedore_id','1')
    ->where('proyecto_id',$id)
    ->get();

    foreach ($oc_csct as $key => $value) {
      $oc_cfw = \App\Compras::where('id',$value->oc_conserflow)->first();
      $proveedor = DB::table('proveedores AS p')->where('p.id',$oc_cfw->proveedore_csct_id)->first();
      $proveedor_csct = \App\ProveedorDBP::where('razon_social',$proveedor->razon_social)->first();
      if (isset($proveedor_csct) == false) {
        $proveedor_csct = new \App\ProveedorDBP();
        $proveedor_csct->nombre = $proveedor->nombre;
        $proveedor_csct->razon_social = $proveedor->razon_social;
        $proveedor_csct->direccion = $proveedor->direccion;
        $proveedor_csct->banco_transferencia = $proveedor->banco_transferencia;
        $proveedor_csct->numero_cuenta = $proveedor->numero_cuenta;
        $proveedor_csct->clabe = $proveedor->clabe;
        $proveedor_csct->dia_credito = $proveedor->dia_credito;
        $proveedor_csct->limite_credito = $proveedor->limite_credito;
        $proveedor_csct->categoria = $proveedor->categoria;
        $proveedor_csct->condicion_pago = $proveedor->condicion_pago;
        $proveedor_csct->giro = $proveedor->giro;
        $proveedor_csct->rfc = $proveedor->rfc;
        $proveedor_csct->ciudad = $proveedor->ciudad;
        $proveedor_csct->estado = $proveedor->estado;
        $proveedor_csct->contacto = $proveedor->contacto;
        $proveedor_csct->telefono = $proveedor->telefono;
        $proveedor_csct->correo = $proveedor->correo;
        $proveedor_csct->pagina = $proveedor->pagina;
        $proveedor_csct->descripcion = $proveedor->descripcion;
        $proveedor_csct->save();
      }

      $oc_csct_up = \App\ComprasDBP::where('id',$value->id)->first();
      $oc_csct_up->proveedore_id = $proveedor_csct->id;
      $oc_csct_up->save();

    }

    return response()->json(['status' => true]);








    try {




      $rhoc = DB::table('ordenes_compras AS oc')
      ->join('proveedores AS p','p.id','oc.proveedore_id')
      ->select('oc.*','p.razon_social')
      ->where('oc.proyecto_id',$data)
      ->get();

      $arreglo = [];

      foreach ($rhoc as $key => $value) {
        $fe = DB::table('facturas_entradas AS fe')->where('fe.orden_compra_id',$value->id)
        ->where('entrada_id','0')
        ->get();

        $rqa = DB::table('requisicion_has_ordencompras AS rhco')
        ->join('requisiciones AS r','r.id','rhco.requisicione_id')
        ->join('articulos AS a','a.id','rhco.articulo_id')
        ->where('rhco.orden_compra_id',$value->id)
        ->where('rhco.articulo_id','!=',null)
        ->select('r.folio','a.descripcion')
        ->first();

        $rqs = DB::table('requisicion_has_ordencompras AS rhco')
        ->join('requisiciones AS r','r.id','rhco.requisicione_id')
        ->join('servicios AS s','s.id','rhco.servicio_id')
        ->where('rhco.orden_compra_id',$value->id)
        ->where('rhco.servicio_id','!=',null)
        ->select('r.folio','s.nombre_servicio As descripcion')
        ->first();

          $total = 0;
          foreach ($fe as $k => $v) {
            $total += (float) $v->total_factura;
          }

        $arreglo [] = [
          'oc' => $value,
          'fe' => $total,
          'ra' => $rqa,
          'rs' => $rqs,
        ];
      }
      return response()->json($arreglo);

      $partidas_requisiciones = \App\PartidasReDBP::
      join('requisiciones AS r','r.id','partidas_requisiciones.requisicione_id')
      ->select('partidas_requisiciones.*','r.folio')
      ->where('proyecto_id',$data)
      ->get();

      $arreglo = [];
      foreach ($partidas_requisiciones as $kpr => $vpr) {
        $rhoc_csct =  \App\RequisicionhasordencomprasDBP::
        where('requisicione_id',$vpr->requisicione_id)
        ->where('articulo_id',$vpr->articulo_id)
        ->where('servicio_id',$vpr->servicio_id)
        ->get();

        $arreglo_entradas =[];
        foreach ($rhoc_csct as $krh => $vrh) {

          $partidas_entradas = \App\PartidaEntradaDBP::
          join('lotes AS l','l.entrada_id','partidas_entradas.id')
          ->join('lote_almacen AS la','la.lote_id','l.id')
          ->select('partidas_entradas.*','l.id AS l_id','la.id AS la_id')
          ->where('req_com_id',$vrh->id)->get();
          $arreglo_salidas = [];
          foreach ($partidas_entradas as $kps => $vps) {
              $salida = \App\PartidasDBP::where('lote_id',$vps->la_id)->get();
              $arreglo_salidas [] = $salida;
          }


          $arreglo_entradas [] = [
            'entrada' => $partidas_entradas,
            'salida' => $arreglo_salidas,
            ];

        }

        $arreglo [] = [
          'requisicion' => $vpr,
          'oc' => $rhoc_csct,
          'entradas_salidas' => $arreglo_entradas,
        ];


      }

      return response()->json($arreglo);

      $valores = explode('&',$data);
      $id = $valores[0];
      $estado = $valores[1];
      $requisicion = \App\Requisicion::where('id',$id)->first();

      $proyecto = DB::table('proyectos AS p')->where('p.id',$requisicion->proyecto_id)->first();
      $proyecto_csct = \App\ProyectoDBP::where('nombre_corto',$proyecto->nombre_corto)->first();
      DB::beginTransaction();
      $requisicion_csct = new \App\RequisicionDBP();
      $requisicion_csct->folio = $requisicion->folio;
      $requisicion_csct->area_solicita_id = $requisicion->area_solicita_id;
      $requisicion_csct->fecha_solicitud = $requisicion->fecha_solicitud;
      $requisicion_csct->descripcion_uso = $requisicion->descripcion_uso;
      $requisicion_csct->tipo_compra = $requisicion->tipo_compra;
      $requisicion_csct->proyecto_id = $proyecto_csct->id;
      $requisicion_csct->estado_id = $estado;
      $requisicion_csct->solicita_empleado_id = $this->empleadoCSCT($requisicion->solicita_empleado_id);
      $requisicion_csct->autoriza_empleado_id = $this->empleadoCSCT($requisicion->autoriza_empleado_id);
      $requisicion_csct->valida_empleado_id = $this->empleadoCSCT($requisicion->valida_empleado_id);
      $requisicion_csct->save();

      $partida_req = DB::table('partidas_requisiciones')->where('requisicione_id',$requisicion->id)->get();
      foreach ($partida_req as $key => $value) {
        $articulo_ = null;
        $servicio_ = null;
        // CREANDO ARTICULOS Y SERVICION EN CSCT
        if ($value->articulo_id != null) {
          $articulo = \App\Articulo::where('id',$value->articulo_id)->first();
          $articulo_csct = \App\ArticuloDBP::where('descripcion',$articulo->descripcion)->first();
          if (isset($articulo_csct) == false) {
            // $articulo_csct = $this->crearArtCSCT($articulo);
            $articulo_csct = new \App\ArticuloDBP();
            $articulo_csct->nombre = $articulo->nombre;
            $articulo_csct->codigo = $articulo->codigo;
            $articulo_csct->descripcion = $articulo->descripcion;
            $articulo_csct->marca = $articulo->marca;
            $articulo_csct->unidad = $articulo->unidad;
            $articulo_csct->comentarios = $articulo->comentarios;
            $articulo_csct->minimo = $articulo->minimo;
            $articulo_csct->maximo = $articulo->maximo;
            $articulo_csct->ficha_tecnica = $articulo->ficha_tecnica;
            $articulo_csct->fotografia = $articulo->fotografia;
            $articulo_csct->grupo_id = $articulo->grupo_id;
            $articulo_csct->calidad_id = $articulo->calidad_id;
            $articulo_csct->tipo_resguardo_id = $articulo->tipo_resguardo_id;
            // $articulo_csct->nombreproveedor = $data->nombre_proveedor;
            $articulo_csct->save();
            $articulo_ = $articulo_csct->id;
          }else {
            $articulo_ = $articulo_csct->id;
          }
        }elseif ($value->servicio_id != null) {
          $servicio = \App\Servicios::where('id',$value->servicio_id)->first();
          $servicio_csct = \App\ServiciosDBP::where('nombre_servicio',$servicio->nombre_servicio)->first();
          if (isset($servicio_csct) == false) {
            // $servicio_csct = $this->crearServCSCT($servicio);
            $servicio_csct = new \App\ServiciosDBP();
            $servicio_csct->nombre_servicio = $servicio->nombre_servicio;
            $servicio_csct->proveedor_marca = $servicio->proveedor_marca;
            $servicio_csct->unidad_medida = $servicio->unidad_medida;
            $servicio_csct->save();
            $servicio_ = $servicio_csct->id;
          }else {
            $servicio_ = $servicio_csct->id;
          }
        }

        $partidare = new \App\PartidasReDBP();
        // $pda_ = $this->crearPda($requisicion_csct->id);
        $partidare->requisicione_id = $requisicion_csct->id;
        $partidare->articulo_id = $articulo_;
        $partidare->servicio_id = $servicio_;
        $partidare->peso = 0;
        $partidare->cantidad = $value->cantidad;
        $partidare->equivalente =  $value->equivalente;
        $partidare->cantidad_compra =   $value->cantidad_compra;
        $partidare->cantidad_almacen =  $value->cantidad_almacen;
        $partidare->fecha_requerido =   $value->fecha_requerido;
        $partidare->pda = $value->pda;
        $partidare->produccion =  $value->produccion;
        $partidare->save();

      }

      DB::commit();
      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      DB::rollBack();
      Utilidades::errors($e);
    }
  }

  public function facturasentradasfixeddoce($id)
    {
      $salidasitio_csct = \App\SalidaSitioDBP::get();
      $arreglo = [];
      foreach ($salidasitio_csct as $key => $value) {
          $partidas = \App\PartidasDBP::where('salida_id',$value->id)->where('tiposalida_id','2')->get();
          $arreglo [] = [
            'salida' => $value,
            'partidas' => count($arreglo),
          ];
      }
      return response()->json($arreglo);

      try {


        $salida_taller = DB::table('salidas')
        ->where('proyecto_id',$id)
        ->get();
        $arreglo = [];

        foreach ($salida_taller as $key => $value) {
          $partidas = DB::table('partidas')
          ->join('lote_almacen AS la','la.id','partidas.lote_id')
          ->join('articulos AS a','a.id','la.articulo_id')
          ->join('grupos AS g','g.id','a.grupo_id')
          ->join('categorias AS c','c.id','g.categoria_id')
          ->select('partidas.*','a.nombre','g.nombre AS gn','c.nombre AS cn')
          ->where('salida_id',$value->id)
          ->where('tiposalida_id','1')
          ->get();

          if (count($partidas) > 0) {
            $arreglo [] = [
              'salida' => $value,
              'partidas' => $partidas
            ];
          }
        }
        DB::beginTransaction();
        foreach ($arreglo as $key_a => $value_a) {
          $salidasitio_csct = new \App\SalidaSitioDBP();
          $salidasitio_csct->fecha = $value_a['salida']->fecha;
          $salidasitio_csct->folio = $value_a['salida']->folio;
          $salidasitio_csct->formato_salida = $value_a['salida']->format_salida;
          $salidasitio_csct->descripcion = $value_a['salida']->descripcion;
          $salidasitio_csct->ubicacion = $value_a['salida']->ubicacion;
          $salidasitio_csct->proyecto_id = $value_a['salida']->proyecto_id;
          $salidasitio_csct->tiposalida_id = 2;
          $salidasitio_csct->empleado_solicita_id = $value_a['salida']->empleado_id;
          $salidasitio_csct->empleado_entrega_id = $value_a['salida']->empleado_entrega_id;
          $salidasitio_csct->empleado_autoriza_id = 151;
          $salidasitio_csct->empleado_recibe_id = 239;
          $salidasitio_csct->supervisores_proyectos_id = $value_a['salida']->supervisores_proyectos_id;
          $salidasitio_csct->save();

          foreach ($value_a['partidas'] as $keyp => $valuep) {

            if ($valuep->cn != 'CONSUMIBLE') {
              $lote_almacen_csct = \App\LoteAlmacenDBP::where('lote_almacen_id_cfw',$valuep->lote_id)->first();
              // dd($valuep);
              $partidas = new \App\PartidasDBP();
              $partidas->salida_id = $salidasitio_csct->id;
              $partidas->tiposalida_id = 2;
              $partidas->lote_id = isset($lote_almacen_csct) == true ? $valuep->lote_id : $lote_almacen_csct->id;
              $partidas->cantidad = $valuep->cantidad;
              $partidas->cantidad_retorno = $valuep->cantidad_retorno;
              $partidas->save();
            }

          }
        }
        // SalidaSitioDBP
        // PartidasDBP
        DB::commit();
        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        DB::rollBack();
        Utilidades::errors($e);
        return response()->json(['status' => false]);
      }



      $valores = explode('&',$data);
             $id = $valores[0];
             $offset = $valores[1];
             $limit = $valores[2];

             try {
               $requisiciones = \App\RequisicionDBP::where('proyecto_id',$id)->get();

               $proyecto = \App\ProyectoDBP::where('id',$id)->first();

               foreach ($requisiciones as $key => $value) {
                 $requisiciones_update = \App\RequisicionDBP::where('id',$value->id)->first();
                 $requisiciones_update->folio = 'REQ-'.$proyecto->nombre_corto.'-'.str_pad(($key + 1), 3, "0", STR_PAD_LEFT);
                 $requisiciones_update->save();

               }

               return response()->json($requisiciones);

             } catch (\Throwable $e) {
               Utilidades::errors($e);
             }



      try {

        DB::beginTransaction();

      $ocs_csct = \App\ComprasDBP::where('proyecto_id',$id)->get();
      $arreglo = [];
      foreach ($ocs_csct as $key => $value) {
        $entradas_cfw = \App\Entrada::where('orden_compra_id',$value->oc_conserflow)->get();
        $arreglo_uno = [];

        foreach ($entradas_cfw as $key_ent => $value_ent) {
          ///CREAR AQUI LA ENTRADA
          $entrada_csct = new \App\EntradaDBP();
          $entrada_csct->fecha = $value_ent->fecha;
          $entrada_csct->formato_entrada = $value_ent->formato_entrada;
          $entrada_csct->empleado_calidad_id = $this->empleadoCSCT($value_ent->empleado_calidad_id);
          $entrada_csct->empleado_almacen_id = $this->empleadoCSCT($value_ent->empleado_almacen_id);
          $entrada_csct->empleado_usuario_id = $this->empleadoCSCT($value_ent->empleado_usuario_id);
          $entrada_csct->condicion = $value_ent->condicion;
          $entrada_csct->orden_compra_id = $value->id;
          Utilidades::auditar($entrada_csct , $entrada_csct->id);
          $entrada_csct->save();

          $sp = $this->getStoke($value->id);


          $partidas_entradas_cfw = \App\PartidaEntrada::
          join('lotes AS l','l.entrada_id','partidas_entradas.id')
          ->join('lote_almacen AS la','la.lote_id','l.id')
          ->select('partidas_entradas.id AS id','l.id AS l_id','la.id AS la_id')
          ->where('partidas_entradas.entrada_id',$value_ent->id)->get();

          foreach ($partidas_entradas_cfw as $key_pec => $value_pec) {
            // aqui se crean las partidas entradas
            $pecfw =  \App\PartidaEntrada::where('id',$value_pec->id)->first();
            $lote_cfw = \App\Lote::where('id',$value_pec->l_id)->first();
            $lote_almacen_cfw = \App\LoteAlmacen::where('id',$value_pec->la_id)->first();

            $articulo = DB::table('articulos')->where('id',$pecfw->articulo_id)->first();
            $articulo_csct = \App\ArticuloDBP::where('descripcion',$articulo->descripcion)->first();

            $rhoc_csct =  \App\RequisicionhasordencomprasDBP::where('orden_compra_id',$value->id)
            ->where('articulo_id',$articulo_csct->id)->first();

            // PartidaEntradaDBP
            $partida_entrada_csct = new \App\PartidaEntradaDBP();
            $partida_entrada_csct->entrada_id = $entrada_csct->id;
            $partida_entrada_csct->req_com_id = $rhoc_csct->id;
            $partida_entrada_csct->articulo_id = $articulo_csct->id;
            $partida_entrada_csct->cantidad = $pecfw->cantidad;
            $partida_entrada_csct->almacene_id = $pecfw->almacene_id;
            $partida_entrada_csct->nivel_id = $pecfw->nivel_id;
            $partida_entrada_csct->stand_id = $pecfw->stand_id;
            $partida_entrada_csct->validacion_calidad = $pecfw->validacion_calidad;
            Utilidades::auditar($partida_entrada_csct, $partida_entrada_csct->id);
            $partida_entrada_csct->save();

            $lote_csct = new \App\LoteDBP();
            $lote_csct->nombre = $lote_cfw->nombre;
            $lote_csct->entrada_id = $partida_entrada_csct->id;
            $lote_csct->articulo_id = $articulo_csct->id;
            $lote_csct->cantidad = $lote_cfw->cantidad;
            Utilidades::auditar($lote_csct , $lote_csct->id);
            $lote_csct->save();


            $lote_almacen_csct = new \App\LoteAlmacenDBP();
            $lote_almacen_csct->lote_id = $lote_csct->id;
            $lote_almacen_csct->almacene_id = $lote_almacen_cfw->almacene_id;
            $lote_almacen_csct->nivel_id = $lote_almacen_cfw->nivel_id;
            $lote_almacen_csct->stand_id = $lote_almacen_cfw->stand_id;
            $lote_almacen_csct->cantidad = $lote_almacen_cfw->cantidad;
            $lote_almacen_csct->stocke_id = $sp->stocke_id;
            $lote_almacen_csct->articulo_id = $articulo_csct->id;
            $lote_almacen_csct->condicion = $lote_almacen_cfw->condicion;
            $lote_almacen_csct->comentario = $lote_almacen_cfw->comentario;
            $lote_almacen_csct->codigo_barras = $lote_almacen_cfw->codigo_barras;
            $lote_almacen_csct->lote_almacen_id_cfw = $value_pec->la_id;
            Utilidades::auditar($lote_almacen_csct, $lote_almacen_csct->id);
            $lote_almacen_csct->save();

            $existencias = new \App\ExistenciaDBP();
            $existencias->id_lote = $lote_csct->id;
            $existencias->articulo_id = $articulo_csct->id;
            $existencias->almacene_id =$lote_almacen_cfw->almacene_id;
            $existencias->nivel_id =  $lote_almacen_cfw->nivel_id;
            $existencias->stand_id =  $lote_almacen_cfw->stand_id;
            $existencias->cantidad =  $lote_almacen_cfw->cantidad;
            Utilidades::auditar($existencias, $existencias->id);
            $existencias->save();

            $hoy = date("Y-m-d");
            $hora = date("H:i:s");
            $movimiento = new \App\MovimientoDBP();
            $movimiento->cantidad = $lote_almacen_cfw->cantidad;
            $movimiento->fecha = $hoy;
            $movimiento->hora = $hora;
            $movimiento->tipo_movimiento = 'Entrada';
            $movimiento->folio = 'Entrada-' . $sp->proyecto_id . $sp->stocke_id;
            $movimiento->proyecto_id =  $sp->proyecto_id;
            $movimiento->lote_id =  $lote_almacen_csct->id;
            $movimiento->stocke_id =  $sp->stocke_id;
            $movimiento->almacene_id = $lote_almacen_cfw->almacene_id;
            $movimiento->stand_id = $lote_almacen_cfw->stand_id;
            $movimiento->nivel_id = $lote_almacen_cfw->nivel_id;
            $movimiento->articulo_id = $articulo_csct->id;
            Utilidades::auditar($movimiento, $movimiento->id);
            $movimiento->save();

            $stokearticulo = new \App\StockArticuloDBP();
            $stokearticulo->cantidad = $lote_almacen_cfw->cantidad;
            $stokearticulo->articulo_id = $articulo_csct->id;
            $stokearticulo->stocke_id = $sp->stocke_id;
            Utilidades::auditar($stokearticulo, $stokearticulo->id);
            $stokearticulo->save();

          }

          // $arreglo_uno [] = [
          //   'entrada' => $value_ent,
          //   'partidas' => $partidas_entradas_cfw,
          // ];
        }


      }
      DB::commit();
      $arreglo = [
        'status' => true
        // 'oc' => $value->folio,
        // // 'partidas' => $rhoc_csct,
        // 'entradas' => $arreglo_uno,
      ];
      return response()->json($arreglo);

    } catch (\Throwable $e) {
      DB::rollBack();
      Utilidades::errors($e);
      $arreglo  = [
        'status' => false
        // 'oc' => $value->folio,
        // // 'partidas' => $rhoc_csct,
        // 'entradas' => $arreglo_uno,
      ];
    }


      /////aagreagar oc y requisiones

      $valores = explode('&',$data);
             $id = $valores[0];
             $offset = $valores[1];
             $limit = $valores[2];


      try {
        $ocs = DB::table('ordenes_compras')
     ->where('proyecto_id',$id)
     ->whereBetween('id',[])
       ->offset($offset)->limit($limit)
     // ->where('conrequisicion','1')
 //            ->select('id')
       ->get();


      DB::beginTransaction();

      $ocs = DB::table('ordenes_compras')
     ->where('proyecto_id',$id)
       ->offset($offset)->limit($limit)
     // ->where('conrequisicion','1')
 //            ->select('id')
       ->get();


      $proyecto = DB::table('proyectos AS p')->where('p.id',$id)->first();
      $proyecto_csct = \App\ProyectoDBP::where('nombre_corto',$proyecto->nombre_corto)->first();
      // return response()->json($ocs);
      $arreglo = [];
      foreach ($ocs as $key_oc => $value_oc) {
        $arreglo_uno = [];
        $oc = DB::table('ordenes_compras')->where('id',$value_oc->id)->first();

        $rhoc = DB::table('requisicion_has_ordencompras')->where('orden_compra_id',$oc->id)->get();

        // $requisicion = \App\Requisicion::where('id',$rhoc[0]->requisicione_id)->first();
        // $partida_req = DB::table('partidas_requisiciones')->where('requisicione_id',$requisicion->id)->get();
        $impuestos = DB::table('impuestos')->where('orden_compra_id',$oc->id)->get();

         $pdboc_ = \App\PartidasDatosBancariosOC::where('orden_compra_id',$oc->id)->first();


        $proveedor = DB::table('proveedores AS p')->where('p.id',$value_oc->proveedore_id)->first();

        $proveedor_csct = \App\ProveedorDBP::where('razon_social',$proveedor->razon_social)->first();
        if (isset($proveedor_csct) == false) {
          $proveedor_csct = new \App\ProveedorDBP();
          $proveedor_csct->nombre = $proveedor->nombre;
          $proveedor_csct->razon_social = $proveedor->razon_social;
          $proveedor_csct->direccion = $proveedor->direccion;
          $proveedor_csct->banco_transferencia = $proveedor->banco_transferencia;
          $proveedor_csct->numero_cuenta = $proveedor->numero_cuenta;
          $proveedor_csct->clabe = $proveedor->clabe;
          $proveedor_csct->dia_credito = $proveedor->dia_credito;
          $proveedor_csct->limite_credito = $proveedor->limite_credito;
          $proveedor_csct->categoria = $proveedor->categoria;
          $proveedor_csct->condicion_pago = $proveedor->condicion_pago;
          $proveedor_csct->giro = $proveedor->giro;
          $proveedor_csct->rfc = $proveedor->rfc;
          $proveedor_csct->ciudad = $proveedor->ciudad;
          $proveedor_csct->estado = $proveedor->estado;
          $proveedor_csct->contacto = $proveedor->contacto;
          $proveedor_csct->telefono = $proveedor->telefono;
          $proveedor_csct->correo = $proveedor->correo;
          $proveedor_csct->pagina = $proveedor->pagina;
          $proveedor_csct->descripcion = $proveedor->descripcion;
          $proveedor_csct->save();
        }



        $oc_csct = new \App\ComprasDBP();
        $oc_csct->folio = str_replace('CONSERFLOW','CSCT',$oc->folio);
        $oc_csct->ordenes_formato = $oc->ordenes_formato;
        $oc_csct->condicion_pago_id = $oc->condicion_pago_id;
        $oc_csct->periodo_entrega = $oc->periodo_entrega;
        $oc_csct->fecha_orden = $oc->fecha_orden;
        $oc_csct->lugar_entrega = $oc->lugar_entrega;
        $oc_csct->observaciones = $oc->observaciones;
        $oc_csct->descuento = $oc->descuento;
        $oc_csct->total = $oc->total;
        $oc_csct->moneda = $oc->moneda;
        $oc_csct->tipo_cambio = $oc->tipo_cambio;
        $oc_csct->referencia = $oc->referencia;
        $oc_csct->cie = $oc->cie;
        $oc_csct->sucursal = $oc->sucursal;
        $oc_csct->proyecto_id = $proyecto_csct->id;//
        $oc_csct->proveedore_id = $proveedor_csct->id;//
        $oc_csct->estado_id = $oc->estado_id;
        $oc_csct->elabora_empleado_id = $this->empleadoCSCT($oc->elabora_empleado_id);//
        $oc_csct->autoriza_empleado_id = $this->empleadoCSCT($oc->autoriza_empleado_id);//
        $oc_csct->condicion = $oc->condicion;
        $oc_csct->created_at = $oc->created_at;
        $oc_csct->updated_at = $oc->updated_at;
        $oc_csct->deleted_at = $oc->deleted_at;
        $oc_csct->comentario_condicion_pago = $oc->comentario_condicion_pago;
        $oc_csct->conrequisicion = $oc->conrequisicion;
        $oc_csct->fecha_probable_pago = $oc->fecha_probable_pago;
        $oc_csct->prioridad = $oc->prioridad;
        $oc_csct->oc_conserflow = $oc->id;
        $oc_csct->save();

        if(isset($pdboc_) == true){
          $pdboc = new \App\PartidasDatosBancariosOCDBP();
          $pdboc->banco = $pdboc_->banco;
          $pdboc->clabe = $pdboc_->clabe;
          $pdboc->cuenta = $pdboc_->cuenta;
          $pdboc->orden_compra_id = $oc_csct->id;
          $pdboc->titular = $pdboc_->titular;
          $pdboc->save();
          }

          foreach ($impuestos as $ki => $vi) {
              $imp = new \App\ImpuestoDBP();
              $imp->orden_compra_id = $oc_csct->id;
              $imp->tipo = $vi->tipo;
              $imp->porcentaje = $vi->porcentaje;
              $imp->retenido  = $vi->retenido;
              $imp->save();
          }


        foreach ($rhoc as $key => $value) {

          //
          // $arreglo_uno [] = [
          //   'partida_oc' => $value,
          //   'partidas_req' => $partida_req,
          // ];
          $articulo_ = null;
          $servicio_ = null;
          // CREANDO ARTICULOS Y SERVICION EN CSCT
          if ($value->articulo_id != null) {
            $articulo = \App\Articulo::where('id',$value->articulo_id)->first();
            $articulo_csct = \App\ArticuloDBP::where('descripcion',$articulo->descripcion)->first();
            if (isset($articulo_csct) == false) {
              // $articulo_csct = $this->crearArtCSCT($articulo);
              $articulo_csct = new \App\ArticuloDBP();
              $articulo_csct->nombre = $articulo->nombre;
              $articulo_csct->codigo = $articulo->codigo;
              $articulo_csct->descripcion = $articulo->descripcion;
              $articulo_csct->marca = $articulo->marca;
              $articulo_csct->unidad = $articulo->unidad;
              $articulo_csct->comentarios = $articulo->comentarios;
              $articulo_csct->minimo = $articulo->minimo;
              $articulo_csct->maximo = $articulo->maximo;
              $articulo_csct->ficha_tecnica = $articulo->ficha_tecnica;
              $articulo_csct->fotografia = $articulo->fotografia;
              $articulo_csct->grupo_id = $articulo->grupo_id;
              $articulo_csct->calidad_id = $articulo->calidad_id;
              $articulo_csct->tipo_resguardo_id = $articulo->tipo_resguardo_id;
              // $articulo_csct->nombreproveedor = $data->nombre_proveedor;
              $articulo_csct->save();
              $articulo_ = $articulo_csct->id;
            }else {
              $articulo_ = $articulo_csct->id;
            }
          }elseif ($value->servicio_id != null) {
            $servicio = \App\Servicios::where('id',$value->servicio_id)->first();
            $servicio_csct = \App\ServiciosDBP::where('nombre_servicio',$servicio->nombre_servicio)->first();
            if (isset($servicio_csct) == false) {
              // $servicio_csct = $this->crearServCSCT($servicio);
              $servicio_csct = new \App\ServiciosDBP();
              $servicio_csct->nombre_servicio = $servicio->nombre_servicio;
              $servicio_csct->proveedor_marca = $servicio->proveedor_marca;
              $servicio_csct->unidad_medida = $servicio->unidad_medida;
              $servicio_csct->save();
              $servicio_ = $servicio_csct->id;
            }else {
              $servicio_ = $servicio_csct->id;
            }
          }
          //FIN DE CREACION DE ARTICULOS Y SERVICIOS EN CSCT
          $requisicion = \App\Requisicion::where('id',$value->requisicione_id)->first();
          $requisicion_csct = \App\RequisicionDBP::where('folio',$requisicion->folio)->first();

          if (isset($requisicion_csct) == false) {
          $requisicion_csct = new \App\RequisicionDBP();
          $requisicion_csct->folio = $requisicion->folio;
          $requisicion_csct->area_solicita_id = $requisicion->area_solicita_id;
          $requisicion_csct->fecha_solicitud = $requisicion->fecha_solicitud;
          $requisicion_csct->descripcion_uso = $requisicion->descripcion_uso;
          $requisicion_csct->tipo_compra = $requisicion->tipo_compra;
          $requisicion_csct->proyecto_id = $proyecto_csct->id;
          $requisicion_csct->estado_id = $requisicion->estado_id;
          $requisicion_csct->solicita_empleado_id = $this->empleadoCSCT($requisicion->solicita_empleado_id);
          $requisicion_csct->autoriza_empleado_id = $this->empleadoCSCT($requisicion->autoriza_empleado_id);
          $requisicion_csct->valida_empleado_id = $this->empleadoCSCT($requisicion->valida_empleado_id);
          $requisicion_csct->save();
          }

          $partida_req = DB::table('partidas_requisiciones')
          ->where('requisicione_id',$value->requisicione_id)
          ->where('articulo_id',$value->articulo_id)
          ->where('servicio_id',$value->servicio_id)->first();
          // return response()->json($partida_req);
          $partidare = new \App\PartidasReDBP();
          $pda_ = $this->crearPda($requisicion_csct->id);
          $partidare->requisicione_id = $requisicion_csct->id;
          $partidare->articulo_id = $articulo_;
          $partidare->servicio_id = $servicio_;
          $partidare->peso = isset($partida_req) == false ? 0 :$partida_req->peso;
          $partidare->cantidad = isset($partida_req) == false ? $value->cantidad :$partida_req->cantidad;
          $partidare->equivalente = isset($partida_req) == false ? 0 :$partida_req->equivalente;
          $partidare->cantidad_compra = isset($partida_req) == false ? $value->cantidad :$partida_req->cantidad_compra;
          $partidare->cantidad_almacen = isset($partida_req) == false ? 0 :$partida_req->cantidad_almacen;
          $partidare->fecha_requerido = isset($partida_req) == false ? $requisicion->fecha_solicitud :$partida_req->fecha_requerido;
          $partidare->pda = isset($partida_req) == false ? $pda_ :$partida_req->pda;
          $partidare->produccion = isset($partida_req) == false ? 0 :$partida_req->produccion;
          $partidare->save();


          $rhoc_csct = new \App\RequisicionhasordencomprasDBP();
          $rhoc_csct->requisicione_id = $requisicion_csct->id;
          $rhoc_csct->orden_compra_id = $oc_csct->id;
          $rhoc_csct->articulo_id = $articulo_;
          $rhoc_csct->servicio_id = $servicio_;
          $rhoc_csct->cantidad = $value->cantidad;
          $rhoc_csct->precio_unitario = $value->precio_unitario;
          $rhoc_csct->tipo_entrada = $value->tipo_entrada;
          $rhoc_csct->condicion = $value->condicion;
          $rhoc_csct->comentario = $value->comentario;
          $rhoc_csct->antigua = $value->antigua;
          $rhoc_csct->cantidad_entrada = $value->cantidad_entrada;
          $rhoc_csct->vehiculo_id = $value->vehiculo_id;
          $rhoc_csct->save();
        }
        // $arreglo [] = [
        //   'oc' => $value_oc,
        //   'partidas' => $arreglo_uno,
        //   'proveedor' => $proveedor,
        //   'proveedor_csct' => $proveedor_csct,
        // ];

      }
      DB::commit();
      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      DB::rollBack();
      Utilidades::errors($e);
      return response()->json(['status' =>  false]);
    }
      ///////

      $partidas = DB::table('partidas AS pa')
      ->join('salidas AS s','s.id','pa.salida_id')
      ->join('lote_almacen AS la','pa.lote_id','la.id')
      ->join('lotes AS l','l.id','la.lote_id')
      ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
      ->join('articulos AS a','a.id','la.articulo_id')
      ->where('pa.tiposalida_id','1')
      ->where('s.proyecto_id','133')
      ->select('la.id','la.stocke_id',
      DB::raw("SUM(pa.cantidad) AS cantidad_salida"),
      'a.descripcion','a.unidad','l.id AS l_id','pe.id AS pe_di','pe.req_com_id'
      )
      ->groupBy('la.id')
      ->get()->toArray();
      // return response()->json($partidas);

      $partidas_r = DB::table('partidas AS pa')
      ->join('salidassitio AS s','s.id','pa.salida_id')
      ->join('lote_almacen AS la','pa.lote_id','la.id')
      ->join('lotes AS l','l.id','la.lote_id')
      ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
      ->join('articulos AS a','a.id','la.articulo_id')
      ->where('pa.tiposalida_id','1')
      ->where('s.proyecto_id','133')
      ->select('la.id','la.stocke_id',
      DB::raw("SUM(pa.cantidad) AS cantidad_salida"),
      'a.descripcion','a.unidad','l.id AS l_id','pe.id AS pe_di','pe.req_com_id'
      )
      ->groupBy('la.id')
      ->get()->toArray();

      $par_uno = array_merge($partidas, $partidas_r);

      $partidas_s = DB::table('partidas AS pa')
      ->join('salidasresguardo AS s','s.id','pa.salida_id')
      ->join('lote_almacen AS la','pa.lote_id','la.id')
      ->join('lotes AS l','l.id','la.lote_id')
      ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
      ->join('articulos AS a','a.id','la.articulo_id')
      ->where('pa.tiposalida_id','1')
      ->where('s.proyecto_id','133')
      ->select('la.id','la.stocke_id',
      DB::raw("SUM(pa.cantidad) AS cantidad_salida"),
      'a.descripcion','a.unidad','l.id AS l_id','pe.id AS pe_di','pe.req_com_id'
      )
      ->groupBy('la.id')
      ->get()->toArray();

      $par = array_merge($par_uno, $partidas_s);

      $arreglo = [];

      foreach ($par as $key => $value) {
        $impuestos = [];
        $folio = '';
        $oc_num = '';
        $comentario = '';

          $partidas_facturas = DB::table('conceptos_oc_xml AS cox')
          ->join('gastos_xml_oc AS gxo','gxo.id','cox.gastos_xml_oc_id')
          ->join('ordenes_compras AS oc','oc.id','gxo.ordenes_compras_gastos_id')
          ->join('proveedores AS p','p.id','oc.proveedore_csct_id')
          ->select('cox.*','gxo.moneda','oc.folio','oc.proyecto_id','p.razon_social')
          ->where('cox.partida_rhc_id',$value->req_com_id)->first();

          $proyecto_origen = DB::table('stocks AS s')
          ->join('proyectos AS p','p.id','s.proyecto_id')
          ->select('p.nombre_corto')
          ->where('s.id',$value->stocke_id)
          ->first();

          $ordencompra = DB::table('requisicion_has_ordencompras AS rhoc')
          ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
          ->where('rhoc.id',$value->req_com_id)
          ->select('oc.folio','rhoc.comentario')
          ->first();
          if (isset($ordencompra) == true) {
            $folio = explode('-',$ordencompra->folio);
            $oc_num = $folio[count($folio) - 1];
            $comentario = $ordencompra->comentario;
          }

          if (isset($partidas_facturas) == true) {
            $impuestos_oc = DB::table('impuestos_oc_xml AS iox')
            ->where('iox.base',$partidas_facturas->importe)
            ->where('iox.base','!=',null)
            ->get();

            $impuestos = $impuestos_oc;
          }

          $arreglo [] = [
            'proyecto_origen' => $proyecto_origen->nombre_corto,
            'orden_compra' => $oc_num,
            'comentario' => $comentario ,
            'salida' => $value,
            'factura' => $partidas_facturas,
            'impuestos' => $impuestos,
          ];

      }

      // return response()->json(count($partidas));
      return response()->json($arreglo);

      $solicitudes = \App\SolicitudViaticos::get();

      $arreglo = [];
      foreach ($solicitudes as $key => $value) {

      $beneficiario = \App\BeneficiarioViatico::where('solicitud_viaticos_id',$value->id)->get();

      if (count($beneficiario) == 0) {
        $sbv = new \App\SolicitudViaticosBeneficiarios();
        $sbv->solicitud_viaticos_id = $value->id;
        $sbv->empleado_beneficiario_id = 0;
        $sbv->datos_bancarios_empleado_id = 0;
        $sbv->banco_nombre = 'Sin datos';
        $sbv->beneficiario_externo = 'Sin datos';
        $sbv->tarjeta = 'Sin datos';
        $sbv->clabe = 'Sin datos';
        $sbv->cuenta = 'Sin datos';
        $sbv->save();
      }elseif (count($beneficiario) > 0) {
        $sbv = new \App\SolicitudViaticosBeneficiarios();
        $sbv->solicitud_viaticos_id = $value->id;
        $sbv->empleado_beneficiario_id = $beneficiario[0]['empleado_beneficiario_id'];
        $sbv->datos_bancarios_empleado_id = $beneficiario[0]['datos_bancarios_empleado_id'];
        $sbv->banco_nombre = $beneficiario[0]['banco_nombre'];
        $sbv->beneficiario_externo = $beneficiario[0]['beneficiario_externo'];
        $sbv->tarjeta = $beneficiario[0]['tarjeta'];
        $sbv->clabe = $beneficiario[0]['clabe'];
        $sbv->cuenta = $beneficiario[0]['cuenta'];
        $sbv->save();
      }

      $arreglo [] = [
        'sol' => $value->folio,
        'con' => count($beneficiario),
      ];
      }

      return response()->json($arreglo);

     //  $ordenes = DB::table('rces')
     // ->join('ordenes_compras AS oc','oc.id','rces.orden_compra_id')
     // ->join('proyectos AS p','p.id','oc.proyecto_id')
     // ->select('rces.*', 'oc.folio', 'oc.id As id_c','p.nombre_corto','oc.fecha_orden')
     // ->where('oc.proyecto_id','105')
     // ->get();
     // return response()->json($ordenes);
     // foreach($ordenes as $key => $value){
     // $folio = 'OC-CSCT-021-'.$value->nombre_corto.'-'.str_pad(($key + 1), 3, "0", STR_PAD_LEFT);
     // $rces = \App\Rces::where('id',$value->id)->first();
     // $rces->folio_csct = $folio;
     // $rces->save();
     // }
     //
     // return response()->json($ordenes);


      // // $valores = explode(',',$uno);
      // $value = '53909.2200';
      // // $precision = (int)$valores[1];
      //
      // $precision = strpos($value,'.') + 3;
      //
      // $fina = substr($value, 0, $precision);
      //
      // // $valor_entero = $valor_separado[0];
      // // $valor_decimal = str_pad(substr($valor_separado[1], 0, 2), $precision, "0", STR_PAD_RIGHT);
      //
      // // $valor_completo = $valor_entero.'.'.$valor_decimal;
      //
      //
      // return response()->json([$fina, $value]);

      $solicitudes = \App\SolicitudViaticos::get();
      foreach ($solicitudes as $key => $value) {
        $detalles_viaticos = \App\DetalleViatico::where('solicitud_viaticos_id','=',$value->id)
        ->select(DB::raw("SUM(transferencia_electronica) AS transferencia"),
        DB::raw("SUM(efectivo) AS efectivo"),
        DB::raw("SUM(total) AS total"))->first();

        $solicitudes_totales = new \App\TotalSolicitudViaticos();
        $solicitudes_totales->solicitud_viatico_id = $value->id;
        $solicitudes_totales->efectivo = $detalles_viaticos->efectivo;
        $solicitudes_totales->transferencia = $detalles_viaticos->transferencia;
        $solicitudes_totales->total = $detalles_viaticos->total;
        $solicitudes_totales->save();
      }

      $solicitudes = \App\SolicitudViaticosDBP::get();
      foreach ($solicitudes as $key => $value) {
        $detalles_viaticos = \App\DetalleViaticoCSCT::where('solicitud_viaticos_id','=',$value->id)
        ->select(DB::raw("SUM(transferencia_electronica) AS transferencia"),
        DB::raw("SUM(efectivo) AS efectivo"),
        DB::raw("SUM(total) AS total"))->first();

        $solicitudes_totales = new \App\TotalSolicitudViaticosDBP();
        $solicitudes_totales->solicitud_viatico_id = $value->id;
        $solicitudes_totales->efectivo = $detalles_viaticos->efectivo;
        $solicitudes_totales->transferencia = $detalles_viaticos->transferencia;
        $solicitudes_totales->total = $detalles_viaticos->total;
        $solicitudes_totales->save();
      }

return response()->json(['status' => true]);

      $fechauno = '2021-04-01';
      $fechados = '2021-04-07';

      $fechas_siete = [];

      $fechaInicio=strtotime($fechauno);
      $fechaFin=strtotime($fechados);

      for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
        $fechas_siete [] = date("Y-m-d", $i);
      }

      return response()->json($fechas_siete);



  $ordenes_compras = \App\ComprasDBP::where('proyecto_id','88')->get();

  foreach ($ordenes_compras as $key => $value) {

    $imp = \App\ImpuestoDBP::where('orden_compra_id',$value->id)->get();

    if (count($imp) == 0) {
      $new_imp = new \App\ImpuestoDBP();
      $new_imp->orden_compra_id = $value->id;
      $new_imp->tipo = 'IVA';
      $new_imp->porcentaje = '16.00';
      $new_imp->retenido = 0;
      $new_imp->save();
    }

  }
  return response()->json(['status' => true]);


  $ep = \App\EntradasPendientes::where('folio','!=',null)->get();

  foreach ($ep as $key => $value) {

    $folio_oc = DB::table('ordenes_compras AS oc')->where('oc.folio','LIKE','%'.$value->folio.'%')->first();
    // $user = Auth::user();}
    $rhoc = \App\requisicionhasordencompras::
    join('ordenes_compras AS oc','oc.id','requisicion_has_ordencompras.orden_compra_id')
    ->where('requisicion_has_ordencompras.id',$value->rhoc_id)
    ->select('requisicion_has_ordencompras.*','oc.folio')
    ->first();

    $lote_almacen = DB::table('lote_almacen AS la')->where('la.id',$value->lote_almacen)->first();
    $date = substr($value->updated_at,0,10);
    $entrada = \App\Entrada::where('fecha',$date)->where('orden_compra_id',$folio_oc->id)->first();
    if (isset($entrada) == false) {
      $entrada = new \App\Entrada();
      $entrada->fecha = $date;
      $entrada->comentarios = 'Entrada OC - EP';
      $entrada->formato_entrada = $this->getFolioEntrada($folio_oc->id);
      $entrada->tipo_entrada_id = 9;
      $entrada->empleado_almacen_id = 3;
      $entrada->empleado_usuario_id = 147;
      $entrada->orden_compra_id = $folio_oc->id;
      Utilidades::auditar($entrada, $entrada->id);
      $entrada->save();
    }

    $partidaentrada = new \App\PartidaEntrada();
    $partidaentrada->entrada_id = $entrada->id;
    $partidaentrada->req_com_id = $value->rhoc_id;
    $partidaentrada->articulo_id = $value->articulo_id;
    $partidaentrada->cantidad = $rhoc->cantidad;
    $partidaentrada->almacene_id = $lote_almacen->almacene_id;
    $partidaentrada->nivel_id = $lote_almacen->nivel_id;
    $partidaentrada->stand_id = $lote_almacen->stand_id;
    $partidaentrada->validacion_calidad = 0;
    Utilidades::auditar($partidaentrada, $partidaentrada->id);
    $partidaentrada->save();
  }






    $partidas = DB::table('conceptos_oc_xml AS cox')
    ->join('requisicion_has_ordencompras AS rhoc','rhoc.id','cox.partida_rhc_id')
    ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
    ->join('proyectos AS p','p.id','oc.proyecto_id')
    ->select('p.nombre_corto','oc.folio','cox.claveprodserv','cox.descripcion','cox.claveunidad','cox.unidad',
    'cox.cantidad','cox.valorunitario','cox.importe')
    ->where('partida_rhc_id','!=',null)
    ->get();


    return response()->json($partidas);

    $palabras = ['DE', 'CON', 'PARA', 'A' ,'POR','EN','en', 'de', 'con', 'para', 'a','por'];

    $busqueda = array_search('codo', $palabras);
    $resultado = is_numeric($busqueda) == true ? true : $busqueda;

$data = DB::table('articulos')
->where('descripcion','=','')->get();

foreach ($data as $key => $value) {
  $art = \App\Articulo::where('id',$value->id)->first();
  $art->descripcion = $value->nombre;
  $art->save();
}
return response()->json(count($data));

$data = DB::table('facturas_entradas AS fe')
->join('ordenes_compras AS oc','oc.id','fe.orden_compra_id')
->where('fe.tipo','1')
->where('fe.orden_compra_id','!=',NULL)
->where('oc.proyecto_id',$id)
->select('oc.id','oc.folio','oc.proyecto_id')
->groupBy('oc.id')
->get();

return response()->json($data);


$arreglo = [];
foreach ($data as $key => $value) {
  $xml = DB::table('gastos_xml_oc AS gx')
  ->where('gx.ordenes_compras_gastos_id',$value->orden_compra_id)
  ->where('gx.tipo','2')->get();

  $arreglo [] = [
    'factura_entrada' => $value,
    'xml_data_grl' => $xml,
  ];
}

return response()->json($arreglo);


  //////////////////////////////////////
  $requisiciones = DB::table('requisiciones AS r')->where('folio','LIKE','%AEROPUERTO SANTA LUCIA%')->get();
  $arreglo = [];
  foreach ($requisiciones as $key => $value) {

    $valores = explode('-',$value->folio);
    $folio = '';
    if (count($valores) === 3) {
      $folio = $valores[0].'-AEROPUERTO FELIPE ANGELES-'.$valores[2];
    }elseif (count($valores) === 6) {
      //  0    1        2        3             4   5
      // OC-CONSERFLOW-021-PETROFAC PAQUETE 1-059-SR
      $folio = $valores[0].'-'.$valores[1].'-'.$valores[2].'-AEROPUERTO FELIPE ANGELES-'.$valores[4].'-'.$valores[5];
    }
    if ($folio != '') {
      // code...
    $req = \App\Requisicion::where('id',$value->id)->first();
    $req->folio = $folio;
    $req->save();
  }

    $arreglo [] = count($valores);

  }
  return response()->json($arreglo);
}

}
