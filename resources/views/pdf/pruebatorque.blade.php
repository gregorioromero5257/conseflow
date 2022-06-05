<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PCA-01-F_01 REPORTE DE TORQUE</title>
    <style type="text/css">
        @page {
            margin-top: 3cm;
            margin-left: 1cm;
            margin-right: 1cm;
            margin-bottom: 2cm;
        }

        header {
            position: fixed;
            top: -100px;
            left: 0px;
            right: 0px;
            height: 160px;
        }

        footer {
            position: fixed;
            bottom: -40px;
            left: 0px;
            right: 0px;
            height: 20px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
        }

        .borc {
            border: 1px solid;
            text-align: center;
        }

        .borce {
            border: 1px solid;
            text-align: center;
            background-color: #0070C0;
            font-weight: bold;
        }

        .borcd {
            border: 1px solid;
            text-align: center;
            background-color: #BFBFBF;
            font-weight: bold;
        }

        .borl {
            text-align: left;
        }

        .bort {
            text-align: center;
            border: 1px solid;
        }

        .tabe {
            border-collapse: collapse;
            border: 1px solid;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            text-align: center;
        }

        .tab {
            border-collapse: collapse;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            text-align: center;
        }

        .tabb {
            border-collapse: collapse;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            text-align: center;
            border: 1px solid;
        }

        .taj {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            text-align: justify;
        }

        .tal {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            text-align: left;
        }
    </style>

</head>

<body>
    <header>
        <p>{{$torque->nombre_proyecto}}</p>
        <p>{{$torque->ubicacion}}</p>
        <p>{{$torque->cliente}}</p>
        <p>{{$torque->fecha}}</p>
        <p>{{$torque->folio}}</p>
        <br>
        <p>{{$torque->sistema_nombre}}</p>
        <p>{{$torque->tag}}</p>
        <p>{{$torque->referencia}}</p>
        <p>{{$torque->diametro}}</p>
        <p>{{$torque->material}}</p>
        <p>{{$torque->procedimiento}}</p>
        <br>
        <p>{{$torque->ec1_desc}}</p>
        <p>{{$torque->ec1_numero_serie}}</p>
        <p>{{$torque->ec1_modelo}}</p>
        <p>{{$torque->secuencia_1}}</p>
        <br>
        <p>{{$torque->ec2_desc}}</p>
        <p>{{$torque->ec2_numero_serie}}</p>
        <p>{{$torque->ec2_modelo}}</p>
        <p>{{$torque->secuencia_2}}</p>
    </header>

    <footer>
        <p>CONSERFLOW S.A. DE C.V.</p>
    </footer>

    <!-- Wrap the content of your PDF inside a main tag -->
   
    <script type="text/php">
        if (isset($pdf)) {
        $text = "PAGINA {PAGE_NUM} DE {PAGE_COUNT}";
        $size = 12;
        $font = $fontMetrics->getFont("Verdana");
        $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
        $x = ($pdf->get_width() - $width) / 1;
        $y = $pdf->get_height() - 35;
        $pdf->page_text($x, $y, $text, $font, $size);
    }
</script>
</body>

</html>