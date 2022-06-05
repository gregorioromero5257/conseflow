<!DOCTYPE html>
<html lang="en" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-family: sans-serif;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-size: 10px;-webkit-tap-highlight-color: rgba(0,0,0,0);">
<head style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;">
  <title style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;">Bootstrap Example</title>
  <meta charset="utf-8" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;">
  <meta name="viewport" content="width=device-width, initial-scale=1" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;">
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;"> -->
<style>
.table {
  border-collapse: collapse;
  width: 100%;
  border: 1px solid #666;
  font-size: 12px;
}
body {
  margin: 0px;
}
.table td, th {
    border: 1px solid #666;
    padding-top: 3px;
    padding-bottom: 3px;
}
p{
    color: #547260;
}
#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}
#customers td, #customers th {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
    color: #547260;
}
#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    border-color: #b4b4b4;
    color: #547260;
    text-align: center;
}
</style>
</head>

<body style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;margin: 0;font-family: &quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-size: 14px;line-height: 1.42857143;background-color: #eee;">

<div class="jumbotron text-center" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;text-align: center;padding-top: 30px;padding-bottom: 30px;margin-bottom: 30px;color: inherit;background-color: #eee; color: #547260;">
  <p style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;orphans: 3;widows: 3;margin: 0 0 10px;margin-bottom: 15px;font-size: 21px;font-weight: 200;">Conserflow de México</p>
  <!-- <p style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;orphans: 3;widows: 3;margin: 0 0 10px;margin-bottom: 15px;font-size: 21px;font-weight: 200;">Constructora y Servicios Calderón Torres</p> -->
  <img src="http://conserflow.com/images/logo_c.png" alt="" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;border: 0;vertical-align: middle;page-break-inside: avoid;max-width: 100%!important;">
</div>
<h1 align="center" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-size: 36px;margin: .67em 0;font-family: inherit;font-weight: 500;line-height: 1.1;color: inherit;margin-top: 20px;margin-bottom: 10px;"><b style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-weight: 700; color:#547260;">{{$mensaje}}</b></h1>
<h3 align="center" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-size: 36px;margin: .67em 0;font-family: inherit;font-weight: 500;line-height: 1.1;color: inherit;margin-top: 20px;margin-bottom: 10px;"><b style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-weight: 700; color:#547260;">{{$mensaje_adicional}}</b></h3>

    <h3 align="center" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-size: 36px;margin: .67em 0;font-family: inherit;font-weight: 500;line-height: 1.1;color: inherit;margin-top: 20px;margin-bottom: 10px;"><b style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-weight: 700; color:#547260;"></b></h3>

    <p align="center" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;orphans: 3;widows: 3;margin: 0 0 10px;"><b style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-weight: 700;">Solicitado por : {{$solicitud->beneficiario}}
  </b></p>
    <p align="center" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;orphans: 3;widows: 3;margin: 0 0 10px;"><b style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-weight: 700;">REVISAR ERP CONSERFLOW MODULO VIATICOS</p>
    <p align="center" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;orphans: 3;widows: 3;margin: 0 0 10px;"><b style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-weight: 700;"><a href="https://syscfw.conserflow.com/">Conserflow ERP</a></p>




</body>
</html>
