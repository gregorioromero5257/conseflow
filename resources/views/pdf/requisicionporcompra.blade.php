<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Requisición</title>
  <style type="text/css">
  .letra {
    font-size: 9px;
    text-align: center;
  }
  .letra {
    text-align: center;
  }
  .letra {
    text-align: center;
    font-size: 5px;
  }
  .letra {
    text-align: center;
  }
  .letra {
    text-align: center;
  }
  .letra {
    text-align: center;
  }
  .img {
    text-align: left;
  }
  .letra {
    text-align: center;
  }
  table {
    width: 100%;
    border: 1px solid #000;
  }
  th, td {
    border: 1px solid #000;
    border-spacing: 0;
  }
  .letras {
    text-align: left;
    font-size: 8px;
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
  }
  .titulo {
    text-align: center;
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 20px;
    /* border-top: 1px solid #00FFFF; */
  }
  .hed {
    text-align: center;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 10px;
  }
  .head {
    text-align: center;
    font-family: Arial, Helvetica, sans-serif;
    font-weight: bold;
    font-size: 17px;
  }
  .letrauno {
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    font-style: italic;
    font-size: 8px;
  }
  .letrados {
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 8px;
    text-align: right;
  }

  .letratres {
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 8px;
    text-align: center;

  }
  .letracuatro {
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 8px;
    text-align: center;
    text-transform: uppercase;
  }
  .letracinco {
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 8px;
    text-align: center;
  }
  .letraseis {
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 8px;
    text-align: center;
  }
  .letraseisd {
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 10px;
    text-align: left;
  }
  .letradocumentosrequeridos{
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 6px;
    text-align: center;
    border: 1px solid #000;

  }
  .letrasiete {
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 6px;
    text-align: left;
  }
  .letraocho {
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 7px;
    text-align: center;
  }
  .letranueve {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 7px;
    text-align: left;
  }
  .letratipocompra {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 8px;
    text-align: left;
  }
  /* .circle { height: 200px; width: 200px; border-radius: 50%; background: #000000;}  */
  table {border-collapse:collapse; border: none;}
  </style>

</head>
<body >
<div style="
    border: 1px solid #000">
<table style="border: none">
<tr>
<td style="border: hidden" rowspan="4" width="148"><img src="img/conserflow.png" alt="Conserflow" width="200" height="60"</td>
<td style="border: hidden" colspan="2" rowspan="4" class="titulo">REQUISICION</td>
<td width="74" class="hed">CODIGO</td>
<td width="74">&nbsp;</td>
</tr>
<tr>
<td class="hed">REVISION</td>
<td>&nbsp;</td>
</tr>
<tr>
<td class="hed">EMISION</td>
<td>&nbsp;</td>
</tr>
</table>
</div>

  <table>
  <tr>
  <td  bgcolor="#E6E6E6" class="head">CONSERFLOW S.A. de C.V.</td>
  </tr>
  </table>

  <table width="775" >
    <tr>
      <td width="148" bgcolor="#E6E6E6" class="letrados">NOMBRE DEL PROYECTO/ SERVICIO</td>
      <td width="300" class="letracuatro">{{$proyecto_->nombre_corto}}</td>
      <td width="105" bgcolor="#E6E6E6" class="letrados">FOLIO</td>
      <td  class="letracuatro">{{$compra[0]->folio}}</td>

    </tr>
    <tr>
      <td bgcolor="#E6E6E6" class="letrados">DESCRIPCIÓN DE UTILIZACIÓN</td>
      <td class="letratres"></td>
      <td bgcolor="#E6E6E6" class="letrados">FECHA SOLICITUD</td>
      <td class="letratres">{{$compra[0]->fecha_orden}}</td>
    </tr>
  </table>
  <table width="775" >
    <tr>
      <td width="148" bgcolor="#E6E6E6" class="letrados">ÁREA QUE SOLICITA</td>
      <td width="300" class="letracuatro"></td>
      <td width="105" bgcolor="#E6E6E6" class="letrados">TIPO COMPRA</td>
      <td width="145" class="letratipocompra">&nbsp;

      </td>
      <td width="63" class="letratres">&nbsp; </td>

    </tr>
  </table>
  <table width="100%">
    <tr>
      <td width="2%" bgcolor="#E6E6E6" class="letracinco">PDA.</td>
      <td width="2%" bgcolor="#E6E6E6" class="letracinco">CANT.</td>
      <td width="2%" bgcolor="#E6E6E6" class="letracinco">UNIDAD DE MEDIDA</td>
      <td width="11%" bgcolor="#E6E6E6" class="letracinco">DESCRIPCION</td>
      <td width="2%" bgcolor="#E6E6E6" class="letracinco">PRECIO UNITARIO</td>
      <td width="2%" bgcolor="#E6E6E6" class="letracinco">MARCA</td>

   </tr>




   {{$nombreproyecto = ''}}
     @foreach($ordenesreq as $key => $comp)
      <tr>

       <td class="letraseis">{{$count}}</td>
       <td class="letraseis">{{$comp->cantidad}}</td>
       <td class="letraseis">{{$comp->unidadA}}</td>
       <td class="letraseis">{{$comp->descripcionA}} {{$comp->comentario}}&nbsp;</td>
       <td class="letraseis">{{$comp->precio_unitario}}&nbsp;</td>
       <td class="letrasiete"></td>
    </tr>
    {{$count +=1}}
      {{$nombreproyecto =$comp->nombrep}}
        @endforeach
          @for ($i = 0; $i < $valora; $i++)
          <tr>
            <td class="letraseis">{{($i+1)+$partidascount}}</td>
            <td class="letraseis">&nbsp;</td>
            <td class="letraseis">&nbsp;</td>
            <td class="letraseis">&nbsp;</td>
            <td class="letraseis">&nbsp;</td>
            <td class="letraseis">&nbsp;</td>
          </tr>
          @endfor

  </table>


  <table >
    <tr>
      <td class="letraocho">(*)Doc. Requerido: (A)Certf Calidad &nbsp;&nbsp;&nbsp; (B)Certf Calibrac.&nbsp;&nbsp;&nbsp;(D)Certf NACE&nbsp;&nbsp;&nbsp;(F)Libro ASME&nbsp;&nbsp;&nbsp;(G)Dibujos&nbsp;&nbsp;&nbsp;(H)Certf. Origen&nbsp;&nbsp;&nbsp;(I)Manuales&nbsp;&nbsp;&nbsp;(J)Certf. UL&nbsp;&nbsp;&nbsp;(K)Carta Gtia.&nbsp;&nbsp;&nbsp;(L)Reporte de Pruebas&nbsp;&nbsp;&nbsp;(M)otros<br>&nbsp;</td>
    </tr>
  </table>
  <table width="100%">
    <tr>
      <td bgcolor="#E6E6E6" width="25%" class="letraseis">SOLICITA</td>
      <td bgcolor="#E6E6E6" width="25%" class="letraseis">AUTORIZA</td>
      <td bgcolor="#E6E6E6" width="25%" class="letraseis">RECIBIDO POR ALMACEN</td>
      <td bgcolor="#E6E6E6" width="25%" class="letraseis">RECIBÍDO POR COMPRAS</td>
    </tr>
  </table>
  <table >
    <tr>
      <td width="191" class="letranueve">Fecha:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><br>&nbsp;<br>Nombre:&nbsp;&nbsp;&nbsp;<b>{{$compra[0]->nomnree}}</b><br>&nbsp;<br>Firma:</td>
      <td width="191" class="letranueve">Fecha:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><br>&nbsp;<br>Nombre:&nbsp;&nbsp;&nbsp;<b>{{$compra[0]->nombrea}}<br>&nbsp;<br>Firma:</td>
      <td width="191" class="letranueve">Fecha:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><br>&nbsp;<br>Nombre:&nbsp;&nbsp;&nbsp;<b></b><br>&nbsp;<br>Firma:</td>
        <td width="191" class="letranueve">Fecha:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><br>&nbsp;<br>Nombre:&nbsp;&nbsp;&nbsp;<b><br>&nbsp;<br>Firma:</td>
    </tr>
  </table>
  <table>
    <tr>
      <td class="letranueve">&nbsp;</td>
    </tr>
  </table>
</body>
</html>
