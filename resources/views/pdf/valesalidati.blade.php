<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PTI-01_F-08 VALE DE SALIDA</title>
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
            font-size: 14px;
            text-align: center;">
        <tr>
        <th colspan="4" style="border: 1px solid; "><div style="color: #4472C4;"> CONSERFLOW S.A. DE C.V.</div></th>
        </tr>
        <tr>
        <td rowspan="3" width="20%"><img src="img/conserflow.png" width="120"></td>
        <td rowspan="3" style="border: 1px solid; text-align: center;" width="55%"><b>VALE DE SALIDA</b> </td>
        <td style="border: 1px solid; text-align: center;"  width="10%">CÓDIGO</td>
        <td style="border: 1px solid; text-align: center;"  width="15%">PTI-01/F-08</td>
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
            <div style="height: 60px;">&nbsp;</div>

            <table width="100%" style="border-collapse: collapse; font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;">
            <tr>
            <td width="70%">&nbsp;</td>
            <td style="border: 1px solid; font-family: Arial, Helvetica, sans-serif;
            font-size: 12px; background-color: #4472C4; text-align: right;" width="15%"><b>Fecha Salida:</b></td>
            <td style="border: 1px solid;" width="15%">&nbsp;{{$fecha_salida}}</td>
            </tr>
            <tr>
            <td colspan="3" style="height: 10px;">&nbsp;</td>
            </tr>
            <tr>
            <td width="70%">&nbsp;</td>
            <td style="border: 1px solid; font-family: Arial, Helvetica, sans-serif;
            font-size: 12px; background-color: #4472C4; text-align: right;" width="15%"><b>Fecha Retorno:</b></td>
            <td style="border: 1px solid;" width="15%">&nbsp;{{$fecha_retorno}}</td>
            </tr>
            </table>
            <table width="100%" style="border-collapse: collapse; font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;">
            <tr>
            <td style="border: 1px solid;  text-align: center;" width="15%">PROYECTO:</td>
            <td style="border: 1px solid;  text-align: center;" width="25%">&nbsp;{{$data->nombre_corto}}</td>
            <td width="60%">&nbsp;</td>
            </tr>
            <tr>
            <td style="border: 1px solid; font-family: Arial, Helvetica, sans-serif;
            font-size: 12px; text-align: center;" width="15%">UBICACIÓN:</td>
            <td style="border: 1px solid; font-family: Arial, Helvetica, sans-serif;
            font-size: 12px; text-align: center;" width="15%">&nbsp;{{$data->ciudad}}</td>
            <td width="60%">&nbsp;</td>
            </tr>
            </table>
            <div style="height: 60px;">&nbsp;</div>
            <table  width="100%" style="border-collapse: collapse; font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;">
            <tr>
            <td style="border: 1px solid;  text-align: center; background-color: #4472C4;" width="8%">PARTIDA</td>
            <td style="border: 1px solid;  text-align: center; background-color: #4472C4;" width="8%">CANTIDAD</td>
            <td style="border: 1px solid;  text-align: center; background-color: #4472C4;" width="8%">UNIDAD</td>
            <td style="border: 1px solid;  text-align: center; background-color: #4472C4;" width="76%">DESCRIPCION</td>
            </tr>
            @foreach ($arreglo as $key => $value)
            <tr>
            <td style="border: 1px solid;  text-align: center;">{{$key +  1}}</td>
            <td style="border: 1px solid;  text-align: center;">&nbsp;{{$value['data']->cantidad}}</td>
            <td style="border: 1px solid;  text-align: center;">&nbsp;{{$value['data']->unidad}}</td>
            <td style="border: 1px solid;  text-align: center;">&nbsp;{{$value['descripcion']}}</td>
            </tr>
            @endforeach
            </table>
            <div style="height: 180px;">&nbsp;</div>
         <table width="100%" style="border-collapse: collapse; font-family: Arial, Helvetica, sans-serif;
            font-size: 12px; text-align: center;">
         <tr>
         <td width="8%">&nbsp;</td>
         <td width="15%">SOLICITA</td>
         <td width="8%">&nbsp;</td>
         <td width="15%">ENTREGA</td>
         <td width="8%">&nbsp;</td>
         <td width="15%">AUTORIZA</td>
         <td width="8%">&nbsp;</td>
         <td width="15%">RETORNO</td>
         <td width="8%">&nbsp;</td>
         </tr>
         <tr>
         <td style="height: 30px;">&nbsp;</td>
         <td style="height: 30px;">&nbsp;{{$data->empleado_r}}</td>
         <td style="height: 30px;">&nbsp;</td>
         <td style="height: 30px;">&nbsp;{{$data->empleado_e}}</td>
         <td style="height: 30px;">&nbsp;</td>
         <td style="height: 30px;">&nbsp;</td>
         <td style="height: 30px;">&nbsp;</td>
         <td style="height: 30px;">&nbsp;{{$data->empleado_retorno}}</td>
         <td style="height: 30px;">&nbsp;</td>
         </tr>
         <tr>
         <td>&nbsp;</td>
         <td style="border-top: 1px solid;">NOMBRE Y FIRMA</td>
         <td>&nbsp;</td>
         <td style="border-top: 1px solid;">NOMBRE Y FIRMA</td>
         <td>&nbsp;</td>
         <td style="border-top: 1px solid;">NOMBRE Y FIRMA</td>
         <td>&nbsp;</td>
         <td style="border-top: 1px solid;">NOMBRE Y FIRMA</td>
         <td>&nbsp;</td>
         </tr>
         </table>



        </main>
    </body>
</html>
