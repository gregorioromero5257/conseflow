    <table>
      <tr></tr>
      <tr></tr>
      <tr></tr>
      <tr></tr>
      <tr></tr>
      <tr>
      <th></th>
      <th></th>
      <th>.:. REPORTE GENERAL SEMANAL. .:.</th>
      </tr>
      <tr></tr>

      <tr>
      <th>{{strtoupper($nomina->periodo)}}</th>
      <th>Del: {{$nomina->fecha_inicial}}</th>
      <th>Al: {{$nomina->fecha_final}}</th>
    </tr>
    <tr></tr>
      <thead>
        <tr>
          <th with="300"><b>NOMBRE DEL EMPLEADO</b></th>
          <th><b>BANCO</b></th>
          <th><b>NUMERO DE CUENTA</b></th>
          <th><b>TRANSFERENCIA TOTAL</b></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($contratosp as $contra)
        <tr>
          <td>{{strtoupper($contra->e_nombre)}}</td>
          <td>{{$contra->banco}}</td>
          <td>{{$contra->numero_cuenta}}</td>
          <td>{{$contra->transferencias}}</td>
       </tr>
        @endforeach
        <tr>
          <td></td>
        </tr>
      </tbody>
    </table>
    <tr>
      <td></td>
      <td></td>
      <td>TOTAL GENERAL</td>
      <td>{{$contrat_to->totaltr_to}}</td>
    </tr>
 <!-- <td>
 /*<*?
 // =$generos[($album->genre_id)-1]->name
 ?>
</td> -->
