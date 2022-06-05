<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Calidad del Articulo
                        <button type="button" @click="abrirModal('tipo-calidad','registrar')" class="btn btn-dark float-sm-right">
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
                                <button type="#" @click="abrirModal('tipo-calidad','actualizar',props.row)" class="dropdown-item" >
                                    <i class="icon-pencil"></i>&nbsp; Actualizar
                                </button>
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
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
                            <input type="hidden" name="id">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="nombre">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required|max:45'" name="nombre" v-model="tipocalidad.nombre" class="form-control" placeholder="Nombre de la calidad del Articulo" autocomplete="off" id="nombre">
                                        <span class="text-danger">{{ errors.first('nombre') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="nombre">Descripcion</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required'" name="descripcion" v-model="tipocalidad.descripcion" class="form-control" placeholder="Descripcion de la calidad del Articulo" autocomplete="off" id="descripcion">
                                        <span class="text-danger">{{ errors.first('descripcion') }}</span>
                                    </div>
                                </div>
                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarTipoentrada(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarTipoentrada(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
                tipocalidad: {
                    id: 0,
                    nombre : '',
                    descripcion : ''
                },
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                columns: ['id', 'nombre','descripcion'],
                tableData: [],
                options: {
                    headings: {
                      descripcion:'Descripcion de calidad',
                        nombre: 'Nombre',
                        id: 'Acciones',
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
                axios.get('/tipocalidad').then(response => {
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
                            url: nuevo ? '/tipocalidad/registrar' : '/tipocalidad/actualizar',
                            data: {
                                'descripcion' : this.tipocalidad.descripcion,
                                'nombre': this.tipocalidad.nombre,
                                'id': this.tipocalidad.id
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                if(!nuevo){
                                toastr.success('Calidad del Articulo Actualizado Correctamente');
                                }
                                else
                                {
                                toastr.success('Calidad del Articulo Registrado Correctamente');
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
                Utilerias.resetObject(this.tipocalidad);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "tipo-calidad":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar calidad del Articulo';
                                Utilerias.resetObject(this.tipocalidad);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar calidad del Articulo';
                                this.tipoAccion=2;
                                this.tipocalidad.id=data['id'];
                                this.tipocalidad.nombre = data['nombre'];
                                this.tipocalidad.descripcion = data ['descripcion'];
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
