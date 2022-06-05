<table>
  <tr>
    <td colspan="9" style="text-align : center;">CONSERFLOW S.A. DE C.V.</td>
  </tr>
  <tr>
    <td rowspan="3" colspan="6" style="text-align : center;">
      <b>REPORTE DE EXISTENCIAS </b>
    </td>
    <td style="text-align : center;">CÓDIGO</td>
    <td style="text-align : center;">PAL-01/F-04</td>
  </tr>
  <tr>
    <td style="text-align : center;">REVISIÓN</td>
    <td style="text-align : center;">00</td>
  </tr>
  <tr>
    <td style="text-align : center;">EMISIÓN</td>
    <td style="text-align : center;">01.ABR.20</td>
  </tr>
  <tr>
    <td></td>
  </tr>

  <tr>
    <th style="background-color : #5E7EFF; text-align : center;"><b>No.</b></th>
    <th style="background-color : #5E7EFF; text-align : center;" width="30"><b>Proyecto</b></th>
    <th style="background-color : #5E7EFF; text-align : center;" width="80"><b>Artículo</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Unidad</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Almacén</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Grupo</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Categoría</b></th>
    <th style="background-color : #5E7EFF; text-align : center;"><b>Existencia</b></th>
  </tr>

  <tbody>
    {{$n=1}}
    @foreach($articulos as $articulo)
    <tr>
      <td>{{$n}}</td>
      <td>{{$articulo->p_nombre}}</td>
      <td>{{$articulo->a_desc}}</td>
      <td>{{$articulo->unidad}}</td>
      <td>{{$articulo->al_nombre}}</td>
      <td>{{$articulo->grupo}}</td>
      <td>{{$articulo->categoria}}</td>
      <td>{{$articulo->existencia}}</td>
    </tr>
    {{$n+=1}}
    @endforeach
  </tbody>
</table>
