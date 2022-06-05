<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> -->
  <title>Renuncia Voluntaria...</title>
<style media="screen">

.div {
  width: 100%;
  margin-left: 30px;
  margin-right: 30px;
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
    text-transform: uppercase;
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
      <td class="letratablar">{{$tiposContratov4->ubicacion}}, </td>
      <td class="letratablar">{{$hoy}}</td>
    </tr>
  </table>

<table style="padding-top: 70px;">
    <tr>
      <td class="letratablar">CONSTRUCTORA Y SERVICIOS CALDERON TORRES, S.A. DE C.V.</td>
    </tr>
    <tr>
      <td class="letratablarr">{{$tiposContratov4->empnomb}}</td>
    </tr>


    <table  style="padding-top: 20px;">
      <tr>
        <td class="letratablar">PRESENTE</td>
      </tr>
    </table>


  </table>

  <p class="letratablarr" style="text-align: justify; padding-top: 20px;">
    - - - Por medio de la presente, me permito hacer de su conocimiento que el día de hoy <u> {{$hoy}}</u> doy por
    terminada en forma voluntaria y por así convenir a mis intereses la relación de trabajo que venía desempeñando para la empresa <b>
    CONSTRUCTORA Y SERVICIOS CALDERON TORRES, S.A. DE C.V.</b> de conformidad con lo dispuesto por el artículo 53 fracción I de la Ley Federal
    del Trabajo. - -  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
</p>
<br>
  <p class="letratablarr" style="text-align: justify;">
    - - - manifiesto además que durante el tiempo que preste mis servicios para la empresa <b>CONSTRUCTORA Y
    SERVICIOS CALDERON TORRES, S.A. DE C.V.</b>; no contraje enfermedades, ni riesgo de trabajo alguno, y asimismo hago constar
     que la empresa siempre me cubrió oportunamente el pago de salarios ordinarios y extraordinarios, aguinaldos, vacaciones,
     prima vacacional, reparto de utilidades, séptimo día y demás prestaciones que tuve derecho de acuerdo con la ley y mi contrato
     individual de trabajo. - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -  - - - - - - - -  - - - - - - - -
  </p>
  <br>
  <p class="letratablarr" style="text-align: justify;">
 - - - por lo anterior extiendo a favor de la empresa <b>CONSTRUCTORA Y SERVICIOS CALDERON TORRES, S.A. DE C.V.</b>,
 el más amplio finiquito que en derecho proceda, sin reservarme acción ni derecho alguno que ejercitar posteriormente
 en su contra. - - - - - - - - - - - - - - - - - -
  </p>


<table align="center" style="padding-left: 80px;">
  <tr><br>
    <td class="letratablapieuno" width="100">ATENTAMENTE<br><br><br></td>
    <td width="120">&nbsp;</td>

  </tr>

  <tr>
    <td class="letratablapiedos" width="180">{{strtoupper($tiposContratov4->e_nombre)}}</td>
    <td>&nbsp;</td>



  </tr>
</table>




</table>
</div>


</body>
</html>
