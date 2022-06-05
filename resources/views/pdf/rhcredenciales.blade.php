<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CREDENCIALES</title>

    <head>
        <style>
            /** Define the margins of your page **/
            @page {
                margin-top: 0.5cm;
                margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 0cm;
            }
        </style>
    </head>

<body>
    <!-- Define header and footer blocks before your content -->
    <header>

        <div class="lol">

            <table width="100%" height="40%" style="font-family: Arial, Helvetica, sans-serif; text-align: center; border-collapse: collapse; background-color: #F2F2F2;">
                <tr>
                    <td width="24%" style="border-top: 1px solid; border-right: 1px solid; border-left: 1px solid;"><br><img src="img/conserflow.png" width="120"></td>
                    <td width="24%" style="border-top: 1px solid; border-right: 1px solid;"><br><img src="img/conserflow.png" width="120"></td>
                    <td width="2%" rowspan="8">&nbsp;</td>
                    <td width="24%" style="border-top: 1px solid; border-right: 1px solid; border-left: 1px solid;"><br><img src="img/conserflow.png" width="120"></td>
                    <td width="24%" style="border-top: 1px solid;  border-right: 1px solid;"><br><img src="img/conserflow.png" width="120"></td>
                </tr>
                <tr>

                    <td style="font-size: 10px; border-right: 1px solid; border-left: 1px solid; color: #002060; font-weight: bold;">Constructora y Servicios Calderon Torres S.A. de C.V.</td>

                    <td rowspan="2" style="text-align: center; font-size: 10px; border-right: 1px solid; color: #002060; font-weight: bold;"><b>MISION</b> <br><br> Somos una empresa que brinda soluciones integrales que satisfacen y superan las expectativas requeridas por nuestros clientes, a través de nuestra experiencia, procesos, recursos humanos y técnicos con un excelente tiempo de respuesta.
                    </td>

                    <td style="font-size: 10px; border-right: 1px solid; border-left: 1px solid; color: #002060; font-weight: bold;">Constructora y Servicios Calderon Torres S.A. de C.V.</td>

                    <td rowspan="2" style="text-align: center; font-size: 10px; border-right: 1px solid; color: #002060; font-weight: bold;"><b>MISION</b> <br><br> Somos una empresa que brinda soluciones integrales que satisfacen y superan las expectativas requeridas por nuestros clientes, a través de nuestra experiencia, procesos, recursos humanos y técnicos con un excelente tiempo de respuesta.
                    </td>
                    <br>
                </tr>
                <tr>
                    <td style="border-right: 1px solid; border-left: 1px solid;">
                        <img src='{{$fotos[0]}}' width="100" height="100">
                    </td>

                    <td style="border-right: 1px solid; border-left: 1px solid;">
                        @if(isset($fotos[1]))
                        <img src='{{$fotos[1]}}' width="100" height="100">
                        @endif
                    </td>

                </tr>
                <tr style="font-size: 10px; ">
                    <td style="border-right: 1px solid; border-left: 1px solid; color: #002060; font-weight: bold;">Nombre: <u>{{$empleados[0]->nombre}}</u>
                        <br>Cargo: <u>{{$empleados[0]->puesto}}</u>
                        <br>NSS: <u>{{$empleados[0]->nss_imss}}</u>
                    </td>

                    <td style="text-align: center; font-size: 10px; border-right: 1px solid; color: #002060; font-weight: bold;"><b>VISION</b><br>
                        <br>Brindar de manera continua y permanente soluciones tecnológicas en el área mecánica, instrumentación y automatización, manteniendo un alto nivel de calidad y confianza
                    </td>

                    <td style="border-right: 1px solid; border-left: 1px solid; color: #002060; font-weight: bold;">Nombre:
                        @if(isset($fotos[1]))
                        <u>{{$empleados[1]->nombre}}</u>
                        <br>Cargo: <u>{{$empleados[1]->puesto}}</u>
                        <br>NSS: <u>{{$empleados[1]->nss_imss}}</u>
                        @endif
                    </td>

                    <td style="text-align: center; font-size: 10px; border-right: 1px solid; color: #002060; font-weight: bold;"><b>VISION</b><br>
                        <br>Brindar de manera continua y permanente soluciones tecnológicas en el área mecánica, instrumentación y automatización, manteniendo un alto nivel de calidad y confianza
                    </td>

                </tr>
                <tr style="font-size:10px; ">
                    <td style="border-left: 1px solid;">&nbsp;</td>
                    <td rowspan="2" style="border-right: 1px solid; border-left: 1px solid; color: #002060; font-weight: bold; border-bottom: 1px solid; text-align:right;"><br> Av. Francisco I Madero No. 1000
                        <br> Col. María de la Piedad C.P. 96410
                        <br> Coatzacoalcos, Veracruz
                        <br> Tel. Oficina: 238-688-10-31
                    </td>
                    <td style="border-left: 1px solid;">&nbsp;</td>
                    <td rowspan="2" style="border-right: 1px solid; border-left: 1px solid; color: #002060; font-weight: bold; border-bottom: 1px solid; text-align:right;"><br> Av. Francisco I Madero No. 1000
                        <br> Col. María de la Piedad C.P. 96410
                        <br> Coatzacoalcos, Veracruz
                        <br> Tel. Oficina: 238-688-10-31
                    </td>
                </tr>

                <tr style="font-size: 10px; ">
                    <td style="border-right: 1px solid; border-left: 1px solid; color: #002060; font-weight: bold; text-decoration: overline; border-bottom: 1px solid;">
                        <ol></ol> Firma del trabajador
                    </td>
                    <td style="border-right: 1px solid; border-left: 1px solid; color: #002060; font-weight: bold; text-decoration: overline; border-bottom: 1px solid;">Firma del trabajador</td>
                </tr>
            </table>

            <table width="100%" height="40%" style="font-family: Arial, Helvetica, sans-serif; text-align: center; border-collapse: collapse; background-color: #F2F2F2;">
                <tr>
                    <td width="24%" style="border-top: 1px solid; border-right: 1px solid; border-left: 1px solid;"><br><img src="img/conserflow.png" width="120"></td>
                    <td width="24%" style="border-top: 1px solid; border-right: 1px solid;"><br><img src="img/conserflow.png" width="120"></td>
                    <td width="2%" rowspan="8">&nbsp;</td>
                    <td width="24%" style="border-top: 1px solid; border-right: 1px solid; border-left: 1px solid;"><br><img src="img/conserflow.png" width="120"></td>
                    <td width="24%" style="border-top: 1px solid;  border-right: 1px solid;"><br><img src="img/conserflow.png" width="120"></td>
                </tr>
                <tr>

                    <td style="font-size: 10px; border-right: 1px solid; border-left: 1px solid; color: #002060; font-weight: bold;">Constructora y Servicios Calderon Torres S.A. de C.V.</td>

                    <td rowspan="2" style="text-align: center; font-size: 10px; border-right: 1px solid; color: #002060; font-weight: bold;"><b>MISION</b> <br><br> Somos una empresa que brinda soluciones integrales que satisfacen y superan las expectativas requeridas por nuestros clientes, a través de nuestra experiencia, procesos, recursos humanos y técnicos con un excelente tiempo de respuesta.
                    </td>

                    <td style="font-size: 10px; border-right: 1px solid; border-left: 1px solid; color: #002060; font-weight: bold;">Constructora y Servicios Calderon Torres S.A. de C.V.</td>

                    <td rowspan="2" style="text-align: center; font-size: 10px; border-right: 1px solid; color: #002060; font-weight: bold;"><b>MISION</b> <br><br> Somos una empresa que brinda soluciones integrales que satisfacen y superan las expectativas requeridas por nuestros clientes, a través de nuestra experiencia, procesos, recursos humanos y técnicos con un excelente tiempo de respuesta.
                    </td>
                    <br>
                </tr>
                <tr>
                    <td style="border-right: 1px solid; border-left: 1px solid;">
                        @if(isset($fotos[2]))
                        <img src='{{$fotos[2]}}' width="100" height="100">
                        @endif
                    </td>

                    <td style="border-right: 1px solid; border-left: 1px solid;">
                        @if(isset($fotos[3]))
                        <img src='{{$fotos[3]}}' width="100" height="100">
                        @endif
                    </td>

                </tr>
                <tr style="font-size: 10px; ">
                    <td style="border-right: 1px solid; border-left: 1px solid; color: #002060; font-weight: bold;">
                        @if(isset($fotos[2]))
                        Nombre: <u>{{$empleados[2]->nombre}}</u>
                        <br>Cargo: <u>{{$empleados[2]->puesto}}</u>
                        <br>NSS: <u>{{$empleados[2]->nss_imss}}</u>
                        @endif
                    </td>

                    <td style="text-align: center; font-size: 10px; border-right: 1px solid; color: #002060; font-weight: bold;"><b>VISION</b><br>
                        <br>Brindar de manera continua y permanente soluciones tecnológicas en el área mecánica, instrumentación y automatización, manteniendo un alto nivel de calidad y confianza
                    </td>

                    <td style="border-right: 1px solid; border-left: 1px solid; color: #002060; font-weight: bold;">
                        @if(isset($fotos[3]))
                        Nombre: <u>{{$empleados[3]->nombre}}</u>
                        <br>Cargo: <u>{{$empleados[3]->puesto}}</u>
                        <br>NSS: <u>{{$empleados[3]->nss_imss}}</u>
                        @endif
                    </td>

                    <td style="text-align: center; font-size: 10px; border-right: 1px solid; color: #002060; font-weight: bold;"><b>VISION</b><br>
                        <br>Brindar de manera continua y permanente soluciones tecnológicas en el área mecánica, instrumentación y automatización, manteniendo un alto nivel de calidad y confianza
                    </td>

                </tr>
                <tr style="font-size:10px; ">
                    <td style="border-left: 1px solid;">&nbsp;</td>
                    <td rowspan="2" style="border-right: 1px solid; border-left: 1px solid; color: #002060; font-weight: bold; border-bottom: 1px solid; text-align:right;"><br> Av. Francisco I Madero No. 1000
                        <br> Col. María de la Piedad C.P. 96410
                        <br> Coatzacoalcos, Veracruz
                        <br> Tel. Oficina: 238-688-10-31
                    </td>
                    <td style="border-left: 1px solid;">&nbsp;</td>
                    <td rowspan="2" style="border-right: 1px solid; border-left: 1px solid; color: #002060; font-weight: bold; border-bottom: 1px solid; text-align:right;"><br> Av. Francisco I Madero No. 1000
                        <br> Col. María de la Piedad C.P. 96410
                        <br> Coatzacoalcos, Veracruz
                        <br> Tel. Oficina: 238-688-10-31
                    </td>
                </tr>

                <tr style="font-size: 10px; ">
                    <td style="border-right: 1px solid; border-left: 1px solid; color: #002060; font-weight: bold; text-decoration: overline; border-bottom: 1px solid;">
                        <ol></ol> Firma del trabajador
                    </td>
                    <td style="border-right: 1px solid; border-left: 1px solid; color: #002060; font-weight: bold; text-decoration: overline; border-bottom: 1px solid;">Firma del trabajador</td>
                </tr>
            </table>
        </div>
    </header>
</body>

</html>