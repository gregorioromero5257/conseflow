<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> -->
  <title>Datos Bancarios...</title>
<style media="screen">

.div {
  width: 100%;
  margin-left: 10px;
  margin-right: 10px;
  margin-top: 0px;
}
.img {
  padding-left: 12px;
  padding-top: 0px;
  
}

.letrauno {
  font-size: 16px;
    font-family: Arial, Helvetica, sans-serif;
    border: 2px;
    
    
}
.letrados {
  font-size: 10px;
    font-family: Arial, Helvetica, sans-serif;
    border: 1px;
    
}

.letradostabla{
    font-size: 10px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
    font-weight: bold;
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


</style>

</head>
<body >
<div class="div">

<table>
  <tr>
    <td><img src="img/conserflow.png" alt="Conserflow" width="120" height="60"class="img" align="center" style="padding-bottom: 40px; padding-top: 40px;"></td>
    <td style="padding-left: 60px; " class="letradostabla" width="300"><b class="letrauno">CONSTRUCTORA Y SERVICIOS CALDERON TORRES S.A. DE C.V. </b>
        <br><b class="letrados">INGENIERIA EN AUTOMATIZACION+ INSTRUMENTACION+ELECTRICA MANTENIMIENTO INDUSTRIAL</b><br>
    </td>

  </tr>
</table>


<table id="customers">
    <tr>
    <th width="20%">Empleado</th>
    <th width="20%">Proyecto</th>
    <th width="15%">No. Tarjeta</th>
    <th width="15%">No. Cuenta</th>
    <th width="15%">CLABE</th>
    <th width="15%">Banco</th>
    </tr>
    @foreach($empleados as $empleado)
    <tr>
        <td>{{ $empleado->empleado }}</td>
        <td>{{ $empleado->proyecto }}</td>
        <td>{{ $empleado->numero_tarjeta }}</td>
        <td>{{ $empleado->numero_cuenta }}</td>
        <td>{{ $empleado->clabe }}</td>
        <td>{{ $empleado->banco }}</td>
    </tr>
    @endforeach
</table>

</div>


</body>
</html>
