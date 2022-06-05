<!DOCTYPE html>
<html >
  <head>
    <meta charset="utf-8">
    <title></title>
    <style>
    .table td, th {
      border: 1px solid #666;
      padding-top: 3px;
      padding-bottom: 3px;
    }
    #customers {
      font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }
    #customers td, #customers th {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: center;
      color: #547260;
    }
    #customers tr:nth-child(even){background-color: #f2f2f2;}

    #customers tr:hover {background-color: #ddd;}

    #customers th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: left;
      border-color: #b4b4b4;
      color: #547260;
      text-align: center;
    }

      .table {
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #666;
        font-size: 12px;
      }
      body {
        margin: 0px;
      }
    </style>
  </head>
  <body>

<table id="customers" class="letradostabla">
  @foreach($arreglo_final  as $key => $value)
  <tr>
    <th width="20%">UNIDAD</th>
    <th width="15%">MARCA</th>
    <th width="15%">MODELO</th>
    <th width="15%">PLACAS</th>
    <th width="15%">AÑO</th>
    <th width="15%"># TARJETA CIRCULACION</th>
    <th width="15%"># SERIE</th>
  </tr>
  <tr>
    <td>{{$value['unidades']['unidad']}}</td>
    <td>{{$value['unidades']['marca']}}</td>
    <td>{{$value['unidades']['modelo']}}</td>
    <td>{{$value['unidades']['placas']}}</td>
    <td>{{$value['unidades']['anio']}}</td>
    <td>{{$value['unidades']['numero_tarjeta_circulacion']}}</td>
    <td>{{$value['unidades']['numero_serie']}}</td>
  </tr>
  <tr>
    <th colspan="7">SERVICIO</th>
  </tr>
  <tr>
    <th width="20%">FECHA SERVICIO</th>
    <th width="15%">FECHA ENTREGA</th>
    <th colspan="2" width="15%">PROVEEDOR</th>
    <th colspan="2" width="15%">RESPONSABLE</th>
    <th width="15%">TOTAL</th>
  </tr>
  @foreach($value['servicios'] as $key => $val)

  <tr>
    <td>{{$val['servicio']['fecha_servicio']}}</td>
    <td>{{$val['servicio']['fecha_entrega']}}</td>
    <td colspan="2">{{$val['servicio']['proveedor']}}</td>
    <td colspan="2">{{$val['servicio']['responsable']}}</td>
    <td>{{$val['servicio']['total']}}</td>
  </tr>
  @foreach($val['partidas'] as $key => $valor)
  <tr>
    <td>PARTIDA:</td>
    <td colspan="6">{{$valor['nombre']}}</td>
  </tr>
  @endforeach
  @endforeach
  <tr>
    <th colspan="7">MANTENIMIENTO</th>
  </tr>
  <tr>
    <th width="10%">PROVEEDOR</th>
    <th width="10%">RESPONSABLE</th>
    <th width="10%">TOTAL</th>
    <th width="10%">FECHA SERVICIO</th>
    <th width="10%">FECHA ENTREGA</th>
    <th width="10%">PROXIMA REVISIÓN</th>
    <th width="10%">KILOMETRAJE REVISION</th>
  </tr>
  @foreach($value['mantenimiento'] as $key => $val)

  <tr>
    <td>{{$val['mantenimiento']['proveedor']}}</td>
    <td>{{$val['mantenimiento']['responsable']}}</td>
    <td >{{$val['mantenimiento']['total']}}</td>
    <td >{{$val['mantenimiento']['fecha_servicio']}}</td>
    <td>{{$val['mantenimiento']['fecha_entrega']}}</td>
    <td>{{$val['mantenimiento']['fecha_prox_rev']}}</td>
    <td>{{$val['mantenimiento']['kilometraje_revision']}}</td>
  </tr>
  @foreach($val['partidas'] as $key => $valor)
  <tr>
    <td>PARTIDA:</td>
    <td colspan="6">{{$valor['nombre']}}</td>
  </tr>
  @endforeach
  @endforeach
  <tr>
    <th colspan="7">POLIZAS</th>
  </tr>
  <tr>
    <th colspan="3" width="10%">PROVEEDOR</th>
    <th colspan="2" width="10%"># POLIZA</th>
    <th width="10%">FECHA INICIO</th>
    <th width="10%">FECHA TERMINO</th>
  </tr>
  @foreach($value['poliza'] as $key => $vap)

  <tr>
    <td colspan="3">{{$vap['proveedor']}}</td>
    <td colspan="2">{{$vap['numero_poliza']}}</td>
    <td >{{$vap['fecha_inicio']}}</td>
    <td >{{$vap['fecha_termino']}}</td>
  </tr>
  @endforeach
  <tr>
    <th colspan="7">VERIFICACIÓN</th>
  </tr>
  <tr>
    <th colspan="3" width="10%">FECHA</th>
    <th colspan="2" width="10%"># COSTO</th>
    <th width="10%">COSTO MULTA</th>
    <th width="10%">PERIODO</th>
  </tr>
  @foreach($value['verificacion'] as $key => $vav)

  <tr>
    <td colspan="3">{{$vav['fecha']}}</td>
    <td colspan="2">{{$vav['costo']}}</td>
    <td >{{$vav['costo_multa']}}</td>
    <td >{{$vav['periodo']}}</td>
  </tr>
  @endforeach
  <tr>
    <th colspan="7">TENENCIA</th>
  </tr>
  <tr>
    <th colspan="3" width="10%">FECHA</th>
    <th colspan="2" width="10%">ANIO</th>
    <th width="10%">FOLIO</th>
    <th width="10%">COSTO</th>
  </tr>
  @foreach($value['tenencia'] as $key => $vat)

  <tr>
    <td colspan="3">{{$vat['fecha']}}</td>
    <td colspan="2">{{$vat['anio']}}</td>
    <td >{{$vat['folio']}}</td>
    <td >{{$vat['costo']}}</td>
  </tr>
  @endforeach
  <tr>
    <td colspan="7">&nbsp;</td>
    <td colspan="7">&nbsp;</td>
  </tr>
@endforeach
</table>
  </body>
</html>
