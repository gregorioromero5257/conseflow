<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>SOLICITUD DE FONDO FIJO</title>
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
                            <p style="padding-top:10px;"> <img src="img/conserflow.png"  width="250px" ></p>
                        </div>
                        <div class="Cell">
                          &nbsp;
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
                                text-align: center; background-color: #d2caca"><b>SOLICITUD DE FONDO FIJO</b> </th>
                            </tr>
                            <tr>
                              <td style="padding-left: 5px;"><b>NÚMERO:</b>{{$solicitud_viaticos->folio}}</td>
                            </tr>
                            <tr>
                              <td style="padding-left: 5px;"><b>FECHA DE SOLICITUD:</b>
                              {{$solicitud_viaticos->fecha_solicitud}}
                              </td>
                            </tr>
                            <tr>
                            <td style="padding-left: 5px;"><b>PROYECTO:</b>
                            {{$solicitud_viaticos->nombre_proyecto}}
                            </td>
                            </tr>
                          </table>
                        </div>
                </div>
        </div>
        <table width="100%" class="frp">
        <tr>
        <td width="80px" style="background-color: rgb(180, 180, 180);"><b>FECHA REQUERIDA DE PAGO</b> </td>
        <td width="30px" style="background-color: rgb(180, 180, 180);"><?=date_format(date_create($solicitud_viaticos->fecha_pago), 'd-m-Y')?> </td>
        <td width="120px">&nbsp;</td>
        </tr>
        </table>
        <br>
        <table class="frp" style="border-collapse: collapse;" width="100%">
        <tr>
        <td width="25%"><b>TRANSFERENCIA ELECTRONICA</b> </td>
        <td style="border-left: 2px solid; text-align: right;" width="20%">$ {{number_format($tet,2)}}</td>
        <td width="5%">&nbsp;</td>
        <td width="20%" style="border-right: 2px solid; text-align: left;"><b>BENEFICIARIO</b> </td>
        <td width="30%" style="text-align: center;">&nbsp;{{ $beneficiario_viatico->beneficiario_externo }}</td>
        </tr>
        <tr>
        <td><b>EFECTIVO</b> </td>
        <td style="border-left: 2px solid; text-align: right;" >$ {{number_format($et,2)}}</td>
        <td>&nbsp;</td>
        <td style="border-right: 2px solid; text-align: left;"><b >BANCO</b> </td>
        <td style="text-align: center;">{{ $beneficiario_viatico->banco_nombre}}</td>
        </tr>
        <tr>
        <td><b>TOTAL</b> </td>
        <td style="border-left: 2px solid; text-align: right;" >$ {{number_format($tt,2)}}</td>
        <td>&nbsp;</td>
        <td style="border-right: 2px solid; text-align: left;"><b>CUENTA</b> </td>
        <td style="text-align: center;">{{ $beneficiario_viatico->cuenta}}</td>
        </tr>
        <tr>
        <td colspan="3">&nbsp;</td>
        <td style="border-right: 2px solid; text-align: left;"><b>CLABE</b> </td>
        <td style="text-align: center;">{{ $beneficiario_viatico->clabe}}</td>
        </tr>
        <tr>
        <td colspan="3">&nbsp;</td>
        <td style="border-right: 2px solid; text-align: left;"><b>TARJETA</b> </td>
        <td style="text-align: center;"> {{ $beneficiario_viatico->tarjeta}}</td>
        </tr>
        <tr>
        <td colspan="3">&nbsp;</td>
        <td style="border-right: 2px solid; text-align: left;"><b>EDENRED</b> </td>
        <td style="text-align: center;"></td>
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
        @foreach ($detalle_viatico as $key => $value_)
        <tr>
          <td style="border-right: 2px solid; text-align:left;">{{$value_->catalogo_concepto_viaticos}}</td>
          <td style=" text-align:right; padding-right: 10px;">$ {{number_format($value_->transferencia_electronica,2)}}</td>
          <td style="border-right: 2px solid; border-left: 2px solid; text-align:right; padding-right: 10px;">$ {{number_format($value_->efectivo,2)}}</td>
          <td style=" text-align:right; padding-right: 10px;">$ {{number_format($value_->total,2)}}</td>
        </tr>
        @endforeach

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
      {{$solicitud_viaticos->nombre_elabora}}
      </td>
      <td>&nbsp;</td>
      <td>
      {{$solicitud_viaticos->nombre_revisa}}
      </td>
      <td>&nbsp;</td>
      <td>
      {{$solicitud_viaticos->nombre_autoriza}}
      </td>
    </tr>




    <tr>
      <td style="border-top: 1px solid;"><b>CONTROL DE DOCUMENTOS</b></td>
      <td>&nbsp;</td>
      <td style="border-top: 1px solid;"><b>DIRECTOR DE OPERACIONES</b></td>
      <td>&nbsp;</td>
      <td style="border-top: 1px solid;"><b>DIRECTOR DE OPERACIONES</b></td>
    </tr>
  </table>
  </body>
</html>
