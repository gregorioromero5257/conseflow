<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>PCO-01_F-03 Histórico de Compras</title>
  <style type="text/css">


  .titulo {
    text-align: center;
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
    background-color: #2F86DE;
  }
  </style>

</head>
<body >

<!-- PAGINA 2 -->
<div>
<table style="border-collapse:collapse; border: 2px solid;" width="100%">
<tr>
<td colspan="4" style="color: #2F86DE; font-weight: bold; text-align:center; font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;">CONSERFLOW S.A. DE C.V.</td>
</tr>
<tr>
<td style="border: 2px solid;" rowspan="3" width="100"><img src="img/conserflow.png" alt="Conserflow" width="100" height="30" style="padding-left: 15px;"></td>
<td style="border: 2px solid;" rowspan="3" class="titulo">HISTÓRICO DE COMPRAS</td>
<td width="50" class="hed">CÓDIGO</td>
<td width="50" class="hed">PCO-01/F-03</td>
</tr>
<tr>
<td class="hed">REVISIÓN</td>
<td class="hed">00</td>
</tr>
<tr>
<td class="hed">FECHA</td>
<td class="hed">26.FEB.20</td>
</tr>
</table>

<table style="border-collapse:collapse; border: 2px solid;" width="100%">

<tr>
<td class="gris" colspan="18">REPORTE GENERADO HASTA EL {{date('Y-m-d')}}</td>

</tr>
<tr>
<td class="lc"><div style="color: white;">FECHA DE OC</div></td>
<td class="lc"><div style="color: white;">OC</div></td>
<td class="lc"><div style="color: white;">COSTO OC</div></td>
<td class="lc"><div style="color: white;">MONEDA</div></td>
<td class="lc"><div style="color: white;">PROYECTO</div></td>
<td class="lc"><div style="color: white;">PROVEEDOR</div></td>
<td class="lc"><div style="color: white;">ARTICULO/SERVICIO</div></td>
<td class="lc"><div style="color: white;">CANTIDAD</div></td>
<td class="lc"><div style="color: white;">CANTIDAD ENTRADA</div></td>
<td class="lc"><div style="color: white;">FECHA ENTRADA</div></td>
<td class="lc"><div style="color: white;">LOTE</div></td>
<td class="lc"><div style="color: white;">CANTIDAD SALIDA</div></td>
<td class="lc"><div style="color: white;">FECHA SALIDA</div></td>
<td class="lc"><div style="color: white;">NO. FACTURA</div></td>
<td class="lc"><div style="color: white;">COSTO FACTURA</div></td>
<td class="lc"><div style="color: white;">DIFERENCIA DE COSTOS</div></td>
<td class="lc"><div style="color: white;">PAGO MIN</div></td>
<td class="lc"><div style="color: white;">PAGO %</div></td>
</tr>
@foreach ($arreglo as $key => $value)
<tr>
<td class="letrat">&nbsp;{{$value['oc']['fecha_orden']}}</td>
<td class="letrat">&nbsp;{{str_replace('OC-CONSERFLOW-020-','',$value['oc']['folio'])}}</td>
<td class="letrat">&nbsp;{{$value['oc']['c_oc']}}</td>
<td class="letrat">&nbsp;
  @if($value['oc']['moneda'] == 2)
  MNX
  @elseif($value['oc']['moneda'] == 1)
  USD
  @endif
</td>
<td class="letrat">&nbsp;{{$value['oc']['nombre_corto']}}</td>
<td class="letrat">&nbsp;{{$value['oc']['razon_social']}}</td>
<td class="letrat">&nbsp;{{$value['oc']['nombre']}}</td>
<td class="letrat">&nbsp;{{$value['oc']['cantidad']}}</td>
<td class="letrat">&nbsp;
  @foreach ($value['entradas'] as $k_e => $value_e)
  {{$value_e['cantidad']}}<br>
  @endforeach
</td>
<td class="letrat">&nbsp;
  @foreach ($value['entradas'] as $k_e => $value_e)
  {{$value_e['fecha']}}<br>
  @endforeach
</td>
<td class="letrat">&nbsp;
  @foreach ($value['entradas'] as $k_e => $value_e)
  {{$value_e['lote_nombre']}}<br>
  @endforeach
</td>
<td class="letrat">&nbsp;
  @foreach ($value['salidas'] as $k_s => $value_s)
  {{$value_s['cantidad']}}<br>
  @endforeach
</td>
<td class="letrat">&nbsp;
  @foreach ($value['salidas'] as $k_s => $value_s)
  {{$value_s['fecha']}}<br>
  @endforeach
</td>
<td class="letrat">&nbsp;</td>
<td class="letrat">&nbsp;</td>
<td class="letrat">&nbsp;</td>
<td class="letrat">&nbsp;</td>
<td class="letrat">&nbsp;</td>
</tr>
@endforeach

</table>
</div>
</body>
</html>
