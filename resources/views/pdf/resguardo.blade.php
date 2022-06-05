<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>PAL-01-F_06 RESGUARDO DE HERRAMIENTAS</title>
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
          <td colspan="4" style="border: 1px solid; "><div style="color: #0070C0;"><b> CONSERFLOW S.A. DE C.V.</b></div></td>
          </tr>
          <tr>
          <td rowspan="3" width="20%"><img src="img/conserflow.png" width="120"></td>
          <td rowspan="3" style="border: 1px solid; text-align: center;"><b>RESGUARDO DE HERRAMIENTAS</b> </td>
          <td style="border: 1px solid; text-align: center;" width="10%"><div style=""> CÓDIGO</div></td>
          <td style="border: 1px solid; text-align: center;" width="15%"><div style="">PAL-01/F-03</div></td>
          </tr>
          <tr>

          <td style="border: 1px solid; text-align: center;" ><div style="">REVISIÓN</div></td>
          <td style="border: 1px solid; text-align: center;" ><div style="">00</div></td>
          </tr>
          <tr>
          <td style="border: 1px solid; text-align: center;" ><div style="">EMISIÓN</div></td>
          <td style="border: 1px solid; text-align: center;" ><div style="">01.ABR.20</div></td>
          </tr>
    </table>

</header>

<footer>
<p>CONSERFLOW S.A. DE C.V.</p>
</footer>

<!-- Wrap the content of your PDF inside a main tag -->
<main>

  <table  width="100%" style="font-family: Arial, Helvetica, sans-serif; font-size: 16px; border-collapse: collapse;">

    <tr>
    <td style="border: 1px solid; text-align: center; background-color: #0070C0;" ><div style="color: white;"><b> NOMBRE DE OPERARIO</b></div></td>
    <td style="border: 1px solid; text-align: center; background-color: #0070C0;" ><div style="color: white;"><b> ÁREA</b></div></td>
    <td style="border: 1px solid; text-align: center; background-color: #0070C0;" ><div style="color: white;"><b> CATEGORIA</b></div></td>
    </tr>
    <tr>
    <td style="border: 1px solid; text-align: center;" >&nbsp;{{$empleado->nombre.' '.$empleado->ap_paterno.' '.$empleado->ap_materno}}</td>
    <td style="border: 1px solid; text-align: center;" >&nbsp;{{$empleado->area}}</td>
    <td style="border: 1px solid; text-align: center;" >&nbsp;{{$empleado->puesto}}</td>
    </tr>
  </table>

  <div style="height: 10px;">&nbsp;</div>

  <table width="100%" style=" border-collapse: collapse; border: 1px solid; font-family: Arial, Helvetica, sans-serif;
font-size: 12px;">
    <tr>
    <td style="border: 1px solid; text-align: center; background-color: #0070C0;" width="3%"><div style="color: white;"><b> PART.</b></div></td>
    <td style="border: 1px solid; text-align: center; background-color: #0070C0;" width="5%"><div style="color: white;"><b> CANT.</b></div></td>
    <td style="border: 1px solid; text-align: center; background-color: #0070C0;" width="10%"><div style="color: white;"><b> U.M.</b></div></td>
    <td style="border: 1px solid; text-align: center; background-color: #0070C0;" width="32%"><div style="color: white;"><b> DESCRIPCION</b></div></td>
    <td style="border: 1px solid; text-align: center; background-color: #0070C0;" width="15%"><div style="color: white;"><b> PROYECTO</b></div></td>
    <td style="border: 1px solid; text-align: center; background-color: #0070C0;" width="10%"><div style="color: white;"><b> FECHA DE ENTREGA.</b></div></td>
    <td style="border: 1px solid; text-align: center; background-color: #0070C0;" width="10%"><div style="color: white;"><b> FECHA DE DEVOLUCION</b></div></td>
    <td style="border: 1px solid; text-align: center; background-color: #0070C0;" width="15%"><div style="color: white;"><b> FIRMA</b></div></td>
    </tr>
    @foreach ($arreglo as $key => $value)
    <tr>
    <td style="border: 1px solid; background-color:#BFBFBF; text-align:center;"  ><b>{{$key + 1}}</b> </td>
    <td style="border: 1px solid; text-align: center;" >&nbsp;{{$value['salida']->cantidad}}</td>
    <td style="border: 1px solid; text-align: center;" >&nbsp;{{$value['salida']->unidad}}</td>
    <td style="border: 1px solid; text-align: center;" >&nbsp;{{$value['salida']->descripcion}}</td>
    <td style="border: 1px solid; text-align: center;" >&nbsp;{{$value['salida']->nombre_corto}}</td>
    <td style="border: 1px solid; text-align: center;" >&nbsp;{{$value['salida']->fecha}}</td>
    <td style="border: 1px solid; text-align: center;" >&nbsp;{{$value['retorno'] == null ? '' : $value['retorno']->fecha}}</td>
    <td style="border: 1px solid; text-align: center;" >&nbsp;</td>
    </tr>
    @endforeach
    <tr>
    <td style="border: 1px solid; background-color:#BFBFBF; text-align:center;"  ><b>-</b> </td>
    <td style="border: 1px solid; text-align: center;" >&nbsp;</td>
    <td style="border: 1px solid; text-align: center;" >&nbsp;</td>
    <td style="border: 1px solid; text-align: center;" >&nbsp;</td>
    <td style="border: 1px solid; text-align: center;" >&nbsp;</td>
    <td style="border: 1px solid; text-align: center;" >&nbsp;</td>
    <td style="border: 1px solid; text-align: center;" >&nbsp;</td>
    <td style="border: 1px solid; text-align: center;" >&nbsp;</td>
    </tr>


  </table>

  <div style="height: 40px;">&nbsp;</div>

  <p style="text-align:justify; font-weight: bold; font-family: Arial, Helvetica, sans-serif;
font-size: 16px;">NOTA: El personal que firma como responsable tiene el compromiso de devolver o reponer las herramientas y/o materiales requeridos mencionados o descritos en la tabla del presente documento.</p>

                  <div style="height: 40px;">&nbsp;</div>

  <table width="95%" style="padding-left: 50px; font-family: Arial, Helvetica, sans-serif;
  font-size: 14px; text-align: center;">
    <tr>
    <td>Entrega</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Responsable</td>
    <td>&nbsp;</td>
    </tr>
    <tr>
      <td style="border-bottom: 1px solid;" width="20%">&nbsp;</td>
      <td  width="15%">&nbsp;</td>
      <td style="" width="20%">&nbsp;</td>
      <td  width="15%">&nbsp;</td>
      <td style="border-bottom: 1px solid;" width="20%">&nbsp;</td>
      <td  width="10%">&nbsp;</td>
    </tr>
    <tr>
      <td>Almacén</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>Empleado/Solicitante</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</main>
<script type="text/php">
    if (isset($pdf)) {
        $text = "PAGINA {PAGE_NUM} DE {PAGE_COUNT}";
        $size = 12;
        $font = $fontMetrics->getFont("Verdana");
        $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
        $x = ($pdf->get_width() - $width) / 1;
        $y = $pdf->get_height() - 35;
        $pdf->page_text($x, $y, $text, $font, $size);
    }
</script>
</body>
</html>
