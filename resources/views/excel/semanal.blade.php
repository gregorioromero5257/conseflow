<html>

<table>
    <!-- uno -->
    <tr></tr>
    <!-- dos -->|
    <tr></tr>
    <!-- tres -->
    <tr>
      <td style="background-color: #115FCA;"></td>
      <td style="background-color: #FFFFFF;"></td>
      <td style="background-color: #115FCA;"></td>
      <td style="background-color: #115FCA;"></td>
      <td style="background-color: #115FCA;"></td>
      <td style="background-color: #115FCA;"></td>
      <td style="background-color: #115FCA;"></td>
      <td style="background-color: #115FCA;"></td>     <!-- Infonavit -->
      <td style="background-color: #115FCA;"></td>
      <td style="background-color: #115FCA;"></td>
      <td style="background-color: #115FCA;"></td>
      <td style="background-color: #115FCA;"></td>
    </tr>
    <!-- cuatro -->
    <tr></tr>
    <!-- cinco -->
    <tr></tr>

    <!-- seis -->
    <tr></tr>
    <!-- siete -->
    <tr>
      <th style="background-color: #ffc000;"></th>
      <th style="background-color: #ffc000;" >{{strtoupper($nomina->periodo)}}</th>
      <th style="background-color: #ffff99;">Del: {{$nomina->fecha_inicial}}</th>
      <th style="background-color: #ffff99;">Al: {{$nomina->fecha_final}}</th>
      <th style="background-color: #ffff99;"></th>
      <th style="background-color: #ffff99;"></th>
    </tr>
    <!-- ocho -->
    <!--
      <tr></tr>
    -->
    <!-- nueve -->
    <tr></tr>
      <tr>
        <th style="background-color: #2f74b5;">NO.</th>
        <th style="background-color: #2f74b5;"><b>NOMBRE</b></th>
        <th style="background-color: #2f74b5;"><b>PUESTO</b></th>
        <th style="background-color: #2f74b5;"><b>SD</b></th>
        <th style="background-color: #2f74b5;"><b>SALARIO SEMANAL</b></th>
        <th style="background-color: #2f74b5;"><b>DIAS LABORADOS</b></th>
        <th style="background-color: #2f74b5;"><b>DESCTOS</b></th>
        <th style="background-color: #2f74b5;"><b>INFONAVIT</b></th>

        <th style="background-color: #ffc000;"><b>NOMINA</b></th>
        <th style="background-color: #ffc000;"><b>VIATICOS ALIMENTOS</b></th>
        <th style="background-color: #ffc000;"><b>TOTAL A PAGAR</b></th>

        <th style="background-color: #2f74b5;"><b>OBSERVACIONES</b></th>
        <th style="background-color: #2f74b5;"><b>BANCO</b></th>
        <th style="background-color: #2f74b5;"><b># TARJETA</b></th>
        <th style="background-color: #2f74b5;"><b># CUENTA</b></th>
        <th style="background-color: #2f74b5;"><b>CLABE</b></th>
      </tr>
  </table>

  @foreach ($arreglo as $proyecto)
    <table>
      <thead>
        <tr>

          <th style="background-color: #ddebf7;" colspan="8">
          {{strtoupper($proyecto['Proyectos']->nombre_corto)}}
        </th>
        </tr>

      </thead>
        <tbody>
          @foreach ($proyecto['contrato'] as $contra)
          <tr>
            <td></td>
            <td>{{strtoupper($contra->e_nombre)}}</td>
            <td>{{$contra->nombre_puesto}}</td>
            <td>{{$contra->sueldo_diario_real}}</td>
            <td>{{$contra->sueldo_diario_real}}</td>
            <td>{{$contra->dias_trabajados}}</td>
            <td>{{$contra->descuento}}</td>
            <td>{{$contra->infonavit}}</td>
            <td>{{($contra->dias_trabajados)*($contra->sueldo_diario_real)}}</td>
            <td>0</td>
            <td>{{($contra->dias_trabajados)*($contra->sueldo_diario_real)}}</td>
            <td></td>
            <td>{{$contra->banco}}</td>
            <td>{{$contra->numero_tarjeta}}</td>
            <td>{{$contra->numero_cuenta}}</td>
            <td>{{$contra->clabe}}</td>
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


</html>
<!--
//47 117 181

//255 192 0

// 255 255 153


// Azul  1: 2f74b5
// Azul  2: ddebf7
// Amarillo 1: ffc000
// Amarillo 1: ffff99
-->
