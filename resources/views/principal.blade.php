 <!DOCTYPE html>
 <?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');
?>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema">
    <meta name="author" content="">
    <meta name="keyword" content="">
    <title>Sistema de Gestion Conserflow</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--Evita ver contenido despues de cerrar sesión-->
    <meta http-equiv="Expires" content="0" />
    <meta http-equiv="Pragma" content="no-cache" />

    <!--Fin-->


    <!-- Icons -->
    <link rel="stylesheet" href="css/tablesfixed.css">
    <link href="css/plantilla.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    <div id="app">
    <header class="app-header navbar">
        <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#" @click="abrirModalModulos()"></a>
        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
          <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="nav navbar-nav d-md-down-none">
            <li class="nav-item px-3">
                <a class="nav-link" href="#" @click="abrirModalModulos()">Panel</a>
            </li>
        </ul>
        <ul class="nav navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <img src="img/avatars/git.png" class="img-avatar">
                    <span class="d-md-down-none">{{ auth()->user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header text-center">
                        <strong>Cuenta</strong>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                            {{ csrf_field() }}
                    <button class="dropdown-item" @click="cerrarSesion()"><i class="fa fa-lock"></i>Cerrar Sesión</button>
                    </form>
                    <form method="POST" action="{{ route('ayuda') }}" target="_blank">
                            {{ csrf_field() }}
                        <button class="dropdown-item"><i class="fas fa-question"></i>Ayuda</button>
                    </form>
                    <form method="POST" action="{{ route('privacidad') }}" target="_blank">
                            {{ csrf_field() }}
                        <button class="dropdown-item"><i class="fas fa-user-secret"></i>Privacidad</button>
                    </form>
                </div>
            </li>
        </ul>
    </header>

    <div class="app-body">

        @include('plantilla.sidebar')
        <!-- Contenido Principal -->
        @yield('contenido')
        <!-- /Fin del contenido principal -->
    </div>
    </div>
    <footer class="d-none d-lg-block app-footer fixed-bottom">
        <span><a href="http://www.conserflow.com/">Conserflow</a> &copy; <?php

$Year = date("Y");
echo "$Year.";
echo "\n";
?></span>
        <span class="ml-auto">Sistema de Gestion Conserflow</span>
    </footer>

<script src="js/app.js"></script>
<script src="js/plantilla.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />



<script type="text/javascript">
  if(history.forward(1)){
    location.replace( history.forward(1) );
  }
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<!-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-2C0M9V6MNC"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-2C0M9V6MNC');
</script> -->
<!--Script para validar inactividad en la aplicación-->
<script type="text/javascript">
// var idleTime = 0;
// $(document).ready(function () {
//     //Increment the idle time counter every minute.
//     idleInterval = setInterval(timerIncrement, 86400); // 1 minute
//
//     //Zero the idle timer on mouse movement.
//     $('body').mousemove(function (e) {
//      // alert("mouse moved" + idleTime);
//      idleTime = 0;
//     });
//
//     $('body').keypress(function (e) {
//       // alert("keypressed"  + idleTime);
//         idleTime = 0;
//     });
//
//
//
//     $('body').click(function() {
//       // alert("mouse moved" + idleTime);
//        idleTime = 0;
//     });
//
// });
//
// function timerIncrement() {
//     idleTime = idleTime + 1;
//     if (idleTime > 300) { // 5 minutes
//
//         window.location.assign("Salir");
//     }
// }
</script>
<!--fin validar inactividad en la aplicación-->



<!--Romper sesion al cerrar el navegador o la pestaña de la aplicacion-->
<script type="text/javascript">
var _wasPageCleanedUp = false;
function pageCleanup()
{
    if (!_wasPageCleanedUp)
    {
        $.ajax({
            type: 'GET',
            async: false,
            url: 'CerrarSesion',
            success: function ()
            {
                _wasPageCleanedUp = true;
            }
        });
    }
}


$(window).on('onbeforeunload', function ()
{
    pageCleanup();
});

$(window).on("unload", function ()
{
    pageCleanup();
});
</script>

<script type="text/javascript">
var _wasPageCleanedUpDos = false;
function pageCleanupDos()
{
    if (!_wasPageCleanedUpDos)
    {
        $.ajax({
            type: 'GET',
            async: false,
            url: 'ActualizarVista',
            success: function ()
            {
                _wasPageCleanedUpDos = true;
            }
        });
    }
}

$(window).on("load", function ()
{
    pageCleanupDos();
});
</script>
<!--Fin Romper sesion al cerrar el navegador o la pestaña de la aplicacion-->


<!--Botar al usuario de la sesión-->
<!--<script type="text/javascript">
var idleTime = 0;
$(document).ready(function () {
    //Increment the idle time counter every minute.
    idleInterval = setInterval(timerIncrement, 3000); // 5 segundos
});

function timerIncrement() {

    $.ajax({
        url: "Solicitud",
        type: "GET",
    }).done(function(respuesta){

        if (respuesta.navegador === "*****-/////-*****") {
            window.location.assign("Salir");
        }
    });
}
</script>-->
<!--Fin Botar al usuario de la sesión-->

</body>

</html>
