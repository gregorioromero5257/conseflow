@switch($condicion)

@case(1)
@foreach($arreglo as $nomina)
<table>
  <thead>
    <tr>
      <th>{{$nomina['nominas']->periodo}}</th>
    </tr>
    <tr>
    <th>Dias trabajados</th>
    <th>Empleado</th>
    <th>
      @if($tipo == 1)
      Efectivo
      @elseif($tipo == 2)
      Transferencia
      @endif
    </th>
  </tr>
  </thead>
  <tbody>
    @foreach($nomina['movimientos'] as $value)
    <tr>
      <td>{{$value->dias_trabajados}}</td>
      <td>{{$value->e_nombre}}</td>
      <td>{{$value->valor}}</td>
    </tr>
    @endforeach
    <tr>
      <td></td><td>Total :</td><td>{{$nomina['suma']->suma}}</td>
    </tr>

  </tbody>
</table>
@endforeach
<table>
  <tr>
    <td></td><td>TOTAL</td><td>{{$suma->suma}}</td><td></td>
  </tr>
</table>
@break

@case(2)
@foreach($arreglo as $nomina)
<table>
  <thead>
    <tr>
      <th>{{$nomina['nominas']->periodo}}</th>
      <th>{{$nomina['movimientos'][0]->nombrep}}</th>
    </tr>
    <tr>
    <th>Dias trabajados</th>
    <th>Empleado</th>
    <th>Transferencia</th>
    <th>Efectivo</th>
    <th>Total</th>

  </tr>
  </thead>
  <tbody>
    @foreach($nomina['movimientos'] as $value)
    <tr>
      <td>{{$value->dias_trabajados}}</td>
      <td>{{$value->e_nombre}}</td>
      <td>{{$value->transferencias}}</td>
      <td>{{$value->efectivos}}</td>
      <td>{{$value->totales}}</td>

    </tr>
    @endforeach
    <tr>
      <td></td><td>Total :</td><td>{{$nomina['suma']->sumat}}</td><td>{{$nomina['suma']->sumae}}</td>
    </tr>

  </tbody>
</table>
@endforeach
<table>
  <thead>
    <tr>
      <th></th><th>TOTAL GENERAL:</th><th>{{$suma->sumatg}}</th><th>{{$suma->sumaeg}}</th>
    </tr>
  </thead>
</table>
@break

@case(3)
<table>
  <tr>
    <td>Nominas pertenecientes del: {{$condiciontrestexto}}</td>
  </tr>
</table>
@foreach($arreglo as $nomina)
<table>
  <thead>
    <tr>
      <th>{{$nomina['nominas']->periodo}}</th>
      <th></th>
    </tr>
    <tr>
    <th>Dias trabajados</th>
    <th>Empleado</th>
    <th>Transferencia</th>
    <th>Efectivo</th>
    <th>Total</th>

  </tr>
  </thead>
  <tbody>
    @foreach($nomina['movimientos'] as $value)
    <tr>
      <td>{{$value->dias_trabajados}}</td>
      <td>{{$value->e_nombre}}</td>
      <td>{{$value->transferencias}}</td>
      <td>{{$value->efectivos}}</td>
      <td>{{$value->totales}}</td>

    </tr>
    @endforeach
    <tr>
      <td></td><td>Total :</td><td>{{$nomina['suma']->sumat}}</td><td>{{$nomina['suma']->sumae}}</td>
    </tr>
  </tbody>
</table>
@endforeach
<table>
  <thead>
    <tr>
      <th></th><th>TOTAL GENERAL:</th><th>{{$suma->sumatg}}</th><th>{{$suma->sumaeg}}</th>
    </tr>
  </thead>
</table>
@break

@case(4)
<table>
  <tr>
    <td>Nominas pertenecientes del: </td>
    <td>{{$condiciontrestexto}}</td>
    <td></td>
  </tr>
</table>
@foreach($arreglo as $nomina)
<table>
  <thead>
    <tr>
      <th>{{$nomina['nominas']->periodo}}</th>
      <th>{{$nomina['movimientos'][0]->nombrep}}</th>
    </tr>
    <tr>
    <th>Dias trabajados</th>
    <th>Empleado</th>
    <th>Transferencia</th>
    <th>Efectivo</th>
    <th>Total</th>

  </tr>
  </thead>
  <tbody>
    @foreach($nomina['movimientos'] as $value)
    <tr>
      <td>{{$value->dias_trabajados}}</td>
      <td>{{$value->e_nombre}}</td>
      <td>{{$value->transferencias}}</td>
      <td>{{$value->efectivos}}</td>
      <td>{{$value->totales}}</td>

    </tr>
    @endforeach
    <tr>
      <td></td><td>Total :</td><td>{{$nomina['suma']->sumat}}</td><td>{{$nomina['suma']->sumae}}</td>
    </tr>
  </tbody>
</table>
@endforeach
<table>
  <thead>
    <tr>
      <th></th><th>TOTAL GENERAL:</th><th>{{$suma->sumatg}}</th><th>{{$suma->sumaeg}}</th>
    </tr>
  </thead>
</table>
@break

@case(5)
<table>
  <tr>
    <td>Nominas pertenecientes del: </td>
    <td>{{$condiciontrestexto}}</td>
    <td></td>
  </tr>
</table>
@foreach($arreglo as $nomina)
<table>
  <thead>
    <tr>
      <th>{{$nomina['nominas']->periodo}}</th>
    </tr>
    <tr>
    <th>Dias trabajados</th>
    <th>Empleado</th>
    <th>  @if($tipo == 1)
      Efectivo
      @elseif($tipo == 2)
      Transferencia
      @endif</th>

  </tr>
  </thead>
  <tbody>
    @foreach($nomina['movimientos'] as $value)
    <tr>
      <td>{{$value->dias_trabajados}}</td>
      <td>{{$value->e_nombre}}</td>
      <td>
        @if($tipo == 1)
         {{$value->efectivos}}
         @elseif($tipo == 2)
         {{$value->transferencias}}
         @endif
      </td>


    </tr>
    @endforeach
    <tr>
      <td></td><td>Total :</td><td>

        {{$nomina['suma']->suma}}

        </td>

    </tr>
  </tbody>
</table>
@endforeach
<table>
  <thead>
    <tr>
      <th></th><th>TOTAL GENERAL:</th><th>
        @if($tipo == 1)
      {{$suma->sumaeg}}
         @elseif($tipo == 2)
      {{$suma->sumatg}}
         @endif
        </th>
    </tr>
  </thead>
</table>
@break

@case(6)
<table>
  <tr>
    <td>Nominas pertenecientes del proyecto:  </td>
    <td></td>
    <td></td>
  </tr>
</table>
@foreach($arreglo as $nomina)
<table>
  <thead>
    <tr>
      <th></th>
    </tr>
    <tr>
    <th>Dias trabajados</th>
    <th>Empleado</th>
    <th>  @if($tipo == 1)
      Efectivo
      @elseif($tipo == 2)
      Transferencia
      @endif</th>
      <th>{{$nomina['movimientos'][0]->nombrep}}</th>

  </tr>
  </thead>
  <tbody>
    @foreach($nomina['movimientos'] as $value)
    <tr>
      <td>{{$value->dias_trabajados}}</td>
      <td>{{$value->e_nombre}}</td>
      <td>
        @if($tipo == 1)
         {{$value->efectivos}}
         @elseif($tipo == 2)
         {{$value->transferencias}}
         @endif
      </td>


    </tr>
    @endforeach
    <tr>
      <td></td><td>Total :</td><td>

        {{$nomina['suma']->suma}}

        </td>

    </tr>
  </tbody>
</table>
@endforeach
<table>
  <thead>
    <tr>
      <th></th><th>TOTAL GENERAL:</th><th>
        @if($tipo == 1)
      {{$suma->sumaeg}}
         @elseif($tipo == 2)
      {{$suma->sumatg}}
         @endif
        </th>
    </tr>
  </thead>
</table>
@break

@case(7)
<table>
  <tr>
    <td>Nominas pertenecientes del: </td>
    <td>{{$condiciontrestexto}}</td>
    <td></td>
  </tr>
</table>
@foreach($arreglo as $nomina)
<table>
  <thead>
    <tr>
      <th>{{$nomina['nominas']->periodo}}</th>
    </tr>
    <tr>
    <th>Dias trabajados</th>
    <th>Empleado</th>
    <th>  @if($tipo == 1)
      Efectivo
      @elseif($tipo == 2)
      Transferencia
      @endif</th>
        <th>{{$nomina['movimientos'][0]->nombrep}}</th>

  </tr>
  </thead>
  <tbody>
    @foreach($nomina['movimientos'] as $value)
    <tr>
      <td>{{$value->dias_trabajados}}</td>
      <td>{{$value->e_nombre}}</td>
      <td>
        @if($tipo == 1)
         {{$value->efectivos}}
         @elseif($tipo == 2)
         {{$value->transferencias}}
         @endif
      </td>


    </tr>
    @endforeach
    <tr>
      <td></td><td>Total :</td><td>

        {{$nomina['suma']->suma}}

        </td>

    </tr>
  </tbody>
</table>
@endforeach
<table>
  <thead>
    <tr>
      <th></th><th>TOTAL GENERAL:</th><th>
        @if($tipo == 1)
      {{$suma->sumaeg}}
         @elseif($tipo == 2)
      {{$suma->sumatg}}
         @endif
        </th>
    </tr>
  </thead>
</table>
@break

@case(8)
<table>
  <tr>
    <td>Nomina General Quincenal: </td>
    <td></td>
  </tr>
</table>
@foreach($arreglo as $nomina)
<table>
  <thead>
    <tr>
      <th>{{$nomina['nominas']->periodo}}</th>
    </tr>
    <tr>
    <th>Dias trabajados</th>
    <th>Empleado</th>
    <th>Transferencia</th>
    <th>Efectivo</th>
    <th>Total</th>

  </tr>
  </thead>
  <tbody>
    @foreach($nomina['movimientos'] as $value)
    <tr>
      <td>{{$value->dias_trabajados}}</td>
      <td>{{$value->e_nombre}}</td>
      <td>{{$value->transferencias}}</td>
      <td>{{$value->efectivos}}</td>
      <td>{{$value->totales}}</td>

    </tr>
    @endforeach
    <tr>
      <td></td><td>Total :</td><td>{{$nomina['suma']->sumat}}</td><td>{{$nomina['suma']->sumae}}</td>
    </tr>
  </tbody>
</table>
@endforeach
<table>
  <thead>
    <tr>
      <th></th><th>TOTAL GENERAL:</th><th>{{$suma->sumatg}}</th><th>{{$suma->sumaeg}}</th>
    </tr>
  </thead>
</table>
@break

@case(99)
<table>
  <thead>
    <tr>
      <th>VACIO</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>VACIO</td>
    </tr>
  </tbody>
</table>
@break

@endswitch
