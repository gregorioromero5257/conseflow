<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>SOLICITUD DE CAJA CHICA</title>
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
    .frp{
        font-size: 12px;
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
    }
    .iyl{
        font-size: 12px;
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        text-align: right;
    }
    .ddv{
        font-size: 14px;
        font-family: Arial, Helvetica, sans-serif;
        text-align: center;
    }
    .tab{
        font-size: 12px;
        font-family: Arial, Helvetica, sans-serif;
        text-align: center;
        border-collapse: collapse;
    }
  </style>
  <body>
        <div class="Table" style="padding-top: 0px">
                <div class="Row">
                        <div class="Cell">
                          @if($solicitud->empresa == 1)
                            <p style="padding-top:10px;"> <img src="img/conserflow.png"  width="250px" ></p>
                          @elseif($solicitud->empresa == 2)
                          <p style="padding-top:10px;"> <img src="img/CSCT.png"  width="250px" ></p>
                          @endif
                        </div>
                        <div class="Cell">
                          &nbsp;
                        </div>
                </div>
                <div class="Row">
                        <div class="Cell">
                          @if($solicitud->empresa == 1)
                            <p class="cons" style="padding-top: -30px;"><b> CONSERFLOW, S.A. DE C.V. </b> <br>
                              <b>RFC:</b> CON19120226U2 <br>
                              <b>DIRECCIÓN:</b>Calle Del Mezquite Lote 5 Mza. 3, Col. <br>
                                    Santa Clara. Parque Industrial Tehuacan-Miahuatlán, <br>
                                    Santiago Miahuatlan. C.P. 75820. Puebla, México
                            </p>
                            @elseif($solicitud->empresa == 2)
                            <p class="cons" style="padding-top: -30px;"><b> CONSTRUCTORA Y SERVICIOS CALDERON TORRES S.A. DE C.V. </b> <br>
                              <b>RFC:</b> CSC050609LF7 <br>
                              <b>DIRECCIÓN:</b>
                              Dirección: Av. Fco. I. Madero #1000, Col. Maria de la piedad,<br> Coatzacoalcos, Ver.
                            </p>
                            @endif
                        </div>
                        <div class="Cell" style="padding-top: -20px;">
                          <table class="ord">
                            <tr>
                              <th style="font-size: 18px;
                                font-family: Arial, Helvetica, sans-serif;
                                text-align: center; background-color: #d2caca"><b>SOLICITUD DE CAJA CHICA</b> </th>
                            </tr>
                            <tr>
                              <td style="padding-left: 5px;"><b>NÚMERO:</b>{{$solicitud->folio}}</td>
                            </tr>
                            <tr>
                              <td style="padding-left: 5px;"><b>FECHA DE SOLICITUD:</b>
                              {{$solicitud->fecha}}
                              </td>
                            </tr>
                            <tr>
                            <td style="padding-left: 5px;"><b>PROYECTO:</b>

                            </td>
                            </tr>
                          </table>
                        </div>
                </div>
        </div>

        <br>
        <table class="frp" style="border-collapse: collapse;" width="100%">
        <tr>
        <td width="25%"><b>TRANSFERENCIA ELECTRONICA</b> </td>
        <td style="border-left: 2px solid; text-align: right;" width="20%">$ {{number_format($solicitud->tranferencia,2)}}</td>
        <td width="5%">&nbsp;</td>
        <td width="20%" style="border-right: 2px solid; text-align: left;"><b>BENEFICIARIO</b> </td>
        <td width="30%" style="text-align: center;">&nbsp;{{ $solicitud->beneficiario }}</td>
        </tr>
        <tr>
        <td><b>EFECTIVO</b> </td>
        <td style="border-left: 2px solid; text-align: right;" >$ {{number_format($solicitud->efectivo,2)}}</td>
        <td>&nbsp;</td>
        <td style="border-right: 2px solid; text-align: left;"><b >BANCO</b> </td>
        <td style="text-align: center;">{{ $solicitud->nombre_banco == '' ? $solicitud->banco : $solicitud->nombre_banco}}</td>
        </tr>
        <tr>
        <td><b>TOTAL</b> </td>
        <td style="border-left: 2px solid; text-align: right;" >$ {{number_format($tt,2)}}</td>
        <td>&nbsp;</td>
        <td style="border-right: 2px solid; text-align: left;"><b>CUENTA</b> </td>
        <td style="text-align: center;">{{ $solicitud->cuentauno}}</td>
        </tr>
        <tr>
        <td colspan="3">&nbsp;</td>
        <td style="border-right: 2px solid; text-align: left;"><b>CLABE</b> </td>
        <td style="text-align: center;">{{ $solicitud->claveuno}}</td>
        </tr>
        <tr>
        <td colspan="3">&nbsp;</td>
        <td style="border-right: 2px solid; text-align: left;"><b>TARJETA</b> </td>
        <td style="text-align: center;"> {{ $solicitud->clavecuentatarjetauno}}</td>
        </tr>
        </table>
        <p style="background-color: rgb(180, 180, 180);" class="ddv"><b>DETALLE DE USO</b></p>

        <table class="tab" width="100%">
        <tr>
        <td style="border-right: 2px solid; border-bottom: 2px solid; " width="40%">CONCEPTO</td>
        <td style="border-right: 2px solid; border-bottom: 2px solid; " width="20%">TRANSFERENCIA ELECTRONICA</td>
        <td style="border-right: 2px solid; border-bottom: 2px solid; " width="20%">EFECTIVO</td>
        <td style="border-bottom: 2px solid; " width="20%">TOTAL</td>
        </tr>
        <tr>
          <td style="border-right: 2px solid; text-align:left;">{{$solicitud->conceptos}}</td>
          <td style=" text-align:right; padding-right: 10px;">$ {{number_format($solicitud->tranferencia,2)}}</td>
          <td style="border-right: 2px solid; border-left: 2px solid; text-align:right; padding-right: 10px;">$ {{number_format($solicitud->efectivo,2)}}</td>
          <td style=" text-align:right; padding-right: 10px;">$ {{number_format($tt,2)}}</td>
        </tr>

        </table>


        <div style="page-break-inside: avoid; width: 100%; bottom: 0px;">
        <p style="font-size: 16px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;">
    <b>Documento creado electrónicamente y aprobado por las siguientes personas:</b>
  </p>


  <table width="100%" style="text-align: center;" class="pdb">
    <tr>
      <td  width="30%"><b>Eláboro:</b> </td>
      <td width="10%">&nbsp;</td>
      <td  width="35%"><b>Revisó:</b></td>
      <td width="10%">&nbsp;</td>
      <td  width="35%"><b>Autorizó:</b> </td>
    </tr>

    <tr>
      <td>
      {{$solicitud->beneficiario}}
      </td>
      <td>&nbsp;</td>
      <td>
      {{$solicitud->empleado_revisa}}
      </td>
      <td>&nbsp;</td>
      <td>
      {{$solicitud->empleado_autoriza}}
      </td>
    </tr>




    <tr>
      <td style="border-top: 1px solid;"><b>--</b></td>
      <td>&nbsp;</td>
      <td style="border-top: 1px solid;"><b>--</b></td>
      <td>&nbsp;</td>
      <td style="border-top: 1px solid;"><b>--</b></td>
    </tr>
  </table>
  </body>
</html>
