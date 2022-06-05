<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>INVENTARIO DE VEHÍCULOS </title>
  <head>
  <style>
            /** Define the margins of your page **/
            @page {
                margin-top: 3cm;|
                margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 2cm;

            }

            header {

                position: fixed;
                top: -100px;
                left: 0px;
                right: 0px;
                height: 20px;
            }

            footer {
                position: fixed;
                bottom: -50px;
                left: 0px;
                right: 0px;
                height: 20px;
                color: #4472C4;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
            }
            bottom-right {
            content: counter(page) " of " counter(pages);
            }
            @bottom-right {
                    content: counter(page) " of " counter(pages);
            }

            .custom-footer-page-number:after {
                content: counter(page);
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
          <td rowspan="3" style="border: 1px solid; text-align: center;" width="55%"><b>INVENTARIO DE VEHÍCULOS</b> </td>
          <td style="border: 1px solid; text-align: center;"  width="10%">CÓDIGO</td>
          <td style="border: 1px solid; text-align: center;"  width="15%">PMN-02/F01</td>
          </tr>
          <tr>
          <td style="border: 1px solid; text-align: center;" >REVISIÓN</td>
          <td style="border: 1px solid; text-align: center;" >00</td>
          </tr>
          <tr>
          <td style="border: 1px solid; text-align: center;" >EMISIÓN</td>
          <td style="border: 1px solid; text-align: center;" >29.JUN.20</td>
          </tr>
        </table>

        </header>

        <footer>

        <table width="100%" style="b font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;">
            <tr>
            <td width="30%"> CONSERFLOW S.A. DE C.V. </td>
            <td  width="40%">&nbsp;</td>
            <td  width="30%" class="custom-footer-page-number">
                    PÁGINA:
                </td>
            </tr>
        </table>
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
    <main >

    <table  width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif; font-size: 8px;">
        <tr>
          <td rowspan="2" style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;">FECHA DE ADQUISICIÓN</div> </td>
          <td rowspan="2" style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;">MARCA</div> </td>
          <td rowspan="2" style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;">MODELO</div> </td>
          <td rowspan="2" style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;">AÑO</div> </td>
          <td rowspan="2" style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;">PLACAS</div> </td>
          <td rowspan="2" style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;">ESTADO</div> </td>
          <td rowspan="2" style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;">FECHA LIMITE TENENCIA VEHÍCULAR</div> </td>
          <td rowspan="2" style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;">COMPAÑÍA DE SEGUROS</div> </td>
          <td rowspan="2" style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;">NO. DE POLIZA DE SEGURO</div> </td>
          <td colspan="2" style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;">FECHA DE COBERTURA DEL SEGURO</div> </td>
          <td colspan="2" style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;">PERIODOS DE VERIFICACIÓN VEHÍCULAR</div> </td>
        </tr>
        <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" ><b>INICIO</b> </td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" ><b>TÉRMINO</b> </td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" ><b>1ER SEMESTRE</b> </td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" ><b>2DO SEMESTRE</b> </td>
        </tr>
        @foreach ($arreglo as $key => $value)
        <tr>
        <td style="border: 1px solid; text-align: center;">&nbsp;{{$value['fecha_ad']}}</td>
        <td style="border: 1px solid; text-align: center;">&nbsp;{{$value['unidad']->marca}}</td>
        <td style="border: 1px solid; text-align: center;">&nbsp;{{$value['unidad']->modelo}}</td>
        <td style="border: 1px solid; text-align: center;">&nbsp;{{$value['unidad']->anio}}</td>
        <td style="border: 1px solid; text-align: center;">&nbsp;{{$value['unidad']->placas}}</td>
        <td style="border: 1px solid; text-align: center;">&nbsp;{{$value['unidad']->estado}}</td>
        <td style="border: 1px solid; text-align: center;">&nbsp;</td>
        <td style="border: 1px solid; text-align: center;">&nbsp;{{$value['compania']}}</td>
        <td style="border: 1px solid; text-align: center;">&nbsp;{{$value['poliza']}}</td>
        <td style="border: 1px solid; text-align: center;">&nbsp;{{$value['inicio_p']}}</td>
        <td style="border: 1px solid; text-align: center;">&nbsp;{{$value['termino_p']}}</td>
        <td style="border: 1px solid; text-align: center;">&nbsp;{{$value['unidad']->primer_semestre}}</td>
        <td style="border: 1px solid; text-align: center;">&nbsp;{{$value['unidad']->segundo_semestre}}</td>
        </tr>
        @endforeach
    </table>



                </div>

    </main>
    </body>
</html>
