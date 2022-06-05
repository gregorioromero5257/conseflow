<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> -->
  <title>Control_Expedientes...</title>
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
  font-size: 13px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: right;
    font-weight: bold;
    padding-left: 10px;
    border-collapse: collapse;

}
.letratablarr{
  font-size: 11px;
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
    border-bottom: 2px solid black;
}
.letratablapiedos{
  font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
    font-weight: bold;
}

table.estilo th, td {
  font-size: 11px;
    font-family: Arial, Helvetica, sans-serif;
}
.estilo th, td {
    padding: 5px;
    text-align: center;
    border-collapse: collapse;
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
    <td><img src="img/conserflow.png" alt="Conserflow" width="120" height="60"class="img" align="center" style="padding-bottom: 10px; padding-top: 10px;"></td>
    <td style="padding-left: 50px; " class="letradostabla" width="300"><b class="letrauno">CONSTRUCTORA Y SERVICIOS CALDERON TORRES S.A. DE C.V. </b>
        <br><b class="letrados">INGENIERIA EN AUTOMATIZACION+ INSTRUMENTACION+ELECTRICA MANTENIMIENTO INDUSTRIAL</b><br>
    </td>

  </tr>
</table>
<table align="right" style="border-collapse: collapse; border: 1px ridge; padding-top: -90px; padding-right: -10px; border:.5px solid ">
          <tr>
            <td >REV. 01</td>
          </tr>
        </table>

  <table style="border-collapse: collapse; padding-left: 0px; padding-top: 10px; " align="right">
    <tr>
<td class="letratablar">RECURSOS HUMANOS.</td></tr>
<tr>
<td class="letratablar">HOJA DE CONTROL DE EXPEDIENTES.</td>
</tr>
  </table>

<table style="border-collapse: collapse; padding-top: 70px;">
    <tr>
      <td class="letratablarr">NOMBRE:</td>
      <td class="letratablapieuno">{{$expediente->entrega}}</td>
    </tr>
    <tr>
      <td class="letratablarr">AREA:</td>
      <td class="letratablapieuno">{{$expediente->area}}</td>
    </tr>
    <tr>
      <td class="letratablarr">UBICACIÓN:</td>
      <td class="letratablapieuno">{{$ubicacion->tun}}</td>
    </tr>
    <tr>
      <td class="letratablarr">PUESTO:</td>
      <td class="letratablapieuno">{{$expediente->areadmin}}</td>
    </tr>
    <tr>
      <td class="letratablarr">INGRESO:</td>
      <td class="letratablapieuno">$ {{$sueldo->ssd}}</td>
    </tr>

    </table>

    <table class= "estilo" border="1" bordercolor="#0000FF" style="border-collapse: collapse; width:100%; padding-top:30px;" >
      <tr>
        <th >Documentación.</th>
        <th >OK.</th>
        <th>Falta.</th>
        <th>No aplica.</th>
      </tr>

      <tr>
        <td class="letratablarr">SOLICITUD</td>
        @if($expediente->solicitud==1)
       <td>X</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       @endif
        @if($expediente->solicitud==0)
        <td>&nbsp;</td>
        <td>X</td>
        <td>&nbsp;</td>
        @endif
        @if($expediente->solicitud==2)
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>X</td>
        @endif
      </tr>

      <tr>
        <td class="letratablarr">EVALUACIÓN PSICO-LABORAL</td>
        @if($expediente->evaluacion_psicolaboral==1)
       <td>X</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       @endif
        @if($expediente->evaluacion_psicolaboral==0)
        <td>&nbsp;</td>
        <td>X</td>
        <td>&nbsp;</td>
        @endif
        @if($expediente->evaluacion_psicolaboral==2)
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>X</td>
        @endif
      </tr>
      <tr>
        <td class="letratablarr">EVALUACIÓN HABILIDADES TÉCNICAS </td>
        @if($expediente->evaluacion_hab_tecnicas==1)
       <td>X</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       @endif
        @if($expediente->evaluacion_hab_tecnicas==0)
        <td>&nbsp;</td>
        <td>X</td>
        <td>&nbsp;</td>
        @endif
        @if($expediente->evaluacion_hab_tecnicas==2)
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>X</td>
        @endif
      </tr>
      <tr>
        <td class="letratablarr">FOTOGRAFÍA</td>
        @if($expediente->foto==1)
       <td>X</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       @endif
        @if($expediente->foto==0)
        <td>&nbsp;</td>
        <td>X</td>
        <td>&nbsp;</td>
        @endif
        @if($expediente->foto==2)
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>X</td>
        @endif
      </tr>
      <tr>
        <td class="letratablarr">ACTA NACIMIENTO</td>
        @if($expediente->acta_nacimiento==1)
       <td>X</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       @endif
        @if($expediente->acta_nacimiento==0)
        <td>&nbsp;</td>
        <td>X</td>
        <td>&nbsp;</td>
        @endif
        @if($expediente->acta_nacimiento==2)
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>X</td>
        @endif
      </tr>
      <tr>
        <td class="letratablarr">CREDENCIAL ELECTOR</td>
        @if($expediente->credencial_elector==1)
       <td>X</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       @endif
        @if($expediente->credencial_elector==0)
        <td>&nbsp;</td>
        <td>X</td>
        <td>&nbsp;</td>
        @endif
        @if($expediente->credencial_elector==2)
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>X</td>
        @endif
      </tr>
      <tr>
        <td class="letratablarr">CURP</td>
        @if($expediente->curp==1)
       <td>X</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       @endif
        @if($expediente->curp==0)
        <td>&nbsp;</td>
        <td>X</td>
        <td>&nbsp;</td>
        @endif
        @if($expediente->curp==2)
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>X</td>
        @endif
      </tr>
      <tr>
        <td class="letratablarr">RFC</td>
        @if($expediente->rfc==1)
       <td>X</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       @endif
        @if($expediente->rfc==0)
        <td>&nbsp;</td>
        <td>X</td>
        <td>&nbsp;</td>
        @endif
        @if($expediente->rfc==2)
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>X</td>
        @endif
      </tr>
      <tr>
        <td class="letratablarr">No. SEGURIDAD SOCIAL</td>
        @if($expediente->nss_imss==1)
       <td>X</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       @endif
        @if($expediente->nss_imss==0)
        <td>&nbsp;</td>
        <td>X</td>
        <td>&nbsp;</td>
        @endif
        @if($expediente->nss_imss==2)
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>X</td>
        @endif
      </tr>
      <tr>
        <td class="letratablarr">ULTIMO GRADO DE ESTUDIOS</td>
        @if($expediente->comprobante_estudios==1)
       <td>X</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       @endif
        @if($expediente->comprobante_estudios==0)
        <td>&nbsp;</td>
        <td>X</td>
        <td>&nbsp;</td>
        @endif
        @if($expediente->comprobante_estudios==2)
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>X</td>
        @endif
      </tr>
      <tr>
        <td class="letratablarr">COMPROBANTE DOMICILIO</td>
        @if($expediente->comprobante_domicilio==1)
       <td>X</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       @endif
        @if($expediente->comprobante_domicilio==0)
        <td>&nbsp;</td>
        <td>X</td>
        <td>&nbsp;</td>
        @endif
        @if($expediente->comprobante_domicilio==2)
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>X</td>
        @endif
      </tr>
      <tr>
        <td class="letratablarr">CARTILLA SERVICIO MILITAR</td>
        @if($expediente->cartilla_servicio_militar==1)
       <td>X</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       @endif
        @if($expediente->cartilla_servicio_militar==0)
        <td>&nbsp;</td>
        <td>X</td>
        <td>&nbsp;</td>
        @endif
        @if($expediente->cartilla_servicio_militar==2)
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>X</td>
        @endif
      </tr>
      <tr>
        <td class="letratablarr">LICENCIA DE MANEJO</td>
        @if($expediente->licencia_manejo==1)
       <td>X</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       @endif
        @if($expediente->licencia_manejo==0)
        <td>&nbsp;</td>
        <td>X</td>
        <td>&nbsp;</td>
        @endif
        @if($expediente->licencia_manejo==2)
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>X</td>
        @endif
      </tr>
      <tr>
        <td class="letratablarr">CARTAS RECOMENDACIÓN LABORAL</td>
        @if($expediente->carta_recomendacion==1)
       <td>X</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       @endif
        @if($expediente->carta_recomendacion==0)
        <td>&nbsp;</td>
        <td>X</td>
        <td>&nbsp;</td>
        @endif
        @if($expediente->carta_recomendacion==2)
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>X</td>
        @endif
      </tr>
      <tr>
        <td class="letratablarr">ACTA MATRIMONIO</td>
        @if($expediente->acta_matrimonio==1)
       <td>X</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       @endif
        @if($expediente->acta_matrimonio==0)
        <td>&nbsp;</td>
        <td>X</td>
        <td>&nbsp;</td>
        @endif
        @if($expediente->acta_matrimonio==2)
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>X</td>
        @endif
      </tr>
      <tr>
        <td class="letratablarr">CARTA INFONAVIT</td>
        @if($expediente->carta_infonavit==1)
       <td>X</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       @endif
        @if($expediente->carta_infonavit==0)
        <td>&nbsp;</td>
        <td>X</td>
        <td>&nbsp;</td>
        @endif
        @if($expediente->carta_infonavit==2)
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>X</td>
        @endif
      </tr>
      <tr>
        <td class="letratablarr">CERTIFICADO MEDICO</td>
        @if($expediente->certificado_medico==1)
       <td>X</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       @endif
        @if($expediente->certificado_medico==0)
        <td>&nbsp;</td>
        <td>X</td>
        <td>&nbsp;</td>
        @endif
        @if($expediente->certificado_medico==2)
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>X</td>
        @endif
      </tr>
      <tr>
        <td class="letratablarr">CARTA DE NO ENFERMEDADES</td>
        @if($expediente->carta_no_enfermedades==1)
       <td>X</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       @endif
        @if($expediente->carta_no_enfermedades==0)
        <td>&nbsp;</td>
        <td>X</td>
        <td>&nbsp;</td>
        @endif
        @if($expediente->carta_no_enfermedades==2)
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>X</td>
        @endif
      </tr>
      <tr>
        <td class="letratablarr">HOJA RETENCIÓN DE CRÉDITO INFONAVIT</td>
        @if($expediente->retencion_credito_infonavit==1)
       <td>X</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       @endif
        @if($expediente->retencion_credito_infonavit==0)
        <td>&nbsp;</td>
        <td>X</td>
        <td>&nbsp;</td>
        @endif
        @if($expediente->retencion_credito_infonavit==2)
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>X</td>
        @endif
      </tr>
      <tr>
        <td class="letratablarr">VALES DE RESGUARDO</td>
        @if($expediente->vales_resguardo==1)
       <td>X</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       @endif
        @if($expediente->vales_resguardo==0)
        <td>&nbsp;</td>
        <td>X</td>
        <td>&nbsp;</td>
        @endif
        @if($expediente->vales_resguardo==2)
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>X</td>
        @endif
      </tr>
    </table>






</div>


</body>

</html>
