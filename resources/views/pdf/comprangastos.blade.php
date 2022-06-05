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
              <td style="border: 1px solid;">CÓDIGO</td>
              <td style="border: 1px solid;">PCO-01/F-02</td>
            </tr>
            <tr>
              <td style="border: 1px solid;">REVISIÓN</td>
              <td style="border: 1px solid;">00</td>
            </tr>
            <tr>
              <td style="border: 1px solid;">EMISIÓN</td>
              <td style="border: 1px solid;">15.FEB.20</td>
            </tr>
          </table>
        </div>

    </div>
    <div class="Row">
        <div class="Cell">
        <p class="cons" style="padding-top: -30px;"><b> CONSERFLOW, S.A. DE C.V. </b> <br>
        <b>RFC:</b>&nbsp; CON19120226U2 <br>
        <b>DIRECCIÓN:</b>&nbsp;Calle Del Mezquite Lote 5 Mza. 3, Col. <br>
                        Santa Clara. Parque Industrial Tehuacan-Miahuatlán, <br>
                        Santiago Miahuatlan. C.P. 75820. Puebla, México
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
              <td>&nbsp;<b>NÚMERO:</b> &nbsp;{{str_replace(['OC-CONSERFLOW-020-'],'',$ocg->folio)}}</td>
           </tr>
            <tr>
              <td>&nbsp;<b>FECHA DE ORDEN:</b>&nbsp;{{$fecha_oc}} </td>
            </tr>
            <tr>
              <td>&nbsp;<b>PROYECTO:</b>&nbsp; {{$ocg->proyecto}}</td>
           </tr>
            <tr>
              <td>&nbsp;<b>COMPRADOR:</b> &nbsp;
                RODOLFO RODRIGUEZ ALVARADO
                </td>
            </tr>
          </table>
        </div>

    </div>
  </div>
  <div class="Table">

    <div class="Row">
        <div class="Cell">
        <p class="pd"><b> PROVEEDOR:</b>&nbsp;{{$ocg->proveedor_acreedor}} <br>
        <b>RFC:&nbsp;</b>{{$goc->rfc_e}}
        <br>

        </div>
        <div class="Cell">
            <p class="pd"><b>DIRECCIÓN DE ENTREGA:</b>
            Calle Del Mezquite Lote 5 Mza. 3, Col. Santa Clara. Parque Industrial Tehuacan-Miahuatlán,
            Santiago Miahuatlan. C.P. 75820. Puebla, México
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
          <td width="55%">Descripción</td>
          <td width="10%">Precio Unitario</td>
          <td width="10%">Total</td>
        </tr>
        @foreach($coc as $key => $comp)
        <tr>
          <td>{{$key + 1}}</td>
          <td>{{$comp->cantidad}}</td>
          <td>{{$comp->unidad_n}}</td>
          <td><b>{{$comp->descripcion}}</b></td>
          <td>{{number_format($comp->valorunitario,2)}}
             {{$goc->moneda}}/1 PZA <br>
                            México
                          </td>
          <td>{{number_format($comp->importe,2)}}</td>
        </tr>
        @endforeach
      </table>
      <p width="100%" style="border-top: 1px solid #000;">&nbsp;</p>
      <div style="page-break-inside: avoid;">
      <table class="taf">
        <tr>
          <td width="500px">&nbsp;</td>
          <td>Subtotal</td>
          <td style="text-align: right;">{{number_format($goc->subtotal, 2)}}</td>
        </tr>


        <tr>
          <td width="500px">&nbsp;</td>
           <td >Descuento: </td>
            <td style="text-align: right;">{{number_format($goc->descuento,2)}}&nbsp;</td>
        </tr>


        @foreach($ioc as $kei => $vi)
        <tr>
        <td>&nbsp;</td>
        <td>
          @if($vi->impuesto == 001)
          ISR {{number_format($vi->tasaocuota * 100)}}%:</td>
          @elseif($vi->impuesto == 002)
          IVA {{number_format($vi->tasaocuota * 100)}}%:</td>
          @elseif($vi->impuesto == 003)
          IEPS {{number_format($vi->tasaocuota * 100)}}%:</td>
          @endif
        </td>
        <td style="text-align: right;">{{number_format($vi->importe,2)}}</td>
        </tr>
        @endforeach
        <tr>
        <td width="550px">&nbsp;<b>{{$valor_letras}}</b> </td>
        <td><b> Total</b></td>
        <td style="text-align: right;"><b>{{$goc->total}}

        </b></td>
        </tr>
      </table>
    </div>
      <br>

  <div style="page-break-inside: avoid;">


      <table width="100%">
      <tr>
        <td class="db">Banco Transferencia</td>
        <td class="pdb">&nbsp;</td>
        <td class="db">Moneda de Pago</td>
        <td class="pdb">
            {{$ocg->moneda}}
        </td>
      </tr>
      <tr>
        <td class="db">Titular de la cuenta</td>
        <td class="pdb">&nbsp;</td>
        <td class="db">Tipo de Cambio</td>
        <td class="pdb">{{$goc->tipo_cambio}}</td>
      </tr>
      <tr>
        <td class="db">Convenio CIEC</td>
        <td class="pdb">&nbsp;</td>
        <td class="db">Sucursal</td>
        <td class="pdb">&nbsp;</td>
      </tr>
      <tr>
        <td class="db">Número de Cuenta</td>
        <td class="pdb">&nbsp;</td>
        <td class="db">Referencia</td>
        <td class="pdb">&nbsp;</td>
      </tr>
      <tr>
      <td class="db">Clabe</td>
      <td class="pdb">&nbsp;</td>
      <td colspan="2"></td>
      </tr>
      </table>
      </div>

      <div style="page-break-inside: avoid;">
    <p style="font-size: 16px;
      font-family: Arial, Helvetica, sans-serif;
      text-align: center;">
      <b>Este pedido se ha creado electrónicamente y ha sido aprobado por las siguientes personas:</b></p>
      <table width="100%">
        <tr>
          <td class="pdb" width="40%"><b>Proveedor:</b> </td>
          <td class="pdb" width="30%"><b>Autoriza</b></td>
          <td class="pdb" width="30%"><b>Compras</b> </td>
        </tr>
        <tr>
          <td></td>
          <td>

          </td>
          <td>

          </td>
        </tr>
        <tr>
          <td class="pdb"></td>
          <td class="pdb">RAMON CRUZ MARTINEZ</td>
          <td class="pdb">
            RODOLFO RODRIGUEZ ALVARADO
          </td>
        </tr>
      </table>
<div width="30px;">&nbsp;</div>

        <div >
      <p style="font-size: 12px;
      font-family: Arial, Helvetica, sans-serif;
      text-align: justify; padding-left: 10px;">CONDICIONES COMERCIALES</p>
              <ol class="c">
                <li>El Proveedor ha leído el contenido íntegro de esta Orden de Compra lo comprende, y reconoce que su adaptación expresa o el inicio de cualquier suministro de productos o prestación de servicios de conformidad con la misma, implicará la aceptación por parte del Proveedor de estos términos y condiciones</li>
                <li>No se aceptará ningún bien que este fabricado con un material diferente al especificado si no se tiene una autorización escrita por parte del comprador</li>
                <li>El PROVEEDOR deberá notificar por escrito si hay algún requerimiento especial en el envío, empaque, manejo o almacenamiento de los bienes adquiridos para asegurar el buen estado de éstos. En caso de no hacerlo se puede hacer acreedor de cargos extras. Debe asumirse que el material suministrado se transportará y almacenará a la interperie y sin protección extra al empaque con el que se recibió. El periodo de almacenamiento a contemplar no es mayor a seis meses. El vendedor pagará por los materiales dañados que resulten debido a empaque o marcado inadecuado</li>
                <li>La entrega de la mercancia debe realizarse en la dirección que indica el pedido. Deno ser así su pago será retrasado.</li>
                <li>Mercancía no pedida o defectuosa será devuelta por cuenta y riesgo del proveedor en forma automática con reporte al comprador</li>
                <li>Documentos minimos de embarque: Lista de Embarque, Remisión del Transportista, Documentos para importación (factura comercial, certificado de origen, certificado NAFTA)</li>
                <li>Todo pedido recibido fuera de las fechas establecidas en el mismo, se devolverá por cuenta y riesgo del proveedor,A menos que el proveedor le notifique al Comprador del embarque extemporáneo y éste lo acepte.</li>
                <li>El proveedor entregará la documentación necesaria al área de Almacén y Control de Calidad</li>
              </ol>
        </div>
      </div>


  </body>
</html>
