<!DOCTYPE html>
<html lang="en" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-family: sans-serif;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-size: 10px;-webkit-tap-highlight-color: rgba(0,0,0,0);">
<head style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;">
  <title style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;">Bootstrap Example</title>
  <meta charset="utf-8" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;">
  <meta name="viewport" content="width=device-width, initial-scale=1" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;">
  <link rel="stylesheet" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;">

</head>
<body style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;margin: 0;font-family: &quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-size: 14px;line-height: 1.42857143;color: #333;background-color: #fff;">

<div class="jumbotron text-center" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;text-align: center;padding-top: 30px;padding-bottom: 30px;margin-bottom: 30px;color: inherit;background-color: #eee;">
  <p style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;orphans: 3;widows: 3;margin: 0 0 10px;margin-bottom: 15px;font-size: 21px;font-weight: 200;">Conserflow de México</p>
  <p style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;orphans: 3;widows: 3;margin: 0 0 10px;margin-bottom: 15px;font-size: 21px;font-weight: 200;">Constructora y Servicios Calderón Torres</p>
  <img src="http://www.conservct.com/images/logo-flow.png?crc=3859741646" alt="" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;border: 0;vertical-align: middle;page-break-inside: avoid;max-width: 100%!important;">
</div>

<div class="container" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding-right: 15px;padding-left: 15px;margin-right: auto;margin-left: auto;">
<h1 align="center" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-size: 36px;margin: .67em 0;font-family: inherit;font-weight: 500;line-height: 1.1;color: inherit;margin-top: 20px;margin-bottom: 10px;">{{$mensaje}}</h1>
<p align="center" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;orphans: 3;widows: 3;margin: 0 0 10px;"><b style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-weight: 700;">Solicitado por :</b>{{$nombre}}</p>
<p align="center" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;orphans: 3;widows: 3;margin: 0 0 10px;"><b style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-weight: 700;">Pagado el :</b>{{$fecha}}</p>
<h4 align="center" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;orphans: 3;widows: 3;margin: 0 0 10px;"><b style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-weight: 700;">Efectivo : $</b>{{$efectivo}}</h4>
<h4 align="center" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;orphans: 3;widows: 3;margin: 0 0 10px;"><b style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-weight: 700;">Transferencia : $</b>{{$transferencia}}</h4>
</div>

</body>
</html>
