<table>
  <tr>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Proyecto Origen</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Folio OC</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Clave SAT</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Descripción</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Clave Unidad</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Cantidad</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Valor Unitario</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Importe</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Impuesto</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Tasa o Cuota</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Tipo</b></th>
  </tr>

  <tbody>
    @foreach($arreglo as $a)
    <tr>
      <td>{{$a['proyecto_origen']}}</td>
      <td>{{$a['orden_compra']}}</td>
      <td>
        @if($a['factura'] == null)
        No hay factura
        @else
        {{$a['factura']->claveprodserv}}
        @endif
      </td>
      <td>
        @if($a['factura'] == null)
          @if($a['comentario'] == null)
          {{$a['salida']->descripcion}}
          @else
          {{$a['comentario']}}
          @endif
        @else
        {{$a['factura']->descripcion}}
        @endif
      </td>
      <td>
        @if($a['factura'] == null)
        No hay factura
        @else
          {{$a['factura']->claveunidad}}
        @endif
      </td>
      <td>{{$a['salida']->cantidad_salida}}</td>
      <td>
        @if($a['factura'] == null)
        No hay factura
        @else
          {{$a['factura']->valorunitario}}
        @endif
      </td>
      <td>
        @if($a['factura'] == null)
        No hay factura
        @else
          {{$a['factura']->importe}}
        @endif
      </td>
        @if(count($a['impuestos']) == 0)
        <td>
        No hay factura
        </td>
        <td>
        No hay factura
        </td>
        <td>
        No hay factura
        </td>
        @else
        @foreach ($a['impuestos'] as $key => $value)
        <td>
          {{$value->impuesto}}
        </td>
        <td>
          {{$value->tasaocuota}}
        </td>
        <td>
          {{$value->tipo}}
        </td>
        @endforeach
        @endif
    </tr>
    @endforeach
  </tbody>
</table>
