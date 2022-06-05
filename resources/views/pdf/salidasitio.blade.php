<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Salida...</title>
  <style media="screen">

*{
  margin:10px;
} 
table {
  border-collapse: collapse;
}
.letrados {
  font-size: 12px;
  font-family: Arial, Helvetica, sans-serif;
  text-align: center;  
}
.letraconser{
  font-size: 16px;
  font-family: Arial, Helvetica, sans-serif;
  text-align: center;
}
.letradoc {
  font-size: 12px;
  font-family: Arial, Helvetica, sans-serif;
  text-align: center;
  font-weight: bold;
}
.letrax {
  font-size: 10px;
  font-family: Arial, Helvetica, sans-serif;
  text-align: center;
}
.letradostabla{
  font-size: 10px;
  font-family: Arial, Helvetica, sans-serif;
  text-align: center;
  font-weight: bold;
  background-color: #A6A6A6;
}
.letracuatrotabla{
  font-size: 10px;
  font-family: Arial, Helvetica, sans-serif;
  text-align: center;
}
.letratablapiedos{
  font-size: 10px;
  font-family: Arial, Helvetica, sans-serif;
  text-align: center;
  border-top: 1px solid black;
}
.letratablapieuno{
  font-size: 14px;
  font-family: Arial, Helvetica, sans-serif;
  text-align: center;
  border: none;
  font-weight: bold;
}
.none{
  font-size: 12px;
  font-family: Arial, Helvetica, sans-serif;
  text-align: center;
  border: none;
}
.alm{
  font-family: Arial, Helvetica, sans-serif;
  text-align: center;
  font-weight: bold; 
  font-size: 18px;
}
</style>

</head>
<body>
<div >

    <table style="border: 1px solid black;" width="100%"> 
      <tr>
        <td rowspan="3" style="border: 1px solid black;" width="22%">
          <img  src="img/conserflow.png" alt="Conserflow" width="140px;"  >
        </td>
        <td class="letraconser" width="58%">
          CONSERFLOW S.A. DE C.V.
        </td>
        <td style="border: 1px solid black;" class="letrados" width="10%">
          CODIGO:
        </td>
        <td style="border: 1px solid black;" class="letrados" width="10%">
          PAL-01/F-08
        </td>
      </tr>
      <tr>
        <td class="letrados">
          INGENIERIA EN AUTOMATIZACION+ INSTRUMENTACION+ELECTRICA+ MANTENIMIENTO INDUSTRIAL+ 
        </td>
        <td style="border: 1px solid black;" class="letrados">
          REVISION:
        </td>
        <td class="letrados">
          01
        </td>
      </tr>
      <tr>
        <td class="letrados">
          Calle Del Mezquite Lote 5 Mza. 3, Parque Industrial Tehuacán-Miahuatlán, C.P. 75820 Tehuacán, Pue. 
          <br>RFC: CON1912026U2
        </td>
        <td style="border: 1px solid black;" class="letrados">
          EMISION:
        </td>
        <td style="border: 1px solid black;" class="letrados">
          27.ABR.20
        </td>
      </tr>
    </table>


  <p class="alm">
    SALIDA DE ALMACÉN SITIO
  </p>
    <table width="100%" style="border-collapse: collapse;" >
      <tr>
        <td style="border: 1px solid black;" width="10%" class="letradoc">
          FECHA
        </td>
        <td style="border: 1px solid black;" width="30%" class="letrax">
          {{$salidas->fecha}}
        </td>
        <td rowspan="5" width="5%">
          &nbsp;
        </td>
        <td style="border: 1px solid black;" width="25%" class="letradoc">
          NOMBRE DEL SOLICITANTE:
        </td>
        <td style="border: 1px solid black;" width="30%" class="letrax">
          {{$salidas->solicita}}
        </td>
      </tr>
      <tr>
        <td style="border: 1px solid black;" class="letradoc">
          PROYECTO:
        </td>
        <td style="border: 1px solid black;" class="letrax">
          {{$salidas->pnombre}}
        </td>
        <td style="border: 1px solid black;" class="letradoc">
          CARGO:
        </td>
        <td style="border: 1px solid black;" class="letrax">
          {{$salidas->punombre}}
        </td>
      </tr>
      <tr>
        <td style="border: 1px solid black;" class="letradoc">
          SALIDA A:
        </td>
        <td style="border: 1px solid black;" class="letrax">
          {{$salidas->ubicacion}}
        </td>
        <td style="border: 1px solid black;" class="letradoc">
          AREA:
        </td>
        <td style="border: 1px solid black;" class="letrax">
        {{$salidas->puarea}}
        </td>
      </tr>
    </table>
    <table border="1" style="border-collapse: collapse;" width="100%">
      <tr>
        <th rowspan="2" class="letradostabla"  width="10%">
          PARTIDA
        </th>
        <th rowspan="2" class="letradostabla"  width="5%">
          CANTIDAD
        </th>
        <th rowspan="2" class="letradostabla"  width="5%">
          UNIDAD
        </th>
        <th rowspan="2" class="letradostabla"  width="5%">
          SALIDA
        </th>
        <th rowspan="2" class="letradostabla"  width="55%">
          DESCRIPCION DEL MATERIAL
        </th>
        <th class="letradostabla"  width="10%">
          FECHA DE ENTREGA:
        </th>
        <th class="letradostabla"  width="10%">
          &nbsp;
        </th>
      </tr>
      <tr>
        <th  class="letradostabla" >
          CANTIDAD
        </th>
        <th  class="letradostabla" >
          OBSERVACIONES
        </th>
      </tr>
      @foreach($partidas as $key => $par)
      
      <tr >
        <td class="letracuatrotabla">
           {{$key + 1}}
        </td>
        <td class="letracuatrotabla">
          {{$par->cantidad}}
        </td>
        <td  class="letracuatrotabla">
          {{$par->unidad}}
        </td>
        <td  class="letracuatrotabla">
        {{$salidas->fecha}}
        </td>
        <td  class="letracuatrotabla">
          {{$par->descripcion}}
        </td>
        <td class="letracuatrotabla">
          &nbsp;
        </td>
        <td class="letracuatrotabla">
          &nbsp;
        </td>
      </tr>
    {{$count +=1}}
      @endforeach
      @for ($i = 0; $i < $tamanio; $i++)
      <tr>
        <td class="letracuatrotabla">
          &nbsp;
        </td>
        <td class="letracuatrotabla"></td>
        <td class="letracuatrotabla"></td>
        <td class="letracuatrotabla"></td>
        <td class="letracuatrotabla"></td>
        <td class="letracuatrotabla"></td>
        <td class="letracuatrotabla"></td>
      </tr>
      @endfor
    </table>
    <div style="page-break-inside: avoid;">
    <table width="100%">
    <tr>
      <td class="letratablapieuno" width="17%">
        SOLICITA
      </td>
      <td  width="3%">
        &nbsp;
      </td>
      <td class="letratablapieuno" width="17%">
        ENTREGA
      </td>
      <td  width="3%">
        &nbsp;
      </td>
      <td class="letratablapieuno" width="17%">
        AUTORIZA
      </td>
      <td  width="3%">
        &nbsp;
      </td>
      <td class="letratablapieuno" width="17%">
        TRANSPORTA
      </td>
      <td  width="3%">
        &nbsp;
      </td>
      <td class="letratablapieuno" width="17%">
        RECIBE
      </td>
      <td  width="3%">
        &nbsp;
      </td>
    </tr>
    <tr>
      <td class="none">
       {{$salidas->solicita}}
      </td>
      <td>
        &nbsp;
      </td>
      <td class="none">
        {{$salidas->entrega}}
      </td>
      <td>
        &nbsp;
      </td>
      <td class="none">
        {{$salidas->autoriza}}
      </td>
      <td>
        &nbsp;
      </td>
      <td class="none">
        {{$salidas->recibe}}
      </td>
      <td>
        &nbsp;
      </td>
      <td class="none">
        {{$salidas->recibe}}
      </td>
    </tr>
    <tr>
      <td class="letratablapiedos" >
        NOMBRE Y FIRMA
      </td>
      <td>
        &nbsp;
      </td>
      <td class="letratablapiedos">
        NOMBRE Y FIRMA
        </td>
      <td>
        &nbsp;
      </td>
      <td class="letratablapiedos" >
        NOMBRE Y FIRMA
      </td>
      <td>
        &nbsp;
      </td>
      <td class="letratablapiedos">
        NOMBRE Y FIRMA
      </td>
      <td>
        &nbsp;
      </td>
      <td class="letratablapiedos">
        NOMBRE Y FIRMA
      </td>
      <td>
        &nbsp;
      </td>
    </tr>
  </table>
  </div>
</div>
</body>
</html>
