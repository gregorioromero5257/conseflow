<template>
    <main class="main">
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Asignacion de supervisor y empleado.
                    <button type="button" @click="abrirModal('tipo-control','registrar')"
                            class="btn btn-dark float-sm-right">
                        <i class="fas fa-plus"></i>&nbsp;Nuevo
                    </button>
                </div>


            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Relacion Supervisor / Empleado
                </div>
                <div class="card-body">
                    <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                    </v-client-table>
                </div>
            </div>


            <!-- Fin ejemplo de tabla Listado -->

            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel"
                 style="display: none;" aria-hidden="true">
                <vue-element-loading :active="isLoading"/>
                <div class="modal-dialog modal-dark modal-lg" role="document">
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


                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Supervisor:</label>
                                        <v-select  v-model="supervisor.supervisor_id"  :options="vs_options" label="name" name="asigna" data-vv-as="asigna"></v-select><!---->
                                        <span class="text-danger">{{ errors.first('asigna') }}</span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Empleado asignado:</label>
                                        <v-select multiple v-model="supervisor.empleado_asignado_id" :options="vs_options" label="name" name="empleado_asignado" data-vv-as="empleado_asignado"></v-select>
                                        <span class="text-danger">{{ errors.first('recibe') }}</span>
                                    </div>
                                </div><br>

                                <div class="form-group col-md-6">
                                    <div class="col-3">Fecha de asignación:</div>
                                    <div class="col-5">
                                        <input type="date" name="fecha_asignacion " id="fecha_asignacion " class="form-control"  v-validate="'required'"
                                               v-model="supervisor.fecha_asignacion " data-vv-as="fecha_asignacion ">
                                        <span class="text-danger"></span>
                                    </div>
                                </div>
                                <br>
                                <br>
                            </div>


                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar
                            </button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary"
                                    @click="guardarTipoControl(1)"><i class="fas fa-save"></i>&nbsp;Guardar
                            </button>
                            <!--
                          //  <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarBanco(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
                            -->
                        </div>

                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->

            <!--Fin del modal-->
        </div>
    </main>
</template>

<script>

    import Utilerias from '../../Herramientas/utilerias.js';

    var config = require('../../Herramientas/config-vuetables-client').call(this);

    export default {
        data() {
            return {
                url: '/supervisor',
                supervisor: {
                    supervisor_id: '',
                    empleado_asignado_id: '',
                    fecha_asignacion: '',
                },
                vs_options : [],
                id: 0,
                listaEmpleados: [],
                listaEmpleadoSubordinado: [],
                modal: 0,
                tituloModal: '',
                tipoAccion: 0,
                isLoading: false,
                columns: ['asignado_nombre','fecha_asignacion'],
                tableData: [],
                options: {
                    headings: {

                        'asignado_nombre' : 'Empleado designado para actividad',
                    },
                    groupBy: ['e_nombre'],
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: [],
                    filterable: [],
                    filterByColumn: true,
                    texts: config.texts
                },
            }
        },

        methods: {

            getData() {
                let me = this;
                axios.get('/supervisorAsigna').then(response => {
                    me.tableData = response.data;
                }).catch(function (error) {
                    console.log(error);
                });

                axios.get('/vertodosempleados').then(response => {
                    for (var i = 0; i < response.data.length; i++) {
                        this.vs_options.push({
                            id : response.data[i].id,
                            name : response.data[i].nombre + ' ' + response.data[i].ap_paterno + ' ' + response.data[i].ap_materno,
                        });
                    }
                }).catch(error => {
                    console.error(error);
                });


            },

            guardarTipoControl(nuevo) {
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? me.url : me.url + '/' + this.id,
                            data: {
                                'supervisor_id' : this.supervisor.supervisor_id,
                                'empleado_asignado' : this.supervisor.empleado_asignado_id == '' ? [] : this.supervisor.empleado_asignado_id,
                                'fecha_asignacion ': this.supervisor.fecha_asignacion,

                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                if (!nuevo) {
                                    toastr.success(' Actualizada Correctamente');
                                } else {
                                    toastr.success('Registrada Correctamente');
                                }
                            } else {
                                swal({
                                    type: 'error',
                                    html: response.data.errors.join('<br>'),
                                });
                            }
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }
                });
            },

            abrirModal(modelo, accion) {
                switch (modelo) {
                    case "tipo-control": {
                        switch (accion) {
                            case 'registrar': {
                                Utilerias.resetObject(this.supervisor);
                                this.modal = 1;
                                this.tituloModal = 'Registrar Actividad';
                                this.tipoAccion = 1;
                                break;
                            }
                        }
                    }
                }
            },

            cerrarModal() {
                this.modal = 0;
                this.tituloModal = '';
                Utilerias.resetObject(this.supervisor);
            },
        },
        computed: {

        },

        mounted() {
            this.getData();
        }
    }
</script>
