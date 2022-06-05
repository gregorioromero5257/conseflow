<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> -->
  <title>Carta de Infonavit, Infonacot...</title>
<style media="screen">

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
.uno {
  text-align:  center;
  padding-left: 150px;

}
.letrauno {
  font-size: 16px;
    font-family: Arial, Helvetica, sans-serif;
    border: 2px;
    
    
}
.letrados {
  font-size: 10px;
    font-family: Arial, Helvetica, sans-serif;
    border: 1px;
    
}
.letratablar{
  font-size: 16px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: left;
    font-weight: bold;
    padding-left: 10px;
    border-collapse: collapse;
    
}
.letratablarr{
  font-size: 14px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: left;
    padding-left: 10px;
    
}

.letradostabla{
    font-size: 10px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
    font-weight: bold;
}

.letratablapieuno{
  font-size: 14px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
  padding-top: 20px;
  border-bottom: 2px solid black;
}
.letratablapiedos{
  font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
    font-weight: bold;
}
.table {
  border-collapse: collapse;
  width: 100%;
}
body {
  margin: 0px;
}

</style>

</head>
<body >
<div class="div">

<table>
  <tr>
    <td><img src="img/conserflow.png" alt="Conserflow" width="120" height="60"class="img" align="center" style="padding-bottom: 40px; padding-top: 40px;"></td>
    <td style="padding-left: 60px; " class="letradostabla" width="300"><b class="letrauno">CONSTRUCTORA Y SERVICIOS CALDERON TORRES S.A. DE C.V. </b>
        <br><b class="letrados">INGENIERIA EN AUTOMATIZACION+ INSTRUMENTACION+ELECTRICA MANTENIMIENTO INDUSTRIAL</b><br>
    </td>

  </tr>
</table>


  <table style="border-collapse: collapse; padding-left: 0px; " align="right">
    <tr>
      <td class="letratablar">{{ $tiposContratov2->ubicacion}} a</td>
      <td class="letratablar">{{$hoy}}</td>
    </tr>
  </table>

<table style="padding-top: 70px;">
    <tr>
      <td class="letratablar">CONSTRUCTORA Y SERVICIOS CALDERON TORRES, S.A. DE C.V.</td>
    </tr>
    <tr>
      <td class="letratablarr">Av. Francisco I. Madero No. 1000</td>
    </tr>
    <tr>
      <td class="letratablarr">Colonia María de la Piedad</td>
    </tr>
    <tr>
      <td class="letratablarr">C. P. 96410</td>
    </tr>
    <tr>
      <td class="letratablarr">Coatzacoalcos, Veracruz</td>
    </tr>
    <tr>
      <td class="letratablar">PRESENTE</td>
    </tr>
    
  </table>

  <p class="letratablarr" style="text-align: justify; padding-top: 20px;">
  Por este medio les informo que no cuento al día de hoy con crédito alguno otorgado por el 
  <b>INFONAVIT</b>
  o que se encuentre en proceso o trámite, y que por consecuencia no habrá responsabilidad de ninguna naturaleza para la empresa 
  <b>CONSTRUCTORA Y SERVICIOS CALDERON TORRES, S.A. DE C.V.</b>
  , en cuanto a cualquiera retención de mi salario para el pago por su conducto a dicha institución sea de amortizaciones de capital, del servicio de intereses o de cualquiera otro concepto relacionado. 
  </p>
  <p class="letratablarr" style="text-align: justify;">
  Lo mismo declaro en cuanto al 
  <b>INFONACOT</b>
  , con el cual no tengo crédito alguno pendiente de pago ni he iniciado trámites o gestiones para obtener uno.
  </p>
  <p class="letratablarr" style="text-align: justify;">
  En el caso de que esta información resulte incorrecta o falsa, me responsabilizo y comprometo a cubrir a 
  <b>CONSTRUCTORA Y SERVICIOS CALDERON TORRES, S.A. DE C.V.</b>
  , las sanciones, recargos y otros montos que resulten como consecuencia.
  </p>
  <p class="letratablarr" style="text-align: justify;">
  Asimismo, si llegara a obtener un crédito durante mi estancia laboral con ustedes, sea del 
  <b>INFONAVIT</b>
   o del 
  <b>INFONACOT</b>
  , me comprometo a entregar oportunamente el aviso de descuento al Área de Recursos Humanos.
  </p>
  <p class="letratablarr" >Quedo de usted.</p>

<table align="center" style="padding-left: 80px; padding-top: 120px;">
  <tr><br>
    <td class="letratablapieuno" width="100">ATENTAMENTE<br><br><br></td>
    <td width="120">&nbsp;</td>
    
  </tr>
 
  <tr>
    <td class="letratablapiedos" >{{ $tiposContratov2->e_nombre}}</td>
    <td>&nbsp;</td>
   
   

  </tr>
</table>




</table>
</div>


</body>
</html>
