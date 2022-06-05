<table>
  <tr>
  <td></td>
  </tr>
  <tr>
    <td colspan="2" style="background-color: #0F75BF">Reporte general</td>
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
    @foreach ($arreglo_uno as $key => $a)
    <tr>
      <td>{{$a['uno']->proyecto}}</td>
      @foreach($a['dos'] as $c)
      <td>{{number_format($c->total,2,'.','')}}</td>
      @endforeach
    </tr>
    @endforeach

  </thead>
  <tbody>



<tr>

</tr>
<tr>

</tr>
<tr>
  <td style="background-color: #0F75BF">Proyecto</td>
  <td style="background-color: #0F75BF">Monto</td>
</tr>
 @foreach($arreglo_uno_dos as $arrp)
 <tr>
   <td style="background-color: #DBDBDC">{{$arrp['uno']->proyecto}}</td>
   <td style="background-color: #DBDBDC">{{number_format($arrp['dos'],2,',','')}}</td>
 </tr>
 @endforeach
</tbody>
</table>
