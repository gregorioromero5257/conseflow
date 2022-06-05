<table>
  <tr>
    <td>{{$proyecto->nombre_corto}}</td>
  </tr>
  <tr>
    <th>NOMBRE</th>
    <th>CATEGORIA</th>
    @foreach ($arreglo_fechas_buscar as $key => $value)
      <th>{{$value}}</th>
     @endforeach
  </tr>
@foreach ($arreglo as $k => $v)
@if($v['empleado'] != null)
<tr>
<td>{{$v['empleado']->empleado}}</td>
<td>{{$v['empleado']->puesto}}</td>
@foreach ($v['registros'] as $g => $vg)
<td>{{$vg}}</td>
@endforeach
</tr>
@endif
 @endforeach
</table>
