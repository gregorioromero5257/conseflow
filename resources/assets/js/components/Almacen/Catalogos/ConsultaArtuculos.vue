<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Consulta articulos
                    </div>
                    <div class="card-body">

                      <v-client-table ref="myTable" :data="tableData"  :columns="columns" :options="options">

                          <template slot="id" slot-scope="props">
                             <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                <div class="btn-group dropup" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                              <template v-if="props.row.condicion">
                                  <button type="button" class="dropdown-item" @click="activarLote(props.row.id, 0)" >
                                      <i class="fas fa-ban"></i>&nbsp; Desactivar
                                  </button>
                              </template>
                              <template v-else>
                                  <button type="button" class="dropdown-item" @click="activarLote(props.row.id, 1)" >
                                      <i class="icon-check"></i>&nbsp; Activar
                                  </button>
                              </template>
                                </div>
                                </div>
                             </div>
                          </template>
                      </v-client-table>
                    </div>
                </div>
                </div>

        </main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

    export default {
        data (){
            return {
                lote: {
                    id: 0,
                    nombre: '',
                    articulo_id: 0,
                    descripcion: '',
                    cantidad: 0,
                    caducidad: null
                },
                dataTable : [],
                tituloForm : 'Registrar lote',
                tipoAccion : 1,
                isLoading: false,
                columns: [ 'nombre', 'codigo','trnom','descripcion','nombreproveedor', 'marca', 'unidad','folio'],
                tableData: [],
                options: {
                    headings: {
                        id: 'Acciones',
                        nombre: 'Lote',
                        condicion: 'Estado',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['nombre', 'codigo','trnom','descripcion','nombreproveedor', 'marca', 'unidad','folio'],
                    filterable: ['nombre', 'codigo','trnom','descripcion','nombreproveedor', 'marca', 'unidad','folio'],
                    filterByColumn: true,

                    texts:config.texts
                },
            }
        },
        computed:{
        },
        methods : {
          getData(){
            axios.get('/consulta/articulos').then(response => {
              this.tableData = response.data;
            }).catch(error => {
              console.error(error);
            });
          }
        },
        components: {
            'bus-articulo': require('../BusArticulo.vue')
        },
        mounted() {
          this.getData();
        }
    }
</script>
