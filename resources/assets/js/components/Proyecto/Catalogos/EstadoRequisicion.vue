<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Estado de Requisicion
                        <button type="button" @click="abrirModal('edorequi','registrar')" class="btn btn-secondary float-sm-right">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">

                        <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                            <template slot="id" slot-scope="props">
                                <button type="button" @click="abrirModal('edorequi','actualizar',props.row)" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                </button>
                            </template>
                        </v-client-table>
                        
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div>
                        <vue-element-loading :active="isLoading"/>
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
                            <input type="hidden" name="id">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="nombre">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required'" name="nombre" v-model="edorequi.nombre" class="form-control" placeholder="Nombre del estado de la requisicion" autocomplete="off" id="nombre">
                                        <span class="text-danger">{{ errors.first('nombre') }}</span>
                                    </div>
                                </div>
                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="guardarTipoentrada(1)">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="guardarTipoentrada(0)">Actualizar</button>
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
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

    export default {
        data (){
            return {
                edorequi: {
                    id: 0,
                    nombre : ''
                },
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                columns: ['id', 'nombre'],
                tableData: [],
                options: {
                    headings: {
                        nombre: 'Nombre',
                        id: 'Acción',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['nombre'],
                    filterable: ['nombre'],
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
                axios.get('/edorequi').then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            guardarTipoentrada(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? '/edorequi/registrar' : '/edorequi/actualizar',
                            data: {
                                'nombre': this.edorequi.nombre,
                                'id': this.edorequi.id
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                if(!nuevo){
                                toastr.success('Estado de Requisicion Actualizado Correctamente');
                                }
                                else
                                {
                                toastr.success('Estado de Requisicion Registrado Correctamente');
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
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                Utilerias.resetObject(this.edorequi);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "edorequi":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar estado de requisicion del Articulo';
                                Utilerias.resetObject(this.edorequi);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar estado de requisicion del Articulo';
                                this.tipoAccion=2;
                                this.edorequi.id=data['id'];
                                this.edorequi.nombre = data['nombre'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.getData();
            this.$root.limpiarDashboard();
        }
    }
</script>