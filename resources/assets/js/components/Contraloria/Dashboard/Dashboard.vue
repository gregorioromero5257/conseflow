<template>
    <main class="main">
        <div class="container-fluid">
            <vue-element-loading :active="isLoading"/>
            <br>
            <div class="row">
                <div class="col-md-12" v-show="widgets.autorizarcompra">
                    <autorizarcompra ref="autorizarcompra"></autorizarcompra>
                </div>
                <div class="col-md-12">
                    <facturasProveedor></facturasProveedor>
                </div>
                <div class="col-md-12">
                    <documentosproveedor></documentosproveedor>
                </div>
            </div>
        </div>
    </main>
</template>
<script>
const Compras = r => require.ensure([], () => r(require('./Compras.vue')), 'contra');
const FacturasProveedor = r => require.ensure([], () => r(require('../FacturasProveedores/FacturasProveedor.vue')), 'conta');
const DocumentosProveedor = r => require.ensure([], () => r(require('../FacturasProveedores/DocumentosSAT.vue')), 'conta');
const modulo_id = 9;

export default {
    data (){
        return {
            isLoading: false,
            listaPermisos: [],
            widgets: {
                autorizarcompra: false,
            }
        }
    },
    components: {
        'autorizarcompra': Compras,
        'facturasProveedor':FacturasProveedor,
        'documentosproveedor':DocumentosProveedor
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

                if (me.listaPermisos.indexOf('autorizarcompra') >= 0) {
                    me.widgets.autorizarcompra = true;
                    var childautorizarcompra = this.$refs.autorizarcompra;
                    childautorizarcompra.cargarcompras();
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
