<html>
<table>



 @foreach($arreglo as $value)

    <tr>
      <td style="background-color: #0B98FF;" colspan="8">{{$value['grupos']->nombre}}</td>
    </tr>
    <tr>
      <td style="background-color:#ddebf7">Articulo</td>
      <td style="background-color:#ddebf7">Cantidad Ingresada</td>
      <td style="background-color:#ddebf7">Precio Unitario</td>
      <td style="background-color:#ddebf7">Total</td>
      <td style="background-color:#ddebf7">Proyecto</td>
      <td style="background-color:#ddebf7">Entradas</td>
      <td style="background-color:#ddebf7">Salidas</td>
      <td style="background-color:#ddebf7">Existencia</td>
    </tr>
    @foreach($value['articulos'] as $valor)
    <tr>
      <td>{{$valor['uno']->descripcion}}</td>
      <td>{{$valor['uno']->cantidad_ingresada}}</td>
      <td>{{$valor['uno']->precio_unitario}}</td>
      <td>{{$valor['uno']->total}}</td>
      <td>{{$valor['uno']->nombre_corto}}</td>
      <td>{{$valor['en']->total_entrada}}</td>
      <td>{{$valor['sa']->total_salida}}</td>
      <td>{{$valor['uno']->existencia}}</td>
    </tr>
    @endforeach
    <br>
    <tr>
      <td colspan="2"></td>
      <td style="background-color: #0489B1">Total</td>
      <td>{{$value['suma']->total_gpo}}</td>
    </tr>
    <tr>
      <td></td>
    </tr>
@endforeach

</table>
</html>
