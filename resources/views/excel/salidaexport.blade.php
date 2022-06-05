<table>
  <tr>
    <td colspan="9" style="text-align : center;">CONSERFLOW S.A. DE C.V.</td>
  </tr>
  <tr>
    <td rowspan="3" colspan="6" style="text-align : center;">
      
    </td>
    <td style="text-align : center;"></td>
    <td style="text-align : center;"></td>
  </tr>
  <tr>
    <td style="text-align : center;"></td>
    <td style="text-align : center;"></td>
  </tr>
  <tr>
    <td style="text-align : center;"></td>
    <td style="text-align : center;"></td>
  </tr>
  <tr>
    <td></td>
  </tr>

  <tr>
    <th style="background-color : #5E7EFF; text-align : center;"><b>No.</b></th>
    <th style="background-color : #5E7EFF; text-align : center;" ><b>Tipo</b></th>
    <th style="background-color : #5E7EFF; text-align : center;" ><b>Oc Folio</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Proyecto Origen</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Folio Salida</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Descripción</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Cantidad</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Unidad</b></th>
  </tr>

  <tbody>
    {{$n=1}}
    @foreach($array as $articulo)
    <tr>
      <td>{{$n}}</td>
      <td>{{$articulo['tipo']}}</td>
      <td>{{$articulo['oc_folio']}}</td>
      <td>{{$articulo['nombre_corto']}}</td>
      <td>{{$articulo['folio']}}</td>
      <td>{{$articulo['desc']}}</td>
      <td>{{$articulo['cantidad_salida']}}</td>
      <td>{{$articulo['unidad']}}</td>
    </tr>
    {{$n+=1}}
    @endforeach
  </tbody>
</table>
