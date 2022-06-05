<template>
            <main>
                <button type="button" @click="abrirModal('referencia','registrar', empleado.id)" class="btn btn-dark float-sm-right">
                    <i class="fas fa-plus"></i>&nbsp;Nuevo
                </button>

                <vue-element-loading :active="isLoadingDetalle"/>
                <v-client-table :columns="columnsreferencia" :data="tableDatareferencia" :options="optionsreferencia" ref="myTablereferencia">

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
                    <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <button type="button" @click="abrirModal('referencia','actualizar',empleado.id,props.row)" class="dropdown-item">
                            <i class="icon-pencil"></i>&nbsp;Actualizar Referencia
                        </button>
                <template v-if="props.row.condicion">
                            <button type="button" class="dropdown-item" @click="actdesact(props.row.id, 0, empleado.id)">
                                <i class="fas fa-ban"></i>&nbsp; Desactivar
                            </button>
                        </template>
                        <template v-else>
                            <button type="button" class="btn btn-info btn-sm" @click="actdesact(props.row.id, 1, empleado.id)">
                                <i class="icon-check"></i>&nbsp; Activar
                            </button>
                        </template>
                </div>
                </div>
                </div>
                        
                </template>     
                </v-client-table>
                 
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
                            <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal"> -->
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="nombre">Nombre de la Referencia</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required'" name="nombre" v-model="referencia.nombre" class="form-control" placeholder="Nombre Completo de la Referencia" autocomplete="off" id="nombre">
                                        <span class="text-danger">{{ errors.first('nombre') }}</span>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="telefono">Teléfono (10 dígitos)</label>
                                    <div class="col-md-9">
                                        <input type="text"  v-model="referencia.telefono" class="form-control" placeholder="Telefono Celular" autocomplete="off" id="telefono">
                                        <span class="text-danger">{{ errors.first('telefono') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="ocupacion">Ocupación</label>
                                    <div class="col-md-9">
                                        <input type="text" name="ocupacion" v-model="referencia.ocupacion" class="form-control" placeholder="Ocupación" autocomplete="off" id="ocupacion">
                                        <span class="text-danger">{{ errors.first('ocupacion') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="direccion">Dirección</label>
                                    <div class="col-md-9">
                                        <input type="text" name="direccion" v-model="referencia.direccion" class="form-control" placeholder="Dirección" autocomplete="off" id="direccion">
                                        <span class="text-danger">{{ errors.first('direccion') }}</span>
                                    </div>
                                </div>

                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <vue-element-loading :active="isLoading"/>
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarreferencia(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secpndary" @click="guardarreferencia(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
                
                url: '/referencia',
                empleado: null,
                referencia: {
                    id: 0,
                    telefono : 0,
                    ocupacion: '',
                    direccion: '',
                    nombre : '',
                    condicion: 0,
                    empleado_id: 0,
                  
                },
                listareferencia: [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                disable: 0,
                isLoading: false,
                isLoadingDetalle: false,
                columnsreferencia: [
                    'id',
                    'nombre',
                    'telefono',
                    'ocupacion',
                    'direccion',
                    'condicion'
                    ],
                tableDatareferencia: [],
                optionsreferencia: {
                    headings: {
                        'id': 'Acciones',
                        'nombre': 'Nombre de la Referencia',
                        'telefono': 'Teléfono',
                        'ocupacion': 'Ocupación',
                        'direccion': 'Dirección',
                        'condicion': 'Condición',
                       
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['nombre', 'empleado', 'condicion'],
                    filterable: ['nombre', 'empleado', 'condicion'],
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
            guardarreferencia(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? me.url+'/'+this.empleado.id+'/referencia' : me.url+'/'+this.referencia.id+'/'+'referencia/'+this.empleado.id,
                            data: {
                                'nombre': this.referencia.nombre,
                                'telefono': this.referencia.telefono,
                                'ocupacion': this.referencia.ocupacion,
                                'direccion': this.referencia.direccion,
                                'empleado_id': this.empleado.id,
                                'id': this.referencia.id
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.cargarreferencia(me.empleado);
                                if(!nuevo){
                                toastr.success('Referencia Actualizada Correctamente');
                                }
                                else
                                {
                                toastr.success('Referencia Registrada Correctamente');

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
                    this.swal_titulo = 'Esta seguro de activar esta referencia?';
                    this.swal_tle = 'Activado';
                    this.swal_msg = 'El registro ha sido activado con éxito.';
                }else{
                    this.swal_titulo = 'Esta seguro de desactivar esta referencia?';
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

                        me.cargarreferencia(data);
                        empleado = '';
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
                Utilerias.resetObject(this.referencia);
            },
            abrirModal(modelo, accion,ide,data = []){
                switch(modelo){
                    case "referencia":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Referencia';

                                Utilerias.resetObject(this.referencia);
                                this.tipoAccion = 1;
                                this.referencia.empleado_id = ide;
                                this.disable=0;
                                break;
                            }
                            case 'actualizar':
                            {
                                Utilerias.resetObject(this.referencia);
                                this.modal=1;
                                this.tituloModal='Actualizar Referencia';
                                this.tipoAccion=2;
                                this.disable=1;
                                this.referencia.id=data['id'];
                                this.referencia.nombre = data['nombre'];
                                this.referencia.telefono=data['telefono'];
                                this.referencia.ocupacion=data['ocupacion'];
                                this.referencia.direccion=data['direccion'];
                                this.referencia.empleado_id=data['empleado_id'];
                                this.disable=0;
                                break;
                            
                        }
                    }
                }
            }
        },
        cargarreferencia(dataEmpleado = []) {
                this.isLoadingDetalle = true;
                let me=this;
               this.empleado = dataEmpleado;
                axios.get(me.url + '/' + dataEmpleado.id + '/' +'referencia').then(response => {
                    me.tableDatareferencia = response.data;
                    me.isLoadingDetalle = false;
                })
                .catch(function (error) {
                    console.log(error);
                });    
            }
        },
        mounted() {
        }
    }
</script>