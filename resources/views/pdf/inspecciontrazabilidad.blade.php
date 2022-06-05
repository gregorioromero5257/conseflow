<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REGISTRO DE TRAZABILIDAD DE SOLDADURAS</title>
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

        .titulo {
            text-align: center;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 22px;
        }

        .letrat {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            text-align: center;
        }

        .letraa {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            text-align: right;
            font-weight: bold;
        }

        .letrab {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8px;
            text-align: center;
            border: 1px solid;
        }

        .cont {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8px;
            text-align: center;
            border: 1px solid;
        }

        .nom {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
        }
    </style>

</head>

<body>
    <header>

        <table width="100%" style="border-collapse: collapse; border: 1px solid; font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            text-align: center;">
            <tr>
                <td rowspan="4"><img src="img/conserflow.png" width="120"></td>
                <td rowspan="2" style="border: 1px solid; ">
                    <div style="color: #4472C4;"> CONSERFLOW S.A. DE C.V.</div>
                </td>
                <td colspan="2"><img src="img/conserflow.png" width="120"></td>
            </tr>
            <tr>
                <td style="border: 1px solid; text-align: center;">CÓDIGO</td>
                <td style="border: 1px solid; text-align: center;">PCA-02/F04</td>
            </tr>
            <tr>
                <td rowspan="2" style="border: 1px solid; text-align: center;"><b>REGISTRO DE TRAZABILIDAD DE SOLDADURAS</b> </td>
                <td style="border: 1px solid; text-align: center;">REVISIÓN</td>
                <td style="border: 1px solid; text-align: center;">00</td>
            </tr>
            <tr>
                <td style="border: 1px solid; text-align: center;">EMISIÓN</td>
                <td style="border: 1px solid; text-align: center;">29.JUN.20</td>
            </tr>
        </table>
        <br>
    </header>

    <footer>
        <p>CONSERFLOW S.A. DE C.V.</p>
    </footer>

    <!-- Wrap the content of your PDF inside a main tag -->
    <main>

        <div height="90px;">&nbsp;</div>
        <table style="" width="100%">
            <tr>
                <td class="letraa" width="20%">PROYECTO: </td>
                <td width="30%" style="border-bottom: 1px solid;" class="letrat">{{$inspec->nombre}}</td>
                <td width="5%">&nbsp;</td>
                <td class="letraa" width="10%">ELEMENTO:</td>
                <td width="30%" style="border-bottom: 1px solid;" class="letrat">{{$inspec->elemento}}</td>
                <td width="5%">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="6" height="10">&nbsp;</td>
            </tr>
            <tr>
                <td class="letraa">IDENTIFICACIÓN:</td>
                <td style="border-bottom: 1px solid;" class="letrat">{{$inspec->identificacion}}</td>
                <td width="5%">&nbsp;</td>
                <td class="letraa" width="10%">NÚMERO DE REPORTE::</td>
                <td width="30%" style="border-bottom: 1px solid;" class="letrat">{{$inspec->folio}}</td>
                <td width="5%">&nbsp;</td>
            </tr>
        </table>
        <br>
        <table style="border-collapse:collapse;" width="100%">
            <tr>
                <td rowspan="2" class="letrab" style="background-color: #D9D9D9;">ITEM</td>
                <td rowspan="2" class="letrab" style="background-color: #D9D9D9;">NÚMERO DE PLANO</td>
                <td rowspan="2" class="letrab" style="background-color: #D9D9D9;">WPS</td>
                <td rowspan="2" class="letrab" style="background-color: #D9D9D9;">PQR</td>
                <td rowspan="2" class="letrab" style="background-color: #D9D9D9;">WPQ</td>
                <td rowspan="2" class="letrab" style="background-color: #D9D9D9;">NOMBRE DEL SOLDADOR</td>
                <td rowspan="2" class="letrab" style="background-color: #D9D9D9;">CLAVE</td>
                <td rowspan="2" class="letrab" style="background-color: #D9D9D9;">N° DE CER. MAQUINA DE SOLDAR</td>
                <td rowspan="2" class="letrab" style="background-color: #D9D9D9;">PROCESO DE SOLDADURA</td>
                <td rowspan="2" class="letrab" style="background-color: #D9D9D9;">NUMERO DE JUNTA</td>
                <td rowspan="2" class="letrab" style="background-color: #D9D9D9;">DIÁMETRO</td>
                <td rowspan="2" class="letrab" style="background-color: #D9D9D9;">TIPO DE SOLDADURA</td>
                <td rowspan="2" class="letrab" style="background-color: #D9D9D9;">MATERIAL DE APORTE</td>
                <td rowspan="2" class="letrab" style="background-color: #D9D9D9;">ESPECIFICACIÓN MATERIALES</td>
                <td rowspan="2" class="letrab" style="background-color: #D9D9D9;">COLADA DE LOS MATERIALES</td>
                <td colspan="4" class="letrab" style="background-color: #FCE4D6;">VT (INSPECCIÓN VISUAL)</td>
                <td colspan="4" class="letrab" style="background-color: #DDEBF7;">PT ( LÍQUIDOS PENETRANTES) </td>
                <td colspan="4" class="letrab" style="background-color: #E2EFDA;">RT/ GT (INSPECCIÓN RADIOGRÁFICA / GAMAGRAFÍA )</td>

            </tr>
            <tr>
                <td class="letrab" style="background-color: #FCE4D6;">N.º DE REPORTE</td>
                <td class="letrab" style="background-color: #FCE4D6;">FECHA</td>
                <td class="letrab" style="background-color: #FCE4D6;">INSPECTOR</td>
                <td class="letrab" style="background-color: #FCE4D6;">ESTATUS</td>
                <td class="letrab" style="background-color: #DDEBF7;">N.º DE REPORTE</td>
                <td class="letrab" style="background-color: #DDEBF7;">FECHA</td>
                <td class="letrab" style="background-color: #DDEBF7;">INSPECTOR</td>
                <td class="letrab" style="background-color: #DDEBF7;">ESTATUS</td>
                <td class="letrab" style="background-color: #E2EFDA;">N.º DE REPORTE</td>
                <td class="letrab" style="background-color: #E2EFDA;">FECHA</td>
                <td class="letrab" style="background-color: #E2EFDA;">INSPECTOR</td>
                <td class="letrab" style="background-color:#E2EFDA;">ESTATUS</td>
            </tr>
            {{$i=0}}
            @for($i=0;$i<=30;$i++) @foreach($juntas as $junta) <tr>
                {{$i++}}
                <td class="cont"><b>{{$i}}</b> </td>
                <td class="cont">{{$junta->plano}}</td>
                <td class="cont">{{$junta->wps}}</td>
                <td class="cont">{{$junta->pqr}}</td>
                <td class="cont">{{$junta->wpq}}</td>
                <td class="cont">{{$junta->soldador}}</td>
                <td class="cont">{{$junta->soldador_clave}}</td>
                <td class="cont">{{$junta->cert_maquina}}</td>
                <td class="cont">{{$junta->proced_clave}}</td>
                <td class="cont">{{$junta->j_no}}</td>
                <td class="cont">{{$junta->diametro}}</td>
                <td class="cont">{{$junta->material_aporte}}</td>
                <td class="cont">{{$junta->tipo_preparacion}}</td>
                <td class="cont">{{$junta->espeificaciones_material}}</td>
                <td class="cont">
                    <b>{{$junta->m_1}}:</b>{{$junta->col_1}}<br>
                    <b>{{$junta->m_2}}:</b>{{$junta->col_2}}
                </td>
                <td class="cont"><b>{{$junta->vt_folio}}</b></td>
                <td class="cont"><b>{{$junta->vt_fecha}}</b></td>
                <td class="cont"><b>{{$junta->clave_inspector}}</b></td>
                <td class="cont">Aceptado</td>
                <td class="cont">N/A</td>
                <td class="cont">N/A</td>
                <td class="cont">N/A</td>
                <td class="cont">N/A</td>
                <td class="cont"><b>------------</b></td>
                <td class="cont">00/00/0000</td>
                <td class="cont">XXX</td>
                <td class="cont"><b>---</b> </td>
                </tr>
                @endforeach
                @endfor

        </table>
        <table width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
            <tr>
                <td style="background-color: #BFBFBF; text-align: center; border: 1px solid;"><b>Nomenclatura:</b> </td>
                <td rowspan="2">&nbsp;</td>
                @if(isset($array_nomens))
                @for($i=0;$i<=3;$i++) @if(isset($array_nomens[0][$i])) <td><b>{{$array_nomens[0][$i]->clave}}</b>&nbsp;{{$array_nomens[0][$i]->nombre}}</td>
                    @else
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    @endif
                    @endfor
                    @endif
            </tr>
            <tr>
                <td rowspan="2">&nbsp;</td>
                @if(isset($array_nomens))
                @for($i=0;$i<=3;$i++) @if(isset($array_nomens[1][$i])) <td><b>{{$array_nomens[1][$i]->clave}}</b>&nbsp;{{$array_nomens[1][$i]->nombre}}</td>
                    @else
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    @endif
                    @endfor
                    @endif
            </tr>
            <tr>
                <td rowspan="2">&nbsp;</td>
                @if(isset($array_nomens))
                @for($i=0;$i<=3;$i++) @if(isset($array_nomens[2][$i])) <td><b>{{$array_nomens[2][$i]->clave}}</b>&nbsp;{{$array_nomens[2][$i]->nombre}}</td>
                    @else
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    @endif
                    @endfor
                    @endif
            </tr>
        </table>

    </main>
    <script type="text/php">
        if (isset($pdf)) {
        $text = "PAGINA {PAGE_NUM} DE {PAGE_COUNT}";
        $size = 14;
        $font = $fontMetrics->getFont("Verdana");
        $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
        $x = ($pdf->get_width() - $width) / 1;
        $y = $pdf->get_height() - 35;
        $pdf->page_text($x, $y, $text, $font, $size);
    }
    else
    {
        echo "nope";
    }
</script>
</body>

</html>