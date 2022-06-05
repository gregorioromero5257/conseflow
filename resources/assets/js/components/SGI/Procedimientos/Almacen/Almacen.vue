<template>
<main class="main">
    <div class="container-fluid">

        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-link active" id="nav-almacen-tab" data-toggle="tab" href="#nav-almacen" role="tab" aria-controls="nav-almacen" aria-selected="true">Almacén</a>
                <a class="nav-link" id="nav-entrada-tab" data-toggle="tab" href="#nav-entrada" role="tab" aria-controls="nav-entrada" aria-selected="true">Entrada de Almacén</a>
                <a class="nav-link" id="nav-salida-tab" data-toggle="tab" href="#nav-salida" role="tab" aria-controls="nav-salida" aria-selected="false">Salida de Almacén</a>
                <a class="nav-link" id="nav-resguardo-tab" data-toggle="tab" href="#nav-resguardo" role="tab" aria-controls="nav-resguardo" aria-selected="false">Resguardo de Herramienta</a>
                <a class="nav-link" id="nav-existencias-tab" data-toggle="tab" href="#nav-existencias" role="tab" aria-controls="nav-existencias" aria-selected="false">Existencias</a>
                <a class="nav-link" id="nav-mantenimiento-tab" data-toggle="tab" href="#nav-mantenimiento" role="tab" aria-controls="nav-mantenimiento" aria-selected="false">Programa de Mantenimiento</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <!-- 0 -->
            <div class="tab-pane fade show active" id="nav-almacen" role="tabpanel" aria-labelledby="nav-almacen-tab">

                <div class="card-header bg-dark">
                    <p class="h4 text-center mt-2 text-white">Almacén</p>
                </div>
                <br>
                <object data="/procedimientos/almacen/PAL-01 Almacén.pdf" type="application/pdf" width="1040" height="1360">
                </object>
            </div>
            
             <!-- 1 -->
            <div class="tab-pane fade" id="nav-entrada" role="tabpanel" aria-labelledby="nav-entrada-tab">

                <div class="card-header bg-dark">
                    <p class="h4 text-center mt-2 text-white">PAL-01_F-01 Entrada de Almacén</p>
                </div>
                <br>
                <object data="/procedimientos/almacen/PAL-01 Almacén.pdf" type="application/pdf" width="1040" height="1360">
                </object>
            </div>

            <!-- 2 -->
            <div class="tab-pane fade" id="nav-salida" role="tabpanel" aria-labelledby="nav-salida-tab">
                <div class="card-header bg-dark">
                    <p class="h4 text-center mt-2 text-white">PAL-01_F-02 Salida de Almacén</p>
                </div>
                <br>
                <object data="/procedimientos/almacen/PAL-01_F-02 Salida de Almacén.pdf" type="application/pdf" width="1040" height="1360">
                </object>
            </div>

            <!-- 3 -->
            <div class="tab-pane fade" id="nav-resguardo" role="tabpanel" aria-labelledby="nav-resguardo-tab">
                <div class="card-header bg-dark">
                    <p class="h4 text-center mt-2 text-white">PAL-01_F-03 Resguardo de Herramienta</p>
                </div>
                <br>
                <object data="/procedimientos/almacen/PAL-01_F-03 Resguardo de Herramienta.pdf" type="application/pdf" width="1040" height="1360">
                </object>
            </div>

            <!-- 4 -->
            <div class="tab-pane fade" id="nav-existencias" role="tabpanel" aria-labelledby="nav-existencias-tab">
                <div class="card-header bg-dark">
                    <p class="h4 text-center mt-2 text-white">PAL-01_F-04 Existencias</p>
                </div>
                <br>
                <object data="/procedimientos/almacen/PAL-01_F-04 Existencias.pdf" type="application/pdf" width="1040" height="1360">
                </object>
            </div>

            <!-- 5 -->
            <div class="tab-pane fade" id="nav-mantenimiento" role="tabpanel" aria-labelledby="nav-mantenimiento-tab">
                <div class="card-header bg-dark">
                    <p class="h4 text-center mt-2 text-white">PAL-01_F-05 Programa de Mantenimiento de herramienta de corte y perforación </p>
                </div>
                <br>
                <object data="/procedimientos/almacen/PAL-01_F-05 Programa de Mantenimiento de herramienta de corte y perforación.pdf" type="application/pdf" width="1040" height="1360">
                </object>
            </div>
        </div>
    </div>
</main>
</template>