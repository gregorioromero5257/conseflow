<html>
<tr>
</tr>
<tr>
  <td></td>
  <td rowspan="3"></td>
  <td colspan="4" rowspan="3">
    <b>CONSERFLOW S.A. DE C.V.</b>
  </td>
</tr>
<tr>
</tr>
<tr>
</tr>
<tr>
  <td colspan="8" style="background-color: #0F75BF">REPORTE GENERADO HASTA EL {{date('Y-m-d')}}</td>
  <td style="background-color: #0F75BF"></td>
  <td style="background-color: #0F75BF"></td>
  <td style="background-color: #0F75BF"></td>
  <td style="background-color: #0F75BF"></td>
  <td style="background-color: #0F75BF"></td>
  <td style="background-color: #0F75BF"></td>
</tr>
<tr>
  <th style="background-color: #0F75BF;">FECHA</th>
  <th style="background-color: #0F75BF;">OC</th>
  <th style="background-color: #0F75BF;">COSTO OC</th>
  <th style="background-color: #0F75BF;">MONEDA</th>
  <th style="background-color: #0F75BF;">PROYECTO</th>
  <th style="background-color: #0F75BF;">NO. FACTURA</th>
  <th style="background-color: #0F75BF;">PROVEEDOR </th>
  <th style="background-color: #0F75BF;">COSTO DE FACTURA</th>
  <th style="background-color: #0F75BF;">DIFERENCIA DE COSTOS</th>
  <th style="background-color: #0F75BF;">ENTRADA ALMACEN %</th>
  <th style="background-color: #0F75BF;">SALIDA ALMACEN %</th>
  <th style="background-color: #0F75BF;">RESTANTE %</th>
  <!-- <th style="background-color: #0F75BF;">TIPO CAMBIO </th> -->
  <th style="background-color: #0F75BF;">PAGO MN</th>
  <th style="background-color: #0F75BF;">PAGO %</th>

</tr>
@foreach($arreglo as $value)
<tr>
  <td style="background-color: #95CCF3">{{$value['oc']->fecha_orden}}</td>
  <td style="background-color: #95CCF3">{{$value['folio_oc']}}</td>
  <td style="background-color: #95CCF3">{{$value['oc']->total}}</td>
  <td style="background-color: #95CCF3">{{$value['moneda']}}</td>
  <td style="background-color: #95CCF3">{{$value['oc']->nombre_corto }}</td>
  <td style="background-color: #95CCF3">{{$value['factura'] }}</td>
  <td style="background-color: #95CCF3">{{$value['oc']->razon_social }}</td>
  <td style="background-color: #95CCF3">{{$value['total_factura']}}</td>
  <td style="background-color: #95CCF3">{{$value['diferencia_costos']}}</td>
  <td style="background-color: #95CCF3">{{round($value['procentaje_entrada'],2)}} %</td>
  <td style="background-color: #95CCF3">{{round($value['porcentaje_salidas'],2)}} %</td>
  <td style="background-color: #95CCF3">{{(round($value['procentaje_entrada'],2)) -(round($value['porcentaje_salidas'],2))}} %</td>
  <td style="background-color: #95CCF3">
    {{$value['total_en_mn']}}
  </td>
  <td style="background-color: #95CCF3">{{round($value['porcentaje_pago'],2)}} %</td>
</tr>

<tr>
  <th></th>
  <th></th>
  <th></th>
  <th colspan="5" style="background-color: #95DEF3">
    ARTICULO / SERVICIO
  </th>
  <th style="background-color: #95DEF3">CANTIDAD</th>
  <th style="background-color: #95DEF3">CANTIDAD ENTRADA</th>
  <th style="background-color: #95DEF3">FECHA</th>
  <th style="background-color: #95DEF3">LOTE</th>
  <th style="background-color: #95DEF3">CANTIDAD SALIDA</th>
  <th style="background-color: #95DEF3">FECHA</th>
</tr>
@foreach($value['partidas'] as $partida)
<tr>
  <td></td>
  <td></td>
  <td></td>
  <td colspan="5" >
    {{$partida['descripcion']}}
  </td>
  <td>{{$partida['cantidad']}}</td>
  @foreach($partida['entrada'] as $entrada)
  <td>{{$entrada['cantidad']}}</td>
  <td>{{$entrada['fecha']}}</td>
  <td>{{$entrada['nombre']}}</td>
  @endforeach
  @foreach($partida['salida'] as $salida)
  <td>{{$salida['cantidad']}}</td>
  <td>{{$salida['fecha']}}</td>
  @endforeach

</tr>
@endforeach
<tr>
  <td colspan="10"></td>
</tr>
@endforeach

</html>
