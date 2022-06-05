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
      <th style="background-color: #0F75BF">Empleado</th>
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
      <td>{{$a['empleados']->empleado_nombre}}</td>
      @foreach($a['apa'] as $c)
      <td>{{$c}}</td>
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
 @foreach($arreglo_proyectos as $arrp)
 <tr>
   <td style="background-color: #DBDBDC">{{$arrp['proyecto']->nombre_corto}}</td>
   <td style="background-color: #DBDBDC">{{$arrp['total']}}</td>
 </tr>
 @endforeach
</tbody>
</table>
