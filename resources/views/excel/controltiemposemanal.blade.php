<table>
  <tr>
  <td></td>
  </tr>
  <tr>
    <td colspan="2" style="background-color: #0F75BF">SUPERVISOR</td>
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
    </tr>
  </thead>
  <tbody>

@foreach($arreglo as $value)
<tr>
  <td style="background-color: #DBDBDC">{{$value['empleado']}}</td>
  @foreach($value['data'] as $registro)
  <td style="background-color: #DBDBDC">{{$registro[0]}}</td>
  @endforeach
</tr>
@endforeach

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
