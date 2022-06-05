<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PMN-02-F_02 SOLICITUD DE VEHÍCULOS</title>
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

    .borc {
      border: 1px solid;
      text-align: center;
    }

    .borce {
      border: 1px solid;
      text-align: center;
      background-color: #0070C0;
      font-weight: bold;
    }

    .borcd {
      border: 1px solid;
      text-align: center;
      background-color: #B2DE82;
      font-weight: bold;
    }

    .borl {
      border: 1px solid;
    }

    .tabe {
      border-collapse: collapse;
      border: 1px solid;
      font-family: Arial, Helvetica, sans-serif;
      font-size: 14px;
      text-align: center;
    }

    .tab {
      border-collapse: collapse;
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
      text-align: center;
      border: 2px solid;
    }

    .gr {
      border: 1px solid;
      background-color: #B2DE82;
    }
  </style>

</head>

<body>
  <header>

    <table width="100%" class="tabe">
      <tr>
        <td colspan="4" style="border: 1px solid; ">
          <div style="color: #0070C0;"><b> CONSTRUCTORA Y SERVICIOS CALDERON TORRES S.A. DE C.V.</b></div>
        </td>
      </tr>
      <tr>
        <td rowspan="3" width="20%"><img src="img/CSCT.png" width="120"></td>
        <td class="borc" width="50%">DOCUMENTO DEL SISTEMA DE GESTIÓN VEHICULAR</td>
        <td class="borc" width="15%">
          <div> CÓDIGO</div>
        </td>
        <td class="borc" width="15%">
          <div>{{$solicitud->empresa==1?'NA':'PMN-02/F-02'}}</div>
        </td>
      </tr>
      <tr>
        <td rowspan="2" class="borc"><b>SOLICITUD DE VEHÍCULOS</b> </td>
        <td class="borc">
          <div>REVISIÓN</div>
        </td>
        <td class="borc">
          <div>{{$solicitud->empresa==1?'NA':'00'}}</div>
        </td>
      </tr>
      <tr>
        <td class="borc">
          <div>EMISIÓN</div>
        </td>
        <td class="borc">
          <div>{{$solicitud->empresa==1?'NA':'13.JUL.2020'}}</div>
        </td>
      </tr>
    </table>

  </header>

  <footer>
    <p>CONSTRUCTORA Y SERVICIOS CALDERON TORRES S.A. DE C.V.</p>
  </footer>

  <!-- Wrap the content of your PDF inside a main tag -->
  <main>
    <table width="100%" class="tab">
      <tr>
        <td class="gr" width="20%">Fecha de Solicitud</td>
        <td colspan="2" class="borl" width="20%">{{$solicitud->fecha_solicitud}}</td>
        <td colspan="2" class="gr" width="20%">Folio</td>
        <td colspan="3" class="borl" width="30%">{{$solicitud->folio}}</td>
      </tr>
      <tr>
        <td colspan="2" class="gr">Tipo de unidad solicitada</td>
        <td colspan="6" class="borl">{{$solicitud->clase_tipo ." ". $solicitud->nombre_unidad}}</td>
      </tr>

      <tr>
        <td class="gr">Lapso de Tiempo</td>
        <td colspan="3" class="borl">{{$solicitud->tiempo}}</td>
        <td class="gr">Fecha de Inicio</td>
        <td class="borl">{{$solicitud->fecha_inicio}}</td>
        <td class="gr">Fecha de Término</td>
        <td class="borl">{{$solicitud->fecha_fin}}</td>
      </tr>
    </table>
    <table width="100%" class="tab">
      <tr>
        <td colspan="6" style="background-color:#0082B0">
          <div style="color:white;"><b>¿Dónde se tiene planeado el uso de la unidad?</b></div>
        </td>
      </tr>
      <tr>
        <td class="gr">Ciudad</td>
        <td class="borl">{{$solicitud->ciudad}}</td>
        <td class="gr">Estado</td>
        <td class="borl">{{$solicitud->estado}}</td>
        <td class="gr">Poblacion</td>
        <td class="borl">{{$solicitud->poblacion}}</td>
      </tr>
      <tr>
        <td class="gr" width="20%">Proyecto (en caso de aplicar)</td>
        <td colspan="5" class="borl" width="80%">{{$solicitud->proyecto==null?"N/A":$solicitud->proyecto}}</td>
      </tr>
      <tr>
        <td colspan="6" style="background-color:#0082B0">
          <div style="color:white;"><b>La necesidad de la unidad es:</b></div>
        </td>
      </tr>
      <tr>
        <td colspan="6" class="borl">{{$solicitud->necesidad}}</td>
      </tr>
      <tr>
        <td colspan="6" style="background-color:#0082B0">
          <div style="color:white;"><b>Personas asignadas para el manejo y responsabilidad de la unidad</b></div>
        </td>
      </tr>
      <tr>
        <td colspan="6" class="borl">responsables</td>
      </tr>
      <tr>
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>{{$solicitud->solicita}}</td>
        <td colspan="2"></td>
        <td>{{$solicitud->autoriza}}</td>
        <td></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td style="border-top:1px solid;">Persona que solicita <br>Nombre y Firma</td>
        <td colspan="2"></td>
        <td style="border-top:1px solid;">Persona que autoriza <br>Nombre y Firma</td>
        <td></td>
      </tr>

      <tr>
        <td colspan="6">&nbsp;</td>
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