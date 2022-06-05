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
  <td colspan="8" >REPORTE GENERADO HASTA EL {{date('Y-m-d')}}</td>

</tr>
<tr>
  <td style="background-color: #0AA537">Requisición</td>
  <td style="background-color: #0AA537">Descripción</td>
  <td style="background-color: #0AA537; word-wrap:break-word;">Tipo de Material</td>
  <td style="background-color: #0AA537; word-wrap:break-word;">Fecha Solicitada</td>
  <td style="background-color: #0AA537">Unidad</td>
  <td style="background-color: #0AA537; word-wrap:break-word;">Cantidad Requerida</td>

  <td style="background-color: #C69556; word-wrap:break-word;">Cantidad en Almacén</td>

  <td style="background-color: #0F75BF; word-wrap:break-word;">Cantidad en compra</td>
  <td style="background-color: #0F75BF">OC</td>
  <td style="background-color: #0F75BF; word-wrap:break-word;">Descripción OC</td>
  <td style="background-color: #0F75BF; word-wrap:break-word;">Proveedor</td>
  <td style="background-color: #0F75BF; word-wrap:break-word;">Tiempo de entrega</td>

  <td style="background-color: #C69556; word-wrap:break-word;">Fecha Recepción</td>
  <td style="background-color: #C69556; word-wrap:break-word;">Cantidad Recepcionada</td>

</tr>
@foreach ($arreglo as $key => $value)
<tr>

  <td>REQ-{{str_replace('REQ-','',$value['requis']->folio)}}</td>
  @if($value['s'] != null)
  <td style="word-wrap:break-word">{{$value['s']->nombre_servicio}}&nbsp; {{$value['requis']->comentario}}</td>
  @elseif($value['a'] != null)
  <td style="word-wrap:break-word">{{utf8_encode($value['a']->descripcion)}}&nbsp; {{$value['requis']->comentario}}</td>
  @else
  <td></td>
  @endif
  <td style="word-wrap:break-word">{{$value['tipo_material']}}</td>
  <td>{{$value['requis']->fecha_solicitud}}</td>
  @if($value['s'] != null)
  <td>{{$value['s']->unidad_medida}}</td>
  @elseif($value['a'] != null)
  <td>{{$value['a']->unidad}}</td>
  @else
  <td></td>
  @endif
  <td>{{$value['requis']->cantidad}}</td>
  <td>{{$value['requis']->cantidad_almacen}}</td>
  <td>{{$value['oc'] != null ? $value['oc']->cantidad : ''}}</td>
  <td>OC-{{$value['oc'] != null ? str_replace('OC-CONSERFLOW-020-','',$value['oc']->folio) : ''}}</td>

  @if($value['oc'] != null)

  @if($value['s'] != null)
  <td style="word-wrap:break-word">
    {{$value['s']->nombre_servicio}}&nbsp;{{$value['requis']->comentario}}&nbsp; {{($value['oc']->comentario == null ? '' : $value['oc']->comentario)}}
  </td>
  @elseif($value['a'] != null)
  <td style="word-wrap:break-word">

    {{utf8_encode($value['a']->descripcion)}}&nbsp; {{$value['requis']->comentario}}
    &nbsp;
    {{($value['oc']->comentario == null ? '' : $value['oc']->comentario)}}
  </td>
  @endif

  @else
  <td></td>
  @endif

  <td style="word-wrap:break-word">
    {{$value['oc'] != null ? $value['oc']->razon_social : ''}}
  </td>

  <td>{{$value['oc'] != null ? $value['oc']->periodo_entrega : ''}}</td>
  @if(count($value['e']) != 0)

  @foreach ($value['e'] as $k => $v)
  <td>{{$v->fecha}}</td>
  <td>{{$v->cantidad}}</td>
  @endforeach
  @else
  <td></td>
  <td></td>
  @endif




</tr>
@endforeach

</html>
