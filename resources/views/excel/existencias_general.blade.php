<table>
  <tr>
    <td colspan="9" style="text-align : center;">CONSERFLOW S.A. DE C.V.</td>
  </tr>
  <tr>
    <td rowspan="3"></td>
    <td rowspan="3" colspan="6" style="text-align : center;">
      <b>REPORTE DE EXISTENCIAS</b>
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
    <td colspan="5"></td>
    <td colspan="2" style="background-color : #8E8F93; text-align : center;">Fecha del Reporte</td>
    <td colspan="2" style="text-align : center;"> <b>{{date("Y-m-d")}}</b>
    </td>
  </tr>
  <thead>
    <tr>
      <th style="background-color : #5E7EFF; text-align : center;"><b>Categoria</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>Orden Compra</b></th>
      <th width="80" style="background-color : #5E7EFF; text-align : justify;"><b>Artículo</b></th>
      <th style="background-color : #5E7EFF; text-align : justify;"><b>Unidad</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>Precio Unitario</b></th>
      <th style="background-color : #5E7EFF; text-align : center;" width="30"><b>Proyecto</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>Entradas</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>Salidas</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>Retorno</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>Existencia</b></th>
    </tr>
  </thead>

  <tbody>
    @foreach($array as $articulo)
    <tr>
      <td>{{$articulo['a']->nombre_grupo}}</td>
      <td>{{$articulo['a']->folio}}</td>
      <td>{{$articulo['a']->descripcion}}</td>
      <td>{{$articulo['a']->unidad}}</td>
      <td>{{$articulo['a']->precio_unitario}}</td>
      <td>{{$articulo['a']->nombre_corto}}</td>
      <td>{{$articulo['a']->cantidad}}</td>
      <td>{{$articulo['b']}}</td>
      <td>{{$articulo['c']}}</td>
      <td>{{$articulo['a']->la_cantidad}}</td>
    </tr>

    @endforeach
  </tbody>
</table>
