<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>PTI-01-F_04 PROGRAMA ANUAL DE MANTENIMIENTO PREVENTIVO DE EQUIPO DE TI</title>
  <style type="text/css">

@page {
    margin-top: 3cm;
    margin-left: 1cm;
    margin-right: 1cm;
    margin-bottom: 2cm;
    }
    header {
    position: fixed;
    top: -100px;
    left: 0px;
    right: 0px;
    height: 160px;
    }
    footer {
    position: fixed;
    bottom: -40px;
    left: 0px;
    right: 0px;
    height: 20px;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 14px;
    }
    .titulo {
      text-align: center;
      font-weight: bold;
      font-family: Arial, Helvetica, sans-serif;
      font-size: 22px;
    }
    .letrat {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
      text-align: center;
    }
    .letraa {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
      text-align: right;
      font-weight: bold;
    }
    .letrab {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 8px;
      text-align: center;
      border: 1px solid;
    }
    .cont {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 10px;
      text-align: center;
      border: 1px solid;
    }
    .nom {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 10px;
    }

  </style>

</head>
<body >
<header>

    <table width="100%" style="border-collapse: collapse; border: 1px solid; font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            text-align: center;">
          <tr>
          <td colspan="4" style="border: 1px solid; "><div style="color: #00B0F0;"> CONSTRUCTORA Y SERVICIOS CALDERÓN TORRES S.A. DE C.V.</div></td>
          </tr>
          <tr>
          <td rowspan="3" width="20%"><img src="img/CSCT.png" width="120"></td>
          <td style="border: 1px solid; " width="60%">DOCUMENTO DEL SISTEMA DE GESTIÓN INTEGRAL</div></td>
          <td style="border: 1px solid; text-align: center;" width="10%"><div style="color: #00B0F0;"> CÓDIGO</div></td>
          <td style="border: 1px solid; text-align: center;" width="10%"><div style="color: #00B0F0;">PTI-01/F-05</div></td>
          </tr>
          <tr>
          <td rowspan="2" style="border: 1px solid; text-align: center;"><b>PROGRAMA ANUAL DE MANTENIMIENTO PREVENTIVO DE EQUIPO DE TI</b> </td>
          <td style="border: 1px solid; text-align: center;" ><div style="color: #00B0F0;">REVISIÓN</div></td>
          <td style="border: 1px solid; text-align: center;" ><div style="color: #00B0F0;">00</div></td>
          </tr>
          <tr>
          <td style="border: 1px solid; text-align: center;" ><div style="color: #00B0F0;">EMISIÓN</div></td>
          <td style="border: 1px solid; text-align: center;" ><div style="color: #00B0F0;">08.JUL.2020</div></td>
          </tr>
    </table>

</header>

<footer>
<p>CONSTRUCTORA Y SERVICIOS CALDERÓN TORRES S.A. DE C.V.</p>
</footer>

<!-- Wrap the content of your PDF inside a main tag -->
<main>
<table width="100%" style="border-collapse: collapse; border: 1px solid; font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            text-align: center;">
<tr>
  <td style="border: 1px solid; text-align: center; background-color: #B2DE82;" >TIPO</td>
  <td style="border: 1px solid; text-align: center; background-color: #B2DE82;" >MARCA</td>
  <td style="border: 1px solid; text-align: center; background-color: #B2DE82;" >MODELO</td>
  <td style="border: 1px solid; text-align: center; background-color: #B2DE82;" >NO.SERIE</td>
  <td style="border: 1px solid; text-align: center; background-color: #B2DE82;" >ENE</td>
  <td style="border: 1px solid; text-align: center; background-color: #B2DE82;" >FEB</td>
  <td style="border: 1px solid; text-align: center; background-color: #B2DE82;" >MAR</td>
  <td style="border: 1px solid; text-align: center; background-color: #B2DE82;" >ABR</td>
  <td style="border: 1px solid; text-align: center; background-color: #B2DE82;" >MAY</td>
  <td style="border: 1px solid; text-align: center; background-color: #B2DE82;" >JUN</td>
  <td style="border: 1px solid; text-align: center; background-color: #B2DE82;" >JUL</td>
  <td style="border: 1px solid; text-align: center; background-color: #B2DE82;" >AGO</td>
  <td style="border: 1px solid; text-align: center; background-color: #B2DE82;" >SEP</td>
  <td style="border: 1px solid; text-align: center; background-color: #B2DE82;" >OCT</td>
  <td style="border: 1px solid; text-align: center; background-color: #B2DE82;" >NOV</td>
  <td style="border: 1px solid; text-align: center; background-color: #B2DE82;" >DIC</td>
</tr>
@foreach ($arreglo as $key => $value)
<tr>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value->tipo == 1 ? 'Computo' : 'Otros'}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value->marca}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value->modelo}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value->num_serie}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value->mes == 1 ? 'X' : ''}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value->mes == 2 ? 'X' : ''}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value->mes == 3 ? 'X' : ''}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value->mes == 4 ? 'X' : ''}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value->mes == 5 ? 'X' : ''}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value->mes == 6 ? 'X' : ''}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value->mes == 7 ? 'X' : ''}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value->mes == 8 ? 'X' : ''}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value->mes == 9 ? 'X' : ''}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value->mes == 10 ? 'X' : ''}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value->mes == 11 ? 'X' : ''}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value->mes == 12 ? 'X' : ''}}</td>
</tr>
@endforeach
</table>
</main>
<script type="text/php">
    if (isset($pdf)) {
        $text = "PAGINA {PAGE_NUM} DE {PAGE_COUNT}";
        $size = 14;
        $font = $fontMetrics->getFont("Verdana");
        $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
        $x = ($pdf->get_width() - $width) / 1;
        $y = $pdf->get_height() - 35;
        $pdf->page_text($x, $y, $text, $font, $size);
    }
</script>
</body>
</html>
