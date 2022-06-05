<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>RECIBO DE PAGO DE NÓMINA...</title>
<style media="screen">

@page {
                margin-top: 3cm;|
                margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 2cm;

            }

            header {

              position: fixed;
              top: -100px;
              left: 0px;
              right: 0px;
              height: 20px;
          }
          footer {
                position: fixed;
                bottom: -40px;
                left: 0px;
                right: 0px;
                height: 20px;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 8px;
            }
            bottom-right {
            content: counter(page) " of " counter(pages);
            }
            @bottom-right {
                    content: counter(page) " of " counter(pages);
            }

            .custom-footer-page-number:after {
                content: counter(page);
            }
.pagenum:before {
    content: counter(page);
}



.letradostabla{
    font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
}

body {
  margin: 0px;
}


p.red{
  background-color:#ddd;
  font-family: Arial, sans-serif;
  font-size: 10px;

}
.tam{
  font-family: Arial, sans-serif;
  font-size: 6px;
  text-align: center;
}

th.bord{
  border-color: #b4b4b4;
  border-style: solid;
  border-width: 1px 1px 1px 1px;
  text-align:center;
}
td.lig{
  font-weight: normal;
  font-size: 8px;
}
th.bordd{
  background-color: #ddd;
  text-color: white;
  text-align: center;
}
td.jus{
  text-align:center;
}
#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    font-size: 8px;
    width: 100%;
}
#customers td, #customers th {
    border: 1px solid #ddd;
    padding: 2px;
    text-align: center;
}
#customers tr:nth-child(even){background-color: #f2f2f2;}



#customers th {

    text-align: left;
    border-color: #b4b4b4;
    text-align: center;
}
.letter{
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
.hh{
  font-family: Arial, Helvetica, sans-serif;
  font-size: 8px;
}
/* @media print {
  footer {page-break-after: always;}
} */

</style>

</head>
<body >

  <header>
      <div style="position: absolute; left: 600px; top:20px;">
        <table>
          <tr>
            <th rowspan="2" width="15%">&nbsp;</th>
            <th width="15%" class="hh"> <div style="background-color: #ddd;">TIPO DE COMPROBANTE</div> </th>
          </tr>
            <tr>
            <th class="hh" >NOMINA</th>
          </tr>
        </table>
      </div>
      <table style="width:65%;">
          <tr>
            <th >
              @if($emisor['rfc'] === 'CON1912026U2')
              <img src="img/conserflow.png" alt="Conserflow" width="150" height="60"class="img">
              @elseif($emisor['rfc'] === 'CSC050609LF7')
              <img src="img/CSCT.png" alt="csct" width="150" height="60"class="img">
              @endif
            </th>
            @if($emisor['rfc'] === 'CON1912026U2')
            <th class="letter"><b>CONSERFLOW S.A. DE C.V.  <br>RECIBO DE PAGO DE NÓMINA</th>
              @elseif($emisor['rfc'] === 'CSC050609LF7')
              <th class="letter"><b>CONSTRUCTORA Y SERVICIOS CALDERON TORRES S.A. DE C.V.  <br>RECIBO DE PAGO DE NÓMINA</th>
              @endif


    </table>
    </header>

  <footer>
      <table width="100%">
        <tr>
        <td  colspan="2" style="border-top: 1px solid;">&nbsp;</td>
        </tr>
        <tr>
          <td sty> ESTE DOCUMENTO ES UNA REPRESENTACION IMPRESA DE UN CFDI  V3.3</td>
          <td   class="custom-footer-page-number">
                  PÁGINA:
           </td>
        </tr>
      </table>
   </footer>
<main>
<table>
    <tr>
    <p style="border-bottom: 2px solid #C0C0C0; font-family: Arial, sans-serif; font-size: 11px; text-align:center;">
    <b>EMISOR</b></p>
    </tr>
  </table>
  <table id="customers" >
      <tr>
        <th >RFC</th>
        <th >RAZÓN SOCIAL</th>
        <th >DOMICILIO</th>
        <th >COLONIA</th>
        <th>MUNICIPIO</th>
      </tr>
      <tr>
        <td>{{$emisor['rfc']}}</td>
        <td>{{$emisor['nombre']}}</td>
        <td>{{$emisor['calle']}}</td>
        <td>{{$emisor['colonia']}}</td>
        <td>{{$emisor['municipio']}}</td>
      </tr>
      <tr>
        <th>ENTIDAD FEDERATIVA</th>
        <th >NUM. EXT</th>
        <th >NUM. INT</th>
        <th >C.P.</th>
        <th >RÉGIMEN FISCAL</th>

      </tr>
      <tr>
        <td>{{$emisor['entidad_federativa']}}</td>
        <td>{{$emisor['numero_exterior']}}</td>
        <td>{{$emisor['numero_interior']}}</td>
        <td>{{$emisor['codigo_postal']}}</td>
        <td>{{$emisor['regimenfiscal']}}</td>

      </tr>
      <tr>
        <th >SERIE</th>
        <th >FOLIO</th>
        <th >LUGAR EMISION</th>
        <th >FECHA Y HORA DE EMISION</th>
      </tr>
      <tr>
        <td>{{$general['serie']}}</td>
        <td>{{$general['folio']}}</td>
        <td>{{$general['lugar_expedicion']}}</td>
        <td>{{$general['fecha']}}</td>
      </tr>
  </table>
<div height="10px;">&nbsp;</div>
  <table>
    <tr>
    <p style="border-bottom: 2px solid #C0C0C0; font-family: Arial, sans-serif; font-size: 11px; text-align:center;">
    <b>RECEPTOR</b></p>
    </tr>
  </table>


<table id="customers" >
    <tr>
      <th >RFC</th>
      <th >NOMBRE</th>
      <th >USO DE CFDI</th>
      <th >NO. SEGURIDAD SOCIAL</th>
      <th >CURP</th>
    </tr>
    <tr>
      <td>{{$general['rfc_r']}}</td>
      <td>{{$empleado->empleado}}</td>
      <td>{{$general['usocfdi']}}</td>
      <td>{{$general['nss']}}</td>
      <td>{{$general['curp']}}</td>
    </tr>
    <tr>
      <th >NO. EMPLEADO</th>
      <th >PUESTO</th>
      <th >DEPARTAMENTO</th>
      <th >ANTIGUEDAD</th>
      <th >RIESGO DE PUESTO</th>
    </tr>
    <tr>
      <td>{{$general['numempleado']}}</td>
      <td>{{$general['puesto']}}</td>
      <td>{{$general['departamento']}}</td>
      <td>{{$general['antiguedad']}}</td>
      <td>
        @if($general['riesgopuesto'] == 1)
        Clase I
        @elseif($general['riesgopuesto'] == 2)
        Clase II
        @elseif($general['riesgopuesto'] == 3)
        Clase III
        @elseif($general['riesgopuesto'] == 4)
        Clase IV
        @elseif($general['riesgopuesto'] == 5)
        Clase V
        @elseif($general['riesgopuesto'] == 99)
        No Aplica
        @endif
      </td>
    </tr>
    <tr>
    <th>TIPO CONTRATO:</th>
    <th>FECHA DE INICIO RELACION LABORAL:</th>
    <th>PERIODICIDAD DE PAGO</th>
    <th>SALARIO BASE</th>
      <th>SALARIO DIARIO</th>

    </tr>
    <tr>
      <td>{{$general['tipocontrato']}}</td>
      <td>{{$general['fechainiciorellaboral']}}</td>
      <td>
        @if($general['periodicidadpago'] == '01')
        Diario
        @elseif($general['periodicidadpago'] == '02')
        Semanal
        @elseif($general['periodicidadpago'] == '03')
        Catorcenal
        @elseif($general['periodicidadpago'] == '04')
        Quincenal
        @elseif($general['periodicidadpago'] == '05')
        Mensual
        @elseif($general['periodicidadpago'] == '06')
        Bimestral
        @elseif($general['periodicidadpago'] == '07')
        Unidad obra
        @elseif($general['periodicidadpago'] == '08')
        Comisión
        @elseif($general['periodicidadpago'] == '09')
        Precio alzado
        @elseif($general['periodicidadpago'] == '10')
        Decenal
        @elseif($general['periodicidadpago'] == '99')
        Otra Periodicidad
        @endif
      </td>
      <td>{{$general['sbc']}}</td>
      <td>{{$general['sdi']}}</td>
    </tr>
    <tr>
    <th>REGIMEN DE CONTRATACION:</th>
    <th>TIPO DE JORNADA:</th>
      <th>SINDICALIZADO</th>
      <th>CLAVE ENTIDAD FEDERATIVA</th>
    </tr>
    <tr>
    <td>

      @if($general['tiporegimen'] == '02')
      Sueldos (Incluye ingresos señalados en la fracción I del artículo 94 de LISR)
      @elseif($general['tiporegimen'] == '03')
      Jubilados
      @elseif($general['tiporegimen'] == '04')
      Pensionados
      @elseif($general['tiporegimen'] == '05')
      Asimilados Miembros Sociedades Cooperativas Produccion
      @elseif($general['tiporegimen'] == '06')
      Asimilados Integrantes Sociedades Asociaciones Civiles
      @elseif($general['tiporegimen'] == '07')
      Asimilados Miembros consejos
      @elseif($general['tiporegimen'] == '08')
      Asimilados comisionistas
      @elseif($general['tiporegimen'] == '09')
      Asimilados Honorarios
      @elseif($general['tiporegimen'] == '10')
      Asimilados acciones
      @elseif($general['tiporegimen'] == '11')
      Asimilados otros
      @elseif($general['tiporegimen'] == '12')
      Jubilados o Pensionados
      @elseif($general['tiporegimen'] == '13')
      Indemnización o Separación
      @elseif($general['tiporegimen'] == '99')
      Otro Regimen
      @endif
    </td>
      <td>
        @if($general['tipojornada'] == '01')
        Diurna
        @elseif($general['tipojornada'] == '02')
        Nocturna
        @elseif($general['tipojornada'] == '03')
        Mixta
        @elseif($general['tipojornada'] == '04')
        Por hora
        @elseif($general['tipojornada'] == '05')
        Reducida
        @elseif($general['tipojornada'] == '06')
        Continuada
        @elseif($general['tipojornada'] == '07')
        Partida
        @elseif($general['tipojornada'] == '08')
        Por turnos
        @elseif($general['tipojornada'] == '99')
        Otra Jornada
        @endif
      </td>
      <td>{{$general['sindicalizado']}}</td>
      <td>{{$general['cef']}}</td>
    </tr>
</table>
<div height="10px;">&nbsp;</div>
<!-- DATOS GENERALES -->
<table>
    <tr>
    <p style="border-bottom: 2px solid #C0C0C0; font-family: Arial, sans-serif; font-size: 11px; text-align:center;">
    <b>DATOS GENERALES</b></p>
    </tr>
  </table>


<table id="customers" >
    <tr>
      <th >TIPO DE NÓMINA:</th>
      <th >FECHA PAGO:</th>
      <th >FECHA INICIAL DE PAGO:</th>
      <th >FECHA FINAL DE PAGO</th>
      <th >NO. DE DIAS PAGADOS:</th>
    </tr>
    <tr>
      <td>Nómina extraordinaria</td>
      <td>{{$general['fecha_pago']}}</td>
      <td>{{$general['fecha_pago_i']}}</td>
      <td>{{$general['fecha_pago_f']}}</td>
      <td>{{$general['dias']}}</td>
    </tr>
    <tr>
      <th >FORMA PAGO:</th>
      <th >METODO DE PAGO:</th>
      <th >MONEDA</th>
      <th >TIPO DE CAMBIO</th>
    </tr>
    <tr>
      <td>{{$forma_pago_clave->descripcion}}</td>
      <td>{{$metodo_pago_clave->descripcion}}</td>
      <td>{{$general['moneda']}}</td>
      <td>{{$general['tipo_cambio']}}</td>
    </tr>
</table>
<div height="10px;">&nbsp;</div>
<!-- CONCEPTO -->
  <table>
  <tr>
  <p style="border-bottom: 2px solid #C0C0C0; font-family: Arial, sans-serif; font-size: 11px; text-align:center;">
  <b>CONCEPTOS</b></p>
  </tr>
  </table>

  <table style="font-family: Arial, sans-serif; font-size: 8px; width: 100%;">
  <tbody>
    <tr>
      <th class="bord">CLAVE <br>UNIDAD</th>
      <th class="bord">CLAVE <br>PRODUCTO/SERVICIO</th>
      <th class="bord">NUM <br>IDENTIFICACIÓN</th>
      <th class="bord">DESCRIPCIÓN</th>
      <th class="bord">VALOR UNITARIO</th>
      <th class="bord">CANTIDAD</th>
      <th class="bord">IMPORTE</th>
    </tr>


    <tr>
      <td  class="jus">{{$general['clave_unidad']}}</td>
      <td  class="jus">{{$general['clave']}}</td>
      <td  class="jus"></td>
      <td  class="jus">{{$general['descripcion']}}</td>
      <td  class="jus" tyle="text-align:right;">{{number_format($general['valor_unitario'],2)}}</td>
      <td  class="jus" style="text-align:right;">{{$general['cantidad']}}</td>
      <td  class="jus" style="text-align:right;">{{number_format($general['importe'],2)}}</td>
    </tr>




    </tbody>
  </table>
  <div height="10px;">&nbsp;</div>
  @if ($general['estado_vista'] == 2 || $general['estado_vista'] == 3)

    <!-- PERCEPCIONES -->
    <table>
    <tr>
    <p style="border-bottom: 2px solid #C0C0C0; font-family: Arial, sans-serif; font-size: 11px; text-align:center;">
    <b>PERCEPCIONES</b></p>
    </tr>
    </table>
  <table style="font-family: Arial, sans-serif; font-size: 8px; width: 100%; text-align:center;">
      <tr>
        <th  class="bord">TIPO DE PERCEPCION:</th>
        <th  class="bord">CLAVE:</th>
        <th  class="bord">CONCEPTO:</th>
        <th  class="bord">IMPORTE GRAVADO:</th>
        <th  class="bord">IMPORTE EXENTO:</th>
      </tr>
      @foreach ($percepciones as $key_p => $value_p)
      <tr>
        <td>{{$value_p->descripcion}}</td>
        <td>{{$value_p->c_tipopercepcion}}</td>
        <td>{{$value_p->concepto}}</td>
        <td>{{$value_p->importegravado}}</td>
        <td>{{$value_p->importeexento}}</td>
      </tr>
      @endforeach
      <tr>
      <td colspan="2" >&nbsp;</td>
      <td><b>Total Percepciones</b> </td>
      <td>{{$percepciones_totales->importegravado}}</td>
      <td>{{$percepciones_totales->importeexento}}</td>
  </table>
  <!-- TOTAL PERCEPCIONES -->
  <table>
    <tr>
    <p style="border-bottom: 2px solid #C0C0C0; font-family: Arial, sans-serif; font-size: 11px; text-align:center;">
    <b>TOTAL PERCEPCIONES</b></p>
    </tr>
    </table>
  <table style="font-family: Arial, sans-serif; font-size: 8px; width: 100%; text-align:center;">
      <tr>
        <th width="25%"  class="bord">TOTAL PERCEPCIONES:</th>
        <th width="25%"  class="bord">TOTAL EXENTO:</th>
        <th width="25%"  class="bord">TOTAL GRAVADO:</th>
        <th width="25%" >&nbsp;</th>
      </tr>
      <tr>
        <td>{{$TotalSueldos}}</td>
        <td>{{$percepciones_totales->importeexento}}</td>
        <td>{{$percepciones_totales->importegravado}}</td>
        <td>&nbsp;</td>
      </tr>
  </table>
   <!-- DEDUCCIONES -->
   <div style="page-break-inside: avoid; width: 100%; bottom: 0px;">
   <table>
    <tr>
    <p style="border-bottom: 2px solid #C0C0C0; font-family: Arial, sans-serif; font-size: 11px; text-align:center;">
    <b>DEDUCCIONES</b></p>
    </tr>
    </table>
  <table style="font-family: Arial, sans-serif; font-size: 8px; width: 100%; text-align:center;">
      <tr>
        <th  class="bord">TIPO DE DEDUCCION:</th>
        <th  class="bord">CLAVE:</th>
        <th  class="bord">CONCEPTO:</th>
        <th  class="bord">IMPORTE:</th>
      </tr>
      @foreach ($deducciones as $key_d => $value_d)
      <tr>
        <td>{{$value_d->descripcion}}</td>
        <td>{{$value_d->c_tipodeduccion}}</td>
        <td>{{$value_d->concepto}}</td>
        <td>{{$value_d->importe}}</td>
      </tr>
      @endforeach
      <tr>
      <td colspan="2" >&nbsp;</td>
      <td><b>Total Deducciones</b> </td>
      <td>{{$TotalOtrasDeducciones}}</td>
  </table>

  <!-- TOTAL DEDUCCIONES -->
  <table>
    <tr>
    <p style="border-bottom: 2px solid #C0C0C0; font-family: Arial, sans-serif; font-size: 11px; text-align:center;">
    <b>TOTAL DEDUCCIONES</b></p>
    </tr>
    </table>
  <table style="font-family: Arial, sans-serif; font-size: 8px; width: 100%; text-align:center;">
      <tr>
        <th width="25%"  class="bord">TOTAL OTRAS DEDUCCIONES:</th>
        <th width="25%"  class="bord">TOTAL IMPUESTOS RETENIDOS:</th>
        <th width="25%" colspan="2">&nbsp;</th>
      </tr>
      <tr>
        <td>{{$TotalOtrasDeducciones}}</td>
        <td>0.00</td>
        <td colspan="2">&nbsp;</td>
      </tr>
  </table>
  </div>

  @endif

<!-- OTROS PAGOS -->
<table>
  <tr>
  <p style="border-bottom: 2px solid #C0C0C0; font-family: Arial, sans-serif; font-size: 11px; text-align:center;">
  <b>OTROS PAGOS</b></p>
  </tr>
  </table>
<table style="font-family: Arial, sans-serif; font-size: 8px; width: 100%; text-align:center;">
    <tr>
      <th  class="bord">TIPO DE PAGO</th>
      <th  class="bord">CLAVE</th>
      <th  class="bord">CONCEPTO</th>
      <th  class="bord">IMPORTE</th>
    </tr>
    @foreach ($partida_d as $key => $value)
    <tr>
      <td>{{$value->descripcion}}</td>
      <td>{{$value->c_tipootropago}}</td>
      <td>{{$value->descripcion}}</td>
      <td>{{$value->importe}}</td>
    </tr>
    @endforeach
    <tr>
    <td colspan="2" >&nbsp;</td>
    <td><b>Total Otros Pagos</b> </td>
    <td>${{$total_o_p->total}}</td>
    </tr>
    <tr>
    <th class="bord">SUBSIDIO AL EMPLEO</th>
    </tr>
    <tr>
    <th  style="background-color: #ddd; font-weight: normal;">SUBSIDIO CAUSADO</th>
    </tr>
    <tr>
    <td>0.00</td>
    </tr>
</table>
<div style="page-break-inside: avoid; width: 100%; bottom: 0px;">
<table>
  <tr>
  <p style="border-bottom: 2px solid #C0C0C0; font-family: Arial, sans-serif; font-size: 11px; text-align:center;">
  <b>TOTALES NOMINA</b></p>
  </tr>
  </table>
  <table style="font-family: Arial, sans-serif; font-size: 8px; width: 100%; text-align:center;">

    <tr>
      <th class="bord">TOTAL PERCEPCIONES</th>
      <th class="bord">TOTAL OTROS PAGOS</th>
    <th class="bord">TOTAL DEDUCIONES</th>
    </tr>
    <tr>
      <td>${{$TotalSueldos}}</td>
      <td>${{$total_o_p->total}}</td>
    <td>${{$TotalOtrasDeducciones}}</td>
    </tr>
</table>
<p style="border-bottom: 2px solid #C0C0C0; font-family: Arial, sans-serif; font-size: 11px; text-align:center;"></p>


  <table style="font-family: Arial, sans-serif; text-align:justify; font-size: 8px; width: 100%;">
          <tr>
          <th>TOTAL EN LETRA</th>
          <th>{{$cambio}}</th>
          <th>IMPORTE NETO:</th>
          <th>${{$general->total}}</th>
          </tr>
  </table>


  <table style="width: 100%;">
              <tr>
                <th width="25%"></th>
                <th width="75%"></th>
              </tr>
    <td>
    <table  width="100%">

      <img src="img/id.png" width="160" height="160">

      </table>
    </td>
    <td>



      </td>
      <td>
        <table width="100%">div</table>
      </td>
</table>

      <br>


  </div>
  </main>

</body>

</html>
