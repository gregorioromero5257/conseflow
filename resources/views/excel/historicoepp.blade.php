<table>
  <tr>
    <td colspan="9" style="text-align : center;">CONSERFLOW S.A. DE C.V.</td>
  </tr>
  <tr>
    <td rowspan="3" colspan="6" style="text-align : center;">
      <b>REPORTE DE HISTOROCO EPP </b>
    </td>
    <td style="text-align : center;">CÓDIGO</td>
    <td style="text-align : center;"></td>
  </tr>
  <tr>
    <td style="text-align : center;">REVISIÓN</td>
    <td style="text-align : center;"></td>
  </tr>
  <tr>
    <td style="text-align : center;">EMISIÓN</td>
    <td style="text-align : center;"></td>
  </tr>
  <tr>
    <td></td>
  </tr>

  <tr>
    <th style="background-color : #5E7EFF; text-align : center;"><b>No.</b></th>
    <th style="background-color : #5E7EFF; text-align : center;" width="80"><b>Artículo</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Cantidad</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Fecha</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Recibe</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Autoriza</b></th>
  </tr>

  <tbody>
    @foreach($articulos_epp as $key => $articulo)
    <tr>
      <td>{{$key + 1}}</td>
      <td>{{$articulo->descripcion}}</td>
      <td>{{$articulo->cantidad}}</td>
      <td>{{$articulo->fecha}}</td>
      <td>{{$articulo->empleador}}</td>
      <td>{{$articulo->empleadoa}}</td>
    </tr>
    @endforeach
  </tbody>
</table>
