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
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
</tr>
<tr>
  <td style="background-color: #56C664;word-wrap:break-word;">REQUISCIÓN</td>
  <td style="background-color: #56C664;word-wrap:break-word;">USO</td>
  <td style="background-color: #56C664;word-wrap:break-word;">FECHA SOLICITADA</td>
  <td style="background-color: #56C664;word-wrap:break-word;">SOLICITA</td>
  <td style="background-color: #C65687;word-wrap:break-word;">OC</td>
  <td style="background-color: #C65687;word-wrap:break-word;">FECHA OC</td>
  <td style="background-color: #C65687;word-wrap:break-word;">PROVEEDOR</td>
  <td style="background-color: #56C664;word-wrap:break-word;">CANTIDAD REQUERIDA</td>
  <td style="background-color: #C69556;word-wrap:break-word;">ALMACEN</td>
  <td style="background-color: #C65687;word-wrap:break-word;">COMPRAS</td>
  <td style="background-color: #56C664;word-wrap:break-word;">UNIDAD</td>
  <td style="background-color: #56C664;word-wrap:break-word;">DESCRIPCIÓN</td>
  <td style="background-color: #C65687;word-wrap:break-word;">TIEMPO DE ENTREGA</td>
  <td style="background-color: #C69556;word-wrap:break-word;">FECHA RECEPCIÓN</td>
  <td style="background-color: #C69556;word-wrap:break-word;">CANTIDAD RECEPCIONADA</td>
  <td style="background-color: #C65687;word-wrap:break-word;">PU</td>

  <td style="background-color: #C65687;word-wrap:break-word;">IMPORTE SIN IVA USD</td>
  <td style="background-color: #C65687;word-wrap:break-word;">IMPORTE CON IVA USD</td>
  <td style="background-color: #C65687;word-wrap:break-word;">IMPORTE SIN IVA MXN</td>
  <td style="background-color: #C65687;word-wrap:break-word;">IMPORTE CON IVA MXN</td>

</tr>
@foreach ($arreglo as $key => $value)
<tr>
  <td style="word-wrap:break-word">{{$value['requis']->folio}}</td>
  <td style="word-wrap:break-word">{{$value['tipo_material']}}</td>
  <td style="word-wrap:break-word">{{$value['requis']->fecha_solicitud}}</td>
  <td style="word-wrap:break-word">{{$value['requis']->empleado_solicita}}</td>
  <td style="word-wrap:break-word">OC-{{$value['oc'] != null ? str_replace('OC-CONSERFLOW-020-','',$value['oc']->folio) : ''}}</td>
  <td style="word-wrap:break-word">{{$value['oc'] != null ? $value['oc']->fecha_orden : ''}}</td>
  <td style="word-wrap:break-word">
    {{$value['oc'] != null ? $value['oc']->razon_social : ''}}
  </td>
  <td style="word-wrap:break-word">{{$value['requis']->cantidad}}</td>
  <td style="word-wrap:break-word">{{$value['requis']->cantidad_almacen}}</td>
  <td style="word-wrap:break-word">{{$value['oc'] != null ? $value['oc']->cantidad : ''}}</td>
  @if($value['s'] != null)
  <td style="word-wrap:break-word">{{$value['s']->unidad_medida}}</td>
  @elseif($value['a'] != null)
  <td style="word-wrap:break-word">{{$value['a']->unidad}}</td>
  @else
  <td></td>
  @endif
  @if($value['s'] != null)
  <td style="word-wrap:break-word">{{$value['s']->nombre_servicio}}&nbsp; {{$value['requis']->comentario}}</td>
  @elseif($value['a'] != null)
  <td style="word-wrap:break-word">{{utf8_encode($value['a']->descripcion)}}&nbsp; {{$value['requis']->comentario}}</td>
  @else
  <td></td>
  @endif
  <td style="word-wrap:break-word">{{$value['oc'] != null ? $value['oc']->periodo_entrega : ''}}</td>
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


    @if($value['oc']->moneda == 1)

    <td>
      {{
          round(($value['oc']->precio_unitario * $value['oc']->cantidad),2)
       }}
     </td>
    <td>
      {{round(
        (($value['oc']->precio_unitario * $value['oc']->cantidad))
      +
       ((($value['oc']->precio_unitario * $value['oc']->cantidad)) * 0.16)
     ,2) }}
     </td>
     <td>
       {{
           round(($value['oc']->precio_unitario * $value['oc']->cantidad)* (float)$value['tc'],2)
        }}
      </td>
     <td>
       {{round(
       (  (($value['oc']->precio_unitario * $value['oc']->cantidad))
       +
        ((($value['oc']->precio_unitario * $value['oc']->cantidad)) * 0.16))*(float)$value['tc']
      ,2) }}
      </td>

     @elseif($value['oc']->moneda == 2)
     <td></td>
     <td></td>
     <td>{{$value['oc']->precio_unitario * $value['oc']->cantidad }}</td>
     <td>{{($value['oc']->precio_unitario * $value['oc']->cantidad) + (($value['oc']->precio_unitario * $value['oc']->cantidad) * 0.16) }}</td>

    @endif



  @else

  <td></td>

  @endif




</tr>
@endforeach

</html>
