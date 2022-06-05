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
  <td style="background-color: #0F75BF">Requisición</td>
  <td style="background-color: #0F75BF">Uso</td>
  <td style="background-color: #0F75BF">Fecha Solicitada</td>
  <td style="background-color: #0F75BF">Solicita</td>
  <td style="background-color: #0F75BF">OC</td>
  <td style="background-color: #0F75BF">Cantidad Requerida</td>
  <td style="background-color: #0F75BF">Unidad</td>
  <td style="background-color: #0F75BF">Descripción</td>
  <td style="background-color: #0F75BF">Cantidad en almacen</td>
  <td style="background-color: #0F75BF">Cantidad en compra</td>
  <td style="background-color: #0F75BF">Tiempo de entrega</td>
  <td style="background-color: #0F75BF">Fecha Recepción</td>
  <td style="background-color: #0F75BF">Cantidad Recepcionada</td>
  <td style="background-color: #0F75BF">PU</td>
  <td style="background-color: #0F75BF">Importe sin iva MXN</td>
  <td style="background-color: #0F75BF">Importe con iva MXN</td>
  <td style="background-color: #0F75BF">Importe sin iva USD</td>
  <td style="background-color: #0F75BF">Importe con iva USD</td>
</tr>
@foreach ($arreglo as $key => $value)
<tr>
  <td>{{$value['requis']->folio}}</td>


  <td>{{$value['requis']->descripcion_uso}}</td>
  <td>{{$value['requis']->fecha_solicitud}}</td>
  <td>{{$value['requis']->empleado_solicita}}</td>
  <td>{{$value['oc'] != null ? $value['oc']->folio : ''}}</td>
  <td>{{$value['requis']->cantidad}}</td>

  @if($value['s'] != null)
  <td>{{$value['s']->unidad_medida}}</td>
  @elseif($value['a'] != null)
  <td>{{$value['a']->unidad}}</td>
  @else
  <td></td>
  @endif

  @if($value['s'] != null)
  <td>{{$value['s']->nombre_servicio}}&nbsp; {{$value['requis']->comentario}}</td>
  @elseif($value['a'] != null)
  <td>{{$value['a']->descripcion}}&nbsp; {{$value['requis']->comentario}}</td>
  @else
  <td></td>
  @endif

  <td>{{$value['requis']->cantidad_almacen}}</td>
  <td>{{$value['oc'] != null ? $value['oc']->cantidad : ''}}</td>
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

  <td>{{$value['oc'] != null ? $value['oc']->precio_unitario : ''}}</td>

  @if($value['oc'] != null)

    @if($value['oc']->moneda == 2)
    <td>{{$value['oc']->precio_unitario * $value['oc']->cantidad }}</td>
    <td>{{($value['oc']->precio_unitario * $value['oc']->cantidad) + (($value['oc']->precio_unitario * $value['oc']->cantidad) * 0.16) }}</td>
    <td></td>
    <td></td>
    @elseif($value['oc']->moneda == 1)
    <td></td>
    <td></td>
    <td>{{($value['oc']->precio_unitario * $value['oc']->cantidad) * 23 }}</td>
    <td>{{(($value['oc']->precio_unitario * $value['oc']->cantidad) * 23) + ((($value['oc']->precio_unitario * $value['oc']->cantidad) * 23) * 0.16) }}</td>

    @endif



  @else

  <td></td>

  @endif




</tr>
@endforeach

</html>
