<!DOCTYPE html>
<html lang="en" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-family: sans-serif;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-size: 10px;-webkit-tap-highlight-color: rgba(0,0,0,0);">
<head style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;">
  <title style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;">Bootstrap Example</title>
  <meta charset="utf-8" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;">
  <meta name="viewport" content="width=device-width, initial-scale=1" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;">
</head>
<body style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;margin: 0;font-family: &quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-size: 14px;line-height: 1.42857143;color: #333;background-color: #fff;">

<div class="jumbotron text-center" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;text-align: center;padding-top: 30px;padding-bottom: 30px;margin-bottom: 30px;color: inherit;background-color: #eee;">
  <p style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;orphans: 3;widows: 3;margin: 0 0 10px;margin-bottom: 15px;font-size: 21px;font-weight: 200;">Conserflow</p>
  <!-- <p style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;orphans: 3;widows: 3;margin: 0 0 10px;margin-bottom: 15px;font-size: 21px;font-weight: 200;"></p> -->
  <img src="http://conserflow.com/images/logo_c.png" alt="" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;border: 0;vertical-align: middle;page-break-inside: avoid;max-width: 100%!important;">
</div>
    <h1 align="center" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-size: 36px;margin: .67em 0;font-family: inherit;font-weight: 500;line-height: 1.1;color: inherit;margin-top: 20px;margin-bottom: 10px;"><b style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-weight: 700;">{{$mensaje}}</b></h1>
    <p align="center" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;orphans: 3;widows: 3;margin: 0 0 10px;"><b style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-weight: 700;">Folio OC: </b>{{$folio}} </p>
    <p align="center" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;orphans: 3;widows: 3;margin: 0 0 10px;"><b style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-weight: 700;">Proveedor: </b>{{$proveedor}} </p>
    <p align="center" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;orphans: 3;widows: 3;margin: 0 0 10px;"><b style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-weight: 700;">Monto: </b>{{$monto}} </p>
    <p align="center" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;orphans: 3;widows: 3;margin: 0 0 10px;"><b style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-weight: 700;">Moneda: </b>{{$moneda}} </p>
    <p align="center" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;orphans: 3;widows: 3;margin: 0 0 10px;"><b style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-weight: 700;">Solicitado por : </b>{{$empleado_solicita}}</p>
    <p align="center" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;orphans: 3;widows: 3;margin: 0 0 10px;"><b style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-weight: 700;">Revisar ERP Conserflow Modúlo Contraloria</p>

      <table style="border-collapse: collapse; border: 1px solid black;" align="center">
          <thead>
              <tr>
                <th style="border: 1px solid black" align="center" scope="row">Artículo/Servicio</th>
                <th style="border: 1px solid black" align="center" scope="row">Cantidad</th>
                <th style="border: 1px solid black" align="center" scope="row">Precio Unitario</th>
              </tr>
          </thead>
          <tbody>
            @foreach($partidas as $value)
            <tr>
              <td style="text-align: justify; border: 1px solid black;" width="450">{{$value->articulo_descripcion}}</td>
              <td style="border: 1px solid black">{{$value->cantidad_compra}}</td>
              <td style="border: 1px solid black">{{$value->precio_unitario}}</td>
            </tr>
            @endforeach
          </tbody>
      </table>


</body>
</html>
