<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Orden de Compra</title>
  <style>
   .Table
    {
        display: table;
        width: 100%;
    }
    .Title
    {
        display: table-caption;
        text-align: center;
        font-weight: bold;
        font-size: larger;
    }
    .Heading
    {
        display: table-row;
        font-weight: bold;
        text-align: center;
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
      padding-top: 50px;
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
    .pd{
      font-size: 12px;
      font-family: Arial, Helvetica, sans-serif;
      text-align: justify;
    }
    .pdb{
      font-size: 12px;
      font-family: Arial, Helvetica, sans-serif;
    }
    .db{
      font-size: 12px;
      font-family: Arial, Helvetica, sans-serif;
      font-weight: bold;
    }
    ol.c {
      list-style-type: upper-latin;
      font-size: 10px;
      font-family: Arial, Helvetica, sans-serif;
      text-align: justify;
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
              <td style="border: 1px solid;">C??DIGO</td>
              <td style="border: 1px solid;">PCO-01/F-02</td>
            </tr>
            <tr>
              <td style="border: 1px solid;">REVISI??N</td>
              <td style="border: 1px solid;">00</td>
            </tr>
            <tr>
              <td style="border: 1px solid;">EMISI??N</td>
              <td style="border: 1px solid;">29.JUN.20</td>
            </tr>
          </table>
        </div>

    </div>
    <div class="Row">
        <div class="Cell">
        <p class="cons" style="padding-top: -30px;"><b> CONSERFLOW, S.A. DE C.V. </b> <br>
        <b>RFC:</b>&nbsp; CON19120226U2 <br>
        <b>DIRECCI??N:</b>&nbsp;Calle Del Mezquite Lote 5 Mza. 3, Col. <br>
                        Santa Clara. Parque Industrial Tehuacan-Miahuatl??n, <br>
                        Santiago Miahuatlan. C.P. 75820. Puebla, M??xico
                        </p>
        </div>
        <div class="Cell">
        <table class="ord">
            <tr>
              <th style="font-size: 18px;
      font-family: Arial, Helvetica, sans-serif;
      text-align: center; background-color: #d2caca"><b>ORDEN DE COMPRA</b> </th>
            </tr>
            <tr>
              <td>&nbsp;<b>N??MERO:</b> &nbsp;{{$numero_pedido}}</td>
           </tr>
            <tr>
              <td>&nbsp;<b>FECHA DE ORDEN:</b>&nbsp;{{$compra[0]->fecha_orden}} </td>
            </tr>
            <tr>
              <td>&nbsp;<b>PROYECTO:</b>&nbsp; {{$proyecto_->nombre_corto}}</td>
           </tr>
            <tr>
              <td>&nbsp;<b>COMPRADOR:</b> &nbsp;
                @if($compra[0]->fecha_orden > '2020-06-15')
                JOEL MACHORRO MARTINEZ
                @endif
                @if($compra[0]->fecha_orden < '2020-06-15')
                RODOLFO RODRIGUEZ ALVARADO
                @endif</td>
            </tr>
          </table>
        </div>

    </div>
  </div>
  <div class="Table">

    <div class="Row">
        <div class="Cell">
        <p class="pd"><b> PROVEEDOR:</b>&nbsp;{{$compra[0]->razonsocialp }} <br>
        <b>RFC:&nbsp;</b>{{$compra[0]->rfc}}
        <br>
        <b>DIRECCI??N:</b>&nbsp;{{$compra[0]->direccion }}
        <br>
        <b>ID:</b>&nbsp;{{str_pad($compra[0]->proveedor_id , 5, "0", STR_PAD_LEFT)}}</p>

        </div>
        <div class="Cell">
            <p class="pd"><b>DIRECCI??N DE ENTREGA:</b>
            Calle Del Mezquite Lote 5 Mza. 3, Col. Santa Clara. Parque Industrial Tehuacan-Miahuatl??n,
            Santiago Miahuatlan. C.P. 75820. Puebla, M??xico
            </p>
        </div>
    </div>
    <p width="100%" style="border-top: 1px solid #000;">&nbsp;</p>
</div>
      <table width="100%" class="tab">

        <tr>
          <td width="5%">Partida</td>
          <td width="10%">Cantidad</td>
          <td width="10%">Unidad</td>
          <td width="55%">Descripci??n</td>
          <td width="10%">Precio Unitario</td>
          <td width="10%">Total</td>
        </tr>
        @foreach($ordenesreq as $key => $comp)
        <tr>
          <td>{{$count}}</td>
          <td>{{$comp->cantidad}}</td>
          <td>{{$comp->unidadA}}</td>
          <td><b>
            {{$comp->comentario == null ? $comp->descripcionA : $comp->comentario}}
          </b></td>
          <td>{{$comp->precio_unitario}}   @if($compra[0]->moneda == 1)  USD
                            @elseif($compra[0]->moneda == 2)  MXN
                            @elseif($compra[0]->moneda == 3) EUR
                            @endif/1 PZA <br>
                            M??xico</td>
          <td>{{number_format((($comp->cantidad)*($comp->precio_unitario)),2)}}</td>
        </tr>
        {{$count +=1}}
        @endforeach
      </table>
      <p width="100%" style="border-top: 1px solid #000;">&nbsp;</p>
      <div style="page-break-inside: avoid;">
      <table class="taf">
        <tr>
          <td width="500px">&nbsp;</td>
          <td>Subtotal</td>
          <td style="text-align: right;">{{number_format($subtotal->subtotal, 2)}}</td>
        </tr>

        @if($compra[0]->descuento > 0)
        <tr>
          <td width="500px">&nbsp;</td>
           <td >Descuento: {{$compra[0]->descuento}} %</td>
            <td style="text-align: right;">{{number_format($subtotal_info,2)}}&nbsp;</td>
        </tr>
        <tr>
          <td width="500px">&nbsp;</td>
           <td>Subtotal: </td>
            <td style="text-align: right;">{{number_format($subtotal_info_dos,2)}}&nbsp;</td>
        </tr>
        @endif
        @if(count($impuestos) == 0)
        @else
        @foreach($arreglo as $impuesto)
        <tr>
        <td>&nbsp;</td>
        <td>{{strtoupper($impuesto['impuestos']->tipo)}} {{$impuesto['impuestos']->porcentaje}}%:</td>
        <td style="text-align: right;">{{number_format($impuesto['totalim'],2)}}</td>
        </tr>
        @endforeach
        @endif
        <tr>
        <td width="550px">&nbsp;<b>{{$valor_letras}}</b> </td>
        <td><b> Total</b></td>
        <td style="text-align: right;"><b>{{$total}}   @if($compra[0]->moneda == 1) USD
          @elseif($compra[0]->moneda == 2) MXN
          @elseif($compra[0]->moneda == 3) EUR
          @endif</b></td>
        </tr>
      </table>
    </div>
      <br>

  <div style="page-break-inside: avoid;">
    @if($compra[0]->observaciones)
    <table width="100%">
    <tr>
      <td class="db">OBSERVACIONES :</td>
      <td class="pdb">{{$compra[0]->observaciones}}</td>
      </td>
    </tr>

    </table>
    @endif

      <table width="100%">
      <tr>
        <td class="db">Banco Transferencia</td>
        <td class="pdb">{{isset($datos_bancarios_oc) == true ? $datos_bancarios_oc->banco : ''}}</td>
        <td class="db">Moneda de Pago</td>
        <td class="pdb">
          @if($compra[0]->moneda == 1)  USD
          @elseif($compra[0]->moneda == 2)  MXN
          @elseif($compra[0]->moneda == 3) EUR
          @endif
        </td>
      </tr>
      <tr>
        <td class="db">Titular de la cuenta</td>
        <td class="pdb">{{isset($datos_bancarios_oc) == true ? $datos_bancarios_oc->titular : ''}}</td>
        <td class="db">Tipo de Cambio</td>
        <td class="pdb">{{$compra[0]->tipocambiop == NULL ? 'N/A' : $compra[0]->tipocambiop}}</td>
      </tr>
      <tr>
        <td class="db">Convenio CIEC</td>
        <td class="pdb">{{$compra[0]->cie == NULL ? 'N/A' : $compra[0]->cie}}</td>
        <td class="db">Sucursal</td>
        <td class="pdb">{{$compra[0]->sucursal == NULL ? 'N/A' : $compra[0]->sucursal }}</td>
      </tr>
      <tr>
        <td class="db">N??mero de Cuenta</td>
        <td class="pdb">{{isset($datos_bancarios_oc) == true ? $datos_bancarios_oc->cuenta : ''}}</td>
        <td class="db">Referencia</td>
        <td class="pdb">{{$compra[0]->referencia == NULL ? 'N/A' : $compra[0]->referencia}}</td>
      </tr>
      <tr>
      <td class="db">Clabe</td>
      <td class="pdb">{{isset($datos_bancarios_oc) == true ? $datos_bancarios_oc->clabe : ''}}</td>
      <td colspan="2"></td>
      </tr>
      </table>
      </div>

      <div style="page-break-inside: avoid;">
    <p style="font-size: 16px;
      font-family: Arial, Helvetica, sans-serif;
      text-align: center;">
      <b>Este pedido se ha creado electr??nicamente y ha sido aprobado por las siguientes personas:</b></p>
      <table width="100%">
        <tr>
          <td class="pdb" width="40%"><b>Proveedor:</b> </td>
          <td class="pdb" width="30%"><b>Autoriza</b></td>
          <td class="pdb" width="30%"><b>Compras</b> </td>
        </tr>
        <tr>
          <td></td>
          <td>
            @if($compra[0]->fecha_orden > '2020-02-02')
            <img src="img/firma.png"  width="150px">
            @endif
          </td>
          <td>
            @if($compra[0]->fecha_orden > '2020-06-15')
            <img src="img/joel.png"  width="150px">
            @endif
            @if($compra[0]->fecha_orden < '2020-06-15')
            <img src="img/reno.png"  width="150px">
            @endif
          </td>
        </tr>
        <tr>
          <td class="pdb">{{$compra[0]->razonsocialp }}</td>
          <td class="pdb">RAMON CRUZ MARTINEZ</td>
          <td class="pdb">
            @if($compra[0]->fecha_orden > '2020-06-15')
            JOEL MACHORRO MARTINEZ
            @endif
            @if($compra[0]->fecha_orden < '2020-06-15')
            RODOLFO RODRIGUEZ ALVARADO
            @endif
          </td>
        </tr>
      </table>
<div width="30px;">&nbsp;</div>

        <div >
      <p style="font-size: 12px;
      font-family: Arial, Helvetica, sans-serif;
      text-align: justify; padding-left: 10px;">CONDICIONES COMERCIALES</p>
              <ol class="c">
                <li>El Proveedor ha le??do el contenido ??ntegro de esta Orden de Compra lo comprende, y reconoce que su adaptaci??n expresa o el inicio de cualquier suministro de productos o prestaci??n de servicios de conformidad con la misma, implicar?? la aceptaci??n por parte del Proveedor de estos t??rminos y condiciones</li>
                <li>No se aceptar?? ning??n bien que este fabricado con un material diferente al especificado si no se tiene una autorizaci??n escrita por parte del comprador</li>
                <li>El PROVEEDOR deber?? notificar por escrito si hay alg??n requerimiento especial en el env??o, empaque, manejo o almacenamiento de los bienes adquiridos para asegurar el buen estado de ??stos. En caso de no hacerlo se puede hacer acreedor de cargos extras. Debe asumirse que el material suministrado se transportar?? y almacenar?? a la interperie y sin protecci??n extra al empaque con el que se recibi??. El periodo de almacenamiento a contemplar no es mayor a seis meses. El vendedor pagar?? por los materiales da??ados que resulten debido a empaque o marcado inadecuado</li>
                <li>La entrega de la mercancia debe realizarse en la direcci??n que indica el pedido. Deno ser as?? su pago ser?? retrasado.</li>
                <li>Mercanc??a no pedida o defectuosa ser?? devuelta por cuenta y riesgo del proveedor en forma autom??tica con reporte al comprador</li>
                <li>Documentos minimos de embarque: Lista de Embarque, Remisi??n del Transportista, Documentos para importaci??n (factura comercial, certificado de origen, certificado NAFTA)</li>
                <li>Todo pedido recibido fuera de las fechas establecidas en el mismo, se devolver?? por cuenta y riesgo del proveedor,A menos que el proveedor le notifique al Comprador del embarque extempor??neo y ??ste lo acepte.</li>
                <li>El proveedor entregar?? la documentaci??n necesaria al ??rea de Almac??n y Control de Calidad</li>
              </ol>
        </div>
      </div>


  </body>
</html>
