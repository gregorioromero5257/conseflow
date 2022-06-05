<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;"> -->
    <style type="text/css">
  .tg  {border-collapse:collapse;border-spacing:0;border-color:#C44D58;}
  .tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-top-width:1px;border-bottom-width:1px;border-color:#C44D58;color:#002b36;background-color:#F9CDAD;}
  .tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-top-width:1px;border-bottom-width:1px;border-color:#C44D58;color:#fdf6e3;background-color:#FE4365;}
  .tg .tg-0lax{text-align:left;vertical-align:top}
  .tg .tg-bw1t{background-color:#FFA4A0;text-align:left;vertical-align:top}
  </style>
  <style type="text/css">
.tz  {border-collapse:collapse;border-spacing:0;border-color:#bbb;}
.tz td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-top-width:1px;border-bottom-width:1px;border-color:#bbb;color:#594F4F;background-color:#E0FFEB;}
.tz th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-top-width:1px;border-bottom-width:1px;border-color:#bbb;color:#493F3F;background-color:#9DE0AD;}
.tz .tg-0lax{text-align:left;vertical-align:top}
.tz .tg-sjuo{background-color:#C2FFD6;text-align:left;vertical-align:top}
</style>

  </head>
  <body>

    <table class="tg">
      <tr>
        <th class="tg-0lax" colspan="5" >ARTÍCULOS CON BAJO STOCK</th>

      </tr>
      <tr>
        <th class="tg-0lax">Código</td>
        <th class="tg-0lax">Nombre</th>
        <th class="tg-0lax">Descripción</th>
        <th class="tg-0lax">Mínimo</th>
        <th class="tg-0lax">Stock</th>
      </tr>
        @foreach($minimo as $key => $value)
      <tr>
        <td class="tg-0lax">{{$value->codigo}}</td>
        <td class="tg-alz1">{{$value->nombre}}</td>
        <td class="tg-0lax">{{$value->descripcion}}</td>
        <td class="tg-alz1">{{$value->minimo}}</td>
        <td class="tg-0lax">{{$value->existencia}}</td>
      </tr>
      @endforeach
    </table>
    <br><br>
    <table class="tz">
      <tr>
        <th class="tg-0lax" colspan="5" >ARTÍCULOS CON ALTO STOCK</th>

      </tr>
      <tr>
        <th class="tg-0lax">Código</td>
        <th class="tg-0lax">Nombre</th>
        <th class="tg-0lax">Descripción</th>
        <th class="tg-0lax">Máximo</th>
        <th class="tg-0lax">Stock</th>
      </tr>
        @foreach($maximo as $key => $value)
      <tr>
        <td class="tg-0lax">{{$value->codigo}}</td>
        <td class="tg-alz1">{{$value->nombre}}</td>
        <td class="tg-0lax">{{$value->descripcion}}</td>
        <td class="tg-alz1">{{$value->maximo}}</td>
        <td class="tg-0lax">{{$value->existencia}}</td>
      </tr>
      @endforeach
    </table>

  </body>
</html>
