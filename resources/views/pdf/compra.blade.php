<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> -->
  <title>Compra</title>
<style media="screen">
  .dos {
    font-family: Arial, Helvetica, sans-serif;
     font-size: 8px;
    text-align: center;
    border: 1px solid;
  }
  .dosanexo {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 10px;
	  font-weigth : bold;
    text-align: center;
    border: 1px solid;
  }
  .doss {
    border: 1px solid ;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 7px;
    text-align: center;
  }
  .cinco {
    border: 1px solid black;
    font-weight: normal;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 9px;
    text-align: center;
  }
  .seis {
    border: 1px solid;
    font-weight: normal;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 8px;
  }
  .siete {
    border: 1px solid ;
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 7px;
    text-align: right;
  }
  .ocho {
    border: 1px solid ;
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 7px;
    text-align: left;
  }
  .nueve {
    border: 1px solid ;
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 8px;
    text-align: left;
  }
  .once {
    font-weight: normal;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 9px;
    text-align: center;
  }
  .doce {
     border: 1px solid ; */
    font-weight: normal;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 8px;
    text-align: right;
  }
  .docea {
     border-right: 0px solid ; */
     border-top: 1px solid;
     border-bottom: 1px solid;
     border-left: 0px solid;
     font-weight: normal;
     font-family: Arial, Helvetica, sans-serif;
     font-size: 8px;
  }
  .doceb {
     border-right: 1px solid ; */
     border-top: 1px solid;
     border-bottom: 1px solid;
     border-left: 0px solid;
     font-weight: normal;
     font-family: Arial, Helvetica, sans-serif;
     font-size: 8px;
     text-align: right;
  }
  .trece {
    border-right: 1px solid ; */
    border-top: 1px solid;
    border-bottom: 1px solid;
    border-left: 0px solid;
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 8px;
    text-align: right;
  }
  .cuatro {
    border: 1px solid black;
    text-decoration: underline;
    font-weight: normal;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 8px;
    text-align: left;
  }
  .table1 {
    border-collapse: collapse;
  }
  .letradostabla{
      font-size: 12px;
      text-align: right;
  }
  }
  .letratresstabla{
      font-size: 8px;
       font-family: Arial, Helvetica, sans-serif;
      text-align: right;
  }
  .letrados {
      font-size: 12px;
      font-family: Arial, Helvetica, sans-serif;
      text-align: right;
  }
  .div {
    width: 100%;
    margin-left: 0px;
    margin-right: 0px;
    margin-left: 10px;
    margin-right: 10px;
    margin-top: 0px;
  }
  body {
    margin: 0px;
  }
  tr {
    height:8px;
  }
  table{
      width:100%;
      table-layout: fixed;
          overflow-wrap: break-word;
  }
.font{
  font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: justify;
    border-collapse: collapse;
    border: 1px solid;
    table-layout: fixed;
    word-wrap: break-word;
    page-break-inside: avoid;
}
.fontc{
  font-size: 10px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: justify;
    border-collapse: collapse;
    border: 1px solid;
    table-layout: fixed;
    word-wrap: break-word;
    page-break-inside: avoid;
}
</style>

</head>
<body>
<div class="div">
  <table>
  <tr>
    <td rowspan="3" width="20%">
        <img src="img/conserflow.png" alt="Conserflow" width="120" height="50">
    </td>
    <td class="letradostabla" width="80%">
        <b>CONSERFLOW S.A. DE C.V.</b>
    </td>
  </tr>
  <tr>
      <td class="letratresstabla">
        INGENIERIA EN AUTOMATIZACION+ INSTRUMENTACION+ELECTRICA MANTENIMIENTO INDUSTRIAL + MONTAJES MECÁNICOS + FABRICACIÓN METAL MECANICA + OBRA PESADA
      </td>
  </tr>
  <tr>
    <td class="letratresstabla" >
      Dirección: Calle Del Mezquite Lote 5 Mza. 3, Parque Industrial Tehuacán-Miahuatlán, C.P. 75820 Tehuacán, Pue.
      <br>
      RFC: CON1912026U2
    </td>
  </tr>
</table>


<p style=" font-family: Arial, sans-serif;  font-size: 14px; text-align:center;">
{{$folio_com[3] === 'VEHÍCULOS' ? 'ORDEN COMPRA VEHICULOS' : 'ORDEN DE COMPRA PROVEEDORES'}}
</p>


 <table class="table1" cellpadding="0" cellspacing="0">
 <tbody >

<tr>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
     <td class="dos">
         <table cellspacing="10" cellpadding="10">
             <td colspan="2" class="dos" style="border: antiquewhite"><b>CODIGO:</b></td>
         </table>
     </td>
     <td>&nbsp;</td>
 </tr>
 <tr>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
     <td class="dos">
         <table cellspacing="0" cellpadding="0">

             <td  class="dos" rowspan="2"><b>REVISION:</b></td>
             <td  class="dos" rowspan="2"><b>EMISION:</b></td>
         </table>
     </td>
     <td>&nbsp;</td>
 </tr>


  <tr>
  <td colspan="6" style="height: 0.2em;"></td>
  </tr>

  <tr>
    <td colspan="4"></td>
    <td bgcolor="#E6E6E6" class="dos">REQUISICIÓN</td>
    <td class="dos">
      @foreach($folios_requis as $fol)
      {{$fol->folio }}&nbsp;<br>
      @endforeach
    </td>
  </tr>

  <tr>
  <td colspan="6" style="height: 0.2em;"></td>
  </tr>

  <tr>
    <td  bgcolor="#E6E6E6" width="200" colspan="4" class="dos">RAZON SOCIAL/RFC/DIRECCIÓN</td>
    <td bgcolor="#E6E6E6" class="dos">COTIZACION / REFERENCIA </td>
    <td  bgcolor="#E6E6E6" class="dos">FOLIO DE ORDEN DE COMPRA</td>
  </tr>
  <tr>
    <th width="10%"  colspan="2" class="dos">#PROV.</th>
    <th  class="cuatro" colspan="2">
    {{$compra[0]->razonsocialp}}
    </th>
    <th  class="cinco">
      @foreach($coti as $co)
      {{$co->folio}}
      @endforeach
    </th>
    <th  class="cinco">{{$compra[0]->folio}}</th>
  </tr>
<tr>
  <td colspan="4" bgcolor="#E6E6E6" class="dos">CONDICIONES DE PAGO</td>
    <td bgcolor="#E6E6E6" class="dos">PERIODO DE ENTREGA</td>
    <td  bgcolor="#E6E6E6" class="dos">FECHA DE ORDEN</td>
  </tr>
  <tr>
    <td colspan="4" class="cinco">{{$compra[0]->condicion_pago}}&nbsp;{{$compra[0]->comentario_condicion_pago}}</td>
    <td class="cinco">{{$compra[0]->periodo_entrega}}</td>
    <td class="cinco">{{$fechafinal}}</td>
  </tr>
  <tr>
  <td colspan="6" style="height: 0.2em;"></td>
  </tr>
</tbody>
</table>

<table class="table1" >
<tbody style="width:100%">
  <tr>
    <td width="5%" bgcolor="#E6E6E6" class="dos">Part.</td>
    <td width="5%" bgcolor="#E6E6E6" class="dos">Cant.</td>
    <td width="5%" bgcolor="#E6E6E6" class="dos">Unid.</td>
    <td width="62.5%" bgcolor="#E6E6E6" class="dos">Descripción</td>
    <td width="65" bgcolor="#E6E6E6" class="dos">Precio Unitario</td>
    <td width="66" bgcolor="#E6E6E6" class="dos">Total</td>
  </tr>

  {{$nombreproyecto = ''}}
    @foreach($ordenesreq as $key => $comp)
    <tr >
      <td width="21" class="doss">{{$count}}</td>
      <td width="50" class="doss">{{$comp->cantidad}}</td>
      <td width="50" class="doss">{{$comp->unidadA}}</td>
      <td width="190" class="seis">{{$comp->comentario == null ? $comp->descripcionA : $comp->comentario}}
      </td>
      <td width="65" class="doce">{{$comp->precio_unitario}}&nbsp;</td>
      <td width="66" class="doce">{{number_format((($comp->cantidad)*($comp->precio_unitario)),2)}}&nbsp;</td>
    </tr>
{{$count +=1}}
  {{$nombreproyecto =$comp->nombrep}}
    @endforeach
      @for ($i = 0; $i < $valora; $i++)
      <tr>
        <td class="doss">{{($i+1)+$partidascount}}</td>
        <td class="doss">&nbsp;</td>
        <td class="doss">&nbsp;</td>
        <td class="doss">&nbsp;</td>
        <td class="doss">&nbsp;</td>
        <td class="doss">&nbsp;</td>
      </tr>
      @endfor

</tbody>
</table>


<table class="table1">
  <tr>
  <td colspan="3" class="nueve" width="77.5%" style="border: 1px solid; border-bottom: none;"></td>
   <td width="11.2%" class="nueve">SUBTOTAL:</td>
   <td class="docea">$</td>
    <td class="doceb">{{number_format($subtotal->subtotal, 2)}}&nbsp;</td>
 </tr>
 @if($compra[0]->descuento > 0)
 <tr>
   <td colspan="3" class="nueve" width="77.5%" style="border: 1px solid; border-bottom: none;"></td>
    <td width="11.2%" class="nueve">DESCUENTO: {{$compra[0]->descuento}} %</td>
    <td class="docea">$</td>
     <td class="doceb">{{$subtotal_info}}&nbsp;</td>
 </tr>
 <tr>
   <td colspan="3" class="nueve" width="77.5%" style="border: 1px solid; border-bottom: none;"></td>
    <td width="11.2%" class="nueve">SUBTOTAL: </td>
    <td class="docea">$</td>
     <td class="doceb">{{$subtotal_info_dos}}&nbsp;</td>
 </tr>
 @endif
 @if(count($impuestos) == 0)
  {{$arreglos}}
@else
 @foreach($arreglo as $impuesto)
 <tr>
  <td colspan="3" class="letratresstabla" style="border-left: 1px solid; text-align: center;"><b></b></td>
   <td class="nueve">{{strtoupper($impuesto['impuestos']->tipo)}} {{$impuesto['impuestos']->porcentaje}}%:</td>
   <td class="docea">$</td>
    <td class="doceb">{{number_format($impuesto['totalim'],2)}}&nbsp;</td>
 </tr>
 @endforeach
 @endif
 <tr>
 <td colspan="3" style="border-bottom: 1px solid; border-right: 1px solid; border-left:1px solid;"></td>
   <td  class="nueve">TOTAL:</td>
   <td class="docea">$</td>
    <td class="trece">{{$total}}&nbsp;</td>
 </tr>
 <tr>
  <td  colspan="6" style="height: 0.2em;">
  </td>
</tr>
</table>

<div style=" table-layout: fixed;
    word-wrap: break-word;
    page-break-inside: avoid;">
<table  class="table1">
  <tr>
    <td width="127" bgcolor="#E6E6E6" class="dos">El proveedor entregará la siguiente <br>documentación</td>
    <td width="330" class="dos">&nbsp;</td>
  </tr>
</table>

<table  class="table1">
  <tr>
    <td width="127" bgcolor="#E6E6E6" class="siete">LUGAR DE ENTREGA</td>
    <td width="330" class="dos">{{$compra[0]->lugar_entrega}}</td>
  </tr>
</table>

<table  class="table1">
  <tr>
    <td bgcolor="#E6E6E6" class="siete" width="127">BANCO DE TRANSFERENCIA</td>
    <td class="dos" width="190">&nbsp;{{count($datos_bancarios) == 0 ? 'N/A' : $datos_bancarios[0]->banco }}</td>
    <td bgcolor="#E6E6E6" class="siete" width="65">MONEDA DE PAGO</td>
    <td class="dos" width="66">

      @if($compra[0]->moneda == 1)  USD
      @elseif($compra[0]->moneda == 2)  MXN
      @elseif($compra[0]->moneda == 3) EUR
      @endif
      </td>
  </tr>
  <tr>
    <td bgcolor="#E6E6E6" class="siete">TITULAR DE LA CUENTA</td>
    <td class="dos">{{$compra[0]->razonsocialp }}</td>
    <td bgcolor="#E6E6E6" class="siete" >TIPO DE CAMBIO</td>
    <td class="dos">{{$compra[0]->tipocambiop == NULL ? 'N/A' : $compra[0]->tipocambiop}}</td>
  </tr>

  <tr>
    <td bgcolor="#E6E6E6" class="siete">CONVENIO CIE</td>
    <td class="dos">{{$compra[0]->cie == NULL ? 'N/A' : $compra[0]->cie}}</td>
    <td bgcolor="#E6E6E6" class="siete" >SUCURSAL</td>
    <td class="dos">{{$compra[0]->sucursal == NULL ? 'N/A' : $compra[0]->sucursal }}</td>
  </tr>

  <tr>
    <td bgcolor="#E6E6E6" class="siete">NUMERO DE CUENTA</td>
    <td class="dosanexo">{{count($datos_bancarios) == 0 ? 'N/A' : $datos_bancarios[0]->cuenta}}</td>
    <td bgcolor="#E6E6E6" class="siete" >REFERENCIA</td>
    <td class="dos">{{$compra[0]->referencia == NULL ? 'N/A' : $compra[0]->referencia}}</td>
  </tr>
  <tr>
    <td bgcolor="#E6E6E6" class="siete">CLABE</td>
    <td class="dosanexo">{{count($datos_bancarios) == 0 ? 'N/A' : $datos_bancarios[0]->clabe}}</td>
    <td colspan="2" class="dos">&nbsp;</td>
  </tr>
</table>



<table  class="table1" >
  <tr>
    <td rowspan="2" width="127" class="ocho">OBSERVACIONES:{{$compra[0]->observaciones === NULL ? '' : $compra[0]->observaciones}}</td>
    <td class="dos" width="190" bgcolor="#E6E6E6">CENTRO DE COSTO</td>
    <td class="dos" width="135" bgcolor="#E6E6E6">NOMBRE DEL SOLICITANTE</td>
  </tr>
  <tr>
    <td class="dos">{{$nombreproyecto}}</td>
    <td class="ocho">--</td>
  </tr>
</table>
</div>
<table>
<tr>
  <td  colspan="3" style="height: 0.2em;">
  </td>
</tr>
  <tr>
    <td class="once" width="160">Elaboró<br><br><br></td>
    <td class="once" width="160">Autorizó<br><br><br></td>
    <td class="once" width="160">Aceptación de orden<br><br><br></td>
  </tr>
  <tr>
    <td class="once" >{{$compra[0]->nomnree}}</td>
    <td class="once" >{{$compra[0]->nombrea}}</td>
    <td class="once" >&nbsp;</td>
  </tr>
  <tr>
  <td class="once" style="border-top: 1px solid; border-collapse: separate; border-spacing: 15px;">Comprador</td>
    <td class="once" style="border-top: 1px solid; border-collapse: separate; border-spacing: 15px;">Project Support</td>
    <td class="once" style="border-top: 1px solid; border-collapse: separate; border-spacing: 15px;">&nbsp;<br>&nbsp;</td>
  </tr>

  <tr>
  <td  colspan="3" style="height: 0.2em;">
  </td>
</tr>
</table>

<div >
<table class="font">
  <tr>
    <td width="15%" style="border-right: 1px solid; text-align: center;">PENALIZACION</td>
    <td>En caso de incumplimiento total o parcial de cualquiera de los plazos de entrega indicados en la presente orden de
      compra CONSERFLOW S.A de C.V. se reserva el derecho de aplicar una penalidad de demora del 2% del importe total del pedido por cada semana
      iniciada de retraso completa o no, con un límite máximo del 10% del importe total del Contrato.
      <br> LA RECEPCIÓN DE LA MERCANCÍA  SERÁ DE LUNES A VIERNES DE 8am - 6pm SABADO 8am - 1pm PREVIO AVISO AL CONTACTO DE LA COMPRA
    </td>
  </tr>
</table>
<table class="fontc"><p>
CONDICIONES COMERCIALES:
<br> a) El proveedor ha leído el contenido íntegro de esta Orden de Compra lo comprende, y reconoce que su aceptación expresa o el inicio de cualquier suministro de productos o prestación de servicios de conformidad con la misma, implicará la aceptación por parte del Proveedor de estos términos y condiciones.
<br>b) No se aceptará ningún bien que esté fabricado con un material diferente al especificado si no se tiene una autorización escrita por parte del comprador.                                                                                                                                                                                                                                                                     b) No se aceptará ningún bien que esté fabricado con un material diferente al especificado si no se tiene una autorización escrita por parte del comprador.
<br>C)El PROVEEDOR deberá notificar por escrito si hay algún requerimiento especial en el envío, empaque, manejo o almacenamiento de los bienes adquiridos para asegurar el buen estado de éstos. En caso de no hacerlo se puede hacer acreedor de cargos extras. Debe asumirse que el material suministrado se transportará y almacenará a la intemperie y sin protección extra al empaque con el que se recibió. El período de almacenamiento a contemplar no es mayor a seis meses. El vendedor pagará por los materiales dañados que resulten debido a empaque o marcado inadecuado
<br>d) Documentos minimos de embarque
<br>• Listas de Embarque.
<br>• Remisión del Transportista.
<br>• Documentos para importación (factura comercial, certificado de origen, certificado NAFTA).
<br>e)El Proveedor entregará dos (2) juegos impresos, un juego se enviara en atención al Personal de Calidad  y el otro se entregará a almacén.
</p></table>
</div>
</div>
</body>
</html>
