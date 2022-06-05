<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REPORTE DE INSPECCION DE MATERIALES Y EQUIPOS</title>
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

        .tab2 {
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            border-collapse: collapse;
            border: 1px solid;

        }

        .tab3 {
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
            padding-left: 700px;
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
            font-size: 8px;
            text-align: center;
        }

        .vert {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: black;
            margin-left: -0.5rem;
            text-align: center;
            g-origin: 10% 10%;
            -webkit-transform: rotate(270deg);
            -moz-transform: rotate(270deg);
            -ms-transform: rotate(270deg);
            -o-transform: rotate(270deg);
            transform: rotate(270deg);
            position: absolute;
            z-index: 99;
        }

        .pdb {
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
        }

        .borc {
            border: 1px solid;
        }
    </style>

</head>

<body>
    <header>

        <table width="100%" class="tabe">
            <tr>
                <td colspan="4" style="border: 1px solid; ">
                    <div style="color:#4472C4;"><b> CONSERFLOW S.A. DE C.V.</b></div>
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
                    <div>30.JUN.17</div>
                </td>
            </tr>
        </table>

    </header>

    <footer>
        <p>CONSERFLOW S.A. DE C.V.</p>
    </footer>

    <!-- Wrap the content of your PDF inside a main tag -->
    <main>
        <table width="100%" class="tab2">
            <tr>
                <td style="border: 1px solid; background-color: #CFC7C5   " width="5%"> <b>Proyecto</b> </td>
                <td style="border: 1px solid;" width="10%">{{$rime->nombre_corto}}</td>
                <td rowspan="2" style="border: 1px solid; background-color: #CFC7C5" width="10%"><b>Proveedor</b> </td>
                <td rowspan="2" style="border: 1px solid;" width="20%">{{$rime->razon_social}}</td>
                <td style="border: 1px solid; background-color: #CFC7C5" width="10%"><b>Factura</b> </td>
                <td style="border: 1px solid;" width="15%">{{$rime->factura}}</td>
                <td style="border: 1px solid; background-color: #CFC7C5" width="10%"><b>Fecha</b> </td>
                <td style="border: 1px solid;" width="20%">{{$fecha}}</td>
            </tr>
            <tr>
                <td style="border: 1px solid; background-color: #CFC7C5"><b>No. de Proyecto</b> </td>
                <td style="border: 1px solid;">{{$rime->no_proyecto}}</td>
                <td style="border: 1px solid; background-color: #CFC7C5"><b>Orden de Compra</b> </td>
                <td style="border: 1px solid;">{{$rime->folio_oc}}</td>
                <td style="border: 1px solid; background-color: #CFC7C5"><b>Número de Reporte</b> </td>
                <td style="border: 1px solid;">{{$rime->folio}}</td>
            </tr>
        </table>
        <table class="tab3">
            <tr>
                <td><b>A ACEPTADO R RECHAZADO N/A NO APLICA</b>
                </td>
            </tr>
        </table>
        <br>


        <span class="vert" style="top: 180px; left: 580px;"> Mecánico</span>
        <span class="vert" style="top: 180px; left: 610px;"> Estructural</span>
        <span class="vert" style="top: 180px; left: 620px;"> Rec. Anticorrosivo</span>
        <span class="vert" style="top: 180px; left: 670px;"> Electrico</span>
        <span class="vert" style="top: 180px; left: 680px;"> Instrumentacion</span>
        <span class="vert" style="top: 180px; left: 720px;"> Coincide OC con <br> factura</span>
        <span class="vert" style="top: 180px; left: 780px;"> Estado Fisico</span>
        <span class="vert" style="top: 175px; left: 820px;"> Dimensiones de <br> acuerdo a <br> especificación</span>
        <span class="vert" style="top: 175px; left: 855px;"> Cumple <br> especificaciones y/o <br> normativa</span>
        <span class="vert" style="top: 180px; left: 895px;"> Nacionalidad cumple <br> con lo solicitado</span>
        <span class="vert" style="top: 180px; left: 940px;"> Certificado de Calidad</span>
        <span class="vert" style="top: 180px; left: 990px;"> Certificado cumple con <br> especificaciones</span>
        <span class="vert" style="top: 175px; left: 1060px;"> Coinciden coladas del <br> material con <br> el certificado</span>
        <span class="vert" style="top: 170px; left: 1140px;"> Verificacion de <br> Documentos y <br> caracteristicas <br> Técnicas</span>
        <span class="vert" style="top: 180px; left: 1170px;"> Se reciben certificados <br> de calibración</span>
        <span class="vert" style="top: 175px; left: 1220px;"> Se recibieron manuales <br> de instalación y/o <br> mantenimiento</span>
        <span class="vert" style="top: 175px; left: 1270px;"> Se recibieron partes de <br> repuesto</span>
        <span class="vert" style="top: 180px; left: 1320px;"> Se recibieron kits de <br> instalación</span>
        <span class="vert" style="top: 175px; left: 1365px;"> El rango de los <br> instrumentos coincide <br> con lo solicitado</span>
        <table width="100%;" style="margin-right: 1rem; font-size:12px; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; text-align: center; z-index:2;">
            <tr>
                <td colspan="7" style="border: 1px solid; background-color: #2F86DE">
                    <b style="color: white;">DATOS DEL MATERIAL / EQUIPOS / INSTRUMENTOS</b>
                </td>
                <td colspan="5" style="border: 1px solid; background-color: #2F86DE">
                    <b style="color: white;">TIPO DE MATERIAL</b>
                </td>
                <td colspan="9" style="border: 1px solid; background-color: #2F86DE">
                    <b style="color: white;">INSPECCION DE MATERIALES</b>
                </td>
                <td colspan="5" style="border: 1px solid; background-color: #2F86DE; ">
                    <b style="color: white;">INSPECCION DE EQUIPOS Y/O INSTRUMENTOS</b>
                </td>
                <td rowspan="2" style="border: 1px solid; background-color: #2F86DE;">
                    <b style="color: white;">OBSERVACIONES</b>
                </td>
            </tr>
            <tr style="background-color: #CFC7C5;color:back">
                <td width="2%" height="150px;" style="border: 1px solid;"><span> No</span></td>
                <td width="2%" style="border: 1px solid;"><span> CANT.</span></td>
                <td width="13%" style="border: 1px solid;"><span> DESCRIPCION DEL MATERIAL</span></td>
                <td width="5%" style="border: 1px solid;"><span> MARCA</span></td>
                <td width="5%" style="border: 1px solid;"><span> MODELO</span></td>
                <td width="5%" style="border: 1px solid;"><span> NO. SERIE</span></td>
                <td width="5%" style="border: 1px solid;"><span> NO. CERTIFICADO</span></td>
                <td width="2%" style="border: 1px solid;">&nbsp;</td>
                <td width="2%" style="border: 1px solid;">&nbsp;</td>
                <td width="2%" style="border: 1px solid;">&nbsp;</td>
                <td width="2%" style="border: 1px solid;">&nbsp;</td>
                <td width="2%" style="border: 1px solid;">&nbsp;</td>
                <td width="3%" style="border: 1px solid;">&nbsp;</td>
                <td width="3%" style="border: 1px solid;">&nbsp;</td>
                <td width="4%" style="border: 1px solid;">&nbsp;</td>
                <td width="3%" style="border: 1px solid;">&nbsp;</td>
                <td width="3%" style="border: 1px solid;">&nbsp;</td>
                <td width="3%" style="border: 1px solid;">&nbsp;</td>
                <td width="4%" style="border: 1px solid;">&nbsp;</td>
                <td width="4%" style="border: 1px solid;">&nbsp;</td>
                <td width="4%" style="border: 1px solid;">&nbsp;</td>
                <td width="3%" style="border: 1px solid;">&nbsp;</td>
                <td width="3%" style="border: 1px solid;">&nbsp;</td>
                <td width="3%" style="border: 1px solid;">&nbsp;</td>
                <td width="3%" style="border: 1px solid;">&nbsp;</td>
                <td width="6%" style="border: 1px solid;">&nbsp;</td>
            </tr>

            @foreach ($ic as $key_ic => $value_)
            <tr>
                <td width="1%" height="150px;" style="border: 1px solid;">{{$key_ic + 1}}</td>
                <td width="1%" style="border: 1px solid;">{{$value_->cantidad}}</td>
                <td width="13%" style="border: 1px solid;">{{$value_->descripcion}}</td>
                <td width="5%" style="border: 1px solid;">{{$value_->marca}}</td>
                <td width="5%" style="border: 1px solid;">{{$value_->modelo}}</td>
                <td width="5%" style="border: 1px solid;">{{$value_->no_serie}}
                <td width="5%" style="border: 1px solid;">{{$value_->no_certificado}}</td>
                <td width="2%" style="border: 1px solid;">{{$value_->tipo_mecanico}}</td>
                <td width="2%" style="border: 1px solid;">{{$value_->tipo_estructura}}</td>
                <td width="2%" style="border: 1px solid;">{{$value_->tipo_anticorrosivo}}</td>
                <td width="2%" style="border: 1px solid;">{{$value_->tipo_electrico}}</td>
                <td width="2%" style="border: 1px solid;">{{$value_->tipo_instrumentacion}}</td>
                <td width="3%" style="border: 1px solid;">{{$value_->ins_mat_factura}}</td>
                <td width="3%" style="border: 1px solid;">{{$value_->ins_mat_edo_fisico}}</td>
                <td width="3%" style="border: 1px solid;">{{$value_->ins_mat_dimesiones}}</td>
                <td width="3%" style="border: 1px solid;">{{$value_->ins_mat_normativa}}</td>
                <td width="3%" style="border: 1px solid;">{{$value_->ins_mat_nacionalidad}}</td>
                <td width="3%" style="border: 1px solid;">{{$value_->ins_mat_cetificado}}</td>
                <td width="4%" style="border: 1px solid;">{{$value_->ins_mat_cet_cumpl}}</td>
                <td width="4%" style="border: 1px solid;">{{$value_->ins_mat_coladas}}</td>
                <td width="4%" style="border: 1px solid;">{{$value_->ins_mat_documentos}}</td>
                <td width="3%" style="border: 1px solid;">{{$value_->ins_equi_recibe_cert}}</td>
                <td width="3%" style="border: 1px solid;">{{$value_->ins_equi_recibe_manual}}</td>
                <td width="3%" style="border: 1px solid;">{{$value_->ins_equi_recibe_repuesto}}</td>
                <td width="3%" style="border: 1px solid;">{{$value_->ins_equi_recibe_kit}}</td>
                <td width="6%" style="border: 1px solid;">{{$value_->ins_equi_rango}}</td>
                <td width="6%" style="border: 1px solid;">{{$value_->observaciones}}</td>
            </tr>
            @endforeach

        </table>
        <div height="10px;">&nbsp;</div>
        <div style="page-break-inside: avoid; width: 100%;">
            <table width="100%" class="pdb">
                <tr>
                    <td style="text-align: left;" width="20%"><b>REVISÓ</b> </td>
                    <td width="20%">&nbsp;</td>
                    <td style="text-align: left;" width="20%"><b>ENTERADO</b></td>
                    <td width="20%">&nbsp;</td>
                    <td style="text-align: left;" width="20%"><b>APROBÓ</b> </td>
                </tr>

                <tr>
                    <td class="pdb">
                        {{$rime->reviso}}
                    </td>
                    <td>&nbsp;</td>
                    <td class="pdb">
                        {{$rime->enterado}}
                    </td>
                    <td>&nbsp;</td>
                    <td class="pdb">
                        {{$rime->aprobo}}
                    </td>
                </tr>
                <tr>
                    <td style="border-top: 1px solid;"><b>INSPECTOR CONTROL CALIDAD</b></td>
                    <td>&nbsp;</td>
                    <td style="border-top: 1px solid;"><b>SUPERVISOR DE PROYECTO</b></td>
                    <td>&nbsp;</td>
                    <td style="border-top: 1px solid;"><b>COORDINADOR DE CONTROL DE CALIDAD</b></td>
                </tr>
                <tr>
                    <td><b>NOMBRE Y FIRMA</b></td>
                    <td>&nbsp;</td>
                    <td><b>NOMBRE Y FIRMA</b></td>
                    <td>&nbsp;</td>
                    <td><b>NOMBRE Y FIRMA</b></td>
                </tr>
            </table>
        </div>

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