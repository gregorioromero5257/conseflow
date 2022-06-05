<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>PTI-01-F_01 INVENTARIO DE EQUIPO COMPUTACIONAL</title>
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
          <td style="border: 1px solid; " width="60%"> DOCUMENTO DEL SISTEMA DE GESTIÓN INTEGRAL</div></td>
          <td style="border: 1px solid; text-align: center;" width="10%"><div style="color: #00B0F0;"> CÓDIGO</div></td>
          <td style="border: 1px solid; text-align: center;" width="10%"><div style="color: #00B0F0;">PTI-01/F-01</div></td>
          </tr>
          <tr>
          <td rowspan="2" style="border: 1px solid; text-align: center;"><b>INVENTARIO DE EQUIPO COMPUTACIONAL</b> </td>
          <td style="border: 1px solid; text-align: center;" ><div style="color: #00B0F0;">REVISIÓN</div></td>
          <td style="border: 1px solid; text-align: center;" ><div style="color: #00B0F0;">00</div></td>
          </tr>
          <tr>
          <td style="border: 1px solid; text-align: center;" ><div style="color: #00B0F0;">EMISIÓN</div></td>
          <td style="border: 1px solid; text-align: center;" ><div style="color: #00B0F0;">08.JUl.2020</div></td>
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
  <td style="border: 1px solid; text-align: center; background-color: #0082B0;" ><div style="color: white;">USUARIO</div></td>
  <td style="border: 1px solid; text-align: center; background-color: #0082B0;" ><div style="color: white;">NÚM. SERIE</div></td>
  <td style="border: 1px solid; text-align: center; background-color: #0082B0;" ><div style="color: white;">MARCA Y MODELO</div></td>
  <td style="border: 1px solid; text-align: center; background-color: #0082B0;" ><div style="color: white;">SO</div></td>
  <td style="border: 1px solid; text-align: center; background-color: #0082B0;" ><div style="color: white;">CPU</div></td>
  <td style="border: 1px solid; text-align: center; background-color: #0082B0;" ><div style="color: white;">RAM</div></td>
  <td style="border: 1px solid; text-align: center; background-color: #0082B0;" ><div style="color: white;">ALMACENAMIENTO</div></td>
  <td style="border: 1px solid; text-align: center; background-color: #0082B0;" ><div style="color: white;">TARJETA DE VIDEO</div></td>
  <td style="border: 1px solid; text-align: center; background-color: #0082B0;" ><div style="color: white;">MAC</div></td>
  <td style="border: 1px solid; text-align: center; background-color: #0082B0;" ><div style="color: white;">OBSERVACIONES</div></td>
  <td style="border: 1px solid; text-align: center; background-color: #0082B0;" ><div style="color: white;">COLOR</div></td>
  <td style="border: 1px solid; text-align: center; background-color: #0082B0;" ><div style="color: white;">FECHA DE RESGUARDO</div></td>
</tr>
@foreach ($arreglo as $key => $value)
@if($value['resguardo'] != null)
<tr>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value['resguardo'] == null ? '' :  $value['resguardo']->nom_usr}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value['computo']['no_serie']}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value['computo']['marca_modelo']}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value['computo']['so']}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value['computo']['cpu']}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value['computo']['ram']}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value['computo']['almacenamiento']}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value['computo']['tarjeta_video']}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value['computo']['mac']}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value['computo']['observaciones']}} <br>{{$value['estado']}}</td>
<td style="border: 1px solid; text-align: center;">&nbsp;</td>
<td style="border: 1px solid; text-align: center;">&nbsp;{{$value['resguardo'] == null ? '' : $value['resguardo']->fecha}}</td>
</tr>
@endif
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
