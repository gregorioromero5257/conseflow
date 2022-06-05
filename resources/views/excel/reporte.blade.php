
<table>

    <tr>
      <td></td>
      @foreach ($a as $k => $v)
      <td>{{$v}}</td>
      @endforeach
    </tr>

    @foreach($arreglo as $art)

      <tr>
        <!-- Id -->
        <td> {{$art['x']->nombre}}</td>
        <!-- Nombre -->
        @foreach ($art['y'] as $key => $value)
        <td > {{$value['b']}}</td>
        @endforeach

      </tr>

    @endforeach


</table>
