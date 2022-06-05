<template>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : mostrar}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dark modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" >Buscar articulo</h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <v-server-table ref="myTable" url="/articulo/busqueda" :columns="columns" :options="options" @row-click="seleccionarArticulo2">

                          <template slot="child_row" slot-scope="props">
                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group" role="group">
                              <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-grip-horizontal"></i>&nbsp;Acciones
                              </button>
                              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                                <button type="button" @click="abrirModal(props.row)" class="dropdown-item" >
                              <i class="icon-pencil"></i>&nbsp;Actualizar Articulo
                            </button>




                              </div>
                            </div>
                            </div>
                          </template>
                        </v-server-table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>

                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
</template>
<script>
var config = require('../Herramientas/config-vuetables-client').call(this);
    export default {
    props: { },
    data () {
        return {
            mostrar: false,
            columns: ['codigo', 'nombre', 'descripcion', 'marca'],
            tableData: [],
            options: {
                headings: {
                    nombre: 'Nombre',
                    descripcion: 'Descripción',
                    marca: 'Marca',
                    codigo: 'Código',
                    id: 'Acción',
                },
                perPage: 5,
                perPageValues: [],
                skin: config.skinBusqueda,
                sortIcon: config.sortIcon,
                sortable: ['nombre', 'codigo', 'marca'],
                filterable: ['nombre', 'codigo','descripcion', 'marca'],
                filterByColumn: true,
                texts:config.texts,
                childRow: true,
            },
        }
    },
    methods: {
        cerrarModal(){
            this.mostrar=false;
        },
        mostrarBusArticulo(){
          this.$refs.myTable.refresh();
            this.mostrar=true;
        },
        seleccionarArticulo(row){
            this.mostrar=false;
            this.$emit('clicked', row );
        },
        seleccionarArticulo2(data)
        {
            this.mostrar=false;
            this.$emit('clicked', data.row );
        },
        cerrarBusArt(){
            this.$emit('cerrarabrir');
          this.mostrar = false;

        },
        abrirModal(data){
          this.$emit('enviar',data);
          this.mostrar = false;

        },
    },
    components: {
    },
    watch: {
        mostrar: function(val, oldVal){
            if (val) {
                setTimeout(function(){
                    var vfEle= global.document.getElementsByName('vf__descripcion')[0];
                    vfEle.focus();
                 }, 500);
            }
        }
    }
  }
</script>
<style >
.VueTables__child-row-toggler--closed::before {
content: "+";
}

.VueTables__child-row-toggler--open::before {
content: "-";
}
</style>
