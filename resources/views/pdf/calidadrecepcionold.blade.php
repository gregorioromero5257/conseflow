<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PCC-01_F-01 Recepción de materiales y equipos NR00</title>
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

        .tabe tr td {
            border: 1 px solid;
        }

        .tabe .tr-green {
            background-color: #b2de82;
        }

        .tab {
            border-collapse: collapse;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8px;
            text-align: center;
        }

        .divTable {
            width: 100%;
            display: table;
            border: 1px solid #666666;
        }

        .divRow {
            display: table-row;
        }

        .divCell {
            float: auto;
            /*fix for  buggy browsers*/
            display: table-column;
        }
    </style>

</head>

<body>
    <header>

        @if($tipo==1)
        <table width="100%" class="tabe">
            <tr>
                <td colspan="4" style="border: 1px solid; ">
                    <div style="color:#4472C4;"><b> CONSTRUCTORA Y SERVICIOS CALDERON TORRES S.A. DE C.V</b></div>
                </td>
            </tr>
            <tr>
                <td rowspan="3" width="20%"><img src="img/conserflow.png" width="120"></td>
                <td rowspan="3" class="borc"><b>RECEPCION DE MATERIALES Y EQUIPOS</b> </td>
                <td class="borc" width="15%">
                    <div> CÓDIGO</div>
                </td>
                <td class="borc" width="15%">
                    <div>PCC-01/F-01</div>
                </td>
            </tr>
            <tr>

                <td class="borc">
                    <div>REVISIÓN</div>
                </td>
                <td class="borc">
                    <div>00</div>
                </td>
            </tr>
            <tr>
                <td class="borc">
                    <div>EMISIÓN</div>
                </td>
                <td class="borc">
                    <div>19.JUN.17</div>
                </td>
            </tr>
        </table>

        @endif
        @if($tipo==2)

        <table width="100%" class="tabe">
            <tr>
                <td colspan="4" style="border: 1px solid; ">
                    <div style="color:#00B0F0;"><b> CONSTRUCTORA Y SERVICIOS CALDERON TORRES S.A. DE C.V.</b></div>
                </td>
            </tr>
            <tr>
                <td rowspan="3" width="20%"><img src="img/CSCT.png" width="120"></td>
                <td class="borc" width="50%">DOCUMENTO DEL SISTEMA DE GESTIÓN INTEGRAL</td>
                <td class="borc" width="15%">
                    <div style="color:#00B0F0;"> CÓDIGO</div>
                </td>
                <td class="borc" width="15%">
                    <div style="color:#00B0F0;">PCC-01/F-01</div>
                </td>
            </tr>
            <tr>
                <td rowspan="2" class="borc"><b>RECEPCION DE MATERIALES Y EQUIPOS</b> </td>
                <td class="borc">
                    <div style="color:#00B0F0;">REVISIÓN</div>
                </td>
                <td class="borc">
                    <div style="color:#00B0F0;">02</div>
                </td>
            </tr>
            <tr>
                <td class="borc">
                    <div style="color:#00B0F0;">EMISIÓN</div>
                </td>
                <td class="borc">
                    <div style="color:#00B0F0;">19.AGO.2020</div>
                </td>
            </tr>
        </table>
        @endif
    </header>

    <table width="100%" class="tabe">
        @if($tipo==2)
        <tr class="tr-green">
            @else
        <tr class="">
            @endif
            <td>Fecha</td>
            <td>Hora de entrega</td>
            <td>Conductor</td>
            <td>Unidad/Placas</td>
            <td>Proyecto</td>
            <td>Folio</td>
        </tr>
        <tr>
            <td>{{$recepcion->fecha}}</td>
            <td>{{$recepcion->hora}}</td>
            <td>{{$recepcion->persona_entrega}}</td>
            <td>{{$unidad->unidad}} {{$unidad->placas}}</td>
            <td>{{$recepcion->nombre_corto}}</td>
            <td>{{$recepcion->folio}}</td>
        </tr>
    </table>

    <footer>
        <p>
            {{$tipo==1?'
                CONSERFLOW S.A. DE C.V.':
                'CONSTRUCTORA Y SERVICIOS CALDERON TORRES S.A. DE C.V.'}}
        </p>
    </footer>

    <!-- Wrap the content of your PDF inside a main tag -->
    <main>

        <div>&nbsp;</div>
        <table width="100%" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; text-align: justify; border-color: solid;border:1 px solid">
            <tr>
                <td width="10%">Proveedor</td>
                <td style="border-bottom:1px solid" width="80%">{{$recepcion->proveedor}}</td>
            </tr>
        </table>
        <div height="1px;">&nbsp;</div>

        <div style="border: 1px solid;">
            <table width="100%" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; text-align: justify;">
                <tr>
                    <td width="90%">Descripcion del Material:</td>
                    <td width="10%">&nbsp;</td>
                </tr>
                @foreach($partidas as $i=>$partida)
                <tr>
                    <td colspan="2" style="border-bottom:1px solid">
                        {{$partida->descripcion}}
                    </td>
                </tr>
                <tr>
                    <td style="height: 1px;"> </td>
                </tr>
                @endforeach
            </table>
            <br>

            <div style="page-break-inside: avoid;">
                <table width="100%" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
                    <tr>
                        <td colspan="3">Documentación</td>
                        <td>&nbsp;</td>
                        <td colspan="3">Inspección</td>
                    </tr>
                    <tr>
                        <td witdh="30%">1. Se cuenta con orden de compra.</td>
                        <td width="4%">SI</td>
                        <td width="10%">&nbsp;</td>
                        <td width="1%">&nbsp;</td>
                        <td witdh="40%">1. El material coincide con lo solicitado</td>
                        <td width="5%">SI</td>
                        <td width="5%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td witdh="30%">2. Se recibe factura y/o remisión. </td>
                        <td width="4%">SI</td>
                        <td width="10%">&nbsp;</td>
                        <td width="1%">&nbsp;</td>
                        <td witdh="40%">2. Daños visibles en el material</td>
                        <td width="5%">NO</td>
                        <td width="5%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td witdh="30%">3. Se recibe con certificado de calidad. </td>
                        <td width="4%">SI</td>
                        <td width="10%">&nbsp;</td>
                        <td width="1%">&nbsp;</td>
                        <td witdh="40%">3. Condiciones de material aceptables. </td>
                        <td width="5%">SI</td>
                        <td width="5%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td witdh="30%">4. La recepción es total conforme O.C.</td>
                        <td width="4%">SI</td>
                        <td width="10%">&nbsp;</td>
                        <td width="1%">&nbsp;</td>
                        <td witdh="40%">4. El material es aceptado.</td>
                        <td width="5%">SI</td>
                        <td width="5%">&nbsp;</td>
                    </tr>
                </table>

                <div>&nbsp;</div>
                <table width="100%" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; text-align: justify;">
                    <tr>
                        <td width="22%">El material es resguardo en almacén:</td>
                        <td style="border-bottom:1px solid" width="80%">General</td>
                    </tr>
                </table>
                <table width="100%" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; text-align: justify;">
                    <tr>
                        <td width="10%">Observaciones</td>
                        <td style="border-bottom:1px solid" width="80%">Sin Observaciones</td>
                    </tr>
                    <tr>
                        <td width="10%"></td>
                    </tr>
                </table>
            </div>

        </div>
        <table width="100%" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; text-align: center;">
            <tr>
                <td colspan="5" height="10px;">&nbsp;</td>
            </tr>

            <tr>
                <td width="5%"></td>
                <td width="20%">&nbsp;</td>
                <td width="5%"></td>
                {{$entrega = 'temp/firmas/'.$recepcion->empleado_aprobo.'.png'}}
                @if(file_exists($entrega))
                <td width="20%">
                    <img src="temp/firmas/{{$recepcion->empleado_aprobo}}.png" width="150px" height="100px">
                </td>
                @else
                <td width="20%">&nbsp;</td>
                @endif
                <td width="5%"></td>
                <td width="20%">
                    <img src="temp/firmas/151.png" width="150px" height="100px">
                </td>

            </tr>

            <tr>
                <td width="5%"></td>
                <td width="20%">{{$recepcion->persona_entrega}}</td>
                <td width="5%"></td>
                <td width="20%">{{$recepcion->empleado_reviso_nombre}}</td>
                <td width="5%"></td>
                <td width="20%">JUAN JAIME MARTINEZ HERRERA</td>
                <td width="5%">&nbsp;</td>
            </tr>
            <tr>
                <td width="5%"></td>
                <td width="20%" style="border-top: 1px solid;">Nombre y Firma De Quien Entrega</td>
                <td width="5%"></td>
                <td width="20%" style="border-top: 1px solid;">Nombre y Firma De Quien Inspecciona</td>
                <td width="5%"></td>
                <td width="20%" style="border-top: 1px solid;">Nombre y Firma De Quien Recibe</td>
                <td width="5%">&nbsp;</td>
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