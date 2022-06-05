<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PSE-01/F04 VALE DE ENTREGA DE EQUIPO DE PROTECCIÓN PERSONAL </title>
  <head>
  <style>
            /** Define the margins of your page **/
            @page {
                margin-top: 3cm;
                margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 2cm;
            }

            header {
                position: fixed;
                top: -80px;
                left: 0px;
                right: 0px;
                height: 20px;

                /** Extra personal styles **/
                /* background-color: #03a9f4;
                color: white;
                text-align: center;
                line-height: 35px; */
            }

            footer {
                position: fixed;
                bottom: -20px;
                left: 0px;
                right: 0px;
                height: 20px;

                /** Extra personal styles **/
                color: #4472C4;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;

            }
          .letter{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
          }
          .vert{
            font-size: 10px;
            white-space:pre-line;
            g-origin:40% 40%;
            -webkit-transform: rotate(270deg);
            -moz-transform: rotate(270deg);
            -ms-transform: rotate(270deg);
            -o-transform: rotate(270deg);
            transform: rotate(270deg);
          }
        </style>
        </head>
        <body>
        <!-- Define header and footer blocks before your content -->
        <header>
        <table width="100%" style="border-collapse: collapse; border: 1px solid; font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            text-align: center;">
        <tr>
        <th colspan="4" style="border: 1px solid; "><div style="color: #4472C4;"> CONSERFLOW S.A. DE C.V.</div></th>
        </tr>
        <tr>
        <td rowspan="3" width="20%"><img src="img/conserflow.png" width="120"></td>
        <td rowspan="3" style="border: 1px solid; text-align: center;" width="55%"><b>VALE DE ENTREGA DE EQUIPO DE PROTECCIÓN PERSONAL </b> </td>
        <td style="border: 1px solid; text-align: center;"  width="10%">CÓDIGO</td>
        <td style="border: 1px solid; text-align: center;"  width="15%">PSE-01/F04</td>
        </tr>
        <tr>
        <td style="border: 1px solid; text-align: center;" >REVISIÓN</td>
        <td style="border: 1px solid; text-align: center;" >00</td>
        </tr>
        <tr>
        <td style="border: 1px solid; text-align: center;" >EMISIÓN</td>
        <td style="border: 1px solid; text-align: center;" >01.ABR.20</td>
        </tr>
        </table>

        </header>

        <footer>
          <p style="color: #2F86DE;">CONSERFLOW S.A. DE C.V.</p>

        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
    <main >
        <div style="height: 30px;">&nbsp;</div>
      <table  width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
        <tr>
          <td colspan="2"  style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;"> DATOS DEL TRABAJADOR A QUIEN SE LE ENTREGA EL ELEMENTO</div> </td>
        </tr>
        <tr>
          <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="20%">Nombre</td>
          <td width="80%" style="border: 1px solid;">&nbsp;{{$empleado_data->nombre}}</td>
        </tr>
        <tr>
          <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >Categoria</td>
          <td style="border: 1px solid;">&nbsp;{{$empleado_data->puesto}}</td>
        </tr>
      </table>
    <div height="20px;">&nbsp;</div>
      <table  width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
      <tr>
      <td colspan="5"  style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;"> DATOS DEL TRABAJADOR A QUIEN SE LE ENTREGA EL ELEMENTO</div> </td>
      </tr>
      <tr>
      <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="50%">Descripción</td>
      <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="10%">Cantidad</td>
      <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="10%">Fecha</td>
      <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="20%">Autoriza</td>
      <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="10%">Firma Recibido</td>

      </tr>
      @foreach ($epp_partidas as $key => $value)
      <tr>
      <td style="border: 1px solid;">&nbsp;{{$value->descripcion}}</td>
      <td style="border: 1px solid;">&nbsp;{{$value->cantidad}}</td>
      <td style="border: 1px solid;">&nbsp;{{$value->fecha}}</td>
      <td style="border: 1px solid;">&nbsp;{{$value->autoriza}}</td>
      {{$img = 'operativos/'.$empleado_data->id.'.png'}}
      <td style="border: 1px solid;">
        <br>&nbsp;
        @if(file_exists($img))
        <img src="operativos/{{$empleado_data->id}}.png" width="50" height="60">
        @endif
      </td>
      </tr>
      @endforeach
      </table>
      <div style="page-break-inside: avoid;">
      <table  width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
      <tr>
      <td   style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;"> COMPROMISO</div> </td>
      </tr>
      <tr>
      <td style="border: 1px solid; text-align: center;">Me comprometo a utilizar adecuadamente durante la jornada laborar los elementos de protección personal recibidos y mantenerlos en
buen estado, dando cumplimineto a las normas de salud ocupacional que contribuyen a mi bienestar físico, psicológico y social. Declaro

que he recibido información sobre el uso adecuado de los mismos</td>
      </tr>
      <tr>
      <td style="border: 1px solid; text-align: center;">El presente compromiso aplica para los elementos de protección personal entregados</td>
      </tr>
      </table>
      </div>

    </main>
    <script type="text/php">
        if (isset($pdf)) {
            $text = "PAGINA {PAGE_NUM} DE {PAGE_COUNT}";
            $size = 10;
            $color = #179AC8;
            $font = $fontMetrics->getFont("Verdana");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 1;
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
    </body>
</html>
