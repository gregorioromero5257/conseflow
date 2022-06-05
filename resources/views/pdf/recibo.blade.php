<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Recibos</title>
    
</head>
<body>
@php
$sumaD = 0;
$sumaP = 0;
@endphp
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
            <p>PERIODO: {{$recibos->periodo}}</p>
            <p>FECHA DE PAGO: {{$recibos->fecha_pago}}</p>
            <p>DEL: {{$recibos->periodo_inicial}} AL: {{$recibos->periodo_final}}</p>
            <br>
            <table class="table table-sm table-bordered">
                <tr>
                    <th scope="col" class="w-25">NOMBRE DEL EMPLEADO</th>
                    <th scope="col" class="w-25">PUESTO</th>
                    <th scope="col" class="w-25">DEPARTAMENTO</th>
                </tr>
                <tr>
                    <td class="w-25">{{ $recibos->ap_paterno.' '.$recibos->ap_materno.' '.$recibos->nombre }}</td>
                    <td class="w-25">{{ $recibos->puesto }}</td>
                    <td class="w-25">{{ $recibos->departamento }}</td>
                </tr>
            </table>
            <br>
            <br>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th scope="col" class="w-25">PERCEPCIONES</th>
                        <th scope="col" class="w-25">DEDUCCIONES</th>
                    </tr>
                </thead>
                    <tr>
                        <td>
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col" class="w-25">Descripción</th>
                                        <th scope="col" class="w-25">Unidad</th>
                                        <th scope="col" class="w-25">Costo</th>
                                        <th scope="col" class="w-25">Importe</th>
                                    </tr>
                                </thead>
                                @foreach ($percepciones as $percepcion)
                                    <tr>
                                        <td class="w-25">{{ $percepcion->nombre }}</td>
                                        <td class="w-25">
                                        {{ $percepcion->unidad }}
                                        </td>
                                        <td class="w-25">
                                        {{ $percepcion->costo }}
                                        </td>
                                        <td class="w-25">
                                        {{ $percepcion->importe }}
                                        </td>
                                    </tr>
                                    @php
                                    $sumaP += $percepcion->unidad + $percepcion->costo + $percepcion->importe;
                                    @endphp
                                @endforeach
                            </table>
                        </td>
                        <td>
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col" class="w-25">Descripción</th>
                                        <th scope="col" class="w-25">Unidad</th>
                                        <th scope="col" class="w-25">Costo</th>
                                        <th scope="col" class="w-25">Importe</th>
                                    </tr>
                                </thead>
                                @foreach ($deducciones as $deduccion)
                                    <tr>
                                        <td class="w-25">{{ $deduccion->nombre }}</td>
                                        <td class="w-25">
                                        {{ $deduccion->unidad }}
                                        </td>
                                        <td class="w-25">
                                        {{ $deduccion->costo }}
                                        </td>
                                        <td class="w-25">
                                        {{ $deduccion->importe }}
                                        </td>
                                    </tr>
                                    @php
                                    $sumaD += $deduccion->unidad + $deduccion->costo + $deduccion->importe;
                                    @endphp
                                @endforeach
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-25" align="right">TOTAL DE PERCEPCIONES</td>
                        <td class="w-25" align="right">TOTAL DE DEDUCCIONES</td>
                    </tr>
                    <tr>
                        <td class="w-25" align="right">$
                            {{ $sumaP }}
                        </td>
                        <td class="w-25" align="right">$
                            {{ $sumaD }}
                        </td>
                    </tr>
                    <tr>
                        <td class="w-25"></td>
                        <td class="w-25 bg-primary" align="right">NETO PAGADO: $ {{ $recibos->total_a_pagar }}</td>
                    </tr>
            </table>
        </div>
    </div>
</div>
</body>
</html>