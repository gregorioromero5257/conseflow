<template>
    <main class="main">
        <div class="container-fluid">
            <vue-element-loading :active="isLoading"/>
            <br>
            <div class="row">
                <div class="col-md-6" v-show="widgets.permisos">
                    <permisos ref="permisos"></permisos>
                </div>
                <div class="col-md-6" v-show="widgets.solicitudes_vacaciones">
                    <solicitues-vacacionales ref="vacaciones"></solicitues-vacacionales>
                </div>
                <div class="col-md-6" v-show="widgets.contratos">
                    <contratos ref="contratos"></contratos>
                </div>
                <div class="col-md-6" v-show="widgets.econvacaciones">
                    <econvacaciones ref="econvacaciones"></econvacaciones>
                </div>
                <div class="col-md-12" v-show="widgets.ver_resguardo">
                    <resguardos ref="resguardos"></resguardos>
                </div>
            </div>
        </div>
    </main>
</template>
<script>
const SolicitudesVacacionales = r => require.ensure([], () => r(require('./SolicitudesVacacionales.vue')), 'rh');
const Permisos = r => require.ensure([], () => r(require('./Permisos.vue')), 'rh');
const Contratos = r => require.ensure([], () => r(require('./Contratos.vue')), 'rh');
const EconVacaciones = r => require.ensure([], () => r(require('./EconVacaciones.vue')), 'rh');
const Resguardo = r => require.ensure([], () => r(require('../Empleados/Resguardos.vue')), 'rh');
const modulo_id = 4;

export default {
    data (){
        return {
            isLoading: false,
            listaPermisos: [],
            widgets: {
                solicitudes_vacaciones: false,
                permisos: false,
                contratos : false,
                econvacaciones : false,
                ver_resguardo:false,
            }
        }
    },
    components: {
        'solicitues-vacacionales': SolicitudesVacacionales,
        'permisos': Permisos,
        'contratos' : Contratos,
        'econvacaciones' : EconVacaciones,
        'resguardos' : Resguardo,
    },
    methods: {
        getListaPermisos() {
            this.isLoading = true;
            let me=this;
            axios.post('/permisosdashboard/porusuariomodulo', {
                modulo_id: modulo_id
            }).then(response => {
                me.listaPermisos = response.data;
                me.isLoading = false;

                if (me.listaPermisos.indexOf('solicitudes-vacaciones') >= 0) {
                    me.widgets.solicitudes_vacaciones = true;
                    var childVacaciones = this.$refs.vacaciones;
                    childVacaciones.cargarSolicitudes();
                }

                if (me.listaPermisos.indexOf('permisos') >= 0) {
                    me.widgets.permisos = true;
                    var childPermisos = this.$refs.permisos;
                    childPermisos.cargarPermisos();
                }

                if (me.listaPermisos.indexOf('contratos') >= 0) {
                    me.widgets.contratos = true;
                    var childContratos = this.$refs.contratos;
                    childContratos.cargarContratos();
                }

                if (me.listaPermisos.indexOf('econvacaciones') >= 0) {
                    me.widgets.econvacaciones = true;
                    var childEconVacaciones = this.$refs.econvacaciones;
                    childEconVacaciones.cargarContratos();
                }
                
                if (me.listaPermisos.indexOf('resguardos') >= 0) {
                    me.widgets.ver_resguardo=true;
                }

            })
            .catch(function (error) {
                console.log(error);
            });
        }
    },
    mounted() {
        this.getListaPermisos();
    }
}
</script>
