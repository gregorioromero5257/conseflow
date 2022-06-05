<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PMN-02_F05 PROGRAMA DE SERVICIOS VEHÍCULARES</title>

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
                content: counter(page) " of "counter(pages);
            }

            @bottom-right {
                content: counter(page) " of "counter(pages);
            }

            .custom-footer-page-number:after {
                content: counter(page);
            }

            .letter {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 16px;
            }

            .vert {
                font-size: 10px;
                white-space: pre-line;
                g-origin: 40% 40%;
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
                <th colspan="4" style="border: 1px solid; ">
                    <div style="color: #0099FF;"> {{$empresa==1?"CONSERFLOW - NO APROBADO":
                        "CONSTRUCTORA Y SERVICIOS CALDERON TORRES S.A. DE C.V."}}</div>
                </th>
            </tr>
            <tr>
                <td rowspan="3" width="20%"><img src="img/CSCT.png" width="120"></td>
                <td style="border: 1px solid; text-align: center;" width="55%">DOCUMENTO DEL SISTEMA DE GESTIÓN INTEGRAL</td>
                <td style="border: 1px solid; text-align: center;" width="10%">
                    <div style="color: #0099FF;"> CÓDIGO</div>
                </td>
                <td style="border: 1px solid; text-align: center;" width="15%">
                    <div style="color: #0099FF;">
                    {{$empresa==1?"NA":"PMN-02/F05"}}
                    </div>
                </td>
            </tr>
            <tr>
                <td rowspan="2" style="border: 1px solid; text-align: center;"><b>PROGRAMA DE SERVICIOS VEHÍCULARES</b> </td>
                <td style="border: 1px solid; text-align: center;">
                    <div style="color: #0099FF;"> REVISIÓN</div>
                </td>
                <td style="border: 1px solid; text-align: center;">
                    <div style="color: #0099FF;"> {{$empresa==1?"NA":"00"}}</div>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid; text-align: center;">
                    <div style="color: #0099FF;"> EMISIÓN</div>
                </td>
                <td style="border: 1px solid; text-align: center;">
                    <div style="color: #0099FF;"> {{$empresa==1?"NA":"00"}}</div>
                </td>
            </tr>
        </table>

    </header>

    <footer>

        <table width="100%" style="font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;">
            <tr>
                <td width="30%"> CONSERFLOW S.A. DE C.V. </td>
                <td width="40%">&nbsp;</td>
                <td width="30%" class="custom-footer-page-number">
                    PÁGINA:
                </td>
            </tr>
        </table>
    </footer>

    <!-- Wrap the content of your PDF inside a main tag -->
    <main>

        <table width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif;
                    font-size: 12px;">
            <tr>
                <td colspan="14">&nbsp;</td>
                <td style="background-color: #00B0F0; border: 1px solid; text-align:center;">
                    <div><b>AÑO</b> </div>
                </td>
                <td style="border: 1px solid; font-size: 10px; tex-align: center;">
                <center><b>{{$anio}}</b></center>
                </td>
            </tr>
            <tr>
                <td style="background-color: #0082B0; border: 1px solid; text-align:center;">
                    <div style="color:white;"><b>Vehiculo</b> </div>
                </td>
                <td style="background-color: #0082B0; border: 1px solid; text-align:center;">
                    <div style="color:white;"><b>Placas</b> </div>
                </td>
                <td style="background-color: #0082B0; border: 1px solid; text-align:center;">
                    <div style="color:white;"><b>Servicio</b> </div>
                </td>
                <td style="background-color: #B2DE82; border: 1px solid; text-align:center;"><b>Ene.</b></td>
                <td style="background-color: #B2DE82; border: 1px solid; text-align:center;"><b>Feb.</b> </td>
                <td style="background-color: #B2DE82; border: 1px solid; text-align:center;"><b>Mar.</b> </td>
                <td style="background-color: #B2DE82; border: 1px solid; text-align:center;"><b>Abr.</b> </td>
                <td style="background-color: #B2DE82; border: 1px solid; text-align:center;"><b>May.</b> </td>
                <td style="background-color: #B2DE82; border: 1px solid; text-align:center;"><b>Jun.</b> </td>
                <td style="background-color: #B2DE82; border: 1px solid; text-align:center;"><b>Jul.</b> </td>
                <td style="background-color: #B2DE82; border: 1px solid; text-align:center;"><b>Ago.</b> </td>
                <td style="background-color: #B2DE82; border: 1px solid; text-align:center;"><b>Sep.</b> </td>
                <td style="background-color: #B2DE82; border: 1px solid; text-align:center;"><b>Oct.</b> </td>
                <td style="background-color: #B2DE82; border: 1px solid; text-align:center;"><b>Nov.</b> </td>
                <td style="background-color: #B2DE82; border: 1px solid; text-align:center;"><b>Dic.</b> </td>
                <td style="background-color: #0082B0; border: 1px solid; text-align:center;">
                    <div style="color:white;"><b>Observaciones</b> </div>
                </td>
            </tr>
            @foreach($mttos as $mtto)
            <tr>
                <td style="border: 1px solid; text-align: center;">{{$mtto->vehiculo}}</td>
                <td style="border: 1px solid; text-align: center;">{{$mtto->placas}}</td>
                <td style="border: 1px solid; text-align: center;">{{$mtto->servicio}}</td>
                @for($i=1;$i<=12;$i++)
                <td style="border: 1px solid; text-align: center;">{{$mtto->mes==$i?"X":""}}</td>
                @endfor
                <td style="border: 1px solid; text-align: center;">{{$mtto->observaciones}}</td>
            </tr>
            @endforeach
        </table>
    </main>
</body>

</html>