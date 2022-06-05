
<table>
  <thead>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr>
        <td></td>
        <td> </td>
    </tr>
    <tr></tr>
    <tr>
        <td style="background-color: #015D93;">Id</td>
        <td style="background-color: #015D93;">Nombre</td>
        <td style="background-color: #015D93;">Proveedor</td>
        <td style="background-color: #015D93;">Ubicación</td>
        <td style="background-color: #015D93;">Lote</td>
        <td style="background-color: #015D93;">Orden Compra</td>
        <td style="background-color: #015D93;">Existencia en Sistema</td>
        <td style="background-color: #015D93;">Existencia Real CSF</td>
        <td style="background-color: #015D93;">Excedente Físico</td>
        <td style="background-color: #015D93;">Faltate Físico</td>
        <td style="background-color: #015D93;">Existencia Real CSCT</td>
        <td style="background-color: #015D93;">Total</td>
      </tr>
  </thead>
  <tbody>
    @foreach($articulos as $art)
    {
      <tr>
        <!-- Id -->
        <td> {{$art->id}}</td>
        <!-- Nombre -->
        <td width="100"> {{$art->concepto}}</td>
        <!-- Proveedor -->
        <td> {{$art->proveedor}}</td>
        <!-- Ubicación -->
        <td> 
             Almacen: {{$art->alm}} - Stand: {{$art->stand}} - Nivel: {{$art->nivel}}
        </td>
        <!-- Lote -->
        <td> {{$art->lote}}</td>
        <td> {{$art->folio_compra}}</td>
        <td> {{$art->existencia_sistema}}</td>
        <td> -</td>
        <td> -</td>
        <td> -</td>
        <td> -</td>
        <td> -</td>
      </tr>
    }
    @endforeach

  </tbody>
</table>
    