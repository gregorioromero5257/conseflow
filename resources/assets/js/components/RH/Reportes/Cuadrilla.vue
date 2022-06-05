<template>
    <main class="main">
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <!-- <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Cuadrilla de trabajo.
                    <button type="button" @click="abrirModal('tipo-control','registrar')"
                            class="btn btn-dark float-sm-right">
                        <i class="fas fa-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
            </div> -->

            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i>Reporte de cuadrilla.
                    <button type="button" @click="abrirModal('tipo-control','registrar')"
                            class="btn btn-dark float-sm-right">
                        <i class="fas fa-plus"></i>&nbsp;Nuevo
                    </button>
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

                                <div class="row">
                                <div class="form-group col-lg-6">
                                    <label>Fecha de actividad:</label>
                                    <input type="date" name="fecha_inicial" id="fecha-inicial" class="form-control"  v-validate="'required'"
                                           v-model="cuadrilla.fechaInicial" data-vv-as="Fecha inicial">
                                    <span class="text-danger"></span>
                                </div>
                                  </div>

                                  <div class="row">
                                  <div class="form-group col-lg-6">
                                    <label>Proyecto:</label>
                                    <select class="form-control custom-select" id="proyecto_id" name="proyecto_id"
                                            v-model="cuadrilla.proyecto_id" v-validate="'required'"
                                            data-vv-as="Proyecto">
                                        <option value="0">---TODOS---</option>
                                        <option v-for="item in listaProyectos" :value="item.proyecto.id"
                                                :key="item.proyecto.id">{{ item.proyecto.nombre_corto }}
                                        </option>
                                    </select>
                                    <span class="text-danger">{{ errors.first('proyecto_id') }}</span>
                                </div>
                                </div>



                                <div class="row">

                                    <div class="form-group col-lg-6">
                                    <label>Supervisor:</label>
                                        <v-select  v-model="cuadrilla.supervisor_id"  :options="vs_options" label="name" name="asigna" data-vv-as="asigna"></v-select><!---->
                                        <span class="text-danger">{{ errors.first('asigna') }}</span>
                                   </div>

                                    <div class="form-group col-lg-6">
                                    <label>Empleado asignado:</label>
                                        <v-select multiple v-model="cuadrilla.empleado_asignado_id" :options="vs_options" label="name" name="empleado_asignado" data-vv-as="empleado_asignado"></v-select>
                                        <span class="text-danger">{{ errors.first('recibe') }}</span>
                                    </div>
                                </div>
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
                url: '/cuadrillaresource',

                cuadrilla: {
                    fechaInicial: '',
                    proyecto_id: 0,
                    supervisor_id: '',
                    empleado_asignado_id: '',

                },
                vs_options : [],
                deshabilitar: 0,
                id: 0,
                listaProyectos: [],
                listaEmpleados: [],
                modal: 0,
                tituloModal: '',
                tipoAccion: 0,
                isLoading: false,
                columns: ['fecha','asignado_nombre','supervisor_nombre', 'puesto'],
                tableData: [],
                options: {
                    headings: {
                        asignado_nombre: 'Supervisor:',
                        supervisor_nombre: 'Asignado:',
                        puesto: 'Puesto'

                    },
                    groupBy: ['nombre_proyecto'],
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['supervisor_nombre','asinado_nombre'],
                    filterable: ['supervisor_nombre','asignado_nombre'],
                    filterByColumn: true,
                    texts: config.texts
                },
            }
        },

        methods: {

            getData() {
                let me = this;
                axios.get('/listacuadrilla').then(response => {
                    me.tableData = response.data;
                }).catch(function (error) {
                    console.log(error);
                });

                axios.get('/proyecto').then(response => {
                    me.listaProyectos = response.data;
                })
                    .catch(function (error) {
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

                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? me.url : me.url + '/' + this.id,
                            data: {
                                'fecha': this.cuadrilla.fechaInicial,
                                'proyecto_id': this.cuadrilla.proyecto_id,
                                'supervisor_id': this.cuadrilla.supervisor_id,
                                'empleado_asignado_id': this.cuadrilla.empleado_asignado_id,

                            }
                        }).then(function (response) {

                            if (response.data.status === true) {
                                me.cerrarModal();
                                me.getData();
                                if (!nuevo) {
                                    toastr.success(' Actualizada Correctamente');
                                } else {
                                    toastr.success('Registrada Correctamente');
                                }
                            }
                               else
                            {
                                if (response.data.error === true && response.data.status === false) {
                                    toastr.error(' Verifica datos registrados \n empleado o fecha duplicada');
                                } else {
                                    swal({
                                        type: 'error',
                                        html: response.data.errors.join('<br>'),
                                    });
                                }
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
                                Utilerias.resetObject(this.controlt);
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
                Utilerias.resetObject(this.cuadrilla);
            },
        },

        mounted() {
            this.getData();
        }
    }
</script>
