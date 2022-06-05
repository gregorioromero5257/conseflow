<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title></title>
</head>
<body>

  <table width="100%">
    @foreach ($arreglo as $key => $value)

    <tr>
      <td style="border: 40px solid;" width="35%" height="196px;">
        <img src="data:image/png;base64,'.{{$value->generate()}}.'"  width="196px"/>
      </td>
      <td width="35%" ><p style="text-align: center">&nbsp;&nbsp;{{$empleados[$key]->empleado}}</p></td>
      <td width="20%">&nbsp;</td>
    </tr>
  @endforeach

  </table>
</body>
</html>
