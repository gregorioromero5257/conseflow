<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> -->
  <title>Vale de Resguardo</title>
<style media="screen">


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
  font-size: 12px;
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
    text-align: justify;
    padding-left: 10px;

}

.letradostabla{
    font-size: 14px;
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

}

body {
  margin: 40px 50px 40px 40px;
}

@page {
/* switch to landscape */

/* set page margins */
/* Default footers */

@bottom-right {
    content: counter(page) " of " counter(pages);
}
}
#header {
position: fixed;
width: 100%;
top: 0;
left: 0;
right: 0;
}
#footer {
position: fixed;
width: 100%;
bottom: 0;
left: 0;
right: 0;
}
bottom-right {
content: counter(page) " of " counter(pages);
}

.custom-footer-page-number:after {
content: counter(page);
}

</style>

</head>
<body>
  <div id="header">
  <table border="1" class="letradostabla" style="border: 1px solid black; border-collapse: collapse;" width="100%">
  <tr><td colspan="4"><font color="blue">CONSTRUCTORA Y SERVICIOS CALDERÓN TORRES S.A. DE C.V.</font></td></tr>
  <tr>
    <td rowspan="3" scope="col"><img src="img/conserflow.png" alt="Conserflow" width="120" height="40" align="center"></td>
    <td rowspan="3" scope="col">VALE DE RESGUARDO</td>
    <td scope="col">CÓDIGO</td>
    <td scope="col">PMN-03/F02</td>
  </tr>
  <tr>
    <td>REVISIÓN</td>
    <td>00</td>
  </tr>
  <tr>
    <td>EMISIÓN</td>
    <td>10-FEB-2020</td>
  </tr>
</table>
  </div>

      <!--PIE DE PÁGINA -->
  <div id="footer">
      <div class="letrados" style="color:#645b61;">
          <table width="100%">
            <tr>
              <td>Control Vehicular</td>
              <td><div align="right">PCV-01/F-02</div></td>
            </tr>
          </table>
      </div>

  </div>
<div class="div">
    <br>
    <br>
    <br>
    <br>
  <table style="border-collapse: collapse; padding-left: 0px; padding-top:40px;" align="right">
    <tr>
      <td class="letratablarr">Fecha: <b>{{$hoy}}</b></td>
    </tr>
  </table>

<table style="padding-top: 70px;">

    <tr>
      <td class="letratablar"><u>VALE DE RESGUARDO</u></td>
    </tr>

  </table>

  <p class="letratablarr" style="text-align: justify;">
  Por medio de la presente el <br>
  C:<u>X</u>
  declara que se le ha asignado un vehículo por el que firma el presente vale de conformidad y resguardo  a su entera satisfacción,
  comprometiéndose a mantener dicho vehículo en el estado en el que lo recibe por un periodo de <u>X</u>. De igual manera el responsable del
  vehículo que en el presente documento se describe mantiene la obligación de conservar y custodiar el vehículo que se le asigna, bajo su cuidado. </p><br>

  <p class="letratablarr" style="text-align: justify;">
    En el entendido de que en caso de que el mismo sufra cualquier daño parcial o total  ocasionado por su dolo o negligencia se hará responsable de la reparación
    del mismo. En el caso de siniestro llamara al Auto seguro AXA al teléfono 01 800 911 12 92, así como también deberá informar inmediatamente al
    área de recursos humanos, para que esta lo asesore.
  </p>

  <p class="letratablarr" >
    Nombre del propietario:<br>
  Constructora y Servicios Calderón Torres, S.A de C.V.
  </p>

  <p class="letratablarr">
    Vehículo:
  </p>

  <table width="100%" border="1" style="border-collapse: collapse;" class="letrados">
  <tr>
    <td colspan="2" style="text-align: center;"><b>DATOS DEL VEHICULO</b></td>
  </tr>
  <tr>
    <td height="20" colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td width="50%">No. DE SERIE/NIV</td>
    <td style="text-align: center;">{{$resguardo->num_serie}}</td>
  </tr>
  <tr>
    <td>MARCA</td>
    <td style="text-align: center;">{{$resguardo->marca}}</td>
  </tr>
  <tr>
    <td>NUMERO DE PUERTAS</td>
    <td style="text-align: center;">{{$resguardo->puertas}}</td>
  </tr>
  <tr>
    <td>CLAVE VEHICULAR</td>
    <td style="text-align: center;">{{$resguardo->clave}}</td>
  </tr>
  <tr>
    <td>MODELO</td>
    <td style="text-align: center;">{{$resguardo->modelo}}</td>
  </tr>
  <tr>
    <td>CAPACIDAD</td>
    <td style="text-align: center;">{{$resguardo->capacidad}}</td>
  </tr>
  <tr>
    <td>NUMERO DE MOTOR</td>
    <td style="text-align: center;">{{$resguardo->motor}}</td>
  </tr>
  <tr>
    <td>POLIZA DE SEGURO DE AUTOMOVILES</td>
    <td style="text-align: center;">{{$resguardo->numero_poliza}}</td>
  </tr>
  <tr>
    <td>COLOR</td>
    <td style="text-align: center;">{{$resguardo->colores}}</td>
  </tr>
  <tr>
    <td>PLACAS</td>
    <td style="text-align: center;">{{$resguardo->placas}}</td>
  </tr>
  <tr>
    <td>CILINDROS</td>
    <td style="text-align: center;">{{$resguardo->cilindros}}</td>
  </tr>
  <tr>
    <td>TRANSPORTE</td>
    <td style="text-align: center;">{{$resguardo->transporte}}</td>
  </tr>
  <tr>
    <td>TARJETA DE CIRCULACION</td>
    <td style="text-align: center;">{{$resguardo->tarjeta_circulacion}}</td>
  </tr>
</table>


    <p class="letratablarr" style="padding-top:50px;">
      Reglamento de uso de vehículos:
    </p>

    <ol class="letratablarr">
      <li>Conducir el vehículo con licencia de manejo vigente, así como entregar una copia de la licencia y La credencial de Elector.</li>
      <li>No manejar el vehículo en estado de ebriedad y bajo la influencia de drogas.</li>
      <li>Deberá verificar que la unidad mantenga en cumplimiento, mantenimientos, verificación vehicular, derecho vehicular y seguro para daños a terceros</li>
      <li>Deberá mantener disponible siempre durante el uso de la unidad, licencia de conducir vigente, póliza de seguro, tarjeta de circulación y engomado de ultima verificación </li>
      <li>No hacer uso del vehículo en forma lucrativa</li>
      <li>Obedecer los reglamentos de tránsito, federal, estatal y local</li>
      <li>No manejar el vehículo para hacer remolques</li>
      <li>No sobrecargar el vehículo con relación a su resistencia o capacidad</li>
      <li>Mantener cerrado el vehículo siempre que se encuentre fuera del el salvaguardando el vehículo estacionándolo en lugares autorizados.</li>
      <li>No podrá participar con el vehículo directa o indirectamente en carreras de velocidad </li>
    </ol>


        <p class="letratablarr">
        El vehículo mencionado en este vale de resguardo, cuenta con la totalidad de los permisos necesarios para circular incluyendo las verificaciones en
        materia ambiental y póliza de seguros misma que anexa al vale de resguardo. En virtud de lo anterior recibe el vehículo en las características
        específicas que anteceden.
        </p>

        <p class="letratablarr">
          <u>( X )</u>, conozco, comprendo y acepto que en caso de generarse daños, multas o siniestros, por incumplimiento a las reglas o actos imprudenciales de mi parte, los costos originados serán cubiertos por mi cuenta.
        <p>

          <table style="border-collapse: collapse; padding-top: 80px; padding-left: 60px;" >
          <tr>
          <td class="letratablapieuno" width="147"><br><br><br></td>
          <td width="100">&nbsp;</td>
          <td class="letratablapieuno" width="163"><br><br></td><p></p>
          <td width="41">&nbsp;</td>
          </tr>

          <tr>
          <td class="letratablapiedos">Responsable del vehículo</td>
          <td>&nbsp;</td>
          <td class="letratablapiedos">Persona que proporciona el equipo</td>
          <td>&nbsp;</td>
          </tr>

          <tr>
          <td class="letratablapiedos">Nombre y Firma</td>
          <td>&nbsp;</td>
          <td class="letratablapiedos">Nombre y Firma</td>
          <td>&nbsp;</td>
          </tr>
          </table>

</table>
</div>

</body>
</html>
