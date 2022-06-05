<tr></tr>
<tr>
  <th style="width: 300;background-color: #ffffff"></th>
</tr>
<th></th>
  <th style="background-color: #2f74b5;"></th>
  <th style="background-color: #2f74b5;"></th>
  <th style="background-color: #2f74b5;"></th>
  <th style="background-color: #2f74b5;"></th>
  <th style="background-color: #2f74b5;"></th>
  <th style="background-color: #2f74b5;"></th>
  <th style="background-color: #2f74b5;"></th>
  <th style="background-color: #2f74b5;"></th>
<tr>
<th style="width: 300;background-color: #ffffff"></th>
</tr>
<tr>
<th style="width: 300;background-color: #ffffff"></th>
</tr>
<tr></tr>
<tr>
<th></th><th></th>
<th>.:. REPORTE GENERAL SEMANAL. .:.</th>
</tr>
<tr></tr>

<tr>
<th style="background-color: #ffc000;">{{strtoupper($nomina->periodo)}}</th>
<th style="background-color: #ffff99;">Del: {{$nomina->fecha_inicial}}</th>
<th style="background-color: #ffff99;">Al: {{$nomina->fecha_final}}</th>
</tr>
<tr></tr>



<tr>
  <th style="background-color: #2f74b5;" with="300"><b>NOMBRE DEL EMPLEADO</b></th>
  <th style="background-color: #2f74b5;"><b>DIAS TRABAJADOS</b></th>
  <th style="background-color: #2f74b5;"><b>DESCUENTOS</b></th>
  <th style="background-color: #2f74b5;"><b>OTROS INGRESOS</b></th>
  <th style="background-color: #2f74b5;"><b>BANCO</b></th>
  <th style="background-color: #2f74b5;"><b>NUMERO DE CUENTA</b></th>
  <th style="background-color: #2f74b5;"><b>TRANSFERENCIA TOTAL</b></th>
  <th style="background-color: #2f74b5;"><b>EFECTIVOS TOTAL</b></th>
  <th style="background-color: #2f74b5;"><b>TOTALES</b></th>

</tr>

@foreach ($arreglo as $proyecto)
    <table>
      <thead>
        <tr>
          <th>{{$proyecto['Proyectos']->nombre}}</th>
        </tr>
<tr></tr>
      </thead>
      <tbody>
        @foreach ($proyecto['contrato'] as $contra)
        <tr>
          <td>{{$contra->e_nombre}}</td>
          <td>{{$contra->dias_trabajados}}</td>
          <td>{{$contra->descuento}}</td>
          <td>{{$contra->otros}}</td>
          <td>{{$contra->banco}}</td>
          <td>{{$contra->numero_cuenta}}</td>
          <td>{{$contra->transferencias}}</td>
          <td>{{$contra->efectivos}}</td>
          <td>{{$contra->totales}}</td>

        </tr>
        @endforeach
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td>TOTAL PROYECTO</td>
          <td>{{$proyecto['contrat']->totaltr}}</td>
          <td>{{$proyecto['contra']->totalef}}</td>
          <td>{{$proyecto['contr']->totalto}}</td>

        </tr>
        <tr>
          <td></td>
        </tr>



      </tbody>
    </table>
    @endforeach
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td>TOTAL GENERAL</td>
      <td>{{$contrat_to->totaltr_to}}</td>
      <td>{{$contra_to->totalef_to}}</td>
      <td>{{$contr_to->totalto_to}}</td>

    </tr>
 <!-- <td>
 /*<*?
 // =$generos[($album->genre_id)-1]->name
 ?>
</td> -->
