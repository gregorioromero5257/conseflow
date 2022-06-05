<template>
    <main class="main">
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <br>
        <div class="card" v-show="!detalle">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Proyectos
            </div>
            <div class="card-body">
                <v-client-table ref="myTable" :columns="columns" :data="tableData" :options="options">
                  <template slot="proyecto.condicion" slot-scope="props" >
                      <template v-if="props.row.proyecto.condicion == 1">
                       <button type="button" class="btn btn-outline-success">Activo</button>
                      </template>
                      <template v-if="props.row.proyecto.condicion == 0">
                      <button type="button" class="btn btn-outline-danger">Terminado</button>
                      </template>
                      <template v-if="props.row.proyecto.condicion == 2">
                      <button type="button" class="btn btn-outline-warning">Pausa</button>
                      </template>
                      <template v-if="props.row.proyecto.condicion == 3">
                      <button type="button" class="btn btn-outline-dark">Rechazado</button>
                      </template>
                  </template>
                    <template slot="proyecto.id" slot-scope="props">
                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <div class="btn-group dropup" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <button type="button" @click.prevent="partidas(props.row)" class="dropdown-item">
                            <i class="icon-eye"></i>&nbsp; Ver Detalles
                        </button>
                    </div>
                    </div>
                    </div>

                    </template>
                </v-client-table>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
        <!-- Partidas por proyecto -->
        <div class="card" v-show="detalle">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> {{ nombre }}
                <!-- <button v-show="PermisosCrud.Create" type="button" @click="nuevo()" class="btn btn-dark float-sm-right">
                    <i class="fas fa-plus"></i>&nbsp;Nuevo
                </button> -->
                <button v-show="PermisosCrud.Create" type="button" @click="nuevo_nodos()" class="btn btn-dark float-sm-right">
                    <i class="fas fa-plus"></i>&nbsp;Nuevo
                </button>
                <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
                    <i class="fas fa-arrow-left"></i>&nbsp;Atras
                </button>
            </div>
            <div class="card-body">
                <!-- <partidas ref="partidas"></partidas> -->
                <partidasnodo ref="partidasnodo"></partidasnodo>
            </div>
        </div>
        <!-- Fin Partidas por proyecto -->
    </div>
    </main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
// const Partidas = r => require.ensure([], () => r(require('./Partidas.vue')), 'cos');
const PartidasNodo = r => require.ensure([], () => r(require('./PartidasNodo.vue')), 'cos');

    export default {
        data (){
            return {
                PermisosCrud : {},
                url: '/proyecto',
                nombre : 'del proyecto',
                detalle: false,
                columns: [
                    'proyecto.id',
                    // 'proyecto.nombre',
                    'proyecto.nombre_corto',
                    'proyecto.clave',
                    'proyecto.ciudad',
                    'proyecto.fecha_inicio',
                    'proyecto.fecha_fin',
                    'proyecto.condicion',
                    'proyecto.updated_at'
                ],
                tableData: [],
                options: {
                    headings: {
                        'proyecto.id': 'Acciones',
                        // 'proyecto.nombre': 'Nombre',
                        'proyecto.nombre_corto': 'Nombre corto',
                        'proyecto.clave': 'Clave',
                        'proyecto.ciudad': 'Ciudad',
                        'proyecto.fecha_inicio': 'F. Inicio',
                        'proyecto.fecha_fin': 'F. Fin',
                        'proyecto.condicion':'Condición',
                        'proyecto.updated_at' : 'Ultima Actualización',
                    },
                    perPage: 5,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: [
                        'proyecto.nombre',
                        'proyecto.nombre_corto',
                        'proyecto.clave',
                        'proyecto.ciudad',
                        'proyecto.fecha_inicio',
                        'proyecto.fecha_fin',
                        'proyecto.condicion',
                        'proyecto.updated_at'
                        ],
                    filterable: [
                        'proyecto.nombre',
                        'proyecto.nombre_corto',
                        'proyecto.clave',
                        'proyecto.ciudad',
                        'proyecto.fecha_inicio',
                        'proyecto.fecha_fin',
                        'proyecto.condicion',
                    ],
                    filterByColumn: true,
                    listColumns: {
                      'proyecto.condicion': [{
                          id: 1,
                          text: 'Activo'
                      },
                      {
                          id: 0,
                          text: 'Terminado'
                      },
                      {
                          id: 2,
                          text: 'Pausa'
                      },
                      {
                          id: 3,
                          text: 'Rechazado'
                      }
                      ]
                    },
                    texts:config.texts
                },
            }
        },
        components: {
            // 'partidas': Partidas,
            'partidasnodo' : PartidasNodo,
        },
        methods : {
            listar(){
                let me=this;
                this.PermisosCrud = Utilerias.getCRUD(this.$route.path);
                axios.get(me.url).then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            partidas(data) {
                this.detalle = true;
                this.nombre = data.proyecto.nombre;
                // var childPartidas = this.$refs.partidas;
                // childPartidas.cargarPartidas(data.proyecto.id);
                var chilPartidasNodo = this.$refs.partidasnodo;
                chilPartidasNodo.cargarPartidas(data.proyecto.id);

            },
            maestro() {
                this.detalle = false;
            },
            nuevo() {
                // var childPartidas = this.$refs.partidas;
                // childPartidas.agregar();
            },
            nuevo_nodos(){
              var chilPartidasNodo = this.$refs.partidasnodo;
              chilPartidasNodo.agregar();
            }
        },
        mounted() {
            this.listar();
        }
    }
</script>
