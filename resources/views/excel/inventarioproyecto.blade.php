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
  <th style="background-color: #95DEF3">PROYECTO</th>
  <th style="background-color: #95DEF3">TIPO</th>
  <th colspan="5" style="background-color: #95DEF3">
    ARTICULO / SERVICIO
  </th>
  <th style="background-color: #95DEF3">CANTIDAD</th>
  <th style="background-color: #95DEF3">CANTIDAD ENTRADA</th>
  <th style="background-color: #95DEF3">CANTIDAD SALIDA</th>
  <th style="background-color: #95DEF3">CANTIDAD DIFERENCIA</th>
</tr>
@foreach($arreglo as $value)

@foreach($value['partidas'] as $partida)
<tr>
  <td>{{$value['folio_oc']}}</td>
  <td>{{$partida['grupo']}}</td>
  <td colspan="5" >
    @if($partida['codificacion'] === 'UTF-8')
    {{utf8_encode($partida['descripcion'])}}
    @else
    {{$partida['descripcion']}}
    @endif
  </td>
  <td>{{$partida['cantidad']}}</td>
  <td>{{$partida['entrada']}}</td>
  <td>{{$partida['salida']}}</td>
  <td>{{number_format((float)$partida['entrada'] - (float)$partida['salida'],2)}}</td>
</tr>
@endforeach
@endforeach

</html>
