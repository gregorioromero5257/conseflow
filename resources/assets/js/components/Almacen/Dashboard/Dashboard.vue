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
            <div class="row">
                <div :class="tamanio" v-show="widgets.epp">
                    <epp ref="epp" @epp:change="escucharHijo">
                    </epp>
                </div>

            </div>

            <div class="row">
                <div :class="tamanio" v-show="widgets.requisicionesalmacenpendientes">
                    <requisicionesalmacenpendientes ref="requisicionesalmacenpendientes"
                     @requisicionesalmacenpendientes:change="escucharHijo">
                    </requisicionesalmacenpendientes>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6" v-show="widgets.maximos">
                    <maximos ref="maximos"></maximos>
                </div>
                <div class="col-md-6" v-show="widgets.minimos">
                    <minimos ref="minimos"></minimos>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6" v-show="widgets.proximoscaducar">
                    <proximoscaducar ref="proximoscaducar"></proximoscaducar>
                </div>
                <div class="col-md-6" v-show="widgets.caducados">
                    <caducados ref="caducados"></caducados>
                </div>
            </div>
            <div>
                <docsProveedores></docsProveedores>
            </div>
        </div>
    </main>
</template>
<script>
const Epp = r => require.ensure([], () => r(require('./Epp.vue')), 'alm');
const Maximos = r => require.ensure([], () => r(require('./Maximos.vue')), 'alm');
const Minimos = r => require.ensure([], () => r(require('./Minimos.vue')), 'alm');
const ProximosCaducar = r => require.ensure([], () => r(require('./ProximosCaducar.vue')), 'alm');
const Caducados = r => require.ensure([], () => r(require('./Caducados.vue')), 'alm');
const Requisiciones = r => require.ensure([], () => r(require('./Requisiciones.vue')), 'alm');
const RequisicionesPendientes = r => require.ensure([], () => r(require('./RequisicionesPendientes.vue')), 'alm');
const DocsProveedores = r => require.ensure([], () => r(require('./DocsProvedores.vue')), 'alm');

const modulo_id = 2;

export default {
    data (){
        return {
            isLoading: false,
            listaPermisos: [],
            widgets: {
                maximos : false,
                minimos : false,
                requisicionesalmacen: false,
                requisicionesalmacenpendientes: false,
                proximoscaducar: false,
                caducados: false,
                epp: false
            },
            tamanio : 'col-md-10',
        }
    },
    components: {
        'maximos': Maximos,
        'minimos' : Minimos,
        'requisicionesalmacen': Requisiciones,
        'requisicionesalmacenpendientes': RequisicionesPendientes,
        'proximoscaducar': ProximosCaducar,
        'caducados': Caducados,
        'docsProveedores':DocsProveedores,
        'epp':Epp
    },
    methods: {
      escucharHijo(){
        if (this.tamanio == 'col-md-10') {
          this.tamanio = 'col-md-10';
        }
        else if (this.tamanio == 'col-md-10') {
          this.tamanio = 'col-md-10';
        }
      },
        getListaPermisos() {
            this.isLoading = true;
            let me=this;
            axios.post('/permisosdashboard/porusuariomodulo', {
                modulo_id: modulo_id
            }).then(response => {
                me.listaPermisos = response.data;
                me.isLoading = false;

                if (me.listaPermisos.indexOf('inventario-maximos') >= 0) {
                    me.widgets.maximos = true;
                    var childMaximos = this.$refs.maximos;
                    childMaximos.cargarMaximos();
                }

                if (me.listaPermisos.indexOf('inventario-minimos') >= 0) {
                    me.widgets.minimos = true;
                    var childMinimos = this.$refs.minimos;
                    childMinimos.cargarMinimos();
                }

                if (me.listaPermisos.indexOf('inventario-proximos-caducar') >= 0) {
                    me.widgets.proximoscaducar = true;
                    var childProximos = this.$refs.proximoscaducar;
                    childProximos.cargarProximosCaducar();
                }

                if (me.listaPermisos.indexOf('inventario-caducados') >= 0) {
                    me.widgets.caducados = true;
                    var childCaducados = this.$refs.caducados;
                    childCaducados.cargarCaducados();
                }

                if (me.listaPermisos.indexOf('requisicionesalmacen') >= 0) {
                      me.widgets.requisicionesalmacen = true;
                      var childrequisicionesalmacen = this.$refs.requisicionesalmacen;
                      childrequisicionesalmacen.cargarcompras();
                  }

                  if (me.listaPermisos.indexOf('requisicionesalmacenpendientes') >= 0) {
                        me.widgets.requisicionesalmacenpendientes = true;
                        var childrequisicionesalmacenpendientes = this.$refs.requisicionesalmacenpendientes;
                        childrequisicionesalmacenpendientes.cargarcompras();
                    }

                    if (me.listaPermisos.indexOf('epp') >= 0) {
                          me.widgets.epp = true;
                          var childepp = this.$refs.epp;
                          childepp.getData();
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
