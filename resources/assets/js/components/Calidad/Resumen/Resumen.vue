<template>
<main class="main">
    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i> Resumen de proyecto{{proyecto_nombre}}
            <button v-show="tipoAccion == 2" type="button" class="ml-2 btn btn-dark float-sm-right" @click="Regresar()">
                <i class="fas fa-arrow-left">&nbsp;</i>Regresar
            </button>
        </div>
        <div class="card-body">
            <!-- Inicio -->
            <div class="container" v-if="tipoAccion == 1">
                <div class="row">
                    <label class="col-md-2 form-control-label" for="proyecto">Proyecto</label>
                    <v-select label="nombre_proyecto" class="col-md-6" :options="list_proyectos" v-model="proyecto"></v-select>
                    <button class="btn btn-sm btn-dark" @click="CargarDatos()">
                        <i class="fas fa-search mr-1"></i> Buscar
                    </button>
                </div>
                <br>
                <br>
                <br>
                <br>
            </div>

            <!-- Contenido -->
            <div class="container" v-if="tipoAccion == 2">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-link active" id="nav-datos-tab" data-toggle="tab" href="#nav-datos" role="tab" aria-controls="nav-ridatosme" aria-selected="true">GENERAL</a>
                        <a class="nav-link" id="nav-rime-tab" data-toggle="tab" href="#nav-rime" role="tab" aria-controls="nav-rime" aria-selected="true">RIME</a>
                        <a class="nav-link" id="nav-riepc-tab" data-toggle="tab" href="#nav-riepc" role="tab" aria-controls="nav-riepc" aria-selected="false">RIEPC</a>
                        <a class="nav-link" id="nav-soldadores-tab" data-toggle="tab" href="#nav-soldadores" role="tab" aria-controls="nav-soldadores" aria-selected="false">Soldadores</a>
                        <a class="nav-link" id="nav-juntas-tab" data-toggle="tab" href="#nav-juntas" role="tab" aria-controls="nav-juntas" aria-selected="false">Juntas</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-datos" role="tabpanel" aria-labelledby="nav-datos-tab">
                        <div class="container-fluid">
                            <p class="h5 font-weight-bold mb-5">EXAMINACIÓN VISUAL DE SOLDADURAS</p>
                            <resumen-datos :proyecto_id="proyecto_id"></resumen-datos>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-rime" role="tabpanel" aria-labelledby="nav-rime-tab">
                        <div class="">
                            <resumen-rimes :proyecto_id="proyecto_id"></resumen-rimes>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-riepc" role="tabpanel" aria-labelledby="nav-riepc-tab">
                        <resumen-riepc :proyecto_id="proyecto_id"></resumen-riepc>
                    </div>
                    <div class="tab-pane fade" id="nav-soldadores" role="tabpanel" aria-labelledby="nav-soldadores-tab">
                        <resumen-soldadores :proyecto_id="proyecto_id"></resumen-soldadores>
                    </div>
                    <div class="tab-pane fade" id="nav-juntas" role="tabpanel" aria-labelledby="nav-juntas-tab">
                        <resumen-juntas :proyecto_id="proyecto_id"></resumen-juntas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</template>

<script>
const ResumenRime = r => require.ensure([], () => r(require('./ResumenRime.vue')), 'calidad');
const ResumenRiepc = r => require.ensure([], () => r(require('./ResumenRiepc.vue')), 'calidad');
const ResumenSoldadores = r => require.ensure([], () => r(require('./ResumenSoldadores.vue')), 'calidad');
const ResumenJuntas = r => require.ensure([], () => r(require('./ResumenJuntas.vue')), 'calidad');
const ResumenDatos = r => require.ensure([], () => r(require('./Datos.vue')), 'calidad');
export default
{
    components:
    {
        "resumen-rimes": ResumenRime,
        "resumen-riepc": ResumenRiepc,
        "resumen-soldadores": ResumenSoldadores,
        "resumen-juntas": ResumenJuntas,
        "resumen-datos": ResumenDatos,
    },
    data()
    {
        ResumenJuntas
        return {
            proyecto:
            {},
            proyecto_id: 0,
            tipoAccion: 1,
            list_proyectos: [],
            proyecto_nombre: "",
        };
    },
    methods:
    {
        /**Carga los proyectos de fabricación */
        CargarProyectos()
        {
            axios.get("/calidad/proyectos/proyectos").then(res =>
            {
                this.list_proyectos = res.data.proyectos;
            });
        },

        /**
         * Carga todos los datos para el resumen del proyecto seleccionado
         */
        CargarDatos()
        {
            // Mostrar resumen
            this.tipoAccion = 2;
            this.proyecto_id = this.proyecto.proyecto_id;
            this.proyecto_nombre = ": " + this.proyecto.nombre_proyecto;
        },

        /**
         * Regresa al inicio
         */
        Regresar()
        {
            this.tipoAccion = 1;
            this.proyecto_nombre = "";
        }
    },
    mounted()
    {
        this.CargarProyectos();
    }
};
</script>
