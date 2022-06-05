<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>SALIDA</title>
  <style>
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
    <footer>
      <p style="color: #2F86DE;">CONSERFLOW S.A. DE C.V.</p>

    </footer>
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
                              <td style="border: 1px solid;">01.ABR.20</td>
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
                                text-align: center; background-color: #d2caca"><b>SALIDA DE ALMACEN</b> </th>
                            </tr>
                            <tr>
                              <td><b>FOLIO:</b>{{$salidas->folio}} </td>
                            </tr>
                            <tr>
                              <td><b>FECHA DE SALIDA:</b>
                                {{$salidas->fecha}}
                              </td>
                            </tr>
                            <tr>
                            <td><b>PROYECTO:</b>
                            {{$proyecto->nombre_corto}}
                            </td>
                            </tr>
                            <tr>
                            <td><b>UBICACION::</b>{{$salidas->ubicacion}} </td>
                            </tr>
                            <tr>
                            <td><b>TIPO DE SALIDA:</b>
                              @if($salidas->tiposalida_id == 1)
                              TALLER
                              @elseif($salidas->tiposalida_id == 2)
                              SITIO
                              @elseif($salidas->tiposalida_id == 3)
                              RESGUARDO
                              @endif
                             </td>
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
                </tr>
                @foreach ($partidas as $key => $pe)
                <tr>
                  <td>
                  {{$key + 1}}
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
                  <td class="pdb" width="30%"><b>Supervisor de proyecto:</b> </td>
                  <td width="5%">&nbsp;</td>
                  <td class="pdb" width="30%"><b>Entrega:</b></td>
                  <td width="5%">&nbsp;</td>
                  <td class="pdb" width="30%"><b>Autoriza:</b> </td>
                </tr>
                <tr>
                  <td>
                    @if($salidas->supervisor === 'SECUNDINO CHACON Y JIMENEZ')
                    <img src="img/scj.png"  width="150px">
                    @elseif($salidas->supervisor === 'RICARDO PONCE PEREZ')
                    <img src="img/rpp.png"  width="150px">
                    @elseif($salidas->supervisor === 'GONZALO ALEJANDRO MARTINEZ MARTINEZ')
                    <img src="img/am_.png"  width="150px">
                    @elseif($salidas->supervisor === 'MARÍA FERNANDA GONZÁLEZ OJEDA')
                    <img src="img/Fer.png"  width="100px">
                    @elseif($salidas->supervisor === 'GUSTAVO ALBERTO MORALES PAULINO')
                    <img src="img/Gusgri.png"  width="150px">
                    @endif

                  </td>
                  <td>&nbsp;</td>
                  <td>
                    @if($salidas->empleado_entrega_id === 3 && $salidas->fecha >= '2020-05-25')
                    <img src="img/3.png"  width="150px">
                    @elseif($salidas->empleado_entrega_id === 2 && $salidas->fecha >= '2020-04-27')
                    <img src="img/2.png"  width="150px" >
                    @elseif($salidas->empleado_entrega_id === 147)
                    <img src="img/1.png"  width="150px" >
                    @endif
                  </td>
                  <td>&nbsp;</td>
                  <td>
                    @if($salidas->fecha >= '2020-03-11')
                    <img src="img/1.png"  width="190px">
                    @endif
                  </td>
                </tr>
                <tr>
                  <td class="pdb">
                  {{$salidas->supervisor}}
                  </td>
                  <td>&nbsp;</td>
                  <td class="pdb">
                    @if($salidas->empleado_entrega_id === 3 && $salidas->fecha >= '2020-05-25')
                    JOSE RAUL VARGAS PACHECO
                    @elseif($salidas->empleado_entrega_id === 2 && $salidas->fecha >= '2020-04-27')
                    JULIETA MARTINEZ DEL VALLE
                    @endif
                  </td>
                  <td>&nbsp;</td>
                  <td class="pdb">
                    @if($salidas->fecha >= '2020-03-11')
                    JUAN JAIME MARTINEZ HERRERA
                    @endif
                    </td>
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
                  &nbsp;
                  </td>
                  <td>&nbsp;</td>
                  <td class="pdb">&nbsp;</td>
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
              <script type="text/php">
                  if (isset($pdf)) {
                      $text = "PAGINA {PAGE_NUM} DE {PAGE_COUNT}";
                      $size = 10;
                      $color = #2F86DE;
                      $font = $fontMetrics->getFont("Verdana");
                      $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                      $x = ($pdf->get_width() - $width) / 1;
                      $y = $pdf->get_height() - 35;
                      $pdf->page_text($x, $y, $text, $font, $size, $color);
                  }
              </script>
  </body>
</html>
