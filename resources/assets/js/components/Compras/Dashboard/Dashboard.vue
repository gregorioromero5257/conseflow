<template>
    <main class="main">
        <div class="container-fluid">
            <vue-element-loading :active="isLoading"/>
            <br>
            <div class="row">
                <div class="col-md-12" v-show="widgets.autorizarequisicion">
                    <autorizarequisicion ref="autorizarequisicion"></autorizarequisicion>
                </div>
            </div>
            <div class="row">
              <div class="col-md-12" v-show="widgets.servicios">
                <servicios ref="servicios"></servicios>
              </div>
            </div>

            <div class="row" v-show="widgets.precios">
              <div class="col-md-12">
                <precios ref="precios"></precios>
              </div>
            </div>

            <div class="row" v-show="widgets.precios2">
              <div class="col-md-12">
                <precios2 ref="precios2"></precios2>
              </div>
            </div>


        </div>
    </main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
const Requisiciones = r => require.ensure([], () => r(require('./Requisiciones.vue')), 'compras');
const Servicios = r => require.ensure([], () => r(require('./Servicios.vue')), 'compras');
// Precios de artículos por requisición
const Precios = r => require.ensure([], () => r(require('./Precios.vue')), 'compras');
// Precios de artículos por salida
const Precios2 = r => require.ensure([], () => r(require('./Precios2.vue')), 'compras');
const modulo_id = 5;

export default {
    data (){
        return {
            isLoading: false,
            listaPermisos: [],
            widgets: {
                autorizarequisicion: true,
                servicios : true,
                precios2:false,
            }
        }
    },
    components: {
        'autorizarequisicion': Requisiciones,
        'servicios' : Servicios,
        'precios':Precios,
        'precios2':Precios2

    },
    methods: {
        getListaPermisos() {
          var id =25;
            this.isLoading = true;
            let me=this;
            axios.post('/permisosdashboard/porusuariomodulo', {
                modulo_id: modulo_id
            }).then(response => {
                me.listaPermisos = response.data;
                me.isLoading = false;

                if (me.listaPermisos.indexOf('autorizarequisicion') >= 0) {
                    me.widgets.autorizarequisicion = true;
                    var childautorizarequisicion = this.$refs.autorizarequisicion;
                    childautorizarequisicion.cargarequisicion(id);
                }
                if (me.listaPermisos.indexOf('servicios') >= 0) {
                    me.widgets.servicios = true;
                    var childservicios = this.$refs.servicios;
                    childservicios.cargarservicios();
                }
                if (me.listaPermisos.indexOf('precios') >= 0) {
                    me.widgets.precios = true;
                    var childprecios = this.$refs.precios;
                    childprecios.ObtenerArticulos(id);
                }
                if (me.listaPermisos.indexOf('precios2') >= 0) {
                    me.widgets.precios2 = true;
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
