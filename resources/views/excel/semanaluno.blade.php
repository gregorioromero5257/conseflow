<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr>
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

  <tr>
          <th with="300"><b>NOMBRE DEL EMPLEADO</b></th>
          <th><b>DIAS TRABAJADOS</b></th>
          <th><b>TRANSFERENCIA TOTAL</b></th>
          <th><b>EFECTIVOS TOTAL</b></th>
        </tr>

@foreach ($arreglo as $proyecto)
    <table>
      <thead>
        <tr></tr>
        <tr>
        <th></th>  <th>{{$proyecto['Proyectos']->nombre}}</th>
        </tr>

      </thead>
      <tbody>
        @foreach ($proyecto['contrato'] as $contra)
        <tr>
          <td>{{strtoupper($contra->e_nombre)}}</td>
          <td>{{$contra->dias_trabajados}}</td>

          <td>{{$contra->transferencias}}</td>
          <td>{{$contra->efectivos}}</td>




        </tr>
        @endforeach
        <tr>
          <td></td>
          <td>TOTAL PROYECTO</td>
          <td>{{$proyecto['contrat']->totaltr}}</td>
          <td>{{$proyecto['contra']->totalef}}</td>


        </tr>
        <tr>
          <td></td>
        </tr>



      </tbody>
    </table>
    @endforeach
    <tr>
      <td></td>

      <td>TOTAL GENERAL</td>
      <td>{{$contrat_to->totaltr_to}}</td>
      <td>{{$contra_to->totalef_to}}</td>


    </tr>
 <!-- <td>
 /*<*?
 // =$generos[($album->genre_id)-1]->name
 ?>
</td> -->
