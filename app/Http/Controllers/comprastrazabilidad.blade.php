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
  <th style="background-color: #3D9EE4;">FECHA</th>
  <th style="background-color: #3D9EE4;">OC</th>
  <th style="background-color: #3D9EE4;">COSTO OC</th>
  <th style="background-color: #3D9EE4;">MONEDA</th>
  <th style="background-color: #3D9EE4;">PROYECTO</th>
  <th style="background-color: #3D9EE4;">NO. FACTURA</th>
  <th style="background-color: #3D9EE4;">PROVEEDOR </th>
  <th style="background-color: #3D9EE4;">COSTO DE FACTURA</th>
  <th style="background-color: #3D9EE4;">DIFERENCIA DE COSTOS</th>
  <th style="background-color: #3D9EE4;">ENTRADA ALMACEN %</th>
  <th style="background-color: #3D9EE4;">SALIDA ALMACEN %</th>
  <th style="background-color: #3D9EE4;">RESTANTE %</th>
  <!-- <th style="background-color: #3D9EE4;">TIPO CAMBIO </th> -->
  <th style="background-color: #3D9EE4;">PAGO MN</th>
  <th style="background-color: #3D9EE4;">PAGO %</th>

</tr>
@foreach($arreglo as $value)
<tr>
  <td>{{$value['oc']->fecha_orden}}</td>
  <td>{{$value['folio_oc']}}</td>
  <td>{{$value['oc']->total}}</td>
  <td>{{$value['moneda']}}</td>
  <td>{{$value['oc']->nombre_corto }}</td>
  <td>{{$value['factura'] }}</td>
  <td>{{$value['oc']->razon_social }}</td>
  <td>{{$value['total_factura']}}</td>
  <td>{{$value['diferencia_costos']}}</td>
  <td>{{round($value['procentaje_entrada'],2)}} %</td>
  <td>{{round($value['porcentaje_salidas'],2)}} %</td>
  <td>{{(round($value['procentaje_entrada'],2)) -(round($value['porcentaje_salidas'],2))}} %</td>
  <td>
    {{$value['total_en_mn']}}
  </td>
  <td>{{round($value['porcentaje_pago'],2)}} %</td>
</tr>
@endforeach

</html>
