<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> -->
  <title>Prestamos por departamento</title>
  <style>
    /** Define the margins of your page **/
    @page {
        margin: 100px 25px 50px 25px;
    }

    header {
        position: fixed;
        top: -120px;
        left: 0px;
        right: 0px;
        height: 100px;
    }

    footer {
        position: fixed; 
        bottom: -40px; 
        left: 0px; 
        right: 0px;
        height: 40px;
    }
    .page-break {
        page-break-after: always;
    }
</style>
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
.letratablar{
  font-size: 16px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: left;
    font-weight: bold;
    padding-left: 10px;
    border-collapse: collapse;
    
}
.letratablarr{
  font-size: 14px;
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
  padding-top: 20px;
  border-bottom: 2px solid black;
}
.letratablapiedos{
  font-size: 12px;
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
</style>
</head>
<body >

    <script type="text/php">
        if (isset($pdf)) {
          $font = $fontMetrics->getFont("Arial", "bold");
          $pdf->page_text(380, 585, "Pag. {PAGE_NUM}/{PAGE_COUNT}", $font, 9, array(0, 0, 0));
        }
    </script>

    <header>
      <table>
        <tr>
          <td><img src="img/conserflow.png" alt="Conserflow" width="120" height="60"class="img" align="center" style="padding-bottom: 40px; padding-top: 40px;"></td>
          <td style="padding-left: 60px; " class="letradostabla" width="300"><b class="letrauno">CONSTRUCTORA Y SERVICIOS CALDERON TORRES S.A. DE C.V. </b>
              <br><b class="letrados">INGENIERIA EN AUTOMATIZACION+ INSTRUMENTACION+ELECTRICA MANTENIMIENTO INDUSTRIAL</b><br>
          </td>

        </tr>
      </table>
    </header>

    <footer>
    </footer>

    <main>
      <div class="div">

      <p>Periodo del {{ $fecha_inicial }} al {{ $fecha_final }}</p>
      <p>Fecha de pago {{ $fecha_pago }}</p>

      <table class="table">
          <tr>
          <th align="center" width="20%">Empleado</th>
          <th align="center" width="20%">Departamento</th>
          <th align="center" width="15%">Fecha</th>
          <th align="center" width="15%">Prestamo</th>
          <th align="center" width="15%">Cantidad</th>
          <th align="center" width="15%">Cantidad pagada</th>
          </tr>
          @foreach($prestamos as $k => $prestamo)
          <tr>
              <td>{{ $prestamos[$k]->nombre.' '.$prestamos[$k]->ap_paterno.' '.$prestamos[$k]->ap_materno }}</td>
              <td>{{ $prestamos[$k]->departamento }}</td>
              <td align="center">{{ $prestamos[$k]->fecha }}</td>
              <td align="center">{{ $prestamos[$k]->cantidad_pagada == $prestamos[$k]->cantidad ? 'Pagado' : 'Por pagar' }}</td>
              <td align="right">{{ $prestamos[$k]->cantidad }}</td>
              <td align="right">{{ $prestamos[$k]->cantidad_pagada }}</td>
          </tr>
          @endforeach
          <tr>
            <td colspan="4"></td>
            <td><b>Cantidad pagada</b></td>
            <td align="right">{{ number_format($total_pagado, 2) }}</td>
          </tr>
          <tr>
            <td colspan="4"></td>
            <td><b>Por pagar</b></td>
            <td align="right">{{ number_format($total_prestamos-$total_pagado, 2) }}</td>
          </tr>
          <tr>
            <td colspan="4"></td>
            <td><b>Total prestamos</b></td>
            <td align="right">{{ number_format($total_prestamos, 2) }}</td>
          </tr>
      </table>

      </div>

    </main>

</body>
</html>
