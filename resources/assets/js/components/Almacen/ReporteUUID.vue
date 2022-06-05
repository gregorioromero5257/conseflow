<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Reporte UUID <-> UUIDR
                        <!-- <button type="button" @click="abrirModal('banco','registrar')" class="btn btn-dark float-sm-right">
                            <i class="fas fa-plus"></i>&nbsp;Nuevo
                        </button> -->
                    </div>
                    <div class="card-body">
                        <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                        </v-client-table>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <vue-element-loading :active="isLoading"/>
                <div class="modal-dialog modal-dark modal-md" role="document">
                    <div class="modal-content">
                        <div>

                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary"  @click="guardarJunta(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarJunta(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
                        </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        </main>
</template>

<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

    export default {
        data (){
            return {
                url: '/reporte',

                modal : 0,
                listaProyectos: [],
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                columns: ['fecha_entrada','uuid_relacionado','folio'],
                tableData: [],
                options: {
                    headings: {
                        fecha_entrada: 'Fecha de Entrada',
                        uuid_relacionado: 'UUID Relacionado',
                        folio : 'Orden de Compra',
                    },
                    groupBy:['uuid'],
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: [],
                    filterable: [],
                    filterByColumn: true,
                    texts:config.texts
                },
            }
        },
        computed:{
        },
        methods : {
            getData() {
                let me=this;
                axios.get(me.url).then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },

            cerrarModal(){
                this.modal=0;
                this.tituloModal='';

            },


        },
        mounted() {
            this.getData();
        }
    }
</script>
