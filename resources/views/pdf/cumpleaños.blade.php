<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cumpleañeros</title>

    <style>

    .img {
    padding-left: 12px;
    padding-top: 0px;
  }
  .uno {
    text-align:  center;
    padding-left: 150px;
  }
  .letrauno {
    font-size: 18px;
    font-family: Arial, Helvetica, sans-serif;
    border: 2px;
  }
  .letrados {
    font-size: 8px;
    font-family: Arial, Helvetica, sans-serif;
    border: 1px;
  }
  .letradostabla{
    font-size: 8px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: left;
    font-weight: bold;
  }
    body {
        font-size: 13px;
    }
    table.tabla th {
        font-weight: bold;
        text-align: left;
        font-size: 20px;
        padding: 4px;

        border-collapse: collapse;
        font-family: Arial, Helvetica, sans-serif;
        font-weight: bold;
    }
    table.tabla td {
        font-weight: normal;
        font-size: 13px;
        padding: 6px;
        text-align: left;
        border-collapse: collapse;
        font-family: Arial, Helvetica, sans-serif;

    }
    .eimg {
  position: absolute;
  left: -45px;
  top: -45px;
  width: 120%;
  z-index: -1;
}

    </style>
</head>

<body>
<div class="div" >
<table><img src="img/enc.png" class="eimg">
</table>
<table>
  <tr>

    <td><img src="img/conserflow.png" alt="Conserflow" width="220" height="110"class="img" align="center" style="padding-bottom: 40px; padding-top: 20px;"></td>

    </td>

  </tr>
  <tr>
<td colspan="2" class="letrauno" style="padding-left: 165px;"><b>EMPLEADOS QUE CUMPLEN AÑOS ESTE MES</b></td>
  </tr>
</table>
        <table>
          <td>
            <img src="img/happy.png"
            width="600" height="400"
            style="
            position: absolute;
            left: -35px;
            top: 270px;
            width: 110%;
            z-index: -1;
            opacity: 0.4;">
          </td>
        </table>

        <div  style="margin: 5 auto;">

    <table width="100%" class="tabla" style="padding-left: 160px;">

        <tr>
            <th width="100%">Nombre</th>
            <th width="60%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Día</th>
        </tr>

    @foreach ($fecha_nacimiento as $item)
        <tr>
        <td style="text-transform: uppercase; ">{{ $item->nombre.' '.$item->ap_paterno.' '.$item->ap_materno }}</td>
        <td style="padding-left: 45px;"><?=date_format(date_create(strftime( $item->fech_nac)), 'd')?></td>
        </tr>
    @endforeach

    </table>
</body>
</html>
