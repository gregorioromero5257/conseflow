<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PTI-01-F_04 >VALE DE RESGUARDO DE EQUIPO DE TI</title>
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

<body>
  <header>

    <table width="100%" style="border-collapse: collapse; border: 1px solid; font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            text-align: center;">
      <tr>
        <td colspan="4" style="border: 1px solid; ">
          <div style="color: #00B0F0;"> CONSTRUCTORA Y SERVICIOS CALDERÓN TORRES S.A. DE C.V.</div>
        </td>
      </tr>
      <tr>
        <td rowspan="3" width="20%"><img src="img/CSCT.png" width="120"></td>
        <td style="border: 1px solid; " width="50%">DOCUMENTO DEL SISTEMA DE GESTIÓN INTEGRAL</div>
        </td>
        <td style="border: 1px solid; text-align: center;" width="15%">
          <div style="color: #00B0F0;"> CÓDIGO</div>
        </td>
        <td style="border: 1px solid; text-align: center;" width="15%">
          <div style="color: #00B0F0;">PTI-01/F-04</div>
        </td>
      </tr>
      <tr>
        <td rowspan="2" style="border: 1px solid; text-align: center;"><b>VALE DE RESGUARDO DE EQUIPO DE TI</b> </td>
        <td style="border: 1px solid; text-align: center;">
          <div style="color: #00B0F0;">REVISIÓN</div>
        </td>
        <td style="border: 1px solid; text-align: center;">
          <div style="color: #00B0F0;">00</div>
        </td>
      </tr>
      <tr>
        <td style="border: 1px solid; text-align: center;">
          <div style="color: #00B0F0;">EMISIÓN</div>
        </td>
        <td style="border: 1px solid; text-align: center;">
          <div style="color: #00B0F0;">08.JUL.2020</div>
        </td>
      </tr>
    </table>

  </header>

  <footer>
    <p>CONSTRUCTORA Y SERVICIOS CALDERÓN TORRES S.A. DE C.V.</p>
  </footer>

  <!-- Wrap the content of your PDF inside a main tag -->
  <main>

    <table width="100%" style="font-family: Arial, Helvetica, sans-serif; font-size: 16px;">
      <tr>
        <td width="70%">&nbsp;</td>
        <td width="10%"><b>Fecha:</b></td>
        <td width="20%" style="border-bottom: 1px solid;">&nbsp;{{$fecha_fin}}</td>
      </tr>
    </table>

    <div style="height: 40px;">&nbsp;</div>

    <table width="100%" style=" border-collapse: collapse; border: 1px solid; font-family: Arial, Helvetica, sans-serif;
font-size: 12px;">
      <tr>
        <td style="border: 1px solid; background-color:#B2DE82; text-align:center;" width="30%" height="20"><b>Tipo de equipo</b> </td>
        <td style="border: 1px solid; " width="70%">&nbsp;
          @if($data->tipo == 1)
          COMPUTO
          @elseif($data->tipo == 2)
          ACECESORIOS
          @elseif($data->tipo == 3)
          IMPRESIÓN
          @elseif($data->tipo == 4)
          VIDEO
          @endif
        </td>
      </tr>
      <tr>
        <td style="border: 1px solid; background-color:#B2DE82; text-align:center;" height="20"><b>Marca</b> </td>
        <td style="border: 1px solid; ">&nbsp;
          @if($data->tipo == 1)
          {{$cdata->marca_modelo}}
          @elseif($data->tipo == 2)
          {{$cdata->marca}}
          @elseif($data->tipo == 3)
          {{$cdata->marca}}
          @elseif($data->tipo == 4)
          {{$cdata->marca}}
          @endif
        </td>
      </tr>
      <tr>
        <td style="border: 1px solid; background-color:#B2DE82; text-align:center;" height="20"><b>Modelo</b> </td>
        <td style="border: 1px solid; ">&nbsp;
          @if($data->tipo == 1)
          {{$cdata->marca_modelo}}
          @elseif($data->tipo == 2)
          {{$cdata->modelo}}
          @elseif($data->tipo == 3)
          {{$cdata->modelo}}
          @elseif($data->tipo == 4)
          {{$cdata->modelo}}
          @endif
        </td>
      </tr>
      <tr>
        <td style="border: 1px solid; background-color:#B2DE82; text-align:center;" height="20"><b>Número de serie</b> </td>
        <td style="border: 1px solid; ">&nbsp;{{$cdata->no_serie}}</td>
      </tr>
      <tr>
        <td style="border: 1px solid; background-color:#B2DE82; text-align:center;" height="20"><b>Características</b> </td>
        <td style="border: 1px solid; ">&nbsp;{{$cdata->caracteristicas}}</td>
      </tr>
      <tr>
        <td style="border: 1px solid; background-color:#B2DE82; text-align:center;" height="20"><b>Enlistar accesorios adicionales</b> </td>
        <td style="border: 1px solid; ">&nbsp;
          @if($data->accesorios_listado == '' || $data->accesorios_listado == null)
          &nbsp;{{$data->acesorios}}
          @else
            @foreach ($accesorios as $key_a => $value_a)
            {{$value_a->descripcion.' Cantidad : '.$value_a->cantidad}}<br>
            @endforeach
          @endif
          </td>
      </tr>
      <tr>
        <td style="border: 1px solid; background-color:#B2DE82; text-align:center;" height="20"><b>Estado del Equipo</b> </td>
        <td style="border: 1px solid; ">&nbsp;{{$data->observacion_uno}}</td>
      </tr>
      <tr>
        <td style="border: 1px solid; background-color:#B2DE82; text-align:center;" height="20"><b> Observaciones adicionales a la recepción</b></td>
        <td style="border: 1px solid; ">&nbsp;{{$data->observacion_uno}}</td>
      </tr>
      <tr>
        <td style="border: 1px solid; background-color:#B2DE82; text-align:center;" height="20"><b>Observaciones adicionales a la entrega</b> </td>
        <td style="border: 1px solid; ">&nbsp;{{$data->observacion_dos}}</td>
      </tr>
    </table>

    <div style="height: 40px;">&nbsp;</div>
    <p style="font-family: Arial, Helvetica, sans-serif;
              font-size: 14px; text-align: justify;">
      El equipo descrito anteriormente, me fue asignado como herramienta de trabajo para el desempeño de mis funciones. Me comprometo a respetar, aplicar y cumplir los lineamientos de uso, manejo de información y recuperación de equipo, así como todas aquellas que CONSTRUCTORA Y SERVICIOS CALDERON TORRES establezca.
      <br><br>
      Al no requerir el equipo por razón de terminación de mi relación laboral, cambio de puesto o responsabilidad u otra similar soy responsable de regresarlos al responsable asignado de tecnologías de la información para la cancelación de este documento. En caso de no devolverlo total, o parcialmente, se me será descontado de mi sueldo al costo de reposición vigente del mercado. En caso de robo, es mi responsabilidad dar aviso inmediato al responsable de recursos humanos y participar en las investigaciones pertinentes.
    </p>
    <div style="height: 40px;">&nbsp;</div>
    <table width="95%" style="padding-left: 50px; font-family: Arial, Helvetica, sans-serif;
              font-size: 14px; text-align: center;">
      <tr>
        <td style="border-bottom: 1px solid;" width="30%">&nbsp;</td>
        <td width="35%">&nbsp;</td>
        <td style="border-bottom: 1px solid;" width="30%">&nbsp;</td>
      </tr>
      <tr>
        <td>Entrega
          <br>Tecnologá de la información
        </td>
        <td>&nbsp;</td>
        <td>Recibe
          <br>Usuario Asignado
        </td>
      </tr>
    </table>
    </div>
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
