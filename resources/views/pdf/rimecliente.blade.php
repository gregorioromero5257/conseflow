<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PCC-01/F-03 REPORTE DE INSPECCIÓN DE EQUIPOS E INSTRUMENTOS PROPIEDAD DEL CLIENTE</title>

  <head>
    <style>
      /** Define the margins of your page **/
      @page {
        margin-top: 3cm;
        margin-left: 2cm;
        margin-right: 2cm;
        margin-bottom: 2cm;
      }

      header {
        position: fixed;
        top: -90px;
        left: 0px;
        right: 0px;
        height: 20px;

        /** Extra personal styles **/
        /* background-color: #03a9f4;
                color: white;
                text-align: center;
                line-height: 35px; */
      }

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

      .letter {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 16px;
      }

      .vert {
        font-size: 10px;
        white-space: pre-line;
        g-origin: 40% 40%;
        -webkit-transform: rotate(270deg);
        -moz-transform: rotate(270deg);
        -ms-transform: rotate(270deg);
        -o-transform: rotate(270deg);
        transform: rotate(270deg);
      }

      .articulo {
        font-size: 10px;
      }
    </style>
  </head>

<body>
  <!-- Define header and footer blocks before your content -->
  <header>
    <table width="100%" style="border-collapse: collapse; border: 1px solid; font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            text-align: center;">
      <tr>
        <th colspan="4" style="border: 1px solid; ">
          <div style="color: #4472C4;"> CONSERFLOW S.A. DE C.V.</div>
        </th>
      </tr>
      <tr>
        <td rowspan="3" width="20%"><img src="img/conserflow.png" width="120"></td>
        <td rowspan="3" style="border: 1px solid; text-align: center;" width="55%"><b>REPORTE DE INSPECCIÓN DE EQUIPOS E INSTRUMENTOS PROPIEDAD DEL CLIENTE</b> </td>
        <td style="border: 1px solid; text-align: center;" width="10%">CÓDIGO</td>
        <td style="border: 1px solid; text-align: center;" width="15%">PCC-01/F-03</td>
      </tr>
      <tr>
        <td style="border: 1px solid; text-align: center;">REVISIÓN</td>
        <td style="border: 1px solid; text-align: center;">00</td>
      </tr>
      <tr>
        <td style="border: 1px solid; text-align: center;">EMISIÓN</td>
        <td style="border: 1px solid; text-align: center;">01.ABR.20</td>
      </tr>
    </table>

  </header>

  <footer>

    <table width="100%" style="font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;">
      <tr>
        <td width="30%"> CONSERFLOW S.A. DE C.V. </td>
        <td width="40%">&nbsp;</td>
        <td width="30%">Página <b>1</b> de <b>1</b></td>
      </tr>
    </table>
  </footer>

  <main>
    <div style="height: 60px;">&nbsp;</div>

    <table width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;">

      <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="10%">Proyecto</td>
        <td style="border: 1px solid; text-align: center;" width="30%">{{$rime->proyecto}}</td>
        <td rowspan="2" style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="10%">Nombre del Cliente </td>
        <td rowspan="2" style="border: 1px solid; text-align: center;" width="30%">{{$rime->cliente}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="10%">Fecha</td>
        <td style="border: 1px solid; text-align: center;" width="10%">{{$fecha}}</td>
      </tr>
      <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;">N° de Proyecto</td>
        <td style="border: 1px solid; text-align: center;">{{$rime->no_proyecto}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;">Número de Reporte</td>
        <td style="border: 1px solid; text-align: center;">{{$rime->folio}}</td>
      </tr>

    </table>

    <div style="height: 30px;">&nbsp;</div>

    <table width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;">
      <tr>
        <td colspan="10" style="border: 1px solid; background-color: #0070C0; text-align: center;" width="48%">
          <div style="color:white; "><b>DATOS DE EQUIPOS E INSTRUMENTOS</b></div>
        </td>
        <td colspan="2" style="border: 1px solid; background-color: #0070C0; text-align: center;" width="10%">
          <div style="color:white; "><b>TIPO</b></div>
        </td>
        <td colspan="8" style="border: 1px solid; background-color: #0070C0; text-align: center;" width="35%">
          <div style="color:white; "><b>DATOS DE EQUIPOS E INSTRUMENTOS</b></div>
        </td>
        <td rowspan="2" style="border: 1px solid; background-color: #0070C0; text-align: center;" width="7%">
          <div style="color:white; "><b>OBSERVACIONES</b></div>
        </td>
      </tr>
      <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="2%" height="110px">ITEM</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="2%">PART. </td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="2%">CANT. </td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="6%">DESCRIPCIÓN DEL MATERIAL</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="5%">MARCA</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="5%">MODELO</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="5%">TAG</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="5%">NO. SERIE</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="5%">HT#</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="6%">NO. CERTIFICADO </td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="4%">
          <div class="vert">Equipo</div>
        </td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="4%">
          <div class="vert">Instrumento</div>
        </td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="4%">
          <div class="vert">Se reciben certificados de calibración</div>
        </td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="4%">
          <div class="vert">Se recibieron manuales de instalación y/o mantenimiento</div>
        </td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="4%">
          <div class="vert">Se recibieron partes de repuesto</div>
        </td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="4%">
          <div class="vert">Se recibieron kits de instalación</div>
        </td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="4%">
          <div class="vert">Presenta daños fisicos</div>
        </td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="4%">
          <div class="vert">Embalaje </div>
        </td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="4%">
          <div class="vert">Almacenamiento</div>
        </td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="4%">
          <div class="vert">Conservacion </div>
        </td>
      </tr>

      @foreach ($inspecciones as $i=> $partida)
      <tr>
        <td style="border: 1px solid;  text-align: center;">{{$i+1}}</td>
        <td style="border: 1px solid;  text-align: center;">{{$partida->partida}}</td>
        <td style="border: 1px solid;  text-align: center;">{{$partida->cantidad}}</td>
        <td class="articulo" style="border: 1px solid;  text-align: center;">{{$partida->articulo}}</td>
        <td style="border: 1px solid;  text-align: center;">{{$partida->marca}}</td>
        <td style="border: 1px solid;  text-align: center;">{{$partida->modelo}}</td>
        <td style="border: 1px solid;  text-align: center;">{{$partida->tag}}</td>
        <td style="border: 1px solid;  text-align: center;">{{$partida->no_serie}}</td>
        <td style="border: 1px solid;  text-align: center;">{{$partida->colada}}</td>
        <td style="border: 1px solid;  text-align: center;">{{$partida->no_certificado}}</td>
        <td style="border: 1px solid;  text-align: center;">{{$partida->tipo_equipo}}</td>
        <td style="border: 1px solid;  text-align: center;">{{$partida->tipo_inssto}}</td>
        <td style="border: 1px solid;  text-align: center;">{{$partida->inspeccion_certificado}}</td>
        <td style="border: 1px solid;  text-align: center;">{{$partida->inspeccion_manuales}}</td>
        <td style="border: 1px solid;  text-align: center;">{{$partida->inspeccion_respuesto}}</td>
        <td style="border: 1px solid;  text-align: center;">{{$partida->inspeccion_kids}}</td>
        <td style="border: 1px solid;  text-align: center;">{{$partida->inspeccion_dagnos}}</td>
        <td style="border: 1px solid;  text-align: center;">{{$partida->inspeccion_embalaje}}</td>
        <td style="border: 1px solid;  text-align: center;">{{$partida->inspeccion_almacenamiento}}</td>
        <td style="border: 1px solid;  text-align: center;">{{$partida->inspeccion_conservacion}}</td>
        <td style="border: 1px solid;  text-align: center;">{{$partida->observaciones}}</td>
      </tr>
      @endforeach

    </table>
    <div style="height: 60px;">&nbsp;</div>
    <div style="page-break-inside: avoid;">

      <table width="100%" style="font-family: Arial, Helvetica, sans-serif;
            font-size: 12px; text-align: center;">

        <tr>
          <td width="5%">&nbsp;</td>
          <td width="30%">REVISÓ</td>
          <td width="10%">&nbsp;</td>
          <td width="30%">ENTERADO</td>
          <td width="15%">&nbsp;</td>
          <td width="30%">APROBÓ</td>
        </tr>
        
        <tr>
          <td width="5%">&nbsp;</td>
          <td width="30%">{{$rime->reviso}}</td>
          <td width="10%">&nbsp;</td>
          <td width="30%">{{$rime->enterado}}</td>
          <td width="15%">&nbsp;</td>
          <td width="30%">{{$rime->aprobo}}</td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
        </tr>
        <tr>
          <td></td>
          <td style="border-top: 1px solid;">INSPECTOR DE CONTROL DE CALIDAD
            <br>NOMBRE Y FIRMA
          </td>
          <td></td>
          <td style="border-top: 1px solid;">SUPERVISOR DE PROYECTO
            <br>NOMBRE Y FIRMA
          </td>
          <td></td>
          <td style="border-top: 1px solid;">COORDINADOR DE CONTROL DE CALIDAD
            <br>NOMBRE Y FIRMA
          </td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </div>
  </main>
</body>

</html>