<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">  
    <title>Formato Vacacional...</title>
  <style>
body {
    font-size: 13px;
}
.grid {
    display: inline;
    float: right;
    margin-left: 20px;
    margin-right: 15px;
    font-family: Arial, Helvetica, sans-serif;
    padding: 4px;
    font-size: 14px;
}
.logo {
    margin-right:25px;
    margin-left: 0px;
    padding: 10px 0;
}
.clear {
    clear: both;
}
.eimg {
    position: absolute;
    left: -45px;
    top: -45px;
    width: 120%;
    z-index: -1;
}
#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}
#customers td, #customers th {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
    color: #547260;

}
#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    border-color: #b4b4b4;
    color: #547260;
    text-align: center;
}
.divl{
  
  width: 40%;
  position: absolute;
  left: 10px;
}
.divr{
  
  width: 40%;
  padding-left: 423px;
  
}
.letratablapiedos{
    font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
    
}
.st{
  font-family: Arial, Helvetica, sans-serif;
  font-size: 16px;
  font-style: normal;
  font-variant: normal; font-weight: 100;
}
.sl{
  font-size: 16px; 
  font-family: Arial, Helvetica, sans-serif;
}
.fo{
  border-collapse: collapse;
  padding: 100px; 
  padding-top: 60px; 
  padding-left: 10px;
}
  </style>   
</head>
<body>
<table>
        <img src="img/enc.png" class="eimg">
</table>
    <div style="padding-top:55px;">
            <p align="center" class="grid">
            <b>CONSTRUCTORA Y SERVICIOS CALDERON TORRES S.A. DE C.V. </b><br>
            Ingeniería en automatización + Instrumentación + Eléctrica + Mantenimiento Industrial<br>
            Montajes Mecánicos + Fabricación Metal Mécanica + Obra Civil + Obra Pesada<br>
            <b>FORMATO DE VACACIONES</b> 
            </p>
        <img src="img/conserflow.png" alt="Conserflow" width="145" height="53" class="grid logo">
        <div class="clear"></div>
    </div>
<br>
<br>
<table>
<tr>
  <th class="sl">PARA: </th>
  <th class="st">Recursos Humanos</th>
</tr>
</table>
<br>
<table>
<tr>
  <th class="sl">AREA SOLICITANTE: </th>
  <th class="st">{{$vacaciones->areadmin}}</th>
</tr>
</table>
<br>
<br>
  <h1 style="padding-left: 380px;" class="st">Tehuacán, Puebla a  {{$dia}}  de {{$mesnombre}} del {{$anio}}</h1>
<br>
<br>
<br>

  <h1 class="st"> Solicito a ustedes tomar nota que disfrutaré de vacaciones durante el período siguiente:</h1>
<br>
<br>
<br>
<br>
<br>

<div class="divl">
  <table id="customers" >
  <tr>
    <td>DEL</td>
  </tr>
</table>
<br>
  <table id="customers" >
  <tr>
    <th>{{$dia1}}</th>
    <th>{{$mes1}}</th>
    <th>{{$anio1}}</th>
  </tr>
  <tr>
    <td>DIA</td>
    <td>MES</td>
    <td>AÑO</td>
  </tr>
  </table>
</div>
<div class="divr">
  <table id="customers" >
  <tr>
    <td>AL</td>
  </tr>
</table><br>
  <table id="customers" >
  <tr>
    <th>{{$dia2}}</th>
    <th>{{$mes2}}</th>
    <th>{{$anio2}}</th>
  </tr>
  <tr>
    <td>DIA</td>
    <td>MES</td>
    <td>AÑO</td>
  </tr>
  </table>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<table  class="fo">
<tr>
  <th class="letratablapiedos">{{$vacaciones->e_solicita}}</th>
  <td width="10">&nbsp;</td>
  <th class="letratablapiedos">{{$vacaciones->e_autoriza}}</th>
  <td width="10">&nbsp;</td>
  <th class="letratablapiedos">&nbsp;</th>
  <td width="10">&nbsp;</td>
  <th class="letratablapiedos">&nbsp;</th>
</tr>
<tr>
  <td class="letratablapiedos" style="border-top: 1px solid color: #547260;" width="125">Empleado</td>
  <td width="10">&nbsp;</td>
  <td class="letratablapiedos" style="border-top: 1px solid color: #547260;" width="125">Autorización</td>
  <td width="10">&nbsp;</td>
  <td class="letratablapiedos" style="border-top: 1px solid color: #547260;" width="125">Vo. Bo.</td>
  <td width="10">&nbsp;</td>
  <td class="letratablapiedos" style="border-top: 1px solid color: #547260;" width="125">Acuse de Recibido</td>
</tr>
<tr>
  <td class="letratablapiedos" >NOMBRE Y FIRMA</td>
  <td width="10">&nbsp;</td>
  <td class="letratablapiedos">GERENTE DEL AREA</td>
  <td width="10">&nbsp;</td>
  <td class="letratablapiedos">RECURSOS HUMANOS</td>
  <td width="10">&nbsp;</td>
  <td class="letratablapiedos">NOMINA</td>
</tr>
</table> 
</div> 
</div>
</body>
</html>
