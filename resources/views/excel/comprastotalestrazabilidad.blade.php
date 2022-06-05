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
  <td style="background-color: #DBDBDC">{{$value['oc']->fecha_orden}}</td>
  <td style="background-color: #DBDBDC">{{$value['folio_oc']}}</td>
  <td style="background-color: #DBDBDC">{{$value['oc']->total}}</td>
  <td style="background-color: #DBDBDC">{{$value['moneda']}}</td>
  <td style="background-color: #DBDBDC">{{$value['oc']->nombre_corto }}</td>
  <td style="background-color: #DBDBDC">{{$value['factura'] }}</td>
  <td style="background-color: #DBDBDC">{{$value['oc']->razon_social }}</td>
  <td style="background-color: #DBDBDC">{{$value['total_factura']}}</td>
  <td style="background-color: #DBDBDC">{{$value['diferencia_costos']}}</td>
  <td style="background-color: #DBDBDC">{{round($value['procentaje_entrada'],2)}} %</td>
  <td style="background-color: #DBDBDC">{{round($value['porcentaje_salidas'],2)}} %</td>
  <td style="background-color: #DBDBDC">{{(round($value['procentaje_entrada'],2)) -(round($value['porcentaje_salidas'],2))}} %</td>
  <td style="background-color: #DBDBDC">
    {{$value['total_en_mn']}}
  </td>
  <td style="background-color: #DBDBDC">{{round($value['porcentaje_pago'],2)}} %</td>
</tr>
@endforeach
<table>
  <tr>
    <td>{{$arreglo['t']}}</td>
  </tr>
  <tr>
    <td colspan="3" style="background-color: #126B92; text-align: center; color: white;">Totales ordenes de compra</td>
  </tr>
  <tr>
    <td style="background-color: #126B92">Total USD</td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td style="background-color: #126B92">Tipo de cambio</td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td style="background-color: #126B92">Total MNX</td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td style="background-color: #126B92">TOTAL</td>
    <td></td>
    <td></td>
  </tr>
</table>

</html>
