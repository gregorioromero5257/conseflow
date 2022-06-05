<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Cat. Productos y servicios
                        <button type="button" @click="abrirModal('productoServicio','registrar')" class="btn btn-dark float-sm-right">
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
                                <button type="button" @click="abrirModal('productoServicio','actualizar',props.row)" class="dropdown-item" >
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
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="clave">Clave</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required|length:8'" name="clave" v-model="productoServicio.clave" class="form-control" placeholder="Clave" autocomplete="off" id="clave">
                                        <span class="text-danger">{{ errors.first('clave') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="descripcion">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required'" name="descripcion" v-model="productoServicio.descripcion" class="form-control" placeholder="Descripción" autocomplete="off" id="descripcion">
                                        <span class="text-danger">{{ errors.first('descripcion') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="fecha_ini_vig">Fecha inicio de vig.</label>
                                    <div class="col-md-9">
                                        <input type="date" v-validate="''" name="fecha_ini_vig" v-model="productoServicio.fecha_ini_vig" class="form-control" placeholder="Fecha inicio de vig." autocomplete="off" id="fecha_ini_vig">
                                        <span class="text-danger">{{ errors.first('fecha_ini_vig') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="fecha_fin_vig">Fecha fin de vig.</label>
                                    <div class="col-md-9">
                                        <input type="date" v-validate="''" name="fecha_fin_vig" v-model="productoServicio.fecha_fin_vig" class="form-control" placeholder="Fecha fin de vig." autocomplete="off" id="fecha_fin_vig">
                                        <span class="text-danger">{{ errors.first('fecha_fin_vig') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="incluir_iva_tras">Incluir IVA Tras.</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="''" name="incluir_iva_tras" v-model="productoServicio.incluir_iva_tras" class="form-control" placeholder="Incluir IVA Tras." autocomplete="off" id="incluir_iva_tras">
                                        <span class="text-danger">{{ errors.first('incluir_iva_tras') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="incluir_ieps_tras">Incluir IEPS Tras.</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="''" name="incluir_ieps_tras" v-model="productoServicio.incluir_ieps_tras" class="form-control" placeholder="Incluir IEPS Tras." autocomplete="off" id="incluir_ieps_tras">
                                        <span class="text-danger">{{ errors.first('incluir_ieps_tras') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="complemento">Complemento</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="''" name="complemento" v-model="productoServicio.complemento" class="form-control" placeholder="Complemento" autocomplete="off" id="complemento">
                                        <span class="text-danger">{{ errors.first('complemento') }}</span>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarProductoServicio(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarProductoServicio(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
                productoServicio: {
                    id: 0,
                    clave : '',
                    descripcion: '',
                    fecha_ini_vig: '',
                    fecha_fin_vig: '',
                    incluir_iva_tras: '',
                    incluir_ieps_tras: '',
                    complemento: ''
                },
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                columns: ['id', 'clave', 'descripcion'],
                tableData: [],
                options: {
                    headings: {
                        clave: 'Clave',
                        descripcion: 'Descripción',
                        id: 'Acciones',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['clave','descripcion'],
                    filterable: ['clave','descripcion'],
                    filterByColumn: true,
                    listColumns: { },
                    texts:config.texts
                },
            }
        },
        computed:{
        },
        methods : {
            getData() {
                let me=this;
                axios.get('/satcatprodser').then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             guardarProductoServicio(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? '/satcatprodser/registrar' : '/satcatprodser/actualizar',
                            data: {
                                'clave': this.productoServicio.clave,
                                'descripcion': this.productoServicio.descripcion,
                                'id': this.productoServicio.id,
                                'fecha_ini_vig': this.productoServicio.fecha_ini_vig,
                                'fecha_fin_vig': this.productoServicio.fecha_fin_vig,
                                'incluir_iva_tras': this.productoServicio.incluir_iva_tras,
                                'incluir_ieps_tras': this.productoServicio.incluir_ieps_tras,
                                'complemento': this.productoServicio.complemento,
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                if(!nuevo){
                                toastr.success('Producto/Servicio Actualizada Correctamente');
                                }
                                else
                                {
                                toastr.success('Producto/Servicio Registrada Correctamente');

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
                Utilerias.resetObject(this.productoServicio);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "productoServicio":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Producto/Servicio';
                                Utilerias.resetObject(this.productoServicio);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar Producto/Servicio';
                                this.tipoAccion=2;
                                this.productoServicio.id=data['id'];
                                this.productoServicio.clave = data['clave'];
                                this.productoServicio.descripcion = data['descripcion'];
                                this.productoServicio.fecha_ini_vig = data['fecha_ini_vig'];
                                this.productoServicio.fecha_fin_vig = data['fecha_fin_vig'];
                                this.productoServicio.incluir_iva_tras = data['incluir_iva_tras'];
                                this.productoServicio.incluir_ieps_tras = data['incluir_ieps_tras'];
                                this.productoServicio.complemento = data['complemento'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.getData();
        }
    }
</script>