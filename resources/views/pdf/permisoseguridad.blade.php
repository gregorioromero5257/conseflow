<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PSE-01_F-07 Permiso General de Trabajo</title>
  <style type="text/css">


  .titulo {
    text-align: center;
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
    /* border-top: 1px solid #00FFFF; */
  }
  .hed {
    text-align: center;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 10px;
    border: 2px solid;
  }
  .lp {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 6px;
    text-align: center;
    border: 1px solid;
  }
  .letrat {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 7px;
    text-align: center;
    border: 1px solid;
  }
  .letrag {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 8px;
    background-color: #B2BABB;
    border: 1px solid;
    text-align: left;
  }
  .gris {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 8px;
    text-align: center;
    background-color: #B2BABB;
    border: 1px solid;
  }
  .lc {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 10px;
    text-align: center;
    border: 1px solid;
    background-color: #2F86DE;
  }
  </style>

</head>
<body >
  <div style="page-break-after: always;">
    <table style="border-collapse:collapse; border: 2px solid;" width="100%">
      <tr>
        <td colspan="4" style="color: #2F86DE; font-weight: bold; text-align:center; font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;">CONSERFLOW S.A. DE C.V.</td>
      </tr>
      <tr>
        <td style="border: 2px solid;" rowspan="3" width="100"><img src="img/conserflow.png" alt="Conserflow" width="100" height="30" style="padding-left: 15px;"></td>
        <td style="border: 2px solid;" rowspan="3" class="titulo">PERMISO GENERAL DE TRABAJO</td>
        <td width="50" class="hed">CÓDIGO</td>
        <td width="50" class="hed">PSE-01/F-07</td>
      </tr>
      <tr>
        <td class="hed">NIVEL</td>
        <td class="hed">00</td>
      </tr>
      <tr>
        <td class="hed">FECHA</td>
        <td class="hed">01.ABR.20</td>
      </tr>
    </table>

    <table style="border-collapse:collapse; border: 2px solid;" width="100%">
      <tr>
        <td class="gris" width="50">Folio</td>
        <td class="letrat">&nbsp;{{$arreglo['uno']->uno}}</td>
        <td class="gris" rowspan="2" width="100">Fecha de elaboración</td>
        <td class="letrat" rowspan="2" colspan="2" width="50">&nbsp;{{$arreglo['uno']->tres}}</td>
      </tr>
      <tr>
        <td class="gris">Asociado</td>
        <td class="letrat">&nbsp;{{$arreglo['uno']->dos}}</td>
      </tr>
      <tr>
        <td class="gris">Instalacion</td>
        <td class="letrat">&nbsp;{{$arreglo['uno']->cuatro}}</td>
        <td class="gris" rowspan="2">Nivel de Riesgo</td>
        @if($arreglo['uno']->seis == 0 )
        <td class="letrat" rowspan="2" style="background-color: red; "><img src="img/AX.PNG" width="28"></td>
        <td class="letrat" rowspan="2" style="background-color: #85C1E9 ; "><img src="img/B.PNG" width="28"> </td>
        @elseif($arreglo['uno']->seis == 1)
        <td class="letrat" rowspan="2" style="background-color: red; "><img src="img/A.PNG" width="28"></td>
        <td class="letrat" rowspan="2" style="background-color: #85C1E9 ; "><img src="img/BX.PNG" width="28"> </td>
        @endif

      </tr>
      <tr>
        <td class="gris">Ubicación</td>
        <td class="letrat">&nbsp;{{$arreglo['uno']->cinco}}</td>
      </tr>
    </table>

    <table style="border-collapse:collapse; border: 2px solid; font-family: Arial, Helvetica, sans-serif;
    font-size: 10px;
    text-align: center;" width="100%">
    <tr>
      <td colspan="21" class="lc"><div style="color: white;" height="10">1. DESCRIPCIÓN DEL TRABAJO</div> </td>
    </tr>
    <tr>
      <td rowspan="3" style="" width="40">Trabajo Caliente:</td>
      <td rowspan="3" width="10">SI</td>
      <td width="10">&nbsp;</td>
      <td rowspan="3" width="10">NO</td>
      <td width="10">&nbsp;</td>
      <td rowspan="3" width="40">Espacios confinados:</td>
      <td rowspan="3" width="10">SI</td>
      <td width="10">&nbsp;</td>
      <td rowspan="3" width="10">NO</td>
      <td width="10">&nbsp;</td>
      <td rowspan="3" width="40">Altura:</td>
      <td rowspan="3" width="10">SI</td>
      <td width="10">&nbsp;</td>
      <td rowspan="3" width="10">NO</td>
      <td width="10">&nbsp;</td>
      <td rowspan="3" width="40">Eléctricos:</td>
      <td rowspan="3" width="10">SI</td>
      <td width="10">&nbsp;</td>
      <td rowspan="3" width="10">NO</td>
      <td width="10">&nbsp;</td>
      <td rowspan="3" width="10">&nbsp;</td>
    </tr>
    <tr>
      @if($arreglo['dos']->uno == 1)
      <td style="border: 1px solid;">&nbsp;X</td>
      @else
      <td style="border: 1px solid;">&nbsp;</td>
      @endif
      <td style="border: 1px solid;">&nbsp;</td>

      @if($arreglo['dos']->dos == 1)
      <td style="border: 1px solid;">&nbsp;X</td>
      @else
      <td style="border: 1px solid;">&nbsp;</td>
      @endif
      <td style="border: 1px solid;">&nbsp;</td>

      @if($arreglo['dos']->tres == 1)
      <td style="border: 1px solid;">&nbsp;X</td>
      @else
      <td style="border: 1px solid;">&nbsp;</td>
      @endif
      <td style="border: 1px solid;">&nbsp;</td>

      @if($arreglo['dos']->cuatro == 1)
      <td style="border: 1px solid;">&nbsp;X</td>
      @else
      <td style="border: 1px solid;">&nbsp;</td>
      @endif
      <td style="border: 1px solid;">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="gris" width="50">Otros Trabajos:</td>
      <td colspan="20" class="letrat">&nbsp;{{$arreglo['dos']->cinco}}</td>
    </tr>
    <td class="gris" style="border-bottom: none;" width="50">Descripción de la tarea:</td>
    <td colspan="20" style="border-bottom: none;" class="letrat">&nbsp;{{$arreglo['dos']->seis}}</td>
  </tr>
</table>
<table style="border-collapse:collapse; border: 2px solid; font-family: Arial, Helvetica, sans-serif;
font-size: 10px; position:relative; top: -2px;
text-align: center;" width="100%">
<tr>
  <td class="letrat">&nbsp;</td>
</tr>
<tr>
  <td class="letrat">&nbsp;</td>
</tr>
<tr>
  <td class="letrat">&nbsp;</td>
</tr>
</table>
<table style="border-collapse:collapse; border: 2px solid;" width="100%">
  <tr>
    <td colspan="4" class="lc"><div style="color: white;" height="10">2. FECHA DE INICIO Y TÉRMINO</div></td>
  </tr>
  <tr>
    <td class="gris" width="30%">FECHA DE INICIO</td>
    <td class="gris" width="20%">HORA</td>
    <td class="gris" width="30%">FECHA DE TÉRMINO</td>
    <td class="gris" width="20%">HORA</td>
  </tr>
  <tr>
    <td class="letrat">&nbsp;{{$arreglo['tres']->uno}}</td>
    <td class="letrat">&nbsp;{{$arreglo['tres']->dos}}</td>
    <td class="letrat">&nbsp;{{$arreglo['tres']->tres}}</td>
    <td class="letrat">&nbsp;{{$arreglo['tres']->cuatro}}</td>
  </tr>
</table>

<table style="border-collapse:collapse; border: 2px solid; font-family: Arial, Helvetica, sans-serif;
font-size: 10px;
text-align: center;" width="100%">
<tr>
  <td colspan="7" class="lc"><div style="color: white;" height="15">3.REQUISITOS A CUMPLIR ANTES DE LA EJECUCION DEL TRABAJO
    <br>(marcar con una "X" o iniciales del responsable de SHSO según corresponda)</div></td>
  </tr>
  <tr>
    <td class="lc" width="45%"><div style="color: white;"> RESPONSABLES DE OPERACIÓN</div></td>
    <td class=gris width="5%">SI</td>
    <td class=gris width="5%">NO</td>
    <td class=gris width="5%">NO APLICA</td>
    <td class=gris width="10%">CONFIRMACIÓN DE SHSO</td>
    <td colspan="2" class="lc" width="30%"><div style="color: white;">EQUIPO DE PROTECCIÓN PERSONAL</div></td>
  </tr>
  <tr>
    <td class="letrag">Los factores meteorológicos permiten realizar el trabajo.</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->uno == 1 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->uno == 2 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->uno == 3 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['iniciales']}}</td>
    <td class="gris" style="padding-left: 130px;"><div style="position: relative; left:-70px;">Casco contraimpacto</div> </td>
    <td class="letrat">&nbsp;{{$arreglo['cinco']->uno == 1 ? 'X' : 'N/A'}}</td>
  </tr>
  <tr>
    <td class="letrag">La iluminación del área de trabajo es la correcta para realizar la actividad.</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->dos == 1 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->dos == 2 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->dos == 3 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['iniciales']}}</td>
    <td class="gris">Ropa de trabajo</td>
    <td class="letrat">&nbsp;{{$arreglo['cinco']->dos == 1 ? 'X' : 'N/A'}}</td>
  </tr>
  <tr>
    <td class="letrag">El área se encuentra con accesos rapidos para su entrada y salida.</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->catorce == 1 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->catorce == 2 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->catorce == 3 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['iniciales']}}</td>
    <td class="gris">Protección ocular</td>
    <td class="letrat">&nbsp;{{$arreglo['cinco']->tres == 1 ? 'X' : 'N/A'}}</td>
  </tr>
  <tr>
    <td class="letrag">Los equipos estan puestas a tierra.</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->tres == 1 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->tres == 2 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->tres == 3 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['iniciales']}}</td>
    <td class="gris">Calzado de seguridad</td>
    <td class="letrat">&nbsp;{{$arreglo['cinco']->cuatro == 1 ? 'X' : 'N/A'}}</td>
  </tr>
  <tr>
    <td rowspan="2" class="letrag">Se han instalado mamparas para proteger y aislar las personas y equipos de las áreas vecinas..</td>
    <td class="letrat" rowspan="2">&nbsp;{{$arreglo['cuatro']->cuatro == 1 ? 'X' : ''}}</td>
    <td class="letrat" rowspan="2">&nbsp;{{$arreglo['cuatro']->cuatro == 2 ? 'X' : ''}}</td>
    <td class="letrat" rowspan="2">&nbsp;{{$arreglo['cuatro']->cuatro == 3 ? 'X' : ''}}</td>
    <td class="letrat" rowspan="2">&nbsp;{{$arreglo['iniciales']}}</td>
    <td class="gris">Protección auditiva</td>
    <td class="letrat" >&nbsp;{{$arreglo['cinco']->cinco == 1 ? 'X' : 'N/A'}}</td>
  </tr>
  <tr>
    <td class="letrat" colspan="2">OTROS:&nbsp;{{$arreglo['cinco']->seis}}</td>
  </tr>
  <tr>
    <td class="letrag">Los andamios han sido inspeccionados</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->cinco == 1 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->cinco == 2 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->cinco == 3 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['iniciales']}}</td>
    <td colspan="2" class="lc"><div style="color: white;">PRECAUCIONES CONTRA INCENDIO</div></td>
  </tr>
  <tr>
    <td class="letrag">La instalación y equipos eléctricos son antiexplosivos.</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->seis == 1 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->seis == 2 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->seis == 3 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['iniciales']}}</td>
    <td class="letrag">Ayudante contraincendio</td>
    <td class="letrat">&nbsp;{{$arreglo['seis']->uno == 1 ? 'X' : 'N/A'}}</td>
  </tr>
  <tr>
    <td class="letrag">Herramientas: estan en condiciones y acordes a las tareas.</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->siete == 1 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->siete == 2 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->siete == 3 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['iniciales']}}</td>
    <td class="letrag">Manguera Contraincendio</td>
    <td class="letrat">&nbsp;{{$arreglo['seis']->dos == 1 ? 'X' : 'N/A'}}</td>
  </tr>
  <tr>
    <td class="letrag">Se ha desenergizado el equipo</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->ocho == 1 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->ocho == 2 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->ocho == 3 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['iniciales']}}</td>
    <td class="letrag">Cortina de agua</td>
    <td class="letrat">&nbsp;{{$arreglo['seis']->tres == 1 ? 'X' : 'N/A'}}</td>
  </tr>
  <tr>
    <td class="letrag">Esta limitado el acceso de personal al área de trabajo.</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->nueve == 1 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->nueve == 2 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->nueve == 3 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['iniciales']}}</td>
    <td class="letrag">Barreras y letreros de seguridad</td>
    <td class="letrat">&nbsp;{{$arreglo['seis']->cuatro == 1 ? 'X' : 'N/A'}}</td>
  </tr>
  <tr>
    <td class="letrag">Los materiales combustibles se encuentren dentro de un radio de 11 metros (35 pies) del área de trabajo.</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->diez == 1 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->diez == 2 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->diez == 3 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['iniciales']}}</td>
    <td class="letrag">Colocar lonas para cubrir drenajes y equipos delicados y humedecerlas</td>
    <td class="letrat">&nbsp;{{$arreglo['seis']->cinco == 1 ? 'X' : 'N/A'}}</td>
  </tr>
  <tr>
    <td class="letrag">El equipo de soldadura ha sido inspeccionado y se encuentra en buen estado.</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->once == 1 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->once == 2 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->once == 3 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['iniciales']}}</td>
    <td class="letrag">Extintor</td>
    <td class="letrat">&nbsp;{{$arreglo['seis']->seis == 1 ? 'X' : 'N/A'}}</td>
  </tr>
  <tr>
    <td class="letrag">Los cilindros se encuentran en posición vertical, bien asegurados y libres de fugas.</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->doce == 1 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->doce == 2 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->doce == 3 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['iniciales']}}</td>
    <td class="letrag">Eliminar todo el material combustible</td>
    <td class="letrat">&nbsp;{{$arreglo['seis']->siete == 1 ? 'X' : 'N/A'}}</td>
  </tr>
  <tr>
    <td class="letrag">Los cilindros cumplen con el estándar de identificación (Nombre del producto y rotulación NFPA o UN).</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->trece == 1 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->trece == 2 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['cuatro']->trece == 3 ? 'X' : ''}}</td>
    <td class="letrat">&nbsp;{{$arreglo['iniciales']}}</td>
    <td class="letrag">Proteger Instrumentación e iluminación</td>
    <td class="letrat">&nbsp;{{$arreglo['seis']->ocho == 1 ? 'X' : 'N/A'}}</td>
  </tr>
</table>
<table style="border-collapse:collapse; border: 2px solid; font-family: Arial, Helvetica, sans-serif;
font-size: 10px;
text-align: center;" width="100%">
<tr>
  <td class="lc"><div style="color: white;">4. PRECAUCIONES ESPECIALES Y RIESGOS POTENCIALES</div></td>
</tr>
<tr>
  <td class="letrat">&nbsp;{{$arreglo['siete']->uno}}</td>
</tr>

</table>
<table style="border-collapse:collapse; border: 2px solid; font-family: Arial, Helvetica, sans-serif;
font-size: 10px;
text-align: center;" width="100%">
<tr>
  <td class="lc"><div style="color: white;">5. VERIFICACION EN CAMPO DEL CUMPLIMIENTO DE LOS REQUISITOS SOLICITADOS</div></td>
</tr>
<tr>
  <td class="letrat">He examinado la descripción del trabajo , las precauciones y las preparaciones citadas arriba , estoy conforme que son suficientes y correctas. Se puede realizar el trabajo entre las horas citadas, debiendo revalidad por cada plazo de trabajo.</td>
</tr>
</table>

<table style="border-collapse:collapse; border: 2px solid;" width="100%">
  <tr>
    <td class="lc"><div style="color: white;">AUTORIZAN</div></td>
    <td class="lc"><div style="color: white;">NOMBRE COMPLETO</div></td>
    <td class="lc"><div style="color: white;">FIRMA</div></td>
    <td class="lc"><div style="color: white;">FECHA</div></td>
  </tr>
  <tr>
    <td class="gris">Residente de Obra:</td>
    <td class="letrat">&nbsp;{{$arreglo['ocho']->residente}}</td>
    <td class="letrat">&nbsp;</td>
    <td class="letrat">&nbsp;{{$arreglo['ocho']->dos}}</td>
  </tr>
  <tr>
    <td class="gris">Supervisor de Trabajo:</td>
    <td class="letrat">&nbsp;{{$arreglo['ocho']->supervisor}}</td>
    <td class="letrat">&nbsp;</td>
    <td class="letrat">&nbsp;{{$arreglo['ocho']->cuatro}}</td>
  </tr>
  <tr>
    <td class="gris">Responsable de SHSO:</td>
    <td class="letrat">&nbsp;{{$arreglo['ocho']->shso}}</td>
    <td class="letrat">&nbsp;</td>
    <td class="letrat">&nbsp;{{$arreglo['ocho']->seis}}</td>
  </tr>
</table>
</div>
<!-- PAGINA 2 -->
<div>


  <table style="border-collapse:collapse; border: 2px solid;" width="100%">
    <tr>
      <td colspan="11" class="lc"><div style="color: white;">6. REVALIDACIONES</div></td>
    </tr>
    <tr>
      <td class="gris" colspan="4">VALIDACION /REVALIDACIÓN</td>
      <td class="gris" colspan="4">ACEPTACIÓN DEL PERMISO</td>
      <td class="gris" colspan="3">SUSPENSIÓN DEL PERMISO</td>
    </tr>


    <tr>
      <td class="lc"><div style="color: white;">Fecha</div></td>
      <td class="lc"><div style="color: white;">Desde</div></td>
      <td class="lc"><div style="color: white;">Hasta</div></td>
      <td class="lc"><div style="color: white;">Nombre</div></td>
      <td class="lc"><div style="color: white;">Fecha</div></td>
      <td class="lc"><div style="color: white;">Desde</div></td>
      <td class="lc"><div style="color: white;">Hasta</div></td>
      <td class="lc"><div style="color: white;">Nombre</div></td>
      <td class="lc"><div style="color: white;">Hora</div></td>
      <td class="lc"><div style="color: white;">Fecha</div></td>
      <td class="lc"><div style="color: white;">Firma</div></td>
    </tr>
    @foreach ($arreglo['once'] as $key => $value)
    <tr>
      <td class="letrat">&nbsp;{{$value->uno}}</td>
      <td class="letrat">&nbsp;{{$value->dos}}</td>
      <td class="letrat">&nbsp;{{$value->tres}}</td>
      <td class="letrat">&nbsp;{{$value->e_cuatro}}</td>
      <td class="letrat">&nbsp;{{$value->cinco}}</td>
      <td class="letrat">&nbsp;{{$value->seis}}</td>
      <td class="letrat">&nbsp;{{$value->siete}}</td>
      <td class="letrat">&nbsp;{{$value->e_ocho}}</td>
      <td class="letrat">&nbsp;{{$value->nueve}}</td>
      <td class="letrat">&nbsp;{{$value->diez}}</td>
      <td class="letrat">&nbsp;</td>
    </tr>
   @endforeach
  </table>
  <table style="border-collapse:collapse; border: 2px solid; font-family: Arial, Helvetica, sans-serif;
  font-size: 10px;
  text-align: center;" width="100%">
  <tr>
    <td colspan="9" class="lc"><div style="color: white;" height="15">7.CANCELACIÓN DEL TRABAJO
      <br>(marcar con una "X" según corresponda)</div></td>
    </tr>
    <tr>
      <td colspan="9" style="text-align:left; font-family: Arial, Helvetica, sans-serif;
      font-size: 10px;">Se verifico que el trabajo:</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td class="letrat">&nbsp;{{$arreglo['nueve']->uno == 1 ? 'X' : ''}}</td>
      <td>&nbsp;</td>
      <td style="text-align: left;">Ha sido completado</td>
      <td>&nbsp;</td>
      <td class="letrat">&nbsp;{{$arreglo['nueve']->cuatro == 1 ? 'X' : ''}}</td>
      <td>&nbsp;</td>
      <td style="text-align: left;">Suspendida o no iniciada por haberse realizado observaciones de seguridad.</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td class="letrat">&nbsp;{{$arreglo['nueve']->dos == 1 ? 'X' : ''}}</td>
      <td>&nbsp;</td>
      <td style="text-align: left;">No ha sido iniciado</td>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td class="letrat">&nbsp;{{$arreglo['nueve']->tres == 1 ? 'X' : ''}}</td>
      <td>&nbsp;</td>
      <td style="text-align: left;">Término de la jornada de trabajo.</td>
      <td>&nbsp;</td>
      <td class="letrat">&nbsp;{{$arreglo['nueve']->cinco == 1 ? 'X' : ''}}</td>
      <td>&nbsp;</td>
      <td style="text-align: left;">El lugar de trabajo ha quedado en condiciones de seguridad, orden y limpieza.</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="9">&nbsp;</td>
    </tr>
  </table>

  <table style="border-collapse:collapse; border: 2px solid; font-family: Arial, Helvetica, sans-serif;
  font-size: 10px;
  text-align: center;" width="100%">
  <tr>
    <td class="lc" colspan="6"><div style="color:white;">8. TERMINACIÓN DE LOS TRABAJOS
      <br> (Por el Residente)</div></td>
    </tr>
    <tr>
      <td class="gris" colspan="3">CANCELACION DEL PERMISO (Trabajo completo)</td>
      <td class="gris" colspan="3">CANCELACION DEL PERMISO (Trabajo no completo)</td>
    </tr>
    <tr>
      <td class="lc" ><div style="color:white;">NOMBRE Y FIRMA</div></td>
      <td class="lc" ><div style="color:white;">HORA</div></td>
      <td class="lc" ><div style="color:white;">FECHA</div></td>
      <td class="lc" ><div style="color:white;">NOMBRE Y FIRMA</div></td>
      <td class="lc" ><div style="color:white;">HORA</div></td>
      <td class="lc" ><div style="color:white;">FECHA</div></td>
    </tr>
    <tr>
      <td class="letrat">&nbsp;{{$arreglo['diez']->e_uno}}</td>
      <td class="letrat">&nbsp;{{$arreglo['diez']->dos}}</td>
      <td class="letrat">&nbsp;{{$arreglo['diez']->tres}}</td>
      <td class="letrat">&nbsp;{{$arreglo['diez']->e_cuatro}}</td>
      <td class="letrat">&nbsp;{{$arreglo['diez']->cinco}}</td>
      <td class="letrat">&nbsp;{{$arreglo['diez']->seis}}</td>
    </tr>
  </table>
</div>
</body>
</html>
