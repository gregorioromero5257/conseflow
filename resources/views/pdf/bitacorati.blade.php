<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PTI-01_F-09 BITÁCORA DE RESGUARDO DE INFORMACIÓN</title>
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
        <header>
        <table width="100%" style="border-collapse: collapse; border: 1px solid; font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            text-align: center;">
        <tr>
        <th colspan="4" style="border: 1px solid; "><div style="color: #4472C4;"> CONSERFLOW S.A. DE C.V.</div></th>
        </tr>
        <tr>
        <td rowspan="3" width="20%"><img src="img/conserflow.png" width="120"></td>
        <td rowspan="3" style="border: 1px solid; text-align: center;" width="55%"><b>BITÁCORA DE RESGUARDO DE INFORMACIÓN</b> </td>
        <td style="border: 1px solid; text-align: center;"  width="10%">CÓDIGO</td>
        <td style="border: 1px solid; text-align: center;"  width="15%">PTI-01/F-09</td>
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

        <main>
            <div style="height: 60px;">&nbsp;</div>

            <table width="100%" style="border-collapse: collapse; border: 2px solid; font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;">
            <tr>
            <td style="border: 1px solid; background-color: #4472C4; text-align: center;"><div style="color:white; "><b> Responsable de la Información</b></div> </td>
            <td style="border: 1px solid; background-color: #4472C4; text-align: center;"><div style="color:white;"><b> Ruta de respaldo</b></div></td>
            <td style="border: 1px solid; background-color: #4472C4; text-align: center;"><div style="color:white;"><b> Tamaño</b></div></td>
            <td style="border: 1px solid; background-color: #4472C4; text-align: center;"><div style="color:white;"><b>Fecha de Resguardo </b></div></td>
            <td style="border: 1px solid; background-color: #4472C4; text-align: center;"><div style="color:white;"><b> Ubicación de Resguardo</b></div></td>
            <td style="border: 1px solid; background-color: #4472C4; text-align: center;"><div style="color:white;"><b> Responsable del respaldo</b></div></td>
            <td style="border: 1px solid; background-color: #4472C4; text-align: center;"><div style="color:white;"><b> Observaciones</b></div></td>
            </tr>
            @foreach ($data as $key => $value)
            <tr>
            <td style="border: 1px solid;">&nbsp;{{$value->empleado_ii}}</td>
            <td style="border: 1px solid;">&nbsp;{{$value->ruta}}</td>
            <td style="border: 1px solid;">&nbsp;{{$value->tamanio}}</td>
            <td style="border: 1px solid;">&nbsp;{{$value->fecha}}</td>
            <td style="border: 1px solid;">&nbsp;{{$value->ubicacion}}</td>
            <td style="border: 1px solid;">&nbsp;{{$value->empleado_ri}}</td>
            <td style="border: 1px solid;">&nbsp;{{$value->observaciones}}</td>
            </tr>
            @endforeach
            </table>





        </main>
    </body>
</html>
