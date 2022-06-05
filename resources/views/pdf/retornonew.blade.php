<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>SALIDA</title>
  <style>
    .Table
    {
        display: table;
        width: 100%;
    }
    .Row
    {
        display: table-row;
    }
    .Cell
    {
        display: table-cell;
        padding-left: 5px;
        padding-right: 5px;
    }
    .tab{
        font-size: 12px;
        font-family: Arial, Helvetica, sans-serif;
        text-align: center;
    }
    .taf{
        font-size: 12px;
        font-family: Arial, Helvetica, sans-serif;
        text-align: justify;
    }
    .tit{
        font-size: 12px;
        font-family: Arial, Helvetica, sans-serif;
        text-align: center;
        border-collapse: collapse;
        border: 1px solid;
        width: 100%;
        padding-top: 40px;
    }
    .ord{
        font-size: 10px;
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        border: 1px solid;
        width: 100%;
    }
    .cons{
        font-size: 12px;
        font-family: Arial, Helvetica, sans-serif;
        text-align: justify;
    }
    .pdb{
        font-size: 12px;
        font-family: Arial, Helvetica, sans-serif;
    }
  </style>
  <body>
        <div class="Table" style="padding-top: 0px">
                <div class="Row">
                        <div class="Cell">
                            <p style="padding-top:10px;"> <img src="img/conserflow.png"  width="250px" ></p>
                        </div>
                        <div class="Cell">
                          <table class="tit">
                            <tr>
                              <td style="border: 1px solid;">CÓDIGO</td>
                              <td style="border: 1px solid;">PAL-01/F-02</td>
                            </tr>
                            <tr>
                              <td style="border: 1px solid;">REVISIÓN</td>
                              <td style="border: 1px solid;">00</td>
                            </tr>
                            <tr>
                              <td style="border: 1px solid;">EMISIÓN</td>
                              <td style="border: 1px solid;">06.MAR.20</td>
                            </tr>
                          </table>
                        </div>
                </div>
                <div class="Row">
                        <div class="Cell">
                            <p class="cons" style="padding-top: -30px;"><b> CONSERFLOW, S.A. DE C.V. </b> <br>
                              <b>RFC:</b> CON19120226U2 <br>
                              <b>DIRECCIÓN:</b>Calle Del Mezquite Lote 5 Mza. 3, Col. <br>
                                    Santa Clara. Parque Industrial Tehuacan-Miahuatlán, <br>
                                    Santiago Miahuatlan. C.P. 75820. Puebla, México
                            </p>
                        </div>
                        <div class="Cell" style="padding-top: -20px;">
                          <table class="ord">
                            <tr>
                              <th style="font-size: 18px;
                                font-family: Arial, Helvetica, sans-serif;
                                text-align: center; background-color: #d2caca"><b>RETORNO MATERIAL</b> </th>
                            </tr>
                            <tr>
                              <td><b>FOLIO:</b>PENDIENTE </td>
                            </tr>
                            <tr>
                              <td><b>FECHA DE SALIDA:</b>

                              </td>
                            </tr>
                            <tr>
                            <td><b>PROYECTO:</b>
                            {{$proyecto->nombre_corto}}
                            </td>
                            </tr>
                            <tr>
                            <td><b>UBICACION::</b>PENDIENTE </td>
                            </tr>
                            <tr>
                            <td><b>TIPO DE SALIDA:</b>PENDIENTE </td>
                            </tr>
                            <tr>
                            <td><b>SOLICITANTE:</b>
                            {{$salidas->solicita}}
                            </td>
                            </tr>
                            <tr>
                            <td><b>CARGO:</b>
                            {{$solicita_datos->nombre}}
                            </td>
                            </tr>
                          </table>
                        </div>
                </div>
        </div>
                  <p width="100%" style="border-top: 1px solid #000;">&nbsp;</p>

              <table width="100%" class="tab">

                <tr>
                  <td width="5%"><b>Partida</b> </td>
                  <td width="10%"><b>Cantidad</b></td>
                  <td width="10%"><b>U.M</b></td>
                  <td width="55%"><b>Descripción</b></td>
                  <td width="55%"><b>Retorno</b></td>
                  <td width="55%"><b>Diferencia</b></td>
                </tr>
                @foreach ($partidas as $key => $pe)
                <tr>
                  <td>
                  {{$key  + 1}}
                  </td>
                  <td>
                  {{$pe->cantidad}}
                  </td>
                  <td>
                  {{$pe->unidad}}
                  </td>
                  <td><b>
                  {{$pe->descripcion}}
                  </b></td>
                </tr>
                @endforeach
              </table>

                <p width="100%" style="border-top: 1px solid #000;">&nbsp;</p>

                <p style="font-size: 16px;
                  font-family: Arial, Helvetica, sans-serif;
                  text-align: center;">
                  <b>Documento creado electrónicamente y aprobado por las siguientes personas:</b>
                </p>

              <div style="page-break-inside: avoid; width: 100%;">
              <table width="100%" style="text-align: center;">
                <tr>
                  <td class="pdb" width="30%"><b>Solicita:</b> </td>
                  <td width="5%">&nbsp;</td>
                  <td class="pdb" width="30%"><b>Entrega:</b></td>
                  <td width="5%">&nbsp;</td>
                  <td class="pdb" width="30%"><b>Autoriza:</b> </td>
                </tr>
                <tr>
                  <td></td>
                  <td>&nbsp;</td>
                  <td><img src="img/1.png"  width="170px" ></td>
                  <td>&nbsp;</td>
                  <td><img src="img/2.png"  width="170px" ></td>
                </tr>
                <tr>
                  <td class="pdb">
                  {{$salidas->solicita}}
                  </td>
                  <td>&nbsp;</td>
                  <td class="pdb">PENDIENTE</td>
                  <td>&nbsp;</td>
                  <td class="pdb">PENDIENTE</td>
                </tr>
                <tr>
                  <td style="border-top: 1px solid;
                            font-size: 12px;
                            font-family: Arial, Helvetica, sans-serif"><b>Nombre y Firma</b></td>
                  <td>&nbsp;</td>
                  <td style="border-top: 1px solid; font-size: 12px;
        font-family: Arial, Helvetica, sans-serif"><b>Nombre y Firma</b></td>
                  <td>&nbsp;</td>
                  <td style="border-top: 1px solid; font-size: 12px;
        font-family: Arial, Helvetica, sans-serif"><b>Nombre y Firma</b></td>
                </tr>
              </table>
              </div>
              <div style="page-break-inside: avoid; width: 100%;">
              <table width="100%" style="text-align: center;">
              <tr>
                  <td class="pdb" width="30%"><b>TRANSPORTA:</b> </td>
                  <td width="40%">&nbsp;</td>
                  <td class="pdb" width="30%"><b>RECIBE:</b> </td>
                </tr>
                <tr>
                  <td></td>
                  <td>&nbsp;</td>
                  <td></td>
                </tr>
                <tr>
                  <td class="pdb">
                  PENDIENTE
                  </td>
                  <td>&nbsp;</td>
                  <td class="pdb">PENDIENTE</td>
                </tr>
                <tr>
                  <td style="border-top: 1px solid;
                            font-size: 12px;
                            font-family: Arial, Helvetica, sans-serif"><b>Nombre y Firma</b></td>
                  <td>&nbsp;</td>
                  <td style="border-top: 1px solid; font-size: 12px;
        font-family: Arial, Helvetica, sans-serif"><b>Nombre y Firma</b></td>
                </tr>
              </table>
              </div>
  </body>
</html>
