<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Solicitud Prestamo</title>
    <style>
        #footer {
  margin: 190px 0 0 0; padding: 0 0 10px 0; /* suponemos un pie de página de altura 100px */

}
    </style>
    
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-secondary">
                <table class="table table-sm">
                  <tbody>
                    <tr>
                        <td class="w-75">
                            <p>
                                Calle Del Mezquite Lote 5, Mza. 3,<br>
                                Parque industrial Tehuacán-Miahuatlán.<br>
                                C.P. 75820
                            </p>
                        </td>
                        <td class="w-25">
                        </td>
                        <td>
                            <img src="img/conserflow.png" alt="Conserflow" width="150" height="70" align="right">
                        </td>
                    </tr>
                  </tbody>
                </table>
            </div>
            <div class="alert alert-primary" role="alert">
            </div>
            <p align="center">SOLICITUD DE PRESTAMO</p>
            <br>
            <table class="table table-sm table-bordered">
                <tr>
                    <th scope="col" class="w-25">FECHA:</th>
                    <td scope="col" class="w-25">{{ $prestamos->fecha }}</td>
                </tr>
            </table>
            <table class="table table-sm table-bordered">
                <tr>
                    <th scope="col" class="w-25">NOMBRE DEL EMPLEADO:</th>
                    <td scope="col" class="w-25">{{ $prestamos->ap_paterno.' '.$prestamos->ap_materno.' '.$prestamos->nombre }}</td>
                </tr>
            </table>
            <br>
            <br>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th scope="col" class="w-25">CANTIDAD</th>
                        <th scope="col" class="w-25">NÚMEROS DE PAGO</th>
                        <th scope="col" class="w-25">OBSERVACIONES</th>
                    </tr>
                </thead>
                    <tr>
                        <td class="w-25">{{ $prestamos->cantidad }}</td>
                        <td class="w-25">{{ $prestamos->numero_pago }}</td>
                        <td class="w-25">{{ $prestamos->observaciones }}</td>
                    </tr>
            </table>
            <!-- div footer -->
            <div id="footer">
                <table class="table table-sm" align="center">
                    <thead>
                        <tr>
                            <th scope="col" class="w-25">________________________</th>
                            <th scope="col" class="w-25"></th>
                            <th scope="col" class="w-25">________________________</th>
                        </tr>
                    </thead>
                        <tr>
                            <td class="w-25" align="center">{{ $prestamos->ap_paterno.' '.$prestamos->ap_materno.' '.$prestamos->nombre }}</td>
                            <td class="w-25"></td>
                            <td class="w-25" align="center">RECURSOS HUMANOS</td>
                        </tr>
                </table>
            </div>
            <!-- end div footer -->
        </div>
    </div>
</div>
</body>
</html>