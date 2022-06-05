<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> -->
  <title>Proveedor Estado</title>
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
.uno {
  text-align:  center;
  padding-left: 150px;

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

.letratablar{
  font-size: 13px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: right;
    font-weight: bold;
    padding-left: 10px;
    border-collapse: collapse;

}
.letratablarr{
  font-size: 11px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: left;
    padding-left: 10px;

}

.letradostabla{
    font-size: 10px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
    font-weight: bold;
}


.letratablapieuno{
  font-size: 14px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
    border-bottom: 2px solid black;
}
.letratablapiedos{
  font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
    font-weight: bold;
}

table.estilo th, td {
  font-size: 11px;
    font-family: Arial, Helvetica, sans-serif;
}
.estilo th, td {
    padding: 5px;
    text-align: center;
    border-collapse: collapse;
}


body {
  margin: 0px;
}

</style>

</head>
<body >
<div class="div">

<table>
  <tr>
    <td><img src="img/conserflow.png" alt="Conserflow" width="120" height="60"class="img" align="center" style="padding-bottom: 10px; padding-top: 10px;"></td>
    <td style="padding-left: 50px; " class="letradostabla" width="300"><b class="letrauno">CONSERFLOW S.A. DE C.V. </b>
    </td>

  </tr>
</table>


  <table style="border-collapse: collapse; padding-left: 0px; padding-top: 10px; " align="right">
    <tr>
<td class="letratablar"></td></tr>
<tr>
<td class="letratablar">ESTADO PROVEEDOR.</td>
</tr>
  </table>


    <table style="border-collapse: collapse; padding-top: 70px;">
        <thead>
            <tr class="table-active">
                <th style="width:10%">Fecha</th>
                <th style="width:10%">Tipo</th>
                <th style="width:25%">Concepto</th>
                <th style="width:25%">Referencia</th>
                <th style="width:10%">Cargo</th>
                <th style="width:10%">Abono</th>
                <th style="width:10%">Saldos</th>
            </tr>
        </thead>
        @foreach ($arreglo as $key => $value)

        <tr>
        <td>{{$value['ocp']->fecha}}</td>
        <td>{{$value['ocp']->tipo}}</td>
        <td>{{$proveedor->razon_social}}</td>
        <td>{{$value['ocp']->folio}}</td>
        <td>{{$value['ocp']->tipo === 'compra' ? $value['ocp']->total : ''}}</td>
        <td>{{$value['ocp']->tipo === 'pago' ? $value['ocp']->total : ''}}</td>
        <td>{{($value['saldo'])}}</td>
        </tr>
      @endforeach
        <tr>
          <td colspan="3"></td>
          <td><b>Total</b></td>
          <td><b>{{$tc}}</b></td>
          <td><b>{{$tp}}</b></td>
          <td><b>{{$value['saldo']}}</b></td>
        </tr>
    </table>






</div>


</body>

</html>
