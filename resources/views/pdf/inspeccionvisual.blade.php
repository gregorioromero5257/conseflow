<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PCC-OP-02 REGISTRO DE EXAMINACIÓN VISUAL DE SOLDADURAS </title>

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
                top: -80px;
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

            .pagenum:before {
                content: counter(page);
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
                    <div style="color: #4472C4;"> CONSERFLOW S.A. DE C.V.</div>
                </th>
            </tr>
            <tr>
                <td rowspan="3" width="20%"><img src="img/conserflow.png" width="120"></td>
                <td rowspan="3" style="border: 1px solid; text-align: center;" width="55%"><b>REGISTRO DE EXAMINACIÓN VISUAL DE SOLDADURAS </b> </td>
                <td style="border: 1px solid; text-align: center;" width="10%">CÓDIGO</td>
                <td style="border: 1px solid; text-align: center;" width="15%">PCC-OP-02</td>
            </tr>
            <tr>
                <td style="border: 1px solid; text-align: center;">REVISIÓN</td>
                <td style="border: 1px solid; text-align: center;">00</td>
            </tr>
            <tr>
                <td style="border: 1px solid; text-align: center;">EMISIÓN</td>
                <td style="border: 1px solid; text-align: center;">01.ABR.20</td>
            </tr>
        </table>

    </header>

    <footer>

        <table width="100%" style="b font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;">
            <tr>
                <td width="30%"> CONSERFLOW S.A. DE C.V. </td>
                <td width="40%">&nbsp;</td>
                <td width="30%" width="30%" class="custom-footer-page-number1">
                    REV.1
                    <br>
                    Página <span class="pagenum"></span>
                </td>
            </tr>
        </table>
    </footer>

    <!-- Wrap the content of your PDF inside a main tag -->
    <main style="border: 1px solid;">

        <div style="height: 30px;">&nbsp;</div>
        <table width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
            <tr>
                <td style="border: 1px solid; background-color: #BFBFBF;" width="10%">Proyecto:</td>
                <td colspan="4" width="90%" style="border: 1px solid;">{{$inspeccion->proyecto_nombre}}</td>
            </tr>
            <tr>
                <td colspan="2" style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="20%">Lugar de Fabricacion</td>
                <td style="border: 1px solid; text-align: center;" width="50%">{{$inspeccion->lugar}}</td>
                <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="10%">N°. de Reporte:</td>
                <td style="border: 1px solid;" width="20%">{{$inspeccion->folio}}</td>
            </tr>
        </table>
        <div style="height: 10px;">&nbsp;</div>

        <table width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
            <tr>
                <td colspan="4" style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><b>DOCUMENTO APLICABLE</b> </td>
            </tr>
            <tr>
                <td width="15%">Procedimiento WPS:</td>
                <td style="border-bottom: 1px solid;" width="35%">{{$inspeccion->wps}}</td>
                <td width="15%">Procedimiento PQR:</td>
                <td style="border-bottom: 1px solid;" width="35%">{{$inspeccion->pqr}}</td>
            </tr>
            <tr>
                <td>Número de Plano:</td>
                <td style="border-bottom: 1px solid;">{{$inspeccion->plano}}</td>
                <td>Elemento</td>
                <td style="border-bottom: 1px solid;">{{$inspeccion->sistema_nombre}}</td>
            </tr>
        </table>
        <div style="height: 10px;">&nbsp;</div>

        <table width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif; font-size: 12px; text-align: center;">
            <tr>
                <td colspan="9" style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><b>ESPÉCIMEN</b> </td>
            </tr>
            <tr>
                <td colspan="9" style="height: 10px;">&nbsp;</td>
            </tr>
            <tr>
                <td width="20%" style="text-align: right; padding-right: 10px;">Recipiente a Presión</td>
                <td width="2%" style="border: 2px solid;">{{$inspeccion->especimen_presion?'X':''}}</td>
                <td width="20%" style="text-align: right; padding-right: 10px;">Tubería</td>
                <td width="2%" style="border: 2px solid;">{{$inspeccion->especimen_tuberia?'X':''}}</td>
                <td width="20%" style="text-align: right; padding-right: 10px;">Placa</td>
                <td width="2%" style="border: 2px solid;">{{$inspeccion->especimen_placa?'X':''}}</td>
                <td width="20%" style="text-align: right; padding-right: 10px;">Perfil</td>
                <td width="2%" style="border: 2px solid;">{{$inspeccion->especimen_perfil?'X':''}}</td>
                <td width="12%">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: right; padding-right: 10px;">Especificación de Materiales:</td>
                <td colspan="8" style="border-bottom: 1px solid;">{{$inspeccion->espeificaciones_material}}</td>
            </tr>
        </table>
        <div style="height: 10px;">&nbsp;</div>
        <table width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif; font-size: 12px; text-align: center;">
            <tr>
                <td colspan="9" style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><b>DATOS DE EXAMINACIÓN</b> </td>
            </tr>
            <tr>
                <td colspan="9" style="height: 10px;">&nbsp;</td>
            </tr>
            <tr>
                <td style="border: 1px solid; background-color: #BFBFBF; text-align: center; font-size: 12px;"><b>Distancia al Objeto</b> </td>
                <td style="border: 1px solid; background-color: #BFBFBF; text-align: center; font-size: 12px;"><b> Angulo aproximado a la superficie</b></td>
                <td colspan="7" style="border: 1px solid; background-color: #BFBFBF; text-align: center; font-size: 12px;"><b>Acabado Superficial</b> </td>
            </tr>
            <tr>
                <td rowspan="3" style="border: 1px solid; text-align: center;">{{$inspeccion->examinacion_distancia}}</td>
                <td rowspan="3" style="border: 1px solid; text-align: center;">{{$inspeccion->examinacion_angulo}}</td>
                <td colspan="7" style="border-right: 1px solid;">&nbsp;</td>
            </tr>
            <tr>

                <td style="text-align: right; padding-right: 10px;">Burdo</td>
                <td width="2%" height="10px" style="border: 2px solid;">{{$inspeccion->acabado_burdo?'X':''}}</td>
                <td style="text-align: right; padding-right: 10px;">Maquinado</td>
                <td width="2%" height="10px" style="border: 2px solid;">{{$inspeccion->acabado_maquinado?'X':''}}</td>
                <td style="text-align: right; padding-right: 10px;">Esmerilado</td>
                <td width="2%" height="10px" style="border: 2px solid;">{{$inspeccion->acabado_esmerilado?'X':''}}</td>
                <td width="12%" style="border-right: 1px solid;">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="7" style="height: 10px; border-right: 1px solid; border-bottom: 1px solid;">&nbsp;</td>
            </tr>
        </table>
        <table width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
            <tr>
                <td colspan="3" width="25%" height="20px" style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><b>Técnica de Examinación Visual</b> </td>
                <td colspan="3" width="25%" style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><b>Iluminación</b> </td>
                <td colspan="3" width="25%" style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><b>Accesorios y Herramientas</b> </td>
                <td colspan="3" width="25%" style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><b>Método de Limpieza de la Superficie</b> </td>
            </tr>
        </table>
        <table width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif; font-size: 14px; text-align: center;">
            <tr>
                <td colspan="12" style="height: 10px; ">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: right; padding-right: 10px; ">Directa</td>
                <td style="border: 2px solid;">{{$inspeccion->examinacion_directa?'X':''}}</td>
                <td style="">&nbsp;</td>
                <td style="text-align: right; padding-right: 10px;">Natural</td>
                <td style="border: 2px solid;">{{$inspeccion->iluminacion_natural?'X':''}}</td>
                <td style="">&nbsp;</td>
                <td style="text-align: right; padding-right: 10px;">Camara Fotográfica</td>
                <td style="border: 2px solid;">{{$inspeccion->accesorios_camara?'X':''}}</td>
                <td style="">&nbsp;</td>
                <td style="text-align: right; padding-right: 10px;">Limpieza Mecánica</td>
                <td style="border: 2px solid;">{{$inspeccion->limpieza_meca?'X':''}}</td>
                <td style="">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="12" style="height: 10px;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: right; padding-right: 10px;">Remota</td>
                <td style="border: 2px solid;">{{$inspeccion->examinacion_remota?'X':''}}</td>
                <td style="">&nbsp;</td>
                <td style="text-align: right; padding-right: 10px;">Lámpara de Mano</td>
                <td style="border: 2px solid;">{{$inspeccion->iluminacion_mano?'X':''}}</td>
                <td style="">&nbsp;</td>
                <td style="text-align: right; padding-right: 10px;">Bridge Cam</td>
                <td style="border: 2px solid;">{{$inspeccion->accesorios_bridge?'X':''}}</td>
                <td style="">&nbsp;</td>
                <td style="text-align: right; padding-right: 10px;">Limpieza con Solvente</td>
                <td style="border: 2px solid;">{{$inspeccion->limpieza_solvente?'X':''}}</td>
                <td style="">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="12" style="height: 10px;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: right; padding-right: 10px;">Translucida</td>
                <td style="border: 2px solid;">{{$inspeccion->examinacion_translucida?'X':''}}</td>
                <td style="">&nbsp;</td>
                <td style="text-align: right; padding-right: 10px;">Lámpara Incandescente</td>
                <td style="border: 2px solid;">{{$inspeccion->iluminacion_incan?'X':''}}</td>
                <td style="">&nbsp;</td>
                <td style="text-align: right; padding-right: 10px;">Seven Fillet</td>
                <td style="border: 2px solid;">{{$inspeccion->accesorios_seven?'X':''}}</td>
                <td style="">&nbsp;</td>
                <td style="text-align: right; padding-right: 10px;">Maquinado</td>
                <td style="border: 2px solid;">{{$inspeccion->limpieza_maquina?'X':''}}</td>
                <td style="">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="12" style="height: 10px;">&nbsp;</td>
            </tr>
            <tr>
                <td style="">&nbsp;</td>
                <td style="">&nbsp;</td>
                <td style="">&nbsp;</td>
                <td style="text-align: right; padding-right: 10px;">Reflector</td>
                <td style="border: 2px solid;">{{$inspeccion->iluminacion_reflector?'X':''}}</td>
                <td style="">&nbsp;</td>
                <td style="text-align: right; padding-right: 10px;">Hi/Lo</td>
                <td style="border: 2px solid;">{{$inspeccion->accesorios_hilo?'X':''}}</td>
                <td style="">&nbsp;</td>
                <td style="text-align: right; padding-right: 10px;">&nbsp;</td>
                <td style="">&nbsp;</td>
                <td style="">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="12" style="height: 10px;">&nbsp;</td>
            </tr>
        </table>
        <table width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
            <tr>
                <td height="20px" style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><b>Soldadura</b> </td>
                <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><b>Diámetro</b> </td>
                <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><b>Espesor</b> </td>
                <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><b>Indicación</b> </td>
                <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><b>Evaluación</b> </td>
                <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><b>Colada de los Materiales</b> </td>
                <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><b>ID del Soldador</b></td>
                <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><b>Servicio / Hoja</b> </td>
                <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><b>Fecha de Inspeccion</b> </td>
            </tr>

            @foreach($juntas as $junta)

            <tr>
                <td style="border: 1px solid; font-size: 10px; tex-align: center;">{{$junta->nombre}}</td>
                <td style="border: 1px solid; font-size: 10px; tex-align: center;">{{$junta->diametro}}</td>
                <td style="border: 1px solid; font-size: 10px; tex-align: center;">{{$junta->espesor}}</td>
                <td style="border: 1px solid; font-size: 10px; tex-align: center;">S.I.R.</td>
                <td style="border: 1px solid; font-size: 10px; tex-align: center;">ACEPTADO</td>
                <td style="border: 1px solid; font-size: 10px; tex-align: center;">
                    {{$junta->nombre_corto1}}: {{$junta->colada1}} <br>
                    {{$junta->nombre_corto2}}: {{$junta->colada2}}
                </td>
                <td style="border: 1px solid; font-size: 10px; tex-align: center;">{{$junta->clave}}</td>
                <td style="border: 1px solid; font-size: 10px; tex-align: center;">{{$junta->plano}}/Hoja {{$junta->hoja}}</td>
                <td style="border: 1px solid; font-size: 10px; tex-align: center;">{{$junta->fecha_inspeccion}}</td>
            </tr>
            @endforeach




        </table>
        <div style="page-break-inside: avoid;">
            <table width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
            <tr>
                    <td style="background-color: #BFBFBF; text-align: center; border: 1px solid;"><b>Nomenclatura:</b> </td>
                    <td rowspan="2">&nbsp;</td>
                    @if(isset($array_nomens))
                    @for($i=0;$i<=3;$i++)
                    @if(isset($array_nomens[0][$i]))
                    <td><b>{{$array_nomens[0][$i]->clave}}</b>&nbsp;{{$array_nomens[0][$i]->nombre}}</td>
                    @else
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    @endif
                    @endfor
                    @endif
                    <td style="background-color: #BFBFBF; text-align: center; border: 1px solid;"><b>Criterio de Aceptación</b></td>
                </tr>
                <tr>
                    <td rowspan="2">&nbsp;</td>
                    @if(isset($array_nomens))
                    @for($i=0;$i<=3;$i++)
                    @if(isset($array_nomens[1][$i]))
                    <td><b>{{$array_nomens[1][$i]->clave}}</b>&nbsp;{{$array_nomens[1][$i]->nombre}}</td>
                    @else
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    @endif
                    @endfor
                    @endif
                    <td style="text-align: center; border: 1px solid;">{{$inspeccion->criterio_aceptacion}}</td>
                </tr>
                <tr>
                    <td rowspan="2">&nbsp;</td>
                    @if(isset($array_nomens))
                    @for($i=0;$i<=3;$i++)
                    @if(isset($array_nomens[2][$i]))
                    <td><b>{{$array_nomens[2][$i]->clave}}</b>&nbsp;{{$array_nomens[2][$i]->nombre}}</td>
                    @else
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    @endif
                    @endfor
                    @endif
                    <td style="text-align: center; border: 1px solid;"> {{$inspeccion->tabla_aceptacion}}</td>
                </tr>
            </table>
        </div>
        <div style="page-break-inside: avoid;">
            <table width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif; font-size: 12px; text-align:center;">
                <tr>
                    <td style="border: 1px solid;">CONSERFLOW S.A. DE C.V. <br><br>
                        {{$inspeccion->empleado_inspecciona}}<br>
                        INSPECCIONÓ
                    </td>
                    <td style="border: 1px solid;">CONSERFLOW S.A. DE C.V. <br><br>
                        {{$inspeccion->empleado_supervisa}}<br>
                        SUPERVISÓ
                    </td>
                    <td style="border: 1px solid;">
                        {{$inspeccion->aprobado}} <br><br>
                        APROBÓ
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid;">NOMBRE Y FIRMA</td>
                    <td style="border: 1px solid;">NOMBRE Y FIRMA</td>
                    <td style="border: 1px solid;">NOMBRE Y FIRMA</td>
                </tr>
            </table>
        </div>

    </main>
</body>

</html>