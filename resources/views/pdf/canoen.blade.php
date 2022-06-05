<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> -->
  <title>Carta de No Enfermedades...</title>
<style media="screen">

.div {
  width: 100%;
  margin-left: 0px;
  margin-right: 0px;
  margin-left: 10px;
  margin-right: 10px;
  margin-top: 0px;
}
.img {
  padding-left: 12px;
  padding-top: 0px;
  
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
.tabvac{
  align="center";
  border-style:solid;
  border-width:1px; 
  padding-left: 30px; 
  padding-top: 80px;
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
      <td class="letratablar">{{ $tiposContratov1->ubicacion}} a</td>
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
  Por este medio les informo que no presento al día de hoy ningún síntoma de enfermedad orgánica o infecciosa, 
  ni de ninguna otra enfermedad transmisible,  así como declaro 
  <b>bajo protesta de decir verdad </b>
  que no padezco ninguna enfermedad crónica que me limite físicamente para realizar las labores como Ayudante.
  </p>
  <p class="letratablarr" style="text-align: justify;">
  En el caso de que esta información resulte incorrecta o falsa, me responsabilizo y comprometo a cubrir a  
  <b>CONSTRUCTORA Y SERVICIOS CALDERON TORRES, S.A. DE C.V.</b>, las sanciones que resulten como consecuencia.
  </p>


<table align="center" style="padding-top: 180px; padding-left: 80px;">
  <tr><br>
    <td class="letratablapieuno" width="180">ATENTAMENTE<br><br><br></td>
    <td style="padding-top: 180px;" width="120">&nbsp;</td>
    
  </tr>
 
  <tr>
    <td class="letratablapiedos" >{{ $tiposContratov1->e_nombre}}</td>
    <td>&nbsp;</td>
   
   

  </tr>




</table>
</div>


</body>
</html>
