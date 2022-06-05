<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> -->
  <title>Materiales...</title>
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

  .letradostabla{
    font-size: 10px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
    font-weight: bold;
  }
  .letratresstabla{
      font-size: 8px;
       font-family: Arial, Helvetica, sans-serif;
      text-align: center;
  }

</style>

</head>
<body >

  <div class="div" >
    <table>
    <tr>
      <td rowspan="3" width="20%">
          <img src="img/conserflow.png" alt="Conserflow" width="120" height="50">
      </td>
      <td class="letradostabla" width="80%">
          <b>CONSERFLOW S.A. DE C.V.</b>
      </td>
    </tr>
    <tr>
        <td class="letratresstabla">
          INGENIERIA EN AUTOMATIZACION+ INSTRUMENTACION+ELECTRICA MANTENIMIENTO INDUSTRIAL + MONTAJES MECÁNICOS + FABRICACIÓN METAL MECANICA + OBRA PESADA
        </td>
    </tr>
    <tr>
      <td class="letratresstabla" >
        Dirección: Calle Del Mezquite Lote 5 Mza. 3, Parque Industrial Tehuacán-Miahuatlán, C.P. 75820 Tehuacán, Pue.
        <br>
        RFC: CON1912026U2
      </td>
    </tr>
  </table>
  <br>

    <table id="customers" class="letradostabla">
      <tr>
        <th width="20%">Artículo</th>
        <th width="15%">Proyecto</th>
        <th width="15%">Salida</th>
        <th width="15%">Lote</th>
        <th width="15%">Cantidad</th>
        <th width="15%">Precio Unitario</th>
        <th width="15%">Total</th>
      </tr>
      @foreach($partidas as $valor)
      <tr>
        <td >{{$valor->descripcion}}</td>
        <td>{{$valor->nombre}}</td>
        <td>{{$valor->tipo_salida_nombre}}</td>
        <td>{{$valor->lote_id}}</td>
        <td>{{$valor->cantidad}}</td>
        <td>{{$valor->precio_unitario}}</td>
        <td>{{$valor->total}}</td>
      </tr>
      @endforeach
      <tr>
        <td colspan="5">&nbsp;</td>
        <td><b>Total</b></td>
        <td>{{$total}}</td>
      </tr>
    </table>


  </div>


</body>
</html>
