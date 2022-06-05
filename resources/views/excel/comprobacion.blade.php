<table>
  <tr>
    <td></td>
    <td colspan="12"></td>
    <td></td>
  </tr>
  <tr>
    <td>Relación comprobación  de gastos</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td >Fecha de comprobación</td>
    <td>=+HOY()</td>
  </tr>
  <tr>
    <td>Responsable del fondo</td>
    <td></td>
    <td>{{$solicitud_caja_chica->nombre_user}}</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>Fecha de pago</td>
    <td>=HOY()</td>
  </tr>
  <tr>
    <td style="background-color: #071E60">Fecha</td>
    <td style="background-color: #071E60">Factura</td>
    <td style="background-color: #071E60">Acreedores y/o proveedor</td>
    <td style="background-color: #071E60">RFC</td>
    <td style="background-color: #071E60">concepto</td>
    <td style="background-color: #071E60">Centro de costo</td>
    <td style="background-color: #071E60">Depto Aut.</td>
    <td style="background-color: #071E60">Depto requirio</td>
    <td style="background-color: #071E60">Importe</td>
    <td style="background-color: #071E60">Tasa 0</td>
    <td style="background-color: #071E60">IEPS</td>
    <td style="background-color: #071E60">IVA</td>
    <td style="background-color: #071E60">Retención de IVA/ IEPS</td>
    <td style="background-color: #071E60">Total a pagar</td>
  </tr>
  @foreach ($arreglo as $key => $value)
  <tr>
    <td>{{substr($value['comprobacion']['fecha'],0,10)}}</td>
    <td>{{$value['comprobacion']['uuid']}}</td>
    <td>{{$value['comprobacion']['nombre_e']}}</td>
    <td>{{$value['comprobacion']['rfc_e']}}</td>
    <td>{{$value['concepto']['descripcion']}}</td>
    <td>{{$value['comprobacion']['nombre_corto']}}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>{{round($value['comprobacion']['subtotal'],2)}}</td>
    <td>
      -
    </td>
    <td>
      @if($value['impuestos'] != null)
        @if($value['impuestos']['impuesto'] === '003')
        {{round($value['impuestos']['importe'],2)}}
        @endif
      @endif
    </td>
    <td>
      @if($value['impuestos'] != null)
        @if($value['impuestos']['impuesto'] === '002')
        {{round($value['impuestos']['importe'],2)}}
        @endif
      @endif
    </td>
    <td>
      @if($value['retencion'] != null)
          {{round($value['retencion']['importe'],2)}}
      @endif
    </td>
    <td>{{round($value['comprobacion']['total'],2)}}</td>
  </tr>
  @endforeach
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Total gastos con factura</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Total comprobando</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>=+N17</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Fondo asignado</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>=+N17</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Saldo actual</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>Diferencia Entre total comprobado y fondo asignado</td>
  </tr>
</table>
