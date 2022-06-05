<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Extensiones Telefonicas en Departamentos
                        <button type="button" @click="abrirModal('extensions','registrar')" class="btn btn-dark float-sm-right">
                            <i class="fas fa-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">

                        <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                                    <template slot="condicion" slot-scope="props" >
                                <template v-if="props.row.condicion == 1">
                                    <button type="button" class="btn btn-outline-success">Activo</button>
                                </template>
                                <template v-if="props.row.condicion == 0">
                                    <button type="button" class="btn btn-outline-danger">Desactivado</button>
                                </template>
                        </template>

                            <template slot="id" slot-scope="props">
                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group dropup" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-grip-horizontal"></i> Acciones
                            </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <button type="button" @click="abrirModal('extensions','actualizar',props.row)" class="dropdown-item">
                                    <i class="icon-pencil"></i>&nbsp; Actualizar Extension
                                </button>
                                <template v-if="props.row.condicion">
                                    <button type="button" class="dropdown-item" @click="actdesact(props.row.id, 0, extension.id)">
                                        <i class="fas fa-ban"></i>&nbsp; Desactivar
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="dropdown-item" @click="actdesact(props.row.id, 1, extension.id)">
                                        <i class="icon-check"></i>&nbsp; Activar
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
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
                            <input type="hidden" name="id">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="nombre">Extension</label>
                                     <div class="col-md-9">
                                        <input type="text" v-validate="'required|numeric|max:6'" name="nombre" v-model="extension.nombre" class="form-control" placeholder="Introduce extensión telefónica" autocomplete="off" id="nombre">
                                      <span class="text-danger">{{ errors.first('nombre') }}</span>
                                    </div>
                                </div>
                                   <div class="form-group row">
                <label class="col-md-3 form-control-label" for="departamento_id">Depto</label>
                <div class="col-md-9">
                  <select class="form-control" id="departamento_id"  name="departamento_id" v-model="extension.departamento_id" v-validate="'excluded:0'" data-vv-as="Solicitante" >
                    <option v-for="item in listadeptos" :value="item.id" :key="item.id">{{ item.nombre}} </option>
                  </select>
                  <span class="text-danger">{{ errors.first('departamento_id') }}</span>
                </div>
              </div>
                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarExtensiones(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarExtensiones(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
              url: '/extension',
                extension: {
                    id: 0,
                    nombre : '',
                    departamento_id: 0,
                    // departamentos : '',
                    condicion :0,
                },
                listadeptos: [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                //aqui van  las columnas a mostrar
                columns: ['id', 'nombre','departamentos', 'condicion'],
                tableData: [],
                columnsDepartamentos:[
                    'id',
                    'nombre',
                    'condicion'
                ],
                options: {
                    headings: {
                        nombre: 'Ext. Telefónica',
                        id: 'Acciones',
                        condicion: 'Condición',
                    },
                    perPage: 5,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['nombre'],
                    filterable: ['nombre'],
                    filterByColumn: true,
                    listColumns: {
                        condicion: [{
                            id: 1,
                            text: 'Activo'
                        },
                        {
                            id: 0,
                            text: 'Baja'
                        }
                        ]
                    },
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
                    
               


                });//aqui llamas  la tabla q necesitas trabajar con sus datos
                  axios.get('/departamento').then(response => {
                    me.listadeptos = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            guardarExtensiones(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                          method: nuevo ? 'POST' : 'PUT',
                          url: nuevo ? me.url : me.url+'/'+this.extension.id,

                            data: {
                                'id': this.extension.id,
                                'nombre': this.extension.nombre,
                                'departamento_id':this.extension.departamento_id

                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                if(!nuevo){
                                toastr.success('Extension Actualizada Correctamente');
                                }
                                else
                                {
                                toastr.success('Extension Registrada Correctamente');

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

 actdesact(id,activar,idb){
                if(activar){
                    this.swal_titulo = 'Esta seguro de activar esta extensión?';
                    this.swal_tle = 'Activado';
                    this.swal_msg = 'El registro ha sido activado con éxito.';
                }else{
                    this.swal_titulo = 'Esta seguro de desactivar esta extensión?';
                    this.swal_tle = 'Desactivado!';
                    this.swal_msg = 'El registro ha sido desactivado con éxito.';
                }
                swal({
                title: this.swal_titulo,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;
                    axios.get(me.url+'/'+id+'/edit').then(function (response) {
                        if(activar){
                        toastr.success(me.swal_tle,me.swal_msg,'success');

                        }else{
                        toastr.error(me.swal_tle,me.swal_msg,'error');

                        }
                        toastr.options = {
                        "closeButton": false,
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": true,
                        "progressBar": true,
                        "positionClass": "toast-top-center",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                       }
                        var data = []
                        data['id'] = idb;

                        me.getData();
                        departamentos = '';
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    result.dismiss === swal.DismissReason.cancel
                ) {

                }
                })
            },






            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                Utilerias.resetObject(this.extension);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "extensions":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Extension';
                                Utilerias.resetObject(this.extension);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar Extension';
                                this.tipoAccion=2;
                                this.extension.id=data['id'];
                                this.extension.nombre = data['nombre'];
                                this.extension.departamento_id= data ['departamento_id']
                                break;
                            }
                        }
                    }
                }
            }
        },
    cargarextension(dataExtension = []) {

                this.detalle = true;
                this.isLoadingDetalle = true;
                let me=this;
               this.empleado = dataExtension;
                axios.get(me.url + '/' + dataExtension.id + '/' +'extension').then(response => {
                    me.tableDatareferencia = response.data;
                    me.isLoadingDetalle = false;
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },


        mounted() {
            this.getData();
        }
    }
</script>
