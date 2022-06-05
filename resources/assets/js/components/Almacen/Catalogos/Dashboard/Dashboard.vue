<template>
    <main class="main">
        <div class="container-fluid">
            <vue-element-loading :active="isLoading"/>
            <br>
            <div class="row">
                <div :class="tamanio" v-show="widgets.requisicionesalmacen">
                    <requisicionesalmacen ref="requisicionesalmacen" @requisicionesalmacen:change="escucharHijo">
                    </requisicionesalmacen>
                </div>

            </div>

        </div>
    </main>
</template>
<script>
const Requisiciones = r => require.ensure([], () => r(require('./Requisiciones.vue')), 'alm');
const modulo_id = 2;

export default {
    data (){
        return {
            isLoading: false,
            listaPermisos: [],
            widgets: {
                requisicionesalmacen: true,

            },
            tamanio : 'col-md-6',
        }
    },
    components: {
        'requisicionesalmacen': Requisiciones,

    },
    methods: {
        escucharHijo(){
          if (this.tamanio == 'col-md-6') {
            this.tamanio = 'col-md-10';
          }
          else if (this.tamanio == 'col-md-10') {
            this.tamanio = 'col-md-6';
          }
        },
        getListaPermisos() {
            this.isLoading = true;
            let me=this;
            // axios.post('/permisosdashboard/porusuariomodulo', {
            //     modulo_id: modulo_id
            // }).then(response => {
            //     me.listaPermisos = response.data;
            //     me.isLoading = false;
            //
            //     if (me.listaPermisos.indexOf('requisicionesalmacen') >= 0) {
            //         me.widgets.requisicionesalmacen = true;
            //         var childrequisicionesalmacen = this.$refs.requisicionesalmacen;
            //         childrequisicionesalmacen.cargarcompras();
            //     }
            //
            //
            // })
            // .catch(function (error) {
            //     console.log(error);
            // });
        }
    },
    mounted() {
        this.getListaPermisos();
    }
}
</script>
