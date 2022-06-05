<html>
<tr>
  <th style="background-color: #2D36A2;">PROVEEDOR</th>
  <th style="background-color: #2D36A2;">COSTO</th>
  <th style="background-color: #2D36A2;">ANTICIPO</th>
  <th style="background-color: #2D36A2;">DEBEMOS</th>
  <th style="background-color: #2D36A2;">MONEDA</th>
  <th style="background-color: #2D36A2;">FECHA SOLICITUD  DE PAGO </th>
  <th style="background-color: #2D36A2;">REQUISICIÓN </th>
  <th style="background-color: #2D36A2;">CONCEPTO</th>
  <th style="background-color: #2D36A2;">OC</th>
  <th style="background-color: #2D36A2;">OBSERVACIONES</th>
  <th style="background-color: #2D36A2;">FACTURA </th>
  <th style="background-color: #2D36A2;">FICHA DE PAGO </th>
  <th style="background-color: #2D36A2;">RESPONSABLE</th>
</tr>
@foreach($arreglo as $value)
<tr>
<td>{{$value['compras']['razon_social']}}</td>
<td>$&nbsp;&nbsp;&nbsp;{{$value['compras']['total']}}</td>
<td></td>
<td></td>
<td>{{$value['compras']['moneda'] == 1 ? 'USD' : $value['compras']['moneda'] == 2  ? 'MN' : 'EUR'}}</td>
<td></td>
<td>
@foreach($value['requi'] as $key => $valor)
  {{$valor->folio}}
@endforeach
</td>
<td>
@foreach($value['requi'] as $key => $val)
  {{$val->descripcion_uso}}
@endforeach
</td>
<td>{{$value['compras']['folio']}}</td>
<td>ENTREGADO</td>
</tr>
@endforeach

</html>
