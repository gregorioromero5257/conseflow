<template>
    <div class="card-body">
        <vue-element-loading :active="isLoading"/>
        <div v-if="!detallePartidas">
        <v-client-table ref="myTable" :columns="columns" :data="tableData" :options="options">
            <template slot="id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group dropup" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <button type="button"  @click.prevent="abrirModal('actualizar',props.row)" class="dropdown-item">
                    <i class="icon-pencil"></i>&nbsp; Editar
                </button>
                 <button type="button"  @click.prevent="cargarPartidas(props.row)" class="dropdown-item">
                    <i class="fa fa-align-justify"></i>&nbsp; Partidas
                </button>                
            </div>
            </div>
            </div>
            </template>
        </v-client-table>
        </div>

        <!-- Form de partida -->
        <div v-if="detallePartidas">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-7">
                        Proveedor: {{ servicio.proveedor }}
                        <br>
                        Chofer: {{ servicio.chofer }}
                    </div>
                    <div class="col-md-3">
                        Fecha: {{ servicio.fecha }}
                        <br>
                        Total: <b>{{ servicio.total }}</b>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-block btn-default" @click="regresarServicios">
                            <i class="fas fa-arrow-left"></i>&nbsp; Regresar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form data-vv-scope="form-partida">
                <div class="row" >
                    <div class="col-md-3">
                        <label for="">Tipo de servicio</label>
                        <select class="form-control" id="tipo_servicio_id"  name="tipo_servicio_id" v-model="partida.tipo_servicio_id" v-validate="'excluded:0'" data-vv-as="Tipo de servicio">
                            <option value="0" selected>---</option>
                            <option v-for="item in listaTiposServicios" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                        </select>
                        <span class="text-danger">{{ errors.first('form-partida.tipo_servicio_id') }}</span>
                    </div>
                    <div class="col-md-4">
                        <label for="">Descripción</label>
                        <input type="text" name="descripcion" id="descripcion" class="form-control" v-validate="'required'" maxlength="250" v-model="partida.descripcion" data-vv-as="Descripción">
                        <span class="text-danger">{{ errors.first('form-partida.descripcion') }}</span>
                    </div>
                    <div class="col-md-3">
                        <label for="">Precio</label>
                        <input type="number" name="precio" id="precio" class="form-control" v-validate="'required'" lang="en" step="0.01" min="0" v-model="partida.precio" data-vv-as="Precio">
                        <span class="text-danger">{{ errors.first('form-partida.precio') }}</span>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-block btn-default" @click="cancelarPartida"><i class="fas fa-close"></i>&nbsp; Cancelar</button>
                        <button type="button" class="btn btn-block btn-primary" @click="guardarPartida"><i class="fas fa-check"></i>&nbsp; {{ accionPartida==1 ? 'Agregar' : 'Actualizar'}}</button>
                    </div>
                </div>
                </form>
            </div>
        </div>

        <v-client-table ref="myTable" :columns="columnsPartidas" :data="tableDataPartidas" :options="optionsPartidas">
            <template slot="id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group dropup" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <button type="button"  @click.prevent="editarPartida(props.row)" class="dropdown-item">
                    <i class="icon-pencil"></i>&nbsp; Editar
                </button>
                 <button type="button"  @click.prevent="eliminarPartida(props.row)" class="dropdown-item">
                    <i class="fa fa-trash"></i>&nbsp; Eliminar
                </button>                
            </div>
            </div>
            </div>
            </template>
        </v-client-table>
        </div>
        <!-- Fin Form partida -->

        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            
            <div class="modal-dialog modal-dark modal-lg" role="document">
                <div class="modal-content">
                    <div>
                        <vue-element-loading :active="isLoadingModal"/>
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button"  class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" data-vv-scope="form-servicio">
                        <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
                        <input type="hidden" name="id">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="chofer_id">Chofer</label>
                            <div class="col-md-9">
                                <select class="form-control" id="chofer_id"  name="chofer_id" v-model="servicio.chofer_id"  data-vv-as="Chofer" v-validate="'excluded:0'" >
                                    <option value="0" selected>---</option>
                                    <option v-for="item in listaChoferes" :value="item.id" :key="item.id">{{ item.empleado }} {{ item.ap_paterno }} {{ item.ap_materno }}</option>
                                </select>
                            <span class="text-danger">{{ errors.first('chofer_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="proveedor">Proveedor</label>
                            <div class="col-md-9">
                                <input type="text" v-validate="'required'" name="proveedor" v-model="servicio.proveedor" class="form-control" placeholder="Proveedor" autocomplete="off" id="proveedor" data-vv-as="Proveedor">
                                <span class="text-danger">{{ errors.first('proveedor') }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="fecha">Fecha</label>
                            <div class="col-md-9">
                                <input type="date" v-validate="'required'" name="fecha" v-model="servicio.fecha" class="form-control" placeholder="fecha" autocomplete="off" id="fecha" data-vv-as="Fecha">
                                <span class="text-danger">{{ errors.first('fecha') }}</span>
                            </div>
                        </div>
                        <!-- </form> -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarServicio(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarServicio(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
                    </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->


    </div>
</template>
<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

export default {
    data (){
        return {
            detallePartidas: false,
            isLoadingModal: false,
            modal: false,
            tipoAccion: 0,
            tituloModal: '',
            unidad_id: 0,
            servicio: {
                id: 0,
                proveedor: '',
                fecha: '',
                total: 0,
                unidad_id: 0,
                chofer_id: 0
            },
            isLoading: false,
            listaChoferes: [],
            tableData: [],
            columns: [
                'id','proveedor', 'fecha', 'total', 'chofer'
            ],
            options: {
                headings: {
                    'id': 'Acciones',
                    'proveedor': 'Proveedor',
                    'fecha': 'Fecha',
                    'total': 'Total',
                    'chofer': 'Chofer'
                },
                perPage: 5,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: [
                    'proveedor',
                    'fecha',
                    'total',
                    'chofer'
                    ],
                filterable: [
                    'proveedor',
                    'fecha',
                    'total',
                    'chofer'
                ],
                filterByColumn: true,
                listColumns: { },
                texts:config.texts
            },
            listaTiposServicios: [],
            tableDataPartidas: [],
            columnsPartidas: [
                'id','tipo', 'descripcion', 'precio'
            ],
            optionsPartidas: {
                headings: {
                    'id': 'Acciones',
                    'tipo': 'Tipo',
                    'descripcion': 'Descripción',
                    'precio': 'Precio',
                },
                perPage: 5,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: [
                    'descripcion',
                    'tipo',
                    'precio'
                    ],
                filterable: [
                    'descripcion',
                    'tipo',
                    'precio'
                ],
                filterByColumn: true,
                listColumns: { },
                texts:config.texts
            },
            partida: {
                id: 0,
                tipo_servicio_id: 0,
                descripcion: '',
                precio: 0
            },
            accionPartida: 0,
        }
    },
    methods: {
        cargarServicios(data) {
            console.log(data);
            this.unidad_id = data;
            this.isLoading = true;
            let me=this;
            axios.get('/serviciotrafico/' + this.unidad_id).then(response => {
                me.tableData = response.data;
                me.isLoading = false;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        agregar() {
            this.abrirModal('registrar');
        },
        getData() {
            let me=this;
            axios.get('/ConductoresStore/').then(response => {
                me.listaChoferes = response.data;
            })
            .catch(function (error) {
                console.log(error);
            });
            axios.get('/tiposerviciotrafico').then(response => {
                me.listaTiposServicios = response.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        cerrarModal(){
            this.modal=0;
            this.tituloModal='';
            Utilerias.resetObject(this.servicio);
        },
        abrirModal(accion, data = []){
            switch(accion){
                case 'registrar':
                {
                    this.modal = 1;
                    this.tituloModal = 'Registrar servicio';
                    Utilerias.resetObject(this.servicio);
                    this.servicio.unidad_id = this.unidad_id;
                    this.tipoAccion = 1;
                    break;
                }
                case 'actualizar':
                {
                    this.modal=1;
                    this.tituloModal='Actualizar servcio';
                    this.tipoAccion=2;
                    this.servicio.id=data['id'];
                    this.servicio.proveedor = data['proveedor'];
                    this.servicio.unidad_id = data['unidad_id'];
                    this.servicio.chofer_id = data['chofer_id'];
                    this.servicio.fecha = data['fecha'];
                    break;
                }
            }
        },
        guardarServicio(nuevo){
            this.$validator.validateAll('form-servicio').then(result => {
                if (result) {
                    this.isLoadingModal = true;
                    let me = this;
                    axios({
                        method: nuevo ? 'POST' : 'PUT',
                        url: nuevo ? '/serviciotrafico/registrar' : '/serviciotrafico/actualizar',
                        data: {
                            'proveedor': this.servicio.proveedor,
                            'id': this.servicio.id,
                            'unidad_id': this.servicio.unidad_id,
                            'chofer_id': this.servicio.chofer_id,
                            'fecha': this.servicio.fecha,
                        }
                    }).then(function (response) {
                        me.isLoadingModal = false;
                        if (response.data.status) {
                            me.cerrarModal();
                            me.cargarServicios(me.unidad_id);
                            if(!nuevo){
                            toastr.success('Servicio Actualizado Correctamente');
                            }
                            else
                            {
                            toastr.success('Servicio Registrado Correctamente');
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
        cargarPartidas(data) {
            this.detallePartidas = true;
            this.servicio = data;
            this.isLoading = true;
            let me=this;
            this.accionPartida = 1; 
            axios.get('/parservtrafico/' + this.servicio.id).then(response => {
                me.tableDataPartidas = response.data;
                me.isLoading = false;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        guardarPartida() {
            this.$validator.validateAll('form-partida').then(result => {
                if (result) {
                    this.isLoading = true;
                    let me = this;
                    axios({
                        method: this.accionPartida == 1 ? 'POST' : 'PUT',
                        url: this.accionPartida == 1 ? '/parservtrafico/registrar' : '/parservtrafico/actualizar',
                        data: {
                            'id': this.partida.id,
                            'descripcion': this.partida.descripcion,
                            'tipo_servicio_id': this.partida.tipo_servicio_id,
                            'precio': this.partida.precio,
                            'servicio_trafico_id': this.servicio.id
                        }
                    }).then(function (response) {
                        me.isLoading = false;
                        if (response.data.status) {
                            Utilerias.resetObject(me.partida);
                            me.servicio.total = response.data.total;
                            if(me.accionPartida == 1){
                                toastr.success('Partida Registrado Correctamente');
                            } else{
                                toastr.success('Partida Actualizado Correctamente');
                            }
                            me.cargarPartidas(me.servicio);
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
        cancelarPartida() {
            Utilerias.resetObject(this.partida);
            this.accionPartida = 1;
        },
        editarPartida(data) {
            this.partida = data;
            this.accionPartida = 2;
        },
        eliminarPartida(data) {
            let me = this;
            swal({
            title: "¿Estas seguro de eliminar la partida?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar!',
            cancelButtonText: 'Cancelar',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false,
            reverseButtons: true
            })
            .then((result) => {
                if (result.value) {
                    me.isLoading = true;
                    axios({
                        method: 'POST',
                        url: '/parservtrafico/borrar',
                        data: {
                            'id': data.id,
                            'servicio_trafico_id': this.servicio.id
                        }
                    }).then(function (response) {
                        me.isLoading = false;
                        if (response.data.status) {
                            Utilerias.resetObject(me.partida);
                            me.servicio.total = response.data.total;
                            toastr.success('Partida Eliminada Correctamente');
                            me.cargarPartidas(me.servicio);
                        } else {
                            swal({
                                type: 'error',
                                html: response.data.errors.join('<br>'),
                            });
                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    result.dismiss === swal.DismissReason.cancel
                ) {
                } 
            });
        },
        regresarServicios() {
            this.detallePartidas = false;
        }
    },
    mounted() {
        this.getData();
    }
}
</script>
