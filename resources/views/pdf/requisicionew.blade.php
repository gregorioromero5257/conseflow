<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Entrada</title>
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
              <td style="border: 1px solid;">PCO-01/F-01</td>
            </tr>
            <tr>
              <td style="border: 1px solid;">REVISIÓN</td>
              <td style="border: 1px solid;">00</td>
            </tr>
            <tr>
              <td style="border: 1px solid;">EMISIÓN</td>
              <td style="border: 1px solid;">29.JUN.20</td>
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
              text-align: center; background-color: #d2caca"><b>REQUISICIÓN DE COMPRA</b> </th>
            </tr>
            <tr>
              <td></td>
            </tr>
            <tr>
              <td>&nbsp;&nbsp;<b>NÚMERO:</b>&nbsp;{{$numero_pedido}}</td>
            </tr>
            <tr>
              <td>&nbsp;&nbsp;<b>PROYECTO:</b>&nbsp;{{$nombre_corto_proyecto}}</td>
            </tr>
            <tr>
              <td>&nbsp;&nbsp;<b>FECHA DE SOLICITUD:</b>&nbsp;{{strtoupper($fechafinal)}} </td>
            </tr>
            <tr>
              <td>&nbsp;&nbsp;<b>ÁREA:</b>&nbsp;{{$requisicion->areasolicitante}} </td>
            </tr>
          

            <tr>
              <td></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <p width="100%" style="border-top: 1px solid #000;">&nbsp;</p>

    <table width="100%" class="tab">

      <tr>
        <td width="5%"><b>Partida</b> </td>
        <td width="7%"><b>Cantidad</b></td>
        <td><b>Compras</b></td>
        <td><b>Almacén</b></td>
        <td width="10%"><b>U.M.</b></td>
        <td width="55%"><b>Descripción</b></td>
        <td width="">Marca</td>
        <td width="">Documento Requerido</td>
        <td width="">Fecha Requerida</td>
      </tr>
      @foreach($partidas as $partida)
      <tr>
        <td>{{$count}}</td>
        <td>{{$partida['par']->cantidad}}</td>
        <td>{{$partida['par']->cantidad_compra}}</td>
        <td>{{$partida['par']->cantidad_almacen}}</td>
        <td>{{$partida['par']->unidadarticulo != null ? $partida['par']->unidadarticulo : $partida['par']->unidadservicio}}</td>
        <td><b>
          {{$partida['par']->descripcionarticulo != null ? $partida['par']->descripcionarticulo : $partida['par']->descripcionservicio}}&nbsp;{{ ($partida['par']->comentario != null ? $partida['par']->comentario : '')}}
        </b></td>
        <td>{{$partida['par']->marcaarticulo != null ? $partida['par']->marcaarticulo : $partida['par']->marcaservicio}}</td>
        <td>
          @foreach($partida['doc'] as $doc)
          {{$doc->nombre_corto}}
          @endforeach
        </td>
        <td>{{$partida['par']->fecha_requerido}}</td>
      </tr>

      {{$count +=1}}
      @endforeach
    </table>

    <p width="100%" style="border-top: 1px solid #000;">&nbsp;</p>

    <div style="page-break-inside: avoid; width: 100%; bottom: 0px;">
      <p style="font-size: 16px;
      font-family: Arial, Helvetica, sans-serif;
      text-align: center;">
      <b>Documento creado electrónicamente y aprobado por las siguientes personas:</b>
    </p>


    <table width="100%" style="text-align: center;">
      <tr>

        {{$solicita = 'administrativos/'.$requisicion->solicita_empleado_id.'.png'}}
        <td class="pdb" width="25%">
          <b>Solicita:</b>
          <br>
          @if(file_exists($solicita))
          <img src="administrativos/{{$requisicion->solicita_empleado_id}}.png" width="150px" height="100px">
          @endif
          {{$requisicion->nombres}}
        </td>

        <td width="10%">&nbsp;</td>

        {{$autoriza = 'administrativos/'.$requisicion->autoriza_empleado_id.'.png'}}
        <td class="pdb" width="25%">
          <b>Autoriza:</b>
          <br>
          @if($requisicion->estado_id == 5 || $requisicion->estado_id == 6)
          @if(file_exists($autoriza))
          <img src="administrativos/{{$requisicion->autoriza_empleado_id}}.png" width="150px" height="100px" >
          @endif
          @endif
          {{$requisicion->nombrea}}
        </td>

        <td width="10%">&nbsp;</td>

        {{$almacen = 'administrativos/'.$requisicion->almacen_empleado_id.'.png'}}
        <td class="pdb" width="25%">
          <b>Recibe Almacén:</b>
          <br>
          @if(file_exists($almacen))
          <img src="administrativos/{{$requisicion->almacen_empleado_id}}.png" width="150px" height="100px" >
          @endif
          {{$requisicion->nombreal}}
        </td>

        {{$compras = 'administrativos/'.$requisicion->recibe_empleado_id.'.png'}}
        <td class="pdb" width="25%">
          <b>Recibe Compras:</b>
          <br>
          @if(file_exists($compras))
          <img src="administrativos/{{$requisicion->recibe_empleado_id}}.png" width="150px" height="100px" >
          @endif
          {{$requisicion->nombrer}} </td>
        </tr>

        </table>
      </div>
      <script type="text/php">
      if (isset($pdf)) {
        $text = "PAGINA {PAGE_NUM} DE {PAGE_COUNT}";
        $size = 8;
        $color = #2F86DE;
        $font = $fontMetrics->getFont("Arial");
        $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
        $x = ($pdf->get_width() - $width) / 1;
        $y = $pdf->get_height() - 22;
        $pdf->page_text($x, $y, $text, $font, $size, $color);
      }
    </script>
  </body>
  </html>
