<table>
  <tr>
  <td></td>
  </tr>
  <tr>
    <td colspan="2" style="background-color: #0F75BF"></td>
    <td colspan="4" style="background-color: #0F75BF"></td>
    <td colspan="2" style="background-color: #0F75BF"></td>
  </tr>
  <thead>
    <tr>
      <th style="background-color: #0F75BF">Proyecto</th>
      @foreach($a as $value)
      <td style="background-color: #0F75BF">Semana {{$value['ns']}}</td>
      @endforeach
      <td style="background-color: #0F75BF"></td>
    </tr>
    <tr>
      <td style="background-color: #0F75BF"></td>
      @foreach($a as $value)
      <td style="background-color: #0F75BF">{{$value['i']}} - {{$value['f']}}</td>
      @endforeach
      <td style="background-color: #0F75BF">TOTAL</td>
    </tr>
  </thead>
  <tbody>

@foreach($arreglo_dos as $value)
<tr>
  <td style="background-color: #DBDBDC">&nbsp;</td>
  <td style="background-color: #DBDBDC">{{$value['proyecto']->nombre_corto}}</td>

</tr>
@foreach ($value['empleados'] as $key => $v)
<tr>
  <td>{{$v['nombre']}}</td>

  @foreach ($v['datos'] as $k => $va)
  <td>{{number_format($va,2,'.','')}}</td>
  @endforeach
  <td>{{number_format($v['total'],2,'.','')}}</td>

</tr>
@endforeach
<tr>
<td></td>
</tr>
@endforeach

<tr>

</tr>
<tr>

</tr>
<tr>
  <td style="background-color: #0F75BF">Proyecto</td>
  <td style="background-color: #0F75BF">Imss</td>
  <td style="background-color: #0F75BF">Infonavit</td>
  <td style="background-color: #0F75BF">Subtotal</td>
  <td style="background-color: #0F75BF">Total</td>
</tr>
 @foreach($arreglo_proyectos_suma as $arrp)
 <tr>
   <td style="background-color: #DBDBDC">{{$arrp['proyecto']->nombre_corto}}</td>
   <td style="background-color: #DBDBDC">{{number_format($arrp['total_imss'],2,'.','')}}</td>
   <td style="background-color: #DBDBDC">{{number_format($arrp['total_infonavit'],2,'.','')}}</td>
   <td style="background-color: #DBDBDC">{{number_format($arrp['subtotal'],2,'.','')}}</td>
   <td style="background-color: #DBDBDC">{{number_format($arrp['total'],2,'.','')}}</td>
 </tr>
 @endforeach


</tbody>
</table>
