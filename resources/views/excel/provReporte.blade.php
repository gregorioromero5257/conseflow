<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de proveedores</title>
</head>

<body>

    <table class="borde">
        <tr>
            <td class="borde" colspan="10">CONSERFLOW S.A. DE C.V.</td>
        </tr>
        <tr>
            <td rowspan="3"></td>
            <td class="borde" rowspan="3" colspan="10">
                <b>CATÁLOGO DE PROVEEDORES</b>
            </td>
            <td >CÓDIGO</td>
            <td >PCO-02/F-02</td>
        </tr>
        <tr>
            <td >REVISIÓN</td>
            <td >00</td>
        </tr>
        <tr>
            <td >EMISIÓN</td>
            <td >29.JUN.20</td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <thead>
            <tr>
                <th style="background-color : #0070C0; text-align : center;" width="40"><b>Razón Social</b></th>
                <th style="background-color : #0070C0; text-align : center;" width="35"><b>Nombre Comercial</b></th>
                <th style="background-color : #0070C0; text-align : center;"  width="16"><b>RFC</b></th>
                <th style="background-color : #0070C0; text-align : center;" width="40"><b>Giro</b></th>
                <th style="background-color : #0070C0; text-align : center;" width="25"><b>Dirección</b></th>
                <th style="background-color : #0070C0; text-align : center;" width="30"><b>Contacto</b></th>
                <th style="background-color : #0070C0; text-align : center;" width="20"><b>Teléfono</b></th>
                <th style="background-color : #0070C0; text-align : center;" width="30"><b>Correo electrónico</b></th>
                <th style="background-color : #0070C0; text-align : center;" width="25"><b>Página</b></th>

                <th style="background-color : #0070C0; text-align : center;" width="25"><b>Límite de crédito</b></th>
                <th style="background-color : #0070C0; text-align : center;" width="25"><b>Días de crédito</b></th>
                <th style="background-color : #0070C0; text-align : center;" width="10"><b>Total Evaluación </b></th>
                <th style="background-color : #0070C0; text-align : center;" width="10"><b>Categoría Proveedor</b></th>

        </thead>
        <tbody>
            @foreach($aux as $proveedor)
            <tr>
                <td style="text-align : justify;">{{$proveedor["razon_social"]}}</td>
                <td style="text-align : justify;">{{$proveedor["nombre"]}}</td>
                <td style="text-align : justify;">{{$proveedor["rfc"]}}</td>
                <td style="text-align : justify;">{{$proveedor["giro"]}}</td>
                <td style="text-align : justify;">{{$proveedor["direccion"]}}</td>
                <td style="text-align : justify;">{{$proveedor["contacto"]}}</td>
                <td style="text-align : justify;">{{$proveedor["telefono"]}}</td>
                <td style="text-align : justify;">{{$proveedor["correo"]}}</td>
                <td style="text-align : justify;">{{$proveedor["pagina"]}}</td>

                <td style="text-align : justify;">{{$proveedor["limite_credito"]}}</td>

                <td style="text-align : inherit;;">{{$proveedor["dia_credito"]}}</td>
                <td style="text-align : center;;">{{$proveedor["total_eval"]}}</td>

                @if($proveedor["total_eval"]==null)
                    <td style="text-align : center;;">SIN EVALUACIÓN</td>
                @elseif($proveedor["total_eval"]>=54)
                    <td style="text-align : center;;">IMPORTANTE</td>
                @elseif($proveedor["total_eval"]>=36 && $proveedor["total_eval"]<=53)
                    <td style="text-align : center;;">NO CRÍTICO</td>
                    @else
                    <td style="text-align : center;;">CRÍTICO</td>
                @endif

            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
