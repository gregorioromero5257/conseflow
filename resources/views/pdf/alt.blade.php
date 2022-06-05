<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> -->
  <title>Alta de Personal...</title>
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
    font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
    border: 2px;
  }
  .letrados {
    font-size: 10px;
    font-family: Arial, Helvetica, sans-serif;
    border: 1px;
  }
  .letrat{
    font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: left;
    font-weight: bold;
  }
  .letratablar{
    font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: left;
    font-weight: bold;
    padding-left: 10px;


  }
  .letratablam{
    font-size: 14px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: left;
    font-weight: bold;
    padding-left: 10px;
    color: #547260;
  }
  .letradostabla{
    font-size: 10px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
    font-weight: bold;
    color: #3366ff;
  }
  .esp{
    font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
    font-weight: bold;
    border-bottom: 1px solid black;
    height: 20px;
  }
  .letratablapieuno{
    font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
    font-weight: bold;
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

  .letraalt{
    font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: left;
    font-weight: bold;
  }
  .tabn{
    border-collapse: collapse;
    width: 100%;
    padding-top: 10px;
  }
  .con{
    padding: 1px;
    text-align: left;
    font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: left;
    font-weight: bold;
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
    font-size: 12px;
    
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
#customersd {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 30%;
    float: left;
}
#customersd td, #customers th {

    padding: 8px;
    text-align: center;
    color: #547260;
    font-size: 12px;

}
#customersd tr:nth-child(even){background-color: #f2f2f2;}

#customersd tr:hover {background-color: #ddd;}

#customersd th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    font-size: 12px;
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

</style>

</head>
<body >
<div class="div" >

        <table style="padding-top: -5px;" align="center">
          <tr>
            <td class="letratablam">ALTA DE PERSONAL</td>
          </tr>
        </table>
        <table align="right" style="border-collapse: collapse; border: 1px ridge; padding-top: -30px; padding-right: -10px; border:.5px solid #b4b4b4; color: #547260;">
          <tr>
            <td >REV. 01</td>
          </tr>
        </table>
      </table>
  <div >

<table id="customers">
<tr>
  <th>NOMBRE DEL EMPLEADO</th>
  <th>LUGAR DE NACIMIENTO</th>
  <th>FECHA DE NACIMIENTO</th>

</tr>
<tr>
  <td>{{$empleado->empleado}}</td>
  <td>{{$empleado->lug_nac}}</td>
  <td><?=date_format(date_create(strftime( $empleado->fech_nac)), 'd/m/Y')?></td>
</tr>
</table>
<table id="customers">
<tr>
<th>EDAD</th>
  <th>ESTADO CIVIL</th>
  <th>TELEFONO</th>
  <th>CURP</th>
  <th>NSS</th>
  <th>RFC</th>
</tr>
<tr>
<td>{{$mescumpleaños->edad}}</td>
  <td>{{$empleado->civil}}</td>
  <td><?= implode(',', array_column($telefonos->toArray(), 'tel_celular'));?></td>
  <td>{{$empleado->curp}}</td>
  <td>{{$empleado->nss}}</td>
  <td>{{$empleado->rfc}}</td>
</tr>
</table>
<!-- <h3 class="letrat">DOMICILIO</h3> -->
<table id="customers">
<tr>
<th>CALLE</th>
<th>NUMERO EXTERIOR</th>
<th>NUMERO INTERIOR</th>
<th>COLONIA</th>
</tr>
<tr>
<td><?= implode(',', array_column($domicilios->toArray(), 'calle'));?></td>
<td><?= implode(',', array_column($domicilios->toArray(), 'numero_exterior'));?></td>
<td><?= implode(',', array_column($domicilios->toArray(), 'numero_interior'));?></td>
<td><?= implode(',', array_column($domicilios->toArray(), 'colonia'));?></td>
</tr>
</table>
<table id="customers">
<tr>
  <th>FECHA INGRESO</th>
  <th>PUESTO</th>
  <th>DEPARTAMENTO</th>
</tr>
<tr>
  <td>{{$fecha_final_ingreso}}</td>
  <td>{{$empleado->puesto}}</td>
  <td>{{$empleado->dep}}</td>
</tr>
</table>
<table id="customers">
<tr>
  <th>TALLA OVEROL</th>
  <th>TALLA ZAPATOS</th>
</tr>
<tr>
  <td>{{$empleado->overol}}</td>
  <td>{{$empleado->botas}}</td>
</tr>
</table>
<br>
<table id="customers">
@foreach ($beneficiarios as $item)
<tr>
  <th>BENEFICIARIO</th>
  <th>PARENTESCO</th>
  <th>TELEFONO</th>
  </tr>
  <tr>
  <td>{{$item->nombre}}</td>
  <td>{{$item->parentesco}}</td>
  <td>{{$item->tel_celular}}</td>
  </tr>
  @endforeach
</table>
<br>


   @if($quincenal_semanal->tipo_nomina_id==2)

  <table id="customers" >
  <tr>
    <td>NOMINA QUINCENAL</td>

  </tr>
</table><br>
  <table id="customers" >
  <tr>
    <th>BRUTO</th>
    <th>NETO</th>
  </tr>
  <tr>
    <td>$</td>
    <td>${{$sueldos == null ? '' : $sueldos->sueldo_mensual}}</td>
  </tr>
  </table>
     <br>
      <br>

@elseif($quincenal_semanal->tipo_nomina_id==1)

  <table id="customers" >
  <tr>
    <td>NOMINA SEMANAL</td>
  </tr>
</table><br>
  <table id="customers" >
  <tr>
    <th>BRUTO</th>
    <th>NETO</th>
  </tr>
  <tr>
    <td>$</td>
    <td>$</td>
  </tr>
  </table>
<br>
<br>
    @endif

<table id="customers">
          <tr>
            <td class="letratablar" >TEHUACÁN, PUEBLA A {{strtoupper($fecha_final_ingreso)}}</td>
          </tr>
        </table>
        <div class="divl">

        <table  style="border-collapse: collapse; padding: 100px; padding-top: 60px; color: #547260; padding-left: 150px;">

          <tr>
            <td class="letratablapiedos" style="border-top: 1px solid color: #547260;" width="125">Vo. Bo.</td>
            <td width="30">&nbsp;</td>
            <td class="letratablapiedos" style="border-top: 1px solid color: #547260;" width="125">Vo. Bo.</td>

          </tr>
          <tr>
            <td class="letratablapiedos" >GERENTE DEL AREA</td>
            <td width="30">&nbsp;</td>
            <td class="letratablapiedos">RECURSOS HUMANOS</td>

          </tr>
        </table>
  </div>
</div>


</body>
</html>
