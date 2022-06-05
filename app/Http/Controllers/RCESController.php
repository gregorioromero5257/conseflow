<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use Illuminate\Http\Request;

class RCESController extends Controller
{
    //
    public function getProyectos()
    {
      $data = DB::table('ordenes_compras AS oc')
      ->join('proyectos AS p','p.id','oc.proyecto_id')
      ->where('proveedore_id','1')
      ->whereNotIn('oc.proyecto_id',['0','1'])
      ->select('oc.proyecto_id AS id','p.nombre_corto AS name')
      ->groupBy('oc.proyecto_id')
      ->get();

      return response()->json($data);

    }

    public function getProyecto($id)
    {
      $data = DB::table('ordenes_compras AS oc')
      ->join('proveedores AS pcfw','pcfw.id','oc.proveedore_id')
      ->join('proveedores AS pcsct','pcsct.id','oc.proveedore_csct_id')
      ->where('oc.proveedore_id','1')
      ->where('oc.proyecto_id',$id)
      ->select('oc.*','pcfw.razon_social AS rs_cfw','pcsct.razon_social AS rs_csct')
      ->get();


      return response()->json($data);
    }

    public function getPartidas($id)
    {
      $arreglo = [];
      $compras_art = DB::table('requisicion_has_ordencompras')
      ->leftJoin('requisiciones AS req', 'req.id', '=', 'requisicion_has_ordencompras.requisicione_id')
      ->leftJoin('ordenes_compras AS oc', 'oc.id' , '=', 'requisicion_has_ordencompras.orden_compra_id')
      ->leftJoin('articulos AS a', 'a.id', '=', 'requisicion_has_ordencompras.articulo_id')
      ->leftJoin('proyectos AS p', 'p.id', '=', 'req.proyecto_id')
      ->leftJoin('costos_proyectos_servicios AS CPS','CPS.requisicion_has_ordencompra_id','=','requisicion_has_ordencompras.id')
      ->leftJoin('recs_entradas_salidas AS res','res.rhoc_id','requisicion_has_ordencompras.id')
      ->join('partidas_requisiciones', function ($join){
        $join->on('requisicion_has_ordencompras.requisicione_id','=','partidas_requisiciones.requisicione_id')
        ->on('requisicion_has_ordencompras.articulo_id','=','partidas_requisiciones.articulo_id');
      })
      ->select('requisicion_has_ordencompras.*','res.id AS res_id',
      DB::raw('requisicion_has_ordencompras.cantidad * requisicion_has_ordencompras.precio_unitario as total'),'req.id AS rid',
      'req.folio AS rf','partidas_requisiciones.comentario',
      'req.fecha_solicitud AS rfs','p.nombre_corto AS proyecton',
      'a.id AS aid','a.descripcion AS ad','CPS.catalogo_centro_costos_id')
      ->where('requisicion_has_ordencompras.orden_compra_id', '=', $id)
      ->where('requisicion_has_ordencompras.articulo_id','!=','null')
      ->get()->toArray();

      $compras_serv = DB::table('requisicion_has_ordencompras')
      ->leftJoin('requisiciones AS req', 'req.id', '=', 'requisicion_has_ordencompras.requisicione_id')
      ->leftJoin('ordenes_compras AS oc', 'oc.id' , '=', 'requisicion_has_ordencompras.orden_compra_id')
      ->leftJoin('servicios AS s', 's.id', '=', 'requisicion_has_ordencompras.servicio_id')
      ->leftJoin('proyectos AS p', 'p.id', '=', 'req.proyecto_id')
      ->leftJoin('costos_proyectos_servicios AS CPS','CPS.requisicion_has_ordencompra_id','=','requisicion_has_ordencompras.id')
      ->leftJoin('recs_entradas_salidas AS res','res.rhoc_id','requisicion_has_ordencompras.id')
      ->join('partidas_requisiciones', function ($join){
        $join->on('requisicion_has_ordencompras.requisicione_id','=','partidas_requisiciones.requisicione_id')
        ->on('requisicion_has_ordencompras.servicio_id','=','partidas_requisiciones.servicio_id');
      })
      ->select('requisicion_has_ordencompras.*','res.id AS res_id',
      DB::raw('requisicion_has_ordencompras.cantidad * requisicion_has_ordencompras.precio_unitario as total'),
      'req.id AS rid','req.folio AS rf','req.fecha_solicitud AS rfs','p.nombre_corto AS proyecton',
      's.id AS aid','partidas_requisiciones.comentario','s.nombre_servicio AS ad','CPS.catalogo_centro_costos_id')
      ->where('requisicion_has_ordencompras.orden_compra_id', '=', $id)
      ->where('requisicion_has_ordencompras.servicio_id','!=','null')
      ->get()->toArray();

      $compras = array_merge($compras_art,$compras_serv);

      $total_oc = \App\requisicionhasordencompras::select(DB::raw('SUM(cantidad) AS suma_total_oc'))
      ->where('orden_compra_id',$id)->first();

      $total_con_en = \App\requisicionhasordencompras::
      select(DB::raw('(SUM(requisicion_has_ordencompras.cantidad)) AS suma_total_con_en'))
      ->where('requisicion_has_ordencompras.orden_compra_id',$id)
      ->where('requisicion_has_ordencompras.condicion','0')->first();

      $total = $total_oc->suma_total_oc == null ? 0 : $total_oc->suma_total_oc;
      $total_con_entradas = $total_con_en->suma_total_con_en == null ? 0 : $total_con_en->suma_total_con_en;
      $calculo= '';

      $ordenes_compras = DB::table('ordenes_compras AS oc')
      ->where('id',$id)
      ->get();

      if (($total_con_entradas) == 0) {
      $calculo = $this->calculo_dia_entrega($ordenes_compras);
      $porcentaje = 0;
      }else {
      $porcentaje = (($total_con_entradas) * 100) / ($total);
      }

      $arreglo = [
        'partidas' => $compras,
        'porcentaje' => $porcentaje,
        'calculo' => $calculo,
      ];


      return response()->json($arreglo);

    }

    public function calculo_dia_entrega($data)
    {
      try {
        $fecha_orden = $data->fecha_orden;
        $periodo_entrega = $data->periodo_entrega;
        $valores = explode(' ',$periodo_entrega);
        $dias = 0;

        if (count($valores) == 2) {
          if ($valores[1] === 'día' || $valores[1] === 'días' || $valores[1] === 'dias' || $valores[1] === 'dia' || $valores[1] === 'DIAS' || $valores[1] === 'DIA') {
            $dias = $valores[0];
          }elseif ($valores[1] === 'semana' || $valores[1] === 'semanas' || $valores[1] === 'SEMANAS' || $valores[1] === 'SEMANA') {
            $dias = $valores[0] * 7;
          }
        }

        $fecha = date("Y-m-d",strtotime($fecha_orden."+ ".$dias." days"));

        return $fecha;
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function generarCSCT($id)
    {
      try {
        $estado_rces = DB::table('rces')->where('orden_compra_id',$id)->first();
        if (isset($estado_rces) == true ) {
          return response()->json(['status' => true, 'estado' => 2]);
        }
        //consultamos la orden de compra para obtener el proyecto y el proveedor
        $orden_compra = DB::table('ordenes_compras AS oc')
        ->where('oc.id',$id)->first();

        $impuestos = DB::table('impuestos')->where('orden_compra_id',$id)->get();

        $pdboc_ = \App\PartidasDatosBancariosOC::where('orden_compra_id',$id)->first();

        $proveedor = DB::table('proveedores AS p')->where('p.id',$orden_compra->proveedore_csct_id)->first();
        $proyecto = DB::table('proyectos AS p')->where('p.id',$orden_compra->proyecto_id)->first();

        DB::beginTransaction();

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
          // $proveedor_csct = $this->crearProveedorCSCT($proveedor);
        }

        $proyecto_csct = \App\ProyectoDBP::where('nombre_corto',$proyecto->nombre_corto)->first();
        if (isset($proyecto_csct) == false) {
          $proyecto_csct = new \App\ProyectoDBP();
          $proyecto_csct->nombre = $proyecto->nombre;
          $proyecto_csct->nombre_corto = $proyecto->nombre_corto;
          $proyecto_csct->monto_total = $proyecto->monto_total;
          $proyecto_csct->clave = $proyecto->clave;
          $proyecto_csct->ciudad = $proyecto->ciudad;
          $proyecto_csct->fecha_inicio = $proyecto->fecha_inicio;
          $proyecto_csct->fecha_fin = $proyecto->fecha_fin;
          $proyecto_csct->condicion = $proyecto->condicion;
          $proyecto_csct->cliente_id = $proyecto->cliente_id;
          $proyecto_csct->pm_cliente = $proyecto->pm_cliente;
          $proyecto_csct->pm_interno = $proyecto->pm_interno;
          $proyecto_csct->adicional = $proyecto->adicional;
          $proyecto_csct->proyecto_id = $proyecto->proyecto_id;
          $proyecto_csct->proyecto_subcategorias_id = $proyecto->proyecto_subcategorias_id;
          $proyecto_csct->save();
          // $proyecto_csct = $this->crearProyectoCSCT($proyecto);
        }

        $rhoc = \App\requisicionhasordencompras::where('orden_compra_id',$id)->get();

        $requisicion_csct = new \App\RequisicionDBP();
        $requisicion_csct->folio = $this->crearFolioReqCSCT($proyecto_csct);
        $requisicion_csct->area_solicita_id = 1;
        $requisicion_csct->fecha_solicitud = $orden_compra->fecha_orden;
        $requisicion_csct->descripcion_uso = '';
        $requisicion_csct->tipo_compra = 1;
        $requisicion_csct->proyecto_id = $proyecto_csct->id;
        $requisicion_csct->estado_id = 5;
        $requisicion_csct->solicita_empleado_id = 8;
        $requisicion_csct->autoriza_empleado_id = 11;
        $requisicion_csct->valida_empleado_id = 11;
        $requisicion_csct->save();

        $orden_compra_csct = new \App\ComprasDBP();
        $folio = $this->crearFolioOcCSCT($proyecto_csct);
        $orden_compra_csct->folio = $folio;
        $orden_compra_csct->condicion_pago_id = $orden_compra->condicion_pago_id;
        $orden_compra_csct->periodo_entrega = $orden_compra->periodo_entrega;
        $orden_compra_csct->fecha_orden = $orden_compra->fecha_orden;
        $orden_compra_csct->lugar_entrega = $orden_compra->lugar_entrega;
        $orden_compra_csct->observaciones = $orden_compra->observaciones;
        $orden_compra_csct->descuento = $orden_compra->descuento;
        $orden_compra_csct->tipo_cambio = $orden_compra->tipo_cambio;
        $orden_compra_csct->moneda = $orden_compra->moneda;
        $orden_compra_csct->referencia = $orden_compra->referencia;
        $orden_compra_csct->cie = $orden_compra->cie;
        $orden_compra_csct->sucursal = $orden_compra->sucursal;
        $orden_compra_csct->proveedore_id = $proveedor_csct->id;
        $orden_compra_csct->proyecto_id = $proyecto_csct->id;
        $orden_compra_csct->elabora_empleado_id = 7;
        $orden_compra_csct->autoriza_empleado_id = 147;
        $orden_compra_csct->comentario_condicion_pago = $orden_compra->comentario_condicion_pago;
        $orden_compra_csct->conrequisicion = $orden_compra->conrequisicion;
        $orden_compra_csct->fecha_probable_pago = $orden_compra->fecha_probable_pago;
        $orden_compra_csct->prioridad = $orden_compra->prioridad;
        $orden_compra_csct->total = $orden_compra->total;
        // $orden_compra_csct->total_aux = $orden_compra->total_aux;
        $orden_compra_csct->save();

        $pdboc = new \App\PartidasDatosBancariosOCDBP();
        $pdboc->banco = $pdboc_->banco;
        $pdboc->clabe = $pdboc_->clabe;
        $pdboc->cuenta = $pdboc_->cuenta;
        $pdboc->orden_compra_id = $orden_compra_csct->id;
        $pdboc->titular = $pdboc_->titular;
        $pdboc->save();

        foreach ($impuestos as $ki => $vi) {
            $imp = new \App\ImpuestoDBP();
            $imp->orden_compra_id = $orden_compra_csct->id;
            $imp->tipo = $vi->tipo;
            $imp->porcentaje = $vi->porcentaje;
            $imp->retenido  = $vi->retenido;
            $imp->save();
        }

        foreach ($rhoc as $key => $value) {
          $articulo_ = null;
          $servicio_ = null;
          //CREANDO ARTICULOS Y SERVICION EN CSCT
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

          $partidare = new \App\PartidasReDBP();
          $pda_ = $this->crearPda($requisicion_csct->id);
        // return response()->json(gettype($pda_));
          $partidare->requisicione_id = $requisicion_csct->id;
          $partidare->articulo_id = $articulo_;
          $partidare->servicio_id = $servicio_;
          $partidare->peso = 0;
          $partidare->cantidad = $value->cantidad;
          $partidare->equivalente = 0;
          $partidare->cantidad_compra = $value->cantidad;
          $partidare->cantidad_almacen = 0;
          $partidare->fecha_requerido = $orden_compra->fecha_orden;
          $partidare->pda = $pda_;
          $partidare->produccion = 0;
          $partidare->save();


          $rhoc_csct = new \App\RequisicionhasordencomprasDBP();
          $rhoc_csct->requisicione_id = $requisicion_csct->id;
          $rhoc_csct->orden_compra_id = $orden_compra_csct->id;
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

        $rces = new \App\Rces();
        $rces->orden_compra_id = $id;
        $rces->requisicion_compra = 1;
        $rces->folio_csct = $orden_compra_csct->folio;
        $rces->save();

        DB::commit();

        return response()->json(['status' => true, 'estado' => 1]);
      } catch (\Throwable $e) {
        DB::rollBack();
        Utilidades::errors($e);
        return response()->json(['status' => true, 'estado' => 3]);
      }
    }

    public function crearProveedorCSCT($data)
    {
      try {

        $proveedor = new \App\ProveedorDBP();
        $proveedor->nombre = $data->nombre;
        $proveedor->razon_social = $data->razon_social;
        $proveedor->direccion = $data->direccion;
        $proveedor->banco_transferencia = $data->banco_transferencia;
        $proveedor->numero_cuenta = $data->numero_cuenta;
        $proveedor->clabe = $data->clabe;
        $proveedor->dia_credito = $data->dia_credito;
        $proveedor->limite_credito = $data->limite_credito;
        $proveedor->categoria = $data->categoria;
        $proveedor->condicion_pago = $data->condicion_pago;
        $proveedor->giro = $data->giro;
        $proveedor->rfc = $data->rfc;
        $proveedor->ciudad = $data->ciudad;
        $proveedor->estado = $data->estado;
        $proveedor->contacto = $data->contacto;
        $proveedor->telefono = $data->telefono;
        $proveedor->correo = $data->correo;
        $proveedor->pagina = $data->pagina;
        $proveedor->descripcion = $data->descripcion;
        $proveedor->save();

        return $proveedor;
      } catch (\Throwable $e) {
        Utilidades::errors($e);
        return false;
      }
    }

    public function crearProyectoCSCT($data)
    {
      try {

        $proyecto = new \App\ProyectoDBP();
        $proyecto->nombre = $data->nombre;
        $proyecto->nombre_corto = $data->nombre_corto;
        $proyecto->monto_total = $data->monto_total;
        $proyecto->clave = $data->clave;
        $proyecto->ciudad = $data->ciudad;
        $proyecto->fecha_inicio = $data->fecha_inicio;
        $proyecto->fecha_fin = $data->fecha_fin;
        $proyecto->condicion = $data->condicion;
        $proyecto->cliente_id = $data->cliente_id;
        $proyecto->pm_cliente = $data->pm_cliente;
        $proyecto->pm_interno = $data->pm_interno;
        $proyecto->adicional = $data->adicional;
        $proyecto->proyecto_id = $data->proyecto_id;
        $proyecto->proyecto_subcategoria_id = $data->proyecto_subcategoria_id;
        $proyecto->save();

        return $proyecto;

      } catch (\Throwable $e) {
        Utilidades::errors($e);
        return false;
      }
    }

    public function crearArtCSCT($data)
    {
      try {

        $articulo = new \App\ArticuloDBP();
        $articulo->nombre = $data->nombre;
        $articulo->codigo = $data->codigo;
        $articulo->descripcion = $data->descripcion;
        $articulo->marca = $data->marca;
        $articulo->unidad = $data->unidad;
        $articulo->comentarios = $data->comentarios;
        $articulo->minimo = $data->minimo;
        $articulo->maximo = $data->maximo;
        $articulo->ficha_tecnica = $data->ficha_tecnica;
        $articulo->fotografia = $data->fotografia;
        $articulo->grupo_id = $data->grupo_id;
        $articulo->calidad_id = $data->calidad_id;
        $articulo->tipo_resguardo_id = $data->tipo_resguardo_id;
        // $articulo->nombreproveedor = $data->nombre_proveedor;
        $articulo->save();

        return $articulo;

      } catch (\Throwable $e) {
        Utilidades::errors($e);
        return false;
      }
    }

    public function crearServCSCT($data)
    {
      try {
        $serv = new \App\ServiciosDBP();
        $serv->nombre_servicio = $data->nombre_servicio;
        $serv->proveedor_marca = $data->proveedor_marca;
        $serv->unidad_medida = $data->unidad_medida;
        $serv->save();

        return $serv;
      } catch (\Throwable $e) {
        Utilidades::errors($e);
        return false;
      }
    }

    public function crearFolioReqCSCT($proyecto)
    {
      $requisicion_csct =  \App\RequisicionDBP::where('proyecto_id',$proyecto->id)->get();
      $tamanio = count($requisicion_csct) + 1;

      $nuevoFolio = 'REQ-' . strtoupper($proyecto->nombre_corto) . '-' . str_pad($tamanio, 3, "0", STR_PAD_LEFT);
      return $nuevoFolio;
    }

    public function crearFolioOcCSCT($proyecto)
    {
      $orden_compra_csct = \App\ComprasDBP::where('proyecto_id',$proyecto->id)->get();
      $tamanio = count($orden_compra_csct) + 1;

      $nuevoFolio = 'OC-CSCT-' . substr(date("Y"), 1, 3) . '-' . strtoupper($proyecto->nombre_corto) . '-' . str_pad($tamanio, 3, "0", STR_PAD_LEFT);
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

    public function generarESCSCT($id)
    {
      try {
        DB::beginTransaction();
        //CONSULTA A LA TABLA requisicion_has_ordencompras, PARA OBTENER LA ID DE LA OC,
        //EL ID DE ARTICULO Y EL ID DE LA TABLA rces PARA SABER EL FOLIO DE LA OC EN CSCT
        $rhoc = DB::table('requisicion_has_ordencompras AS rhoc')
        ->where('id',$id)->first();

        //OBTENER EL REGISTRO DE LA TABLA rces PARA OBTENER FOLIO DE OC CSCT
        $rces = DB::table('rces')->where('orden_compra_id',$rhoc->orden_compra_id)->first();

        //BUSCAMOS EL ARTICULO
        $articulo = DB::table('articulos')->where('id',$rhoc->articulo_id)->first();


        $oc_csct = \App\ComprasDBP::where('folio','LIKE',$rces->folio_csct)->first();
        $articulo_csct = \App\ArticuloDBP::where('descripcion',$articulo->descripcion)->first();

        $partidas_entradas = DB::table('partidas_entradas')->where('req_com_id',$id)->get();

        $rhoc_csct = \App\RequisicionhasordencomprasDBP::where('orden_compra_id',$oc_csct->id)
        ->where('articulo_id',$articulo_csct->id)
        ->first();

        $rces_save = \App\Rces::where('orden_compra_id',$rhoc->orden_compra_id)->first();


        foreach ($partidas_entradas as $kpe => $vpa) {
          $entrada = DB::table('entradas')->where('id',$vpa->entrada_id)->first();

          $entrada_csct = \App\EntradaDBP::where('fecha',$entrada->fecha)
          ->where('formato_entrada',$entrada->formato_entrada)
          ->where('orden_compra_id',$oc_csct->id)
          ->first();

          if (isset($entrada_csct) == false) {
            $entrada_csct = new \App\EntradaDBP();
            $entrada_csct->fecha = $entrada->fecha;
            $entrada_csct->formato_entrada = $entrada->formato_entrada;
            $entrada_csct->empleado_calidad_id = $entrada->empleado_calidad_id;
            $entrada_csct->empleado_almacen_id = $entrada->empleado_almacen_id;
            $entrada_csct->empleado_usuario_id = $entrada->empleado_usuario_id;
            $entrada_csct->condicion = $entrada->condicion;
            $entrada_csct->orden_compra_id = $oc_csct->id;
            Utilidades::auditar($entrada_csct , $entrada_csct->id);
            $entrada_csct->save();
          }

          $sp = $this->getStoke($rhoc_csct->id);

          $partida_entrada_csct = new \App\PartidaEntradaDBP();
          $partida_entrada_csct->entrada_id = $entrada_csct->id;
          $partida_entrada_csct->req_com_id = $rhoc_csct->id;
          $partida_entrada_csct->articulo_id = $articulo_csct->id;
          $partida_entrada_csct->cantidad = $vpa->cantidad;
          $partida_entrada_csct->almacene_id = $vpa->almacene_id;
          $partida_entrada_csct->nivel_id = $vpa->nivel_id;
          $partida_entrada_csct->stand_id = $vpa->stand_id;
          $partida_entrada_csct->validacion_calidad = $vpa->validacion_calidad;
          Utilidades::auditar($partida_entrada_csct, $partida_entrada_csct->id);
          $partida_entrada_csct->save();

          $lote = \App\Lote::where('entrada_id',$vpa->id)->first();
          $lote_almacen = \App\LoteAlmacen::where('lote_id',$lote->id)->first();

          $lote_csct = new \App\LoteDBP();
          $lote_csct->nombre = $lote->nombre;
          $lote_csct->entrada_id = $partida_entrada_csct->id;
          $lote_csct->articulo_id = $articulo_csct->id;
          $lote_csct->cantidad = $lote->cantidad;
          Utilidades::auditar($lote_csct , $lote_csct->id);
          $lote_csct->save();


          $lote_almacen_csct = new \App\LoteAlmacenDBP();
          $lote_almacen_csct->lote_id = $lote_csct->id;
          $lote_almacen_csct->almacene_id = $lote_almacen->almacene_id;
          $lote_almacen_csct->nivel_id = $lote_almacen->nivel_id;
          $lote_almacen_csct->stand_id = $lote_almacen->stand_id;
          $lote_almacen_csct->cantidad = $lote_almacen->cantidad;
          $lote_almacen_csct->stocke_id = $sp->stocke_id;
          $lote_almacen_csct->articulo_id = $articulo_csct->id;
          $lote_almacen_csct->condicion = $lote_almacen->condicion;
          Utilidades::auditar($lote_almacen_csct, $lote_almacen_csct->id);
          $lote_almacen_csct->save();

          $existencias = new \App\ExistenciaDBP();
          $existencias->id_lote = $lote_csct->id;
          $existencias->articulo_id = $articulo_csct->id;
          $existencias->almacene_id =$lote_almacen->almacene_id;
          $existencias->nivel_id =  $lote_almacen->nivel_id;
          $existencias->stand_id =  $lote_almacen->stand_id;
          $existencias->cantidad =  $lote_almacen->cantidad;
          Utilidades::auditar($existencias, $existencias->id);
          $existencias->save();

          $hoy = date("Y-m-d");
          $hora = date("H:i:s");
          $movimiento = new \App\MovimientoDBP();
          $movimiento->cantidad = $lote_almacen->cantidad;
          $movimiento->fecha = $hoy;
          $movimiento->hora = $hora;
          $movimiento->tipo_movimiento = 'Entrada';
          $movimiento->folio = 'Entrada-' . $sp->proyecto_id . $sp->stocke_id;
          $movimiento->proyecto_id =  $sp->proyecto_id;
          $movimiento->lote_id =  $lote_almacen_csct->id;
          $movimiento->stocke_id =  $sp->stocke_id;
          $movimiento->almacene_id = $lote_almacen->almacene_id;
          $movimiento->stand_id = $lote_almacen->stand_id;
          $movimiento->nivel_id = $lote_almacen->nivel_id;
          $movimiento->articulo_id = $articulo_csct->id;
          Utilidades::auditar($movimiento, $movimiento->id);
          $movimiento->save();

          $stokearticulo = new \App\StockArticuloDBP();
          $stokearticulo->cantidad = $lote_almacen->cantidad;
          $stokearticulo->articulo_id = $articulo_csct->id;
          $stokearticulo->stocke_id = $sp->stocke_id;
          Utilidades::auditar($stokearticulo, $stokearticulo->id);
          $stokearticulo->save();


          //BUSCANDO SALIDAS DEL ARTICULO
          $partidas = DB::table('partidas')->where('lote_id',$lote_almacen->id)->get();

          //CREAMOS LA SALIDA
          if (count($partidas) > 0) {
            foreach ($partidas as $ks => $vs) {

              //SALIDA A TALLER
              if ($vs->tiposalida_id == 1) {
                $salida = DB::table('salidas')->where('id',$vs->salida_id)->first();

                $salida_csct = \App\SalidaDBP::where('fecha',$salida->fecha)
                ->where('folio',$salida->folio)->first();

                $proyecto_csct = $this->BuscarProyectoCSCT($salida->proyecto_id);

                if (isset($salida_csct) == false) {
                  $salida_csct = new \App\SalidaDBP();
                  $salida_csct->fecha = $salida->fecha;
                  $salida_csct->folio = $salida->folio;
                  $salida_csct->ubicacion = $salida->ubicacion;
                  $salida_csct->proyecto_id = $proyecto_csct;
                  $salida_csct->tiposalida_id = 1;
                  $salida_csct->empleado_id = 11;
                  $salida_csct->empleado_entrega_id = $salida->empleado_entrega_id;
                  $salida_csct->empleado_recibe_id = $salida->empleado_recibe_id;
                  $salida_csct->empleado_autoriza_id = $salida->empleado_autoriza_id;
                  $salida_csct->condicion = 1;
                  Utilidades::auditar($salida_csct, $salida_csct->id);
                  $salida_csct->save();
                }

                $partidas_csct_salidas = new \App\PartidasDBP();
                $partidas_csct_salidas->salida_id = $salida_csct->id;
                $partidas_csct_salidas->tiposalida_id = 1;
                $partidas_csct_salidas->lote_id = $lote_almacen_csct->id;
                $partidas_csct_salidas->cantidad = $vs->cantidad;
                $partidas_csct_salidas->cantidad_retorno = $vs->cantidad_retorno;
                Utilidades::auditar($partidas_csct_salidas, $partidas_csct_salidas->id);
                $partidas_csct_salidas->save();
                // return response()->json($partidas_csct_salidas);
                $rces_save->salidas = $rces->salidas + 1;


              }elseif ($vs->tiposalida_id == 2) {//sitio
                $salida = DB::table('salidassitio')->where('id',$vs->salida_id)->first();

                $salida_csct = \App\SalidaSitioDBP::where('fecha',$salida->fecha)
                ->where('folio',$salida->folio)->first();

                $proyecto_csct = $this->BuscarProyectoCSCT($salida->proyecto_id);

                if (isset($salida_csct) == false) {
                  $salida_csct = new \App\SalidaSitioDBP();
                  $salida_csct->fecha = $salida->fecha;
                  $salida_csct->folio = $salida->folio;
                  $salida_csct->ubicacion = $salida->ubicacion;
                  $salida_csct->proyecto_id = $proyecto_csct;
                  $salida_csct->tiposalida_id = 2;
                  $salida_csct->empleado_id = 11;
                  $salida_csct->empleado_entrega_id = $salida->empleado_entrega_id;
                  $salida_csct->empleado_recibe_id = $salida->empleado_recibe_id;
                  $salida_csct->empleado_autoriza_id = $salida->empleado_autoriza_id;
                  $salida_csct->condicion = 1;
                  Utilidades::auditar($salida_csct, $salida_csct->id);
                  $salida_csct->save();
                }

                $partidas_csct_salidas = new \App\PartidasDBP();
                $partidas_csct_salidas->salida_id = $salida_csct->id;
                $partidas_csct_salidas->tiposalida_id = 2;
                $partidas_csct_salidas->lote_id = $lote_almacen_csct->id;
                $partidas_csct_salidas->cantidad = $vs->cantidad;
                $partidas_csct_salidas->cantidad_retorno = $vs->cantidad_retorno;
                Utilidades::auditar($partidas_csct_salidas, $partidas_csct_salidas->id);
                $partidas_csct_salidas->save();

                $rces_save->salidas = $rces->salidas + 1;


              }elseif ($vs->tiposalida_id == 3) {//resguardo
                $salida = DB::table('salidasresguardo')->where('id',$vs->salida_id)->first();

                $salida_csct = \App\SalidasResguardoDBP::where('fecha',$salida->fecha)
                ->where('folio',$salida->folio)->first();

                $proyecto_csct = $this->BuscarProyectoCSCT($salida->proyecto_id);

                if (isset($salida_csct) == false) {
                  $salida_csct = new \App\SalidasResguardoDBP();
                  $salida_csct->fecha = $salida->fecha;
                  $salida_csct->folio = $salida->folio;
                  $salida_csct->ubicacion = $salida->ubicacion;
                  $salida_csct->proyecto_id = $proyecto_csct;
                  $salida_csct->tiposalida_id = 3;
                  $salida_csct->empleado_entrega_id = $salida->empleado_entrega_id;
                  $salida_csct->empleado_supervisor_id = 11;
                  $salida_csct->empleado_solicita_id = 11;
                  $salida_csct->condicion = 1;
                  Utilidades::auditar($salida_csct, $salida_csct->id);
                  $salida_csct->save();
                }

                $partidas_csct_salidas = new \App\PartidasDBP();
                $partidas_csct_salidas->salida_id = $salida_csct->id;
                $partidas_csct_salidas->tiposalida_id = 3;
                $partidas_csct_salidas->lote_id = $lote_almacen_csct->id;
                $partidas_csct_salidas->cantidad = $vs->cantidad;
                $partidas_csct_salidas->cantidad_retorno = $vs->cantidad_retorno;
                Utilidades::auditar($partidas_csct_salidas, $partidas_csct_salidas->id);
                $partidas_csct_salidas->save();

                $rces_save->salidas = $rces->salidas + 1;

              }


            }

          }
        $rces_save->entradas = $rces->entradas + 1;
        }

        $rces_save->save();

        $rceses = new \App\RcesES();
        $rceses->rhoc_id = $id;
        $rceses->save();

        DB::commit();
        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        DB::rollBack();
        Utilidades::errors($e);
      }
    }

    public function getStoke($req_com_id)
    {
      $stoke = \App\RequisicionhasordencomprasDBP::
        join('ordenes_compras AS oc', 'oc.id', 'requisicion_has_ordencompras.orden_compra_id')
        ->join('proyectos AS p', 'p.id', 'oc.proyecto_id')
        ->join('stocks AS s', 's.proyecto_id', 'p.id')
        ->select('s.id as stocke_id', 'p.id AS proyecto_id')
        ->where('requisicion_has_ordencompras.id', $req_com_id)
        ->first();

      return $stoke;
    }

    public function BuscarProyectoCSCT($id)
    {

      $proyecto = DB::table('proyectos')->where('id',$id)->first();
      $proyecto_csct = \App\ProyectoDBP::where('nombre_corto','LIKE','%'.$proyecto->nombre_corto.'%')->first();

      return $proyecto_csct->id;
    }
}
