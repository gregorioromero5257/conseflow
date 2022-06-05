<template>
    <main class="main">
        <div class="container-fluid">
            <vue-element-loading :active="isLoading"/>
            <br>
            <div class="row">
                <div class="col-md-12" v-show="widgets.compras">
                    <compras ref="compras"></compras>
                </div>
                <div class="col-md-12" v-show="widgets.pagosclientes">
                    <pagosclientes ref="pagosclientes"></pagosclientes>
                </div>
            </div>

        </div>
    </main>
</template>
<script>
const Compras = r => require.ensure([], () => r(require('./Compras.vue')), 'conta');
const PagosClientes = r => require.ensure([], () => r(require('./PagosClientes.vue')), 'conta');
const modulo_id = 8;

export default {
    data (){
        return {
            isLoading: false,
            listaPermisos: [],
            widgets: {
                compras: false,
                pagosclientes :  false,
            }
        }
    },
    components: {
        'compras': Compras,
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

                if (me.listaPermisos.indexOf('compras') >= 0) {
                    me.widgets.compras = true;
                    var childcompras = this.$refs.compras;
                    childcompras.cargarcompras();
                }
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
