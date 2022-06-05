<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Formato factura...</title>
<style media="screen">

@page {
    margin: 100px 25px;
}

header {
    position: fixed;
    top: -50px;
    left: 0px;
    right: 0px;
    height: 150px;

}
.pagenum:before {
    content: counter(page);
}

.div {
  width: 100%;
  margin-left: 10px;
  margin-right: 10px;
  margin-top: 0px;
}
.img {
  padding-left: 12px;
  padding-top: 0px;
}
.letradostabla{
    font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
}
.table {
  border-collapse: collapse;
  width: 100%;
}
body {
  margin: 0px;
}


p.red{
  background-color:#ddd;
  font-family: Arial, sans-serif;
  font-size: 10px;

}
.tam{
  font-family: Arial, sans-serif;
  font-size: 6px;
  text-align: center;
}

th.bord{
  border-color: #b4b4b4;
  border-style: solid;
  border-width: 1px 1px 1px 1px;
  text-align:center;
}
td.lig{
  font-weight: normal;
  font-size: 8px;
}
th.bordd{
  background-color: #ddd;
  text-color: white;
  text-align: center;
}
td.jus{
  text-align:center;
}
#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    font-size: 8px;
    width: 100%;
}
#customers td, #customers th {
    border: 1px solid #ddd;
    padding: 2px;
    text-align: center;
}
#customers tr:nth-child(even){background-color: #f2f2f2;}



#customers th {

    text-align: left;
    border-color: #b4b4b4;
    text-align: center;
}
/* @media print {
  footer {page-break-after: always;}
} */

</style>

</head>
<body >
@if($factura->emisor_id == 1)
<header>
<table style="width:100%;">
    <tr>
      <th><img src="img/conserflow.png" alt="Conserflow" width="120" height="60"class="img"></th>
      <th class="letradostabla">CONSERFLOW S.A. DE C.V.</th>
      <th style="text-align:center;">
          <table class="tam" width="100%">
            <tr>
            <td style="background-color: #b4b4b4; text-align: center;">TIPO DE  COMPROBANTE</td>
            </tr>
            <tr>

            <td style="text-transform: uppercase;">{{$factura->tipo_factura_d}}</td>

            </tr>
            <tr><th style="border-bottom: 1px solid #b4b4b4">PAGINA</th></tr>
            <tr>
              <th class="pagenum"></th>
            </tr>

          </table>
      </th>
    </tr>
  </table>
</header>
@else
<header>
<br>
<table style="width:100%;">
    <tr>
      <th style="font-family: Arial, sans-serif; font-size: 14px; text-align: center;">DIEGO CRUZ MARTINEZ </th>
      <th style="text-align:center;" width="120px">
              <table class="tam" width="100%">
                <tr>
                <td style="background-color: #b4b4b4; text-align: center;">TIPO DE  COMPROBANTE</td>
                </tr>
                <tr>
                <td style="text-transform: uppercase;">{{$factura->tipo_factura_d}}</td>
                </tr>
                <tr><th style="border-bottom: 1px solid #b4b4b4">PAGINA</th></tr>
                <tr>
                  <th class="pagenum"></th>
                </tr>

              </table>
      </th>
    </tr>
    <tr>
        <th style="font-family: Arial, sans-serif; font-size: 8px; text-align: center; padding-top: -20px;">RFC: CUMD8310244R3</th>
    </tr>
  </table>
</header>
<br>
@endif
  <table>
    <tr>
    <p style="border-bottom: 2px solid #C0C0C0; font-family: Arial, sans-serif; font-size: 11px; text-align:center;">
    <b>EMISOR</b></p>
    </tr>
  </table>

  <table id="customers" >
      <tr>
        <th >RFC</th>
        <th >RAZÓN SOCIAL</th>
        <th >DOMICILIO</th>
        <th >COLONIA</th>
        <th>MUNICIPIO</th>
        <th>ENTIDAD FEDERATIVA</th>
      </tr>
      <tr>
        <td>{{$emisor->rfc}}</td>
        <td>{{$emisor->razon_social}}</td>
        <td>{{$emisor->calle}}</td>
        <td>{{$emisor->colonia}}</td>
        <td>{{$emisor->municipio}}</td>
        <td>{{$emisor->entidad_federativa}}</td>
      </tr>
      <tr>
        <th >NUM. EXT</th>
        <th >NUM. INT</th>
        <th >C.P.</th>
        <th >RÉGIMEN FISCAL</th>
      </tr>
      <tr>
        <td>{{$emisor->numero_exterior}}</td>
        <td>{{$emisor->numero_interior}}</td>
        <td>{{$emisor->codigo_postal}}</td>
        <td>{{$emisor->regimenfiscal}}</td>
      </tr>
  </table>

  <table>
    <tr>
    <p style="border-bottom: 2px solid #C0C0C0; font-family: Arial, sans-serif; font-size: 11px; text-align:center;">
    <b>RECEPTOR</b></p>
    </tr>
  </table>


<table id="customers" >
    <tr>
      <th >RFC</th>
      <th >RAZÓN SOCIAL</th>
      <th >DOMICILIO</th>
      <th >COLONIA</th>
      <th >MUNICIPIO</th>
      <th >ENTIDAD FEDERATIVA</th>
    </tr>
    <tr>
      <td>{{$receptor->rfc}}</td>
      <td>{{$receptor->nombre}}</td>
      <td>{{$receptor->calle}}</td>
      <td>{{$receptor->colonia}}</td>
      <td>{{$receptor->municipio}}</td>
      <td>{{$receptor->entidad_federativa}}</td>
    </tr>
    <tr>
      <th >NUM. EXT</th>
      <th >NUM. INT</th>
      <th >C.P.</th>
      <th >USO DEL CFDI</th>
    </tr>
    <tr>
      <td>{{$receptor->numero_exterior}}</td>
      <td>{{$receptor->numero_interior}}</td>
      <td>{{$receptor->codigo_postal}}</td>
      <td>{{$factura->uso_cfdi}}</td>
    </tr>
</table>
<table>
    <tr>
    <p style="border-bottom: 2px solid #C0C0C0; font-family: Arial, sans-serif; font-size: 11px; text-align:center;">
    <b>COMPROBANTE FISCAL</b></p>
    </tr>
  </table>
  <table id="customers">
    <tr>
      <th >SERIE</th>
      <th >FOLIO</th>
      <th >LUGAR DE EMISIÓN</th>
      <th >FECHA Y HORA DE EMISIÓN</th>

      <th colspan="2"  >OBSERVACIONES</th>
      @if($factura->taxid != null)
      <th> TAX ID</th>
      @endif
    </tr>
    <tr>
    <td >{{$factura->serie}}</td>
    <td >{{$factura->folio}}</td>
    <td >{{$factura->codigo_postal}}</td>
    <td >{{$fecha_emision}}</td>

    <th  rowspan="2" colspan="2">{{$factura->proveeade}}<br>{{$factura->clave_ob}}<br>{{$factura->orden_ob}}</th>
    @if($factura->taxid != null)
    <td> {{$factura->taxid}}</td>
    @endif
    </tr>
    <tr>
    </tr>
</table>

  <table>
  <tr>
  <p style="border-bottom: 2px solid #C0C0C0; font-family: Arial, sans-serif; font-size: 11px; text-align:center;">
  <b>CONCEPTOS</b></p>
  </tr>
  </table>

  <table style="font-family: Arial, sans-serif; font-size: 8px; width: 100%;">
  <tbody>
    <tr>
      <th class="bord">CÓDIGO</th>
      <th class="bord">CLAVE <br>UNIDAD</th>
      <th class="bord">NUM <br>IDENTIFICACIÓN</th>
      <th class="bord">DESCRIPCIÓN</th>
      <th class="bord">VALOR UNITARIO</th>
      <th class="bord">CANTIDAD</th>
      <th class="bord">IMPORTE</th>
      <th class="bord">DESCUENTO</th>
    </tr>
    @foreach($arreglo_pf as $value)
    <tr>
      <td class="jus">{{$value['sat_ps']}}</td>
      <td  class="jus">{{$value['partidas']->cla}}</td>
      <td  class="jus">{{$value['partidas']->numero_identificacion}}</td>
      <td class="jus">{{$value['partidas']->descripcion}}&nbsp;{{$value['partidas']->comentario}}</td>
      <td  class="jus" tyle="text-align:right;">{{number_format($value['partidas']->valor_unitario,2)}}</td>
      <td  class="jus" style="text-align:right;">{{$value['partidas']->cantidad}}</td>
      <td  class="jus" style="text-align:right;">{{number_format($value['partidas']->importe,2)}}</td>
      <td  class="jus" style="text-align:right;">{{number_format($value['partidas']->descuento,2)}}</td>
    </tr>
    <tr>
      <th>TRASLADO</th>
      <th style="background-color:transparent; font-weight: normal;" >IMPUESTO: IVA</th>
      <th style="background-color:transparent; font-weight: normal;">TASA O CUOTA: {{$value['partidas']->impuesto_iva}}</th>
      <th style="background-color:transparent; font-weight: normal;">BASE: {{number_format($value['partidas']->importe,2)}}</th>
      <th style="background-color:transparent; font-weight: normal;">IMPORTE ${{number_format($value['partidas']->valor_impuesto,2)}}</th>

    </tr>
    @if($value['partidas']->retencion != 0)
    <tr>
      <th>RETENCION</th>
      <th style="background-color:transparent; font-weight: normal;" >IMPUESTO: IVA</th>
      <th style="background-color:transparent; font-weight: normal;">TASA O CUOTA: {{$value['partidas']->retencion}}</th>
      <th style="background-color:transparent; font-weight: normal;">BASE: {{number_format($value['partidas']->importe,2)}}</th>
      <th style="background-color:transparent; font-weight: normal;">IMPORTE ${{($value['partidas']->importe * $value['partidas']->retencion)}}</th>

    </tr>
    @endif
    <tr>
    <td colspan="7" style="border-top: 1px solid #000000"></td>
    </tr>
    @endforeach

    </tbody>
  </table>


  @if($factura->tipo_factura_d === 'Pago')
  <table>
  <tr>
  <p style="border-bottom: 2px solid #C0C0C0; font-family: Arial, sans-serif; font-size: 11px; text-align:center;">
  <b>RECIBOS DE PAGO</b></p>
  </tr>
  </table>

  <table style="font-family: Arial, sans-serif; font-size: 8px; width: 100%;">
  <tbody>
    <tr>
      <th class="bord">FECHA PAGO</th>
      <th class="bord">FORMA PAGO</th>
      <th class="bord">MONEDA</th>
      <th class="bord">T. CAMBIO</th>
      <th class="bord">IMPORTE PAGADO</th>
      <th class="bord">NÚMERO OPERACIÓN</th>
      <th class="bord">RFC EMISOR ORD</th>
      <th class="bord">BANCO ORDENANTE</th>
      <th class="bord">CTA ORDENANTE</th>
    </tr>

    <tr>
      <td class="jus">{{$factura->fecha_pago}}</td>
      <td  class="jus">{{$factura->forma_pago}}</td>
      <td class="jus">{{$factura->scm_c_moneda}}</td>
      <td  class="jus" >{{$factura->tipo_cambio}}</td>
      <td  class="jus" style="text-align:right;">{{$suma_partidas_pagos}}</td>
      <td  class="jus" >{{$factura->num_operacion}}</td>
      <td  class="jus" >{{$receptor->rfc}}</td>
      <td  class="jus" >{{$factura->ban_ordenante}}</td>
      <td  class="jus" >{{$factura->cuenta_ordenante}}</td>
    </tr>
    <tr>
      <th class="bord">RFC BANCO ORDENANTE</th>
      <th class="bord">RFC BANCO BENEFICIARIO</th>
    </tr>

    <tr>
      <td class="jus">{{$factura->rfc_cuenta_ordenante}}</td>
      <td  class="jus">{{$factura->rfc_cuenta_beneficiario}}</td>
    </tr>


    <tr>
    <td colspan="9" style="border-top: 1px solid #000000"></td>
    </tr>


    </tbody>
  </table>
  @endif


    @if($factura->tipo_factura_d === 'Pago')
    <table>
    <tr>
    <p style="border-bottom: 2px solid #C0C0C0; font-family: Arial, sans-serif; font-size: 11px; text-align:center;">
    <b>RECIBOS DE PAGO - DOCUMENTOS RELACIONADOS</b></p>
    </tr>
    </table>

    <table style="font-family: Arial, sans-serif; font-size: 8px; width: 100%;">
    <tbody>
      <tr>
        <th class="bord">UUID</th>
        <th class="bord">SERIE</th>
        <th class="bord">FOLIO</th>
        <th class="bord">METODO DE PAGO</th>
        <th class="bord">SALDO ANTERIOR</th>
        <th class="bord">IMPORTE PAGADO</th>
        <th class="bord">SALDO INSOLUTO</th>

      </tr>
      @foreach($partidas_pagos as $value)
      <tr>
        <td class="jus" style="text-transform: uppercase;">{{$value->uuid}}</td>
        <td  class="jus" style="text-transform: uppercase;">{{$value->serie}}</td>
        <td class="jus" style="text-transform: uppercase;">{{$value->folio}}</td>
        <td  class="jus" tyle="text-align:right;">{{substr($factura->metodo_pago,0, 3)}}</td>
        <td  class="jus" style="text-align:right;">{{$value->saldo_anterior}}</td>
        <td  class="jus" style="text-align:right;">{{$value->importe_pagado}}</td>
        <td  class="jus" style="text-align:right;">{{$value->saldo_insoluto}}</td>

      </tr>

      <tr>
      <td colspan="7" style="border-top: 1px solid #000000"></td>
      </tr>
      @endforeach
      <tr>
        <td colspan="7" style="text-transform: uppercase;">TOTAL EN LETRA: <b>({{$v_l_total_pagos}})</b></td>
      </tr>
      </tbody>
    </table>
    @endif



  @if($factura->tipo_factura_id == 1 || $factura->tipo_relacion != '')
  <table>
    <tr>
    <p style="border-bottom: 2px solid #C0C0C0; font-family: Arial, sans-serif; font-size: 11px; text-align:center;">
    <b>DOCUMENTOS RELACIONADOS</b></p>
    </tr>
  </table>


  <table id="customers" style="page-break-inside: avoid;">
  <tbody>
  <tr>
  <th >TIPO DE RELACIÓN</th>
  <th >UUID</th>
  </tr>
  @foreach ($a as $ky => $aas)
  <tr>
    <td class="bordd">{{$factura->tiporel_cod}} - {{$factura->tiporel_desc}}</td>
    <td class="bordd">{{$aas->uuid}}</td>
  </tr>
  @endforeach
  </tbody>
  </table>
@endif





  <div style="page-break-inside: avoid; width: 100%; bottom: 0px;">

        @if($factura->tipo_factura_d != 'Pago')
  <table style="font-family: Arial, sans-serif; text-align:justify; font-size: 8px; width: 100%;">

          <tr>
            <th class="bord" width="50%">CONDICIONES COMERCIALES</th>
            <th class="bord" width="50%">TOTAL DOCUMENTO</th>
          </tr>
        <tr>
            <td >
                <table style="text-align: right; width:100%;">
                  <tr>
                    <td  width="50%">FORMA DE PAGO</td>
                    <td  width="50%">{{$factura->forma_pago}}</td>
                  </tr>
                  <tr>
                    <td>MÉTODO DE PAGO</td>
                    <td>{{$factura->metodo_pago}}</td>
                  </tr>
                  <tr>
                    <td>CONDICIÓN DE PAGO</td>
                    <td>{{$factura->condicion_pago}}</td>
                  </tr>
                  <tr>
                    <td>MONEDA</td>
                    <td>{{$factura->scm_c_moneda}}</td>
                  </tr>
                  <tr>
                    <td>TCAMBIO</td>
                    <td>{{$factura->tipo_cambio}}</td>
                  </tr>
                </table>
            </td>
            <td>
                <table style="text-align: right; width:100%;">
                <tr>
                <td width="50%">SUBTOTAL</td>
                <td width="50%">{{number_format($suma_subtotal,2)}}</td>
                </tr>
                <tr>
                <td>(+)IVA %16.00</td>
                <td>{{number_format($suma_impuesto,2)}}</td>
                </tr>
                @if($suma_retencion != 0)
                <tr>
                <td>(-)RETENCION IVA </td>
                <td>{{number_format($suma_retencion,2)}}</td>
                </tr>
                @endif
                <tr>
                <td>DESCUENTO</td>
                <td>{{number_format($suma_descuento,2)}}</td>
                </tr>
                <tr>
                <td>TOTAL {{$factura->scm_c_moneda}}</td>

                <td>{{number_format(((($suma_subtotal - $suma_descuento) + $suma_impuesto)- $suma_retencion),2)}}</td>
                </tr>
                </table>
            </td>
        </tr>
        <tr>
          <td style="text-transform: uppercase;">TOTAL EN LETRA: <b>({{$cambio}})</b></td>
        </tr>
  </table>
  @endif

  <table style="width: 100%;">
              <tr>
                <th width="25%"></th>
                <th width="75%"></th>
              </tr>
    <td>
    <table  width="100%">

      <img src="img/{{$id}}.png" width="160" height="160">

      </table>
    </td>
    <td>


      <table width="100%" style="font-family: Arial, sans-serif; font-size: 6px;  table-layout: fixed; word-wrap: break-word; ">
             <tr>
               <td height="10"></td>
            </tr>
            <tr>
              <th class="bordd">Número de certificado Emisor</th>
              <th class="bordd">Folio Fiscal</th>
              <th class="bordd">Número de certificado SAT</th>
            </tr>
            <tr>
              <td style="text-align: center;">{{$certificado_emisor}}</td>
              <td style="text-align: center;">{{$uuid}}</td>
              <td style="text-align: center;">{{$numcersat}}</td>
            </tr>
            <tr>
            <th class="bordd" >Cadena Original del Timbre</th>
            <th class="bordd">Sello Digital del CFDI</th>
            <th class="bordd">Sello Digital del Sat</th>
            <tr>
              <td>{{$cadenaoriginal}}</td>
              <td>{{$sellocfd}}</td>
              <td>{{$sellosat}}</td>
            </tr>
            <tr>
            <th class="bordd">Fecha y hora de emision</th>
            <th class="bordd">RFC del Proveedor</th>
            </tr>
            </tr>
            <tr>
            <td style="text-align: center;">{{$fecha_certificacion}}</td>
            <td style="text-align: center;">{{$emisor->rfc}}</td>
            </tr>


      </table>
      </td>
      <td>
        <table width="100%">div</table>
      </td>
</table>

      <br>

      <p style=" font-family: Arial, sans-serif;  font-size: 10px; text-align:center;">ESTE DOCUMENTO ES UNA REPRESENTACION IMPRESA DE UN CFDI V3.3</p>
  </div>

</body>

</html>
