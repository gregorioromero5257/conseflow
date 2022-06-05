<html>
<tr>
  <th style="background-color: #2D36A2;">EMPLEADO</th>
  <th style="background-color: #2D36A2;">TIPO</th>
  <th style="background-color: #2D36A2;">CANTIDAD</th>
  <th style="background-color: #2D36A2;">FECHA</th>
  <th style="background-color: #2D36A2;">AUTORIZÓ</th>
</tr>
@foreach($data as $value)
<tr>
<td>{{$value->empleado}}</td>
<td>{{$value->tipo}}</td>
<td>{{$value->cantidad}}</td>
<td>{{$value->fecha}}</td>
<td>{{$value->autoriza}}</td>
</tr>
@endforeach

</html>
