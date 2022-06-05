<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Inicio de Sesión</title>
	<link rel="stylesheet" href="/css/app.css">
	<link rel="shortcut icon" href="img/web.ico">
	
	<!--Evita ver contenido despues de cerrar sesión-->
    <meta http-equiv="Expires" content="0" /> 
    <meta http-equiv="Pragma" content="no-cache" />
    <!--Fin-->
    <style>
    	body{
    		background-image: url('img/Back-Conserflow.jpg');
			background-position: center center;
			background-repeat: no-repeat;
			background-attachment: fixed;
			background-size: cover;
			background-position-y: 90;

    	}
    </style>
</head>
<body>
	<div class="container">
		
		@if (session()->has('flash'))
			<div class="alert alert-info">{{ session('flash') }}</div>
		@endif

		@yield('content')
	</div>
	<footer class="app-footer" align="center">
	<br>
        <h2> <a target="_blank" href="http://www.conserflow.com/">CONSERFLOW
		<?php

$Year = date("Y");
echo "$Year.";
echo "\n";
?>
		</a>&copy;
		</h2>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<h3><b>CONSERFLOW S.A DE C.V.</b></h3>
    </footer>
</body>
</html>

<script type="text/javascript">
  if(history.forward(1)){
    location.replace( history.forward(1) );
  }
</script>