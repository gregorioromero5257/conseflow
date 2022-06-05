<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\DB;


class CatalogoSatFacturaController extends Controller
{
    /**
    * [usocfdi funcion que retorna todos los registro del modelo SatCatUsoCfdi]
    * @return Response
    */
    public function usocfdi()
    {
      $usocfdi = \App\SatCatUsoCfdi::orderBy('id', 'desc');
      return $this->busqueda($usocfdi);
    }

    /**
    * [usocfdi funcion que retorna todos los registro del modelo SatCatMonedas]
    * @return Response
    */
    public function satcatalogomonedas()
    {
      $catmonedas = \App\SatCatMonedas::orderBy('id', 'desc');
      return $this->busqueda($catmonedas);
    }

    /**
    * [satcatformapago funcion que retorna todos los registro del modelo SatCatFormaPago]
    * @return Response
    */
    public function satcatformapago()
    {
      $catformapago = \App\SatCatFormaPago::orderBy('id', 'desc');
      return $this->busqueda($catformapago);
    }

    /**
    * [satcatmetodopago funcion que retorna todos los registro del modelo SatCatMetodoPago]
    * @return Response
    */
    public function satcatmetodopago()
    {
      $satcatmetodopago = \App\SatCatMetodoPago::orderBy('id', 'desc');
      return $this->busqueda($satcatmetodopago);
    }

    /**
    * [satcatmetodopago funcion que retorna todos los registro del modelo DatosGenerales]
    * @return Response
    */
    public function datosgenerales()
    {
      $datosgenerales = \App\DatosGenerales::orderBy('id', 'desc');
      return $this->busqueda($datosgenerales);
    }

    /**
    * [satcatmetodopago funcion que retorna todos los registro del modelo SatCatTipoFactura]
    * @return Response
    */
    public function satcattipofactura()
    {
      $tipofactura = \App\SatCatTipoFactura::orderBy('id', 'desc');
      return $this->busqueda($tipofactura);
    }

    /**
    * [satcattipofactura funcion que retorna todos los registro del modelo SatCatProdSer]
    * @return Response
    */
    public function satcatprodser()
    {
      $tipofactura = \App\SatCatProdSer::orderBy('id', 'desc');
      return $this->busqueda($tipofactura);
    }

    /**
    * [satcatunidades funcion que retorna todos los registro del modelo SatCatUnidades]
    * @return Response
    */
    public function satcatunidades()
    {
      $tipofactura = \App\SatCatUnidades::orderBy('id', 'desc')->get()->toArray();
      return response()->json($tipofactura);
    }

    /**
    * [satcattiporelacion funcion que retorna todos los registro del modelo SatCatTipoRelacion]
    * @return Response
    */
    public function satcattiporelacion()
    {
      $tiporelacion = \App\SatCatTipoRelacion::orderBy('id', 'desc');
      return $this->busqueda($tiporelacion);
    }

    public function catfactura()
    {
      $facturas = \App\Factura::
      select('factura.*')
      ;
      return $this->busqueda($facturas);
    }

    public function busqueda($dato)
    {
      extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));

      $data = $dato;

      if (isset($query) && $query) {
          $data = $byColumn == 1 ?
              Utilidades::busqueda_filterByColumn($data, $query) :
              Utilidades::busqueda_filter($data, $query, $fields);
      }

      $count = $data->count();

      if (isset($orderBy)) {
          $direction = $ascending == 1 ? 'ASC' : 'DESC';
          $data->orderBy($orderBy, $direction);
      }

      $results = $data->get();

      return [
          'data' => $results,
          'count' => $count,
      ];
    }

    public function satcatdeduccion()
    {
      $total = DB::table('sat_cat_tipodeduccion')->select('id',DB::raw("CONCAT(c_tipodeduccion,' ',descripcion) AS descripcion"),'descripcion AS desc','c_tipodeduccion')->get();
      return response()->json($total);
    }

    public function satcatpercepcion()
    {
      $total = DB::table('sat_cat_tipopercepcion')->select('id',DB::raw("CONCAT(c_tipopercepcion,' ',descripcion) AS descripcion"),'descripcion AS desc','c_tipopercepcion')->get();
      return response()->json($total);
    }
}
