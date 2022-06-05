<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PSE-01_F-02 BITÁCORA DE ENTRADAS Y SALIDAS DE RESIDUOS DE MANEJO ESPECIAL Y RESIDUOS PELIGROSOS</title>
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
          .letter{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
          }
        </style>
        </head>
        <body>
        <!-- Define header and footer blocks before your content -->
        <header>
        <table width="100%" style="border-collapse: collapse; border: 1px solid; font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            text-align: center;">
        <tr>
        <th colspan="4" style="border: 1px solid; "><div style="color: #4472C4;"> CONSERFLOW S.A. DE C.V.</div></th>
        </tr>
        <tr>
        <td rowspan="3" width="20%"><img src="img/conserflow.png" width="120"></td>
        <td rowspan="3" style="border: 1px solid; text-align: center;" width="55%"><b>BITÁCORA DE ENTRADAS Y SALIDAS DE RESIDUOS DE MANEJO ESPECIAL Y RESIDUOS PELIGROSOS</b> </td>
        <td style="border: 1px solid; text-align: center;"  width="10%">CÓDIGO</td>
        <td style="border: 1px solid; text-align: center;"  width="15%">PSE-02/F-03</td>
        </tr>
        <tr>
        <td style="border: 1px solid; text-align: center;" >REVISIÓN</td>
        <td style="border: 1px solid; text-align: center;" >00</td>
        </tr>
        <tr>
        <td style="border: 1px solid; text-align: center;" >EMISIÓN</td>
        <td style="border: 1px solid; text-align: center;" >01.ABR.20</td>
        </tr>
        </table>

        </header>

        <footer>

            <table width="100%" style="b font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;">
        <tr>
        <td width="30%"> CONSERFLOW S.A. DE C.V. </td>
        <td  width="40%">&nbsp;</td>
        <td  width="30%">Página <b>1</b>  de <b>1</b></td>
        </tr>
        </table>
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            <div style="height: 60px;">&nbsp;</div>

            <table width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;">
            <tr>
            <td colspan="14">&nbsp;</td>
            </tr>
            <tr>
            <td colspan="5">&nbsp;</td>
            <td colspan="9" style="border: 1px solid; text-align: center; background-color: #0070C0;">CLAVE CRETIB</td>
            </tr>
            <tr>
            <td colspan="5">&nbsp;</td>
            <td style="border: 1px solid; text-align: center; background-color: #BFBFBF;">C</td>
            <td style="border: 1px solid; text-align: center; background-color: #BFBFBF;">R</td>
            <td style="border: 1px solid; text-align: center; background-color: #BFBFBF;">E</td>
            <td style="border: 1px solid; text-align: center; background-color: #BFBFBF;">T</td>
            <td style="border: 1px solid; text-align: center; background-color: #BFBFBF;">Te</td>
            <td style="border: 1px solid; text-align: center; background-color: #BFBFBF;">Th</td>
            <td style="border: 1px solid; text-align: center; background-color: #BFBFBF;">Tt</td>
            <td style="border: 1px solid; text-align: center; background-color: #BFBFBF;">I</td>
            <td style="border: 1px solid; text-align: center; background-color: #BFBFBF;">B</td>
            </tr>
            <tr>
            <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;">Localidad</td>
            <td colspan="3" style="border: 1px solid; text-align: center;">&nbsp;{{$gral->localidad}}</td>
            <td>&nbsp;</td>
            <td style="border: 1px solid; text-align: center;">Corrosivo</td>
            <td style="border: 1px solid; text-align: center;">Reactivo</td>
            <td style="border: 1px solid; text-align: center;">Explosivo</td>
            <td style="border: 1px solid; text-align: center;">Tóxico</td>
            <td style="border: 1px solid; text-align: center;">Tóxico Ambiental</td>
            <td style="border: 1px solid; text-align: center;">Tóxico Agudo</td>
            <td style="border: 1px solid; text-align: center;">Tóxico Crónico</td>
            <td style="border: 1px solid; text-align: center;">Inflamable</td>
            <td style="border: 1px solid; text-align: center;">Biologicamente Infeccioso</td>
            </tr>
            <tr>
            <td colspan="14">&nbsp;</td>
            </tr>
            <tr>
            <td rowspan="2" style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white; "><b> Fecha de ingreso al (AT)</b></div> </td>
            <td rowspan="2" style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;"><b> Nombre del Residuo</b></div></td>
            <td rowspan="2" style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;"><b> Tipo de Residuos   (RME/ RP)</b></div></td>
            <td rowspan="2" style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;"><b>Cantidad </b></div></td>
            <td rowspan="2" style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;"><b> Unidad de medida</b></div></td>
            <td rowspan="2" style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;"><b> Área o Proceso de generación</b></div></td>
            <td rowspan="2" style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;"><b> Fecha de salida RME</b></div></td>
            <td colspan="7" style="border: 1px solid; background-color: #FFC000; text-align:center;"><b> SOLO RESIDUOS PELIGROSO</b></td>
            </tr>
            <tr>
            <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><div style=""><b> CRETIB</b></div></td>
            <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><div style=""><b> Fecha Salida</b></div></td>
            <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><div style=""><b> Proveedor</b></div></td>
            <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><div style=""><b> No. de Autorización</b></div></td>
            <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><div style=""><b> Clave CRETIB</b></div></td>
            <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><div style=""><b> Clave Generica</b></div></td>
            <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;"><div style=""><b> Núm. De Manifiesto</b></div></td>
            </tr>
            @foreach ($bre as $key => $value)
            <tr>
            <td style="border: 1px solid; text-align: center;">&nbsp;{{$value->fecha}}</td>
            <td style="border: 1px solid; text-align: center;">&nbsp;
              {{$value->nr}}
            </td>
            <td style="border: 1px solid; text-align: center;">&nbsp;
              @if($value->tipo == 1)
              RME
              @elseif($value->tipo == 2)
              RP
              @endif
            </td>
            <td style="border: 1px solid; text-align: center;">&nbsp;
              {{$value->cantidad}}
            </td>
            <td style="border: 1px solid; text-align: center;">&nbsp;
              {{$value->unidad}}
            </td>
            <td style="border: 1px solid; text-align: center;">&nbsp;
              {{$value->area_proceso}}
            </td>
            <td style="border: 1px solid; text-align: center;">&nbsp;
              {{$value->fecha_salida_rme}}
            </td>
            <td style="border: 1px solid; text-align: center;">&nbsp;
              @if($value->crebit == 1)
              Corrosivo
              @elseif($value->crebit == 2)
              Reactivo
              @elseif($value->crebit == 3)
              Explosivo
              @elseif($value->crebit == 4)
              Tóxico
              @elseif($value->crebit == 5)
              Tóxico Ambiental
              @elseif($value->crebit == 6)
              Tóxico Agudo
              @elseif($value->crebit == 7)
              Tóxico Crónico
              @elseif($value->crebit == 8)
              Inflamable
              @elseif($value->crebit == 9)
              Biologicamente Infeccioso
              @endif
            </td>
            <td style="border: 1px solid; text-align: center;">&nbsp;
              {{$value->fecha_dos}}
            </td>
            <td style="border: 1px solid; text-align: center;">&nbsp;
              {{$value->proveedor}}
            </td>
            <td style="border: 1px solid; text-align: center;">&nbsp;
              {{$value->no_autorizacion}}
            </td>
            <td style="border: 1px solid; text-align: center;">&nbsp;
              @if($value->crebit == 1)
              C
              @elseif($value->crebit == 2)
              R
              @elseif($value->crebit == 3)
              E
              @elseif($value->crebit == 4)
              T
              @elseif($value->crebit == 5)
              Te
              @elseif($value->crebit == 6)
              Th
              @elseif($value->crebit == 7)
              Tt
              @elseif($value->crebit == 8)
              I
              @elseif($value->crebit == 9)
              B
              @endif
            </td>
            <td style="border: 1px solid; text-align: center;">&nbsp;
              @if($value->clave == 1)
                SO1
              @elseif($value->clave == 2)
                O1
              @elseif($value->clave == 3)
                SO5
              @elseif($value->clave == 4)
                L5
              @elseif($value->clave == 5)
                1
              @endif
            </td>
            <td style="border: 1px solid; text-align: center;">&nbsp;
              {{$value->num_manifiesto}}
            </td>
            </tr>
           @endforeach

            <tr>
            <td colspan="12" style="text-align: right">&nbsp;{{$gral->responsable_nombre}}</td>
            <td></td>
            <td></td>
            </tr>
            <tr>
            <td colspan="10">&nbsp;</td>
            <td colspan="3" style="border-top: 1px solid; text-align: center;">Nombre y Firma del Responsable</td>
            <td>&nbsp;</td>
            </tr>
            </table>





        </main>
    </body>
</html>
