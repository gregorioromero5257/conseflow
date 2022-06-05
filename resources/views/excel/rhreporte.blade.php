<table>
  <tr>
    <th></th>
    <th></th>
    @foreach($arreglo_nombre_dia as $key_uno => $array_nd)
    <th style="background-color: #8DDCEA;">{{$array_nd}}</th>
    @endforeach
  </tr>
  <tr>
    <th style="background-color: #8DDCEA;">NOMBRE</th>
    <th style="background-color: #8DDCEA;">CATEGORIA</th>
    @foreach($arreglo_fechas_buscar as $key_dos => $array_d)
    <th style="background-color: #8DDCEA;">{{$array_d}}</th>
    @endforeach
  </tr>

  @foreach ($arreglo as $key => $value)
  <tr>
    <td border="1">{{$value['empleado']->empleado}}</td>
    <td>{{$value['empleado']->puesto}}</td>
    @foreach ($value['registros'] as $key_ => $value_)
    <td>
      @if(count($value_) != 0)
      @foreach ($value_ as $k => $v)
        {{substr($v->hora,0,5).'-'}}
      @endforeach
      @else
      -
      @endif
    </td>
    @endforeach
  </tr>
  <tr>

  </tr>
  @endforeach



</table>
