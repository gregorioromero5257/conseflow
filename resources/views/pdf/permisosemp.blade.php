<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">  
  <title>Formato De Permiso...</title>
  <style>
  .eimg {
  position: absolute;
  left: -45px;
  right: -0px;
  top: -45px;
  width: 120%;
  z-index: -1;
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
#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}
#customers td, #customers th {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
    color: #0a0a0a;
}
#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: center;
    border-color: #b4b4b4;
    color: #0a0a0a;
    text-align: center;
}
#customersd {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 70%;
}
#customersd td, #customers th {
    padding: 8px;
    text-align: center;
    color: #0a0a0a;
}
#customersd tr:nth-child(even){background-color: #ffffff;}

#customersd tr:hover {background-color: #ddd;}

#customersd th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    border-color: #b4b4b4;
    color: #0a0a0a;
    text-align: center;
}
#customerss {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 60%;
    font-size: 14px;
}
#customerss td, #customers th {
    border: 1px ;
    padding: 8px;
    text-align: left;
    color: #0a0a0a;
}
#customerss tr:nth-child(even){background-color: #ffffff;}

#customerss tr:hover {background-color: #ddd;}

#customerss th {
    padding-top: 10px;
    padding-bottom: 10px;
    text-align: left;
    border-color: #b4b4b4;
    color: #547260;
    text-align: left;
}
    .letrados {
    font-size: 14px;
    font-family: Arial, Helvetica, sans-serif; 
    padding-left: -170px;
    color: #0a0a0a;
  }
  .letradostabla{
    font-size: 16px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
    padding-top: 10px;
    padding-left: 20px;
    color: #0a0a0a;
  }
  .fuente{
    font-size: 14px;
    font-family: Arial, Helvetica, sans-serif;
    color: #0a0a0a;
  }
  .letratablapiedos{
    font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
    font-weight: bold;
    color: #0a0a0a;
    border-top: 1px;
      
  }
  .letratablar{
    font-size: 18px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: left;
    font-weight: bold;
    padding-left: 190px;     
  }
</style>    
</head>
<body>
<div class="div" >
  <table>
  <img src="img/enc.png" class="eimg"> 
  </table>
<table style="padding-top: 40px;">
  <tr  >
    <td>
    <img src="img/conserflow.png" alt="Conserflow" width="150" height="40" align="left">
    </td>
    <td class="letradostabla"><b>CONSTRUCTORA Y SERVICIOS CALDERON TORRES S.A. DE C.V. </b>
        <br><b class="letrados">FORMATO DE PERMISO</b><br>
    </td>
  </tr>
</table>
  <br><br><br>
<table id="customerss">
  <tr>
    <td width="15%">PARA: </td><td>Recursos Humanos</td>
  </tr>
  <tr>
    <td>AREA:</td><td>{{$permisos->areadmin}}</td>
  </tr>
  <tr>
    <td>PUESTO:</td><td> {{$permisos->nombreemp}}</td>
  </tr>
</table>
<p class="fuente">Solicito a ustedes el permiso para ausentarme de mis labores por el motivo siguiente:
</p>
<table class="fuente">
  <tr>
    <td><u>{{$permisos->motivo}}</u></td>
  </tr>
</table>
<p class="fuente">Bajo la característica siguiente:</p>
<br>
  @if($permisos->tipo_salida==0)

  <table id="customers" >
  <tr>
    <td>PERMISO TEMPORAL</td>
  </tr>
</table><br>
  <table id="customers" >
  <tr>
    <th style="text-align: center; border: 1px solid #ddd;">FECHA</th>
  </tr>
  <td><?=date_format(date_create($permisos->fech_temp), 'd/m/Y')?></td>
</table>
<table id="customers">
  <tr>
    <td style="text-align: center;">HORA SALIDA</td>
    <td style="text-align: center;">HORA ENTRADA</td>
  </tr>
  <tr>
    <th style="text-align: center;"> {{$horasalidafinal}} </th>
    <th  style="text-align: center;">{{$horaregresofinal}}</th>
  </tr>
</table>

    @elseif($permisos->tipo_salida==1)

<table id="customers" >
  <tr>
    <td style="border: 1px solid #ddd;">PERMISO DIA COMPLETO</td>
  </tr>
</table>
<br>
  <table id="customers">
  <tr>
    <th width="25%" style="text-align: center; border: 1px solid #ddd;">INICIO DE PERMISO</th>
    <th width="25%" style="text-align: center; border: 1px solid #ddd;">{{$dia1 }}</th>
    <th width="25%" style="text-align: center; border: 1px solid #ddd;">{{$mes1}}</th>
    <th width="25%" style="text-align: center; border: 1px solid #ddd;">{{$anio1}}</th>
  </tr>
    <tr>
      <th width="25%" style="text-align: center; border: 1px solid #ddd;">FIN DE PERMISO</th>
      <th width="25%" style="text-align: center; border: 1px solid #ddd;">{{$dia2 }}</th>
      <th width="25%" style="text-align: center; border: 1px solid #ddd;">{{$mes2}}</th>
      <th width="25%" style="text-align: center; border: 1px solid #ddd;">{{$anio2}}</th>
    </tr>
  </table>

  @endif
  <br><br><br>

  <table id="customersd" style="padding-left: 200px;">
  <tr>
      <td > Permiso con goce de sueldo </td>
      <td width="15%" style="border: 1px solid #ddd;">
      {{$goces =''}}
            <center>
            @if($congoce == 1)
            {{$goces = 'X'}}
            @endif
            @if($congoce == 0)
            {{$goces = ''}}
            @endif
            </center>
      </td>    
  </tr>
    <tr>
      <td > Permiso sin goce de sueldo </td>
      <td width="15%" style="border: 1px solid #ddd;"> 
        <center>
        @if($congoce == 1)
        {{$goces = ''}}
        @endif
        @if($congoce == 0)
        {{$goces = 'X'}}
        @endif
        <center>
      </td>
    </tr>
  </table>
<br>
    <table>
      <tr>
        <td class="letratablar" >Tehuacán, Puebla a {{$dia}} de {{$mesnombre}} del {{$anio}}</td>
      </tr>
    </table> 
  <div class="divl">
<br>
    <table  style="border-collapse: collapse; padding: 100px; padding-top: 60px; color: #547260; padding-left: 10px;">
      <tr>
      <td class="letratablapiedos">{{$permisos->NombreSolicita}}</td>
        <td></td>
      <td class="letratablapiedos">{{$permisos->NombreAutoriza}}</td>
        <td></td>
      <td class="letratablapiedos"></td>
        <td></td>
      
      </tr>
      <tr>
        <td class="letratablapiedos" style="border-top: 1px solid color: #0a0a0a;" width="140">Empleado</td>
        <td width="35">&nbsp;</td>
        <td class="letratablapiedos" style="border-top: 1px solid color: #0a0a0a;" width="140">Autorización</td>
        <td width="35">&nbsp;</td>
        <td class="letratablapiedos" style="border-top: 1px solid color: #0a0a0a;" width="140">Vo. Bo.</td>
        <td width="35">&nbsp;</td>
        
      </tr>
      <tr>
        <td class="letratablapiedos" >NOMBRE Y FIRMA</td>
        <td width="30">&nbsp; </td>
        <td class="letratablapiedos">GERENTE DEL ÁREA</td>
        <td width="30">&nbsp;</td>
        <td class="letratablapiedos">RECURSOS HUMANOS</td>
        <td width="30">&nbsp;</td>
        
      </tr>
    </table>
</div> </div> </body></html>