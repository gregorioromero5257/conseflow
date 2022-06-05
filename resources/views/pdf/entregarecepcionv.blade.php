<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PMN-02_F04 ENTREGA Y RECEPCIÓN DE UNIDAD VEHICULAR </title>
  <head>
  <style>
            /** Define the margins of your page **/
            @page {
                margin-top: 3cm;
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
                bottom: -50px;
                left: 0px;
                right: 0px;
                height: 20px;
                color: #4472C4;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
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
            .letter{
              font-family: Arial, Helvetica, sans-serif;
              font-size: 16px;
            }
            .vert{
              font-size: 10px;
              white-space:pre-line;
              g-origin:40% 40%;
              -webkit-transform: rotate(270deg);
              -moz-transform: rotate(270deg);
              -ms-transform: rotate(270deg);
              -o-transform: rotate(270deg);
              transform: rotate(270deg);
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
          <td rowspan="3" style="border: 1px solid; text-align: center;" width="55%"><b>ENTREGA Y RECEPCIÓN DE UNIDAD VEHICULAR </b> </td>
          <td style="border: 1px solid; text-align: center;"  width="10%">CÓDIGO</td>
          <td style="border: 1px solid; text-align: center;"  width="15%">PMN-02/F04</td>
          </tr>
          <tr>
          <td style="border: 1px solid; text-align: center;" >REVISIÓN</td>
          <td style="border: 1px solid; text-align: center;" >00</td>
          </tr>
          <tr>
          <td style="border: 1px solid; text-align: center;" >EMISIÓN</td>
          <td style="border: 1px solid; text-align: center;" >29.JUN.20</td>
          </tr>
        </table>

        </header>

        <footer>

        <table width="100%" style="b font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;">
            <tr>
            <td width="30%"> CONSERFLOW S.A. DE C.V. </td>
            <td  width="40%">&nbsp;</td>
            <td  width="30%" class="custom-footer-page-number">
                    PÁGINA:
                </td>
            </tr>
        </table>
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
    <main >

    <table  width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif; font-size: 8px;">
        <tr>
          <td colspan="10" width="100%" style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;">DATOS DE LA UNIDAD</div> </td>
        </tr>
        <tr>
          <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="5%">UNIDAD</td>
          <td width="10%" style="border: 1px solid;">&nbsp;{{$arreglo['unidad']}}</td>
          <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="10%">MODELO</td>
          <td width="10%" style="border: 1px solid;">&nbsp;{{$arreglo['modelo']}}</td>
          <td width="10%" style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >NO. PERMISO DE CARGA</td>
          <td width="10%" colspan="" style="border: 1px solid;">&nbsp;</td>
          <td width="10%" style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >TARJETA DE CIRCULACIÓN</td>
          <td width="10%" colspan="" style="border: 1px solid;">&nbsp;{{$arreglo['numero_tarjeta_circulacion']}}</td>
          <td width="10%" style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >NUM. SERIE</td>
          <td width="15%" style="border: 1px solid;">&nbsp;{{$arreglo['numero_serie']}}</td>
        </tr>
        <tr>
          <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" width="">PLACAS</td>
          <td width="" style="border: 1px solid;">&nbsp;{{$arreglo['placas']}}</td>
          <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >NO. POLIZA DE SEGUROS </td>
          <td style="border: 1px solid;">&nbsp;{{$arreglo['numero_poliza_seguro']}}</td>
          <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >FECHA DE ENTREGA</td>
          <td  colspan="" style="border: 1px solid;">&nbsp;{{$arreglo['fecha_entrega']}}</td>
          <td  style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >FECHA DE RECEPCIÓN</td>
          <td colspan="" style="border: 1px solid;">&nbsp;{{$arreglo['fecha_recepcion']}}</td>
          <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;">NUM. MOTOR</td>
          <td style="border: 1px solid;">&nbsp;</td>
        </tr>
        <tr>
          <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >VIGENCIA DE POLIZA</td>
          <td style="border: 1px solid;">&nbsp;{{$arreglo['vigencia_poliza']}}</td>
          <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >VERIFICACION</td>
          <td style="border: 1px solid;">&nbsp;</td>
          <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >KILOMETRAJE DE ENTREGA</td>
          <td style="border: 1px solid;">&nbsp;{{$arreglo['kilometraje_entrega']}}</td>
          <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >KILOMETRAJE DE RECEPCIÓN</td>
          <td colspan="3" style="border: 1px solid;">&nbsp;{{$arreglo['kilometraje_recepcion']}}</td>
        </tr>
        <tr>
          <td colspan="2" style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >LUGAR DE ENTREGA</td>
          <td colspan="4" style="border: 1px solid;">&nbsp;{{$arreglo['lugar_entrega']}}</td>
          <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >LUGAR DE RECEPCIÓN</td>
          <td colspan="3" style="border: 1px solid;">&nbsp;{{$arreglo['lugar_recepcion']}}</td>
        </tr>
        <tr>
          <td colspan="2" style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >PROYECTO Y DESTINATARIO</td>
          <td colspan="8" style="border: 1px solid;">&nbsp;{{$arreglo['proyecto']. ' '.$arreglo['destinatario']}}</td>
       </tr>
       <tr>
          <td colspan="10">&nbsp;</td>
       </tr>
        <tr>
          <td colspan="10" style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;"><div style="color:white;">DATOS DEL USUARIO</div> </td>
        </tr>
        <tr>
          <td colspan="3" style="border: 1px solid; background-color: #BFBFBF; text-align: center; font-size: 8px;">NOMBRE COMPLETO</td>
          <td colspan="7" style="border: 1px solid;">&nbsp;{{$conductor == null ? '' : $conductor->nombre_empleado}}</td>
        </tr>
        <tr>
          <td colspan="3" style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >NO. LICENCIA DE CONDUCIR</td>
          <td colspan="2" style="border: 1px solid;">&nbsp;{{$conductor == null ? '' : $conductor->licencia}}</td>
          <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >TIPO</td>
          <td colspan="2" style="border: 1px solid;">&nbsp;{{$conductor == null ? '' : $conductor->tipo}}</td>
          <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >VIGENCIA</td>
          <td style="border: 1px solid;">&nbsp;{{$conductor == null ? '' : $conductor->vigencia}}</td>
        </tr>

    </table>

    <table  width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif; font-size: 8px;">

        <tr>
          <td colspan="13" style="border: 1px solid; background-color: #0070C0; text-align: center;"><div style="color:white;">CHECK LIST</div> </td>
        </tr>

        <tr>
        <td colspan="6" style="border: 1px solid; background-color: #BFBFBF; text-align: center;" ><b>ACCESORIOS EXTERNOS</b> </td>
        <td  width="1px">&nbsp;</td>
        <td colspan="6" style="border: 1px solid; background-color: #BFBFBF; text-align: center;" ><b>ACCESORIOS INTERNOS</b> </td>
        </tr>
        <tr>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> DESCRIPCIÓN</div> </td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> ENT</div></td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> REC</div></td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> DESCRIPCIÓN</div> </td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> ENT</div></td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> REC</div></td>
        <td>&nbsp;</td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> DESCRIPCIÓN</div> </td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> ENT</div></td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> REC</div></td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> DESCRIPCIÓN</div> </td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> ENT</div></td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> REC</div></td>
        </tr>

        <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >ESPEJO LATERAL DERECHO </td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e->uno}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e_r == null ? '' : $accesorios_e_r->uno}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >ESPEJO LATERAL IZQUIERDO</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e->dos}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e_r == null ? '' : $accesorios_e_r->dos}}</td>
        <td>&nbsp;</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >ESPEJO RETROVISOR </td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_i->uno}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_i_r == null ? '' : $accesorios_i_r->uno}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >TAPETES</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_i->dos}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_i_r == null ? '' : $accesorios_i_r->dos}}</td>
        </tr>
        <tr>

        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >PARABRISAS </td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e->tres}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e_r == null ? '' : $accesorios_e_r->tres}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >MEDALLÓN TRASERO  </td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e->cuatro}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e_r == null ? '' : $accesorios_e_r->cuatro}}</td>
        <td>&nbsp;</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >ENCENDEDOR	</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_i->tres}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_i_r == null ? '' : $accesorios_i_r->tres}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >CLAXON</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_i->cuatro}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_i_r == null ? '' : $accesorios_i_r->cuatro}}</td>
        </tr>
        <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >CRISTALES DE PUERTAS (LATERALES)  </td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e->cinco}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e_r == null ? '' : $accesorios_e_r->cinco}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >TAPON DE GASOLINA</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e->seis}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e_r == null ? '' : $accesorios_e_r->seis}}</td>
        <td>&nbsp;</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >	VISERAS</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_i->cinco}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_i_r == null ? '' : $accesorios_i_r->cinco}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >CINTURONES DE SEGURIDAD</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_i->seis}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_i_r == null ? '' : $accesorios_i_r->seis}}</td>
        </tr>
        <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >LIMPIADORES  </td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e->siete}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e_r == null ? '' : $accesorios_e_r->siete}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >FAROS Y LUCES</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e->ocho}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e_r == null ? '' : $accesorios_e_r->ocho}}</td>
        <td>&nbsp;</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >MANIJAS</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_i->siete}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_i_r == null ? '' : $accesorios_i_r->siete}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >RADIO</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_i->ocho}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_i_r == null ? '' : $accesorios_i_r->ocho}}</td>
        </tr>
        <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >BIRLOS DE LLANTAS </td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e->nueve}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e_r == null ? '' : $accesorios_e_r->nueve}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >MOLDURAS</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e->diez}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e_r == null ? '' : $accesorios_e_r->diez}}</td>
        <td>&nbsp;</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >RADIO / CD </td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_i->nueve}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_i_r == null ? '' : $accesorios_i_r->nueve}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >CLIMA</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_i->diez}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_i_r == null ? '' : $accesorios_i_r->diez}}</td>
        </tr>
        <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >CALAVERAS </td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e->once}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e_r == null ? '' : $accesorios_e_r->once}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >MATA CHISPAS</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e->doce}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e_r == null ? '' : $accesorios_e_r->doce}}</td>
        <td colspan="7">&nbsp;</td>
        </tr>
        <tr>

        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >ANTENA </td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e->trece}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e_r == null ? '' : $accesorios_e_r->trece}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >DEFENSAS DELANTERAS</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e->catorce}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e_r == null ? '' : $accesorios_e_r->catorce}}</td>
        <td width="">&nbsp;</td>
        <td colspan="6" style="border: 1px solid; background-color: #BFBFBF; text-align: center;" ><b>NIVELES</b> </td>
        </tr>
        <tr>

        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >PARILLA </td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e->quince}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e_r == null ? '' : $accesorios_e_r->quince}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >PORTA LLANTAS</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e->dieciseis}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e_r == null ? '' : $accesorios_e_r->dieciseis}}</td>
        <td>&nbsp;</td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> DESCRIPCIÓN</div> </td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> ENT</div></td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> REC</div></td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> DESCRIPCIÓN</div> </td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> ENT</div></td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> REC</div></td>
        </tr>
        <tr>

        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >PLACA DELANTERA / TRASERA </td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e->diecisiete}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e_r == null ? '' : $accesorios_e_r->diecisiete}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >DEFENSAS TRASERAS </td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e->dieciocho}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e_r == null ? '' : $accesorios_e_r->dieciocho}}</td>
        <td>&nbsp;</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >GASOLINA</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$niveles->uno}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$niveles_r == null ? '' : $niveles_r->uno}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >LIQUIDO DE  FRENOS</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$niveles->dos}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$niveles_r == null ? '' : $niveles_r->dos}}</td>
        </tr>
        <tr>

        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >TAPONES DE RUEDAS</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e->diecinueve}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e_r == null ? '' : $accesorios_e_r->diecinueve}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >LLANTA DE REFACCION</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e->veinte}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e_r == null ? '' : $accesorios_e_r->veinte}}</td>
        <td>&nbsp;</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >ACEITE DE MOTOR</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$niveles->tres}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$niveles_r == null ? '' : $niveles_r->tres}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >ANTICONGELANTE</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$niveles->cuatro}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$niveles_r == null ? '' : $niveles_r->cuatro}}</td>
        </tr>
        <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >PLUMAS</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e->ventiuno}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e_r == null ? '' : $accesorios_e_r->ventiuno}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >ROLLE CAGE</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e->ventidos}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$accesorios_e_r == null ? '' : $accesorios_e_r->ventidos}}</td>
        <td colspan="">&nbsp;</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >DIRECCION HIDRAULICA</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$niveles->cinco}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$niveles_r == null ? '' : $niveles_r->cinco}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >LIQUIDO LIMPIA PARABRISAS</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$niveles->seis}}</td>
        <td style="border: 1px solid; text-align:center;  text-align:center; ">{{$niveles_r == null ? '' : $niveles_r->seis}}</td>
        </tr>
        <tr>
        <td colspan="13" height="5px;">&nbsp;</td>
        </tr>

        <tr>
        <td colspan="6" style="border: 1px solid; background-color: #BFBFBF; text-align: center;" ><b>CARROCERÍA E INTERIORES</b> </td>
        <td>&nbsp;</td>
        <td colspan="6" style="border: 1px solid; background-color: #BFBFBF; text-align: center;" ><b>DETALLES DE FUNCIONAMIENTO</b> </td>
        </tr>
        <tr>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> DESCRIPCIÓN</div> </td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> ENT</div></td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> REC</div></td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> DESCRIPCIÓN</div> </td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> ENT</div></td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> REC</div></td>
        <td>&nbsp;</td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> DESCRIPCIÓN</div> </td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> ENT</div></td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> REC</div></td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> DESCRIPCIÓN</div> </td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> ENT</div></td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> REC</div></td>
        </tr>
        <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >COSTADO DERECHO</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria->uno}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria_r == null ? '' : $carroceria_r->uno}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >CAJUELA</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria->dos}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria_r == null ? '' : $carroceria_r->dos}}</td>
        <td>&nbsp;</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >MOTOR</td>
        <td style="border: 1px solid;  text-align:center; ">{{$funcionamiento->uno}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$funcionamiento_r == null ? '' : $funcionamiento_r->uno}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >CLUTCH</td>
        <td style="border: 1px solid;  text-align:center; ">{{$funcionamiento->dos}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$funcionamiento_r == null ? '' : $funcionamiento_r->dos}}</td>
        </tr>
        <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >COSTADO IZQUIERDO</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria->tres}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria_r == null ? '' : $carroceria_r->tres}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >PINTURA</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria->cuatro}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria_r == null ? '' : $carroceria_r->cuatro}}</td>
        <td>&nbsp;</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >DIRECCION HIDRAULICA</td>
        <td style="border: 1px solid;  text-align:center; ">{{$funcionamiento->tres}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$funcionamiento_r == null ? '' : $funcionamiento_r->tres}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >LIMPIA PARA PARABRISAS</td>
        <td style="border: 1px solid;  text-align:center; ">{{$funcionamiento->cuatro}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$funcionamiento_r == null ? '' : $funcionamiento_r->cuatro}}</td>
        </tr>
        <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >COFRE</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria->cinco}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria_r == null ? '' : $carroceria_r->cinco}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >SISTEMA DE ALARMA</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria->seis}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria_r == null ? '' : $carroceria_r->seis}}</td>
        <td>&nbsp;</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >CAJA DE VELOCIDADES</td>
        <td style="border: 1px solid;  text-align:center; ">{{$funcionamiento->cinco}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$funcionamiento_r == null ? '' : $funcionamiento_r->cinco}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >FRENOS </td>
        <td style="border: 1px solid;  text-align:center; ">{{$funcionamiento->seis}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$funcionamiento_r == null ? '' : $funcionamiento_r->seis}}</td>
        </tr>
        <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >TOLDO</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria->siete}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria_r == null ? '' : $carroceria_r->siete}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >VESTIDURAS</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria->ocho}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria_r == null ? '' : $carroceria_r->ocho}}</td>
        <td colspan="">&nbsp;</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >GPS</td>
        <td style="border: 1px solid;  text-align:center; ">{{$funcionamiento->siete}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$funcionamiento_r == null ? '' : $funcionamiento_r->siete}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >CAMARA REVERSA</td>
        <td style="border: 1px solid;  text-align:center; ">{{$funcionamiento->ocho}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$funcionamiento_r == null ? '' : $funcionamiento_r->ocho}}</td>
        </tr>
        <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >ASIENTOS ( )</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria->nueve}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria_r == null ? '' : $carroceria_r->nueve}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >CARROCERIA</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria->diez}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria_r == null ? '' : $carroceria_r->diez}}</td>
        <td colspan="">&nbsp;</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >REVERSERO</td>
        <td style="border: 1px solid;  text-align:center; ">{{$funcionamiento->nueve}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$funcionamiento_r == null ? '' : $funcionamiento_r->nueve}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >OTRO</td>
        <td style="border: 1px solid;  text-align:center; ">{{$funcionamiento->diez}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$funcionamiento_r == null ? '' : $funcionamiento_r->diez}}</td>
        </tr>
        <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >VENTANAS</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria->once}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria_r == null ? '' : $carroceria_r->once}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >CHASIS</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria->doce}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria_r == null ? '' : $carroceria_r->doce}}</td>
        <td colspan="7">&nbsp;</td>
        </tr>
        <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >EXTERIOR </td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria->trece}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria_r == null ? '' : $carroceria_r->trece}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >INTERIOR </td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria->catorce}}</td>
        <td style="border: 1px solid;  text-align:center; ">{{$carroceria_r == null ? '' : $carroceria_r->catorce}}</td>
        <td colspan="7">&nbsp;</td>
        </tr>
        <tr>
        <td colspan="13" height="5px;">&nbsp;</td>
        </tr>
        <tr>
        <td colspan="6" style="border: 1px solid; background-color: #BFBFBF; text-align: center;" ><b>ACCESORIOS Y HERRAMIENTAS AUXILIARES</b> </td>
        <td >&nbsp;</td>
        <td colspan="6" style="border: 1px solid; background-color: #BFBFBF; text-align: center;" ><b>COFRE</b> </td>
        </tr>
        <tr>

        <td  style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> DESCRIPCIÓN</div> </td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> ENT</div></td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> REC</div></td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> DESCRIPCIÓN</div> </td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> ENT</div></td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> REC</div></td>
        <td>&nbsp;</td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> DESCRIPCIÓN</div> </td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> ENT</div></td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> REC</div></td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> DESCRIPCIÓN</div> </td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> ENT</div></td>
        <td  style="border: 1px solid; background-color: #0070C0; text-align: center; font-size: 8px;" ><div style="color:white;"> REC</div></td>
        </tr>
        <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >EXTINTOR</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$accesorios->uno}}</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$accesorios_r == null ? '' : $accesorios_r->uno}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >BOTIQUIN DE PRIMEROS AUXILIOS</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$accesorios->dos}}</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$accesorios_r == null ? '' : $accesorios_r->dos}}</td>
        <td>&nbsp;</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >TAPON DE RADIADOR</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$cofre->uno}}</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$cofre_r == null ? '' : $cofre_r->uno}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >TAPON DE ACEITE</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$cofre->dos}}</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$cofre_r == null ? '' : $cofre_r->dos}}</td>
        </tr>
        <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >GATO HIDRAULICO</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$accesorios->tres}}</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$accesorios_r == null ? '' : $accesorios_r->tres}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >FANTASMAS O SEÑALAMIENTOS</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$accesorios->cuatro}}</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$accesorios_r == null ? '' : $accesorios_r->cuatro}}</td>
        <td>&nbsp;</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >BAYONETA ACEITE</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$cofre->tres}}</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$cofre_r == null ? '' : $cofre_r->tres}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >TAPON DE DIRECCION</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$cofre->cuatro}}</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$cofre_r == null ? '' : $cofre_r->cuatro}}</td>
        </tr>
        <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >LLAVE DE CRUZ O ELE</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$accesorios->cinco}}</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$accesorios_r == null ? '' : $accesorios_r->cinco}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >LLAVES, DESAMADORES, PINZAS</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$accesorios->seis }}</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$accesorios_r == null ? '' : $accesorios_r->seis}}</td>
        <td>&nbsp;</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >SUJETADOR DE BATERIA</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$cofre->cinco}}</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$cofre_r == null ? '' : $cofre_r->cinco}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >LIQUIDO LIMPIA PARABRISAS</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$cofre->seis}}</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$cofre_r == null ? '' : $cofre_r->seis}}</td>
        </tr>
        <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >BIRLO DE SEGURIDAD</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$accesorios->siete}}</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$accesorios_r == null ? '' : $accesorios_r->siete}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >EXTENSIONES Y GANCHOS  (    )</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$accesorios->ocho}}</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$accesorios_r == null ? '' : $accesorios_r->ocho}}</td>
        <td colspan="7">&nbsp;</td>
        </tr>
        <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >CABLES PARA CORRIENTE</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$accesorios->nueve}}</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$accesorios_r == null ? '' : $accesorios_r->nueve}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >LAMPARA, IMPERMEABLE</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$accesorios->diez}}</td>
        <td style="border: 1px solid; text-align:center;   text-align:center; ">{{$accesorios_r == null ? '' : $accesorios_r->diez}}</td>
        <td colspan="7">&nbsp;</td>
        </tr>
        <tr>
        <td colspan="13" height="5px;">&nbsp;</td>
        </tr>
        <tr>
        <td colspan="6" style="border: 1px solid; background-color: #BFBFBF; text-align: center;" ><b>LLANTAS</b> </td>
        <td>&nbsp;</td>
        <td colspan="6" style="border: 1px solid; background-color: #BFBFBF; text-align: center;" ><b>LUCES</b> </td>
        </tr>
        <tr>
        <td style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> DESCRIPCIÓN</div> </td>
        <td style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> ENT</div></td>
        <td style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> REC</div></td>
        <td style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> DESCRIPCIÓN</div> </td>
        <td style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> ENT</div></td>
        <td style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> REC</div></td>
        <td>&nbsp;</td>
        <td style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> DESCRIPCIÓN</div> </td>
        <td style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> ENT</div></td>
        <td style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> REC</div></td>
        <td style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> DESCRIPCIÓN</div> </td>
        <td style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> ENT</div></td>
        <td style="border: 1px solid; background-color: #0070C0; text-align: center;" ><div style="color:white;"> REC</div></td>
        </tr>
        <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >DELANTERA DERECHA</td>
        <td style="border: 1px solid; text-align:center;">{{$llantas->uno}}</td>
        <td style="border: 1px solid; text-align:center;">{{$llantas_r == null ? '' : $llantas_r->uno}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >DELANTERA IZQUIERDA</td>
        <td style="border: 1px solid; text-align:center;">{{$llantas->dos}}</td>
        <td style="border: 1px solid; text-align:center;">{{$llantas_r == null ? '' : $llantas_r->dos}}</td>
        <td>&nbsp;</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >CARRETERA (LARGA)</td>
        <td style="border: 1px solid; text-align:center;">{{$luces->uno}}</td>
        <td style="border: 1px solid; text-align:center;">{{$luces_r == null ? '' : $luces_r->uno}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >CRUCE (CORTA)</td>
        <td style="border: 1px solid; text-align:center;">{{$luces->dos}}</td>
        <td style="border: 1px solid; text-align:center;">{{$luces_r == null ? '' : $luces_r->dos}}</td>

        </tr>

        <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >TRASERA DERECHA</td>
        <td style="border: 1px solid; text-align:center;">{{$llantas->tres}}</td>
        <td style="border: 1px solid; text-align:center;">{{$llantas_r == null ? '' : $llantas_r->tres}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >TRASERA IZQUIERDA</td>
        <td style="border: 1px solid; text-align:center;">{{$llantas->cuatro}}</td>
        <td style="border: 1px solid; text-align:center;">{{$llantas_r == null ? '' : $llantas_r->cuatro}}</td>
        <td>&nbsp;</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >DIRECCIONALES</td>
        <td style="border: 1px solid; text-align:center;">{{$luces->tres}}</td>
        <td style="border: 1px solid; text-align:center;">{{$luces_r == null ? '' : $llantas_r->tres}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >STOP</td>
        <td style="border: 1px solid; text-align:center;">{{$luces->cuatro}}</td>
        <td style="border: 1px solid; text-align:center;">{{$luces_r == null ? '' : $luces_r->cuatro}}</td>
        </tr>

        <tr>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >REFACCIÓN</td>
        <td style="border: 1px solid; text-align:center;">{{$llantas->cinco}}</td>
        <td style="border: 1px solid; text-align:center;">{{$llantas_r == null ? '' : $llantas_r->cinco}}</td>
        <td colspan="4">&nbsp;</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >REVERSA</td>
        <td style="border: 1px solid; text-align:center;">{{$luces->cinco}}</td>
        <td style="border: 1px solid; text-align:center;">{{$luces_r == null ? '' : $luces_r->cinco}}</td>
        <td style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >NIEBLA</td>
        <td style="border: 1px solid; text-align:center;">{{$luces->seis}}</td>
        <td style="border: 1px solid; text-align:center;">{{$luces_r == null ? '' : $luces_r->seis}}</td>
        </tr>


    </table>

    <table  width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif; font-size: 8px;">
        <tr>
        <td colspan="" style="border: 1px solid; background-color: #BFBFBF; text-align: center;" >ESTADO FISICO DEL VEHICULO</td>
        </tr>
        </table>
        <br>
        <div>

          @foreach ($arreglo_img as $key => $value)
          <img src="{{$value}}" alt="n" width="48%" height="200">
          @endforeach
          
          @if($arreglo_img_r != null)
          @foreach ($arreglo_img_r as $k => $v)
          <img src="{{$v}}" alt="n" width="48%" height="200">
          @endforeach
          @endif
        </div>

        <div style="page-break-inside: avoid; width: 100%; bottom: 0px;">
        <table  width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif; font-size: 8px;">
        <tr>
        <td  style="  text-align: center;" >ANOTAR LAS OBSERVACIONES DE LA UNIDAD</td>
        </tr>
        <tr>
        <td  style="border-bottom: 1px solid;  text-align: center;" >&nbsp;{{$data->observaciones}}</td>
        </tr>
        <tr>
        <td  style="border-bottom: 1px solid;  text-align: center;" >&nbsp;{{$data_r == null ? '--' : $data_r->observaciones}}</td>
        </tr>
        <tr>
        <td  style="border-bottom: 1px solid;  text-align: center;" >&nbsp;</td>
        </tr>
        <tr>
        <td  style="border-bottom: 1px solid;  text-align: center;" >&nbsp;</td>
        </tr>
        <tr>
        <td  style="border-bottom: 1px solid;  text-align: center;" >&nbsp;</td>
        </tr>
        </table>

        <table  width="100%" style="border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif; font-size: 8px;">
        <tr>
        <td  rowspan="3" style=" text-align: center; border: 1px solid;" >ENTREGA</td>
        <td  colspan="3" style="text-align: center; border: 1px solid; border-bottom: none;" height="80px">PERSONA QUE ENTREGA</td>
        <td  colspan="3" style="text-align: center; border: 1px solid; border-bottom: none;" >PERSONA QUE RECIBE</td>
        </tr>
        <tr>
        <td  colspan="3" style="text-align: center; border: 1px solid; border-top: none; border-bottom: none;" >&nbsp;{{$data->nombre_entrega}}</td>
        <td  colspan="3" style="text-align: center; border: 1px solid; border-top: none; border-bottom: none;" >&nbsp;{{$data->nombre_recibe}}</td>
        </tr>
        <tr>
        <td  style="text-align: center; border-bottom: 1px solid;">&nbsp;</td>
        <td  style="text-align: center; border-bottom: 1px solid; border-top: 1px solid;" >NOMBRE Y FIRMA</td>
        <td  style="text-align: center; border-bottom: 1px solid;">&nbsp;</td>
        <td  style="text-align: center; border-bottom: 1px solid; border-left: 1px solid;">&nbsp;</td>
        <td  style="text-align: center; border-bottom: 1px solid; border-top: 1px solid;" >NOMBRE Y FIRMA</td>
        <td  style="text-align: center; border-bottom: 1px solid; border-right: 1px solid;">&nbsp;</td>
        </tr>
        <tr>
        <td  rowspan="3" style=" text-align: center; border: 1px solid;" >RECP.</td>
        <td  colspan="3" style="text-align: center; border: 1px solid; border-bottom: none;" height="80px" >&nbsp; </td>
        <td  colspan="3" style="text-align: center; border: 1px solid; border-bottom: none;" >&nbsp;</td>
        </tr>
        <tr>
        <td  colspan="3" style="text-align: center; border: 1px solid; border-top: none; border-bottom: none;" >&nbsp;{{$data_r == null ? '-' : $data_r->nombre_entrega}}</td>
        <td  colspan="3" style="text-align: center; border: 1px solid; border-top: none; border-bottom: none;" >&nbsp;{{$data_r == null ? '-' : $data_r->nombre_recibe}}</td>
        </tr>
        <tr>
        <td  style="text-align: center; border-bottom: 1px solid;">&nbsp;</td>
        <td  style="text-align: center; border-bottom: 1px solid; border-top: 1px solid;" >NOMBRE Y FIRMA</td>
        <td  style="text-align: center; border-bottom: 1px solid;">&nbsp;</td>
        <td  style="text-align: center; border-bottom: 1px solid; border-left: 1px solid;">&nbsp;</td>
        <td  style="text-align: center; border-bottom: 1px solid; border-top: 1px solid;" >NOMBRE Y FIRMA</td>
        <td  style="text-align: center; border-bottom: 1px solid; border-right: 1px solid;">&nbsp;</td>
        </tr>
        </table>
      </div>


    </main>
    </body>
</html>
