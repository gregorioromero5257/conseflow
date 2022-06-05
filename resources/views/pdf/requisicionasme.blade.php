<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>EXHIBIT 3  Material requisition SEC 19...</title>
<style media="screen">

@page {
        margin-top: 3cm;
        margin-left: 1cm;
        margin-right: 1cm;
        margin-bottom: 2cm;

        }

    header {

        position: fixed;
        top: -100px;
        left: 0px;
        right: 0px;
        height: 20px;
        }
    footer {
        position: fixed;
        bottom: -40px;
        left: 0px;
        right: 0px;
        height: 20px;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 8px;
            }
    bottom-right {
    content: counter(page) " of " counter(pages);
            }
    @bottom-right {
            content: counter(page) " of " counter(pages);
            }

            .custom-footer-page-number:after {
                content: counter(page);
                    }
        .pagenum:before {
            content: counter(page);
        }



        .letradostabla{
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
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
        .letter{
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        }
        .hh{
        font-family: Arial, Helvetica, sans-serif;
        font-size: 8px;
        }



        /* Elemento Radio, cuando está marcado */
        input[type="radio"]:checked {
          box-shadow: 0 0 0 3px orange;
        }

</style>

</head>
<body >

<header>
<table width="100%" style="border-collapse: collapse; border: 2px solid; font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            text-align: center;">
          <tr>
          <th colspan="4" style="border: 1px solid; "><div style="color: #0070C0;"> CONSERFLOW S.A. DE C.V..</div></th>
          </tr>
          <tr>
          <td rowspan="3" width="20%" style="border: 1px solid;"><img src="img/conserflow.png" width="120"></td>
          <td rowspan="3" style="border: 1px solid; text-align: center;" width="55%"><b>REQUISICIÓN DE MATERIAL</b> <br> (MATERIAL REQUISITION) </td>
          <td style="border: 1px solid; text-align: center;" width="10%"><div  style="font-weight: bold;"> EXHIBIT </div></td>
          <td style="border: 1px solid; text-align: center;" width="15%"><div  style="">03 </div></td>
          </tr>
          <tr>

          <td style="border: 1px solid; text-align: center;"><div  style="font-weight: bold;"> REVISION</div></td>
          <td style="border: 1px solid; text-align: center;"><div  style=""> 00</div></td>
          </tr>
          <tr>
          <td style="border: 1px solid; text-align: center;"><div  style="font-weight: bold;"> EMISION</div></td>
          <td style="border: 1px solid; text-align: center;"><div  style=""> 05.APR.21</div></td>
          </tr>
          <div class="custom-footer-page-number" style="text-align: right; font-weight:bold;">PAGE</div>
        </table>
  </header>

  <footer>
      <table width="100%" style="color: #0070C0; font-weight: bold;">
        <tr>
          <td> CONSERFLOW S.A. DE C.V.</td>
          <td>
              SECTION 19 EXHIBIT 3
          </td>
        </tr>
      </table>
  </footer>
  <main>
      <table width="100%" style="border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; font-size: 8px; text-align: center; border: 2px solid;">
        <tr>
        <td style="border: 1px solid; text-align: center;  background-color: #BFBFBF; font-weight:bold;" width="10%"><b>SOLICITADO POR</b> <br>REQUIRED BY</td>
        <td style="border: 1px solid; text-align: center;" width="15%">&nbsp;{{$requisicion->nombres}}</td>
        <td style="border: 1px solid; text-align: center;  background-color: #BFBFBF; font-weight:bold;" width="10%"><b>DEPARTAMENTO</b><br>DEPARTMENT</td>
        <td style="border: 1px solid; text-align: center;" width="15%">&nbsp;{{$requisicion->areasolicitante}}</td>
        <td style="border: 1px solid; text-align: center;  background-color: #BFBFBF; font-weight:bold;" width="10%"><b>REQUISICIÓN</b> <br>REQUISITION</td>
        <td style="border: 1px solid; text-align: center;" width="15%">&nbsp;{{$requisicion->folio}}</td>
        <td style="border: 1px solid; text-align: center;  background-color: #BFBFBF; font-weight:bold;" width="10%"><b>FECHA</b><br>DATE</td>
        <td style="border: 1px solid; text-align: center;" width="15%">&nbsp;{{strtoupper($fechafinal)}}</td>
        </tr>
      </table>
        <br>
        <table width="100%" style="border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; font-size: 8px; text-align: center; border: 2px solid;">
        <tr>
        <td style="border: 1px solid; text-align: center;  background-color: #BFBFBF; font-weight:bold;" width="10%"><b>CLIENTE</b> <br>CUSTOMER</td>
        <td style="border: 1px solid; text-align: center;" width="20%">&nbsp;{{$proyecto_data->nombre_cliente}}</td>
        <td style="border: 1px solid; text-align: center;  background-color: #BFBFBF; font-weight:bold;" width="15%"><b>UBICACIÓN DEL PROYECTO</b><br>PROJECT LOCATION</td>
        <td style="border: 1px solid; text-align: center;" width="20%">&nbsp;{{$proyecto_data->ciudad}}</td>
        <td style="border: 1px solid; text-align: center;  background-color: #BFBFBF; font-weight:bold;" width="20%"><b>CONTRATO NÚMERO / ORDEN DE TRABAJO</b> <br>CONTRACT NUMBER / JOB ORDER</td>
        <td style="border: 1px solid; text-align: center;" width="20%">&nbsp;{{$proyecto_data->clave}}</td>
        </tr>
        <tr>
        <td style="border: 1px solid; text-align: center;  background-color: #BFBFBF; font-weight:bold;" width="10%"><b>EQUIPO</b> <br>EQUIPMENT</td>
        <td style="border: 1px solid; text-align: center;" width="20%">&nbsp;</td>
        <td style="border: 1px solid; text-align: center;  background-color: #BFBFBF; font-weight:bold;" width="15%"><b>DIBUJO NÚM.</b><br>DRAWING NO.</td>
        <td style="border: 1px solid; text-align: center;" width="20%">&nbsp;</td>
        <td style="border: 1px solid; text-align: center;  background-color: #BFBFBF; font-weight:bold;" width="20%"><b>FECHA REQUERIDA DE ENTREGA</b> <br>REQUIRED DELIVERED DATE</td>
        <td style="border: 1px solid; text-align: center;" width="20%">&nbsp;{{$partidas[0]['par']['fecha_requerido']}}</td>
        </tr>
      </table>
        <br>
        <table  width="100%" style="border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; font-size: 8px; text-align: center; border: 2px solid;">
        <tr>
        <td rowspan="2" style="border: 1px solid; text-align: center;  background-color: #0070C0; font-weight:bold;" width="10%"><div style="color:white;"><b>NUMERO DE PARTE </b> <br>ITEM</div></td>
        <td colspan="2" style="border: 1px solid; text-align: center;  background-color: #0070C0; font-weight:bold;" width="20%"><div style="color:white;"><b>REQUERIDO </b> <br>REQUIRED UNITS</div></td>
        <td style="border: 1px solid; text-align: center;  background-color: #0070C0; font-weight:bold;" width="50%"><div style="color:white;"><b>CONCEPTO </b> <br>CONCEPT</div></td>
        <td rowspan="2" style="border: 1px solid; text-align: center;  background-color: #0070C0; font-weight:bold;" width="10%"><div style="color:white;"><b>ESPECIFICACIÓN </b> <br>SPECIFICACTION</div></td>
        <td rowspan="2" style="border: 1px solid; text-align: center;  background-color: #0070C0; font-weight:bold;" width="10%"><div style="color:white;"><b>OBSERVACIONES</b> <br>REMARKS</div></td>
        </tr>
        <tr>
        <td style="border: 1px solid; text-align: center;  background-color: #BFBFBF; font-weight:bold;" width=""><b>CANTIDAD </b> <br>QUANTITY</td>
        <td style="border: 1px solid; text-align: center;  background-color: #BFBFBF; font-weight:bold;" width=""><b>UNIDAD </b> <br>UNIT</td>
        <td style="border: 1px solid; text-align: center;  background-color: #BFBFBF; font-weight:bold;" width=""><b>DESCRIPCION DE LAS PARTES </b> <br>PARTS DESCRIPTION</td>
        </tr>

        @foreach($partidas as $partida)
        <tr>
        <td style="border: 1px solid; text-align: center;">&nbsp;</td>
        <td style="border: 1px solid; text-align: center;">&nbsp;{{$partida['par']->cantidad}}</td>
        <td style="border: 1px solid; text-align: center;">&nbsp;{{$partida['par']->unidadarticulo != null ? $partida['par']->unidadarticulo : $partida['par']->unidadservicio}}</td>
        <td style="border: 1px solid; text-align: center;">&nbsp;{{$partida['par']->descripcionarticulo != null ? $partida['par']->descripcionarticulo : $partida['par']->descripcionservicio}}&nbsp;{{ ($partida['par']->comentario != null ? $partida['par']->comentario : '')}}</td>
        <td style="border: 1px solid; text-align: center;">&nbsp;</td>
        <td style="border: 1px solid; text-align: center;">&nbsp;</td>
        </tr>
        @endforeach

        </table>
        <br>
        <table width="100%" style="border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; font-size: 8px; text-align: center; border: 2px solid;">
        <tr>
        <td style="border: 1px solid; text-align: center;  background-color: #BFBFBF; font-weight:bold;" width="10%"><b>NOTAS </b> <br>NOTES</td>
        <td style="border: 1px solid; text-align: center;" width="90%">&nbsp;</td>
        </tr>
        </table>
        <br>
      <table width="100%" style=" font-family: Arial, Helvetica, sans-serif; font-size: 10px; text-align: center; border: 2px solid; border-collapsed : collapsed;">
      <tr>
      <td colspan="9">&nbsp;</td>
      </tr>
      <tr>
      <td rowspan="3">&nbsp;</td>
      <td><b>ELABORADO POR(1):</b> <br>PREPARED BY <br><br><br> </td>
      <td rowspan="3">&nbsp;</td>
      <td><b>APROBADO POR(1):</b> <br>APPROVED BY(1):</td>
      <td rowspan="3">&nbsp;</td>
      <td><b>INFORMADO(1):</b> <br>INFORMED <br><br><br></td>
      <td rowspan="3">&nbsp;</td>
      <td><b>AUTORIZACIÓN DE COMPRA(1):</b> <br>PURCHASE AUTHORIZATION (1) <br><br><br></td>
      <td rowspan="3">&nbsp;</td>
      </tr>
      <tr>
      <td style="border-bottom : 1px solid;">&nbsp;{{$requisicion->nombres}}</td>
      <td style="border-bottom : 1px solid;">&nbsp;</td>
      <td style="border-bottom : 1px solid;">&nbsp;{{$requisicion->nombrea}} </td>
      <td style="border-bottom : 1px solid;">&nbsp;{{$requisicion->nombrer}}</td>
      </tr>
      <tr>
      <td ><b>NOMBRE, FIRMA Y FECHA</b> <br>NAME, SIGNATURE AND DATE</td>
      <td ><b>NOMBRE, FIRMA Y FECHA</b> <br>NAME, SIGNATURE AND DATE</td>
      <td ><b>NOMBRE, FIRMA Y FECHA</b> <br>NAME, SIGNATURE AND DATE <br><b>FABRICACIÓN</b> / FABRICATION</td>
      <td ><b>NOMBRE, FIRMA Y FECHA</b> <br>NAME, SIGNATURE AND DATE</td>
      </tr>
      <tr>
      <td colspan="9">&nbsp;</td>
      </tr>
      <tr>
      <td colspan="9" style="text-align: left; font-size: 8px;">NOTA: (1) Se requieren las 3 firmas para validación e iniciar con el proceso de compra.</td>
      </tr>
      <tr>
      <td colspan="9" style="text-align: left; font-size: 8px;">NOTE: (1) Three signatures are required for validation and start th purchasing process.</td>
      </tr>
      </table>

  </main>
</body>

</html>
