<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> -->
  <title>Carta Compromiso...</title>
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
      <td class="letratablar">{{ $tiposContratov3 == null ? '' : $tiposContratov3->ubicacion }} a</td>
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
  Por medio del presente manifiesto
  <b>BAJO PROTESTA DE DECIR VERDAD</b>
   que en mi categoría o puesto de Empleado de Confianza de esta empresa, me obligo a guardar y dar el carácter de estrictamente
   <u><b>CONFIDENCIAL</b></u>
  a la información que manejo respecto al puesto de
  <b>{{$tiposContratov3 == null ? '' : $tiposContratov3->nombre_puesto }}</b>
   así mismo me obligo a no divulgar la información de manera verbal o escrita y a no revelar a terceros, ni extraer, transmitir o divulgar parcial, ni  totalmente en forma alguna el contenido de dicha información, durante la vigencia de mi contrato de trabajo y con posterioridad a su terminación.
  </p>
  <p class="letratablarr" style="text-align: justify;">
  Mi  anterior compromiso se refiere expresamente a la información que he venido trabajando y manejado, la cual consta en:
  </p>
  <p class="letratablarr" style="text-align: justify;">
 <dl >
 <dd>- Negociación con clientes y proveedores.</dd>
 <dd>- Información de clientes.</dd>
 <dd>- Relaciones comerciales.</dd>
 <dd>- Costos del servicio negociado con clientes</dd>
 <dd>- Información de los socios de la empresa</dd>
 <dd>- Información relacionada con procedimientos, prácticas y políticas internas de la organización.</dd>
 <dd>- Cualquier otra que por su naturaleza, se considere sensible y que se me hará notificación de forma escrita que no está sujeta a divulgación hacia la competencia o cualquier persona que no sea proveedor o colaboradora de esta organización.</dd>
 </dl>
  </p>

  <p class="letratablarr" >Por lo anterior, reconozco que al incumplimiento a este compromiso de confidencialidad adquirido por mi parte con esta empresa, me hace acreedor de sanciones de carácter laboral y penal, contempladas en la Ley Federal del Trabajo y Código Penal y de Procedimientos Penales vigentes, respectivamente en materia común  y para toda la  República Mexicana en Materia Federal.</p>

<table align="center" style="padding-left: 80px;">
  <tr><br>
    <td class="letratablapieuno" width="100">ATENTAMENTE<br><br><br></td>
    <td width="120">&nbsp;</td>

  </tr>

  <tr>
    <td class="letratablapiedos" >{{ $tiposContratov3 == null ? '' :$tiposContratov3->e_nombre}}</td>
    <td>&nbsp;</td>



  </tr>
</table>




</table>
</div>


</body>
</html>
