<table>
  <tr>
    <td colspan="8" style="text-align : center;">CONSERFLOW S.A. DE C.V.</td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td colspan="4"></td>
    <td colspan="2" style="background-color : #8E8F93; text-align : center;">Fecha del Reporte</td>
    <td colspan="2" style="text-align : center;"> <b>{{$fecha_reporte}}</b>
    </td>
  </tr>
  <thead>
    <tr>
      <th style="background-color : #5E7EFF; text-align : center;"><b>Empleado</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>Puesto</b></th>
      <th style="background-color : #5E7EFF; text-align : center;"><b>Hora de entrada</b></th>
      <th  width="80" style="background-color : #5E7EFF; text-align : justify;"><b>Firma</b></th>
    </tr>
  </thead>
</table>

<tbody>
  @foreach($asistencias as $articulo)
  <tr>
    <td>{{$articulo->empleado}}</td>
    <td>{{$articulo->nombre}}</td>
    <td>{{$articulo->hora_entrada}}</td>
    <td></td>

  </tr>

  @endforeach
</tbody>
