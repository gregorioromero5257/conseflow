<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PTI-01_F-02 Vale de resguardo de equipo de TI</title>

  <head>
    <style>
      /** Define the margins of your page **/
      @page {
        margin-top: 3cm;
        margin-left: 2cm;
        margin-right: 2cm;
        margin-bottom: 2cm;
      }

      header {
        position: fixed;
        top: -70px;
        left: 0px;
        right: 0px;
        height: 20px;

        /** Extra personal styles **/
        /* background-color: #03a9f4;
                color: white;
                text-align: center;
                line-height: 35px; */
      }

      footer {
        position: fixed;
        bottom: -20px;
        left: 0px;
        right: 0px;
        height: 20px;

        /** Extra personal styles **/
        color: #4472C4;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;

      }

      .letter {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 16px;
      }
    </style>
  </head>

<body>
  <!-- Define header and footer blocks before your content -->
  <header>
    <table width="100%" style="border-collapse: collapse; border: 1px solid; font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            text-align: center;">
      <tr>
        <th colspan="4" style="border: 1px solid; ">
          <div style="color: #4472C4;"> CONSERFLOW S.A. DE C.V.</div>
        </th>
      </tr>
      <tr>
        <td rowspan="3" width="20%"><img src="img/conserflow.png" width="120"></td>
        <td rowspan="3" style="border: 1px solid; text-align: center;" width="50%"><b>VALE DE RESGUARDO DE EQUIPO DE TI</b> </td>
        <td style="border: 1px solid; text-align: center;" width="15%">CÓDIGO</td>
        <td style="border: 1px solid; text-align: center;" width="15%">PTI-01/F-02</td>
      </tr>
      <tr>
        <td style="border: 1px solid; text-align: center;">REVISIÓN</td>
        <td style="border: 1px solid; text-align: center;">00</td>
      </tr>
      <tr>
        <td style="border: 1px solid; text-align: center;">EMISIÓN</td>
        <td style="border: 1px solid; text-align: center;">01.ABR.20</td>
      </tr>
    </table>

  </header>

  <footer>


    <table width="100%" style="b font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;">
      <tr>
        <td width="30%"> CONSERFLOW S.A. DE C.V. </td>
        <td width="40%">&nbsp;</td>
        <td width="30%">Página <b>1</b> de <b>1</b></td>
      </tr>
    </table>
  </footer>

  <!-- Wrap the content of your PDF inside a main tag -->
  <main>
    <div style="">
      <div style="height: 60px;">&nbsp;</div>
      <table width="100%" style="border-collapse: collapse; border: 1px solid; font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;">
        <tr>
          <td style="border: 1px solid; background-color:#BFBFBF; padding-left:10px;" width="20%" height="20"><b>Fecha</b> </td>
          <td style="border: 1px solid; " width="75%">&nbsp;{{$fecha_fin}}</td>
        </tr>
        <tr>
          <td style="border: 1px solid; background-color:#BFBFBF; padding-left:10px;" height="20"><b>Tipo</b> </td>
          <td style="border: 1px solid; ">&nbsp;
            @if($data->tipo == 1)
            COMPUTO
            @elseif($data->tipo == 2)
            ACECESORIOS
            @elseif($data->tipo == 3)
            IMPRESIÓN
            @elseif($data->tipo == 4)
            VIDEO
            @endif
          </td>
        </tr>
        <tr>
          <td style="border: 1px solid; background-color:#BFBFBF; padding-left:10px;" height="20"><b>Marca</b> </td>
          <td style="border: 1px solid; ">&nbsp;
            @if($data->tipo == 1)
            {{$cdata->marca_modelo}}
            @elseif($data->tipo == 2)
            {{$cdata->marca}}
            @elseif($data->tipo == 3)
            {{$cdata->marca}}
            @elseif($data->tipo == 4)
            {{$cdata->marca}}
            @endif
          </td>
        </tr>
        <tr>
          <td style="border: 1px solid; background-color:#BFBFBF; padding-left:10px;" height="20"><b>Modelo</b> </td>
          <td style="border: 1px solid; ">&nbsp;
            @if($data->tipo == 1)
            {{$cdata->marca_modelo}}
            @elseif($data->tipo == 2)
            {{$cdata->modelo}}
            @elseif($data->tipo == 3)
            {{$cdata->modelo}}
            @elseif($data->tipo == 4)
            {{$cdata->modelo}}
            @endif
          </td>
        </tr>
        <tr>
          <td style="border: 1px solid; background-color:#BFBFBF; padding-left:10px;" height="20"><b>Número de serie</b> </td>
          <td style="border: 1px solid; ">&nbsp;{{$cdata->no_serie}}</td>
        </tr>
        <tr>
          <td style="border: 1px solid; background-color:#BFBFBF; padding-left:10px;" height="20"><b>Características</b> </td>
          <td style="border: 1px solid; ">&nbsp;{{$cdata->caracteristicas}}</td>
        </tr>
        <tr>
          <td style="border: 1px solid; background-color:#BFBFBF; padding-left:10px;" height="20"><b>Enlistar accesorios adicionales</b> </td>
          <td style="border: 1px solid; ">
            @if($data->accesorios_listado == '' || $data->accesorios_listado == null)
            &nbsp;{{$data->acesorios}}
            @else
              @foreach ($accesorios as $key_a => $value_a)
              {{$value_a->descripcion.' Cantidad : '.$value_a->cantidad}}<br>
              @endforeach
            @endif
          </td>
        </tr>
        <tr>
          <td style="border: 1px solid; background-color:#BFBFBF; padding-left:10px;" height="20"><b> Observaciones adicionales a la recepción</b></td>
          <td style="border: 1px solid; ">&nbsp;{{$data->observacion_uno}}</td>
        </tr>
        <tr>
          <td style="border: 1px solid; background-color:#BFBFBF; padding-left:10px;" height="20"><b>Observaciones adicionales a la entrega</b> </td>
          <td style="border: 1px solid; ">&nbsp;{{$data->observacion_dos}}</td>
        </tr>
      </table>

      <div style="height: 40px;">&nbsp;</div>
      <p style="font-family: Arial, Helvetica, sans-serif;
            font-size: 12px; text-align: justify;">
        El equipo descrito anteriormente, me fue asignado como herramienta de trabajo para el desempeño de mis funciones. Me comprometo a respetar, aplicar y cumplir los lineamientos de uso, manejo de información y recuperación de equipo, así como todas aquellas que CONSERFLOW establezca.
        <br><br><br>
        Al no requerir el equipo por razón de terminación de mi relación laboral, cambio de puesto o responsabilidad u otra similar soy responsable de regresarlos al responsable asignado de tecnologías de la información para la cancelación de este documento. En caso de no devolverlo total, o parcialmente, se me será descontado de mi sueldo al costo de reposición vigente del mercado. En caso de robo, es mi responsabilidad dar aviso inmediato al responsable de recursos humanos y participar en las investigaciones pertinentes.
      </p>
      <div style="height: 40px;">&nbsp;</div>
      <table width="95%" style="padding-left: 50px; font-family: Arial, Helvetica, sans-serif;
            font-size: 12px; text-align: center;">
        <tr>
          <td style="border-bottom: 1px solid;" width="30%">&nbsp;{{$data->empleado_e}}</td>
          <td width="35%">&nbsp;</td>
          <td style="border-bottom: 1px solid;" width="30%">&nbsp;{{$data->empleado_r}}</td>
        </tr>
        <tr>
          <td>Entrega
            <br>Tecnologá de la información
          </td>
          <td>&nbsp;</td>
          <td>Recibe
            <br>Usuario Asignado
          </td>
        </tr>
      </table>
    </div>
  </main>
</body>

</html>
