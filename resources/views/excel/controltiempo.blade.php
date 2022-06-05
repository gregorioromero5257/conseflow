<table>
  <tr>
  <td></td>
  </tr>
  <tr>
    <td colspan="2" style="background-color: #0F75BF"></td>
    <td colspan="4" style="background-color: #0F75BF"></td>
    <td colspan="2" style="background-color: #0F75BF">Semana {{$semana}} del {{date('Y')}}</td>
  </tr>
  <thead>
    <tr>
      <td style="background-color: #0F75BF"></td>
      @for($i=0; $i < count($arreglo_dias); $i++)
      <th style="background-color: #0F75BF">{{$arreglo_dias[$i]}}</th>
      @endfor
    </tr>
    <tr>
      <th style="background-color: #0F75BF">Empleado</th>
      @for($i=0; $i < count($arreglo_fechas); $i++)
      <th style="background-color: #0F75BF">{{$arreglo_fechas[$i]}}</th>
      @endfor
      <td style="background-color: #0F75BF">TOTAL</td>
    </tr>
  </thead>
  <tbody>

@foreach($arreglo_proyectos_new as $value)
<tr>
  <td style="background-color: #DBDBDC">&nbsp;</td>
  <td style="background-color: #DBDBDC">&nbsp;</td>
  <td colspan="6" style="background-color: #DBDBDC">{{$value['proyecto']}}</td>
</tr>
@foreach ($value['total'] as $key => $v)
<tr>
  <td>{{$v['empleados']}}</td>
  @for($dd = 1; $dd <= $v['d']; $dd++)
  <td>&nbsp;</td>
  @endfor
  @foreach ($v['registros'] as $k => $va)
  <td>{{number_format($va['total'],2,'.','')}}</td>
  @endforeach
  @for($ddd = 1; $ddd <= $v['f']; $ddd++)
  <td>&nbsp;</td>
  @endfor
  <td>{{number_format($v['total'],2,'.','')}}</td>
</tr>
@endforeach
<tr>
<td></td>
</tr>
@endforeach

<!--  -->

<tr>
  <td style="background-color: #DBDBDC">&nbsp;</td>
  <td style="background-color: #DBDBDC">&nbsp;</td>
  <td colspan="6" style="background-color: #DBDBDC">Sin proyecto</td>
</tr>
@foreach ($arreglo_sin_p as $key => $sp)
<tr>
  <td>{{$sp['e']}}</td>
  @foreach ($sp['ct'] as $k => $vsp)
  @if($vsp['data'] == null)
  <td>{{number_format(number_format($vsp['total'],2,'.','')/6,2,'.','') }}</td>
  @else
  <td>&nbsp;</td>
  @endif
  @endforeach
  <td>{{number_format($sp['total'],2,'.','')}}</td>
</tr>
@endforeach



<!--  -->
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
 @foreach($arreglo_proyectos as $arrp)
 <tr>
   <td style="background-color: #DBDBDC">{{$arrp['proyecto']->nombre_corto}}</td>
   <td style="background-color: #DBDBDC">{{number_format($arrp['total_imss'],2,'.','')}}</td>
   <td style="background-color: #DBDBDC">{{number_format($arrp['total_infonavit'],2,'.','')}}</td>
   <td style="background-color: #DBDBDC">{{number_format($arrp['subtotal'],2,'.','')}}</td>
   <td style="background-color: #DBDBDC">{{number_format($arrp['total'],2,'.','')}}</td>
 </tr>
 @endforeach
 <tr>
   <td style="background-color: #DBDBDC">Sin proyecto</td>
   <td style="background-color: #DBDBDC">{{number_format($total_sueldos_sin_proyecto_ema,2,'.','')}}</td>
   <td style="background-color: #DBDBDC">{{number_format($total_sueldos_sin_proyecto_eba,2,'.','')}}</td>
   <td style="background-color: #DBDBDC">{{number_format($total_sueldos_sin_proyecto,2,'.','')}}</td>
   <td style="background-color: #DBDBDC">{{number_format(($total_sueldos_sin_proyecto + $total_sueldos_sin_proyecto_ema + $total_sueldos_sin_proyecto_eba),2,'.','')}}</td>
 </tr>
</tbody>
</table>
