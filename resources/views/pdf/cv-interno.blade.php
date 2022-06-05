<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CV Interno...</title>
    
    <style>
body {
    font-size: 13px;
}
.grid {
    display: inline;
    /* float: right;
    margin-left: 10px;
    margin-right: 10px; */
    font-family: Arial, Helvetica, sans-serif;
    
}
.logo {
    /* margin-right:50px;
    margin-left: 0px;
    padding: 10px 0; */
}
.clear {
    clear: both;
}
.letratablar{
    font-size: 16px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: left;
    font-weight: bold;
    padding-left: 10px;   
  }
.div {
    float:center;
    width: 12%;
    height: 11%;
    border: 1px solid #ddd;
    margin-left:110px;
    margin-right:-110px;
    margin-top: 55px;
    border-radius: 10px;
    position: absolute;
    top: 167px;
    left: 460px;    
}
.divs {
    /* float:center;
    width: 12%;
    height: 11%;
    border: 1px solid #ddd;
    margin-left:5px;
    margin-right:-5px;
    margin-top: 55px;
    border-radius: 10px;
    position: absolute;
    top: 147px;
    left: 460px;  */
    position:absolute;
    z-index:2;
    left:0px; 
    top:100px;
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
#customerss {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 70%;
    padding-left: 150px;
}
#customerss td, #customers th {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
    color: #547260;
}
#customerss tr:nth-child(even){background-color: #f2f2f2;}

#customerss tr:hover {background-color: #ddd;}

#customerss th {
    border: 1px solid #b4b4b4;
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    border-color: #b4b4b4;
    color: #547260;
    text-align: center;
}
.alt{
    height: 50px;
}

    </style>
</head>
<body>
   
<img src="img/conserflow.png" alt="Conserflow" width="175" height="110" class="grid logo">
        <div class="clear"></div>

    <div style="position:absolute;
                z-index:2;
                left:400px; 
                top:0px;">
            <p class="grid">
            <b>CONSERFLOW, S.A. DE C.V. </b> <br>
            <b>RFC:</b> CON19120226U2 <br>
            <b>DIRECCIÓN:</b>Calle Del Mezquite Lote 5 Mza. 3, Col. <br>
            Santa Clara. Parque Industrial Tehuacan-Miahuatlán, <br>
            Santiago Miahuatlan. C.P. 75820. Puebla, México
            </p>
        
    </div>
 

    <div class="divs"> <img src="img/bart.png"  width="140px"></div>

    <table id="customerss">
    <tr>
        <th style="width: 190px;">NOMBRE</th>
        <th style="width: 120px;">PUESTO</th>
        <th>TELEFONO</th>
        <th>CORREO</th>
    </tr>
    <tr>
        <td>{{ ucwords(mb_strtolower($empleado->nombre))}} {{ucwords(mb_strtolower($empleado->ap_paterno))}} {{ucwords(mb_strtolower($empleado->ap_materno)) }}</td>
        <td>{{ $empleado->puesto }}</td>
        <td> <?= implode(',', array_column($telefonos->toArray(), 'nombre'));?> </td>
        <td> <?= implode(',', array_column($correos->toArray(), 'nombre'));?> </td>
    </tr>
  </table>
<div class="alt">&nbsp;</div>

    <table id="customers">
    <tr>
       <td class="letratablar" >Experiencia laboral</td>
    </tr>
    </table> 

    <table id="customers">
    <tr>
        <th>EMPRESA</th>
        <th>PERIODO</th>
        <th>PUESTO</th>
        <th>ACTIVIDADES</th>
    </tr>
  @foreach ($explabs as $explab)
   <tr>
        <td>{{ $explab->empresa }}</td>
        <td>Del <?=date_format(date_create($explab->fecha_inicio), 'd/m/Y')?> al <?=date_format(date_create($explab->fecha_termino), 'd/m/Y')?></td>
        <td>{{ $explab->puesto }}</td>
        <td>{{ $explab->actividades }}</td>
  </tr>
  @endforeach
  </div>
<div>
<br>
    <table id="customers">
    <tr>
        <td class="letratablar" >Escolaridad</td>
    </tr>
    </table> 
    <table id="customers">
    <tr>
        <th>GRADO</th>
        <th>PERIODO</th>
        <th>TITULO OBTENIDO</th>
        <th>DOCUMENTO NUMERO</th>
    </tr>
  @foreach ($escolaridades as $escolaridad)
    <tr>
        <td>{{ $escolaridad->grado }}</td>
        <td>Del <?=date_format(date_create($escolaridad->fecha_inicio), 'd/m/Y')?> al <?=date_format(date_create($escolaridad->fecha_termino), 'd/m/Y')?></td>
        <td>{{ $escolaridad->titulo }}</td>
        <td>{{ $escolaridad->cedula_prof }}</td>
    </tr>
  @endforeach
    </table>
</div>
</body>

</html>