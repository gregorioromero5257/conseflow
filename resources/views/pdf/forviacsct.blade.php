<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Formato de viaticos...</title>
<style media="screen">
.div {
  width: 100%;
  margin-left: 10px;
  margin-right: 10px;
  margin-top: 0px;
}
.img {
  padding-left: 25px;
  padding-top: 0px;
}
.letradostabla{
    font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
    border: 1px solid black;
}
.table {
  border-collapse: collapse;
  width: 100%;
}
body {
  margin: 0px;
}
.tam{
  font-family: Arial, sans-serif;
  font-size: 10px;
  text-align: center;
  border-collapse: collapse;
}
.si{
  font-family: Arial, sans-serif;
  font-size: 10px;
}
.header{
  width:100%;
  border: 1px solid black;
  border-collapse: collapse;
}
.headerr{
  border: 1px solid black;
  padding-top: 6px;
}
.bord{
  text-align:right;
  border: 1px solid black;
}
.bordd{
  border: 1px solid black;
  text-align:center;
}
.borddd{
  text-align:center;
  border: 1px solid black;
}
.encgr{
  border: 1px solid black;
  font-family: Arial, sans-serif;
  font-size: 11px;
  text-align:center;
  background-color: #C0C0C0;
}
.enc{
  border: 1px solid black;
}
.col{
  border-bottom: 1px solid black;
}
.ccol{
  border: 1px solid black
}
.ccool{
  border-bottom: 1 px solid black;
  text-align:center;
}
.fot{
  border: 1 px solid black;
  text-align:center;
  padding-bottom: 100px;
}
.fott{
  border-right: 1 px solid black;
  border-left: 1 px solid black;
  text-align:center;
}
.fottt{
  border-right: 1 px solid black;
  border-bottom: 1 px solid black;
  border-left: 1 px solid black;
  text-align:center;
}
</style>

</head>
<body >
<div class="div">

  <table class="header">
    <tr>
      <th style="border: 1px solid black;"><img src="img/CSCT.png" alt="Conserflow" width="120" height="60" class="img"></th>
      <th class="letradostabla" width="60%">SOLICITUD DE VIATICOS</th>
      <th class="borddd">

          <table class="tam" width="100%">
            <tr>
              <th style="border: 1px solid black; padding-top: 5px;">PTE-01/F-01</th>

            </tr>

            <tr>
              <th class="headerr">REVISIÓN: 00</th>

            </tr>
            <tr>
              <th class="headerr">EMISION: 11.02.19</th>
            </tr>
          </table>
      </th>
    </tr>
  </table>

<br>
<table class="si" style="border-collapse: collapse;" width="100%">

  <tbody>
    <tr>
      <td colspan="4"></td>
      <td colspan="2" class="bord"><b>FECHA DE SOLICITUD:</b></td>
      <td class="bordd"><b>{{$solicitud_viaticos->fecha_solicitud}}</b></td>
    </tr>
    <tr>
      <td colspan="7" style="height: 10px;"></td>

    </tr>
    <tr>
      <td class="borddd"><b>TIPO</b></td>
      <td class="borddd"><b>NO. SOLICITUD</b></td>
      <td class="borddd"><b>PROYECTO</b></td>
      <td></td>
      <td colspan="2" class="bord"><b>FECHA REQUERIDA DE PAGO:</b></td>
      <td style="border: 1px solid black; text-align:center;"><b>{{$solicitud_viaticos->fecha_pago}}</b></td>
    </tr>
    <tr>
      <td class="borddd">VIATICOS</td>
      <td class="borddd">{{$solicitud_viaticos->folio}}</td>
      <td class="borddd">{{$solicitud_viaticos->nombre_proyecto}}</td>
      <td colspan=4></td>
    </tr>
    <tr>
      <td colspan="7" style="height: 10px;"></td>
    </tr>
    <tr>
      <td colspan="3"></td>
      <td colspan="2" class="bord"><b>BENEFICIARIO:</b></td>
      <td colspan="2" class="borddd">{{$beneficiario_viatico == null ? '' :$beneficiario_viatico->nombre_beneficiario}}</td>

    </tr>
    <tr>
      <td colspan="3"></td>

      <td colspan="2" class="bord"><b>CTA/CLABE/TARJETA:</b> </td>
      <td colspan="2" class="borddd">
        {{$beneficiario_viatico->cuenta}}<br>
        {{$beneficiario_viatico->clabe}}<br>
        {{$beneficiario_viatico->tarjeta}}
      </td>
    </tr>
    <tr>
      <td class="enc"><b>CONCEPTOS</b></td>
      <td class="borddd"><b>MONTO</b></td>
      <td></td>
      <td colspan="2" class="bord"><b>BANCO:</b></td>

      <td colspan="2" class="borddd">{{$beneficiario_viatico->banco_nombre}}</td>

    </tr>
    <tr>
      <td class="enc"><b>IMPORTE T.E. (TRANSF. ELECTRONICA)</b></td>
      <td class="bord">$ {{number_format($tet,2)}}</td>
      <td colspan="5"></td>
    </tr>
    <tr>
      <td class="enc"><b>EFECTIVO</b></td>
      <td class="bord">$ {{number_format($et,2)}}</td>
      <td colspan="5"></td>
    </tr>
    <tr>
      <td class="enc"><b>TOTAL</b></td>
      <td class="bord"><b>$ {{number_format($tt,2)}}</b></td>
      <td></td>
      <td colspan="2" class="bord"><b>BENEFICIARIO:</b></td>

      <td colspan="2" class="borddd"></td>

    </tr>
    <tr>
      <td colspan="3"></td>
      <td colspan="2" class="bord"><b>CTA/CLABE/TARJETA:</b></td>

      <td colspan="2" class="borddd">

      </td>
    </tr>
    <tr>
      <td colspan="3"></td>
      <td colspan="2" class="bord"><b>BANCO:</b></td>
      <td colspan="2" class="borddd"></td>
    </tr>
  </tbody>
</table>




<table>
    <tr>
    <p class="encgr">
      <b>DETALLE DE VIATICOS</b>
    </p>
    </tr>
  </table>

  <table class="si" style="border-collapse: collapse;" width="70%">
    <thead>
      <tr>
        <th class="enc">CONCEPTO</th>
        <th class="borddd">T.E (TRANSF. ELECTRONICA)</th>
        <th class="borddd">EFECTIVO</th>
        <th class="borddd">TOTAL</th>
      </tr>
    </thead>
  <tbody>
    <tr>
      <td class="enc">COMBUSTIBLE</td>
      <td class="bord">$ {{number_format($detalle_viatico[0]->transferencia_electronica,2)}}</td>
      <td class="bord">$ {{number_format($detalle_viatico[0]->efectivo,2)}}</td>
      <td class="bord">$ {{number_format($detalle_viatico[0]->total,2)}}</td>
    </tr>
    <tr>
      <td class="enc">PEAJE</td>
      <td class="bord">$ {{number_format($detalle_viatico[1]->transferencia_electronica,2)}}</td>
      <td class="bord">$ {{number_format($detalle_viatico[1]->efectivo,2)}}</td>
      <td class="bord">$ {{number_format($detalle_viatico[1]->total,2)}}</td>
    </tr>
    <tr>
      <td class="enc">HOSPEDAJE</td>
      <td class="bord">$ {{number_format($detalle_viatico[2]->transferencia_electronica,2)}} </td>
      <td class="bord">$ {{number_format($detalle_viatico[2]->efectivo,2)}}</td>
      <td class="bord">$ {{number_format($detalle_viatico[2]->total,2)}}</td>
    </tr>
    <tr>
      <td class="enc">ALIMENTOS</td>
      <td class="bord">$ {{number_format($detalle_viatico[3]->transferencia_electronica,2)}}</td>
      <td class="bord">$ {{number_format($detalle_viatico[3]->efectivo,2)}}</td>
      <td class="bord">$ {{number_format($detalle_viatico[3]->total,2)}}</td>
    </tr>
    <tr>
      <td class="enc">OTROS</td>
      <td class="bord">$ {{number_format($detalle_viatico[4]->transferencia_electronica,2)}}</td>
      <td class="bord">$ {{number_format($detalle_viatico[4]->efectivo,2)}}</td>
      <td class="bord">$ {{number_format($detalle_viatico[4]->total,2)}}</td>
    </tr>
    <tr>
      <td class="enc">BOLETOS DE AVIÓN</td>
      <td class="bord">$ {{number_format($detalle_viatico[5]->transferencia_electronica,2)}}</td>
      <td class="bord">$ {{number_format($detalle_viatico[5]->efectivo,2)}}</td>
      <td class="bord">$ {{number_format($detalle_viatico[5]->total,2)}}</td>
    </tr>
    <tr>
      <td class="enc">BOLETOS DE AUTOBÚS</td>
      <td class="bord">$ {{number_format($detalle_viatico[6]->transferencia_electronica,2)}}</td>
      <td class="bord">$ {{number_format($detalle_viatico[6]->efectivo,2)}}</td>
      <td class="bord">$ {{number_format($detalle_viatico[6]->total,2)}}</td>
    </tr>
    <tr>
      <td class="enc">RENTA DE AUTOMOVIL</td>
      <td class="bord">$ {{number_format($detalle_viatico[7]->transferencia_electronica,2)}}</td>
      <td class="bord">$ {{number_format($detalle_viatico[7]->efectivo,2)}}</td>
      <td class="bord">$ {{number_format($detalle_viatico[7]->total,2)}}</td>
    </tr>
    <tr>
      <td colspan="4" class="col">&nbsp;</td>
    </tr>
    <tr>
      <td class="enc">TOTAL</td>
      <td class="bord">$&nbsp;{{number_format($tet,2)}}</td>
      <td class="bord">$&nbsp; {{number_format($et,2)}}</td>
      <td class="bord">$&nbsp; {{number_format($tt,2)}}</td>
    </tr>
    <tr>
      <td colspan="4" class="col"></td>
    </tr>
    <tr>
      <td colspan="4" class="col"></td>
    </tr>
  </tbody>
  </table>
<!--  -->
  <table>
      <tr>
        <p class="encgr">
          <b>NOTAS DE ITINERIARIO Y LOGISTICA</b>
        </p>
      </tr>
  </table>

    <table class="si" style="border-collapse: collapse;" width="70%">
      <tbody>
        <tr>
          <td class="ccol">ORIGEN-DESTINO</td>
          <td colspan="3" class="ccol">{{$solicitud_viaticos->origen_destino}}&nbsp;{{$solicitud_viaticos->origen_destino_destino}}</td>
          <td colspan="3"></td>
        </tr>
      </tbody>
    </table>
    <br>
    <table class="si" style="border-collapse: collapse;" width="100%">
      <tbody>
        <tr>
          <td class="ccol" width="24%">FECHA SALIDA</td>
          <td class="ccol">{{date("Y/m/d", strtotime($solicitud_viaticos->fecha_salida))}}</td>
          <td width="14%"></td>
          <td colspan="2" class="ccol" width="24%">FECHA DE OPERACIÓN</td>
          <td class="ccol">{{date("Y/m/d", strtotime($solicitud_viaticos->fecha_operacion))}}</td>
          <td width="14%"></td>
        </tr>
        <tr>
          <td class="ccol">HORA ESTIMADA DE SALIDA</td>
          <td class="ccol">{{$solicitud_viaticos->hora_estimada_salida == null ? '' : date("g:i a", strtotime($solicitud_viaticos->hora_estimada_salida))}}</td>
          <td ></td>
          <td colspan="2" class="ccol">FECHA DE RETORNO ESTIMADA</td>
          <td class="ccol">{{date("Y/m/d", strtotime($solicitud_viaticos->fecha_retorno))}}</td>
          <td ></td>
        </tr>
        <tr>
          <td colspan="7" style="height: 10px;"></td>
        </tr>

        <tr>
          <td class="bordd"><b>UNIDAD</b></td>
          <td class="bordd"><b>KM INICIAL</b></td>
          <td colspan="4" class="bordd"><b>OPERADOR</b></td>
          <td></td>
        </tr>
        @foreach ($vehiculoiv as $key => $value)
        <tr>
          <td class="bordd">{{$value->unidad}}</td>
          <td class="bordd">{{$value->km_inicial}}</td>
          <td colspan="4" class="bordd">{{$value->nombre_operador}}</td>
          <td></td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <table>
        <tr>
          <p class="encgr">
            <b>PERSONAL DESTINADO AL SERVICIO</b>
          </p>
        </tr>
    </table>

    <table class="si" style="border-collapse: collapse;" width="100%">
      <tbody>
        <tr>
          <td class="bordd" width="24%">TOTAL DE PERSONAL A ASISTIR</td>
          <td class="bordd">{{$solicitud_viaticos->total_personas}}</td>
          <td width="14%"></td>
          <td class="bordd" colspan="2">SUPERVISOR</td>
          <td class="bordd" colspan="2">{{$solicitud_viaticos->nombre_supervisor}}</td>
        </tr>
        <tr>
          <td colspan="7" style="height: 10px;"></td>
        </tr>
        <tr>
          <td></td>
          <td colspan="4" class="bordd"><b>NOMBRE DEL PERSONAL QUE ASISTE AL SERVICIO:</b></td>
          <td colspan="2"></td>
        </tr>

        @foreach($personalsv as $value)
        <tr>
          <td colspan="2" class="ccool">{{$value->nombre_empleado}}</td>
          <td colspan="2"></td>
          <td colspan="3"  class="ccool"></td>
        </tr>
        @endforeach
        <tr>
          <td colspan="7" style="height: 10px;"></td>
        </tr>
      </tbody>
    </table>

  <div style="page-break-inside: avoid;">
    <table class="si" style="border-collapse: collapse;" width="100%">
      <tr>
        <td class="fot">Elaboro</td>
        <td class="fot">Reviso</td>
        <td class="fot">Autorizo</td>
      </tr>
      <tr>
        <td class="fott">{{$solicitud_viaticos->nombre_elabora}}</td>
        <td class="fott">{{$solicitud_viaticos->nombre_revisa}}</td>
        <td class="fott">{{$solicitud_viaticos->nombre_autoriza}}</td>
      </tr>
      <tr>
        <td class="fottt"></td>
        <td class="fottt"></td>
        <td class="fottt"></td>
        </tr>
    </table>
  </div>

 </div>
</body>

</html>
