<template>
    <main class="main">
        <div class="container-fluid">
            <vue-element-loading :active="isLoading"/>
            <br>
            <div class="row">
                        <div class="col-md-6" v-show="widgets.pagosclientes">
                    <pagosclientes ref="pagosclientes"></pagosclientes>
                  <br>
                  <br>
                </div>
            </div>

        </div>
    </main>
</template>
<script>
const PagosClientes = r => require.ensure([], () => r(require('./PagosClientes.vue')), 'venta');
const modulo_id = 11;

export default {
    data (){
        return {
            isLoading: false,
            listaPermisos: [],
            widgets: {
                pagosclientes :  false,
            }
        }
    },
    components: {
        'pagosclientes' : PagosClientes,
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

                    if (me.listaPermisos.indexOf('pagosclientes') >= 0) {
                    me.widgets.pagosclientes = true;
                    var childpagosclientes = this.$refs.pagosclientes;
                    childpagosclientes.cargarpagosclientes();
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
