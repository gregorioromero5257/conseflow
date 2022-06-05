<template>
    <main class="main">
        <div class="container-fluid">
            <vue-element-loading :active="isLoading"/>
            <br>
            <div class="row">
                        <div class="col-md-10" v-show="widgets.solicitudviaticosrevision">
                    <solicitudviaticosrevision ref="solicitudviaticosrevision" @revision:change="getListaPermisos()"></solicitudviaticosrevision>
                  <br>
                  <br>
                </div>
            </div>
            <div class="row">
                        <div class="col-md-10" v-show="widgets.solicitudviaticosautorizar">
                    <solicitudviaticosautorizar ref="solicitudviaticosautorizar" @autoriza:change="getListaPermisos()"></solicitudviaticosautorizar>
                  <br>
                  <br>
                </div>
            </div>

        </div>
    </main>
</template>
<script>
const SolicitudViaticosRevision = r => require.ensure([], () => r(require('./SolicitudViaticosRevision.vue')), 'via');
const SolicitudViaticosAutorizar = r => require.ensure([], () => r(require('./SolicitudViaticosAutorizar.vue')), 'via');
const modulo_id = 12;

export default {
    data (){
        return {
            isLoading: false,
            listaPermisos: [],
            widgets: {
              solicitudviaticosrevision :  false,
                solicitudviaticosautorizar :  false,
            }
        }
    },
    components: {
      'solicitudviaticosrevision' : SolicitudViaticosRevision,
        'solicitudviaticosautorizar' : SolicitudViaticosAutorizar,
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

                    if (me.listaPermisos.indexOf('solicitudviaticosrevision') >= 0) {
                    me.widgets.solicitudviaticosrevision = true;
                    var childSolicitudViaticos = this.$refs.solicitudviaticosrevision;
                    childSolicitudViaticos.cargarsolicitudviaticos();
                }
                if (me.listaPermisos.indexOf('solicitudviaticosautorizar') >= 0) {
                me.widgets.solicitudviaticosautorizar = true;
                var childSolicitudViaticos = this.$refs.solicitudviaticosautorizar;
                childSolicitudViaticos.cargarsolicitudviaticos();
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
