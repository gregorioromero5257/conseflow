<table>
  <tr>
    <td colspan="8" style="text-align : center;">{{$empresa}}</td>
  </tr>
    <tr>
      <th style="background-color : #5E7EFF; text-align : center;"><b>#</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>FECHA DE SOLICITUD</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>TIPO</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>PROYECTO</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>FOLIO ERP</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>MONTO TE SOLICITADO</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>MONTO EFECTIVO SOLICITADO</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>NOMBRE BENEFICIARIO</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>NOMBRE SOLICITANTE</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>FECHA DEL DEPOSITO</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>MONTO DEPOSITADO</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>MONEDA</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>BENEFICIARIO</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>BANCO</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>BANCO</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>FECHA DE COMPROBACION</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>MONTO COMPROBADO</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>COMENTARIO</b></th>
    </tr>

  @foreach($arreglo as $key => $articulo)
  <tr>
    <td>{{$key + 1}}</td>
    <td>{{$articulo["solicitud"]->fecha_solicitud}}</td>
    <td>
      @if($articulo["solicitud"]->tipo == 0)
        SINDICATO
      @elseif($articulo["solicitud"]->tipo == 1)
        REEMBOLSO
      @elseif($articulo["solicitud"]->tipo == 2)
        VIATICOS
      @endif
    </td>
    <td>{{$articulo["solicitud"]->nombre_proyecto}}</td>
    <td>{{$articulo["solicitud"]->folio}}</td>
    <td>{{$articulo["detalle"]->transferencia}}</td>
    <td>{{$articulo["detalle"]->efectivo}}</td>
    <td>{{$articulo["beneficiario"]!=null? $articulo["beneficiario"]["beneficiario"]:'N/D'}}</td>
    <td>{{$articulo['solicitud']->nombre_elabora}}</td>
    <td>
      @foreach ($articulo['pagos'] as $key => $value)
      {{$value->fecha_pago}}
      @endforeach
    </td>
    <td>{{$articulo['total_pago']}}</td>
    <td></td>
    <td>{{$articulo["beneficiario"]!=null? $articulo["beneficiario"]["beneficiario"]:'N/D'}}</td>
    <td></td>
    <td>{{$articulo["beneficiario"]!=null? $articulo["beneficiario"]["banco_nombre"]:'N/D'}}</td>
    <td>
      @foreach ($articulo['comprobacion'] as $key => $value_s)
      {!! nl2br($value_s->fecha."\n") !!}
      @endforeach
    </td>
    <td>{{$articulo['total_comprobacion']}}</td>
    <td></td>

  </tr>
  @endforeach
</table>
