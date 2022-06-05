<template>
    <div class="card border-dark">
        <div class="card-header bg-dark text-white">
            <i class="fa fa-align-justify"> </i> Solitud de Epp
        </div>
        <!-- <div class="card-body"> -->
        <vue-element-loading :active="isLoading"/>
        <v-client-table :data="dataTable" :options="options" :columns="columns" >
            <template slot="id" slot-scope="props">
                <button class="btn btn-sm btn-success" @click="autorizar(1, props.row, props.row.id)"><i class="far fa-check-square"></i>&nbsp; Si&nbsp;&nbsp;</button>
                <button class="btn btn-sm btn-danger" @click="autorizar(0, 0, props.row.id)"><i class="fas fa-window-close"></i>&nbsp; No</button>
            </template>
        </v-client-table>

        <!--Inicio del modal Mostrar Lote-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dark modal-lg" role="document">
                <div class="modal-content">

                    <div>
                        <!-- <vue-element-loading :active="isLoading"/> -->
                        <div class="modal-header">
                            <h4 class="modal-title"> Registrar articulo</h4>
                            <button type="button" class="close" @click="cerraModalQuitar()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-row">
                                <div class="col-md-12 mb-3">
                                    <b>Articulo : </b> {{data_detalle_partida == '' ? '' : data_detalle_partida.descripcion}}
                                </div>
                            </div>
                            <v-client-table :data="data" :columns="columnsPartidas" :options="optionsPartidas" @row-click="seleccionarArticulo">
                            </v-client-table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="actualizar();">Actualizar</button>
                            <button type="button" class="btn btn-outline-dark" @click="cerraModal()">Cerrar</button>
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal agregar almacen-->

        <!-- </div> -->
    </div>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
    data() {
        return {
            modal : 0,
            isLoading: false,
            registros: [],
            data_detalle_partida : '',

            dataTable : [],
            columns : ['id','descripcion','cantidad','fecha','nombre_solicita','nombre_autoriza'],
            options :{
                headings: {
                    'id' : 'Acciones',
                },
                perPage: 15,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts:config.texts
            },

            data : [],
            columnsPartidas : ['descripcion','cantidad','nombre_corto'],
            optionsPartidas :{
                headings: {
                    'id' : 'Acciones',
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts:config.texts
            }
        }
    },
    methods: {
        getData() {
            this.isLoading = true;
            let me=this;
            axios.get('/get/partidas/epp/almacen').then(response => {
                me.dataTable = response.data;
                me.isLoading = false;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        cargarMinimos() {
            this.getData();
        },

        autorizar(edo, data, id){
            this.data_detalle_partida = data;
            swal({
                title: edo == 1  ? 'Esta seguro(a) autorizar la entrega?' : 'Esta seguro(a) de rechazar la entrega',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4dbd74',
                cancelButtonColor: '#f86c6b',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then(result => {
                if (result.value) {///
                    if (edo == 1) {
                        axios.post('get/partida/vale/epp/asignacion',{
                            desc : data.descripcion,
                        }).then(response =>{
                            this.data = response.data;
                        }).catch(e => {
                            console.error(e);
                        });
                        this.modal = 1;
                    }
                }
            });
        },

        cerraModal(){
            this.modal = 0;
        },

        seleccionarArticulo(data){
            console.log(data);
            Swal.mixin({
                input: 'text',
                confirmButtonText: 'Guardar &rarr;',
                showCancelButton: true,
                progressSteps: ['1']
            }).queue([
                {
                    title: 'Cantidad',
                    text: data.row.descripcion,
                    inputValue : this.data_detalle_partida.cantidad,
                }
            ]).then((result) => {
                if (result.value) {
                    if (result.value[0] > parseFloat(data.row.cantidad)) {
                        toastr.warning('No se puede seleccionar una cantidad mayor a la existente!!');
                    }else {
                        console.log(result.value[0]);
                        axios.post('guardar/asignacion/a')
                    }
                    console.log(this.data_detalle_partida);
                }
            })
        },
    },
    mounted() {
        // this.getData();
    }
}
</script>
