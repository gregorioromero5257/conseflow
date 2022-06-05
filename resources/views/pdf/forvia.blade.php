<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>SOLICITUD DE VIATICOS</title>
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
                          <table class="tit">
                            <tr>
                              <td style="border:  1px solid;">CÓDIGO</td>
                              <td style="border:  1px solid;">PTE-/F-01</td>
                            </tr>
                            <tr>
                              <td style="border: 1px solid;">REVISIÓN</td>
                              <td style="border: 1px solid;">00</td>
                            </tr>
                            <tr>
                              <td style="border: 1px solid;">EMISIÓN</td>
                              <td style="border: 1px solid;">07.SEP.20</td>
                            </tr>
                          </table>
                        </div>
                </div>
                <div class="Row">
                        <div class="Cell">
                            <p class="cons" style="padding-top: -30px;"><b> CONSERFLOW, S.A. DE C.V. </b> <br>
                              <b>RFC:</b> CON19120226U2 <br>
                              <b>DIRECCIÓN:</b>&nbsp;Calle Del Mezquite Lote 5 Mza. 3, Col. <br>
                                    Santa Clara. Parque Industrial Tehuacan-Miahuatlán, <br>
                                    Santiago Miahuatlan. C.P. 75820. Puebla, México
                            </p>
                        </div>
                        <div class="Cell" style="padding-top: -20px;">
                          <table class="ord">
                            <tr>
                              <th style="font-size: 18px;
                                font-family: Arial, Helvetica, sans-serif;
                                text-align: center; background-color: #d2caca"><b>SOLICITUD DE VIATICOS</b> </th>
                            </tr>
                            <tr>
                              <td style="padding-left: 5px;"><b>NÚMERO:</b>&nbsp;{{$solicitud_viaticos->folio}}</td>
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
                            <tr>
                            <td style="padding-left: 5px;"><b>TIPO DE SOLICITUD:</b>
                              {{$solicitud_viaticos->tipo == 1 ? 'REEMBOLSO' : 'VIATICOS'}}
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
        <td width="30%" style="text-align: center;">{{$beneficiario_viatico->empleado_beneficiario_id > 0 ? $beneficiario_viatico->nombre_beneficiario : $beneficiario_viatico->beneficiario_externo }}</td>
        </tr>
        <tr>
        <td><b>EFECTIVO</b> </td>
        <td style="border-left: 2px solid; text-align: right;" >$ {{number_format($et,2)}}</td>
        <td>&nbsp;</td>
        <td style="border-right: 2px solid; text-align: left;"><b >BANCO</b> </td>
        <td style="text-align: center;">{{$beneficiario_viatico->banco_nombre }}</td>
        </tr>
        <tr>
        <td><b>TOTAL</b> </td>
        <td style="border-left: 2px solid; text-align: right;" >$ {{number_format($tt,2)}}</td>
        <td>&nbsp;</td>
        <td style="border-right: 2px solid; text-align: left;"><b>CUENTA</b> </td>
        <td style="text-align: center;">{{$beneficiario_viatico->cuenta }}</td>
        </tr>
        <tr>
        <td colspan="3">&nbsp;</td>
        <td style="border-right: 2px solid; text-align: left;"><b>CLABE</b> </td>
        <td style="text-align: center;">{{$beneficiario_viatico->clabe }}</td>
        </tr>
        <tr>
        <td colspan="3">&nbsp;</td>
        <td style="border-right: 2px solid; text-align: left;"><b>TARJETA</b> </td>
        <td style="text-align: center;">{{$beneficiario_viatico->banco_nombre === 'EDENRED CONSERFLOW' ? '' : $beneficiario_viatico->tarjeta }}</td>
        </tr>
        <tr>
        <td colspan="3">&nbsp;</td>
        <td style="border-right: 2px solid; text-align: left;"><b>EDENRED</b> </td>
        <td style="text-align: center;">{{$beneficiario_viatico->banco_nombre === 'EDENRED CONSERFLOW' ? $beneficiario_viatico->tarjeta : '' }}</td>
        </tr>
        </table>
        <p style="background-color: rgb(180, 180, 180);" class="ddv"><b>DETALLE DE VIATICOS</b></p>

        <table class="tab" width="100%">
        <tr>
        <td style="border-right: 2px solid; border-bottom: 2px solid;" width="40%">CONCEPTO</td>
        <td style="border-right: 2px solid; border-bottom: 2px solid; " width="20%">TRANSFERENCIA ELECTRONICA</td>
        <td style="border-right: 2px solid; border-bottom: 2px solid; " width="20%">EFECTIVO</td>
        <td style="border-bottom: 2px solid;" width="20%">TOTAL</td>
        </tr>
        <tr>
        <td style="border-right: 2px solid; text-align:left;">Combustible</td>
        <td style=" text-align:right; padding-right: 10px;">
          @if($detalle_viatico[0]->transferencia_electronica != 0)
          $ {{number_format($detalle_viatico[0]->transferencia_electronica,2)}}
          @endif </td>
        <td style="border-right: 2px solid; border-left: 2px solid; text-align:right; padding-right: 10px;">@if($detalle_viatico[0]->efectivo != 0)$ {{number_format($detalle_viatico[0]->efectivo,2)}}@endif </td>
        <td style=" text-align:right; padding-right: 10px;">@if($detalle_viatico[0]->total != 0) $ {{number_format($detalle_viatico[0]->total,2)}} @endif</td>
        </tr>
        <tr>
        <td style="border-right: 2px solid; text-align:left;">Peaje</td>
        <td style=" text-align:right; padding-right: 10px;">
          @if($detalle_viatico[1]->transferencia_electronica != 0)
          $ {{number_format($detalle_viatico[1]->transferencia_electronica,2)}}
          @endif </td>
        <td style="border-right: 2px solid; border-left: 2px solid; text-align:right; padding-right: 10px;">@if($detalle_viatico[1]->efectivo != 0)$ {{number_format($detalle_viatico[1]->efectivo,2)}}@endif </td>
        <td style=" text-align:right; padding-right: 10px;">@if($detalle_viatico[1]->total != 0) $ {{number_format($detalle_viatico[1]->total,2)}} @endif</td>
        </tr>
        <tr>
        <td style="border-right: 2px solid; text-align:left;">Hospedaje</td>
        <td style=" text-align:right; padding-right: 10px;">
          @if($detalle_viatico[2]->transferencia_electronica != 0)
          $ {{number_format($detalle_viatico[2]->transferencia_electronica,2)}}
          @endif </td>
        <td style="border-right: 2px solid; border-left: 2px solid; text-align:right; padding-right: 10px;">@if($detalle_viatico[2]->efectivo != 0)$ {{number_format($detalle_viatico[2]->efectivo,2)}}@endif </td>
        <td style=" text-align:right; padding-right: 10px;">@if($detalle_viatico[2]->total != 0) $ {{number_format($detalle_viatico[2]->total,2)}} @endif</td>
        </tr>

        @if (count($detalle_viatico) > 5)
        <tr>
        <td style="border-right: 2px solid; text-align:left;">Boletos de Avion</td>
        <td style=" text-align:right; padding-right: 10px;">
          @if($detalle_viatico[5]->transferencia_electronica != 0)
          $ {{number_format($detalle_viatico[5]->transferencia_electronica,2)}}
          @endif </td>
        <td style="border-right: 2px solid; border-left: 2px solid; text-align:right; padding-right: 10px;">@if($detalle_viatico[5]->efectivo != 0)$ {{number_format($detalle_viatico[5]->efectivo,2)}}@endif </td>
        <td style=" text-align:right; padding-right: 10px;">@if($detalle_viatico[5]->total != 0) $ {{number_format($detalle_viatico[5]->total,2)}} @endif</td>
        </tr>
        <tr>
        <td style="border-right: 2px solid; text-align:left;">Boletos de Autobús</td>
        <td style=" text-align:right; padding-right: 10px;">
          @if($detalle_viatico[6]->transferencia_electronica != 0)
          $ {{number_format($detalle_viatico[6]->transferencia_electronica,2)}}
          @endif </td>
        <td style="border-right: 2px solid; border-left: 2px solid; text-align:right; padding-right: 10px;">@if($detalle_viatico[6]->efectivo != 0)$ {{number_format($detalle_viatico[6]->efectivo,2)}}@endif </td>
        <td style=" text-align:right; padding-right: 10px;">@if($detalle_viatico[6]->total != 0) $ {{number_format($detalle_viatico[6]->total,2)}} @endif</td>
        </tr>
        <tr>
        <td style="border-right: 2px solid; text-align:left;">Renta de Automovil</td>
        <td style=" text-align:right; padding-right: 10px;">
          @if($detalle_viatico[7]->transferencia_electronica != 0)
          $ {{number_format($detalle_viatico[7]->transferencia_electronica,2)}}
          @endif</td>
        <td style="border-right: 2px solid; border-left: 2px solid; text-align:right; padding-right: 10px;">@if($detalle_viatico[7]->efectivo != 0)$ {{number_format($detalle_viatico[7]->efectivo,2)}}@endif</td>
        <td style=" text-align:right; padding-right: 10px;">@if($detalle_viatico[7]->total != 0) $ {{number_format($detalle_viatico[7]->total,2)}}@endif</td>
        </tr>
        @endif
        
        <tr>
        <td style="border-right: 2px solid; text-align:left;">Alimentos</td>
        <td style=" text-align:right; padding-right: 10px;">
          @if($detalle_viatico[3]->transferencia_electronica != 0)
          $ {{number_format($detalle_viatico[3]->transferencia_electronica,2)}}
          @endif </td>
        <td style="border-right: 2px solid; border-left: 2px solid; text-align:right; padding-right: 10px;">@if($detalle_viatico[3]->efectivo != 0)$ {{number_format($detalle_viatico[3]->efectivo,2)}}@endif </td>
        <td style=" text-align:right; padding-right: 10px;">@if($detalle_viatico[3]->total != 0) $ {{number_format($detalle_viatico[3]->total,2)}} @endif</td>
        </tr>
        <tr>
        <td style="border-right: 2px solid; text-align:left;">Otro</td>
        <td style=" text-align:right; padding-right: 10px;">
          @if($detalle_viatico[4]->transferencia_electronica != 0)
          $ {{number_format($detalle_viatico[4]->transferencia_electronica,2)}}
          @endif </td>
        <td style="border-right: 2px solid; border-left: 2px solid; text-align:right; padding-right: 10px;">@if($detalle_viatico[4]->efectivo != 0)$ {{number_format($detalle_viatico[4]->efectivo,2)}}@endif </td>
        <td style=" text-align:right; padding-right: 10px;">@if($detalle_viatico[4]->total != 0) $ {{number_format($detalle_viatico[4]->total,2)}} @endif</td>
        </tr>
        </table>

        <p style="background-color: rgb(180, 180, 180);" class="ddv"><b>ITINERARIO Y LOGÍSTICA</b></p>

        <table class="iyl" style="border-collapse: collapse;" width="100%">
        <tr>
        <td width="25%"><b>ORIGEN </b> </td>
        <td style="border-left: 2px solid; text-align:left; padding-left: 10px;" width="30%">  {{$solicitud_viaticos->origen_destino}}</td>
        <td width="5%">&nbsp;</td>
        <td width="15%" style="border-right: 2px solid;"><b>UNIDAD VEHICULAR </b> </td>
        <td width="30%" style="text-align: center;">{{count($vehiculoiv) == 0 ? '' : $vehiculoiv[0]->unidad }}</td>
        </tr>
        <tr>
        <td><b>FECHA DE SALIDA </b> </td>
        <td style="border-left: 2px solid; text-align:left; padding-left: 10px;">  {{date("d/m/Y", strtotime($solicitud_viaticos->fecha_salida))}}</td>
        <td>&nbsp;</td>
        <td style="border-right: 2px solid;"><b >PLACAS </b> </td>
        <td style="text-align: center;">{{count($vehiculoiv) == 0 ? '' : $vehiculoiv[0]->km_inicial }}</td>
        </tr>
        <tr>
        <td><b>DESTINO </b> </td>
        <td style="border-left: 2px solid; text-align:left; padding-left: 10px;">  {{$solicitud_viaticos->origen_destino_destino}}</td>
        <td>&nbsp;</td>
        <td style="border-right: 2px solid; "><b>OPERADOR </b> </td>
        <td style="text-align:left;">&nbsp; {{count($vehiculoiv) == 0 ? '' : $vehiculoiv[0]->nombre_operador }}</td>
        </tr>
        <tr>
        <td style="border-right: 2px solid; "><b>FECHA DE OPERACION </b> </td>
        <td style="text-align:left; padding-left: 10px;">  {{date("d/m/Y", strtotime($solicitud_viaticos->fecha_operacion))}}</td>
        <td>&nbsp;</td>
        <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
        <td style="border-right: 2px solid; "><b>FECHA DE RETORNO ESTIMADA </b> </td>
        <td style="text-align:left; padding-left: 10px;">  {{date("d/m/Y", strtotime($solicitud_viaticos->fecha_retorno))}}</td>
        <td colspan="3">&nbsp;</td>
        </tr>
        </table>
        <p style="background-color: rgb(180, 180, 180);" class="ddv"><b>PERSONAL DESTINADO AL SERVICIO</b></p>
        <table width="100%" class="iyl">
        <tr>
        <td>&nbsp;</td>
        <td style="border-right: 2px solid; text-align: right;"><b>TOTAL PERSONAS </b> </td>
        <td>{{$solicitud_viaticos->total_personas}}</td>
        <td>&nbsp;</td>
        <td style="border-right: 2px solid; text-align: right;"><b>SUPERVISOR </b> </td>
        <td>{{$solicitud_viaticos->nombre_supervisor}}</td>
        <td></td>
        </tr>
        </table>
        <p style="border-bottom: 1px solid;" class="ddv"> <b> PERSONAL</b>
          <br>
          @foreach ($personalsv as $key => $value)
          {{$value->nombre_empleado.','}}&nbsp;
          @endforeach
        </p>

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
