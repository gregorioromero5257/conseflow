
<table>
  <thead>
    <tr>
      <td colspan="10" style="background-color: #015D93;">Reporte generada hasta {{date('Y-m-d')}}</td>
    </tr>
    <tr>
        <td style="background-color: #015D93;">Nombre</td>

        <td style="background-color: #015D93;">Fecha Alta/Modifocacion IMMS</td>

        <td style="background-color: #015D93;">Origen</td>
        <td style="background-color: #015D93;">Puesto</td>
        <td style="background-color: #015D93;">NSS</td>
        <td style="background-color: #015D93;">SDI</td>
        <td style="background-color: #015D93;">Tipo Nomina</td>
        <td style="background-color: #015D93;">Dias laborados</td>
        <td style="background-color: #015D93;">Salario Diario</td>
        <td style="background-color: #015D93;">Salario</td>
        <td style="background-color: #015D93;">Pago Ordinario</td>
        <td style="background-color: #015D93;">Otros Ingresos</td>

        <td style="background-color: #39BF1B;">Ajuste al neto</td>
        <td style="background-color: #39BF1B;">ISR ajustado por subsidio</td>
        <td style="background-color: #39BF1B;">Subs al Empleo mes</td>
        <td style="background-color: #39BF1B;">Subs entregado que no correspondía</td>
        <td style="background-color: #39BF1B;">Subsidio al Empleo sp</td>
        <td style="background-color: #39BF1B;">Subsidio al empleo</td>



        <td style="background-color: #015D93;">IMSS</td>
        <td style="background-color: #015D93;">ISR mes</td>
        <td style="background-color: #015D93;">ISR Art142</td>
        <td style="background-color: #015D93;">ISR de ajuste mensual</td>
        <td style="background-color: #015D93;">ISR sp</td>
        <td style="background-color: #015D93;">Ajuste al neto</td>
        <td style="background-color: #015D93;">Ajuste en Subsidio para el empleo</td>
        <td style="background-color: #015D93;">Ajuste al Subsidio Causado</td>
        <td style="background-color: #015D93;">Desc Habitacion</td>
        <td style="background-color: #015D93;">Desc Alimentacion</td>
        <td style="background-color: #015D93;">Permiso Sin Goce de Sueldo</td>
        <td style="background-color: #015D93;">Préstamo Infonavit cf</td>
        <td style="background-color: #015D93;">Préstamo Infonavit vsm</td>
        <td style="background-color: #015D93;">Préstamo empresa</td>
        <td style="background-color: #015D93;">Seguro de vivienda Infonavit</td>

        <td style="background-color: #015D93;">Infonavit</td>
        <td style="background-color: #015D93;">Importe Neto</td>
        <td style="background-color: #015D93;">Importe RH</td>
        <td style="background-color: #015D93;">Diferencia</td>
      </tr>
  </thead>
  <tbody>



@foreach($arreglo_masivo as $d)

    @foreach($d as $k => $arr)

      <tr>
        <td> {{$arr['movimiento']->nombre}}</td>

        <td>{{$arr['movimiento']->fecha}}</td>

        <td> {{$arr['estado'] == 1 ? 'Factura' : 'RH'}}</td>
        <td> {{$arr['semana'] == null ? '' :  $arr['semana']->puesto}}</td>
        <td> {{$arr['movimiento']->nss}}</td>

          @if($arr['estado'] == 1)
          @if($arr['factura']->SalarioBaseCotApor != $arr['movimiento']->salario)
          <td style="background-color: #F1432B;">
            {{$arr['factura']->SalarioBaseCotApor}}
          </td>
          @else
          <td>
            {{$arr['factura']->SalarioBaseCotApor}}
          </td>
          @endif
          @elseif($arr['estado'] == 0)
            <td>{{$arr['movimiento']->salario}}</td>
          @endif

        <td>Semanal</td>
        <td>{{$arr['semana'] == null ? $arr['factura']->NumDiasPagados : $arr['semana']->dl}}</td>
        <td>{{$arr['semana'] == null ? $arr['factura']->SalarioDiarioIntegrado : $arr['semana']->sd}}</td>
        <td>{{round($arr['salario'],2) }}</td>
        <td>{{round($arr['po'],2) }}</td>

        @if($arr['estado'] == 1)
        <td>0</td>
        @elseif($arr['estado'] == 0)
        <td>{{$arr['semana']['viaticos_alimentos']}}</td>
        @endif


        @if($arr['estado'] == 1)

        <td>
        @foreach ($arr['otros'] as $o => $ot)
        @if($ot->Concepto === "Ajuste al neto")
        {{$ot->Importe}}
        @endif
        @endforeach
        </td>

        <td>
        @foreach ($arr['otros'] as $o => $ot)
        @if($ot->Concepto === "ISR ajustado por subsidio")
        {{$ot->Importe}}
        @endif
        @endforeach
        </td>

        <td>
        @foreach ($arr['otros'] as $o => $ot)
        @if($ot->Concepto === "Subs al Empleo mes")
        {{$ot->Importe}}
        @endif
        @endforeach
        </td>

        <td>
        @foreach ($arr['otros'] as $o => $ot)
        @if($ot->Concepto === "Subs entregado que no correspondía")
        {{$ot->Importe}}
        @endif
        @endforeach
        </td>

        <td>
        @foreach ($arr['otros'] as $o => $ot)
        @if($ot->Concepto === "Subsidio al Empleo sp")
        {{$ot->Importe}}
        @endif
        @endforeach
        </td>

        <td>
        @foreach ($arr['otros'] as $o => $ot)
        @if($ot->Concepto === "Subsidio al empleo")
        {{$ot->Importe}}
        @endif
        @endforeach
        </td>

        @elseif($arr['estado'] == 0)
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        @endif

          @if($arr['estado'] == 1)

          <td>
          @foreach ($arr['de'] as $m => $d)
          @if($d->Concepto === "IMSS")
          {{$d->Importe}}
          @endif
          @endforeach
          </td>

          <td>
          @foreach ($arr['de'] as $m => $d)
          @if($d->Concepto === "ISR mes")
          {{$d->Importe}}
          @endif
          @endforeach
          </td>

          <td>
          @foreach ($arr['de'] as $m => $d)
          @if($d->Concepto === "ISR Art142")
          {{$d->Importe}}
          @endif
          @endforeach
          </td>

          <td>
          @foreach ($arr['de'] as $m => $d)
          @if($d->Concepto === "ISR de ajuste mensual")
          {{$d->Importe}}
          @endif
          @endforeach
          </td>

          <td>
          @foreach ($arr['de'] as $m => $d)
          @if($d->Concepto === "ISR sp")
          {{$d->Importe}}
          @endif
          @endforeach
          </td>

          <td>
          @foreach ($arr['de'] as $m => $d)
          @if($d->Concepto === "Ajuste al neto")
          {{$d->Importe}}
          @endif
          @endforeach
          </td>

          <td>
          @foreach ($arr['de'] as $m => $d)
          @if($d->Concepto === "Ajuste en Subsidio para el empleo")
          {{$d->Importe}}
          @endif
          @endforeach
          </td>

          <td>
          @foreach ($arr['de'] as $m => $d)
          @if($d->Concepto === "Ajuste al Subsidio Causado")
          {{$d->Importe}}
          @endif
          @endforeach
          </td>

          <td>
          @foreach ($arr['de'] as $m => $d)
          @if($d->Concepto === "Desc Habitacion")
          {{$d->Importe}}
          @endif
          @endforeach
          </td>

          <td>
          @foreach ($arr['de'] as $m => $d)
          @if($d->Concepto === "Desc Alimentacion")
          {{$d->Importe}}
          @endif
          @endforeach
          </td>

          <td>
          @foreach ($arr['de'] as $m => $d)
          @if($d->Concepto === "Permiso Sin Goce de Sueldo")
          {{$d->Importe}}
          @endif
          @endforeach
          </td>

          <td>
          @foreach ($arr['de'] as $m => $d)
          @if($d->Concepto === "Préstamo Infonavit cf")
          {{$d->Importe}}
          @endif
          @endforeach
          </td>


          <td>
          @foreach ($arr['de'] as $m => $d)
          @if($d->Concepto === "Préstamo Infonavit vsm")
          {{$d->Importe}}
          @endif
          @endforeach
          </td>

          <td>
          @foreach ($arr['de'] as $m => $d)
          @if($d->Concepto === "Préstamo empresa")
          {{$d->Importe}}
          @endif
          @endforeach
          </td>

          @elseif($arr['estado'] == 0)
          <td>{{round($arr['factura']['imss'],2)}}</td>
          <td>{{round($arr['factura']['isr'],2)}}</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>


          @endif
          <td></td>

          @if($arr['infonavit'] != null)
          <td>{{($arr['infonavit']->importe/$arr['infonavit']->dias)*$arr['semana']->dl}}</td>
          @else
          <td></td>
          @endif



          @if($arr['estado'] == 1)
          <td>{{round($arr['factura']['tt'],2)}}</td>
          @elseif($arr['estado'] == 0)
          <td>{{ round( ($arr['po'] + ($arr['estado'] == 1 ? 0 : $arr['semana']['viaticos_alimentos'])) - (($arr['infonavit'] != null ? (($arr['infonavit']->importe/$arr['infonavit']->dias)*$arr['semana']->dl) : 0 ) + ( ($arr['factura']['imss'] + $arr['factura']['isr']) )) ,2) }}</td>
          @endif

          <td>{{$arr['semana'] == null ? 0 : $arr['semana']->total}}</td>

<td></td>
          <td>{{$arr['semana']['semana']}}</td>


      </tr>

    @endforeach


@endforeach

  </tbody>
</table>
