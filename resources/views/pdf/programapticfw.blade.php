<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PTI-01_F-04 PROGRAMA ANUAL DE MANTENIMIENTO PREVENTIVO DE EQUIPO DE TI</title>
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
                top: -70px;
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
            font-size: 12px;
            text-align: center;">
        <tr>
        <th colspan="4" style="border: 1px solid; "><div style="color: #4472C4;"> CONSERFLOW S.A. DE C.V.</div></th>
        </tr>
        <tr>
        <td rowspan="3" width="20%"><img src="img/conserflow.png" width="120"></td>
        <td rowspan="3" style="border: 1px solid; text-align: center;" width="55%"><b>PROGRAMA ANUAL DE MANTENIMIENTO PREVENTIVO DE EQUIPO DE TI</b> </td>
        <td style="border: 1px solid; text-align: center;"  width="10%">CÓDIGO</td>
        <td style="border: 1px solid; text-align: center;"  width="15%">PTI-01/F-04</td>
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
            <div style="">
            <div style="height: 60px;">&nbsp;</div>
                 <table width="100%" style="border-collapse: collapse; border: 1px solid; font-family: Arial, Helvetica, sans-serif;
                    font-size: 12px;">
                <tr>
                <td style="background-color: #0070C0; border: 1px solid; text-align:center;"><div style="color:white;"><b>Tipo de Equipo</b> </div></td>
                <td style="background-color: #0070C0; border: 1px solid; text-align:center;"><div style="color:white;"><b>Marca</b> </div></td>
                <td style="background-color: #0070C0; border: 1px solid; text-align:center;"><div style="color:white;"><b>Modelo</b> </div></td>
                <td style="background-color: #0070C0; border: 1px solid; text-align:center;"><div style="color:white;"><b>No. Serie</b> </div></td>
                <td style="background-color: #0070C0; border: 1px solid; text-align:center;"><div style="color:white;"><b>Ene.</b> </div></td>
                <td style="background-color: #0070C0; border: 1px solid; text-align:center;"><div style="color:white;"><b>Feb.</b> </div></td>
                <td style="background-color: #0070C0; border: 1px solid; text-align:center;"><div style="color:white;"><b>Mar.</b> </div></td>
                <td style="background-color: #0070C0; border: 1px solid; text-align:center;"><div style="color:white;"><b>Abr.</b> </div></td>
                <td style="background-color: #0070C0; border: 1px solid; text-align:center;"><div style="color:white;"><b>May.</b> </div></td>
                <td style="background-color: #0070C0; border: 1px solid; text-align:center;"><div style="color:white;"><b>Jun.</b> </div></td>
                <td style="background-color: #0070C0; border: 1px solid; text-align:center;"><div style="color:white;"><b>Jul.</b> </div></td>
                <td style="background-color: #0070C0; border: 1px solid; text-align:center;"><div style="color:white;"><b>Ago.</b> </div></td>
                <td style="background-color: #0070C0; border: 1px solid; text-align:center;"><div style="color:white;"><b>Sep.</b> </div></td>
                <td style="background-color: #0070C0; border: 1px solid; text-align:center;"><div style="color:white;"><b>Oct.</b> </div></td>
                <td style="background-color: #0070C0; border: 1px solid; text-align:center;"><div style="color:white;"><b>Nov.</b> </div></td>
                <td style="background-color: #0070C0; border: 1px solid; text-align:center;"><div style="color:white;"><b>Dic.</b> </div></td>
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
    </body>
</html>
