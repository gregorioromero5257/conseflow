<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>Directorio Telefónico
                        <button type="button" @click="abrirModal('directorio','registrar')" class="btn btn-dark float-sm-right">
                            <i class="fas fa-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <vue-element-loading :active="isLoading"/>
                        <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                            <template slot="id" slot-scope="props">
                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                <div class="btn-group dropup" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-grip-horizontal"></i> Acciones
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1"> 
                                <button type="button" @click="abrirModal('directorio','actualizar',props.row)" class="dropdown-item">
                                    <i class="icon-pencil"></i>&nbsp; Actualizar Datos
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
                                    <label class="col-md-3 form-control-label" for="numero_telefonico">Número de Teléfono</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required|max:15'" name="numero_telefonico" v-model="directorio.numero_telefonico" class="form-control" placeholder="Número Telefónico" autocomplete="off" id="numero_telefonico">
                                        <span class="text-danger">{{ errors.first('numero_telefonico') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="numero_telefonico">Proveedor de Servício</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required|max:45'" name="proveedor_telefonia" v-model="directorio.proveedor_telefonia" class="form-control" placeholder="Proveedor" autocomplete="off" id="proveedor_telefonia">
                                        <span class="text-danger">{{ errors.first('proveedor_telefonia') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="ubicacion">Ubicación</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required|max:45'" name="ubicacion" v-model="directorio.ubicacion" class="form-control" placeholder="Ubicación" autocomplete="off" id="ubicacion">
                                        <span class="text-danger">{{ errors.first('ubicacion') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="comentario">Comentarios</label>
                                    <div class="col-md-9">
                                        <input type="text"  name="comentario" v-model="directorio.comentario" class="form-control" placeholder="Comentarios" autocomplete="off" id="comentario">
                                        <span class="text-danger">{{ errors.first('comentario') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="empleado_encargado_id">Encargado</label>
                                    <div class="col-md-9">
                                        <select class="form-control" id="empleado_encargado_id" name="empleado_encargado_id" v-model="directorio.empleado_encargado_id" v-validate="'excluded:0'" data-vv-as="Solicita">
                                        <option v-for="item in listaEmpleados" :value="item.empleado.id" :key="item.empleado.id">{{ item.empleado.nombre }} {{ item.empleado.ap_paterno }} {{ item.empleado.ap_materno }}</option>
                                        </select>
                                        <span class="text-danger">{{ errors.first('empleado_encargado_id') }}</span>
                                    </div>
                                </div>
                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarDirectorio(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarDirectorio(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
                url: '/directoriotelefonico',
                directorio: {
                    id: 0,
                    numero_telefonico : '',
                    proveedor_telefonia: '',
                    ubicacion: '',
                    comentario: '',
                    empleado_encargado_id:0
                },
                listaEmpleados: [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                columns: ['id', 'numero_telefonico', 'proveedor_telefonia', 'ubicacion', 'comentario', 'nombrec'],
                tableData: [],
                options: {
                    headings: {
                        nombrec: 'Encargado',
                        comentario: 'Comentarios',
                        ubicacion: 'Ubicación',
                        proveedor_telefonia: 'Compañia',
                        numero_telefonico: 'Número de Teléfono',
                        id: 'Acciones',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['numero_telefonico', 'ubicacion', 'nombrec', 'proveedor_telefonia'],
                    filterable: ['numero_telefonico', 'ubicacion', 'nombrec', 'proveedor_telefonia'],
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

                axios.get('/empleado').then(response => {
                    me.listaEmpleados = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            guardarDirectorio(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                          method: nuevo ? 'POST' : 'PUT',
                          url: nuevo ? me.url : me.url+'/'+this.id,
                            data: {
                                'empleado_encargado_id': this.directorio.empleado_encargado_id,
                                'comentario': this.directorio.comentario,
                                'ubicacion': this.directorio.ubicacion,
                                'proveedor_telefonia': this.directorio.proveedor_telefonia,
                                'numero_telefonico': this.directorio.numero_telefonico,
                                'id': this.directorio.id
                            }
                        }).then(function (response) {
                        if (response.data.status) {
                            me.cerrarModal();
                            me.getData();
                            if(!nuevo){
                                toastr.success('Datos Actualizados Correctamente');
                            }
                            else
                            {
                                toastr.success('Datos Registrados Correctamente');
                            }
                            me.isLoading = false;
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
                Utilerias.resetObject(this.directorio);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "directorio":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Datos';
                                Utilerias.resetObject(this.directorio);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar Datos';
                                this.tipoAccion=2;
                                this.directorio.id=data['id'];
                                this.directorio.numero_telefonico = data['numero_telefonico'];
                                this.directorio.proveedor_telefonia = data['proveedor_telefonia'];
                                this.directorio.ubicacion = data['ubicacion'];
                                this.directorio.comentario = data['comentario'];
                                this.directorio.empleado_encargado_id = data['empleado_encargado_id'];
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
