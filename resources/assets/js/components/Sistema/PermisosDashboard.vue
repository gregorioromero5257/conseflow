<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Permisos Dashboard
                        <button type="button" @click="abrirModal('permiso','registrar')" class="btn btn-dark float-sm-right">
                            <i class="fas fa-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">

                        <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                            <template slot="id" slot-scope="props">
                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                <div class="btn-group dropup" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                                    </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">   
                                <template>
                                <button type="button" class="dropdown-item" @click="eliminar(props.row.id)" href="#">
                                    <i class="icon-trash"></i>&nbsp; Eliminar Permiso Dashboard
                                </button>
                                </template>
                                </div>
                                </div>
                                </div>
                            </template>
                        </v-client-table>

                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <vue-element-loading :active="isLoading"/>
                <div class="modal-dialog modal-dark modal-lg" role="document">
                    <div class="modal-content">
                        <div>
                        
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true"> ×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal"> -->
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="elemento_dashboard_id">Modulo-Elemento</label>
                                    <div class="col-md-9">
                                        <select class="form-control" id="elemento_dashboard_id"  name="elemento_dashboard_id" v-model="permiso.elemento_dashboard_id" v-validate="'excluded:0'" data-vv-as="Elemento Dashboard">
                                            <option v-for="item in listaElementos" :value="item.id" :key="item.id">{{ item.modulo }} - {{ item.nombre }}</option>
                                        </select>
                                        <span class="text-danger">{{ errors.first('elemento_dashboard_id') }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="user_id">Usuario</label>
                                    <div class="col-md-9">
                                        <select class="form-control" id="user_id"  name="user_id" v-model="permiso.user_id" v-validate="'excluded:0'" data-vv-as="Usuario">
                                            <option v-for="item in listaUsuarios" :value="item.usuario.id" :key="item.usuario.id">{{ item.usuario.name }}</option>
                                        </select>
                                        <span class="text-danger">{{ errors.first('user_id') }}</span>
                                    </div>
                                </div>

                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarPermiso()"><i class="fas fa-save"></i>&nbsp;Guardar</button>
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
                permiso: {
                    id: 0,
                    elemento_dashboard_id: 0,
                    user_id : 0,
                },
                listaElementos: [],
                listaUsuarios: [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                columns: ['id', 'modulo', 'elemento', 'usuario'],
                tableData: [],
                options: {
                    headings: {
                        elemento: 'Elemento',
                        modulo: 'Modulo',
                        usuario: 'Usuario',
                        id: 'Acción',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['elemento', 'usuario', 'modulo'],
                    filterable: ['elemento', 'usuario', 'modulo'],
                    filterByColumn: true,
                    // listColumns: {
                    //     condicion: config.columnCondicion
                    // },
                    texts:config.texts
                },
            }
        },
        computed:{
        },
        methods : {
            getData() {
                let me=this;
                axios.get('/permisosdashboard').then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getListaElementosDashboard() {
                let me=this;
                axios.get('/elementosdashboard').then(response => {
                    me.listaElementos = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getListaUsuarios() {
                let me=this;
                axios.get('/usuario').then(response => {
                    me.listaUsuarios = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            guardarPermiso(){
                this.$validator.validate().then(result => {
                    if (result) {
                        console.log(this.permiso.elemento_dashboard_id);
                        console.log(this.permiso.user_id);
                        console.log(this.permiso.id);
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: 'POST',
                            url: '/permisosdashboard/registrar',
                            data: {
                                'elemento_dashboard_id': this.permiso.elemento_dashboard_id,
                                'user_id': this.permiso.user_id,
                                'id': this.permiso.id
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                toastr.success('Permiso Registrado Correctamente');
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
            eliminar(id){
               swal({
                title: 'Esta seguro de eliminar este Permiso?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4dbd74',
                cancelButtonColor: '#f86c6b',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/permisosdashboard/eliminar',{
                        'id': id
                    }).then(function (response) {
                        toastr.error('Permiso Desactivado Correctamente');
                        me.getData();
                    }).catch(function (error) {
                        console.log(error);
                    });
                }
                })
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                Utilerias.resetObject(this.permiso);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "permiso":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar permiso';
                                Utilerias.resetObject(this.permiso);
                                this.tipoAccion = 1;
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.getData();
            this.getListaElementosDashboard();
            this.getListaUsuarios();
        }
    }
</script>
