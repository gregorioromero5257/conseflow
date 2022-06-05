<html>
  <body>




  <table>
    <tr>
      <td colspan="29"></td>
    </tr>

    <tr>
      <td rowspan="3" colspan="4"></td>
      <td rowspan="3" colspan="21"></td>
      <td colspan="2" style="text-align : center;">
        CÓDIGO
      </td>
      <td colspan="3" style="text-align : center;">PCO-01/F-03</td>
    </tr>
    <tr>
      <td colspan="2" style="text-align : center;">REVISIÓN</td>
      <td colspan="3" style="text-align : center;">00</td>
    </tr>
    <tr>
      <td colspan="2" style="text-align : center;">EMISIÓN</td>
      <td colspan="3" style="text-align : center;">29.JUN.20</td>
    </tr>

  <tr>
    <td colspan="29"></td>
  </tr>
  <tr>
    <th style="background-color: #0F75BF; text-align : justify;">Fecha de requisición</th>
    <th style="background-color: #0F75BF; text-align : justify;">Requisición</th>
    <th style="background-color: #0F75BF; text-align : justify;">Fecha de Orden</th>
    <th style="background-color: #0F75BF; text-align : justify;">Orden de Compra</th>
    <th style="background-color: #0F75BF; text-align : justify;">Proyecto</th>
    <th style="background-color: #0F75BF; text-align : justify;">Clasificación del producto</th>
    <th style="background-color: #0F75BF; text-align : justify;">Proveedor</th>
    <th style="background-color: #0F75BF; text-align : justify;">Credito</th>
    <th style="background-color: #0F75BF; text-align : justify;">Periódo de entrega</th>
    <th style="background-color: #0F75BF; text-align : justify;">Cant.</th>
    <th style="background-color: #0F75BF; text-align : justify;">Unid.</th>
    <th style="background-color: #0F75BF; text-align : justify;">Descripción</th>
    <th style="background-color: #0F75BF; text-align : justify;">Precio Unitario </th>
    <th style="background-color: #0F75BF; text-align : justify;">Total</th>
    <th style="background-color: #0F75BF; text-align : justify;">Total IVA</th>
    <th style="background-color: #0F75BF; text-align : justify;">Divisa</th>
    <th style="background-color: #0F75BF; text-align : justify;">Tipo de cambio</th>
    <th style="background-color: #0F75BF; text-align : justify;">Total MXN</th>
    <th style="background-color: #0F75BF; text-align : justify;">Certificados</th>
    <th style="background-color: #0F75BF; text-align : justify;">Estatus de Orden</th>
    <th style="background-color: #0F75BF; text-align : justify;">Factura</th>
    <th style="background-color: #0F75BF; text-align : justify;">Monto Pagado</th>
    <th style="background-color: #0F75BF; text-align : justify;">Debemos</th>
    <th style="background-color: #0F75BF; text-align : justify;">Estatus de Factura</th>
    <th style="background-color: #0F75BF; text-align : justify;">Observaciones</th>
    <th style="background-color: #0F75BF; text-align : justify;">Fecha de ingreso almacén</th>
    <th style="background-color: #0F75BF; text-align : justify;">Código</th>
    <th style="background-color: #0F75BF; text-align : justify;">Cantidad recibida</th>
    <th style="background-color: #0F75BF; text-align : justify;">Unidad</th>
    <th style="background-color: #0F75BF; text-align : justify;">Material pendiente</th>
  </tr>
  @foreach ($arreglo as $key => $value)
  <tr>
    <td>
      @if($value['b'] != null)
      {{$value['b']->fecha_solicitud}}
      @endif
    </td>
    <td>
      @if($value['b'] != null)
      {{str_replace(['OC-CSCT-020-','OC-CONSERFLOW-020-','-SR'],'',$value['b']->folio)}}
      @endif
    </td>
    <td>
      {{$value['a']->fecha_orden}}
    </td>
    <td>
      {{$value['a']->folio}}
    </td>
    <td>
      {{$value['a']->nombre_corto}}
    </td>
    <td>
      {{$value['a']->nombre}}
    </td>
    <td>
      {{$value['a']->razon_social}}
    </td>
    <td>
      @if($value['a']->condicion_pago_id == 1)
      CRÉDITO
      @elseif($value['a']->condicion_pago_id == 2)
      CONTADO
      @endif
    </td>
    <td>
      {{$value['a']->periodo_entrega}}
    </td>
    <td>
      {{$value['a']->cantidad}}
    </td>
    <td>
      {{$value['a']->unidad}}
    </td>
    <td >
      {{  ($value['a']->descripcion)}}&nbsp; {{($value['a']->comentario)}}
    </td>
    <td>
      $ {{$value['a']->precio_unitario}}
    </td>
    <td>
      $ {{$value['a']->cantidad * $value['a']->precio_unitario}}
    </td>
    <td>
      $ {{ ($value['a']->cantidad * $value['a']->precio_unitario) * 1.16 }}
    </td>
    <td>
      @if($value['a']->moneda == 1)
      USD
      @elseif($value['a']->moneda == 2)
      MNX
      @endif
    </td>
    <td></td>
    <td>-</td>
    <td>
      @if($value['b'] != null)
        @if($value['b']->produccion == 1)
        SI
        @else
        NO
        @endif
      @endif
    </td>
    <td>
      @if($value['a']->condicion == 1)
      ACTIVA
      @elseif($value['a']->condicion == 2)
      CERRADA
      @endif
    </td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>
      @if($value['c'] != null)
      @foreach($value['c'] as $fechas)
       {{$fechas->fecha.' / '}}
      @endforeach
      @endif
    </td>
    <td></td>
    <td>
      @if($value['d'] != null)
      {{$value['d']->cantidad}}
      @endif
    </td>
    <td>
      {{$value['a']->unidad}}
    </td>
    <td>
      @if($value['d'] != null)
      {{$value['a']->cantidad - $value['d']->cantidad}}
      @endif
    </td>

  </tr>
  @endforeach
  </table>

  </body>
</html>
