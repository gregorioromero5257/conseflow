<table>
  <tr>
  <td></td>
  </tr>
  <tr>
    <td colspan="2" style="background-color: #0F75BF">Reporte General Administrativos</td>
    <td colspan="4" style="background-color: #0F75BF"></td>
  </tr>
  <thead>
    <tr>
      <th style="background-color: #0F75BF">Proyecto</th>
      @foreach($a as $value)
      <td style="background-color: #0F75BF">Semana {{$value['ns']}}</td>
      @endforeach
    </tr>
    <tr>
      <td style="background-color: #0F75BF"></td>
      @foreach($a as $value)
      <td style="background-color: #0F75BF">{{$value['i']}} - {{$value['f']}}</td>
      @endforeach
    </tr>
    @foreach ($arreglos_final as $key => $a)
    <tr>
      <td>{{$a['proyectos']->nombre_corto}}</td>
      {{$sma = 0}}
      @foreach($a['apa'] as $k => $c)
      <td>
        @if($c == 0)
        {{number_format($c,2,'.','')}}
        @else
        {{ ((number_format(($c),2,'.','')))   }}
        @endif
        </td>
      @endforeach
    </tr>
    @endforeach

  </thead>
  <tbody>



<tr>

</tr>
<tr>

</tr>

</tbody>
</table>
