<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> -->
  <title>Finiquito...</title>
<style media="screen">

  .div {
    width: 100%;
    margin-left: 85px;
    margin-right: 85px;
    margin-top: 20px;
  }

  .letratablapieuno{
    font-size: 12px;
    font-family: Consolas, monaco, monospace;
    text-align: center;
    font-style: oblique;
    padding-top: 20px;
    border-bottom: 1px solid;
  }
  .letratablapiedos{
    font-size: 12px;
    font-family: Consolas, monaco, monospace;
    text-align: center;
    font-style: oblique;
      
  }
  .table {
    border-collapse: collapse;
    width: 100%;
  }

  body {
    margin: 0px;
  }


  .con{
    padding: 1px;
    text-align: left;
    font-size: 14px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: left;
    font-weight: bold;
  }
  .textoo{
        font-family: Consolas, monaco, monospace;
        font-size: 12.5px;     
        text-align: justify;
        font-style: oblique;
    }
    
</style>

</head>
<body >

<div class="div" >
        <table class="textoo">
            <p>En la Ciudad De Tehuacán, Puebla, el día 
               {{$hoy}}.
               Por la parte del trabajador
             <b><u>{{$finiquito->empleado}}</b></u>
                quien se identifica con 
             <u>CREDENCIAL DE ELECTOR</u>
               y bajo protesta de decir verdad, dijo llamarse como quedara escrito, originario (a) de la ciudad de 
             <u>{{$finiquito->lug_nac}}</u>
               del estado de 
             {{$finiquito->estado}}
                de 
             <u>{{$finiquito->edad}} años</u> 
                de edad, estado civil 
             <u>{{$finiquito->civil}}</u>
                , con domicilio ubicado en 
                Calle {{$finiquito->calle}}
            Número {{$finiquito->exterior}}
            Interior {{$finiquito->interior}}
            Colonia {{$finiquito->colonia}}
            {{$finiquito->lug_nac}},
            {{$finiquito->estado}}
           </p>
        </table>
        <table class="textoo">
            <p> La empresa denominada Constructora y Servicios Calderón Torres S.A. de C.V. con domicilio en Av. Francisco I Madero No. 1000, Col. María de la Piedad, C.P. 96410, Coatzacoalcos, Veracruz.<br> 
            <br>En este acto viene a celebrar un convenio de manera voluntaria, sin que exista presión o coacción alguna de las partes el cual se regirá al tenor de lo siguiente:
           </p>
        </table>  
        <table class="textoo">
        <tr>
            <th width= "170">&nbsp;</th>
            <th ><b>CLAUSULAS</b></th>
            <th width= "170">&nbsp;</th>
          </tr> 
          <tr>
            <th style="border-top-style: dotted;" width= "180">&nbsp;</th>
            <th ></th>
            <th style="border-top-style: dotted;" width= "178">&nbsp;</th>
          </tr>
        </table>
        <table style="padding-top: -12px;">
          <td style="border-top-style: solid;" width= "415">&nbsp;</td>  
        </table>
 
        <table class="textoo">
            <p><b>PRIMERA:</b> Ambas partes se reconocen mutua y recíprocamente la personalidad con la que se ostentan.</p>
        </table> 
        <table class="textoo">
            <p><b>SEGUNDA:</b> Ambas partes comparecientes manifiestan que por a si convenir a sus intereses en este acto dan por <b><u>TERMINADA LA RELACION DE TRABAJO </u></b>que les unía de conformidad con lo dispuesto en el artículo 53 Fracción I de la Ley Federal de Trabajo en Vigor.</p>
        </table> 
        <table class="textoo">
            <p><b>TERCERA:</b> Manifiesta la parte patronal compareciente, que en este acto se compromete  y  obliga  a cubrir al trabajador compareciente  la cantidad  
            <b>${{$finiquito->total}}</b> 
            pesos en efectivo, por concepto:</p>
        </table>
        <table class="textoo">
            <p>Aguinaldo proporcional por un importe: <b>${{$finiquito->aguinaldo_proporcional}}</b></p>
        </table><br>
        <table class="textoo">
            <td>Vacaciones por un importe:</td><td width= "175" style="text-align: center;"><b>${{$finiquito->prima_vacacional}}</b></td>
        </table><br>
        <table class="textoo">
            <td>Prima Vacacional  por un importe: </td><td width= "95" style="text-align: center;"><b>${{$finiquito->vacaciones_proporcional}}</b></td>
        </table>
        <table class="textoo">
            <p>los servicios prestados así como sus alcances legales correspondientes a que tiene derecho el citado trabajador, no adeudándole cantidad alguna por otro concepto, manifestado que extrajudicialmente se le cubrió a la parte trabajadora todas y cada una de las prestaciones a que tuvo derecho.</p>
        </table>
        <table class="textoo">
            <p><b>CUARTA:</b> Manifiesta el trabajador compareciente, estar de acuerdo en recibir la cantidad antes indicada y por el concepto antes señalado, y que durante el tiempo que existió la relación laboral, la parte trabajadora no sufrió ni padeció enfermedad profesional o riesgo de trabajo derivado de la misma, motivo por el cual extiende a favor de la parte patronal el 
            <b><u>Recibo Finiquito</u></b>
             más amplio que en derecho corresponda, así mismo manifiesta que en forma oportuna le fueron cubiertas todas y cada una las prestaciones que tuvo derecho.</p>
        </table>
        <table class="textoo">
            <p><b>QUINTA:</b> Ambas partes manifiestan que desde este momento no se reservan acciones, derechos o prestación que ejercitarse mutuamente, por ninguna de las vías del derecho, sean estas de carácter, penal, civil, laboral, mercantil, administrativo, fiscal o de cualquier otra índole, ni presente ni futuro, por lo que se solicitan que su oportunidad se sancione el presente convenio y se archive como un asunto total y definitivamente concluido.</p>
        </table>

        <table class="textoo">
            <p>Acto seguido se hace constar y se Certifica que está presente el C. <b><u>{{$finiquito->empleado}}</u></b> a quien en este acto se le hace entrega de la cantidad de 
            <b>${{$finiquito->total}}({{$cambio}})  </b> en efectivo, por los conceptos indicados a lo que dijo: Que recibe a su más entera conformidad, la cantidad establecida en las clausula tercera del presente convenio, por lo que firma y estampa sus huellas dactilares al margen de la presente diligencia para debida constancia. Con lo que se da por TERMINADA la presente relación laboral.</p>
        </table>
        <table  style="padding-left: 80px;">
          <tr><br>
            <td class="letratablapieuno" width="125">PATRON<br><br><br></td>
            <td  width="60">&nbsp;</td>
            <td class="letratablapieuno" width="125">EMPLEADO<br><br><br></td>
            <td  width="60">&nbsp;</td>
            

          </tr>
          <tr>
            <td class="letratablapiedos" >CONSTRUCTORA Y SERVICIOS CALDERON TORRES S.A DE C.V</td>
            <td>&nbsp;</td>
            <td class="letratablapiedos">NOMBRE Y FIRMA</td>
            <td>&nbsp;</td>
            


          </tr>
        </table>
        <table  style="padding-left: 180px;">
          <tr><br>
            <td class="letratablapieuno" width="125">TESTIGO<br><br><br></td>
            <td  width="60">&nbsp;</td>
          </tr>
          <tr>
            <td class="letratablapiedos" >C. FLORES LOZANO LAURA DANIELA.</td>
            <td>&nbsp;</td>
          </tr>
        </table>
  </div>
</div>


</body>
</html>
