<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>REPORTE DE SOLICITUDES 2020</title>
  <style type="text/css">


  .titulo {
    padding-left: 140px;
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
    /* border-top: 1px solid #00FFFF; */
  }
  .hed {
    text-align: center;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 10px;
    border: 2px solid;
  }
  .lp {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 6px;
    text-align: center;
    border: 1px solid;
  }
  .letrat {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 7px;
    text-align: center;
    border: 1px solid;
  }
  .letrag {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 8px;
    background-color: #B2BABB;
    border: 1px solid;
    text-align: left;
  }
  .gris {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 8px;
    text-align: center;
    background-color: #B2BABB;
    border: 1px solid;
  }
  .lc {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 10px;
    text-align: center;
    border: 1px solid;
  }
  </style>

</head>
<body >
<div>
<table style="border-collapse:collapse; border: 2px solid;" width="100%">
<tr>
<td colspan="2" style="color: #2F86DE; font-weight: bold; text-align:center; font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;">
    @if($empresa === 'CONSERFLOW S.A. DE C.V.')
    CONSERFLOW S.A. DE C.V.
    @elseif($empresa === 'CSCT')
    CONSTRUCTORA Y SERVICIOS CALDERON TORRES S.A. DE C.V.
    @endif
  </td>
</tr>
<tr>
  @if ($empresa === 'CONSERFLOW S.A. DE C.V.')
  <td width="100"><img src="img/conserflow.png" alt="Conserflow" width="100" height="30" style="padding-left: 15px;"></td>
  @elseif($empresa === 'CSCT')
  <td width="100"><img src="img/CSCT.png" alt="Conserflow" width="100" height="30" style="padding-left: 15px;"></td>

  @endif
<td class="titulo" >REPORTE DE SOLICITUDES 2020</td>
</tr>

</table>

<table style="border-collapse:collapse; border: 2px solid;" width="100%">

<tr>
<td class="lc">#</td>
<td class="lc">FECHA DE SOLICITUD</td>
<td class="lc">No DE SOLICITUD</td>
<td class="lc">PROYECTO</td>
<td class="lc">PERSONAL / BENEFICIARIO</td>
<td class="lc">SPEI</td>
<td class="lc">EFECTIVO</td>
<td class="lc">MONTO SOLICITADO</td>
<td class="lc">BANCO</td>
<td class="lc">FECHA DE PAGO</td>
<td class="lc">FECHA DE OPERACIÓN</td>
<td class="lc">OBSERVACIONES</td>
</tr>

@foreach ($arreglo as $key => $value)
<tr>
<td class="letrat">&nbsp;{{$key + 1}}</td>
<td class="letrat">&nbsp;{{$value["solicitud"]->fecha_solicitud}}</td>
<td class="letrat">&nbsp;{{$value["solicitud"]->folio}}</td>
<td class="letrat">&nbsp;{{$value["solicitud"]->nombre_proyecto}}</td>
<td class="letrat">&nbsp;{{$value["beneficiario"] != null ? $value["beneficiario"]["beneficiario"] : 'N/D'}}</td>
<td class="letrat">&nbsp;$&nbsp;{{$value["detalle"]->transferencia}}</td>
<td class="letrat">&nbsp;$&nbsp;{{$value["detalle"]->efectivo}}</td>
<td class="letrat">&nbsp;$&nbsp;{{$value["detalle"]->total}}</td>
<td class="letrat">&nbsp;{{$value["beneficiario"]!=null? $value["beneficiario"]["banco_nombre"]:'N/D'}}</td>
<td class="letrat">&nbsp;
  @foreach ($value['pagos'] as $k => $v)
  {{$v->fecha_pago}}
  @endforeach
</td>
<td class="letrat">&nbsp;{{$value["solicitud"]->fecha_operacion}}</td>
<td class="letrat">&nbsp;</td>
</tr>
@endforeach
</table>
</div>
</body>
</html>
