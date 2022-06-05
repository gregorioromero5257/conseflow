<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PSE-01_F-01 BITÁCORA DE RESIDUOS URBANOS</title>
  <head>
  <style>
            /** Define the margins of your page **/
            @page {
                margin-top: 3cm;
                margin-left: 2cm;
                margin-right: 2cm;
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
        <td rowspan="3" style="border: 1px solid; text-align: center;" width="55%"><b>BITÁCORA DE RESIDUOS URBANOS</b> </td>
        <td style="border: 1px solid; text-align: center;"  width="10%">CÓDIGO</td>
        <td style="border: 1px solid; text-align: center;"  width="15%">PSE-01/F-01</td>
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

            <table width="100%" style="b font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;">
        <tr>
        <td width="30%"> CONSERFLOW S.A. DE C.V. </td>
        <td  width="40%">&nbsp;</td>
        <td  width="30%">Página <b>1</b>  de <b>1</b></td>
        </tr>
        </table>
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            <div style="height: 30px;">&nbsp;</div>

            <table width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;">

            <tr>
            <td  style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white; "><b>No.</b></div> </td>
            <td  style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;"><b>Fecha Entrada</b></div></td>
            <td  style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;"><b> Residuo</b></div></td>
            <td  style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;"><b>Cantidad </b></div></td>
            <td  style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;"><b> Unidad</b></div></td>
            <td  style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;"><b> Fecha de Salida</b></div></td>
            <td  style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;"><b> Nombre del personal que entrega</b></div></td>
            <td  style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;"><b>Proveedor que recolecto</b></div></td>
            <td  style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;"><b>Folio</b></div></td>
            </tr>

            @foreach ($data as $key => $value)
            <tr>
            <td style="border: 1px solid; text-align: center; background-color: #BFBFBF;">{{$key + 1}}</td>
            <td style="border: 1px solid; text-align: center;">{!! nl2br(str_replace(',',PHP_EOL,$value->fechas)) !!}</td>
            <td style="border: 1px solid; text-align: center;">&nbsp;{{$value->residuo}}</td>
            <td style="border: 1px solid; text-align: center;">&nbsp;{{$value->cantidad}}</td>
            <td style="border: 1px solid; text-align: center;">&nbsp;{{$value->unidad}}</td>
            <td style="border: 1px solid; text-align: center;">&nbsp;{{$value->fecha_salida}}</td>
            <td style="border: 1px solid; text-align: center;">&nbsp;{{$value->entrega}}</td>
            <td style="border: 1px solid; text-align: center;">&nbsp;{{$value->proveedor}}</td>
            <td style="border: 1px solid; text-align: center;">&nbsp;{{$value->folio}}</td>
            </tr>
            @endforeach



            </table>





        </main>
    </body>
</html>
<script type="text/php">
  if (isset($pdf))
    {
      $font = Font_Metrics::get_font("Arial", "bold");
      $pdf->page_text(765, 550, "Pagina {PAGE_NUM} de {PAGE_COUNT}", $font, 9, array(0, 0, 0));
    }
</script>
