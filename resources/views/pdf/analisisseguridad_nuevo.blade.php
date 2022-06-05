<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PSE-01_F-08 Análisis de seguridad en el trabajo (AST)</title>
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
            height: 30px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
        }

        .titulo {
            text-align: center;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            /* border-top: 1px solid #00FFFF; */
        }

        .hed {
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            border: 2px solid;
        }

        .lp {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            text-align: center;
            border: 1px solid;
        }

        .letrat {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            text-align: center;
            border: 1px solid;
        }

        .gris {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8px;
            text-align: center;
            background-color: #B2BABB;
            border: 1px solid;
        }

        .grid {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8px;
            text-align: justify;
            background-color: #B2BABB;
            border: 1px solid;
        }

        .lc {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            text-align: center;
            color: white;
            background-color: #2F86DE;
        }

        .borc {
            border: 1px solid;
            text-align: center;
        }

        .borl {
            border: 1px solid;
            text-align: left;
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
            font-size: 14px;
            text-align: center;
        }
    </style>

</head>

<body>
    <header>

        <table width="100%" class="tabe">
            <tr>
                <td colspan="4" style="border: 1px solid; ">
                    <div style="color: #2F86DE;"><b>CONSERFLOW S.A. DE C.V.</b></div>
                </td>
            </tr>
            <tr>
                <td rowspan="3" width="20%"><img src="img/conserflow.png" width="120"></td>
                <td rowspan="3" class="borc"><b>ANÁLISIS DE SEGURIDAD EN EL TRABAJO (AST)</b> </td>
                <td class=borc width="10%"> CÓDIGO</td>
                <td class="borc" width="10%">PSE-01/F-08</td>
            </tr>
            <tr>
                <td class="borc">REVISIÓN</td>
                <td class="borc">00</td>
            </tr>
            <tr>
                <td class="borc">EMISIÓN</td>
                <td class="borc">01.ABR.20</td>
            </tr>
        </table>

    </header>

    <footer>
        <p style="color: #2F86DE;">CONSERFLOW S.A. DE C.V.</p>
    </footer>

    <!-- Wrap the content of your PDF inside a main tag -->
    <main>

        <div style="page-break-after: always;">

            <table style="border-collapse:collapse; border: 2px solid;" width="100%">
                <tr>
                    <td colspan="4" class="lc">DATOS GENERALES</td>
                </tr>
                <tr>
                    <td class="gris" width="20%">Ubicación del trabajo</td>
                    <td width="40%" class="letrat">&nbsp;{{$analisis->ubicacion}}</td>
                    <td class="gris" width="20%">Número de permiso</td>
                    <td width="20%" class="letrat">&nbsp;{{$analisis->no_permiso}}</td>
                </tr>
                <tr>
                    <td class="gris" width="20%">Descripción del trabajo</td>
                    <td class="letrat" width="60%">&nbsp;{{$analisis->descripcion}}</td>
                    <td width="10%" class="gris">Fecha</td>
                    <td width="10%" class="letrat">{{$fechafinal}}</td>
                </tr>
            </table>

            <table style="border-collapse:collapse; border: 2px solid;" width="100%">
                <tr>
                    <td colspan="6" class="lc">EVALUACIÓN DE RIESGOS</td>
                </tr>
                <tr>
                    <td class="gris" width="3%">No.</td>
                    <td class="grid" width="20%">Actividad para la ejecucion del trabajo</td>
                    <td class="gris" width="20%">Riesgos</td>
                    <td class="gris" width="27%">Medidas de control</td>
                    <td class="gris" width="15%">Supervisor de Área</td>
                    <td class="gris" width="15%">Firma</td>
                </tr>
                <p>
                </p>
                @foreach ($lol as $i=> $partida)

                <tr>
                    <td class="letrat">&nbsp;{{$i+1}}</td>
                    <td class="letrat">&nbsp;{{$partida["secuencia"]}}</td>
                    <td class="letrat">&nbsp;{{$partida["riesgos"]}}</td>
                    <td class="letrat">&nbsp;{{$partida["recomendaciones"]}}</td>
                    <td class="letrat">&nbsp;{{$partida["supervisor"]}}</td>
                    <td class="letrat">&nbsp;</td>
                </tr>
                @endforeach


            </table>

            <table style="border-collapse:collapse; border: 2px solid;" width="100%">
                <tr>
                    <td colspan="2" class="lc" style="border-bottom: 2px solid;">RESPONSABLES DE AUTORIZACIÓN</td>
                </tr>
                <tr>
                    <td class="lp">{{$analisis->operaciones_empleado}}
                        <br> Responsable del Área
                        <br><b>Operaciones</b>
                    </td>
                    <td class="lp">{{$analisis->shso_empleado}}
                        <br> Responsable del Área
                        <br><b>SHSO</b>
                    </td>
                </tr>
            </table>
        </div>

        <div>


            <table style="border-collapse:collapse; border: 2px solid;" width="100%">
                <tr>
                    <td colspan="4" class="lc">PARTICIPANTES</td>
                </tr>
                <tr>
                    <td class="gris" width="5%">No.</td>
                    <td class="gris" width="25%">Nombre</td>
                    <td class="gris" width="20%">Puesto</td>
                    <td class="gris" width="20%">Firma</td>
                </tr>
                @foreach ($participantes as $key_p => $value_p)
                <tr>
                    <td class="gris" height="30px">{{$key_p + 1}}-.</td>
                    <td class="letrat">&nbsp;{{$value_p->empleado}}</td>
                    <td class="letrat">&nbsp;{{$value_p->puesto}}</td>
                    <td class="letrat">&nbsp;</td>
                </tr>
                @endforeach

    </main>
    <script type="text/php">
        if (isset($pdf)) {
        $text = "PAGINA {PAGE_NUM} DE {PAGE_COUNT}";
        $size = 12;
        $color = #2F86DE;
        $font = $fontMetrics->getFont("Verdana");
        $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
        $x = ($pdf->get_width() - $width) / 1;
        $y = $pdf->get_height() - 35;
        $pdf->page_text($x, $y, $text, $font, $size);
    }
</script>
</body>

</html>