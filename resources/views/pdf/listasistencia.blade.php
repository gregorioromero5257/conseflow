<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>LISTA DE ASISTENCIA</title>
  <style type="text/css">
  .ft {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 10px;
    text-align: center;
    border-collapse: collapse;
  }
  .lc {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 10px;
    text-align: center;
    border: 1px solid;
    border-collapse: collapse;
  }

  </style>

</head>
<body >
<div>
<table width="100%" class="lc">
  <tr>
    <td colspan="2" style="border: 1px solid; ">
    <b style="color: #2F86DE ">CONSERFLOW S.A. DE C.V.</b>
    </td>
  </tr>
  <tr>
    <td style="border: 1px solid;"><img src="img/conserflow.png"  width="95px" ></td>
    <td style="border: 1px solid;"><b>LISTA DE ASISTENCIA</b> </td>
  </tr>
</table>
<table width="100%" class="ft">
<tr>
<td width="25%">&nbsp;</td>
<td width="25%">&nbsp;</td>
<td width="25%" style="border: 1px solid; background-color: #4D5761"><div style="color:white;">Fecha del Reporte:</div> </td>
<td width="25%" style="border: 1px solid;">{{$fecha_reporte}}</td>
</tr>
</table>
@if($tipo == 1)
<table width="100%" class="lc">
<tr>
<th style="border: 1px solid; background-color: #035C93;"><div style="color:white;">#</div> </th>
<th style="border: 1px solid; background-color: #035C93;"><div style="color:white;">EMPLEADO</div> </th>
<th style="border: 1px solid; background-color: #035C93;"><div style="color:white;">PUESTO</div> </th>
<th style="border: 1px solid; background-color: #035C93;"><div style="color:white;">PROYECTO</div> </th>
<th style="border: 1px solid; background-color: #035C93;"><div style="color:white;">HORA</div> </th>
<th style="border: 1px solid; background-color: #035C93;"><div style="color:white;">FIRMA</div> </th>
</tr>
@foreach($arreglo as $key => $asistencia)
<tr>
  <td>{{$key + 1}}</td>
  <td style="border: 1px solid;">{{$asistencia['empleado']->empleado}}</td>
  <td style="border: 1px solid;">{{$asistencia['puesto']->nombre}}</td>
  <td style="border: 1px solid;">{{$asistencia['proyecto']}}</td>
  <td style="border: 1px solid;">

    @foreach ($asistencia['registros'] as $k => $value)
    {{ substr($value->hora,0,5).' / '}}
    @endforeach

   </td>
  <td style="border: 1px solid;"></td>

</tr>

@endforeach
</table>
@elseif($tipo == 2)
<table width="100%" class="lc">
<tr>
<th style="border: 1px solid; background-color: #035C93;"><div style="color:white;">#</div> </th>
<th style="border: 1px solid; background-color: #035C93;"><div style="color:white;">EMPLEADO</div> </th>
<th style="border: 1px solid; background-color: #035C93;"><div style="color:white;">PUESTO</div> </th>
<th style="border: 1px solid; background-color: #035C93;"><div style="color:white;">RANGO</div> </th>
<th style="border: 1px solid; background-color: #035C93;"><div style="color:white;">FIRMA</div> </th>
</tr>
@foreach($control_tiempo as $key_ct => $ct)
<tr>
  <td>{{$key_ct + 1}}</td>
  <td style="border: 1px solid;">{{$ct->nombre.' '.$ct->ap_paterno.' '.$ct->ap_materno}}</td>
  <td style="border: 1px solid;">{{$ct->nom_puesto}}</td>
  <td style="border: 1px solid;">
    @if($ct->id_checador == 1)
    Operativo
    @elseif($ct->id_checador == 2)
    Administrativo
    @elseif($ct->id_checador == 3)
    Operativo
    @elseif($ct->id_checador == 4)
    Administrativo
    @endif
  </td>
  <td style="border: 1px solid;"></td>

</tr>

@endforeach
</table>
@endif
</div>
</body>
</html>
