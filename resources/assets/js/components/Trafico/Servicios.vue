<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card" v-show="!detalle">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Servicios 
                    </div>
                    <div class="card-body">
                        <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                            <template slot="id" slot-scope="props">
                              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                               <div class="btn-group dropup" role="group">
                                 <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                     <i class="fas fa-grip-horizontal"></i> Acciones
                                 </button>
                                 <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <button v-show="PermisosCrud.Read" type="button" @click.prevent="servicios(props.row)" class="dropdown-item">
                                    <i class="icon-pencil"></i>&nbsp;Servicios.
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
                        <button type="button" @click="nuevo()" class="btn btn-dark float-sm-right">
                            <i class="fas fa-plus"></i>&nbsp;Nuevo
                        </button>
                        <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
                            <i class="fas fa-arrow-left"></i>&nbsp;Atras
                        </button>
                    </div>
                    <div class="card-body">
                        <unidadservicios ref="unidadservicios"></unidadservicios>
                    </div>
                </div>
                <!-- Fin Partidas por proyecto -->
            </div>

        </main>
</template>

<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);
const unidadservicios = r => require.ensure([], () => r(require('./UnidadServicios.vue')), 'trafico');

    export default {
        data (){
            return {
                PermisosCrud : {},
                url: '/UnidadesStore',
                nombre : '',
                detalle: false,

                Unidades: {
                    id: 0,
                    unidad: '',
                    modelo : '',
                    placas: '',
                    ultimo_servicio: '',
                    poliza: '',
                    tarjeta_circulacion: '',
                    verificacion: '',
                    historial_servicio: '',
                },
                Metodo: '',
                
                isLoading: false,
                columns: ['id', 'unidad', 'modelo', 'placas', 'ultimo_servicio'],
                tableData: [],
                options: {
                    headings: {
                        ultimo_servicio: 'Ultimo Servicio',
                        placas: 'Placas',
                        modelo: 'Modelo',
                        unidad: 'Unidad',
                        id: 'Opciones',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['modelo', 'placas', 'unidad'],
                    filterable: ['modelo', 'placas', 'unidad'],
                    filterByColumn: true,
                    texts:config.texts
                },
            }
        },
        components: {
            'unidadservicios': unidadservicios,
        },
        methods : {
            getData() {
                
                let me=this;
                this.PermisosCrud = Utilerias.getCRUD(this.$route.path);
                axios.get(me.url).then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            maestro() {
                this.detalle = false;
                this.getData();
            },
            nuevo() {
                var childServiciosUnidad = this.$refs.unidadservicios;
                childServiciosUnidad.agregar();
            },
            servicios(data) {
                console.log(data.id);
                this.detalle = true;
                this.nombre = data.unidad+' '+data.modelo+' '+data.placas;
                var childServiciosUnidad = this.$refs.unidadservicios;
                childServiciosUnidad.cargarServicios(data.id);
            },    
        },
        mounted() {
            this.getData();
        }
    }
</script>
