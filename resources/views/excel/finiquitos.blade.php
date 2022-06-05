<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <table>
    <thead>

      <tr>
        <th><b>Nombre del empleado</b></th>
        <th><b>Fecha inicial</b></th>
        <th><b>Fecha final</b></th>
        <th><b>Empresa</b></th>
        <th><b>Proyecto</b></th>
        <th><b>Total</b></th>
      </tr>

    </thead>
    <tbody>
      @foreach ($finiquitos as $finiquito)
        <tr>
          <td>{{$finiquito->empleado}}</td>
          <td>{{$finiquito->fecha_inicial}}</td>
          <td>{{$finiquito->fecha_final}}</td>
          <td>{{$finiquito->empresa}}</td>
          <td>{{$finiquito->proyecto}}</td>
          <td>{{$finiquito->total}}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</html>